@extends('layouts.admin')

@section('title', 'Edit Fasilitas - GreensaInn Admin')
@section('page_title', 'Edit Fasilitas')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center gap-3">
        <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-outline-secondary rounded-circle shadow-sm" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h4 class="h5 m-0 fw-bold" style="color: var(--primary-color);">Form Edit Fasilitas</h4>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <p class="text-muted small mb-0">Perbarui data fasilitas yang sudah ada di sistem.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.fasilitas.update', $fasilitas->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="nama_fasilitas" class="form-label fw-bold text-dark small">NAMA FASILITAS <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0 px-3"><i class="fa-solid fa-tag text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 ps-0 fs-6 @error('nama_fasilitas') is-invalid @enderror" id="nama_fasilitas" name="nama_fasilitas" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
                        </div>
                        @error('nama_fasilitas')
                            <div class="text-danger small mt-2 fw-semibold"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stok_tersedia" class="form-label fw-bold text-dark small">STOK TERSEDIA <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0 px-3"><i class="fa-solid fa-boxes-stacked text-muted"></i></span>
                            <input type="number" class="form-control border-start-0 ps-0 fs-6 fw-bold text-primary @error('stok_tersedia') is-invalid @enderror" id="stok_tersedia" name="stok_tersedia" value="{{ old('stok_tersedia', $fasilitas->stok_tersedia) }}" min="0" required>
                            <span class="input-group-text bg-light text-muted fw-semibold">Unit</span>
                        </div>
                        @error('stok_tersedia')
                            <div class="text-danger small mt-2 fw-semibold"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="text-muted opacity-25 my-4">

                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.fasilitas.index') }}" class="btn btn-light px-4 py-2 fw-bold rounded-pill text-dark shadow-sm">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm" style="background-color: var(--primary-color); border-color: var(--primary-color);">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Update Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
