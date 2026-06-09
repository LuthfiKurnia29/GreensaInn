@extends('layouts.admin')

@section('title', 'Edit Ruangan - GreensaInn Admin')
@section('page_title', 'Edit Data Ruangan')

@section('styles')
<style>
    .upload-area {
        border: 2px dashed #c8d8dc;
        border-radius: 14px;
        background: #f7fbfc;
        padding: 30px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .upload-area:hover, .upload-area.drag-over {
        border-color: var(--primary-color);
        background: var(--primary-light);
    }
    .upload-area input[type="file"] { display: none; }
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 16px;
    }
    .preview-item {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 4/3;
        background: #eee;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    .preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .preview-item .remove-btn {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: rgba(220,53,69,0.88);
        color: white;
        border: none;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .preview-item .remove-btn:hover { background: #dc3545; transform: scale(1.1); }
    .existing-photo {
        position: relative;
        border-radius: 10px;
        overflow: hidden;
        aspect-ratio: 4/3;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .existing-photo img { width: 100%; height: 100%; object-fit: cover; }
    .existing-photo .delete-form {
        position: absolute;
        top: 4px;
        right: 4px;
    }
    .section-divider {
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1px;
        color: #9ca3af;
        text-transform: uppercase;
        border-bottom: 1px solid #f0f2f4;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert" style="background:#e8f5e9;color:#2e7d32;">
    <i class="fa-solid fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Back + Header --}}
<div class="row mb-4">
    <div class="col-12 d-flex align-items-center gap-3">
        <a href="{{ route('admin.ruangan.index') }}" class="btn btn-outline-secondary rounded-circle shadow-sm"
           style="width:42px;height:42px;display:flex;align-items:center;justify-content:center;transition:all 0.3s ease;">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h4 class="h5 m-0 fw-bold" style="color:var(--primary-color);">Edit: {{ $ruangan->nama_ruangan }}</h4>
            <p class="text-muted small mb-0">Perbarui data dan kelola foto ruangan.</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.ruangan.update', $ruangan->id) }}" method="POST" enctype="multipart/form-data">
@csrf
@method('PUT')

<div class="row g-4">
    {{-- Kolom Kiri --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-body p-4">
                <div class="section-divider">Informasi Utama</div>

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="nama_ruangan" class="form-label fw-bold text-dark small">NAMA RUANGAN <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-door-open text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 @error('nama_ruangan') is-invalid @enderror"
                                   id="nama_ruangan" name="nama_ruangan"
                                   value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}" required>
                        </div>
                        @error('nama_ruangan')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="tipe_ruangan" class="form-label fw-bold text-dark small">TIPE RUANGAN <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm @error('tipe_ruangan') is-invalid @enderror"
                                id="tipe_ruangan" name="tipe_ruangan" required>
                            @foreach(['Seminar Hall','Training Room','Meeting Room'] as $tipe)
                                <option value="{{ $tipe }}" {{ old('tipe_ruangan', $ruangan->tipe_ruangan) === $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                            @endforeach
                        </select>
                        @error('tipe_ruangan')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="kapasitas" class="form-label fw-bold text-dark small">KAPASITAS (PAX) <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-users text-muted"></i></span>
                            <input type="number" class="form-control border-start-0 @error('kapasitas') is-invalid @enderror"
                                   id="kapasitas" name="kapasitas"
                                   value="{{ old('kapasitas', $ruangan->kapasitas) }}" min="1" required>
                            <span class="input-group-text bg-light text-muted fw-semibold">Pax</span>
                        </div>
                        @error('kapasitas')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="harga_per_jam" class="form-label fw-bold text-dark small">HARGA PER JAM <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0 fw-semibold text-muted">Rp</span>
                            <input type="number" class="form-control border-start-0 @error('harga_per_jam') is-invalid @enderror"
                                   id="harga_per_jam" name="harga_per_jam"
                                   value="{{ old('harga_per_jam', $ruangan->harga_per_jam) }}" min="0" required>
                        </div>
                        @error('harga_per_jam')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="lokasi_ruangan" class="form-label fw-bold text-dark small">LOKASI / LANTAI <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm rounded-3">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-location-dot text-muted"></i></span>
                            <input type="text" class="form-control border-start-0 @error('lokasi_ruangan') is-invalid @enderror"
                                   id="lokasi_ruangan" name="lokasi_ruangan"
                                   value="{{ old('lokasi_ruangan', $ruangan->lokasi_ruangan) }}" required>
                        </div>
                        @error('lokasi_ruangan')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label for="deskripsi" class="form-label fw-bold text-dark small">DESKRIPSI RUANGAN <span class="text-danger">*</span></label>
                        <textarea class="form-control shadow-sm @error('deskripsi') is-invalid @enderror"
                                  id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi', $ruangan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <div class="text-danger small mt-1"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4 opacity-10">

                {{-- Upload foto baru --}}
                <div class="section-divider">Tambah Foto Baru</div>
                <div class="upload-area" id="uploadArea" onclick="document.getElementById('fotos').click()">
                    <i class="fa-solid fa-cloud-arrow-up fa-2x mb-3" style="color:var(--primary-color);opacity:0.7;"></i>
                    <p class="fw-semibold text-dark mb-1">Klik atau seret foto baru ke sini</p>
                    <p class="text-muted small mb-0">Format: JPG, PNG, WEBP — Maks. 5MB per foto</p>
                    <input type="file" id="fotos" name="fotos[]" multiple accept="image/*" onchange="previewFotos(this)">
                </div>
                @error('fotos.*')
                    <div class="text-danger small mt-2"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
                <div class="preview-grid" id="previewGrid"></div>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <div class="section-divider">Status Ketersediaan</div>
                <div class="d-flex flex-column gap-3">
                    @foreach(['tersedia' => ['label' => 'Tersedia', 'icon' => 'circle-check', 'color' => 'success', 'desc' => 'Ruangan aktif dan bisa dipesan'],
                              'kosong'   => ['label' => 'Kosong / Tidak Aktif', 'icon' => 'circle-xmark', 'color' => 'warning', 'desc' => 'Ruangan tidak tersedia untuk booking']] as $val => $opt)
                    <label class="d-flex align-items-start gap-3 p-3 border rounded-3"
                           style="cursor:pointer;transition:all 0.2s;"
                           onmouseover="this.style.borderColor='var(--primary-color)'" onmouseout="this.style.borderColor=''">
                        <input type="radio" name="status_tersedia" value="{{ $val }}"
                               class="mt-1 form-check-input"
                               {{ old('status_tersedia', $ruangan->status_tersedia) === $val ? 'checked' : '' }}>
                        <div>
                            <div class="fw-bold text-dark small">
                                <i class="fa-solid fa-{{ $opt['icon'] }} text-{{ $opt['color'] }} me-1"></i>
                                {{ $opt['label'] }}
                            </div>
                            <div class="text-muted" style="font-size:0.75rem;">{{ $opt['desc'] }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('status_tersedia')
                    <div class="text-danger small mt-2"><i class="fa-solid fa-circle-exclamation me-1"></i>{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4 d-flex flex-column gap-3">
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm"
                        style="background-color:var(--primary-color);border-color:var(--primary-color);">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Update Ruangan
                </button>
                <a href="{{ route('admin.ruangan.index') }}" class="btn btn-light w-100 py-2 fw-semibold rounded-3 text-dark">
                    <i class="fa-solid fa-xmark me-2"></i>Batal
                </a>
            </div>
        </div>

        {{-- Info ruangan --}}
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-body p-4">
                <div class="section-divider">Info Record</div>
                <div class="d-flex flex-column gap-2 small text-muted">
                    <div><i class="fa-solid fa-hashtag me-2 text-primary"></i>ID: <strong class="text-dark">{{ $ruangan->id }}</strong></div>
                    <div><i class="fa-regular fa-calendar-plus me-2 text-primary"></i>Dibuat: <strong class="text-dark">{{ $ruangan->created_at->format('d M Y') }}</strong></div>
                    <div><i class="fa-regular fa-calendar-check me-2 text-primary"></i>Diperbarui: <strong class="text-dark">{{ $ruangan->updated_at->format('d M Y, H:i') }}</strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
</form>

{{-- ============================================================ --}}
{{-- Foto Tersimpan — HARUS di luar form update agar tidak nested --}}
{{-- ============================================================ --}}
<div class="row mt-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="section-divider">
                    Foto Tersimpan
                    <span class="badge bg-primary ms-2" style="font-size:0.65rem;background-color:var(--primary-color)!important;">
                        {{ $ruangan->fotoRuangan->count() }} foto
                    </span>
                </div>

                @if($ruangan->fotoRuangan->isNotEmpty())
                <div class="preview-grid">
                    @foreach($ruangan->fotoRuangan as $foto)
                    <div class="existing-photo">
                        <img src="{{ asset('storage/' . $foto->file_foto) }}" alt="Foto Ruangan">
                        <div class="delete-form">
                            <form action="{{ route('admin.foto-ruangan.destroy', $foto->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="remove-btn" title="Hapus foto ini">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted rounded-3" style="background:#f7fbfc;border:1px dashed #c8d8dc;">
                    <i class="fa-regular fa-image fa-2x mb-2 opacity-50"></i>
                    <p class="small mb-0">Belum ada foto tersimpan untuk ruangan ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let selectedFiles = [];

    function previewFotos(input) {
        const grid = document.getElementById('previewGrid');
        const newFiles = Array.from(input.files);

        newFiles.forEach((file) => {
            // Validasi ukuran di sisi klien (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert(`File "${file.name}" terlalu besar. Maksimal 5MB per foto.`);
                return;
            }
            const reader = new FileReader();
            reader.onload = (e) => {
                selectedFiles.push(file);
                const item = document.createElement('div');
                item.className = 'preview-item';
                item.innerHTML = `
                    <img src="${e.target.result}" alt="preview">
                    <button type="button" class="remove-btn" onclick="removePreview(this)">
                        <i class="fa-solid fa-xmark"></i>
                    </button>`;
                grid.appendChild(item);
                rebuildFileInput();
            };
            reader.readAsDataURL(file);
        });
        input.value = '';
    }

    function removePreview(btn) {
        const items = document.querySelectorAll('#previewGrid .preview-item');
        const idx = Array.from(items).indexOf(btn.closest('.preview-item'));
        if (idx > -1) selectedFiles.splice(idx, 1);
        btn.closest('.preview-item').remove();
        rebuildFileInput();
    }

    function rebuildFileInput() {
        const input = document.getElementById('fotos');
        if (selectedFiles.length === 0) {
            input.value = '';
            return;
        }
        try {
            const dt = new DataTransfer();
            selectedFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
        } catch(e) {
            // fallback: browser tidak support DataTransfer assignment
        }
    }

    const uploadArea = document.getElementById('uploadArea');
    uploadArea.addEventListener('dragover', (e) => { e.preventDefault(); uploadArea.classList.add('drag-over'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('drag-over'));
    uploadArea.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        previewFotos({ files: e.dataTransfer.files, value: '' });
    });
</script>
@endsection
