<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\FotoRuanganController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\DetailPaketController;

function getRooms() {
    $dbRooms = \App\Models\Ruangan::with('fotoRuangan')->get();
    $rooms = [];
    foreach ($dbRooms as $room) {
        $images = $room->fotoRuangan->pluck('foto')->map(function ($foto) {
            return asset('storage/' . $foto);
        })->toArray();

        if (empty($images)) {
            $images = [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=600&auto=format&fit=crop'
            ];
        }

        $rooms[$room->id] = [
            'id' => $room->id,
            'name' => $room->nama_ruangan,
            'type' => $room->tipe_ruangan ?? 'Meeting Room',
            'capacity' => $room->kapasitas,
            'price' => $room->harga_per_jam,
            'rating' => 4.9,
            'reviews' => 42,
            'size' => ($room->kapasitas * 2) . 'm²',
            'floor' => $room->lokasi_ruangan,
            'short_desc' => \Illuminate\Support\Str::limit($room->deskripsi, 100),
            'description' => $room->deskripsi,
            'amenities' => [
                'High-speed Wi-Fi',
                'Smart TV / Projector',
                'Air Mineral & Kopi/Teh',
                'AC & Colokan Listrik'
            ],
            'images' => $images,
            'calendar' => [
                '08:00' => 'tersedia',
                '09:00' => 'tersedia',
                '10:00' => 'tersedia',
                '11:00' => 'tersedia',
                '12:00' => 'tersedia',
                '13:00' => 'tersedia',
                '14:00' => 'tersedia',
                '15:00' => 'tersedia',
                '16:00' => 'tersedia',
                '17:00' => 'tersedia',
            ]
        ];
    }
    return $rooms;
}

Route::post('/api/payment/midtrans-callback', [\App\Http\Controllers\PaymentCallbackController::class, 'callback'])->name('api.payment.midtrans-callback');

Route::get('/', function () {
    $rooms = getRooms();
    return view('landing', compact('rooms'));
});

Route::get('/room/{id}', function ($id) {
    $rooms = getRooms();
    if (!isset($rooms[$id])) {
        abort(404, 'Ruang rapat tidak ditemukan');
    }
    $room = $rooms[$id];

    // Ambil booking yang sudah disetujui (approved/pending) untuk hari ini
    $today = \Carbon\Carbon::today()->toDateString();
    $bookedSlots = \App\Models\Peminjaman::where('ruangan_id', $id)
        ->whereIn('status', ['approved', 'pending'])
        ->where('tanggal_mulai', $today)
        ->get(['waktu_mulai', 'waktu_selesai'])
        ->map(fn($p) => [
            'mulai'   => substr($p->waktu_mulai, 0, 5),
            'selesai' => substr($p->waktu_selesai, 0, 5),
        ])
        ->values();

    return view('detail', compact('room', 'bookedSlots'));
});

