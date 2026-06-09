@extends('layouts.admin')

@section('title', 'Masterdata Ruangan - GreensaInn Admin')
@section('page_title', 'Masterdata Kelola Ruangan')

@section('styles')
<style>
    .room-thumb {
        width: 64px;
        height: 48px;
        object-fit: cover;
        border-radius: 10px;
    }
    .room-thumb-placeholder {
        width: 64px;
        height: 48px;
        border-radius: 10px;
        background: var(--primary-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 1.2rem;
    }
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
        <h4 class="h5 m-0"><i class="fa-solid fa-door-open me-2" style="color:var(--primary-color)"></i>Daftar Ruang Rapat</h4>
        <p class="text-muted small mb-0 mt-1">Total: <strong>{{ $ruangans->count() }}</strong> ruangan terdaftar</p>
    </div>
    <a href="{{ route('admin.ruangan.create') }}" class="btn btn-primary shadow-sm rounded-3 px-4"
       style="background-color:var(--primary-color);border-color:var(--primary-color);">
        <i class="fa-solid fa-plus me-2"></i>Tambah Ruangan
    </a>
</div>

{{-- Table --}}
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:80px;">Foto</th>
                        <th>Nama Ruangan</th>
                        <th>Tipe</th>
                        <th>Kapasitas</th>
                        <th>Harga / Jam</th>
                        <th>Status</th>
                        <th class="text-center" style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ruangans as $ruangan)
                    <tr>
                        <td>
                            @if($ruangan->fotoRuangan->isNotEmpty())
                                <img src="{{ asset('storage/' . $ruangan->fotoRuangan->first()->file_foto) }}"
                                     class="room-thumb border shadow-sm"
                                     alt="{{ $ruangan->nama_ruangan }}">
                            @else
                                <div class="room-thumb-placeholder border">
                                    <i class="fa-regular fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size:0.95rem;">{{ $ruangan->nama_ruangan }}</div>
                            <span class="text-muted" style="font-size:0.75rem;">
                                <i class="fa-solid fa-location-dot me-1"></i>{{ $ruangan->lokasi_ruangan }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border badge-tipe px-2 py-1">
                                {{ $ruangan->tipe_ruangan ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-semibold text-dark">{{ $ruangan->kapasitas }} Pax</td>
                        <td class="fw-bold text-success">
                            Rp {{ number_format($ruangan->harga_per_jam, 0, ',', '.') }}
                            <span class="fw-normal text-muted" style="font-size:0.78rem;">/jam</span>
                        </td>
                        <td>
                            @if($ruangan->status_tersedia === 'tersedia')
                                <span class="badge bg-success rounded-pill px-3">Tersedia</span>
                            @else
                                <span class="badge bg-warning text-dark rounded-pill px-3">Kosong</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.ruangan.edit', $ruangan->id) }}"
                                   class="action-btn btn btn-outline-primary btn-sm" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form action="{{ route('admin.ruangan.destroy', $ruangan->id) }}" method="POST"
                                      onsubmit="return confirm('Hapus ruangan \'{{ $ruangan->nama_ruangan }}\' beserta semua fotonya?')">
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
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="fa-solid fa-door-closed fs-1 opacity-25"></i></div>
                            <h6 class="fw-bold text-dark mb-1">Belum ada data ruangan</h6>
                            <p class="mb-3 small">Mulai tambahkan data ruangan pertama Anda.</p>
                            <a href="{{ route('admin.ruangan.create') }}" class="btn btn-primary btn-sm px-4 rounded-pill"
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
