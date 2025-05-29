@extends('layouts.main')

@section('title', 'Kelola Alur Pendaftaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Alur Pendaftaran</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFlowModal">
                            Tambah Alur
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>No Urut</th>
                                        <th>Judul</th>
                                        <th>Deskripsi</th>
                                        {{-- <th>Ikon</th> --}}
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($flows as $flow)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $flow->step_number }}</td>
                                            <td>{{ $flow->title }}</td>
                                            <td>{{ $flow->description }}</td>
                                            <td class="d-none">
                                                @if ($flow->icon)
                                                    <i class="{{ $flow->icon }}"></i> <span
                                                        class="ms-2">({{ $flow->icon }})</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $flow->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $flow->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $flow->id }}, '{{ $flow->step_number }}', '{{ $flow->title }}', `{{ str_replace(["\r\n", "\n", "\r"], '&#13;', $flow->description) }}`, '{{ $flow->icon }}')">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('registration-flows.destroy', $flow->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus alur ini? Ini tidak dapat dikembalikan!')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="dropdown-item">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createFlowModal" tabindex="-1" aria-labelledby="createFlowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFlowModalLabel">Tambah Alur Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFlowForm" method="POST" action="{{ route('registration-flows.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createStepNumber" class="form-label">Nomor Urut</label>
                            <input type="number" class="form-control" id="createStepNumber" name="step_number" required
                                min="1">
                        </div>
                        <div class="mb-3">
                            <label for="createTitle" class="form-label">Judul</label>
                            <input type="text" class="form-control" id="createTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="createDescription" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="createDescription" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3 d-none">
                            <label for="createIcon" class="form-label">Ikon (Opsional, misal: bi bi-person)</label>
                            <input type="text" class="form-control" id="createIcon" name="icon">
                            <small class="form-text text-muted">Gunakan kelas ikon dari Bootstrap Icons.</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editFlowModal" tabindex="-1" aria-labelledby="editFlowModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFlowModalLabel">Edit Alur Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="editFlowForm" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editFlowId" name="id">
                            <div class="mb-3">
                                <label for="editStepNumber" class="form-label">Nomor Urut</label>
                                <input type="number" class="form-control" id="editStepNumber" name="step_number" required min="1">
                            </div>
                            <div class="mb-3">
                                <label for="editTitle" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="editTitle" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="editDescription" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="3" required></textarea>
                            </div>
                            <div class="mb-3 d-none">
                                <label for="editIcon" class="form-label">Ikon (Opsional)</label>
                                <input type="text" class="form-control" id="editIcon" name="icon">
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, stepNumber, title, description, icon) {
            document.getElementById('editStepNumber').value = stepNumber;
            document.getElementById('editTitle').value = title;
            document.getElementById('editDescription').value = description.replace(/&#13;/g, '\n');
            document.getElementById('editIcon').value = icon;
            document.getElementById('editFlowId').value = id;
            document.getElementById('editFlowForm').action = '{{ url('registration-flows') }}/' +
            id;
            var myModal = new bootstrap.Modal(document.getElementById('editFlowModal'));
            myModal.show();
        }
    </script>
@endsection
