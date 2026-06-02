@extends('layouts.admin')

@section('title', 'Peninjauan Booking - GreensaInn Admin')
@section('page_title', 'Peninjauan Permintaan Booking')

@section('styles')
<style>
    /* Styling for financial badge block */
    .finance-badge-box {
        background-color: var(--light-bg);
        border: 1px solid rgba(15, 76, 92, 0.05);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 0.85rem;
    }
    
    .proof-img-preview {
        max-width: 100%;
        max-height: 380px;
        object-fit: contain;
        border-radius: 12px;
        border: 1px solid #dee2e6;
    }

    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1050;
    }
</style>
@endsection

@section('content')

<!-- Header Explanation alert -->
<div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 p-4 d-flex gap-3 align-items-start" role="alert">
    <i class="fa-solid fa-circle-info fs-4 text-info mt-1"></i>
    <div>
        <h5 class="alert-heading fw-bold">Aturan Peninjauan & Finansial</h5>
        <p class="mb-0 small">
            Sistem secara otomatis mengklasifikasikan booking. Untuk pihak <strong>Internal UINSA</strong>, peminjaman bersifat <strong>Bebas Biaya (Gratis)</strong> dan tidak memerlukan uang muka. Untuk pihak <strong>Eksternal</strong>, peminjaman dikenakan tarif normal dan wajib melakukan <strong>pembayaran Uang Muka (DP) minimal 50%</strong> untuk validasi reservasi ruang rapat.
        </p>
    </div>
</div>

