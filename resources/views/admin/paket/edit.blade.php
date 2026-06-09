@extends('layouts.admin')

@section('title', 'Edit Paket - GreensaInn Admin')
@section('page_title', 'Edit Paket')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.paket.index') }}" class="btn btn-light btn-sm rounded-circle me-3" style="width:35px;height:35px;display:inline-flex;align-items:center;justify-content:center;">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                    <h5 class="m-0 fw-bold">Informasi Paket</h5>
                </div>
            </div>
            
            <div class="card-body p-4">
                @if($errors->any())
                <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>
                        <strong class="mb-0">Terdapat kesalahan pengisian:</strong>
                    </div>
                    <ul class="mb-0 small ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.paket.update', $paket->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Pilih Ruangan <span class="text-danger">*</span></label>
                        <select name="ruangan_id" class="form-select border-light-subtle shadow-sm @error('ruangan_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($ruangans as $ruangan)
                                <option value="{{ $ruangan->id }}" {{ (old('ruangan_id') ?? $paket->ruangan_id) == $ruangan->id ? 'selected' : '' }}>
                                    {{ $ruangan->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                        @error('ruangan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Nama Paket <span class="text-danger">*</span></label>
                        <input type="text" name="nama_paket" class="form-control border-light-subtle shadow-sm @error('nama_paket') is-invalid @enderror" 
                               value="{{ old('nama_paket', $paket->nama_paket) }}" placeholder="Contoh: Paket Meeting Half-day" required>
                        @error('nama_paket') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Harga Paket (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-light-subtle">Rp</span>
                            <input type="number" name="harga_paket" class="form-control border-light-subtle @error('harga_paket') is-invalid @enderror" 
                                   value="{{ old('harga_paket', $paket->harga_paket) }}" placeholder="Contoh: 500000" required>
                        </div>
                        @error('harga_paket') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Deskripsi Paket <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" rows="4" class="form-control border-light-subtle shadow-sm @error('deskripsi') is-invalid @enderror" 
                                  placeholder="Tuliskan deskripsi mengenai paket ini..." required>{{ old('deskripsi', $paket->deskripsi) }}</textarea>
                        @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-dark small">Pilih Fasilitas (Opsional)</label>
                        <div class="row">
                            @php
                                $selectedFasilitas = old('fasilitas', $paket->fasilitas->pluck('id')->toArray());
                            @endphp
                            @foreach($fasilitas as $item)
                            <div class="col-md-6 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="fasilitas[]" value="{{ $item->id }}" id="fasilitas_{{ $item->id }}"
                                        {{ in_array($item->id, $selectedFasilitas) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="fasilitas_{{ $item->id }}">
                                        {{ $item->nama_fasilitas }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('fasilitas') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 pt-3 border-top border-light-subtle">
                        <a href="{{ route('admin.paket.index') }}" class="btn btn-light px-4 rounded-3 shadow-sm">Batal</a>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm" style="background-color:var(--primary-color);border-color:var(--primary-color);">
                            <i class="fa-solid fa-save me-2"></i>Update Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
