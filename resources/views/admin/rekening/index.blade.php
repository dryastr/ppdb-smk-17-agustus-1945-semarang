@extends('layouts.main')

@section('title', 'Daftar Rekening')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Rekening</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createRekeningModal">
                            Tambah Rekening
                        </button>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Bank</th>
                                        <th>Nomor Rekening</th>
                                        <th>Atas Nama</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rekenings as $rekening)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $rekening->nama_bank }}</td>
                                            <td>{{ $rekening->nomor_rekening }}</td>
                                            <td>{{ $rekening->nama_pemilik }}</td>
                                            <td>{{ $rekening->keterangan ?? '-' }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $rekening->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $rekening->id }}">
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="openEditModal({{ $rekening->id }}, '{{ $rekening->nama_bank }}', '{{ $rekening->nomor_rekening }}', '{{ $rekening->nama_pemilik }}', `{{ $rekening->keterangan }}`)">Ubah</a>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('rekenings.destroy', $rekening->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekening ini?')">
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

    <!-- Create Modal -->
    <div class="modal fade" id="createRekeningModal" tabindex="-1" aria-labelledby="createRekeningModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRekeningModalLabel">Tambah Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createRekeningForm" method="POST" action="{{ route('rekenings.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="createNamaBank" class="form-label">Nama Bank</label>
                            <input type="text" class="form-control" id="createNamaBank" name="nama_bank" required>
                        </div>
                        <div class="mb-3">
                            <label for="createNomorRekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="createNomorRekening" name="nomor_rekening"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="createNamaPemilik" class="form-label">Atas Nama</label>
                            <input type="text" class="form-control" id="createNamaPemilik" name="nama_pemilik" required>
                        </div>
                        <div class="mb-3">
                            <label for="createKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="createKeterangan" name="keterangan" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editRekeningModal" tabindex="-1" aria-labelledby="editRekeningModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRekeningModalLabel">Edit Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editRekeningForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="editRekeningId" name="id">
                        <div class="mb-3">
                            <label for="editNamaBank" class="form-label">Nama Bank</label>
                            <input type="text" class="form-control" id="editNamaBank" name="nama_bank" required>
                        </div>
                        <div class="mb-3">
                            <label for="editNomorRekening" class="form-label">Nomor Rekening</label>
                            <input type="text" class="form-control" id="editNomorRekening" name="nomor_rekening"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editNamaPemilik" class="form-label">Atas Nama</label>
                            <input type="text" class="form-control" id="editNamaPemilik" name="nama_pemilik"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editKeterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="editKeterangan" name="keterangan" rows="3"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, namaBank, nomorRekening, namaPemilik, keterangan) {
            document.getElementById('editNamaBank').value = namaBank;
            document.getElementById('editNomorRekening').value = nomorRekening;
            document.getElementById('editNamaPemilik').value = namaPemilik;
            document.getElementById('editKeterangan').value = keterangan;
            document.getElementById('editRekeningId').value = id;
            document.getElementById('editRekeningForm').action = 'rekenings/' + id;
            var myModal = new bootstrap.Modal(document.getElementById('editRekeningModal'));
            myModal.show();
        }
    </script>
@endsection