<!-- Table Container -->
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="h5 m-0"><i class="fa-solid fa-file-shield me-2 text-primary-custom"></i>Daftar Pengajuan Menunggu Evaluasi</h4>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: 180px;">
                    <option value="all">Semua Tipe Pemohon</option>
                    <option value="internal">Internal UINSA</option>
                    <option value="external">Eksternal</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th style="width: 100px;">ID Pengajuan</th>
                        <th>Pemohon & Instansi</th>
                        <th>Ruangan & Waktu</th>
                        <th>Tipe Pemohon</th>
                        <th>Rincian Biaya & DP</th>
                        <th>Status Peninjauan</th>
                        <th class="text-center" style="width: 200px;">Aksi Evaluasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reviews as $review)
                    <tr id="review-row-{{ $review->id }}">
                        <td class="fw-bold text-dark">REV-{{ str_pad($review->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $review->user->nama_lengkap ?? 'Unknown' }}</div>
                            <span class="text-muted small" style="font-size: 0.8rem;"><i class="fa-solid fa-id-card-clip me-1"></i>{{ $review->user->role ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $review->ruangan->nama_ruangan ?? 'Unknown' }}</div>
                            <span class="text-muted small d-block" style="font-size: 0.78rem;">
                                <i class="fa-regular fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($review->tanggal_mulai)->translatedFormat('l, d M Y') }}
                            </span>
                            <span class="text-muted small d-block" style="font-size: 0.78rem;">
                                <i class="fa-regular fa-clock me-1"></i>{{ \Carbon\Carbon::parse($review->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($review->waktu_selesai)->format('H:i') }}
                            </span>
                        </td>
                        <td>
                            @if(($review->user->instansi ?? 'umum') === 'internal')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1.5">
                                    <i class="fa-solid fa-graduation-cap me-1"></i>Internal UINSA
                                </span>
                            @else
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5">
                                    <i class="fa-solid fa-building me-1"></i>Eksternal
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="finance-badge-box">
                                @if(($review->user->instansi ?? 'umum') === 'internal')
                                    <div class="text-success fw-bold"><i class="fa-solid fa-tags me-1"></i>Bebas Biaya (Rp 0)</div>
                                    <div class="text-muted small mt-0.5">Wajib DP: Rp 0</div>
                                @else
                                    <div class="text-dark fw-bold">Total: Rp 0 (Disimulasikan)</div>
                                    <div class="text-danger small fw-semibold mt-0.5"><i class="fa-solid fa-circle-exclamation me-1"></i>Min. DP: Rp 0</div>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($review->status === 'approved')
                                <span class="badge-status success" id="badge-{{ $review->id }}">Disetujui</span>
                            @elseif($review->status === 'rejected')
                                <span class="badge-status danger" id="badge-{{ $review->id }}">Ditolak</span>
                            @elseif($review->status === 'completed')
                                <span class="badge-status bg-info text-white" id="badge-{{ $review->id }}">Selesai</span>
                            @else
                                <span class="badge-status warning" id="badge-{{ $review->id }}">Menunggu Review</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2" id="action-box-{{ $review->id }}">
                                <span class="text-muted small"><i class="fa-solid fa-circle-info me-1"></i>Telah Diproses</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Verifikasi Bukti Pembayaran DP -->
<div class="modal fade" id="paymentVerificationModal" tabindex="-1" aria-labelledby="paymentVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark" id="paymentVerificationModalLabel"><i class="fa-solid fa-clipboard-check me-2 text-primary-custom"></i>Verifikasi Bukti Transfer DP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="premium-card bg-light p-3 text-start mb-4 border-0">
                    <div class="row g-2 small">
                        <div class="col-5 text-muted">ID Booking:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalReviewId">-</div>
                        <div class="col-5 text-muted">Nama Pemesan:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalReviewBooker">-</div>
                        <div class="col-5 text-muted">Ruang Rapat:</div>
                        <div class="col-7 fw-bold text-dark text-end" id="modalReviewRoom">-</div>
                        <hr class="my-2 border-secondary opacity-10">
                        <div class="col-5 text-muted">Total Tarif Sewa:</div>
                        <div class="col-7 fw-semibold text-dark text-end" id="modalReviewTotal">-</div>
                        <div class="col-5 fw-bold text-danger">Minimal Uang Muka (DP):</div>
                        <div class="col-7 fw-extrabold text-danger text-end" id="modalReviewMinDP">-</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted d-block mb-2">DOKUMEN BUKTI TRANSFER</label>
                    <img src="" id="modalReviewProofImg" class="proof-img-preview shadow-sm" alt="Bukti Transfer">
                </div>

                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-lg py-3" onclick="confirmPayment()"><i class="fa-solid fa-circle-check me-2"></i>Konfirmasi Pembayaran DP Lunas</button>
                    <button class="btn btn-light py-2.5" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container for Notifications -->
<div class="toast-container">
    <div id="liveToast" class="toast align-items-center text-white bg-success border-0 rounded-3 shadow" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="toastMessage">
                Evaluasi booking berhasil diproses!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let verificationModal = null;
    let activeReviewId = null;

    document.addEventListener("DOMContentLoaded", () => {
        verificationModal = new bootstrap.Modal(document.getElementById('paymentVerificationModal'));
    });

    // Approve internal booking (Rp 0 cost UINSA)
    function approveInternal(id) {
        // Update badge
        const badge = document.getElementById('badge-' + id);
        badge.className = 'badge-status success';
        badge.innerText = 'Disetujui (Internal)';

        // Update actions
        const actionBox = document.getElementById('action-box-' + id);
        actionBox.innerHTML = '<span class="text-muted small"><i class="fa-solid fa-circle-check text-success me-1"></i>Disetujui (Gratis)</span>';

        showToast(`Reservasi ${id} (Internal UINSA) telah disetujui tanpa biaya sewa.`, 'success');
    }

    // Reject booking
    function rejectBooking(id) {
        if (confirm(`Apakah Anda yakin ingin menolak permohonan booking ${id}?`)) {
            // Update badge
            const badge = document.getElementById('badge-' + id);
            badge.className = 'badge-status danger';
            badge.innerText = 'Ditolak';

            // Update actions
            const actionBox = document.getElementById('action-box-' + id);
            actionBox.innerHTML = '<span class="text-muted small text-danger"><i class="fa-solid fa-ban me-1"></i>Reservasi Ditolak</span>';

            showToast(`Reservasi ${id} telah ditolak.`, 'danger');
        }
    }

    // Send DP invoice email simulation
    function sendInvoice(id) {
        // Update status badge
        const badge = document.getElementById('badge-' + id);
        badge.className = 'badge-status warning bg-primary-subtle text-primary border border-primary-subtle';
        badge.innerText = 'Menunggu Verifikasi DP';

        // Update actions
        const actionBox = document.getElementById('action-box-' + id);
        actionBox.innerHTML = `
            <button class="btn btn-primary btn-sm px-2.5 py-1.5" onclick="openVerificationModal({id:'${id}', booker:'Riana Dewanti', room:'Emerald Executive Boardroom', price:450000, proof_img:'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?q=80&w=600'})">
                <i class="fa-solid fa-file-invoice-dollar me-1"></i> Verifikasi DP
            </button>
            <button class="btn btn-outline-danger btn-sm px-2.5 py-1.5" onclick="rejectBooking('${id}')">
                <i class="fa-solid fa-ban"></i>
            </button>
        `;

        showToast(`Tagihan invoice DP telah terkirim ke pemesan ${id}.`, 'success');
    }

    // Open validation modal pre-filled
    function openVerificationModal(review) {
        activeReviewId = review.id;
        
        const minDp = review.price / 2;

        document.getElementById('modalReviewId').innerText = review.id;
        document.getElementById('modalReviewBooker').innerText = review.booker;
        document.getElementById('modalReviewRoom').innerText = review.room;
        document.getElementById('modalReviewTotal').innerText = formatRupiah(review.price);
        document.getElementById('modalReviewMinDP').innerText = formatRupiah(minDp);
        
        // Load transfer receipt proof image (mock)
        const proofImg = review.proof_img || 'https://images.unsplash.com/photo-1554415707-6e8cfc93fe23?q=80&w=600';
        document.getElementById('modalReviewProofImg').src = proofImg;

        verificationModal.show();
    }

    // Confirm DP payment
    function confirmPayment() {
        if (activeReviewId) {
            // Update badge
            const badge = document.getElementById('badge-' + activeReviewId);
            badge.className = 'badge-status success';
            badge.innerText = 'DP Lunas & Disetujui';

            // Update actions
            const actionBox = document.getElementById('action-box-' + activeReviewId);
            actionBox.innerHTML = '<span class="text-muted small text-success"><i class="fa-solid fa-circle-check me-1"></i>DP Lunas & Disetujui</span>';

            verificationModal.hide();
            showToast(`Uang Muka (DP) untuk ${activeReviewId} terkonfirmasi Lunas. Reservasi disetujui.`, 'success');
        }
    }

    // Helper: format currency
    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\n))/g, ".");
    }

    // Helper: trigger bootstrap toast
    function showToast(message, type) {
        const toastEl = document.getElementById('liveToast');
        const toastMsg = document.getElementById('toastMessage');
        
        toastMsg.innerText = message;
        
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
