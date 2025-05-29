@extends('layouts.main')

@section('title', 'Dashboard User')

@push('header-styles')
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #eee;
            left: 50%;
            margin-left: -1.5px;
        }

        .timeline-item {
            margin-bottom: 20px;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .timeline-item:nth-child(even) {
            flex-direction: row-reverse;
        }

        .timeline-icon {
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 50%;
            border: 2px solid #eee;
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
        }

        .timeline-icon i {
            /* font-size: 1.8rem; */
            position: relative;
            top: -5px;
        }

        .timeline-content {
            width: calc(50% - 40px);
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .05);
            position: relative;
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-right: auto;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-left: auto;
        }

        .timeline-item.active .timeline-icon {
            border-color: #0d6efd;
        }

        .timeline-item.active .timeline-content {
            background: #e9f5ff;
            border-color: #0d6efd;
        }

        @media (max-width: 768px) {
            .timeline::before {
                left: 20px;
            }

            .timeline-item {
                flex-direction: column !important;
                align-items: flex-start;
            }

            .timeline-icon {
                left: 20px;
                top: 0;
                transform: translateX(-50%);
                position: relative;
                margin-bottom: 10px;
            }

            .timeline-content {
                width: calc(100% - 40px);
                margin-left: 40px !important;
                margin-right: 0 !important;
            }
        }
    </style>
