@extends('layouts.app')

@section('title', 'Pemesanan Ruangan - GreensaInn')

@section('content')
<div class="breadcrumb-nav bg-light py-3 mb-4 border-bottom border-light-subtle">
    <div class="container">
        <nav aria-label="breadcrumb" class="m-0">
            <ol class="breadcrumb m-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-primary-custom fw-semibold"><i class="fa-solid fa-house me-1"></i>Beranda</a></li>
                <li class="breadcrumb-item active text-muted fw-medium" aria-current="page">Pemesanan Ruangan</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container py-4 mb-5">
    <div class="max-w-600 mx-auto text-center mb-5">
        <span class="badge bg-primary-subtle text-primary-custom px-3 py-2 fs-6 mb-3 rounded-pill">Reservasi Mudah</span>
        <h2 class="h1 fw-bold text-dark">Formulir Pemesanan</h2>
        <p class="text-muted">Lengkapi detail di bawah ini untuk memesan ruang rapat sesuai dengan kebutuhan agenda Anda.</p>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="premium-card p-4 p-md-5 bg-white border border-light-subtle rounded-4 shadow-sm">
                
                @if($errors->any())
                    <div class="alert alert-danger mb-4 rounded-3 border-0 bg-danger-subtle text-danger">
                        <strong class="d-block mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>Mohon periksa kembali:</strong>
                        <ul class="mb-0 ps-3 small">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('booking_success'))
                    <div class="alert alert-success mb-4 rounded-3 border-0 bg-success-subtle text-success">
                        <strong class="d-block mb-1"><i class="fa-solid fa-circle-check me-1"></i>Pemesanan Berhasil!</strong>
                        <span class="small">Permintaan peminjaman ruang rapat Anda telah kami catat dan sedang menunggu konfirmasi admin.</span>
                    </div>
                @endif

                <form action="#" method="POST" id="genericBookingForm">
                    @csrf
                    
                    <h5 class="fw-bold mb-4 border-bottom pb-2 text-dark"><i class="fa-solid fa-door-open me-2 text-primary-custom"></i>1. Pilih Ruangan</h5>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">RUANG RAPAT</label>
                        <select class="form-select p-3 border-light-subtle rounded-3" id="roomSelect" required>
                            <option value="" selected disabled>-- Pilih Ruangan --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r['id'] }}" data-price="{{ $r['price'] }}" data-capacity="{{ $r['capacity'] }}" data-name="{{ $r['name'] }}">
                                    {{ $r['name'] }} (Maks {{ $r['capacity'] }} Org) - Rp {{ number_format($r['price'], 0, ',', '.') }}/jam
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="booking_room_name" id="bookingRoomName" value="">
                    </div>

                    <div class="mb-4" id="paketContainer" style="display: none;">
                        <label class="form-label small fw-bold text-muted">PILIH PAKET (OPSIONAL)</label>
                        <select class="form-select p-3 border-light-subtle rounded-3" name="paket_id" id="paketSelect">
                            <option value="">-- Sewa Reguler (Per Jam) --</option>
                            @foreach($pakets as $paket)
                                <option value="{{ $paket->id }}" data-ruangan="{{ $paket->ruangan_id }}" style="display:none;">
                                    {{ $paket->nama_paket }} - Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="small text-muted mt-1">Jika memilih paket, biaya akan dihitung berdasarkan harga paket tersebut.</div>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5 border-bottom pb-2 text-dark"><i class="fa-regular fa-calendar-check me-2 text-primary-custom"></i>2. Waktu Pelaksanaan</h5>
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-12">
                            <label class="form-label small fw-bold text-muted">TANGGAL PEMINJAMAN</label>
                            <input type="date" class="form-control p-3 border-light-subtle rounded-3" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">WAKTU MULAI</label>
                            <input type="time" class="form-control p-3 border-light-subtle rounded-3" name="waktu_mulai" value="{{ old('waktu_mulai') }}" required>
                            <div class="small text-muted mt-1">Misal: 09:00</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">WAKTU SELESAI</label>
                            <input type="time" class="form-control p-3 border-light-subtle rounded-3" name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                            <div class="small text-muted mt-1">Misal: 12:00</div>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5 border-bottom pb-2 text-dark"><i class="fa-solid fa-list-check me-2 text-primary-custom"></i>3. Detail Kegiatan</h5>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">JUMLAH PESERTA</label>
                        <div class="input-group">
                            <input type="number" class="form-control p-3 border-light-subtle rounded-start-3" id="participantCount" name="jumlah_peserta" value="{{ old('jumlah_peserta', 1) }}" min="1" required>
                            <span class="input-group-text bg-light border-light-subtle rounded-end-3">Orang</span>
                        </div>
                        <div class="text-danger small mt-1 d-none" id="capacityWarning"><i class="fa-solid fa-circle-exclamation me-1"></i>Jumlah peserta melebihi kapasitas maksimal ruangan ini.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">TUJUAN RAPAT / AGENDA</label>
                        <textarea class="form-control p-3 border-light-subtle rounded-3" name="tujuan_rapat" rows="4" placeholder="Contoh: Rapat Koordinasi Kuartal 1, Training Staf Baru, dll." required maxlength="500">{{ old('tujuan_rapat') }}</textarea>
                    </div>

                    <h5 class="fw-bold mb-4 mt-5 border-bottom pb-2 text-dark"><i class="fa-solid fa-couch me-2 text-primary-custom"></i>4. Fasilitas Tambahan (Opsional)</h5>
                    
                    <div class="mb-5">
                        <p class="small text-muted mb-3">Pilih fasilitas tambahan yang Anda butuhkan (masukkan jumlah).</p>
                        <div class="row g-3">
                            @foreach($fasilitas as $item)
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center justify-content-between p-3 border border-light-subtle rounded-3">
                                        <div>
                                            <div class="fw-bold">{{ $item->nama_fasilitas }}</div>
                                            <div class="small text-muted">Tersedia: {{ $item->stok_tersedia }}</div>
                                        </div>
                                        <div style="width: 100px;">
                                            <input type="number" class="form-control text-center" name="fasilitas[{{ $item->id }}]" value="0" min="0" max="{{ $item->stok_tersedia }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @if($fasilitas->isEmpty())
                                <div class="col-12">
                                    <div class="alert alert-info small mb-0">Saat ini tidak ada fasilitas tambahan yang tersedia.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-accent btn-lg w-100 py-3 fw-bold shadow-sm" id="submitBtn" disabled>
                        <i class="fa-solid fa-paper-plane me-2"></i>Kirim Permintaan Pemesanan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const form = document.getElementById('genericBookingForm');
    const roomSelect = document.getElementById('roomSelect');
    const participantCount = document.getElementById('participantCount');
    const capacityWarning = document.getElementById('capacityWarning');
    const submitBtn = document.getElementById('submitBtn');
    const roomNameInput = document.getElementById('bookingRoomName');
    
    // Default action base URL
    const baseUrl = "{{ url('/booking') }}";
    
    function validateForm() {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        
        if (roomSelect.value === "") {
            submitBtn.disabled = true;
            document.getElementById('paketContainer').style.display = 'none';
            return;
        } else {
            const paketContainer = document.getElementById('paketContainer');
            const paketSelect = document.getElementById('paketSelect');
            const roomId = roomSelect.value;
            const paketOptions = document.querySelectorAll('#paketSelect option[data-ruangan]');
            
            let hasPaket = false;
            paketOptions.forEach(opt => {
                if (opt.getAttribute('data-ruangan') == roomId) {
                    opt.style.display = 'block';
                    hasPaket = true;
                } else {
                    opt.style.display = 'none';
                }
            });
            
            paketSelect.value = '';
            
            if (hasPaket) {
                paketContainer.style.display = 'block';
            } else {
                paketContainer.style.display = 'none';
            }
        }
        
        // Update form action dynamically
        form.action = baseUrl + '/' + roomSelect.value;
        
        // Update hidden room name
        roomNameInput.value = selectedOption.getAttribute('data-name');
        
        // Check capacity
        const capacity = parseInt(selectedOption.getAttribute('data-capacity'));
        const participants = parseInt(participantCount.value) || 0;
        
        if (participants > capacity) {
            capacityWarning.classList.remove('d-none');
            capacityWarning.innerText = `Jumlah peserta melebihi kapasitas maksimal ruangan ini (${capacity} orang).`;
            participantCount.classList.add('is-invalid');
        } else {
            capacityWarning.classList.add('d-none');
            participantCount.classList.remove('is-invalid');
        }
        
        submitBtn.disabled = false;
    }
    
    roomSelect.addEventListener('change', validateForm);
    participantCount.addEventListener('input', validateForm);
</script>
@endsection
