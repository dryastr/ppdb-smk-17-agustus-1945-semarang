@extends('layouts.main')

@section('title', 'Riwayat Pembayaran Pendaftaran')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Riwayat Pembayaran Pendaftaran</h4>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        @if ($payments->isEmpty())
                            <div class="alert alert-info text-center" role="alert">
                                Anda belum memiliki riwayat pembayaran untuk pendaftaran ini.
                                <br>
                                <small>Silakan lakukan pendaftaran atau cek status pembayaran Anda.</small>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-xl table-hover  align-middle">
                                    <thead class="">
                                        <tr>
                                            <th scope="col" class="text-center">#</th>
                                            <th scope="col">No. Pendaftaran</th>
                                            <th scope="col">Jumlah Pembayaran</th>
                                            <th scope="col">Rekening Tujuan</th>
                                            <th scope="col" class="text-center">Status</th>
                                            <th scope="col" class="text-center">Bukti</th>
                                            <th scope="col">Keterangan</th>
                                            <th scope="col">Tanggal Transaksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payments as $index => $payment)
                                            <tr>
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $payment->studentRegistration->no_pendaftaran ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $payment->studentRegistration->nama ?? 'Nama Pendaftar' }}</small>
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        Rp{{ number_format($payment->amount, 0, ',', '.') }}
                                                    </strong>
                                                </td>
                                                <td>
                                                    @if ($payment->rekening)
                                                        {{ $payment->rekening->nama_bank }}<br>
                                                        <small
                                                            class="text-muted">{{ $payment->rekening->nomor_rekening }}</small><br>
                                                        <small
                                                            class="text-muted">{{ $payment->rekening->nama_pemilik }}</small>
                                                    @else
                                                        <span class="text-danger">Rekening tidak tersedia</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $status = $payment->status;
                                                        $statusColors = [
                                                            'pending' => 'badge bg-warning text-dark',
                                                            'ditolak' => 'badge bg-danger',
                                                            'gagal' => 'badge bg-secondary',
                                                            'diterima' => 'badge bg-success',
                                                        ];
                                                        $displayStatus = ucfirst($status);
                                                    @endphp
                                                    <span class="{{ $statusColors[$status] ?? 'badge bg-info' }}">
                                                        {{ $displayStatus }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($payment->bukti_pembayaran)
                                                        <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}"
                                                            target="_blank" class="btn btn-sm btn-primary">Lihat Bukti</a>
                                                    @else
                                                        <span class="text-muted">Belum ada</span>
                                                    @endif
                                                </td>
                                                <td>{{ $payment->keterangan ?? '-' }}</td>
                                                <td>{{ $payment->created_at->format('d M Y') }}</td>
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