@endpush

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
                            <div class="mb-4">
                                @if ($registration->ppdbStage)
                                    <div class="alert alert-info d-flex align-items-center">
                                        <div class="me-4" style="width:100px; flex-shrink: 0;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#0dcaf0" viewBox="0 0 24 24"
                                                width="100" height="100">
                                                <path
                                                    d="M12 2a10 10 0 1 0 10 10A10.0114 10.0114 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            @if ($registration->ppdbStage)
                                                @php
                                                    $hasPaid = false;
                                                    if (
                                                        $registration->ppdbStage &&
                                                        $registration->ppdbStage->slug === 'pembayaran'
                                                    ) {
                                                        $hasPaid = $registration->payments
                                                            ->where('status', 'dibayar')
                                                            ->isNotEmpty();
                                                    }
                                                @endphp

                                                @if (!$hasPaid && $registration->ppdbStage)
                                                    <div>
                                                        <h5>Anda saat ini berada di Tahap:
                                                            <strong>{{ $registration->ppdbStage->name }}</strong>
                                                        </h5>
                                                        <p>{{ $registration->ppdbStage->description }}</p>
                                                        <p class="mb-0">
                                                            Periode Tahap:
                                                            <strong>{{ \Carbon\Carbon::parse($registration->ppdbStage->start_date)->format('d F Y') }}</strong>
                                                            sampai
                                                            <strong>{{ \Carbon\Carbon::parse($registration->ppdbStage->end_date)->format('d F Y') }}</strong>
                                                        </p>
                                                        @if ($registration->stage_entered_at)
                                                            <small class="text-muted">Anda masuk tahap ini pada:
                                                                {{ \Carbon\Carbon::parse($registration->stage_entered_at)->format('d F Y H:i') }}</small>
                                                        @endif

                                                    </div>
                                                @endif

                                                @if ($registration->ppdbStage->slug === 'pembayaran')
                                                    @php
                                                        $latestPayment = $registration->payments
                                                            ->where('status', 'dibayar')
                                                            ->first();
                                                    @endphp

                                                    @if ($latestPayment && $latestPayment->status === 'dibayar')
                                                        <div class="alert alert-success mt-3" role="alert">
                                                            <h5 class="alert-heading">Pembayaran Selesai!</h5>
                                                            <p>Selamat! Anda telah <b>berhasil menyelesaikan tahap
                                                                    pembayaran</b>. Bukti pembayaran Anda sudah
                                                                terkonfirmasi.</p>
                                                                <br>
                                                            <p class="mb-0 ">Kami akan segera menghubungi Anda melalui
                                                                nomor telepon yang terdaftar
                                                                (<strong>{{ $registration->user->phone_number ?? 'belum tersedia' }}</strong>)
                                                                untuk informasi tahap selanjutnya atau instruksi lebih
                                                                lanjut dari pihak sekolah.</p>
                                                            <hr>
                                                            <p class="mb-0">Terima kasih atas partisipasi Anda.</p>
                                                        </div>
                                                    @else
                                                        @php
                                                            $hasNoPaymentRecords = $registration->payments->isEmpty();

                                                            $hasPaidPayment =
                                                                !$hasNoPaymentRecords &&
                                                                $registration->payments
                                                                    ->where('status', 'dibayar')
                                                                    ->isNotEmpty();
                                                        @endphp
                                                        <p class="mt-2 text-success">Selamat! Anda telah melewati tahap
                                                            sebelumnya dan kini berada di <b>Tahap Pembayaran</b>. Silakan
                                                            selesaikan pembayaran Anda.</p>
                                                        @if ($registration->ppdbStage && $registration->ppdbStage->slug === 'pembayaran')
                                                            @if ($hasPaidPayment)
                                                                {{-- Jika tahap pembayaran dan sudah lunas --}}
                                                                <p class="mt-2 text-success">Selamat! Pembayaran Anda telah
                                                                    terkonfirmasi. Silakan tunggu informasi selanjutnya dari
                                                                    pihak sekolah.</p>
                                                            @elseif ($hasNoPaymentRecords)
                                                                <div class="alert alert-warning mt-2" role="alert">
                                                                    <h6 class="alert-heading mb-1">Informasi Pembayaran
                                                                        Belum Tersedia</h6>
                                                                    <p class="mb-0">Mohon bersabar. Data tagihan
                                                                        pembayaran Anda sedang diproses oleh admin. Silakan
                                                                        cek kembali secara berkala.</p>
                                                                </div>
                                                            @else
                                                                <a href="{{ route('payment-registration.index') }}"
                                                                    class="btn btn-primary btn-sm mt-2">Lanjut ke
                                                                    Pembayaran</a>
                                                            @endif
                                                        @elseif ($registration->ppdbStage->slug === 'tes-fisik')
                                                            <p class="mt-2 text-primary">Harap datang untuk tes fisik pada
                                                                tanggal yang telah ditentukan.</p>
                                                        @endif
                                                    @endif
                                                @elseif ($registration->ppdbStage->slug === 'tes-fisik')
                                                    <p class="mt-2 text-primary">Harap datang untuk tes fisik pada tanggal
                                                        yang telah ditentukan.</p>
                                                @else
                                                    <p class="mt-2 text-white">Silakan ikuti instruksi untuk tahap
                                                        <strong>{{ $registration->ppdbStage->name }}</strong>.
                                                    </p>
                                                @endif
                                            @else
                                                <div class="alert alert-warning" role="alert">
                                                    <h5 class="alert-heading">Informasi Tahap Belum Tersedia</h5>
                                                    <p>Informasi mengenai tahap PPDB Anda saat ini belum tersedia. Mohon cek
                                                        kembali nanti atau hubungi administrator.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-secondary d-flex align-items-center">
                                        <div class="me-4" style="width:100px; flex-shrink: 0;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="#6c757d" viewBox="0 0 24 24"
                                                width="100" height="100">
                                                <path
                                                    d="M12 2a10 10 0 1 0 10 10A10.0114 10.0114 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h5>Status Tahap Pendaftaran: <strong>Belum Ditentukan</strong></h5>
                                            <p>Admin sedang memproses pendaftaran Anda. Mohon tunggu konfirmasi atau
                                                penentuan tahap.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if ($registration->status === 'menunggu' && !$registration->ppdbStage)
                                <div class="alert alert-warning d-flex align-items-center">
                                    <div class="me-4" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#FFC107" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M12 22c5.421 0 10-4.579 10-10S17.421 2 12 2 2 6.579 2 12s4.579 10 10 10zm0-18c4.411 0 8 3.589 8 8 0 4.411-3.589 8-8 8-4.411 0-8-3.589-8-8 0-4.411 3.589-8 8-8zm-1 4v6l5 3 .75-1.23-4.25-2.55V8H11z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Status Berkas: <strong>Menunggu Konfirmasi</strong></h5>
                                        <p>Silakan tunggu hingga admin memverifikasi berkas pendaftaran Anda.</p>
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
                                        <h5>Mohon Maaf, Pendaftaran Anda <strong>DITOLAK</strong>.</h5>
                                        <p>Alasan:
                                            <strong>{{ $registration->keterangan_status ?? 'Tidak disebutkan' }}</strong>
                                        </p>
                                    </div>
                                </div>
                            @elseif ($registration->status === 'diperiksa')
                                <div class="alert alert-info d-flex align-items-center">
                                    <div class="me-4" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#0dcaf0" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M12 2a10 10 0 1 0 10 10A10.0114 10.0114 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Status Berkas: <strong>Dalam Proses Pemeriksaan</strong></h5>
                                        <p>Silakan tunggu hingga admin menyelesaikan pemeriksaan berkas Anda.</p>
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
                                        @if ($payment->status === 'pending' && $registration->ppdbStage && $registration->ppdbStage->slug === 'pembayaran')
                                            <p class="mb-0 mt-2">Segera selesaikan pembayaran Anda.</p>
                                            <a href="{{ route('payment-registration.index') }}"
                                                class="btn btn-info btn-sm mt-2">Lihat Detail Pembayaran</a>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <div class="mt-5">
                                <h4 class="fw-bold text-center mb-4">Alur Pendaftaran PPDB</h4>
                                <div class="timeline">
                                    @foreach ($activeStages as $stage)
                                        <div
                                            class="timeline-item {{ $registration->ppdbStage && $registration->ppdbStage->order >= $stage->order ? 'active' : '' }}">
                                            <div class="timeline-icon">
                                                @php
                                                    $isPaymentStageAndPaid = false;
                                                    if ($stage->slug === 'pembayaran') {
                                                        $hasPaidPayment = $registration->payments
                                                            ->where('status', 'dibayar')
                                                            ->isNotEmpty();
                                                        if ($hasPaidPayment) {
                                                            $isPaymentStageAndPaid = true;
                                                        }
                                                    }
                                                @endphp

                                                @if ($isPaymentStageAndPaid)
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($registration->ppdbStage && $registration->ppdbStage->order > $stage->order)
                                                    <i class="bi bi-check-circle-fill text-success"></i>
                                                @elseif ($registration->ppdbStage && $registration->ppdbStage->order == $stage->order)
                                                    <i class="bi bi-arrow-right-circle-fill text-primary"></i>
                                                @else
                                                    <i class="bi bi-circle-fill text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="timeline-content">
                                                <h5>{{ $stage->name }}</h5>
                                                <p>{{ $stage->description }}</p>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($stage->start_date)->format('d M Y') }} -
                                                    {{ \Carbon\Carbon::parse($stage->end_date)->format('d M Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            @php
                                $firstStage = $activeStages->first();
                            @endphp

                            @if ($firstStage)
                                <div
                                    class="alert alert-info d-flex align-items-center flex-column flex-md-row text-center text-md-start">
                                    <div class="me-md-4 mb-3 mb-md-0" style="width:100px; flex-shrink: 0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="#0dcaf0" viewBox="0 0 24 24"
                                            width="100" height="100">
                                            <path
                                                d="M12 2a10 10 0 1 0 10 10A10.0114 10.0114 0 0 0 12 2zm1 14h-2v-2h2zm0-4h-2V7h2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h5>Anda belum melakukan pendaftaran.</h5>
                                        <p>Selamat datang! Untuk memulai proses PPDB, silakan daftarkan diri Anda.</p>
                                        <p class="mb-2">Tahap awal pendaftaran adalah:
                                            <strong>{{ $firstStage->name }}</strong>.
                                        </p>
                                        <p class="mb-0">{{ $firstStage->description }}</p>
                                        <small class="text-muted">Periode:
                                            {{ \Carbon\Carbon::parse($firstStage->start_date)->format('d F Y') }} -
                                            {{ \Carbon\Carbon::parse($firstStage->end_date)->format('d F Y') }}</small>
                                        <br>
                                        <a href="{{ route('student-registrations.index') }}"
                                            class="btn btn-primary mt-3">Daftar Sekarang</a>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning" role="alert">
                                    <h5>Informasi Pendaftaran Belum Tersedia</h5>
                                    <p>Sistem pendaftaran belum dibuka atau tidak ada tahap pendaftaran aktif saat ini.
                                        Silakan coba lagi nanti atau hubungi administrator.</p>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
