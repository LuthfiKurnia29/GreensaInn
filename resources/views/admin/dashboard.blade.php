@extends('layouts.admin')

@section('title', 'Admin Dashboard - GreensaInn')
@section('page_title', 'Dashboard Overview')

@section('styles')
<style>
    /* Custom style for stats card icons */
    .bg-icon-rooms { background-color: #e3f2fd; color: #1e88e5; }
    .bg-icon-bookings { background-color: #e8f5e9; color: #2e7d32; }
    .bg-icon-hours { background-color: #fff3e0; color: #fb8b24; }
    .bg-icon-revenue { background-color: #f3e5f5; color: #8e24aa; }

    /* Toast styling */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
    }
</style>
@endsection

@section('content')

<!-- Metric Stats Row -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold d-block mb-1">TOTAL RUANGAN</span>
                    <h3 class="mb-0 fw-bold">{{ $ruanganCount }} Ruang</h3>
                </div>
                <div class="stat-icon bg-icon-rooms">
                    <i class="fa-solid fa-door-open"></i>
                </div>
            </div>
            <!-- <div class="mt-3 small text-muted">
                <span class="text-success fw-bold"><i class="fa-solid fa-arrow-up me-1"></i>+1 baru</span> bulan ini
            </div> -->
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold d-block mb-1">TOTAL BOOKING</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['total_bookings'] }} Kali</h3>
                </div>
                <div class="stat-icon bg-icon-bookings">
                    <i class="fa-regular fa-calendar-check"></i>
                </div>
            </div>
            <!-- <div class="mt-3 small text-muted">
                <span class="text-success fw-bold"><i class="fa-solid fa-arrow-up me-1"></i>+12%</span> dibanding pekan lalu
            </div> -->
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold d-block mb-1">JAM TERSEWA</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['rented_hours'] }} Jam</h3>
                </div>
                <div class="stat-icon bg-icon-hours">
                    <i class="fa-regular fa-clock"></i>
                </div>
            </div>
            <!-- <div class="mt-3 small text-muted">
                <span class="text-danger fw-bold"><i class="fa-solid fa-arrow-down me-1"></i>-3%</span> dibanding bulan lalu
            </div> -->
        </div>
    </div>
    <!-- <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted small fw-bold d-block mb-1">TOTAL PENDAPATAN</span>
                    <h3 class="mb-0 fw-bold">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</h3>
                </div>
                <div class="stat-icon bg-icon-revenue">
                    <i class="fa-solid fa-rupiah-sign"></i>
                </div>
            </div>
            <div class="mt-3 small text-muted">
                <span class="text-success fw-bold"><i class="fa-solid fa-arrow-up me-1"></i>+18.5%</span> dari target Q2
            </div>
        </div>
    </div> -->
</div>

<!-- Recent Bookings Table -->
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="h5 m-0"><i class="fa-solid fa-list-check me-2 text-primary-custom"></i>Pemesanan Terbaru Masuk</h4>
            <div class="d-flex gap-2">
                <input type="text" class="form-control form-control-sm" placeholder="Cari pemesan..." style="width: 200px;">
                <button class="btn btn-outline-primary btn-sm"><i class="fa-solid fa-filter me-1"></i>Filter</button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>ID Reservasi</th>
                        <th>Pemesan</th>
                        <th>Ruangan</th>
                        <th>Tanggal & Jam</th>
                        <th>Agenda/Keperluan</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 180px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                    <tr id="row-{{ $booking->id }}">
                        <td class="fw-bold text-dark">BK-{{ str_pad($booking->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $booking->user->nama_lengkap ?? 'Unknown' }}</div>
                            <span class="text-muted small" style="font-size: 0.75rem;">Umum</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $booking->ruangan->nama_ruangan ?? 'Unknown' }}</div>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('l, d M Y') }}</div>
                            <span class="text-muted small" style="font-size: 0.8rem;"><i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($booking->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->waktu_selesai)->format('H:i') }}</span>
                        </td>
                        <td>
                            <p class="text-truncate mb-1 small" style="max-width: 200px;" title="{{ $booking->tujuan_rapat }}">{{ $booking->tujuan_rapat }}</p>
                            @if($booking->detailFasilitas->isNotEmpty())
                                <div class="small text-muted mt-1">
                                    <strong>Fasilitas:</strong>
                                    <ul class="mb-0 ps-3">
                                    @foreach($booking->detailFasilitas as $detail)
                                        <li>{{ $detail->fasilitas->nama_fasilitas }} ({{ $detail->stok_tersedia }})</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td class="fw-bold text-primary-custom">-</td>
                        <td>
                            @if($booking->status === 'approved')
                                <span class="badge-status success" id="badge-{{ $booking->id }}">Disetujui</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge-status warning" id="badge-{{ $booking->id }}">Menunggu Konfirmasi</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge-status bg-info text-white" id="badge-{{ $booking->id }}">Selesai</span>
                            @else
                                <span class="badge-status danger" id="badge-{{ $booking->id }}">Dibatalkan</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2" id="action-box-{{ $booking->id }}">
                                @if($booking->status === 'pending')
                                    <button class="btn btn-outline-success btn-sm px-2 py-1" onclick="approveBooking('{{ $booking->id }}')" title="Setujui Pemesanan">
                                        <i class="fa-solid fa-check me-1"></i> Setujui
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm px-2 py-1" onclick="rejectBooking('{{ $booking->id }}')" title="Tolak Pemesanan">
                                        <i class="fa-solid fa-xmark me-1"></i> Tolak
                                    </button>
                                @else
                                    <span class="text-muted small"><i class="fa-solid fa-circle-info me-1"></i>Telah Diproses</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Toast Container for Notifications -->
<div class="toast-container">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0 rounded-3 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Pemesanan berhasil disetujui!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function updateBookingStatus(id, status) {
        fetch(`/admin/peminjaman/${id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                // Update badge UI
                const badge = document.getElementById('badge-' + id);
                if (status === 'approved') {
                    badge.className = 'badge-status success';
                    badge.innerText = 'Disetujui';
                    showToast(`Reservasi BK-${id.toString().padStart(4, '0')} berhasil disetujui!`, 'success');
                } else {
                    badge.className = 'badge-status danger';
                    badge.innerText = 'Ditolak';
                    showToast(`Reservasi BK-${id.toString().padStart(4, '0')} ditolak!`, 'danger');
                }

                // Update actions column
                const actionBox = document.getElementById('action-box-' + id);
                actionBox.innerHTML = '<span class="text-muted small"><i class="fa-solid fa-circle-info me-1"></i>Telah Diproses</span>';
            } else {
                showToast(`Gagal: ${data.message}`, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(`Terjadi kesalahan sistem saat menghubungi server.`, 'danger');
        });
    }

    function approveBooking(id) {
        updateBookingStatus(id, 'approved');
    }

    function rejectBooking(id) {
        updateBookingStatus(id, 'rejected');
    }

    // Helper: trigger bootstrap toast
    function showToast(message, type) {
        const toastEl = document.getElementById('liveToast');
        const toastMsg = document.getElementById('toastMessage');
        
        toastMsg.innerText = message;
        
        // Toggle color theme
        if (type === 'success') {
            toastEl.className = 'toast align-items-center text-white bg-success border-0 rounded-3 shadow';
        } else {
            toastEl.className = 'toast align-items-center text-white bg-danger border-0 rounded-3 shadow';
        }

        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
</script>
@endsection
