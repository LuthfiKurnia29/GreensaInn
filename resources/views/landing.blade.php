@extends('layouts.app')

@section('title', 'GreensaInn - Peminjaman Ruang Rapat Premium & Kolaboratif')

@section('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--primary-color) 0%, #051c22 100%);
        padding: 100px 0 140px 0;
        position: relative;
        color: white;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 80% 20%, rgba(251, 139, 36, 0.15) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-title {
        font-size: 3.5rem;
        line-height: 1.2;
        font-weight: 800;
        margin-bottom: 24px;
        color: white;
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 40px;
        line-height: 1.7;
    }

    /* Floating Search Bar */
    .search-panel {
        background: white;
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        margin-top: -60px;
        position: relative;
        z-index: 10;
        border: 1px solid rgba(15, 76, 92, 0.08);
    }

    .search-input-group {
        border-right: 1px solid #e9ecef;
        padding-right: 20px;
    }

    @media (max-width: 991.98px) {
        .search-input-group {
            border-right: none;
            border-bottom: 1px solid #e9ecef;
            padding-right: 0;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .hero-title {
            font-size: 2.5rem;
        }
    }

    /* Feature Highlights */
    .benefit-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 20px;
        transition: var(--transition-smooth);
    }

    .premium-card:hover .benefit-icon {
        background-color: var(--primary-color);
        color: white;
        transform: scale(1.1) rotate(5deg);
    }

    /* Room Cards */
    .room-img-container {
        position: relative;
        height: 220px;
        overflow: hidden;
    }

    .room-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }

    .premium-card:hover .room-img-container img {
        transform: scale(1.08);
    }

    .room-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: rgba(15, 76, 92, 0.9);
        backdrop-filter: blur(5px);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        z-index: 2;
    }

    .room-price-tag {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background-color: rgba(251, 139, 36, 0.95);
        backdrop-filter: blur(5px);
        color: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.9rem;
        font-weight: 700;
        z-index: 2;
    }

    .room-meta-item {
        font-size: 0.85rem;
        color: #6c757d;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Steps Section */
    .step-number {
        width: 45px;
        height: 45px;
        background-color: var(--accent-color);
        color: white;
        font-size: 1.25rem;
        font-weight: 800;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        box-shadow: 0 5px 15px rgba(251, 139, 36, 0.3);
    }
</style>
@endsection

@section('content')

<!-- Hero Section -->
<section class="hero-section text-center text-lg-start">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 animate-fade-in">
                <h1 class="hero-title">Ruang Rapat Premium untuk Kolaborasi Hebat</h1>
                <p class="hero-subtitle">
                    Temukan dan pesan ruang rapat profesional dengan fasilitas tercanggih secara instan. Dirancang untuk meningkatkan produktivitas, kenyamanan, dan kesuksesan tim Anda di setiap sesi pertemuan.
                </p>
                <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
                    <a href="#rooms" class="btn btn-accent btn-lg px-4"><i class="fa-regular fa-calendar-check me-2"></i>Pesan Ruangan</a>
                    <a href="#facilities" class="btn btn-outline-light btn-lg px-4"><i class="fa-solid fa-play me-2"></i>Lihat Fasilitas</a>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block animate-fade-in delay-1">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?q=80&w=1200&auto=format&fit=crop" class="img-fluid rounded-4 shadow-lg border border-secondary border-opacity-25" alt="Meeting Room Hero">
            </div>
        </div>
    </div>
</section>

<!-- Quick Booking Search Panel -->
<section class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="search-panel animate-fade-in delay-2">
                <form action="#rooms" method="GET">
                    <div class="row align-items-center g-3">
                        <div class="col-lg-3 col-md-6 search-input-group">
                            <label class="form-label text-muted small fw-bold"><i class="fa-solid fa-users text-primary-custom me-2"></i>Jumlah Peserta</label>
                            <select class="form-select border-0 shadow-none p-0 fw-semibold fs-5 text-dark">
                                <option value="any">Berapa saja</option>
                                <option value="small">1 - 5 Orang</option>
                                <option value="medium">6 - 15 Orang</option>
                                <option value="large">16 - 30 Orang</option>
                                <option value="hall">Lebih dari 30</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 search-input-group">
                            <label class="form-label text-muted small fw-bold"><i class="fa-regular fa-calendar text-primary-custom me-2"></i>Pilih Tanggal</label>
                            <input type="date" class="form-control border-0 shadow-none p-0 fw-semibold fs-5 text-dark" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-lg-3 col-md-6 search-input-group">
                            <label class="form-label text-muted small fw-bold"><i class="fa-regular fa-clock text-primary-custom me-2"></i>Tipe Ruangan</label>
                            <select class="form-select border-0 shadow-none p-0 fw-semibold fs-5 text-dark">
                                <option value="any">Semua Tipe</option>
                                <option value="boardroom">Boardroom</option>
                                <option value="creative">Creative Space</option>
                                <option value="seminar">Seminar Hall</option>
                                <option value="huddle">Huddle Pod</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 text-center text-lg-end">
                            <button type="submit" class="btn btn-primary btn-lg w-100 py-3"><i class="fa-solid fa-magnifying-glass me-2"></i>Cari Ruangan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Core Benefits Section -->
<section id="facilities" class="py-5">
    <div class="container">
        <div class="text-center max-w-600 mx-auto mb-5">
            <span class="badge bg-light text-primary-custom px-3 py-2 fs-6 mb-2">Mengapa Memilih Kami</span>
            <h2 class="h1">Fasilitas Standar Kelas Dunia</h2>
            <p class="text-muted">Setiap ruang rapat dilengkapi dengan standar fasilitas premium untuk menjamin kenyamanan dan kelancaran kolaborasi Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100">
                    <div class="benefit-icon"><i class="fa-solid fa-wifi"></i></div>
                    <h5>Internet Super Cepat</h5>
                    <p class="text-muted small mb-0">Sambungan Wi-Fi fiber optik dedicated berkecepatan tinggi tanpa hambatan untuk video call lancar.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100">
                    <div class="benefit-icon"><i class="fa-solid fa-tv"></i></div>
                    <h5>Smart TV & Proyektor</h5>
                    <p class="text-muted small mb-0">Layar 4K berukuran besar dan proyektor mutakhir untuk presentasi bisnis yang tajam dan dinamis.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100">
                    <div class="benefit-icon"><i class="fa-solid fa-mug-hot"></i></div>
                    <h5>Kopi & Teh Gratis</h5>
                    <p class="text-muted small mb-0">Nikmati teh premium dan kopi hitam arabika segar sepanjang rapat secara cuma-cuma.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100">
                    <div class="benefit-icon"><i class="fa-solid fa-headphones"></i></div>
                    <h5>Kedap Suara Maksimal</h5>
                    <p class="text-muted small mb-0">Desain akustik dinding dan kaca kedap suara menjaga kerahasiaan diskusi internal penting Anda.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rooms Catalog Section -->
<section id="rooms" class="py-5 bg-white border-top border-bottom border-light">
    <div class="container">
        <div class="text-center max-w-600 mx-auto mb-5">
            <span class="badge bg-light text-primary-custom px-3 py-2 fs-6 mb-2">Daftar Pilihan</span>
            <h2 class="h1">Eksplorasi Ruang Rapat Kami</h2>
            <p class="text-muted">Pilih kapasitas dan gaya ruang rapat yang paling sesuai dengan kebutuhan agenda pertemuan Anda hari ini.</p>
        </div>
        <div class="row g-4">
            @foreach($rooms as $room)
            <div class="col-lg-6 col-xl-3 col-md-6">
                <div class="premium-card h-100 d-flex flex-column">
                    <div class="room-img-container">
                        <span class="room-badge">{{ $room['type'] }}</span>
                        <img src="{{ $room['images'][0] }}" alt="{{ $room['name'] }}">
                        <span class="room-price-tag">Rp {{ number_format($room['price'], 0, ',', '.') }}<span class="fs-7 fw-normal">/jam</span></span>
                    </div>
                    <div class="p-4 d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center text-warning fs-7 gap-1">
                                <i class="fa-solid fa-star"></i>
                                <span class="fw-bold text-dark fs-6">{{ $room['rating'] }}</span>
                                <span class="text-muted fs-7">({{ $room['reviews'] }})</span>
                            </div>
                            <span class="text-muted small"><i class="fa-solid fa-layer-group me-1"></i>{{ $room['floor'] }}</span>
                        </div>
                        <h4 class="h5 mb-2">{{ $room['name'] }}</h4>
                        <p class="text-muted small mb-4 flex-grow-1">{{ $room['short_desc'] }}</p>
                        
                        <div class="row g-2 mb-4 border-top pt-3 border-light-subtle">
                            <div class="col-6">
                                <span class="room-meta-item"><i class="fa-solid fa-users text-primary-custom"></i>Kapasitas {{ $room['capacity'] }} Pax</span>
                            </div>
                            <div class="col-6">
                                <span class="room-meta-item"><i class="fa-solid fa-arrows-left-right text-primary-custom"></i>Luas {{ $room['size'] }}</span>
                            </div>
                        </div>
                        
                        <a href="{{ url('/room/'.$room['id']) }}" class="btn btn-outline-primary w-100 mt-auto">Lihat Detail Ruang</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- How to Book Steps -->
<section id="steps" class="py-5">
    <div class="container text-center">
        <div class="max-w-600 mx-auto mb-5">
            <span class="badge bg-light text-primary-custom px-3 py-2 fs-6 mb-2">Mudah & Cepat</span>
            <h2 class="h1">Cara Melakukan Pemesanan</h2>
            <p class="text-muted">Hanya butuh 4 langkah mudah untuk mengamankan ruang rapat impian Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100 border-0 shadow-none bg-transparent">
                    <div class="step-number">1</div>
                    <h5>Pilih Ruangan</h5>
                    <p class="text-muted small mb-0">Eksplor katalog ruangan dan temukan yang paling cocok dengan tim Anda.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100 border-0 shadow-none bg-transparent">
                    <div class="step-number">2</div>
                    <h5>Pilih Jadwal & Jam</h5>
                    <p class="text-muted small mb-0">Pilih tanggal dan slot jam ketersediaan yang pas untuk rapat Anda.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100 border-0 shadow-none bg-transparent">
                    <div class="step-number">3</div>
                    <h5>Lengkapi Data</h5>
                    <p class="text-muted small mb-0">Masukkan detail keperluan meeting dan info kontak pemesan.</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="premium-card p-4 h-100 border-0 shadow-none bg-transparent">
                    <div class="step-number">4</div>
                    <h5>Selesai & Konfirmasi</h5>
                    <p class="text-muted small mb-0">Dapatkan e-receipt dan kode akses masuk ruangan rapat instan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-5 bg-white border-top border-light">
    <div class="container">
        <div class="text-center max-w-600 mx-auto mb-5">
            <span class="badge bg-light text-primary-custom px-3 py-2 fs-6 mb-2">Testimoni Klien</span>
            <h2 class="h1">Apa Kata Mereka?</h2>
            <p class="text-muted">Pendapat para pimpinan tim, sekretaris, dan eksekutif yang sering menggunakan fasilitas kami.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="premium-card p-4 h-100 bg-light border-0 shadow-none">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3 border border-3 border-white shadow-sm" style="width: 55px; height: 55px; object-fit: cover;" alt="Clara">
                        <div>
                            <h6 class="mb-0">Clara Amanda</h6>
                            <span class="text-muted small">HR Manager, TechSpace</span>
                        </div>
                    </div>
                    <div class="text-warning mb-2 fs-7">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-muted small mb-0 italic">"Sangat suka dengan suasana Emerald Boardroom. Desain interiornya sangat profesional dan wifinya sangat kencang, membuat presentasi proposal bisnis kami berjalan sangat sukses depan investor."</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="premium-card p-4 h-100 bg-light border-0 shadow-none">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3 border border-3 border-white shadow-sm" style="width: 55px; height: 55px; object-fit: cover;" alt="Luthfi">
                        <div>
                            <h6 class="mb-0">Luthfi Kurnia</h6>
                            <span class="text-muted small">CEO, Creative Studio</span>
                        </div>
                    </div>
                    <div class="text-warning mb-2 fs-7">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-muted small mb-0 italic">"Creative Hub sangat menyenangkan! Tim kami sangat produktif bekerja di sana selama 4 jam penuh. Dinding cat tulisnya sangat membantu memvisualisasikan ide. Kopi gratisnya enak!"</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 d-md-none d-lg-block">
                <div class="premium-card p-4 h-100 bg-light border-0 shadow-none">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop" class="rounded-circle me-3 border border-3 border-white shadow-sm" style="width: 55px; height: 55px; object-fit: cover;" alt="Dewi">
                        <div>
                            <h6 class="mb-0">Dewi Kartika</h6>
                            <span class="text-muted small">Operational Director, FinCorp</span>
                        </div>
                    </div>
                    <div class="text-warning mb-2 fs-7">
                        <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-2"></i>
                    </div>
                    <p class="text-muted small mb-0 italic">"Seminar Hall sangat luas dan konfigurasi kursinya bisa kami kustom sesuai selera. Bantuan asisten ruang rapat yang standby sangat sigap. Acara training 50 staf berjalan tanpa kendala."</p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
