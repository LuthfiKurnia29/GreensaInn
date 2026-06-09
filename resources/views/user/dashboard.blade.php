@extends('layouts.app')

@section('title', 'Pesanan Saya - GreensaInn')

@section('styles')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary-color) 0%, #1a6f85 100%);
        color: white;
        padding: 60px 0 40px;
        margin-bottom: 40px;
        border-radius: 0 0 30px 30px;
    }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    
    .payment-unpaid { background: #f8d7da; color: #721c24; }
    .payment-pending { background: #cce5ff; color: #004085; }
    .payment-verified { background: #d4edda; color: #155724; }
</style>
@endsection

@section('content')
<div class="dashboard-header">
    <div class="container text-center">
        <h1 class="font-heading mb-3">Pesanan Saya</h1>
        <p class="lead opacity-75">Pantau status pemesanan ruangan dan kelola pembayaran Anda di sini.</p>
    </div>
</div>

<div class="container mb-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        @forelse($bookings as $booking)
            <div class="col-12">
                <div class="premium-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-3 mb-3 mb-md-0">
                            <h5 class="font-heading text-primary-custom mb-1">{{ $booking->ruangan->nama_ruangan ?? 'Ruangan ' . $booking->ruangan_id }}</h5>
                            <p class="text-muted small mb-2"><i class="fa-regular fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('d F Y') }}</p>
                            <p class="text-muted small mb-0"><i class="fa-regular fa-clock me-2"></i>{{ substr($booking->waktu_mulai, 0, 5) }} - {{ substr($booking->waktu_selesai, 0, 5) }}</p>
                            @if($booking->paket_id && $booking->paket)
                                <p class="text-muted small mb-0 mt-1"><i class="fa-solid fa-box-open me-2"></i>Paket: {{ $booking->paket->nama_paket }}</p>
                            @endif
                        </div>
                        
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="mb-1"><small class="text-muted">Total Tagihan:</small></p>
                            <h6 class="mb-2 text-primary-custom fw-bold">Rp {{ number_format($booking->pembayaran->total_harga ?? 0, 0, ',', '.') }}</h6>
                            <p class="mb-1 mt-2"><small class="text-muted">Status Booking:</small></p>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>

                        @if(Auth::user()->instansi === 'umum')
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="mb-1"><small class="text-muted">Status Pembayaran:</small></p>
                            @php
                                $payStatusClass = 'unpaid';
                                $payStatusText = 'Belum Dibayar';
                                $paymentStatus = $booking->pembayaran ? $booking->pembayaran->status_pembayaran : 'unpaid';
                                if($paymentStatus === 'pending_verification') {
                                    $payStatusClass = 'pending';
                                    $payStatusText = 'Menunggu Verifikasi';
                                } elseif($paymentStatus === 'verified') {
                                    $payStatusClass = 'verified';
                                    $payStatusText = 'Lunas (Terverifikasi)';
                                }
                            @endphp
                            <span class="status-badge payment-{{ $payStatusClass }}">
                                {{ $payStatusText }}
                            </span>
                        </div>

                        <div class="col-md-3 text-md-end d-flex flex-column gap-2 align-items-md-end mt-3 mt-md-0">
                            @php
                                $isUmum = Auth::user()->instansi === 'umum';
                                $paymentStatus = $booking->pembayaran ? $booking->pembayaran->status_pembayaran : ($isUmum ? 'unpaid' : 'verified');
                            @endphp

                            @if($isUmum && $paymentStatus === 'unpaid')
                                <button type="button" class="btn btn-primary btn-sm w-100" onclick="payNow('{{ $booking->pembayaran->snap_token ?? '' }}', {{ $booking->id }})">
                                    <i class="fa-solid fa-credit-card me-1"></i> Bayar Sekarang
                                </button>
                                
                                @if($booking->status === 'pending')
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                        <i class="fa-solid fa-xmark me-1"></i> Batalkan
                                    </button>
                                @endif
                            @else
                                <button type="button" class="btn btn-outline-info btn-sm w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $booking->id }}">
                                    <i class="fa-solid fa-circle-info me-1"></i> Detail
                                </button>

                                @if(!$isUmum && $booking->status === 'pending')
                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                        <i class="fa-solid fa-xmark me-1"></i> Batalkan
                                    </button>
                                @endif
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Detail Booking Modal -->
            <div class="modal fade" id="detailModal{{ $booking->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title font-heading" id="detailModalLabel{{ $booking->id }}">Detail Pemesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Ruangan</span>
                                    <span class="fw-bold">{{ $booking->ruangan->nama_ruangan ?? 'Ruangan ' . $booking->ruangan_id }}</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Waktu</span>
                                    <span class="fw-bold">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('d M Y') }} ({{ substr($booking->waktu_mulai, 0, 5) }} - {{ substr($booking->waktu_selesai, 0, 5) }})</span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Tujuan Rapat</span>
                                    <span class="fw-bold text-end">{{ $booking->tujuan_rapat }}</span>
                                </li>
                                @if($booking->paket_id && $booking->paket)
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Paket Makanan</span>
                                    <span class="fw-bold text-end">{{ $booking->paket->nama_paket }}</span>
                                </li>
                                @endif
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Jumlah Peserta</span>
                                    <span class="fw-bold">{{ $booking->jumlah_peserta }} Orang</span>
                                </li>
                            </ul>
                            
                            @if(Auth::user()->instansi === 'umum')
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted small">Total Tagihan</span>
                                    <span class="fw-bold text-primary-custom">Rp {{ number_format($booking->pembayaran->total_harga ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span class="text-muted small">Status Pembayaran</span>
                                    <span class="badge {{ $booking->pembayaran && $booking->pembayaran->status_pembayaran === 'verified' ? 'bg-success' : 'bg-warning text-dark' }}">
                                        {{ $booking->pembayaran && $booking->pembayaran->status_pembayaran === 'verified' ? 'Lunas' : 'Menunggu Verifikasi' }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cancel Booking Modal -->
            @if($booking->status === 'pending')
            <div class="modal fade" id="cancelModal{{ $booking->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <form action="{{ route('user.dashboard.booking.cancel', $booking->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-heading text-danger" id="cancelModalLabel{{ $booking->id }}">Batalkan Pesanan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Apakah Anda yakin ingin membatalkan pesanan <strong>{{ $booking->ruangan->nama_ruangan ?? 'Ruangan' }}</strong> pada <strong>{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('d M Y') }}</strong>?</p>
                                <p class="small text-muted mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Kembali</button>
                                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        @empty
            <div class="col-12 text-center py-5">
                <i class="fa-solid fa-box-open fa-3x text-muted mb-3 opacity-50"></i>
                <h5 class="text-muted">Anda belum memiliki pesanan ruangan.</h5>
                <a href="{{ route('booking.index') }}" class="btn btn-primary mt-3">Pesan Sekarang</a>
            </div>
        @endempty
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    function payNow(token, bookingId) {
        if (!token) {
            alert('Token tidak tersedia. Silakan hubungi admin.');
            return;
        }
        window.snap.pay(token, {
            onSuccess: function(result){
                // Simulasi webhook untuk local environment
                fetch(`/user/dashboard/payment/${bookingId}/success`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    alert("Pembayaran berhasil! Sistem telah diperbarui.");
                    window.location.reload();
                });
            },
            onPending: function(result){
                alert("Menunggu pembayaran Anda!");
                window.location.reload();
            },
            onError: function(result){
                alert("Pembayaran gagal!");
            },
            onClose: function(){
                // Popup tertutup
            }
        });
    }
</script>
@endsection
