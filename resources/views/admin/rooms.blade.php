@extends('layouts.admin')

@section('title', 'Kelola Ruangan - GreensaInn Admin')
@section('page_title', 'Kelola Ruang Rapat')

@section('styles')
<style>
    .room-thumb {
        width: 60px;
        height: 45px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>
@endsection

@section('content')

<!-- Header controls -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="h5 m-0"><i class="fa-solid fa-door-closed me-2 text-primary-custom"></i>Katalog Data Ruang Rapat</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoomModal">
        <i class="fa-solid fa-plus me-2"></i>Tambah Ruangan
    </button>
</div>

<!-- Table list -->
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table class="table align-middle" id="roomsTable">
                <thead>
                    <tr>
                        <th style="width: 80px;">Foto</th>
                        <th>Nama Ruangan</th>
                        <th>Tipe</th>
                        <th>Kapasitas</th>
                        <th>Harga Sewa</th>
                        <th>Dimensi (Luas)</th>
                        <th>Lantai</th>
                        <th class="text-center" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr id="room-row-{{ $room['id'] }}">
                        <td>
                            <img src="{{ $room['images'][0] }}" class="room-thumb border shadow-sm" alt="{{ $room['name'] }}">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $room['name'] }}</div>
                            <span class="text-muted small" style="font-size: 0.75rem;">ID: #{{ $room['id'] }}</span>
                        </td>
                        <td><span class="badge bg-light text-primary border px-2.5 py-1.5">{{ $room['type'] }}</span></td>
                        <td class="fw-semibold text-dark">{{ $room['capacity'] }} Pax</td>
                        <td class="fw-bold text-success">Rp {{ number_format($room['price'], 0, ',', '.') }}<span class="fw-normal text-muted fs-7">/jam</span></td>
                        <td>{{ $room['size'] }}</td>
                        <td>{{ $room['floor'] }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn btn-sm btn-outline-primary" 
                                        onclick="openEditModal({{ json_encode($room) }})" 
                                        title="Ubah Data">
                                    <i class="fa-solid fa-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" 
                                        onclick="confirmDelete('{{ $room['id'] }}', '{{ $room['name'] }}')" 
                                        title="Hapus Ruangan">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Tambah Ruangan -->
<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark" id="addRoomModalLabel"><i class="fa-solid fa-circle-plus me-2 text-primary-custom"></i>Form Tambah Ruangan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveNewRoom(event)">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">NAMA RUANGAN</label>
                            <input type="text" class="form-control p-3 border-light-subtle rounded-3" id="addName" placeholder="Contoh: Sapphire Room" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TIPE RUANG</label>
                            <select class="form-select p-3 border-light-subtle rounded-3" id="addType" required>
                                <option value="Boardroom">Boardroom</option>
                                <option value="Creative Space">Creative Space</option>
                                <option value="Seminar Hall">Seminar Hall</option>
                                <option value="Huddle Room">Huddle Room</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">KAPASITAS (PAX)</label>
                            <input type="number" class="form-control p-3 border-light-subtle rounded-3" id="addCapacity" placeholder="pax" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">HARGA SEWA PER JAM (RP)</label>
                            <input type="number" class="form-control p-3 border-light-subtle rounded-3" id="addPrice" placeholder="Rp" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">DIMENSI (LUAS)</label>
                            <input type="text" class="form-control p-3 border-light-subtle rounded-3" id="addSize" placeholder="Contoh: 45m²" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">LANTAI GEDUNG</label>
                            <select class="form-select p-3 border-light-subtle rounded-3" id="addFloor" required>
                                <option value="Lantai 1">Lantai 1</option>
                                <option value="Lantai 2">Lantai 2</option>
                                <option value="Lantai 3">Lantai 3</option>
                                <option value="Lantai 4">Lantai 4</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL PREVIEW FOTO</label>
                            <input type="url" class="form-control p-3 border-light-subtle rounded-3" id="addPhoto" value="https://images.unsplash.com/photo-1497215728101-856f4ea42174?q=80&w=600" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">DESKRIPSI RUANGAN</label>
                            <textarea class="form-control border-light-subtle rounded-3" id="addDesc" rows="3" placeholder="Tuliskan deskripsi lengkap ruangan..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2">Simpan Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Edit Ruangan -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom border-light p-4">
                <h5 class="modal-title fw-bold text-dark" id="editRoomModalLabel"><i class="fa-solid fa-pen-to-square me-2 text-primary-custom"></i>Form Edit Data Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="saveEditedRoom(event)">
                <input type="hidden" id="editId">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">NAMA RUANGAN</label>
                            <input type="text" class="form-control p-3 border-light-subtle rounded-3" id="editName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">TIPE RUANG</label>
                            <select class="form-select p-3 border-light-subtle rounded-3" id="editType" required>
                                <option value="Boardroom">Boardroom</option>
                                <option value="Creative Space">Creative Space</option>
                                <option value="Seminar Hall">Seminar Hall</option>
                                <option value="Huddle Room">Huddle Room</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">KAPASITAS (PAX)</label>
                            <input type="number" class="form-control p-3 border-light-subtle rounded-3" id="editCapacity" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">HARGA SEWA PER JAM (RP)</label>
                            <input type="number" class="form-control p-3 border-light-subtle rounded-3" id="editPrice" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">DIMENSI (LUAS)</label>
                            <input type="text" class="form-control p-3 border-light-subtle rounded-3" id="editSize" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">LANTAI GEDUNG</label>
                            <select class="form-select p-3 border-light-subtle rounded-3" id="editFloor" required>
                                <option value="Lantai 1">Lantai 1</option>
                                <option value="Lantai 2">Lantai 2</option>
                                <option value="Lantai 3">Lantai 3</option>
                                <option value="Lantai 4">Lantai 4</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold text-muted">URL PREVIEW FOTO</label>
                            <input type="url" class="form-control p-3 border-light-subtle rounded-3" id="editPhoto" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-bold text-muted">DESKRIPSI RUANGAN</label>
                            <textarea class="form-control border-light-subtle rounded-3" id="editDesc" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top border-light p-4">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 py-2">Update Ruangan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let editModal = null;
    let addModal = null;

    document.addEventListener("DOMContentLoaded", () => {
        editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
        addModal = new bootstrap.Modal(document.getElementById('addRoomModal'));
    });

    // Populate and open edit modal
    function openEditModal(room) {
        document.getElementById('editId').value = room.id;
        document.getElementById('editName').value = room.name;
        document.getElementById('editType').value = room.type;
        document.getElementById('editCapacity').value = room.capacity;
        document.getElementById('editPrice').value = room.price;
        document.getElementById('editSize').value = room.size;
        document.getElementById('editFloor').value = room.floor;
        document.getElementById('editPhoto').value = room.images[0];
        document.getElementById('editDesc').value = room.description;
        
        editModal.show();
    }

    // Save added room simulation
    function saveNewRoom(event) {
        event.preventDefault();
        
        const name = document.getElementById('addName').value;
        const type = document.getElementById('addType').value;
        const capacity = document.getElementById('addCapacity').value;
        const price = document.getElementById('addPrice').value;
        const size = document.getElementById('addSize').value;
        const floor = document.getElementById('addFloor').value;
        const photo = document.getElementById('addPhoto').value;
        
        // Generate new dummy ID
        const newId = Math.floor(Math.random() * 1000) + 10;
        
        // Format price to IDR
        const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price).replace("Rp", "Rp ");

        // Append dynamic row to table
        const tbody = document.querySelector("#roomsTable tbody");
        const newRow = document.createElement("tr");
        newRow.id = `room-row-${newId}`;
        newRow.innerHTML = `
            <td>
                <img src="${photo}" class="room-thumb border shadow-sm" alt="${name}">
            </td>
            <td>
                <div class="fw-bold text-dark">${name}</div>
                <span class="text-muted small" style="font-size: 0.75rem;">ID: #${newId}</span>
            </td>
            <td><span class="badge bg-light text-primary border px-2.5 py-1.5">${type}</span></td>
            <td class="fw-semibold text-dark">${capacity} Pax</td>
            <td class="fw-bold text-success">${formattedPrice}/jam</td>
            <td>${size}</td>
            <td>${floor}</td>
            <td>
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-sm btn-outline-primary" onclick="alert('Ini simulasi edit ruangan baru')" title="Ubah Data">
                        <i class="fa-solid fa-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger" onclick="confirmDelete('${newId}', '${name}')" title="Hapus Ruangan">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        tbody.appendChild(newRow);
        
        // Reset form and close modal
        event.target.reset();
        addModal.hide();

        alert(`Ruangan "${name}" berhasil ditambahkan secara fiktif ke dalam tabel katalog.`);
    }

    // Save edited room simulation
    function saveEditedRoom(event) {
        event.preventDefault();
        
        const id = document.getElementById('editId').value;
        const name = document.getElementById('editName').value;
        const type = document.getElementById('editType').value;
        const capacity = document.getElementById('editCapacity').value;
        const price = document.getElementById('editPrice').value;
        const size = document.getElementById('editSize').value;
        const floor = document.getElementById('editFloor').value;
        const photo = document.getElementById('editPhoto').value;

        // Find row and update cells
        const row = document.getElementById(`room-row-${id}`);
        if (row) {
            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price).replace("Rp", "Rp ");
            
            row.cells[0].querySelector('img').src = photo;
            row.cells[1].querySelector('.fw-bold').innerText = name;
            row.cells[2].querySelector('.badge').innerText = type;
            row.cells[3].innerText = `${capacity} Pax`;
            row.cells[4].innerText = `${formattedPrice}/jam`;
            row.cells[5].innerText = size;
            row.cells[6].innerText = floor;
        }

        editModal.hide();
        alert(`Data ruangan "${name}" berhasil diperbarui.`);
    }

    // Confirm and animate row deletion simulation
    function confirmDelete(id, name) {
        if (confirm(`Apakah Anda yakin ingin menghapus ruangan "${name}" dari katalog?`)) {
            const row = document.getElementById(`room-row-${id}`);
            if (row) {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(50px)';
                setTimeout(() => {
                    row.remove();
                }, 500);
            }
        }
    }
</script>
@endsection
