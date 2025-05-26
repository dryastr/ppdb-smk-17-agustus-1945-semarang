@extends('layouts.main')

@section('title', 'Kelola Pembayaran Registrasi')

@section('content')
    <div class="row">
        <div class="col-12">

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Data Pembayaran Registrasi</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Jumlah</th>
                                    <th>Status</th>
                                    <th>Bukti</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $payment->user->name }}</td>
                                        <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
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
                                        </td>
                                        <td>
                                            @if ($payment->bukti_pembayaran)
                                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}"
                                                    target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                                            @else
                                                <span class="text-muted">Tidak ada</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->keterangan ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning"
                                                onclick="openStatusModal({{ $payment->id }}, '{{ $payment->status }}', '{{ $payment->keterangan }}')">
                                                Ubah Status
                                            </button>
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
                            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
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
