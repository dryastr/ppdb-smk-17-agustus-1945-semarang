@extends('layouts.main')

@section('title', 'Pendaftaran Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title">Daftar Pendaftaran Siswa</h4>
                    <ul class="nav nav-tabs mt-3" id="statusTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="menunggu-tab" data-bs-toggle="tab"
                                data-bs-target="#menunggu" type="button" role="tab">Menunggu</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="diterima-tab" data-bs-toggle="tab" data-bs-target="#diterima"
                                type="button" role="tab">Diterima</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#ditolak"
                                type="button" role="tab">Ditolak</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ditolak-tab" data-bs-toggle="tab" data-bs-target="#diperiksa"
                                type="button" role="tab">Diperiksa</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body tab-content" id="statusTabContent">
                    @php
                        $statuses = [
                            'menunggu' => 'Menunggu',
                            'diterima' => 'Diterima',
                            'diperiksa' => 'Diperiksa',
                            'ditolak' => 'Ditolak',
                        ];
                    @endphp

                    @foreach ($statuses as $statusKey => $statusLabel)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $statusKey }}"
                            role="tabpanel">
                            <div class="table-responsive mt-3">
                                <table class="table table-xl">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Pendaftaran</th>
                                            <th>Nama Siswa</th>
                                            <th>Jurusan</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registrations->where('status', $statusKey) as $registration)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $registration->no_pendaftaran }}</td>
                                                <td>{{ $registration->nama }}</td>
                                                <td>{{ $registration->jurusan }}</td>
                                                <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                                <td class="text-nowrap">
                                                    <div class="dropdown dropup">
                                                        <button class="btn btn-sm btn-secondary dropdown-toggle"
                                                            type="button" id="dropdownMenuButton-{{ $registration->id }}"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu"
                                                            aria-labelledby="dropdownMenuButton-{{ $registration->id }}">
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                    onclick="openStatusModal(
                                                                        {{ $registration->id }},
                                                                        '{{ $registration->status }}',
                                                                        '{{ $registration->keterangan_status }}',
                                                                        '{{ $registration->ppdb_stage_id ?? '' }}',
                                                                        {
                                                                            tato: {{ $registration->tato ? 'true' : 'false' }},
                                                                            tindik: {{ $registration->tindik ? 'true' : 'false' }},
                                                                            buta_warna: {{ $registration->buta_warna ? 'true' : 'false' }},
                                                                            hasil_tes: '{{ $registration->hasil_tes ?? '' }}',
                                                                            keterangan_fisik_tato: '{{ $registration->keterangan_fisik_tato ?? '' }}',
                                                                            keterangan_fisik_tindik: '{{ $registration->keterangan_fisik_tindik ?? '' }}',
                                                                            keterangan_fisik_butawarna: '{{ $registration->keterangan_fisik_butawarna ?? '' }}',
                                                                            keterangan_fisik_tinggi_berat: '{{ $registration->keterangan_fisik_tinggi_berat ?? '' }}'
                                                                        }
                                                                    )">Ubah
                                                                    Status</a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                    onclick="showDetailModal({{ $registration->id }})">Detail</a>
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
                    @endforeach
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="statusForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Penerimaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="statusRegistrationId" name="id">

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="diperiksa">Diperiksa</option>
                                <option value="diterima">Diterima</option>
                                <option value="ditolak">Ditolak</option>
                                <option value="menunggu">Menunggu</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="ppdbStageSelect" class="form-label">Tahap PPDB</label>
                            <select name="ppdb_stage_id" id="ppdbStageSelect" class="form-select">
                                <option value="">-- Pilih Tahap --</option>
                                @foreach ($ppdbStages as $stage)
                                    <option value="{{ $stage->id }}">
                                        {{ $stage->name }}
                                        ({{ \Carbon\Carbon::parse($stage->start_date)->format('d M Y') }} -
                                        {{ \Carbon\Carbon::parse($stage->end_date)->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Akan memperbarui tahap pendaftaran siswa.</small>
                        </div>

                        <div class="mb-3 d-none" id="keteranganDiv">
                            <label for="keterangan_status" class="form-label">Keterangan Penolakan</label>
                            <textarea name="keterangan_status" id="keteranganInput" class="form-control" rows="3"
                                placeholder="Alasan penolakan..."></textarea>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="tato" value="0">
                            <input class="form-check-input" type="checkbox" value="1" name="tato"
                                id="tatoCheckbox">
                            <label class="form-check-label" for="tatoCheckbox">Tato</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="tindik" value="0">
                            <input class="form-check-input" type="checkbox" value="1" name="tindik"
                                id="tindikCheckbox">
                            <label class="form-check-label" for="tindikCheckbox">Tindik</label>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="hidden" name="buta_warna" value="0">
                            <input class="form-check-input" type="checkbox" value="1" name="buta_warna"
                                id="butawarnaCheckbox">
                            <label class="form-check-label" for="butawarnaCheckbox">Buta Warna</label>
                        </div>

                        <div class="mb-3">
                            <label for="hasil_tes" class="form-label">Hasil Tes</label>
                            <textarea class="form-control" name="hasil_tes" id="hasilTesInput" rows="3" placeholder="Tulis hasil tes..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_fisik_tato" class="form-label">Keterangan Tato</label>
                            <textarea class="form-control" name="keterangan_fisik_tato" id="ketTatoInput" rows="2"
                                placeholder="Tulis keterangan tato..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_fisik_tindik" class="form-label">Keterangan Tindik</label>
                            <textarea class="form-control" name="keterangan_fisik_tindik" id="ketTindikInput" rows="2"
                                placeholder="Tulis keterangan tindik..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_fisik_butawarna" class="form-label">Keterangan Buta Warna</label>
                            <textarea class="form-control" name="keterangan_fisik_butawarna" id="ketButaInput" rows="2"
                                placeholder="Tulis keterangan buta warna..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_fisik_tinggi_berat" class="form-label">Keterangan Tinggi/Berat</label>
                            <textarea class="form-control" name="keterangan_fisik_tinggi_berat" id="ketTinggiBeratInput" rows="2"
                                placeholder="Tulis keterangan tinggi/berat..."></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="detailRegistrationModal" tabindex="-1" aria-labelledby="detailRegistrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailRegistrationModalLabel">Detail Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($registrations as $registration)
                        <div class="detail-content" id="detail-{{ $registration->id }}" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="mb-3">A. Identitas Pribadi</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>No Pendaftaran</th>
                                            <td>{{ $registration->no_pendaftaran }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Pengantar</th>
                                            <td>{{ $registration->nama_pengantar ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Lengkap</th>
                                            <td>{{ $registration->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Kelamin</th>
                                            <td>{{ $registration->jenis_kelamin }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tempat/Tanggal Lahir</th>
                                            <td>{{ $registration->tempat_lahir }},
                                                {{ $registration->tanggal_lahir->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Golongan Darah</th>
                                            <td>{{ $registration->golongan_darah ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Agama</th>
                                            <td>{{ $registration->agama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pelajaran Agama</th>
                                            <td>{{ $registration->pelajaran_agama ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat</th>
                                            <td>{{ $registration->alamat }}</td>
                                        </tr>
                                        <tr>
                                            <th>Telepon</th>
                                            <td>{{ $registration->telepon ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td>{{ $registration->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Saudara</th>
                                            <td>{{ $registration->jumlah_saudara }}</td>
                                        </tr>
                                    </table>

                                    <h5 class="mb-3 mt-4">C. Orang Tua/Wali</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Nama Ayah</th>
                                            <td>{{ $registration->nama_ayah }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Ibu</th>
                                            <td>{{ $registration->nama_ibu }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pekerjaan Ayah</th>
                                            <td>{{ $registration->pekerjaan_ayah ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pekerjaan Ibu</th>
                                            <td>{{ $registration->pekerjaan_ibu ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keadaan Ayah</th>
                                            <td>{{ $registration->keadaan_ayah }}</td>
                                        </tr>
                                        <tr>
                                            <th>Keadaan Ibu</th>
                                            <td>{{ $registration->keadaan_ibu }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat Orang Tua</th>
                                            <td>{{ $registration->alamat_orang_tua ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Nama Wali</th>
                                            <td>{{ $registration->nama_wali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat Wali</th>
                                            <td>{{ $registration->alamat_wali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Telepon Wali</th>
                                            <td>{{ $registration->telepon_wali ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Penerima PIP</th>
                                            <td>{{ $registration->penerima_pip ? 'Ya' : 'Tidak' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <h5 class="mb-3">B. Pendidikan</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Sekolah Asal</th>
                                            <td>{{ $registration->sekolah_asal }}</td>
                                        </tr>
                                        <tr>
                                            <th>NISN</th>
                                            <td>{{ $registration->nisn ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. STTB</th>
                                            <td>{{ $registration->no_sttb ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tahun STTB</th>
                                            <td>{{ $registration->tahun_sttb ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Alamat Sekolah</th>
                                            <td>{{ $registration->alamat_sekolah ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ijazah Terakhir</th>
                                            <td>{{ $registration->ijazah_terakhir ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>No. Seri Ijazah</th>
                                            <td>{{ $registration->no_seri_ijazah ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tahun Lulus</th>
                                            <td>{{ $registration->tahun_lulus ?? '-' }}</td>
                                        </tr>
                                    </table>

                                    <h5 class="mb-3 mt-4">D. Jurusan/Peminatan</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Jurusan</th>
                                            <td>{{ $registration->jurusan }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mengetahui Dari</th>
                                            <td>{{ $registration->mengetahui_dari ?? '-' }}</td>
                                        </tr>
                                    </table>

                                    <h5 class="mb-3 mt-4">E. Syarat-syarat</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Foto 3x4</th>
                                            <td>{{ $registration->foto_3x4 ? 'Lengkap' : 'Belum' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Fotokopi KK</th>
                                            <td>
                                                @if ($registration->fotokopi_kk)
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ asset($registration->fotokopi_kk) }}" alt="Pas Foto"
                                                            class="img-thumbnail" style="max-height: 150px;">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-primary me-2"
                                                                onclick="openImageViewModal('{{ $registration->fotokopi_kk }}', 'Fotokopi KK')">
                                                                Lihat
                                                            </button>
                                                            <a href="{{ $registration->fotokopi_kk }}"
                                                                class="btn btn-sm btn-info" download>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">Belum</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fotokopi Ijazah</th>
                                            <td>
                                                @if ($registration->fotokopi_ijazah)
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ asset($registration->fotokopi_ijazah) }}"
                                                            alt="Pas Foto" class="img-thumbnail"
                                                            style="max-height: 150px;">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-primary me-2"
                                                                onclick="openImageViewModal('{{ $registration->fotokopi_ijazah }}', 'Fotokopi Ijazah')">
                                                                Lihat
                                                            </button>
                                                            <a href="{{ $registration->fotokopi_ijazah }}"
                                                                class="btn btn-sm btn-info" download>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">Belum</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Fotokopi Akte</th>
                                            <td>
                                                @if ($registration->fotokopi_akte)
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ asset($registration->fotokopi_akte) }}"
                                                            alt="Pas Foto" class="img-thumbnail"
                                                            style="max-height: 150px;">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-primary me-2"
                                                                onclick="openImageViewModal('{{ $registration->fotokopi_akte }}', 'Fotokopi Akte')">
                                                                Lihat
                                                            </button>
                                                            <a href="{{ $registration->fotokopi_akte }}"
                                                                class="btn btn-sm btn-info" download>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="badge bg-warning">Belum</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>

                                    <h5 class="mb-3 mt-4">F. Tes Fisik</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Tinggi Badan</th>
                                            <td>{{ $registration->tinggi_badan ? $registration->tinggi_badan . ' cm' : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Berat Badan</th>
                                            <td>{{ $registration->berat_badan ? $registration->berat_badan . ' kg' : '-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tato</th>
                                            <td>{{ $registration->tato ? 'Ya' : 'Tidak' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tindik</th>
                                            <td>{{ $registration->tindik ? 'Ya' : 'Tidak' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Buta Warna</th>
                                            <td>{{ $registration->buta_warna ? 'Ya' : 'Tidak' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hasil Tes</th>
                                            <td>{{ $registration->hasil_tes ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Pas Foto</th>
                                            <td>
                                                @if ($registration->pas_foto)
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ asset($registration->pas_foto) }}" alt="Pas Foto"
                                                            class="img-thumbnail" style="max-height: 150px;">
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-primary me-2"
                                                                onclick="openImageViewModal('{{ $registration->pas_foto }}', 'Fotokopi KK')">
                                                                Lihat
                                                            </button>
                                                            <a href="{{ $registration->pas_foto }}"
                                                                class="btn btn-sm btn-info" download>
                                                                Download
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    Belum
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-labelledby="imageViewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageViewModalLabel">Pratinjau Gambar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="imageViewModalImage" src="" alt="Pratinjau Dokumen" class="img-fluid"
                        style="max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openImageViewModal(imageUrl, title) {
            document.getElementById('imageViewModalImage').src = imageUrl;
            document.getElementById('imageViewModalLabel').innerText = 'Pratinjau ' + title;

            var imageViewModal = new bootstrap.Modal(document.getElementById('imageViewModal'));
            imageViewModal.show();
        }
    </script>
    <script>
        function openStatusModal(id, currentStatus = '', keterangan = '', ppdbStageId = '',
        extraData = {}) {
            const modal = new bootstrap.Modal(document.getElementById('statusModal'));
            document.getElementById('statusRegistrationId').value = id;

            const ppdbStageSelect = document.getElementById('ppdbStageSelect');
            ppdbStageSelect.value = ppdbStageId;

            const form = document.getElementById('statusForm');
            form.action = `/manage-student-registrations/${id}/update-status`;

            const statusSelect = document.getElementById('statusSelect');
            const keteranganDiv = document.getElementById('keteranganDiv');
            const keteranganInput = document.getElementById('keteranganInput');

            statusSelect.value = currentStatus;
            keteranganInput.value = keterangan || '';

            if (currentStatus === 'ditolak') {
                keteranganDiv.classList.remove('d-none');
            } else {
                keteranganDiv.classList.add('d-none');
                keteranganInput.value = '';
            }

            statusSelect.addEventListener('change', function() {
                if (this.value === 'ditolak') {
                    keteranganDiv.classList.remove('d-none');
                } else {
                    keteranganDiv.classList.add('d-none');
                    keteranganInput.value = '';
                }
            });

            document.getElementById('tatoCheckbox').checked = extraData.tato ?? false;
            document.getElementById('tindikCheckbox').checked = extraData.tindik ?? false;
            document.getElementById('butawarnaCheckbox').checked = extraData.buta_warna ?? false;
            // document.getElementById('tinggiInput').value = extraData.tinggi_badan ?? '';
            // document.getElementById('beratInput').value = extraData.berat_badan ?? '';
            document.getElementById('hasilTesInput').value = extraData.hasil_tes ?? '';
            document.getElementById('ketTatoInput').value = extraData.keterangan_fisik_tato ?? '';
            document.getElementById('ketTindikInput').value = extraData.keterangan_fisik_tindik ?? '';
            document.getElementById('ketButaInput').value = extraData.keterangan_fisik_butawarna ?? '';
            document.getElementById('ketTinggiBeratInput').value = extraData.keterangan_fisik_tinggi_berat ?? '';


            modal.show();
        }
    </script>
    <script>
        function showDetailModal(id) {
            document.querySelectorAll('.detail-content').forEach(el => {
                el.style.display = 'none';
            });

            document.getElementById('detail-' + id).style.display = 'block';

            const detailModal = new bootstrap.Modal(document.getElementById('detailRegistrationModal'));
            detailModal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('a[href*="student-registrations.show"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('href').split('/').pop();
                    showDetailModal(id);
                });
            });
        });
    </script>

@endsection
