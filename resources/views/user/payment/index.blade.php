@extends('layouts.main')

@section('title', 'Pembayaran Pendaftaran')

@push('header-styles')
    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: none;
        }

        .border {
            border-radius: 8px;
        }

        .fw-bold {
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">

            @forelse ($payments as $payment)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">Tagihan Pembayaran Pendaftaran</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="border p-3 mb-3 rounded">
                                    <h5 class="text-primary">Informasi Tagihan</h5>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Nomor Tagihan:</span>
                                        <span>#{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Tanggal Dibuat:</span>
                                        <span>{{ $payment->created_at->format('d M Y H:i') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="fw-bold">Status:</span>
                                        <span
                                            class="badge bg-{{ $payment->status == 'dibayar' ? 'success' : ($payment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($payment->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border p-3 mb-3 rounded">
                                    <h5 class="text-primary">Rekening Tujuan</h5>
                                    @if ($payment->rekening)
                                        <div class="mb-2">
                                            <span class="fw-bold">Bank:</span>
                                            {{ $payment->rekening->nama_bank }}
                                        </div>
                                        <div class="mb-2">
                                            <span class="fw-bold">Nomor Rekening:</span>
                                            {{ $payment->rekening->nomor_rekening }}
                                        </div>
                                        <div class="mb-2">
                                            <span class="fw-bold">Atas Nama:</span>
                                            {{ $payment->rekening->nama_pemilik }}
                                        </div>
                                    @else
                                        <div class="text-danger">Rekening belum ditentukan</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border p-3 mb-3 rounded bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="text-primary mb-0">Jumlah Tagihan</h5>
                                <h4 class="text-primary mb-0">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h4>
                            </div>
                        </div>

                        <div class="border p-3 rounded">
                            <h5 class="text-primary mb-3">Bukti Pembayaran</h5>

                            @if ($payment->bukti_pembayaran)
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="fw-bold me-3">File Bukti:</span>
                                        <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye-fill"></i> Lihat Bukti
                                        </a>
                                    </div>
                                    @if ($payment->metode_pembayaran)
                                        <div class="mb-2">
                                            <span class="fw-bold">Metode Pembayaran:</span>
                                            {{ $payment->metode_pembayaran }}
                                        </div>
                                    @endif
                                    @if ($payment->status == 'pending')
                                        <div class="alert alert-warning mt-2 mb-0">
                                            Pembayaran sedang menunggu verifikasi admin
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Silakan upload bukti pembayaran Anda
                                </div>
                            @endif

                            <div class="d-flex gap-2 mt-3">
                                @if (!$payment->bukti_pembayaran || $payment->status == 'pending')
                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#uploadModal{{ $payment->id }}">
                                        <i class="bi bi-upload"></i> {{ $payment->bukti_pembayaran ? 'Ganti' : 'Upload' }}
                                        Bukti
                                    </button>
                                @endif

                                @if ($payment->status == 'pending')
                                    <form action="{{ route('payment-registration.destroy', $payment->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Hapus pembayaran ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Upload Bukti -->
                <div class="modal fade" id="uploadModal{{ $payment->id }}" tabindex="-1"
                    aria-labelledby="uploadModalLabel{{ $payment->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="{{ route('payment-registration.upload', $payment->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="uploadModalLabel{{ $payment->id }}">
                                        <i class="bi bi-upload"></i> Upload Bukti Pembayaran
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="metode_pembayaran{{ $payment->id }}" class="form-label">Metode
                                            Pembayaran</label>
                                        <select class="form-select" id="metode_pembayaran{{ $payment->id }}"
                                            name="metode_pembayaran" required>
                                            <option value="">Pilih Metode</option>
                                            <option value="Transfer Bank"
                                                {{ $payment->metode_pembayaran == 'Transfer Bank' ? 'selected' : '' }}>
                                                Transfer Bank</option>
                                            <option value="E-Wallet"
                                                {{ $payment->metode_pembayaran == 'E-Wallet' ? 'selected' : '' }}>E-Wallet
                                            </option>
                                            <option value="Tunai"
                                                {{ $payment->metode_pembayaran == 'Tunai' ? 'selected' : '' }}>Tunai
                                            </option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bukti_pembayaran{{ $payment->id }}" class="form-label">Bukti
                                            Pembayaran</label>
                                        <input type="file" class="form-control" id="bukti_pembayaran{{ $payment->id }}"
                                            name="bukti_pembayaran" required accept="image/*">
                                        <div class="form-text">Upload bukti transfer/setoran (format: JPG, PNG, maks 2MB)
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-receipt-cutoff display-4 text-muted"></i>
                        <h4 class="mt-3">Tidak ada tagihan pembayaran</h4>
                        <p class="text-muted">Silakan tunggu admin membuat tagihan pembayaran untuk Anda</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
