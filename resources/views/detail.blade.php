@extends('layouts.app')

@section('title')
    {{ $room['name'] }} - GreensaInn
@endsection

@section('styles')
<style>
    /* Detail Layout CSS */
    .breadcrumb-nav {
        background-color: var(--primary-light);
        padding: 12px 0;
        margin-bottom: 40px;
    }
    
    .breadcrumb-item a {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
    }
    
    .breadcrumb-item.active {
        color: #6c757d;
        font-weight: 500;
    }

    /* Image Gallery */
    .gallery-main {
        height: 450px;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 16px;
        border: 1px solid rgba(15, 76, 92, 0.08);
    }
    
    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition-smooth);
    }
    
    .gallery-thumbnail {
        height: 100px;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        opacity: 0.6;
        transition: var(--transition-smooth);
        border: 2px solid transparent;
    }
    
    .gallery-thumbnail:hover, .gallery-thumbnail.active {
        opacity: 1;
        border-color: var(--primary-color);
    }
    
    .gallery-thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Specs badge */
    .spec-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background-color: var(--primary-light);
        color: var(--primary-color);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    /* Facilities checklist */
    .facility-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px;
        border-radius: 10px;
        background-color: #fdfdfd;
        border: 1px solid rgba(15, 76, 92, 0.03);
    }

    /* Time Slot Selection */
    .slot-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 12px;
    }

    .time-slot-btn {
        padding: 12px 8px;
        font-size: 0.9rem;
        font-weight: 700;
        border-radius: 12px;
        border: 1.5px solid;
        text-align: center;
        cursor: pointer;
        transition: var(--transition-smooth);
        user-select: none;
    }

    .time-slot-btn.available {
        background-color: white;
        border-color: rgba(15, 76, 92, 0.15);
        color: var(--primary-color);
    }

    .time-slot-btn.available:hover {
        background-color: var(--primary-light);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .time-slot-btn.selected {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
        box-shadow: 0 4px 10px rgba(15, 76, 92, 0.2);
    }

    .time-slot-btn.booked {
        background-color: #f1f3f5;
        border-color: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
        text-decoration: line-through;
    }

    /* Sticky Sidebar */
    .sticky-booking-card {
        position: sticky;
        top: 100px;
        z-index: 100;
    }

    .price-total-box {
        background-color: var(--light-bg);
        border-radius: 14px;
        padding: 16px;
        border: 1px dashed rgba(15, 76, 92, 0.15);
    }

    /* Success Modal Checkmark */
    .success-checkmark-box {
        width: 80px;
        height: 80px;
        background-color: #e8f5e9;
        color: #2e7d32;
        font-size: 2.5rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 20px auto;
        animation: scaleUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    @keyframes scaleUp {
        0% { transform: scale(0.5); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endsection

@section('content')

<!-- Breadcrumbs -->
<div class="breadcrumb-nav">
    <div class="container">
        <nav aria-label="breadcrumb" class="m-0">
            <ol class="breadcrumb m-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fa-solid fa-house me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/') }}#rooms">Daftar Ruangan</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $room['name'] }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Details Section -->
<section class="pb-5">
    <div class="container">
        <div class="row g-5">
            <!-- Left Side Details (Col-8) -->
            <div class="col-lg-8">
                <!-- Header Info -->
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-primary px-3 py-2 rounded-pill fs-7">{{ $room['type'] }}</span>
                        <div class="text-warning fs-7 d-flex align-items-center gap-1 ms-2">
                            <i class="fa-solid fa-star"></i>
                            <span class="fw-bold text-dark fs-6">{{ $room['rating'] }}</span>
                            <span class="text-muted fs-7">({{ $room['reviews'] }} reviews)</span>
                        </div>
                    </div>
                    <h1 class="display-5 text-dark fw-800">{{ $room['name'] }}</h1>
                    <p class="text-muted fs-5 mb-0"><i class="fa-solid fa-location-dot me-2 text-accent-custom"></i>{{ $room['floor'] }} - Gedung Utama GreensaInn</p>
                </div>

                <!-- Gallery -->
                <div class="mb-5">
                    <div class="gallery-main shadow-sm">
                        <img src="{{ $room['images'][0] }}" id="mainGalleryImg" alt="Room View Main">
                    </div>
                    <div class="row g-2">
                        @foreach($room['images'] as $index => $img)
                        <div class="col-4">
                            <div class="gallery-thumbnail {{ $index === 0 ? 'active' : '' }}" onclick="switchMainImage('{{ $img }}', this)">
                                <img src="{{ $img }}" alt="Room View Thumbnail {{ $index + 1 }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Specs Grid -->
                <div class="premium-card p-4 mb-5">
                    <h4 class="h5 mb-4">Spesifikasi Utama Ruangan</h4>
                    <div class="row g-4 text-center text-sm-start">
                        <div class="col-4 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-users"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Kapasitas</div>
                                    <div class="fw-bold text-dark">{{ $room['capacity'] }} Orang</div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-6 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-arrows-left-right"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Luas Ruang</div>
                                    <div class="fw-bold text-dark">{{ $room['size'] }}</div>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-4 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-layer-group"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Lokasi</div>
                                    <div class="fw-bold text-dark">{{ $room['floor'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-shield-halved"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Keamanan</div>
                                    <div class="fw-bold text-dark">CCTV & Akses</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-5">
                    <h3 class="h4 mb-3 border-bottom pb-2 border-light">Deskripsi Ruangan</h3>
                    <p class="text-muted fs-6" style="line-height: 1.8;">{!! nl2br(e($room['description'])) !!}</p>
                </div>

                <!-- Facilities List -->
                <div class="mb-5">
                    <h3 class="h4 mb-4 border-bottom pb-2 border-light">Fasilitas Termasuk</h3>
                    <div class="row g-3">
                        @foreach($room['amenities'] as $amenity)
                        <div class="col-md-6 col-12">
                            <div class="facility-item">
                                <i class="fa-solid fa-circle-check text-success fs-5"></i>
                                <span class="fw-semibold text-dark-emphasis">{{ $amenity }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Interactive Time Slot Selection -->
                <div class="mb-5">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <h3 class="h4 mb-0 border-bottom pb-2 border-light w-100">Pilih Jam Pertemuan</h3>
                    </div>
                    <p class="text-muted small mb-3"><i class="fa-solid fa-circle-info me-2 text-primary-custom"></i>Slot <span class="badge bg-danger-subtle text-danger fw-semibold px-2 py-1"><i class="fa-solid fa-ban me-1"></i>Terisi</span> tidak dapat dipilih. Jam yang tersedia bisa dipilih beberapa sekaligus secara berurutan.</p>
                    <div class="d-flex align-items-center gap-3 mb-3 small">
                        <div class="d-flex align-items-center gap-1"><div style="width:14px;height:14px;border-radius:4px;background:white;border:1.5px solid var(--primary-color);"></div> Tersedia</div>
                        <div class="d-flex align-items-center gap-1"><div style="width:14px;height:14px;border-radius:4px;background:var(--primary-color);"></div> Dipilih</div>
                        <div class="d-flex align-items-center gap-1"><div style="width:14px;height:14px;border-radius:4px;background:#f1f3f5;border:1.5px solid #e9ecef;"></div> Terisi/Diblokir</div>
                    </div>
                    <div id="slotLoading" class="text-center py-4 d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        <span class="ms-2 small text-muted">Memuat ketersediaan slot...</span>
                    </div>
                    <div class="slot-grid" id="timeSlotsContainer"></div>
                </div>
            </div>

            <!-- Right Side Sticky Booking Form (Col-4) -->
            <div class="col-lg-4">
                <div class="premium-card p-4 sticky-booking-card">
                    <div class="mb-4 border-bottom pb-3 border-light">
                        <span class="text-muted small">Harga Sewa</span>
                        <div class="d-flex align-items-baseline">
                            <h3 class="display-6 text-primary-custom fw-bold mb-0">Rp {{ number_format($room['price'], 0, ',', '.') }}</h3>
                            <span class="text-muted ms-2">/ jam</span>
                        </div>
                    </div>

                    @auth
                        {{-- Hanya tampil untuk user yang sudah login --}}

                        @if($errors->any())
                            <div class="alert alert-danger small mb-3" style="border-radius:12px;background:#fef2f2;border:none;color:#b91c1c;">
                                <strong><i class="fa-solid fa-triangle-exclamation me-1"></i>Mohon periksa kembali:</strong>
                                <ul class="mb-0 mt-1 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="bookingForm" method="POST" action="{{ route('booking.store', $room['id']) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="booking_room_name" value="{{ $room['name'] }}">
                            <input type="hidden" name="waktu_mulai" id="hiddenWaktuMulai" value="">
                            <input type="hidden" name="waktu_selesai" id="hiddenWaktuSelesai" value="">

                            @if(count($pakets) > 0)
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-box-open me-2"></i>TIPE PESANAN</label>
                                <select class="form-select p-3 border-light-subtle rounded-3" id="paketSelect" name="paket_id" onchange="updateBookingSummary()">
                                    <option value="">Sewa Reguler (Per Jam) - Rp {{ number_format($room['price'], 0, ',', '.') }}/jam</option>
                                    @foreach($pakets as $paket)
                                        <option value="{{ $paket->id }}" data-price="{{ $paket->harga_paket }}">{{ $paket->nama_paket }} - Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><i class="fa-regular fa-calendar me-2"></i>TANGGAL PEMINJAMAN</label>
                                <input type="date" class="form-control p-3 border-light-subtle rounded-3" id="bookingDate" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                            </div>

                            {{-- Displays selected slots read-only --}}
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><i class="fa-regular fa-clock me-2"></i>JAM DIPILIH</label>
                                <input type="text" class="form-control p-3 border-light-subtle rounded-3 bg-light" id="selectedSlotsDisplay" placeholder="Pilih jam di tabel kiri..." readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-users me-2"></i>JUMLAH PESERTA</label>
                                <div class="input-group">
                                    <input type="number" class="form-control p-3 border-light-subtle rounded-start-3" id="participantCount" name="jumlah_peserta" value="{{ old('jumlah_peserta', 5) }}" min="1" max="{{ $room['capacity'] }}" required>
                                    <span class="input-group-text bg-light border-light-subtle rounded-end-3">Orang</span>
                                </div>
                                <div class="text-muted" style="font-size:0.8rem;">Maks. {{ $room['capacity'] }} orang</div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-list-check me-2"></i>TUJUAN RAPAT</label>
                                <textarea class="form-control border-light-subtle rounded-3" id="meetingPurpose" name="tujuan_rapat" rows="3" placeholder="Contoh: Rapat Koordinasi Kuartal 1" required maxlength="500">{{ old('tujuan_rapat') }}</textarea>
                            </div>

                            @if(Auth::check() && Auth::user()->instansi !== 'umum')
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-file-arrow-up me-2"></i>DOKUMEN PENDUKUNG (SURAT TUGAS/PERMOHONAN)</label>
                                <input type="file" class="form-control border-light-subtle rounded-3 p-2" name="dokumen_pendukung" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" required>
                                <div class="text-muted mt-1" style="font-size:0.8rem;">Wajib diunggah untuk keperluan validasi. Maks. 5MB (PDF/DOC/IMG).</div>
                            </div>
                            @endif

                            {{-- Price Breakdown --}}
                            <div class="price-total-box mb-4" id="priceCalculatorBox" style="display: none;">
                                <h6 class="fw-bold mb-3">Rincian Estimasi Biaya</h6>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted" id="calcRoomLabel">Sewa Ruangan (<span id="calcHoursText">0 jam</span>)</span>
                                    <span class="small fw-semibold" id="calcRoomPrice">Rp 0</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="small text-muted">Pajak &amp; Layanan (10%)</span>
                                    <span class="small fw-semibold" id="calcServiceTax">Rp 0</span>
                                </div>
                                <hr class="my-2 border-secondary opacity-25">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-dark">Total Biaya</span>
                                    <span class="fs-5 fw-extrabold text-primary-custom" id="calcTotalPrice">Rp 0</span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-accent btn-lg w-100 py-3 mt-2" id="submitBookingBtn" disabled>
                                <i class="fa-regular fa-calendar-check me-2"></i>Ajukan Pemesanan
                            </button>
                        </form>

                    @else
                        {{-- Guest: tampilkan prompt login --}}
                        <div class="text-center py-3">
                            <div style="width:70px;height:70px;background:var(--primary-light);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                                <i class="fa-solid fa-lock text-primary-custom fs-3"></i>
                            </div>
                            <h5 class="fw-bold text-dark mb-2">Login untuk Memesan</h5>
                            <p class="text-muted small mb-4">Anda perlu masuk ke akun Anda terlebih dahulu untuk dapat melakukan pemesanan ruangan ini.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-3 mb-2">
                                <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk Sekarang
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="fa-solid fa-user-plus me-2"></i>Daftar Akun Baru
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Confirmation Success Modal -->
<div class="modal fade" id="successBookingModal" tabindex="-1" aria-labelledby="successBookingModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-body text-center p-5">
                <div class="success-checkmark-box">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <h3 class="fw-bold text-dark mb-2">Pemesanan Berhasil Dikirim!</h3>
                <p class="text-muted small mb-4">Permintaan peminjaman ruang rapat Anda telah kami catat. Admin akan segera melakukan verifikasi.</p>

                <div class="premium-card bg-light p-4 text-start mb-4 border-0">
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Ruangan:</div>
                        <div class="col-7 fw-bold text-dark text-end">{{ $room['name'] }}</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Tanggal:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalDateVal">{{ session('booking_date') ? date('d M Y', strtotime(session('booking_date'))) : '-' }}</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Jam:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalHoursVal">{{ session('booking_time', '-') }}</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Tujuan Rapat:</div>
                        <div class="col-7 fw-semibold text-dark text-end text-truncate" id="modalPurposeVal">{{ session('booking_purpose', '-') }}</div>
                    </div>
                    <hr class="my-2 border-secondary opacity-10">
                    <div class="row g-2">
                        <div class="col-6 fw-bold text-dark">Status:</div>
                        <div class="col-6 fw-extrabold text-end">
                            <span class="badge bg-warning text-dark px-3 py-2">Menunggu Konfirmasi</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg py-3"><i class="fa-solid fa-house me-2"></i>Kembali ke Beranda</a>
                    <button class="btn btn-outline-secondary py-3" data-bs-dismiss="modal">Lihat Detail Ruangan</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const hourlyPrice  = {{ $room['price'] }};
    const ruanganId    = {{ $room['id'] }};
    // Slots tersedia: jam 07:00 sampai 21:00 (slot per jam)
    const ALL_SLOTS    = ['07:00','08:00','09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00'];
    let selectedSlots  = [];
    // Booked ranges dari server (diisi saat init & saat tanggal berubah)
    let bookedRanges   = @json($bookedSlots);

    // -------------------------------------------------------
    // Helper: cek apakah slot jam H:00 overlap dengan suatu booking range
    // Booking menempati dari waktu_mulai s/d waktu_selesai (eksklusif selesai)
    // Slot "08:00" berarti jam 08:00 - 09:00
    // -------------------------------------------------------
    function isSlotBooked(slotTime, ranges) {
        const [sh, sm] = slotTime.split(':').map(Number);
        const slotStart = sh * 60 + sm;       // menit mulai slot
        const slotEnd   = slotStart + 60;     // menit akhir slot (eksklusif)

        return ranges.some(r => {
            const [bmh, bmm] = r.mulai.split(':').map(Number);
            const [bsh, bsm] = r.selesai.split(':').map(Number);
            const bookStart = bmh * 60 + bmm;
            const bookEnd   = bsh * 60 + bsm;
            // Overlap jika slot tidak sepenuhnya sebelum atau sesudah booking
            return slotStart < bookEnd && slotEnd > bookStart;
        });
    }

    // -------------------------------------------------------
    // Render semua slot ke dalam #timeSlotsContainer
    // -------------------------------------------------------
    function renderSlots(ranges) {
        selectedSlots = [];   // reset pilihan saat re-render
        updateBookingSummary();

        const container = document.getElementById('timeSlotsContainer');
        container.innerHTML = '';

        ALL_SLOTS.forEach(slotTime => {
            const booked = isSlotBooked(slotTime, ranges);
            const div    = document.createElement('div');

            if (booked) {
                div.className = 'time-slot-btn booked';
                div.innerHTML = `<i class="fa-solid fa-ban me-1"></i> ${slotTime}<div class="small fw-normal mt-1 fs-7">Terisi</div>`;
            } else {
                div.className = 'time-slot-btn available';
                div.setAttribute('data-time', slotTime);
                div.setAttribute('onclick', 'toggleSlot(this)');
                div.innerHTML = `<i class="fa-regular fa-clock me-1"></i> ${slotTime}<div class="small fw-normal text-muted mt-1 fs-7">Tersedia</div>`;
            }
            container.appendChild(div);
        });
    }

    // Render awal dengan data dari PHP (halaman pertama kali load)
    document.addEventListener('DOMContentLoaded', function () {
        renderSlots(bookedRanges);
    });

    // -------------------------------------------------------
    // AJAX: refresh slot saat tanggal berubah
    // -------------------------------------------------------
    const dateInput = document.getElementById('bookingDate');
    if (dateInput) {
        dateInput.addEventListener('change', function () {
            const date = this.value;
            if (!date) return;

            const loading   = document.getElementById('slotLoading');
            const container = document.getElementById('timeSlotsContainer');

            loading.classList.remove('d-none');
            container.classList.add('d-none');

            fetch(`/api/booked-slots/${ruanganId}?date=${date}`)
                .then(res => res.json())
                .then(data => {
                    bookedRanges = data.booked;
                    renderSlots(bookedRanges);
                })
                .catch(() => {
                    // Jika gagal, anggap semua tersedia
                    renderSlots([]);
                })
                .finally(() => {
                    loading.classList.add('d-none');
                    container.classList.remove('d-none');
                });
        });
    }

    // -------------------------------------------------------
    // Toggle pilihan slot
    // -------------------------------------------------------
    function toggleSlot(el) {
        const timeVal = el.getAttribute('data-time');

        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            el.querySelector('.small').innerText = 'Tersedia';
            el.querySelector('.small').classList.add('text-muted');
            selectedSlots = selectedSlots.filter(t => t !== timeVal);
        } else {
            el.classList.add('selected');
            el.querySelector('.small').innerText = 'Dipilih';
            el.querySelector('.small').classList.remove('text-muted');
            selectedSlots.push(timeVal);
        }

        selectedSlots.sort();
        updateBookingSummary();
    }

    // -------------------------------------------------------
    // Update form & kalkulasi harga
    // -------------------------------------------------------
    function updateBookingSummary() {
        const slotsCount    = selectedSlots.length;
        const displayField  = document.getElementById('selectedSlotsDisplay');
        const submitBtn     = document.getElementById('submitBookingBtn');
        const calcBox       = document.getElementById('priceCalculatorBox');
        const hiddenMulai   = document.getElementById('hiddenWaktuMulai');
        const hiddenSelesai = document.getElementById('hiddenWaktuSelesai');

        if (slotsCount === 0) {
            if (displayField)  displayField.value   = '';
            if (submitBtn)     submitBtn.disabled   = true;
            if (calcBox)       calcBox.style.display = 'none';
            if (hiddenMulai)   hiddenMulai.value    = '';
            if (hiddenSelesai) hiddenSelesai.value  = '';
            return;
        }

        if (displayField) displayField.value = selectedSlots.join(', ') + ` (${slotsCount} jam)`;
        if (submitBtn)    submitBtn.disabled  = false;
        if (calcBox)      calcBox.style.display = 'block';

        // waktu_mulai = slot pertama, waktu_selesai = slot terakhir + 1 jam
        if (hiddenMulai)   hiddenMulai.value   = selectedSlots[0] + ':00';
        if (hiddenSelesai) {
            const lastSlot  = selectedSlots[selectedSlots.length - 1];
            const [h, m]    = lastSlot.split(':').map(Number);
            const selesaiH  = String(h + 1).padStart(2, '0');
            hiddenSelesai.value = `${selesaiH}:${String(m).padStart(2, '0')}:00`;
        }

        const paketSelect = document.getElementById('paketSelect');
        const selectedPaketId = paketSelect ? paketSelect.value : '';
        const selectedPaketOption = paketSelect && paketSelect.selectedIndex > 0 ? paketSelect.options[paketSelect.selectedIndex] : null;

        let rawPrice = 0;
        let pricingText = '';

        if (selectedPaketId && selectedPaketOption) {
            rawPrice = parseInt(selectedPaketOption.getAttribute('data-price'));
            pricingText = 'Paket ' + selectedPaketOption.text.split(' -')[0];
        } else {
            rawPrice = hourlyPrice * slotsCount;
            pricingText = `Sewa Ruangan (${slotsCount} jam)`;
        }
        
        const serviceTax = Math.round(rawPrice * 0.1);
        const totalPrice = rawPrice + serviceTax;

        const calcHours = document.getElementById('calcHoursText');
        const calcRoom  = document.getElementById('calcRoomPrice');
        const calcTax   = document.getElementById('calcServiceTax');
        const calcTotal = document.getElementById('calcTotalPrice');

        const calcRoomLabel = document.getElementById('calcRoomLabel');

        if (calcRoomLabel) {
            calcRoomLabel.innerHTML = pricingText;
        }

        if (calcRoom)  calcRoom.innerText   = formatRupiah(rawPrice);
        if (calcTax)   calcTax.innerText    = formatRupiah(serviceTax);
        if (calcTotal) calcTotal.innerText  = formatRupiah(totalPrice);
    }

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Switch main image gallery
    function switchMainImage(imgUrl, el) {
        document.getElementById('mainGalleryImg').src = imgUrl;
        document.querySelectorAll('.gallery-thumbnail').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    // Auto-open success modal jika session ada booking_success
    @if(session('booking_success'))
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('successBookingModal'));
        modal.show();
    });
    @endif

    // Auto-select paket from URL parameter
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const paketId = urlParams.get('paket');
        if (paketId) {
            const paketSelect = document.getElementById('paketSelect');
            if (paketSelect) {
                paketSelect.value = paketId;
                updateBookingSummary();
                // Scroll gently to the booking form
                setTimeout(() => {
                    paketSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 500);
            }
        }
    });
</script>
@endsection
