<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;

// Data Dummy Ruang Rapat
function getMockRooms() {
    return [
        1 => [
            'id' => 1,
            'name' => 'Emerald Executive Boardroom',
            'type' => 'Boardroom',
            'capacity' => 20,
            'price' => 150000,
            'rating' => 4.9,
            'reviews' => 42,
            'size' => '60m²',
            'floor' => 'Lantai 3',
            'short_desc' => 'Ruang rapat eksklusif dengan desain elegan dan kursi ergonomis mewah untuk keputusan penting bisnis Anda.',
            'description' => 'Emerald Executive Boardroom dirancang khusus untuk memfasilitasi rapat direksi, presentasi pemegang saham, dan negosiasi bisnis tingkat tinggi. Dengan sentuhan panel kayu premium, pencahayaan pintar yang dapat disesuaikan (dimmable), dan akustik ruangan superior, ruangan ini memberikan suasana formal, tenang, dan prestisius yang mendukung kenyamanan selama rapat berjam-jam.',
            'amenities' => [
                'High-speed Wi-Fi 200 Mbps',
                'Smart TV UHD 75"',
                'System Sound & Wireless Mic',
                'Papan Tulis Kaca (Glassboard)',
                'AC Central Kontrol Mandiri',
                'Sistem Konferensi Video Logitech Rally',
                'Air Mineral & Kopi/Teh Hangat',
                'Colokan Listrik & USB Universal di Meja'
            ],
            'images' => [
                'https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1497366811353-6870744d04b2?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=600&auto=format&fit=crop'
            ],
            'calendar' => [
                '08:00' => 'tersedia',
                '09:00' => 'tersedia',
                '10:00' => 'terisi',
                '11:00' => 'terisi',
                '12:00' => 'tersedia',
                '13:00' => 'tersedia',
                '14:00' => 'tersedia',
                '15:00' => 'terisi',
                '16:00' => 'tersedia',
                '17:00' => 'tersedia',
            ]
        ],
        2 => [
            'id' => 2,
            'name' => 'Creative Hub & Jam Space',
            'type' => 'Creative Space',
            'capacity' => 12,
            'price' => 95000,
            'rating' => 4.8,
            'reviews' => 28,
            'size' => '35m²',
            'floor' => 'Lantai 2',
            'short_desc' => 'Ruang kolaborasi dengan atmosfer kasual dan penuh warna untuk memicu ide-ide kreatif tim Anda.',
            'description' => 'Creative Hub adalah tempat sempurna untuk tim kreatif, start-up, atau sesi brainstorming pengembangan produk. Didesain dengan nuansa kontemporer industrial yang energetik, dilengkapi beanbag opsional, kursi warna-warni, serta dinding corat-coret yang luas untuk membantu menyalurkan kreativitas tanpa batas.',
            'amenities' => [
                'High-speed Wi-Fi 150 Mbps',
                'Interactive Smart Whiteboard 65"',
                'Proyektor Short-Throw Full HD',
                'Dinding Cat Tulis Kaca (Full Wall Blackboard)',
                'Pendingin Udara Split Dual Inverter',
                'Bluetooth Speaker Harman Kardon',
                'Pilihan Minuman Soda & Cemilan Ringan',
                'Universal Power Outlets'
            ],
            'images' => [
                'https://images.unsplash.com/photo-1517502884422-41eaaced0168?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1531538606174-0f90ff5dce83?q=80&w=600&auto=format&fit=crop'
            ],
            'calendar' => [
                '08:00' => 'tersedia',
                '09:00' => 'terisi',
                '10:00' => 'tersedia',
                '11:00' => 'tersedia',
                '12:00' => 'tersedia',
                '13:00' => 'tersedia',
                '14:00' => 'terisi',
                '15:00' => 'terisi',
                '16:00' => 'tersedia',
                '17:00' => 'tersedia',
            ]
        ],
        3 => [
            'id' => 3,
            'name' => 'Synergy Seminar Hall',
            'type' => 'Seminar Hall',
            'capacity' => 60,
            'price' => 280000,
            'rating' => 4.7,
            'reviews' => 54,
            'size' => '120m²',
            'floor' => 'Lantai 1',
            'short_desc' => 'Ruangan berskala besar dengan panggung kecil dan sound system lengkap untuk seminar, pelatihan, atau workshop.',
            'description' => 'Synergy Seminar Hall merupakan pilihan ideal untuk menyelenggarakan presentasi publik, pelatihan karyawan berskala besar, seminar regional, hingga konferensi pers. Tata letak kursi dapat dikonfigurasi ulang (Theater, Classroom, U-Shape, atau Banquet) menyesuaikan dengan kebutuhan acara Anda.',
            'amenities' => [
                'High-speed Wi-Fi 250 Mbps',
                'Dual Screen Projector 4K 150"',
                'Premium Sound System & 4 Wireless Mics',
                'Podium Pembicara dengan Mikrofon Gooseneck',
                'Panggung Kecil (Portable Stage)',
                'Asisten Rapat & Sound Engineer Standby',
                'Air Mineral Botol & Permen',
                'Area Registrasi & Reception Desk khusus'
            ],
            'images' => [
                'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?q=80&w=600&auto=format&fit=crop'
            ],
            'calendar' => [
                '08:00' => 'terisi',
                '09:00' => 'terisi',
                '10:00' => 'terisi',
                '11:00' => 'terisi',
                '12:00' => 'tersedia',
                '13:00' => 'tersedia',
                '14:00' => 'tersedia',
                '15:00' => 'tersedia',
                '16:00' => 'tersedia',
                '17:00' => 'tersedia',
            ]
        ],
        4 => [
            'id' => 4,
            'name' => 'Huddle Pod Room 4A',
            'type' => 'Huddle Room',
            'capacity' => 4,
            'price' => 50000,
            'rating' => 4.9,
            'reviews' => 19,
            'size' => '12m²',
            'floor' => 'Lantai 4',
            'short_desc' => 'Ruang diskusi kecil privat yang kedap suara, cocok untuk wawancara, meeting 1-on-1, atau panggilan video.',
            'description' => 'Huddle Pod Room menawarkan privasi maksimal dalam ruang compact. Dilengkapi dengan dinding kedap suara bertaraf studio (acoustic insulation), pencahayaan optimal untuk kamera web, dan meja melingkar yang nyaman untuk memfasilitasi rapat internal singkat maupun sesi interviu penting.',
            'amenities' => [
                'High-speed Wi-Fi 100 Mbps',
                'Smart Monitor 43" (HDMI/AirPlay/Chromecast)',
                'Dinding Kaca Kedap Suara',
                'Whiteboard Kecil',
                'AC Sunyi (Silent Split AC)',
                'Webcam HD Wide-Angle terintegrasi',
                'Air Mineral Gelas',
                'Colokan Listrik Tanam di Meja'
            ],
            'images' => [
                'https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1200&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1515187029135-18ee286d815b?q=80&w=600&auto=format&fit=crop'
            ],
            'calendar' => [
                '08:00' => 'tersedia',
                '09:00' => 'tersedia',
                '10:00' => 'tersedia',
                '11:00' => 'tersedia',
                '12:00' => 'terisi',
                '13:00' => 'terisi',
                '14:00' => 'tersedia',
                '15:00' => 'tersedia',
                '16:00' => 'terisi',
                '17:00' => 'tersedia',
            ]
        ],
    ];
}

Route::post('/api/payment/midtrans-callback', [\App\Http\Controllers\PaymentCallbackController::class, 'callback'])->name('api.payment.midtrans-callback');

Route::get('/', function () {
    $rooms = getMockRooms();
    return view('landing', compact('rooms'));
});

Route::get('/room/{id}', function ($id) {
    $rooms = getMockRooms();
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
        $mockRooms = getMockRooms();
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
        $rooms = getMockRooms();
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
