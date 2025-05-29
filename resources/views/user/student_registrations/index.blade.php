@extends('layouts.main')

@section('title', 'Pendaftaran Siswa')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Daftar Pendaftaran Siswa</h4>
                        @php
                            $hasRegistration = \App\Models\StudentRegistration::where(
                                'user_id',
                                auth()->id(),
                            )->exists();
                        @endphp

                        @if (!$hasRegistration)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#registrationModalA">
                                Tambah Pendaftaran
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-xl" style="margin-top: 25px!important;">
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
                                    @foreach ($registrations as $registration)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $registration->no_pendaftaran }}</td>
                                            <td>{{ $registration->nama }}</td>
                                            <td>{{ $registration->jurusan }}</td>
                                            <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $registration->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $registration->id }}">
                                                        @php
                                                            $isPaid = $registration->payments
                                                                ->where('status', 'dibayar')
                                                                ->isNotEmpty();

                                                            $isAccepted = $registration->status === 'diterima';

                                                            $isDisabled = $isPaid && $isAccepted;
                                                        @endphp

                                                        <li>
                                                            <a class="dropdown-item {{ $isDisabled ? 'disabled' : '' }}"
                                                                href="javascript:void(0)"
                                                                onclick="{{ $isDisabled ? 'event.preventDefault();' : 'openEditModal(' . json_encode($registration) . ');' }}"
                                                                {{ $isDisabled ? 'aria-disabled="true"' : '' }}>
                                                                Ubah
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="javascript:void(0)"
                                                                onclick="showDetailModal({{ $registration->id }})">
                                                                Detail
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('student-registrations.destroy', $registration->id) }}"
                                                                method="POST"
                                                                onsubmit="{{ $isDisabled ? 'return false;' : "return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')" }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="dropdown-item {{ $isDisabled ? 'disabled' : '' }}"
                                                                    {{ $isDisabled ? 'disabled' : '' }}
                                                                    {{ $isDisabled ? 'aria-disabled="true"' : '' }}>
                                                                    Hapus
                                                                </button>
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

    <form method="POST" action="{{ route('student-registrations.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal fade" id="registrationModalA" tabindex="-1" aria-labelledby="registrationModalALabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalALabel">A. Identitas Pribadi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_pengantar" class="form-label">Nama Pengantar (Jika Ada)</label>
                                <input type="text" class="form-control" id="nama_pengantar" name="nama_pengantar">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Lengkap*</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin*</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir*</label>
                                <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir*</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="golongan_darah" class="form-label">Golongan Darah</label>
                                <select class="form-select" id="golongan_darah" name="golongan_darah">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="agama" class="form-label">Agama*</label>
                                <input type="text" class="form-control" id="agama" name="agama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pelajaran_agama" class="form-label">Pelajaran Agama</label>
                                <input type="text" class="form-control" id="pelajaran_agama" name="pelajaran_agama">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Lengkap*</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="telepon" name="telepon">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_saudara" class="form-label">Jumlah Saudara</label>
                            <input type="number" class="form-control" id="jumlah_saudara" name="jumlah_saudara"
                                min="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalB" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrationModalB" tabindex="-1" aria-labelledby="registrationModalBLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalBLabel">B. Pendidikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="sekolah_asal" class="form-label">Sekolah Asal*</label>
                                <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="text" class="form-control" id="nisn" name="nisn">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="no_sttb" class="form-label">No. STTB</label>
                                <input type="text" class="form-control" id="no_sttb" name="no_sttb">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tahun_sttb" class="form-label">Tahun STTB</label>
                                <input type="text" class="form-control" id="tahun_sttb" name="tahun_sttb"
                                    maxlength="4">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_sekolah" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control" id="alamat_sekolah" name="alamat_sekolah" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ijazah_terakhir" class="form-label">Ijazah Terakhir</label>
                                <input type="text" class="form-control" id="ijazah_terakhir" name="ijazah_terakhir">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="no_seri_ijazah" class="form-label">No. Seri Ijazah</label>
                                <input type="text" class="form-control" id="no_seri_ijazah" name="no_seri_ijazah">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                            <input type="text" class="form-control" id="tahun_lulus" name="tahun_lulus"
                                maxlength="4">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalA">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalC" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrationModalC" tabindex="-1" aria-labelledby="registrationModalCLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalCLabel">C. Orang Tua/Wali</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_ayah" class="form-label">Nama Ayah*</label>
                                <input type="text" class="form-control" id="nama_ayah" name="nama_ayah" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="nama_ibu" class="form-label">Nama Ibu*</label>
                                <input type="text" class="form-control" id="nama_ibu" name="nama_ibu" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ayah" class="form-label">Pekerjaan Ayah</label>
                                <input type="text" class="form-control" id="pekerjaan_ayah" name="pekerjaan_ayah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="pekerjaan_ibu" class="form-label">Pekerjaan Ibu</label>
                                <input type="text" class="form-control" id="pekerjaan_ibu" name="pekerjaan_ibu">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="keadaan_ayah" class="form-label">Keadaan Ayah*</label>
                                <select class="form-select" id="keadaan_ayah" name="keadaan_ayah" required>
                                    <option value="">Pilih Keadaan</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="keadaan_ibu" class="form-label">Keadaan Ibu*</label>
                                <select class="form-select" id="keadaan_ibu" name="keadaan_ibu" required>
                                    <option value="">Pilih Keadaan</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_orang_tua" class="form-label">Alamat Orang Tua</label>
                            <textarea class="form-control" id="alamat_orang_tua" name="alamat_orang_tua" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nama_wali" class="form-label">Nama Wali (Jika Ada)</label>
                                <input type="text" class="form-control" id="nama_wali" name="nama_wali">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telepon_wali" class="form-label">Telepon Wali</label>
                                <input type="text" class="form-control" id="telepon_wali" name="telepon_wali">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="alamat_wali" class="form-label">Alamat Wali</label>
                            <textarea class="form-control" id="alamat_wali" name="alamat_wali" rows="2"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="penerima_pip" name="penerima_pip"
                                value="1">
                            <label class="form-check-label" for="penerima_pip">Penerima PIP</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalB">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalD" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrationModalD" tabindex="-1" aria-labelledby="registrationModalDLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalDLabel">D. Jurusan/Peminatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan*</label>
                            <select class="form-select" id="jurusan" name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="Otomotif">Otomotif</option>
                                <option value="DKV">DKV</option>
                                <option value="Farmasi">Farmasi</option>
                                <option value="Technopreneur">Technopreneur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="mengetahui_dari" class="form-label">Mengetahui Sekolah Ini Dari</label>
                            <input type="text" class="form-control" id="mengetahui_dari" name="mengetahui_dari">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalC">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalE" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrationModalE" tabindex="-1" aria-labelledby="registrationModalELabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalELabel">E. Syarat-syarat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="foto_3x4" name="foto_3x4"
                                value="1">
                            <label class="form-check-label" for="foto_3x4">Foto 3x4</label>
                        </div>
                        <div class="mb-3">
                            <label for="fotokopi_kk" class="form-label">Fotokopi KK</label>
                            <input type="file" class="form-control" id="fotokopi_kk" name="fotokopi_kk"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="fotokopi_ijazah" class="form-label">Fotokopi Ijazah</label>
                            <input type="file" class="form-control" id="fotokopi_ijazah" name="fotokopi_ijazah"
                                accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="fotokopi_akte" class="form-label">Fotokopi Akte Kelahiran</label>
                            <input type="file" class="form-control" id="fotokopi_akte" name="fotokopi_akte"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalD">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalF" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="registrationModalF" tabindex="-1" aria-labelledby="registrationModalFLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registrationModalFLabel">F. Tes Fisik</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                                <input type="number" class="form-control" id="tinggi_badan" name="tinggi_badan"
                                    min="100" max="250">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="berat_badan" class="form-label">Berat Badan (kg)</label>
                                <input type="number" class="form-control" id="berat_badan" name="berat_badan"
                                    min="20" max="200">
                            </div>
                        </div>
                        <div class="d-none">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="tato" name="tato"
                                    value="0">
                                <label class="form-check-label" for="tato">Memiliki Tato</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="tindik" name="tindik"
                                    value="0">
                                <label class="form-check-label" for="tindik">Memiliki Tindik</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="buta_warna" name="buta_warna"
                                    value="0">
                                <label class="form-check-label" for="buta_warna">Buta Warna</label>
                            </div>
                            <div class="mb-3">
                                <label for="hasil_tes" class="form-label">Hasil Tes</label>
                                <textarea class="form-control" id="hasil_tes" name="hasil_tes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="pas_foto" class="form-label">Upload Pas Foto</label>
                            <input type="file" class="form-control" id="pas_foto" name="pas_foto"
                                accept="image/jpeg,image/png">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#registrationModalE">Kembali</button>
                        <button type="submit" class="btn btn-success">Simpan Pendaftaran</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <form id="editRegistrationForm" method="POST" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal fade" id="editRegistrationModalA" tabindex="-1" aria-labelledby="editRegistrationModalALabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <input type="hidden" id="editRegistrationId" name="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalALabel">A. Edit Identitas Pribadi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_pengantar" class="form-label">Nama Pengantar (Jika Ada)</label>
                                <input type="text" class="form-control" id="edit_nama_pengantar"
                                    name="nama_pengantar">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama" class="form-label">Nama Lengkap*</label>
                                <input type="text" class="form-control" id="edit_nama" name="nama" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin*</label>
                                <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tempat_lahir" class="form-label">Tempat Lahir*</label>
                                <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir"
                                    required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_tanggal_lahir" class="form-label">Tanggal Lahir*</label>
                                <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_golongan_darah" class="form-label">Golongan Darah</label>
                                <select class="form-select" id="edit_golongan_darah" name="golongan_darah">
                                    <option value="">Pilih Golongan Darah</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_agama" class="form-label">Agama*</label>
                                <input type="text" class="form-control" id="edit_agama" name="agama" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_pelajaran_agama" class="form-label">Pelajaran Agama</label>
                                <input type="text" class="form-control" id="edit_pelajaran_agama"
                                    name="pelajaran_agama">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat Lengkap*</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_telepon" class="form-label">Telepon</label>
                                <input type="text" class="form-control" id="edit_telepon" name="telepon">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="edit_email" name="email">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_jumlah_saudara" class="form-label">Jumlah Saudara</label>
                            <input type="number" class="form-control" id="edit_jumlah_saudara" name="jumlah_saudara"
                                min="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#editRegistrationModalB"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRegistrationModalB" tabindex="-1" aria-labelledby="editRegistrationModalBLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalBLabel">B. Edit Pendidikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_sekolah_asal" class="form-label">Sekolah Asal*</label>
                                <input type="text" class="form-control" id="edit_sekolah_asal" name="sekolah_asal"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_nisn" class="form-label">NISN</label>
                                <input type="text" class="form-control" id="edit_nisn" name="nisn">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_no_sttb" class="form-label">No. STTB</label>
                                <input type="text" class="form-control" id="edit_no_sttb" name="no_sttb">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_tahun_sttb" class="form-label">Tahun STTB</label>
                                <input type="text" class="form-control" id="edit_tahun_sttb" name="tahun_sttb"
                                    maxlength="4">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat_sekolah" class="form-label">Alamat Sekolah</label>
                            <textarea class="form-control" id="edit_alamat_sekolah" name="alamat_sekolah" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_ijazah_terakhir" class="form-label">Ijazah Terakhir</label>
                                <input type="text" class="form-control" id="edit_ijazah_terakhir"
                                    name="ijazah_terakhir">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_no_seri_ijazah" class="form-label">No. Seri Ijazah</label>
                                <input type="text" class="form-control" id="edit_no_seri_ijazah"
                                    name="no_seri_ijazah">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tahun_lulus" class="form-label">Tahun Lulus</label>
                            <input type="text" class="form-control" id="edit_tahun_lulus" name="tahun_lulus"
                                maxlength="4">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#editRegistrationModalA"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#editRegistrationModalC"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRegistrationModalC" tabindex="-1" aria-labelledby="editRegistrationModalCLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalCLabel">C. Edit Orang Tua/Wali</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_ayah" class="form-label">Nama Ayah*</label>
                                <input type="text" class="form-control" id="edit_nama_ayah" name="nama_ayah"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_ibu" class="form-label">Nama Ibu*</label>
                                <input type="text" class="form-control" id="edit_nama_ibu" name="nama_ibu" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_pekerjaan_ayah" class="form-label">Pekerjaan Ayah</label>
                                <input type="text" class="form-control" id="edit_pekerjaan_ayah"
                                    name="pekerjaan_ayah">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_pekerjaan_ibu" class="form-label">Pekerjaan Ibu</label>
                                <input type="text" class="form-control" id="edit_pekerjaan_ibu" name="pekerjaan_ibu">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_keadaan_ayah" class="form-label">Keadaan Ayah*</label>
                                <select class="form-select" id="edit_keadaan_ayah" name="keadaan_ayah" required>
                                    <option value="">Pilih Keadaan</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_keadaan_ibu" class="form-label">Keadaan Ibu*</label>
                                <select class="form-select" id="edit_keadaan_ibu" name="keadaan_ibu" required>
                                    <option value="">Pilih Keadaan</option>
                                    <option value="Masih Hidup">Masih Hidup</option>
                                    <option value="Sudah Meninggal">Sudah Meninggal</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat_orang_tua" class="form-label">Alamat Orang Tua</label>
                            <textarea class="form-control" id="edit_alamat_orang_tua" name="alamat_orang_tua" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_nama_wali" class="form-label">Nama Wali (Jika Ada)</label>
                                <input type="text" class="form-control" id="edit_nama_wali" name="nama_wali">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_telepon_wali" class="form-label">Telepon Wali</label>
                                <input type="text" class="form-control" id="edit_telepon_wali" name="telepon_wali">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat_wali" class="form-label">Alamat Wali</label>
                            <textarea class="form-control" id="edit_alamat_wali" name="alamat_wali" rows="2"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_penerima_pip" name="penerima_pip"
                                value="1">
                            <label class="form-check-label" for="edit_penerima_pip">Penerima PIP</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#editRegistrationModalB"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#editRegistrationModalD"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRegistrationModalD" tabindex="-1" aria-labelledby="editRegistrationModalDLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalDLabel">D. Edit Jurusan/Peminatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_jurusan" class="form-label">Jurusan*</label>
                            <select class="form-select" id="edit_jurusan" name="jurusan" required>
                                <option value="">Pilih Jurusan</option>
                                <option value="Otomotif">Otomotif</option>
                                <option value="DKV">DKV</option>
                                <option value="Farmasi">Farmasi</option>
                                <option value="Technopreneur">Technopreneur</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_mengetahui_dari" class="form-label">Mengetahui Sekolah Ini Dari</label>
                            <input type="text" class="form-control" id="edit_mengetahui_dari" name="mengetahui_dari">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#editRegistrationModalC"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#editRegistrationModalE"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRegistrationModalE" tabindex="-1" aria-labelledby="editRegistrationModalELabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalELabel">E. Edit Syarat-syarat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 form-check">
                            <input type="hidden" name="foto_3x4" value="0">
                            <input type="checkbox" class="form-check-input" id="edit_foto_3x4" name="foto_3x4"
                                value="1">
                            <label class="form-check-label" for="edit_foto_3x4">Foto 3x4</label>
                        </div>

                        <div class="mb-3">
                            <label for="edit_fotokopi_kk" class="form-label">Fotokopi KK</label>
                            <input type="file" class="form-control" id="edit_fotokopi_kk" name="fotokopi_kk"
                                accept="image/*">
                            <div id="current_fotokopi_kk_preview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_fotokopi_ijazah" class="form-label">Fotokopi Ijazah</label>
                            <input type="file" class="form-control" id="edit_fotokopi_ijazah" name="fotokopi_ijazah"
                                accept="image/*">
                            <div id="current_fotokopi_ijazah_preview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_fotokopi_akte" class="form-label">Fotokopi Akte Kelahiran</label>
                            <input type="file" class="form-control" id="edit_fotokopi_akte" name="fotokopi_akte"
                                accept="image/*">
                            <div id="current_fotokopi_akte_preview" class="mt-2"></div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#editRegistrationModalD"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</button>
                        <button type="button" class="btn btn-primary" data-bs-target="#editRegistrationModalF"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Selanjutnya</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editRegistrationModalF" tabindex="-1" aria-labelledby="editRegistrationModalFLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="editRegistrationModalFLabel">F. Edit Tes Fisik</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_tinggi_badan" class="form-label">Tinggi Badan (cm)</label>
                                <input type="number" class="form-control" id="edit_tinggi_badan" name="tinggi_badan"
                                    min="100" max="250">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_berat_badan" class="form-label">Berat Badan (kg)</label>
                                <input type="number" class="form-control" id="edit_berat_badan" name="berat_badan"
                                    min="20" max="200">
                            </div>
                        </div>
                        <div class="d-none">
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="edit_tato" name="tato"
                                    value="1">
                                <label class="form-check-label" for="edit_tato">Memiliki Tato</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="edit_tindik" name="tindik"
                                    value="1">
                                <label class="form-check-label" for="edit_tindik">Memiliki Tindik</label>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="edit_buta_warna"
                                    name="buta_warna" value="1">
                                <label class="form-check-label" for="edit_buta_warna">Buta Warna</label>
                            </div>
                            <div class="mb-3">
                                <label for="edit_hasil_tes" class="form-label">Hasil Tes</label>
                                <textarea class="form-control" id="edit_hasil_tes" name="hasil_tes" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_pas_foto" class="form-label">Upload Pas Foto (Biarkan kosong jika tidak
                                mengubah)</label>
                            <input type="file" class="form-control" id="edit_pas_foto" name="pas_foto"
                                accept="image/jpeg,image/png">
                            <div id="current_pas_foto_preview" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#editRegistrationModalE"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Kembali</button>
                        <button type="submit" class="btn btn-success">Simpan Perubahan Pendaftaran</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="modal fade" id="detailRegistrationModal" tabindex="-1"
        aria-labelledby="detailRegistrationModalLabel" aria-hidden="true">
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
                                                        <img src="{{ asset($registration->fotokopi_kk) }}"
                                                            alt="Pas Foto" class="img-thumbnail"
                                                            style="max-height: 150px;">
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
                                            <td>{{ $registration->tato ? 'Ya' : 'Akan diupdate oleh Admin' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tindik</th>
                                            <td>{{ $registration->tindik ? 'Ya' : 'Akan diupdate oleh Admin' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Buta Warna</th>
                                            <td>{{ $registration->buta_warna ? 'Ya' : 'Akan diupdate oleh Admin' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Hasil Tes</th>
                                            <td>{{ $registration->hasil_tes ?? 'Akan diupdate oleh Admin' }}</td>
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
        function toDateInputFormat(dateStr) {
            const parts = dateStr.split('-');
            return `${parts[2]}-${parts[1]}-${parts[0]}`;
        }

        function openEditModal(registration) {
            const updateRoute = `/student-registrations/${registration.id}`;
            document.getElementById('editRegistrationForm').action = updateRoute;

            document.getElementById('editRegistrationId').value = registration.id;

            document.getElementById('edit_nama_pengantar').value = registration.nama_pengantar || '';
            document.getElementById('edit_nama').value = registration.nama || '';
            document.getElementById('edit_jenis_kelamin').value = registration.jenis_kelamin || '';
            document.getElementById('edit_tempat_lahir').value = registration.tempat_lahir || '';
            document.getElementById('edit_tanggal_lahir').value = registration.tanggal_lahir.substring(0, 10);
            console.log("Tanggal lahir dari backend:", registration.tanggal_lahir);
            document.getElementById('edit_golongan_darah').value = registration.golongan_darah || '';
            document.getElementById('edit_agama').value = registration.agama || '';
            document.getElementById('edit_pelajaran_agama').value = registration.pelajaran_agama || '';
            document.getElementById('edit_alamat').value = registration.alamat || '';
            document.getElementById('edit_telepon').value = registration.telepon || '';
            document.getElementById('edit_email').value = registration.email || '';
            document.getElementById('edit_jumlah_saudara').value = registration.jumlah_saudara || 0;

            document.getElementById('edit_sekolah_asal').value = registration.sekolah_asal || '';
            document.getElementById('edit_nisn').value = registration.nisn || '';
            document.getElementById('edit_no_sttb').value = registration.no_sttb || '';
            document.getElementById('edit_tahun_sttb').value = registration.tahun_sttb || '';
            document.getElementById('edit_alamat_sekolah').value = registration.alamat_sekolah || '';
            document.getElementById('edit_ijazah_terakhir').value = registration.ijazah_terakhir || '';
            document.getElementById('edit_no_seri_ijazah').value = registration.no_seri_ijazah || '';
            document.getElementById('edit_tahun_lulus').value = registration.tahun_lulus || '';

            document.getElementById('edit_nama_ayah').value = registration.nama_ayah || '';
            document.getElementById('edit_nama_ibu').value = registration.nama_ibu || '';
            document.getElementById('edit_pekerjaan_ayah').value = registration.pekerjaan_ayah || '';
            document.getElementById('edit_pekerjaan_ibu').value = registration.pekerjaan_ibu || '';
            document.getElementById('edit_keadaan_ayah').value = registration.keadaan_ayah || '';
            document.getElementById('edit_keadaan_ibu').value = registration.keadaan_ibu || '';
            document.getElementById('edit_alamat_orang_tua').value = registration.alamat_orang_tua || '';
            document.getElementById('edit_nama_wali').value = registration.nama_wali || '';
            document.getElementById('edit_telepon_wali').value = registration.telepon_wali || '';
            document.getElementById('edit_alamat_wali').value = registration.alamat_wali || '';
            document.getElementById('edit_penerima_pip').checked = registration.penerima_pip == 1;

            document.getElementById('edit_jurusan').value = registration.jurusan || '';
            document.getElementById('edit_mengetahui_dari').value = registration.mengetahui_dari || '';

            document.getElementById('edit_foto_3x4').checked = registration.foto_3x4 == 1;
            // document.getElementById('edit_fotokopi_kk').checked = registration.fotokopi_kk == 1;
            // document.getElementById('edit_fotokopi_ijazah').checked = registration.fotokopi_ijazah == 1;
            // document.getElementById('edit_fotokopi_akte').checked = registration.fotokopi_akte == 1;

            document.getElementById('edit_tinggi_badan').value = registration.tinggi_badan || '';
            document.getElementById('edit_berat_badan').value = registration.berat_badan || '';
            document.getElementById('edit_tato').checked = registration.tato == 1;
            document.getElementById('edit_tindik').checked = registration.tindik == 1;
            document.getElementById('edit_buta_warna').checked = registration.buta_warna == 1;
            document.getElementById('edit_hasil_tes').value = registration.hasil_tes || '';

            const fotokopiKkPreview = document.getElementById('current_fotokopi_kk_preview');
            if (registration.fotokopi_kk_url) {
                fotokopiKkPreview.innerHTML =
                    `<p>Foto saat ini:</p><img src="${registration.fotokopi_kk_url}" alt="Fotokopi KK" style="max-width: 150px; height: auto;">`;
            } else {
                fotokopiKkPreview.innerHTML = '';
            }

            const fotokopiIjazahPreview = document.getElementById('current_fotokopi_ijazah_preview');
            if (registration.fotokopi_ijazah_url) {
                fotokopiIjazahPreview.innerHTML =
                    `<p>Foto saat ini:</p><img src="${registration.fotokopi_ijazah_url}" alt="Fotokopi Ijazah" style="max-width: 150px; height: auto;">`;
            } else {
                fotokopiIjazahPreview.innerHTML = '';
            }

            const fotokopiAktePreview = document.getElementById('current_fotokopi_akte_preview');
            if (registration.fotokopi_akte_url) {
                fotokopiAktePreview.innerHTML =
                    `<p>Foto saat ini:</p><img src="${registration.fotokopi_akte_url}" alt="Fotokopi Akte" style="max-width: 150px; height: auto;">`;
            } else {
                fotokopiAktePreview.innerHTML = '';
            }
            const currentPhotoPreview = document.getElementById('current_pas_foto_preview');
            if (registration.pas_foto_url) {
                currentPhotoPreview.innerHTML =
                    `<p>Foto saat ini:</p><img src="${registration.pas_foto_url}" alt="Pas Foto" style="max-width: 150px; height: auto;">`;
            } else {
                currentPhotoPreview.innerHTML = '';
            }

            var myModal = new bootstrap.Modal(document.getElementById('editRegistrationModalA'));
            myModal.show();
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
