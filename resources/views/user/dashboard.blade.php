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
                        </div>
                        
                        <div class="col-md-3 mb-3 mb-md-0">
                            <p class="mb-1"><small class="text-muted">Total Tagihan:</small></p>
                            <h6 class="mb-2 text-primary-custom fw-bold">Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}</h6>
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
                                if($booking->status_pembayaran === 'pending_verification') {
                                    $payStatusClass = 'pending';
                                    $payStatusText = 'Menunggu Verifikasi';
                                } elseif($booking->status_pembayaran === 'verified') {
                                    $payStatusClass = 'verified';
                                    $payStatusText = 'Lunas (Terverifikasi)';
                                }
                            @endphp
                            <span class="status-badge payment-{{ $payStatusClass }}">
                                {{ $payStatusText }}
                            </span>
                        </div>

                        <div class="col-md-3 text-md-end d-flex flex-column gap-2 align-items-md-end mt-3 mt-md-0">
                            @if(Auth::user()->instansi === 'umum')
                                @if($booking->status_pembayaran === 'unpaid' || $booking->status_pembayaran === 'pending_verification')
                                    @if($booking->snap_token)
                                        <button type="button" class="btn btn-primary btn-sm w-100" onclick="payNow('{{ $booking->snap_token }}')">
                                            <i class="fa-solid fa-credit-card me-1"></i> Bayar Sekarang
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#paymentModal{{ $booking->id }}">
                                            <i class="fa-solid fa-upload me-1"></i> Unggah Bukti
                                        </button>
                                    @endif
                                @elseif($booking->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $booking->bukti_pembayaran) }}" target="_blank" class="btn btn-outline-success btn-sm w-100">
                                        <i class="fa-solid fa-eye me-1"></i> Lihat Bukti
                                    </a>
                                @endif
                            @endif

                            @if($booking->status === 'pending')
                                <button type="button" class="btn btn-outline-danger btn-sm w-100 mt-2" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $booking->id }}">
                                    <i class="fa-solid fa-xmark me-1"></i> Batalkan
                                </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upload Payment Modal -->
            @if(Auth::user()->instansi === 'umum' && in_array($booking->status_pembayaran, ['unpaid', 'pending_verification']))
            <div class="modal fade" id="paymentModal{{ $booking->id }}" tabindex="-1" aria-labelledby="paymentModalLabel{{ $booking->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" style="border-radius: 16px; border: none;">
                        <form action="{{ route('user.dashboard.payment', $booking->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-header border-0 pb-0">
                                <h5 class="modal-title font-heading" id="paymentModalLabel{{ $booking->id }}">Unggah Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p class="small text-muted mb-4">Silakan unggah bukti transfer/pembayaran Anda (maksimal 2MB, format JPG/PNG) untuk pesanan <strong>{{ $booking->ruangan->nama_ruangan ?? 'Ruangan' }}</strong> pada <strong>{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->translatedFormat('d M Y') }}</strong>.</p>
                                
                                <div class="mb-3">
                                    <label for="bukti_pembayaran_{{ $booking->id }}" class="form-label fw-bold small">Pilih File Bukti Pembayaran <span class="text-danger">*</span></label>
                                    <input class="form-control" type="file" id="bukti_pembayaran_{{ $booking->id }}" name="bukti_pembayaran" accept="image/jpeg,image/png,image/jpg" required>
                                </div>
                                
                                @if($booking->bukti_pembayaran)
                                    <div class="alert alert-info py-2 small mb-0">
                                        <i class="fa-solid fa-circle-info me-1"></i> Anda sudah mengunggah bukti sebelumnya. Mengunggah file baru akan menggantikan file lama.
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer border-0 pt-0">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan & Unggah</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

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
    function payNow(token) {
        if (!token) {
            alert('Token tidak tersedia. Silakan hubungi admin.');
            return;
        }
        window.snap.pay(token, {
            onSuccess: function(result){
                alert("Pembayaran berhasil! Silakan tunggu verifikasi admin.");
                window.location.reload();
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
