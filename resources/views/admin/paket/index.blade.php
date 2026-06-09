@extends('layouts.admin')

@section('title', 'Masterdata Paket - GreensaInn Admin')
@section('page_title', 'Masterdata Kelola Paket')

@section('styles')
<style>
    .badge-tipe {
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .action-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s ease;
        font-size: 0.8rem;
    }
    .action-btn:hover { transform: translateY(-2px); }
</style>
@endsection

@section('content')

{{-- Flash messages --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert" style="background:#e8f5e9;color:#2e7d32;">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="h5 m-0"><i class="fa-solid fa-box-open me-2" style="color:var(--primary-color)"></i>Daftar Paket Ruangan</h4>
        <p class="text-muted small mb-0 mt-1">Total: <strong>{{ $pakets->count() }}</strong> paket terdaftar</p>
    </div>
    <a href="{{ route('admin.paket.create') }}" class="btn btn-primary shadow-sm rounded-3 px-4"
       style="background-color:var(--primary-color);border-color:var(--primary-color);">
        <i class="fa-solid fa-plus me-2"></i>Tambah Paket
    </a>
</div>

{{-- Table --}}
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Ruangan</th>
                        <th>Fasilitas</th>
                        <th>Harga</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pakets as $paket)
                    <tr>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:0.95rem;">{{ $paket->nama_paket }}</div>
                            <span class="text-muted text-truncate d-inline-block" style="max-width: 250px; font-size:0.75rem;">
                                {{ $paket->deskripsi }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border badge-tipe px-2 py-1">
                                {{ $paket->ruangan->nama_ruangan ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($paket->fasilitas->count() > 0)
                                @foreach($paket->fasilitas as $fasilitas)
                                    <span class="badge bg-info text-dark rounded-pill px-2 py-1 mb-1" style="font-size:0.7rem;">
                                        {{ $fasilitas->nama_fasilitas }}
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($paket->harga_paket, 0, ',', '.') }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.paket.edit', $paket->id) }}"
                                   class="action-btn btn btn-outline-primary btn-sm" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.paket.destroy', $paket->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus paket \'{{ $paket->nama_paket }}\'?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn btn btn-outline-danger btn-sm" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="fa-solid fa-box-open fs-1 opacity-25"></i></div>
                            <h6 class="fw-bold text-dark mb-1">Belum ada data paket</h6>
                            <p class="mb-3 small">Mulai tambahkan data paket pertama Anda.</p>
                            <a href="{{ route('admin.paket.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill"
                               style="background-color:var(--primary-color);border-color:var(--primary-color);">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Sekarang
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
