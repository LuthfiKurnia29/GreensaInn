@extends('layouts.admin')

@section('title', 'Masterdata Fasilitas - GreensaInn Admin')
@section('page_title', 'Masterdata Fasilitas')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="h5 m-0" style="color: var(--primary-color);"><i class="fa-solid fa-couch me-2"></i>Daftar Fasilitas</h4>
        <a href="{{ route('admin.fasilitas.create') }}" class="btn btn-primary shadow-sm rounded-3 px-4" style="background-color: var(--primary-color); border-color: var(--primary-color);">
            <i class="fa-solid fa-plus me-2"></i> Tambah Fasilitas
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert" style="background-color: #e8f5e9; color: #2e7d32;">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="table-responsive bg-white rounded-4 shadow-sm border-0" style="padding: 24px;">
            <table class="table align-middle table-hover mb-0">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="40%">Nama Fasilitas</th>
                        <th width="30%">Stok Tersedia</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fasilitas as $item)
                    <tr>
                        <td class="fw-bold text-dark">#{{ $item->id }}</td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $item->nama_fasilitas }}</div>
                        </td>
                        <td>
                            <span class="badge {{ $item->stok_tersedia > 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2 fw-semibold shadow-sm" style="font-size: 0.85rem;">
                                {{ $item->stok_tersedia }} Unit
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.fasilitas.edit', $item->id) }}" class="btn btn-outline-primary btn-sm px-3 py-1 rounded-pill fw-bold" title="Edit">
                                    <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                </a>
                                <form action="{{ route('admin.fasilitas.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 py-1 rounded-pill fw-bold" title="Hapus">
                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <div class="mb-3"><i class="fa-solid fa-box-open fs-1 text-light"></i></div>
                            <h5 class="fw-bold text-dark mb-1">Belum ada data fasilitas</h5>
                            <p class="mb-0 small">Silakan tambah data fasilitas baru.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
