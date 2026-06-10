<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pembayaran;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Simpan data pemesanan ruangan baru ke database.
     */
    public function store(Request $request, $ruangan_id)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'waktu_mulai'     => 'required',
            'waktu_selesai'   => 'required|after:waktu_mulai',
            'jumlah_peserta'  => 'required|integer|min:1',
            'tujuan_rapat'    => 'required|string|max:500',
            'paket_id'        => 'nullable|exists:pakets,id',
            'dokumen_pendukung' => \Illuminate\Support\Facades\Auth::user()->instansi === 'internal' ? 'required|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120' : 'nullable|file|mimes:pdf,doc,docx,jpg,png,jpeg|max:5120',
        ], [
            'tanggal_mulai.required'  => 'Tanggal peminjaman wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal tidak boleh lebih awal dari hari ini.',
            'waktu_mulai.required'    => 'Waktu mulai wajib dipilih.',
            'waktu_selesai.required'  => 'Waktu selesai wajib dipilih.',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.min'      => 'Jumlah peserta minimal 1 orang.',
            'tujuan_rapat.required'   => 'Tujuan rapat wajib diisi.',
            'dokumen_pendukung.required' => 'Dokumen pendukung wajib diunggah untuk peminjam internal.',
            'dokumen_pendukung.file'  => 'Dokumen pendukung harus berupa file.',
            'dokumen_pendukung.mimes' => 'Format dokumen harus pdf, doc, docx, jpg, png, atau jpeg.',
            'dokumen_pendukung.max'   => 'Ukuran maksimal dokumen adalah 5MB.',
        ]);

        $isExternal = Auth::user()->instansi === 'umum';

        // Calculate Price
        $total_harga = null;
        $snap_token  = null;

        if ($isExternal) {
            // Get room price directly from the database
            $ruangan = Ruangan::find($ruangan_id);
            $roomPricePerHour = $ruangan ? $ruangan->harga_per_jam : 0;

            if ($request->filled('paket_id')) {
                $paket    = \App\Models\Paket::find($request->paket_id);
                $rawPrice = $paket ? $paket->harga_paket : 0;
            } else {
                $start = \Carbon\Carbon::parse($request->waktu_mulai);
                $end   = \Carbon\Carbon::parse($request->waktu_selesai);

                // If end time is before start time (e.g. next day), add a day.
                if ($end->lessThan($start)) {
                    $end->addDay();
                }

                $durationHours = $start->diffInHours($end);
                if ($durationHours == 0) $durationHours = 1; // Minimum 1 hour

                $rawPrice = $roomPricePerHour * $durationHours;
            }

            $serviceTax  = round($rawPrice * 0.1);
            $total_harga = $rawPrice + $serviceTax;
        }

        // Simpan data peminjaman (tanpa field pembayaran)
        $peminjaman = Peminjaman::create([
            'ruangan_id'     => $ruangan_id,
            'paket_id'       => $request->paket_id,
            'user_id'        => Auth::id(),
            'tanggal_mulai'  => $request->tanggal_mulai,
            'waktu_mulai'    => $request->waktu_mulai,
            'waktu_selesai'  => $request->waktu_selesai,
            'status'         => 'pending',
            'jumlah_peserta' => $request->jumlah_peserta,
            'tujuan_rapat'   => $request->tujuan_rapat,
        ]);

        // Buat record pembayaran terpisah
        $pembayaran = Pembayaran::create([
            'peminjaman_id'    => $peminjaman->id,
            'total_harga'      => $total_harga,
            'snap_token'       => null,
            'bukti_pembayaran' => null,
            'status_pembayaran' => $isExternal ? 'unpaid' : 'verified',
        ]);

        if (!$isExternal && $request->hasFile('dokumen_pendukung')) {
            $file = $request->file('dokumen_pendukung');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('dokumen_pendukung', $filename, 'public');

            \App\Models\DokumenPendukung::create([
                'peminjaman_id' => $peminjaman->id,
                'file_dokumen' => $filename,
            ]);
        }

        if ($isExternal && $total_harga > 0) {
            // Midtrans Configuration
            \Midtrans\Config::$serverKey     = config('midtrans.server_key');
            \Midtrans\Config::$isProduction  = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized   = config('midtrans.is_sanitized');
            \Midtrans\Config::$is3ds         = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id'     => 'BOOK-' . $peminjaman->id . '-' . time(),
                    'gross_amount' => $total_harga,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->nama_lengkap,
                    'email'      => Auth::user()->email,
                    'phone'      => Auth::user()->nomor_telepon,
                ],
            ];

            try {
                $snap_token = \Midtrans\Snap::getSnapToken($params);
                $pembayaran->update(['snap_token' => $snap_token]);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Midtrans Error: ' . $e->getMessage());
            }

            \App\Models\Notification::create([
                'user_id'       => Auth::id(),
                'peminjaman_id' => $peminjaman->id,
                'type'          => 'payment_required',
                'message'       => 'Pemesanan Anda berhasil dibuat! Silakan lakukan pembayaran di dashboard agar pemesanan dapat segera diproses.',
                'is_read'       => false,
            ]);
        }

        if ($request->has('fasilitas')) {
            foreach ($request->fasilitas as $fasilitas_id => $qty) {
                if ($qty > 0) {
                    \App\Models\DetailPeminjamanFasilitas::create([
                        'peminjaman_id' => $peminjaman->id,
                        'fasilitas_id'  => $fasilitas_id,
                        'stok_tersedia' => $qty,
                    ]);
                }
            }
        }

        if ($isExternal) {
            return redirect('/user/dashboard')
                ->with('booking_success', true)
                ->with('message', 'Silakan selesaikan pembayaran untuk melanjutkan proses pemesanan.');
        }

        return redirect("/room/{$ruangan_id}")
            ->with('booking_success', true)
            ->with('booking_room', $request->booking_room_name ?? '')
            ->with('booking_date', $request->tanggal_mulai)
            ->with('booking_time', $request->waktu_mulai . ' - ' . $request->waktu_selesai)
            ->with('booking_purpose', $request->tujuan_rapat);
    }
}
