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
        $bookings = \App\Models\Peminjaman::with(['ruangan'])
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
            'bukti_pembayaran.image' => 'File harus berupa gambar.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB.',
        ]);

        $peminjaman = Peminjaman::where('user_id', Auth::id())->findOrFail($id);

        if ($request->hasFile('bukti_pembayaran')) {
            // Delete old file if exists
            if ($peminjaman->bukti_pembayaran) {
                Storage::disk('public')->delete($peminjaman->bukti_pembayaran);
            }

            $path = $request->file('bukti_pembayaran')->store('payments', 'public');
            
            $peminjaman->bukti_pembayaran = $path;
            $peminjaman->status_pembayaran = 'pending_verification';
            $peminjaman->save();

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
        if ($peminjaman->bukti_pembayaran) {
            Storage::disk('public')->delete($peminjaman->bukti_pembayaran);
        }

        $peminjaman->delete();

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
