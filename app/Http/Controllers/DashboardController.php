<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;

class DashboardController extends Controller
{
    public function index()
    {
        $rooms = getMockRooms();
        $bookings = \App\Models\Peminjaman::with(['user', 'ruangan', 'detailFasilitas.fasilitas'])->latest()->get();

        // Stats
        $stats = [
            'total_rooms' => count($rooms),
            'total_bookings' => \App\Models\Peminjaman::count(),
            'rented_hours' => 124, // Dummy for now
            'revenue' => 4580000, // Dummy for now
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

        $peminjaman = \App\Models\Peminjaman::findOrFail($id);
        $peminjaman->status = $request->status;
        $peminjaman->save();

        return response()->json([
            'success' => true,
            'message' => 'Status peminjaman berhasil diperbarui'
        ]);
    }
}
