@extends('layouts.main')

@section('title', 'Daftar Tahap PPDB')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Tahap PPDB</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createStageModal">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Tahap
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Tahap</th>
                                        <th>Deskripsi</th>
                                        <th>Mulai</th>
                                        <th>Selesai</th>
                                        <th>Urutan</th>
                                        <th>Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($stages as $stage)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $stage->name }}</td>
                                            <td>{{ Str::limit($stage->description, 50) }}</td>
                                            <td>{{ $stage->start_date->isoFormat('D MMMM Y') }}</td>
                                            <td>{{ $stage->end_date->isoFormat('D MMMM Y') }}</td>
                                            <td>{{ $stage->order }}</td>
                                            <td>
                                                @if ($stage->is_active)
                                                    <span class="badge bg-success">Ya</span>
                                                @else
                                                    <span class="badge bg-danger">Tidak</span>
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $stage->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $stage->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal(
                                                                    {{ $stage->id }},
                                                                    '{{ $stage->name }}',
                                                                    '{{ $stage->description }}',
                                                                    '{{ $stage->start_date->format('Y-m-d') }}',
                                                                    '{{ $stage->end_date->format('Y-m-d') }}',
                                                                    {{ $stage->order }},
                                                                    {{ $stage->is_active ? 'true' : 'false' }}
                                                                )">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('ppdb-stages.destroy', $stage->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahap PPDB ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Tidak ada tahap PPDB yang tersedia.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createStageModal" tabindex="-1" aria-labelledby="createStageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createStageModalLabel">Tambah Tahap PPDB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createStageForm" method="POST" action="{{ route('ppdb-stages.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createName" class="form-label">Nama Tahap</label>
                            <input type="text" class="form-control" id="createName" name="name" required>
                            <span class="form-text text-muted">Catatan: Untuk tahap pembayaran gunakan keyword <b>"Pembayaran"</b></span>
                            <span class="form-text text-muted">Catatan: Untuk tahap test fisik gunakan keyword <b>"tes fisik"</b></span>
                        </div>
                        <div class="mb-3">
                            <label for="createDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="createDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="createStartDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="createStartDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="createEndDate" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="createEndDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="createOrder" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="createOrder" name="order" required
                                min="0">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="createIsActive" name="is_active"
                                value="1">
                            <label class="form-check-label" for="createIsActive">
                                Tahap Aktif
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStageModal" tabindex="-1" aria-labelledby="editStageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStageModalLabel">Edit Tahap PPDB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStageForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editStageId" name="id">
                        <div class="mb-3">
                            <label for="editName" class="form-label">Nama Tahap</label>
                            <input type="text" class="form-control" id="editName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editStartDate" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="editStartDate" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editEndDate" class="form-label">Tanggal Selesai</label>
                            <input type="date" class="form-control" id="editEndDate" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOrder" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="editOrder" name="order" required
                                min="0">
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active"
                                value="1">
                            <label class="form-check-label" for="editIsActive">
                                Tahap Aktif
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, name, description, startDate, endDate, order, isActive) {
            document.getElementById('editStageId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editDescription').value = description;
            document.getElementById('editStartDate').value = startDate;
            document.getElementById('editEndDate').value = endDate;
            document.getElementById('editOrder').value = order;
            document.getElementById('editIsActive').checked = isActive;

            document.getElementById('editStageForm').action = '{{ route('ppdb-stages.update', '') }}/' + id;

            var myModal = new bootstrap.Modal(document.getElementById('editStageModal'));
            myModal.show();
        }
    </script>
@endsection
