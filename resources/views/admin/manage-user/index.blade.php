@extends('layouts.main')

@section('title', 'Kelola Pengguna')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Daftar Pengguna Sistem</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-xl table-hover align-middle">
                                <thead class="">
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th>Nomor Telepon</th>
                                        <th scope="col">Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $index => $user)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->phone_number)
                                                    {{ $user->phone_number }}
                                                @else
                                                    <span class="text-muted">Tidak Ada Nomor Telepon</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($user->role->name == 'user')
                                                    Siswa
                                                @else
                                                    Tidak Ada Role
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <div class="dropdown dropup">
                                                    <button class="btn btn-sm btn-secondary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton-{{ $user->id }}"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi bi-three-dots-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton-{{ $user->id }}">
                                                        <li>
                                                            @if ($user->studentRegistration)
                                                                <a class="dropdown-item" href="javascript:void(0)"
                                                                    onclick="showDetailModal('{{ $user->studentRegistration->id }}')">Detail
                                                                    Pendaftaran</a>
                                                            @else
                                                                <span class="dropdown-item text-muted">Belum Ada
                                                                    Pendaftaran</span>
                                                            @endif
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

    <div class="modal fade" id="detailRegistrationModal" tabindex="-1" aria-labelledby="detailRegistrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailRegistrationModalLabel">Detail Pendaftaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($users as $user)
                        @if ($user->studentRegistration)
                            <div class="detail-content" id="detail-{{ $user->studentRegistration->id }}"
                                style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="mb-3">A. Identitas Pribadi</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>No Pendaftaran</th>
                                                <td>{{ $user->studentRegistration->no_pendaftaran ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Pengantar</th>
                                                <td>{{ $user->studentRegistration->nama_pengantar ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Lengkap</th>
                                                <td>{{ $user->studentRegistration->nama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jenis Kelamin</th>
                                                <td>{{ $user->studentRegistration->jenis_kelamin ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tempat/Tanggal Lahir</th>
                                                <td>
                                                    {{ $user->studentRegistration->tempat_lahir ?? '-' }},
                                                    {{ $user->studentRegistration->tanggal_lahir ? $user->studentRegistration->tanggal_lahir->format('d/m/Y') : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Golongan Darah</th>
                                                <td>{{ $user->studentRegistration->golongan_darah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Agama</th>
                                                <td>{{ $user->studentRegistration->agama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pelajaran Agama</th>
                                                <td>{{ $user->studentRegistration->pelajaran_agama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat</th>
                                                <td>{{ $user->studentRegistration->alamat ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Telepon</th>
                                                <td>{{ $user->studentRegistration->telepon ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $user->studentRegistration->email ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jumlah Saudara</th>
                                                <td>{{ $user->studentRegistration->jumlah_saudara ?? '-' }}</td>
                                            </tr>
                                        </table>

                                        <h5 class="mb-3 mt-4">C. Orang Tua/Wali</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Nama Ayah</th>
                                                <td>{{ $user->studentRegistration->nama_ayah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Ibu</th>
                                                <td>{{ $user->studentRegistration->nama_ibu ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pekerjaan Ayah</th>
                                                <td>{{ $user->studentRegistration->pekerjaan_ayah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Pekerjaan Ibu</th>
                                                <td>{{ $user->studentRegistration->pekerjaan_ibu ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Keadaan Ayah</th>
                                                <td>{{ $user->studentRegistration->keadaan_ayah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Keadaan Ibu</th>
                                                <td>{{ $user->studentRegistration->keadaan_ibu ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat Orang Tua</th>
                                                <td>{{ $user->studentRegistration->alamat_orang_tua ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nama Wali</th>
                                                <td>{{ $user->studentRegistration->nama_wali ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat Wali</th>
                                                <td>{{ $user->studentRegistration->alamat_wali ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Telepon Wali</th>
                                                <td>{{ $user->studentRegistration->telepon_wali ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Penerima PIP</th>
                                                <td>{{ $user->studentRegistration->penerima_pip ?? false ? 'Ya' : 'Tidak' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="mb-3">B. Pendidikan</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Sekolah Asal</th>
                                                <td>{{ $user->studentRegistration->sekolah_asal ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NISN</th>
                                                <td>{{ $user->studentRegistration->nisn ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. STTB</th>
                                                <td>{{ $user->studentRegistration->no_sttb ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tahun STTB</th>
                                                <td>{{ $user->studentRegistration->tahun_sttb ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Alamat Sekolah</th>
                                                <td>{{ $user->studentRegistration->alamat_sekolah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Ijazah Terakhir</th>
                                                <td>{{ $user->studentRegistration->ijazah_terakhir ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Seri Ijazah</th>
                                                <td>{{ $user->studentRegistration->no_seri_ijazah ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tahun Lulus</th>
                                                <td>{{ $user->studentRegistration->tahun_lulus ?? '-' }}</td>
                                            </tr>
                                        </table>

                                        <h5 class="mb-3 mt-4">D. Jurusan/Peminatan</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Jurusan</th>
                                                <td>{{ $user->studentRegistration->jurusan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Mengetahui Dari</th>
                                                <td>{{ $user->studentRegistration->mengetahui_dari ?? '-' }}</td>
                                            </tr>
                                        </table>

                                        <h5 class="mb-3 mt-4">E. Syarat-syarat</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Foto 3x4</th>
                                                <td>{{ $user->studentRegistration->foto_3x4 ?? false ? 'Lengkap' : 'Belum' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Fotokopi KK</th>
                                                <td>
                                                    @if ($user->studentRegistration->fotokopi_kk)
                                                        <img src="{{ asset($user->studentRegistration->fotokopi_kk) }}"
                                                            alt="Fotokopi KK" class="img-thumbnail"
                                                            style="max-height: 150px;">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Fotokopi Ijazah</th>
                                                <td>
                                                    @if ($user->studentRegistration->fotokopi_ijazah)
                                                        <img src="{{ asset($user->studentRegistration->fotokopi_ijazah) }}"
                                                            alt="Fotokopi Ijazah" class="img-thumbnail"
                                                            style="max-height: 150px;">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Fotokopi Akte</th>
                                                <td>
                                                    @if ($user->studentRegistration->fotokopi_akte)
                                                        <img src="{{ asset($user->studentRegistration->fotokopi_akte) }}"
                                                            alt="Fotokopi Akte" class="img-thumbnail"
                                                            style="max-height: 150px;">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>

                                        <h5 class="mb-3 mt-4">F. Tes Fisik</h5>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="40%">Tinggi Badan</th>
                                                <td>{{ $user->studentRegistration->tinggi_badan ?? null ? $user->studentRegistration->tinggi_badan . ' cm' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Berat Badan</th>
                                                <td>{{ $user->studentRegistration->berat_badan ?? null ? $user->studentRegistration->berat_badan . ' kg' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tato</th>
                                                <td>{{ $user->studentRegistration->tato ?? false ? 'Ya' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tindik</th>
                                                <td>{{ $user->studentRegistration->tindik ?? false ? 'Ya' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Buta Warna</th>
                                                <td>{{ $user->studentRegistration->buta_warna ?? false ? 'Ya' : '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Hasil Tes</th>
                                                <td>{{ $user->studentRegistration->hasil_tes ?? '-' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Pas Foto</th>
                                                <td>
                                                    @if ($user->studentRegistration->pas_foto)
                                                        <img src="{{ asset($user->studentRegistration->pas_foto) }}"
                                                            alt="Pas Foto" class="img-thumbnail" style="max-height: 150px;">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showDetailModal(registrationId) {
            document.querySelectorAll('.detail-content').forEach(el => {
                el.style.display = 'none';
            });

            const detailElement = document.getElementById('detail-' + registrationId);
            if (detailElement) {
                detailElement.style.display = 'block';
            } else {
                console.warn('Detail content for registration ID ' + registrationId + ' not found.');
            }

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
