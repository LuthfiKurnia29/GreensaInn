<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
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
        ], [
            'tanggal_mulai.required'  => 'Tanggal peminjaman wajib diisi.',
            'tanggal_mulai.after_or_equal' => 'Tanggal tidak boleh lebih awal dari hari ini.',
            'waktu_mulai.required'    => 'Waktu mulai wajib dipilih.',
            'waktu_selesai.required'  => 'Waktu selesai wajib dipilih.',
            'waktu_selesai.after'     => 'Waktu selesai harus setelah waktu mulai.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.min'      => 'Jumlah peserta minimal 1 orang.',
            'tujuan_rapat.required'   => 'Tujuan rapat wajib diisi.',
        ]);

        $isExternal = Auth::user()->instansi === 'umum';

        $peminjaman = Peminjaman::create([
            'ruangan_id'        => $ruangan_id,
            'user_id'           => Auth::id(),
            'tanggal_mulai'     => $request->tanggal_mulai,
            'waktu_mulai'       => $request->waktu_mulai,
            'waktu_selesai'     => $request->waktu_selesai,
            'status'            => 'pending',
            'status_pembayaran' => $isExternal ? 'unpaid' : 'verified',
            'jumlah_peserta'    => $request->jumlah_peserta,
            'tujuan_rapat'      => $request->tujuan_rapat,
        ]);

        if ($isExternal) {
            \App\Models\Notification::create([
                'user_id'       => Auth::id(),
                'peminjaman_id' => $peminjaman->id,
                'type'          => 'payment_required',
                'message'       => 'Pemesanan Anda berhasil dibuat! Silakan unggah bukti pembayaran di dashboard agar pemesanan dapat segera diproses.',
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
                ->with('message', 'Silakan unggah bukti pembayaran untuk melanjutkan proses pemesanan.');
        }

        return redirect("/room/{$ruangan_id}")
            ->with('booking_success', true)
            ->with('booking_room', $request->booking_room_name ?? '')
            ->with('booking_date', $request->tanggal_mulai)
            ->with('booking_time', $request->waktu_mulai . ' - ' . $request->waktu_selesai)
            ->with('booking_purpose', $request->tujuan_rapat);
    }
}
