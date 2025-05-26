@extends('layouts.main')

@section('title', 'Dashboard User')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center align-items-center min-vh-50">
            <div class="col-md-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Dashboard Pendaftaran & Pembayaran</h2>
                            <p class="text-muted">Selamat datang, berikut status pendaftaran dan pembayaran Anda.</p>
                        </div>

                        @if ($registration)
                            @if ($registration->status === 'menunggu')
                                <div class="alert alert-warning d-flex align-items-center">
                                    <div class="me-4" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#FFC107" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M12 22c5.421 0 10-4.579 10-10S17.421 2 12 2 2 6.579 2 12s4.579 10 10 10zm0-18c4.411 0 8 3.589 8 8 0 4.411-3.589 8-8 8-4.411 0-8-3.589-8-8 0-4.411 3.589-8 8-8zm-1 4v6l5 3 .75-1.23-4.25-2.55V8H11z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Status Pendaftaran: <strong>Menunggu Konfirmasi</strong></h5>
                                        <p>Silakan tunggu hingga admin memverifikasi pendaftaran Anda.</p>
                                    </div>
                                </div>
                            @elseif ($registration->status === 'diterima')
                                <div class="alert alert-success d-flex align-items-center">
                                    <div class="me-4" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#198754" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M20.285 6.707a1 1 0 0 0-1.415-1.414l-9.193 9.192-4.243-4.243a1 1 0 1 0-1.414 1.414l5 5a1 1 0 0 0 1.414 0l10-10z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Selamat! Anda <strong>DITERIMA</strong>.</h5>
                                        <p>Silakan lanjut ke proses pembayaran melalui link berikut:</p>
                                        <a href="{{ route('payment-registration.index') }}"
                                            class="btn btn-primary mt-2">Lanjut ke Pembayaran</a>
                                    </div>
                                </div>
                            @elseif ($registration->status === 'ditolak')
                                <div class="alert alert-danger d-flex align-items-center">
                                    <div class="me-4" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#DC3545" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M18.364 5.636a1 1 0 0 0-1.414 0L12 10.586 7.05 5.636a1 1 0 1 0-1.414 1.414L10.586 12l-4.95 4.95a1 1 0 1 0 1.414 1.414L12 13.414l4.95 4.95a1 1 0 0 0 1.414-1.414L13.414 12l4.95-4.95a1 1 0 0 0 0-1.414z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Mohon Maaf, Anda <strong>DITOLAK</strong>.</h5>
                                        <p>Alasan:
                                            <strong>{{ $registration->keterangan_status ?? 'Tidak disebutkan' }}</strong>
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($payment)
                                <div class="mt-4">
                                    <h5 class="fw-semibold">Status Pembayaran:</h5>
                                    @php
                                        $color = match ($payment->status) {
                                            'pending' => 'warning',
                                            'dibayar' => 'success',
                                            'gagal' => 'danger',
                                            'ditolak' => 'secondary',
                                            default => 'light',
                                        };
                                    @endphp
                                    <div class="alert alert-{{ $color }}">
                                        <i class="bi bi-cash-coin me-2"></i>
                                        Status: <strong>{{ ucfirst($payment->status) }}</strong><br>
                                        @if (in_array($payment->status, ['gagal', 'ditolak']) && $payment->keterangan)
                                            Keterangan: {{ $payment->keterangan }}
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info d-flex align-items-center">
                                <div class="me-4" style="width:100px; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="#0dcaf0" viewBox="0 0 24 24"
                                        width="100" height="100">
                                        <path
                                            d="M12 2a10 10 0 1 0 10 10A10.0114 10.0114 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h5>Anda belum melakukan pendaftaran.</h5>
                                    <a href="{{ route('student-registrations.index') }}" class="btn btn-primary mt-2">Daftar
                                        Sekarang</a>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
