@extends('layouts.main')

@section('title', 'Kelola Pembayaran Registrasi')

@push('header-styles')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Pembayaran Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('manage-payment-registration.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Pendaftar</label>
                                    <select name="student_registration_id" class="form-control tom-select" required>
                                        <option value="">Pilih Pendaftar</option>
                                        @foreach ($registrations->where('status', '!=', 'diterima') as $reg)
                                            <option value="{{ $reg->id }}">
                                                {{ $reg->nama }} - {{ $reg->no_pendaftaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Rekening Tujuan</label>
                                    <select name="rekening_id" class="form-control" required>
                                        <option value="">Pilih Rekening</option>
                                        @foreach ($rekenings as $rekening)
                                            <option value="{{ $rekening->id }}">
                                                {{ $rekening->nama_bank }} - {{ $rekening->nomor_rekening }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">Jumlah Pembayaran</label>
                                    <input type="number" name="amount" class="form-control" required min="1000">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary w-100">Tambah</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">Data Pembayaran Registrasi</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="unpaid-tab" data-bs-toggle="tab" data-bs-target="#unpaid" type="button" role="tab" aria-controls="unpaid" aria-selected="true">Belum Bayar</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="paid-tab" data-bs-toggle="tab" data-bs-target="#paid" type="button" role="tab" aria-controls="paid" aria-selected="false">Sudah Bayar</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="paymentTabsContent">
                        <div class="tab-pane fade show active" id="unpaid" role="tabpanel" aria-labelledby="unpaid-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-xl">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Pendaftaran</th>
                                            <th>Pendaftar</th>
                                            <th>Jumlah</th>
                                            <th>Rekening Tujuan</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $unpaidCount = 0; @endphp
                                        @foreach ($payments as $payment)
                                            @if (is_null($payment->bukti_pembayaran))
                                                @php $unpaidCount++; @endphp
                                                <tr>
                                                    <td>{{ $unpaidCount }}</td>
                                                    <td>{{ $payment->studentRegistration->no_pendaftaran }}</td>
                                                    <td>
                                                        <strong>{{ $payment->studentRegistration->nama_lengkap }}</strong><br>
                                                        <small>{{ $payment->user->name }}</small>
                                                    </td>
                                                    <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($payment->rekening)
                                                            {{ $payment->rekening->nama_bank }}<br>
                                                            {{ $payment->rekening->nomor_rekening }}<br>
                                                            <small>{{ $payment->rekening->nama_pemilik }}</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $status = $payment->status;
                                                            $statusColors = [
                                                                'pending' => 'bg-warning text-dark',
                                                                'dibayar' => 'bg-success text-white',
                                                                'gagal' => 'bg-danger text-white',
                                                                'ditolak' => 'bg-secondary text-white',
                                                            ];
                                                        @endphp
                                                        <span class="badge {{ $statusColors[$status] ?? 'bg-light text-dark' }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">Belum upload</span>
                                                    </td>
                                                    <td>{{ $payment->keterangan ?? '-' }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning mb-1"
                                                            onclick="openStatusModal({{ $payment->id }}, '{{ $payment->status }}', '{{ $payment->keterangan }}')">
                                                            Ubah Status
                                                        </button>
                                                        <form action="{{ route('manage-payment-registration.destroy', $payment->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Hapus pembayaran ini?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="paid" role="tabpanel" aria-labelledby="paid-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-xl">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No Pendaftaran</th>
                                            <th>Pendaftar</th>
                                            <th>Jumlah</th>
                                            <th>Rekening Tujuan</th>
                                            <th>Status</th>
                                            <th>Bukti</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $paidCount = 0; @endphp
                                        @foreach ($payments as $payment)
                                            @if (!is_null($payment->bukti_pembayaran))
                                                @php $paidCount++; @endphp
                                                <tr>
                                                    <td>{{ $paidCount }}</td>
                                                    <td>{{ $payment->studentRegistration->no_pendaftaran }}</td>
                                                    <td>
                                                        <strong>{{ $payment->studentRegistration->nama_lengkap }}</strong><br>
                                                        <small>{{ $payment->user->name }}</small>
                                                    </td>
                                                    <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                                    <td>
                                                        @if ($payment->rekening)
                                                            {{ $payment->rekening->nama_bank }}<br>
                                                            {{ $payment->rekening->nomor_rekening }}<br>
                                                            <small>{{ $payment->rekening->nama_pemilik }}</small>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @php
                                                            $status = $payment->status;
                                                            $statusColors = [
                                                                'pending' => 'bg-warning text-dark',
                                                                'dibayar' => 'bg-success text-white',
                                                                'gagal' => 'bg-danger text-white',
                                                                'ditolak' => 'bg-secondary text-white',
                                                            ];
                                                        @endphp
                                                        <span class="badge {{ $statusColors[$status] ?? 'bg-light text-dark' }}">
                                                            {{ ucfirst($status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}"
                                                            target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                                                    </td>
                                                    <td>{{ $payment->keterangan ?? '-' }}</td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning mb-1"
                                                            onclick="openStatusModal({{ $payment->id }}, '{{ $payment->status }}', '{{ $payment->keterangan }}')">
                                                            Ubah Status
                                                        </button>
                                                        <form action="{{ route('manage-payment-registration.destroy', $payment->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger"
                                                                onclick="return confirm('Hapus pembayaran ini?')">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select name="status" id="statusSelect" class="form-control" required
                                onchange="toggleKeterangan()">
                                <option value="pending">Pending</option>
                                <option value="dibayar">Diterima</option>
                                <option value="gagal">Gagal</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3" id="keteranganGroup" style="display: none;">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                            <small class="text-muted">Wajib diisi jika status Gagal atau Ditolak</small>
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

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new TomSelect('.tom-select', {
                create: false,
                allowEmptyOption: true,
                placeholder: 'Cari pendaftar...'
            });
        });
    </script>
    <script>
        function openStatusModal(id, status, keterangan) {
            const form = document.getElementById('statusForm');
            form.action = '/manage-payment-registration/update-status/' + id;
            document.getElementById('statusSelect').value = status;
            document.getElementById('keterangan').value = keterangan || '';
            toggleKeterangan();
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }

        function toggleKeterangan() {
            const status = document.getElementById('statusSelect').value;
            const keteranganGroup = document.getElementById('keteranganGroup');
            if (status === 'gagal' || status === 'ditolak') {
                keteranganGroup.style.display = 'block';
            } else {
                keteranganGroup.style.display = 'none';
                document.getElementById('keterangan').value = '';
            }
        }
    </script>
@endsection
