<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    public function index()
    {
        $bookings = \App\Models\Peminjaman::with(['ruangan', 'pembayaran', 'paket'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.dashboard', compact('bookings'));
    }

    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'bukti_pembayaran.required' => 'Bukti pembayaran wajib diunggah.',
            'bukti_pembayaran.image'    => 'File harus berupa gambar.',
            'bukti_pembayaran.max'      => 'Ukuran file maksimal 2MB.',
        ]);

        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);
        $pembayaran = $peminjaman->pembayaran;

        if (!$pembayaran) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('payments', 'public');

            $pembayaran->bukti_pembayaran  = $path;
            $pembayaran->status_pembayaran = 'pending_verification';
            $pembayaran->save();

            return back()->with('success', 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.');
        }

        return back()->with('error', 'Gagal mengunggah bukti pembayaran.');
    }

    public function cancelBooking($id)
    {
        $peminjaman = \App\Models\Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($peminjaman->status === 'approved') {
            return back()->with('error', 'Pesanan yang sudah disetujui tidak dapat dibatalkan.');
        }
        
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        // Hapus bukti pembayaran jika ada
        if ($peminjaman->pembayaran && $peminjaman->pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($peminjaman->pembayaran->bukti_pembayaran);
        }

        $peminjaman->delete();

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function paymentSuccessLocal($id)
    {
        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);
        $pembayaran = $peminjaman->pembayaran;

        if ($pembayaran && $pembayaran->status_pembayaran !== 'verified') {
            $pembayaran->status_pembayaran = 'verified';
            $pembayaran->save();

            \App\Models\Notification::create([
                'user_id'       => $peminjaman->user_id,
                'peminjaman_id' => $peminjaman->id,
                'type'          => 'payment_success',
                'message'       => 'Pembayaran untuk pesanan Anda berhasil diverifikasi. Silakan tunggu konfirmasi selanjutnya.',
                'is_read'       => false,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
