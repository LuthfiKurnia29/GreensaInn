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
                        <div class="col-6 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-users"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Kapasitas</div>
                                    <div class="fw-bold text-dark">{{ $room['capacity'] }} Orang</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-arrows-left-right"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Luas Ruang</div>
                                    <div class="fw-bold text-dark">{{ $room['size'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
                            <div class="d-sm-flex align-items-center gap-3">
                                <div class="spec-icon-box mb-2 mb-sm-0"><i class="fa-solid fa-layer-group"></i></div>
                                <div>
                                    <div class="text-muted small fw-bold">Lokasi</div>
                                    <div class="fw-bold text-dark">{{ $room['floor'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-3">
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
                    <h3 class="h4 mb-2 border-bottom pb-2 border-light">Pilih Jam Pertemuan</h3>
                    <p class="text-muted small mb-4"><i class="fa-solid fa-circle-info me-2 text-primary-custom"></i>Klik slot waktu yang tersedia untuk memilih jam booking rapat Anda. Anda bisa memilih beberapa jam sekaligus secara berurutan.</p>
                    
                    <div class="slot-grid" id="timeSlotsContainer">
                        @foreach($room['calendar'] as $time => $status)
                            @if($status === 'tersedia')
                                <div class="time-slot-btn available" data-time="{{ $time }}" onclick="toggleSlot(this)">
                                    <i class="fa-regular fa-clock me-1"></i> {{ $time }}
                                    <div class="small fw-normal text-muted mt-1 fs-7">Tersedia</div>
                                </div>
                            @else
                                <div class="time-slot-btn booked">
                                    <i class="fa-solid fa-ban me-1"></i> {{ $time }}
                                    <div class="small fw-normal mt-1 fs-7">Terisi</div>
                                </div>
                            @endif
                        @endforeach
                    </div>
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

                    <!-- Interactive Form Layout -->
                    <form id="bookingForm" onsubmit="submitBooking(event)">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted"><i class="fa-regular fa-calendar me-2"></i>PILIH TANGGAL</label>
                            <input type="date" class="form-control p-3 border-light-subtle rounded-3" id="bookingDate" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <!-- Displays selected slots read-only -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted"><i class="fa-regular fa-clock me-2"></i>JAM DIPILIH</label>
                            <input type="text" class="form-control p-3 border-light-subtle rounded-3 bg-light" id="selectedSlotsDisplay" placeholder="Pilih jam di tabel kiri..." readonly required>
                            <input type="hidden" id="selectedHoursCount" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-users me-2"></i>ESTIMASI PESERTA</label>
                            <div class="input-group">
                                <input type="number" class="form-control p-3 border-light-subtle rounded-start-3" id="participantCount" value="5" min="1" max="{{ $room['capacity'] }}" required>
                                <span class="input-group-text bg-light border-light-subtle rounded-end-3">Orang</span>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted"><i class="fa-solid fa-list-check me-2"></i>AGENDA RAPAT</label>
                            <textarea class="form-control border-light-subtle rounded-3" id="meetingPurpose" rows="3" placeholder="Contoh: Rapat Koordinasi Kuartal 1" required></textarea>
                        </div>

                        <!-- Price Breakdown calculations -->
                        <div class="price-total-box mb-4" id="priceCalculatorBox" style="display: none;">
                            <h6 class="fw-bold mb-3">Rincian Estimasi Biaya</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted">Sewa Ruangan (<span id="calcHoursText">0 jam</span>)</span>
                                <span class="small fw-semibold" id="calcRoomPrice">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted">Pajak & Layanan (10%)</span>
                                <span class="small fw-semibold" id="calcServiceTax">Rp 0</span>
                            </div>
                            <hr class="my-2 border-secondary opacity-25">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Total Biaya</span>
                                <span class="fs-5 fw-extrabold text-primary-custom" id="calcTotalPrice">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-accent btn-lg w-100 py-3 mt-2" id="submitBookingBtn" disabled>
                            <i class="fa-regular fa-calendar-check me-2"></i>Pesan Sekarang
                        </button>
                    </form>
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
                <h3 class="fw-bold text-dark mb-2">Booking Berhasil Dikirim!</h3>
                <p class="text-muted small mb-4">Permintaan peminjaman ruang rapat Anda telah kami catat dalam antrean sistem.</p>
                
                <!-- Ticket details summary card -->
                <div class="premium-card bg-light p-4 text-start mb-4 border-0">
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Ruangan:</div>
                        <div class="col-7 fw-bold text-dark text-end">{{ $room['name'] }}</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Tanggal:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalDateVal">-</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Jam Booking:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalHoursVal">-</div>
                    </div>
                    <div class="row g-2 small mb-1">
                        <div class="col-5 text-muted">Tujuan Rapat:</div>
                        <div class="col-7 fw-semibold text-dark text-end text-truncate" id="modalPurposeVal">-</div>
                    </div>
                    <hr class="my-2 border-secondary opacity-10">
                    <div class="row g-2">
                        <div class="col-5 fw-bold text-dark">Total Bayar:</div>
                        <div class="col-7 fw-extrabold text-primary-custom text-end" id="modalPriceVal">-</div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">
                    <button class="btn btn-primary btn-lg py-3" onclick="downloadReceipt()"><i class="fa-solid fa-file-pdf me-2"></i>Unduh Bukti Reservasi (PDF)</button>
                    <a href="{{ url('/') }}" class="btn btn-outline-secondary py-3">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Config values
    const hourlyPrice = {{ $room['price'] }};
    let selectedSlots = [];

    // Switch main image gallery
    function switchMainImage(imgUrl, el) {
        document.getElementById('mainGalleryImg').src = imgUrl;
        document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        el.classList.add('active');
    }

    // Toggle selected time slot
    function toggleSlot(el) {
        const timeVal = el.getAttribute('data-time');
        
        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            selectedSlots = selectedSlots.filter(t => t !== timeVal);
        } else {
            el.classList.add('selected');
            selectedSlots.push(timeVal);
        }

        // Sort slots sequentially
        selectedSlots.sort();

        // Update displays
        updateBookingSummary();
    }

    // Update booking form and recalculate pricing
    function updateBookingSummary() {
        const slotsCount = selectedSlots.length;
        const displayField = document.getElementById('selectedSlotsDisplay');
        const countField = document.getElementById('selectedHoursCount');
        const submitBtn = document.getElementById('submitBookingBtn');
        const calcBox = document.getElementById('priceCalculatorBox');
        
        if (slotsCount === 0) {
            displayField.value = '';
            countField.value = '0';
            submitBtn.disabled = true;
            calcBox.style.display = 'none';
            return;
        }

        // Display string like "08:00, 09:00 (2 jam)"
        displayField.value = selectedSlots.join(', ') + ` (${slotsCount} jam)`;
        countField.value = slotsCount;
        submitBtn.disabled = false;
        calcBox.style.display = 'block';

        // Calculation logic
        const rawPrice = hourlyPrice * slotsCount;
        const serviceTax = Math.round(rawPrice * 0.1);
        const totalPrice = rawPrice + serviceTax;

        // Populate calculator panel
        document.getElementById('calcHoursText').innerText = `${slotsCount} jam`;
        document.getElementById('calcRoomPrice').innerText = formatRupiah(rawPrice);
        document.getElementById('calcServiceTax').innerText = formatRupiah(serviceTax);
        document.getElementById('calcTotalPrice').innerText = formatRupiah(totalPrice);
    }

    // Utility: Format number to Indonesian Rupiah currency
    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\n))/g, ".");
    }

    // Submit booking action mockup
    function submitBooking(event) {
        event.preventDefault();
        
        const dateInput = document.getElementById('bookingDate').value;
        const slotsText = selectedSlots.join(', ');
        const meetingPurpose = document.getElementById('meetingPurpose').value;
        
        const slotsCount = selectedSlots.length;
        const rawPrice = hourlyPrice * slotsCount;
        const serviceTax = Math.round(rawPrice * 0.1);
        const totalPrice = rawPrice + serviceTax;

        // Set values inside modal
        // Format Date to indonesian readable format
        const dateObj = new Date(dateInput);
        const indonesianDate = dateObj.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

        document.getElementById('modalDateVal').innerText = indonesianDate;
        document.getElementById('modalHoursVal').innerText = slotsText + ` (${slotsCount} jam)`;
        document.getElementById('modalPurposeVal').innerText = meetingPurpose;
        document.getElementById('modalPriceVal').innerText = formatRupiah(totalPrice);

        // Open Bootstrap Modal
        const successModal = new bootstrap.Modal(document.getElementById('successBookingModal'));
        successModal.show();
    }

    // Mock receipt download
    function downloadReceipt() {
        alert("E-Receipt PDF berhasil diunduh! (Ini merupakan fitur mockup cetak tanda terima)");
    }
</script>
@endsection
