<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ruangan;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $rooms = getMockRooms();
        $bookings = [
            [
                'id' => 'BK-9021',
                'user' => 'Clara Amanda',
                'room' => 'Emerald Executive Boardroom',
                'date' => 'Selasa, 19 Mei 2026',
                'time' => '08:00 - 10:00 (2 jam)',
                'purpose' => 'Rapat Koordinasi Evaluasi Bulanan',
                'price' => 330000,
                'status' => 'Disetujui'
            ],
            [
                'id' => 'BK-9022',
                'user' => 'Luthfi Kurnia',
                'room' => 'Creative Hub & Jam Space',
                'date' => 'Rabu, 20 Mei 2026',
                'time' => '14:00 - 16:00 (2 jam)',
                'purpose' => 'Sesi Brainstorming Produk Kreatif',
                'price' => 209000,
                'status' => 'Menunggu Konfirmasi'
            ],
            [
                'id' => 'BK-9023',
                'user' => 'Dewi Kartika',
                'room' => 'Synergy Seminar Hall',
                'date' => 'Kamis, 21 Mei 2026',
                'time' => '08:00 - 12:00 (4 jam)',
                'purpose' => 'Pelatihan Staf Operasional Baru',
                'price' => 1232000,
                'status' => 'Menunggu Konfirmasi'
            ],
            [
                'id' => 'BK-9024',
                'user' => 'Ahmad Fauzi',
                'room' => 'Huddle Pod Room 4A',
                'date' => 'Jumat, 22 Mei 2026',
                'time' => '16:00 - 17:00 (1 jam)',
                'purpose' => 'Wawancara Rekrutmen Tim Dev',
                'price' => 55000,
                'status' => 'Dibatalkan'
            ],
        ];

        // Stats
        $stats = [
            'total_rooms' => count($rooms),
            'total_bookings' => 38,
            'rented_hours' => 124,
            'revenue' => 4580000,
        ];

        $ruanganCount = Ruangan::count();
        return view('admin.dashboard', [
            'bookings' => $bookings,
            'stats' => $stats,
            'ruanganCount' => $ruanganCount
        ]);
    }
}