// API endpoint: ambil booked slots berdasarkan ruangan_id + tanggal (AJAX)
Route::get('/api/booked-slots/{ruangan_id}', function ($ruangan_id, \Illuminate\Http\Request $request) {
    $date = $request->query('date', \Carbon\Carbon::today()->toDateString());

    $bookedSlots = \App\Models\Peminjaman::where('ruangan_id', $ruangan_id)
        ->whereIn('status', ['approved', 'pending'])
        ->where('tanggal_mulai', $date)
        ->get(['waktu_mulai', 'waktu_selesai'])
        ->map(fn($p) => [
            'mulai'   => substr($p->waktu_mulai, 0, 5),
            'selesai' => substr($p->waktu_selesai, 0, 5),
        ])
        ->values();

    return response()->json(['booked' => $bookedSlots]);
})->name('api.booked-slots');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [DashboardController::class, 'index']);
    Route::patch('/admin/peminjaman/{id}/status', [DashboardController::class, 'updateStatus'])->name('admin.peminjaman.status');

    Route::get('/admin/rooms', function () {
        $mockRooms = getRooms();
        $dbRooms = \App\Models\Ruangan::with(['peminjaman' => function($query) {
            $query->where('status', 'approved')
                  ->where(function($q) {
                      $q->where('tanggal_mulai', '>', now()->toDateString())
                        ->orWhere(function($q2) {
                            $q2->where('tanggal_mulai', now()->toDateString())
                               ->where('waktu_selesai', '>', now()->toTimeString());
                        });
                  })
                  ->orderBy('tanggal_mulai', 'asc')
                  ->orderBy('waktu_mulai', 'asc');
        }])->get();

        $rooms = [];
        foreach ($mockRooms as $id => $room) {
            $dbRoom = $dbRooms->firstWhere('id', $id);
            $room['status_tersedia'] = $dbRoom ? $dbRoom->status_tersedia : 'tersedia';
            
            $nextSchedule = null;
            if ($dbRoom && $dbRoom->peminjaman->count() > 0) {
                $next = $dbRoom->peminjaman->first();
                $nextSchedule = \Carbon\Carbon::parse($next->tanggal_mulai)->translatedFormat('d M') . ', ' . 
                                \Carbon\Carbon::parse($next->waktu_mulai)->format('H:i') . '-' . 
                                \Carbon\Carbon::parse($next->waktu_selesai)->format('H:i');
                
                // Check if currently ongoing
                if ($next->tanggal_mulai == now()->toDateString() && 
                    $next->waktu_mulai <= now()->toTimeString() && 
                    $next->waktu_selesai >= now()->toTimeString()) {
                    $room['status_tersedia'] = 'terpakai';
                }
            }
            $room['next_schedule'] = $nextSchedule ?? '-';
            $rooms[$id] = $room;
        }

        return view('admin.rooms', compact('rooms'));
    });

    Route::get('/admin/reviews', function () {
        $reviews = \App\Models\Peminjaman::with(['user', 'ruangan'])
            ->whereIn('status', ['approved', 'rejected', 'completed'])
            ->latest()
            ->get();
        return view('admin.booking-reviews', compact('reviews'));
    });

    Route::get('/admin/reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('/admin/reports/print', [ReportController::class, 'print'])->name('admin.reports.print');

    Route::get('/admin/fasilitas', [FasilitasController::class, 'index'])->name('admin.fasilitas.index');
    Route::get('/admin/fasilitas/create', [FasilitasController::class, 'create'])->name('admin.fasilitas.create');
    Route::post('/admin/fasilitas', [FasilitasController::class, 'store'])->name('admin.fasilitas.store');
    Route::get('/admin/fasilitas/{id}/edit', [FasilitasController::class, 'edit'])->name('admin.fasilitas.edit');
    Route::put('/admin/fasilitas/{id}', [FasilitasController::class, 'update'])->name('admin.fasilitas.update');
    Route::delete('/admin/fasilitas/{id}', [FasilitasController::class, 'destroy'])->name('admin.fasilitas.destroy');

    // Masterdata Ruangan CRUD
    Route::get('/admin/ruangan', [RuanganController::class, 'index'])->name('admin.ruangan.index');
    Route::get('/admin/ruangan/create', [RuanganController::class, 'create'])->name('admin.ruangan.create');
    Route::post('/admin/ruangan', [RuanganController::class, 'store'])->name('admin.ruangan.store');
    Route::get('/admin/ruangan/{id}/edit', [RuanganController::class, 'edit'])->name('admin.ruangan.edit');
    Route::put('/admin/ruangan/{id}', [RuanganController::class, 'update'])->name('admin.ruangan.update');
    Route::delete('/admin/ruangan/{id}', [RuanganController::class, 'destroy'])->name('admin.ruangan.destroy');

    // Masterdata Paket CRUD
    Route::get('/admin/paket', [PaketController::class, 'index'])->name('admin.paket.index');
    Route::get('/admin/paket/create', [PaketController::class, 'create'])->name('admin.paket.create');
    Route::post('/admin/paket', [PaketController::class, 'store'])->name('admin.paket.store');
    Route::get('/admin/paket/{id}/edit', [PaketController::class, 'edit'])->name('admin.paket.edit');
    Route::put('/admin/paket/{id}', [PaketController::class, 'update'])->name('admin.paket.update');
    Route::delete('/admin/paket/{id}', [PaketController::class, 'destroy'])->name('admin.paket.destroy');

    // Hapus foto ruangan individual
    Route::delete('/admin/foto-ruangan/{id}', [FotoRuanganController::class, 'destroy'])->name('admin.foto-ruangan.destroy');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Booking Route - Hanya untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::get('/booking', function () {
        $rooms = getRooms();
        $fasilitas = \App\Models\Fasilitas::where('stok_tersedia', '>', 0)->get();
        return view('booking', compact('rooms', 'fasilitas'));
    })->name('booking.index');
    
    Route::post('/booking/{ruangan_id}', [BookingController::class, 'store'])->name('booking.store');

    // User Dashboard Routes
    Route::get('/user/dashboard', [\App\Http\Controllers\UserDashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/dashboard/payment/{id}', [\App\Http\Controllers\UserDashboardController::class, 'uploadPayment'])->name('user.dashboard.payment');
    Route::delete('/user/dashboard/booking/{id}', [\App\Http\Controllers\UserDashboardController::class, 'cancelBooking'])->name('user.dashboard.booking.cancel');

    // Notification API Routes
    Route::get('/api/user/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/api/user/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/api/user/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.readAll');
});
