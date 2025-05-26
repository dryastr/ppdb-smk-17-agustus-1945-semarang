@extends('layouts.main')

@section('title', 'Pembayaran Pendaftaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Pembayaran Pendaftaran</h4>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createPaymentModal">
                            Tambah Pembayaran
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
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Metode</th>
                                        <th>Bukti</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($payments as $payment)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>Rp {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $payment->status == 'dibayar' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $payment->metode_pembayaran ?? '-' }}</td>
                                            <td>
                                                @if ($payment->bukti_pembayaran)
                                                    <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}"
                                                        target="_blank">Lihat</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $payment->keterangan ?? '-' }}</td>
                                            <td>
                                                <form action="{{ route('payment-registration.destroy', $payment->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($payments->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center">Belum ada data pembayaran.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createPaymentModal" tabindex="-1" aria-labelledby="createPaymentModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('payment-registration.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createPaymentModalLabel">Tambah Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Pembayaran</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <input type="text" class="form-control" id="metode_pembayaran" name="metode_pembayaran">
                        </div>
                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Upload Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran"
                                accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
