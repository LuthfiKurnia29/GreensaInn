<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Models\Peminjaman;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = \App\Models\Ruangan::All();
        $bookings = \App\Models\Peminjaman::with(['user', 'ruangan', 'detailFasilitas.fasilitas', 'pembayaran', 'paket'])->latest()->get();

        // Stats
        $stats = [
            'total_rooms' => count($rooms),
            'total_bookings' => \App\Models\Peminjaman::count(),
            'rented_hours' => 0, // Dummy for now
            'revenue' => 0, // Dummy for now
        ];

        $ruanganCount = Ruangan::count();
        return view('admin.dashboard', [
            'bookings' => $bookings,
            'stats' => $stats,
            'ruanganCount' => $ruanganCount
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected'
        ]);

        $peminjaman = \App\Models\Peminjaman::with(['ruangan', 'pembayaran'])->findOrFail($id);
        $peminjaman->status = $request->status;
        if ($request->status === 'approved' && $peminjaman->pembayaran && $peminjaman->pembayaran->status_pembayaran === 'pending_verification') {
            $peminjaman->pembayaran->status_pembayaran = 'verified';
            $peminjaman->pembayaran->save();
        }
        $peminjaman->save();

        // Buat notifikasi untuk user yang memesan
        $namaRuangan = $peminjaman->ruangan->nama_ruangan ?? 'Ruangan';
        $tanggal = \Carbon\Carbon::parse($peminjaman->tanggal_mulai)->translatedFormat('d M Y');

        if ($request->status === 'approved') {
            $message = "Pemesanan Anda untuk \"{$namaRuangan}\" pada {$tanggal} telah Disetujui! Silakan hadir tepat waktu.";
        } else {
            $message = "Pemesanan Anda untuk \"{$namaRuangan}\" pada {$tanggal} Ditolak oleh admin. Silakan hubungi kami untuk informasi lebih lanjut.";
        }

        Notification::create([
            'user_id'       => $peminjaman->user_id,
            'peminjaman_id' => $peminjaman->id,
            'type'          => $request->status,
            'message'       => $message,
            'is_read'       => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status peminjaman berhasil diperbarui'
        ]);
    }
}
