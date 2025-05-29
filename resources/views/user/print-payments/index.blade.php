@extends('layouts.main') {{-- Sesuaikan dengan layout utama Anda --}}

@section('title', 'Riwayat Pembayaran')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold text-primary">Riwayat Pembayaran Anda</h2>
                            <p class="text-muted">Berikut adalah daftar transaksi pembayaran pendaftaran Anda.</p>
                        </div>

                        @if ($payments->isEmpty())
                            <div class="alert alert-info text-center" role="alert">
                                Anda belum memiliki riwayat pembayaran.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Jumlah</th>
                                            <th>Metode</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::parse($payment->created_at)->locale('id')->isoFormat('D MMMM Y') }}
                                                </td>
                                                <td>{{ $payment->formatted_amount }}</td>
                                                <td>{{ $payment->metode_pembayaran ?? '-' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $payment->status == 'dibayar'
                                                            ? 'bg-success'
                                                            : ($payment->status == 'menunggu'
                                                                ? 'bg-warning text-dark'
                                                                : ($payment->status == 'gagal'
                                                                    ? 'bg-danger'
                                                                    : 'bg-secondary')) }}">
                                                        {{ $payment->status_label }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($payment->status == 'dibayar')
                                                        <a href="{{ route('print-payments.receipt', $payment->id) }}"
                                                            target="_blank" class="btn btn-info btn-sm">
                                                            <i class="bi bi-printer me-1"></i> Cetak Kuitansi
                                                        </a>
                                                    @else
                                                        <button class="btn btn-secondary btn-sm" disabled>
                                                            <i class="bi bi-printer me-1"></i> Cetak Kuitansi
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
