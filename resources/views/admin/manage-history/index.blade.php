@extends('layouts.main') 

@section('title', 'Kelola Pembayaran Pendaftaran')

@section('content')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Daftar Pembayaran Pendaftaran</h4>
                        <div>
                            <a href="{{ route('manage-history.exportExcel') }}" class="btn btn-success btn-sm me-2">
                                <i class="bi bi-file-earmark-excel"></i> Export Excel
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-xl table-hover align-middle">
                            <thead class="">
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">No. Pendaftaran</th>
                                    <th scope="col">Pendaftar</th>
                                    <th scope="col">Jumlah</th>
                                    <th scope="col">Rekening Tujuan</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col" class="text-center">Bukti</th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($payments as $index => $payment)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $payment->studentRegistration->no_pendaftaran ?? '-' }}</td>
                                        <td>
                                            <strong>{{ $payment->studentRegistration->nama ?? 'N/A' }}</strong><br>
                                            <small
                                                class="text-muted">{{ $payment->user->name ?? 'User Tidak Ditemukan' }}</small>
                                        </td>
                                        <td>Rp{{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($payment->rekening)
                                                {{ $payment->rekening->nama_bank }}<br>
                                                {{ $payment->rekening->nomor_rekening }}<br>
                                                <small>{{ $payment->rekening->nama_pemilik }}</small>
                                            @else
                                                <span class="text-danger">Tidak Ditemukan</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @php
                                                $status = $payment->status;
                                                $statusColors = [
                                                    'pending' => 'badge bg-warning text-dark',
                                                    'gagal' => 'badge bg-danger',
                                                    'ditolak' => 'badge bg-secondary',
                                                    'diterima' => 'badge bg-success',
                                                ];
                                            @endphp
                                            <span class="{{ $statusColors[$status] ?? 'badge bg-info' }}">
                                                {{ ucfirst($status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if ($payment->bukti_pembayaran)
                                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}"
                                                    target="_blank" class="btn btn-sm btn-primary">Lihat</a>
                                            @else
                                                <span class="text-muted">Belum upload</span>
                                            @endif
                                        </td>
                                        <td>{{ $payment->keterangan ?? '-' }}</td>
                                        <td class="text-center text-nowrap">
                                            <button class="btn btn-sm btn-info text-white mb-1"
                                                onclick="printReceipt({{ $payment->id }})">
                                                Cetak Kuitansi
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">Tidak ada data pembayaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="statusModalLabel">Ubah Status Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="statusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="paymentId" name="id">
                        <div class="mb-3">
                            <label for="statusSelect" class="form-label">Status</label>
                            <select class="form-select" id="statusSelect" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="dibayar">Dibayar</option>
                                <option value="gagal">Gagal</option>
                                <option value="ditolak">Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="keteranganTextarea" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="keteranganTextarea" name="keterangan" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="receipt-print-area" style="display: none;">
    </div>

@endsection

@push('scripts')
    <script>
        function openStatusModal(paymentId, currentStatus, currentKeterangan) {
            document.getElementById('paymentId').value = paymentId;
            document.getElementById('statusSelect').value = currentStatus;
            document.getElementById('keteranganTextarea').value = currentKeterangan;
            document.getElementById('statusForm').action = `{{ url('admin/manage-payments') }}/${paymentId}/status`;
            var statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
            statusModal.show();
        }

        function printReceipt(paymentId) {
            fetch(`/payments-print/${paymentId}`)
                .then(response => response.json())
                .then(data => {
                    const payment = data;

                    let receiptContent = `
                    <div style="font-family: Arial, sans-serif; padding: 30px; max-width: 600px; margin: 0 auto; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
                            <h2 style="color: #333; margin-bottom: 5px;">KUITANSI PEMBAYARAN</h2>
                            <p style="font-size: 14px; color: #666;">Bukti Pembayaran Pendaftaran</p>
                        </div>

                        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee; width: 30%;"><strong>No. Kuitansi</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">: ${payment.id}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;"><strong>Tanggal</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">: ${new Date(payment.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;"><strong>No. Pendaftaran</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">: ${payment.student_registration.no_pendaftaran || '-'}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;"><strong>Nama Pendaftar</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">: ${payment.student_registration.nama_lengkap || '-'}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;"><strong>Pembayaran Untuk</strong></td>
                                <td style="padding: 8px 0; border-bottom: 1px dashed #eee;">: Biaya Pendaftaran</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0;"><strong>Status Pembayaran</strong></td>
                                <td style="padding: 8px 0;">: <span style="font-weight: bold; color: ${getStatusColor(payment.status)};">${payment.status.toUpperCase()}</span></td>
                            </tr>
                        </table>

                        <div style="margin-bottom: 20px; text-align: center; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
                            <p style="font-size: 16px; margin-bottom: 5px;">Jumlah Pembayaran</p>
                            <h3 style="color: #28a745; margin: 0;">Rp${new Intl.NumberFormat('id-ID').format(payment.amount)}</h3>
                        </div>

                        <div style="margin-bottom: 20px;">
                            <p style="font-size: 14px; margin-bottom: 5px;"><strong>Metode Pembayaran:</strong> ${payment.metode_pembayaran || 'Transfer Bank'}</p>
                            <p style="font-size: 14px; margin-bottom: 5px;"><strong>Rekening Tujuan:</strong> ${payment.rekening.nama_bank || '-'} - ${payment.rekening.nomor_rekening || '-'} (A.N. ${payment.rekening.nama_pemilik || '-'})</p>
                            ${payment.keterangan ? `<p style="font-size: 14px;"><strong>Catatan Admin:</strong> ${payment.keterangan}</p>` : ''}
                        </div>

                        <div style="text-align: right; margin-top: 40px;">
                            <p style="margin-bottom: 50px;">Hormat Kami,</p>
                            <p><strong>Nama Admin</strong></p>
                            <p style="font-size: 12px;">Jabatan</p>
                        </div>

                        <div style="text-align: center; margin-top: 30px; font-size: 12px; color: #999;">
                            Terima kasih atas pembayaran Anda.
                        </div>
                    </div>
                `;

                    document.getElementById('receipt-print-area').innerHTML = receiptContent;

                    const printWindow = window.open('', '', 'height=600,width=800');
                    printWindow.document.write('<html><head><title>Kuitansi Pembayaran</title>');
                    printWindow.document.write('<style>');
                    printWindow.document.write(`
                    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                    .receipt-container { padding: 30px; max-width: 600px; margin: 20px auto; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
                    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .header h2 { color: #333; margin-bottom: 5px; }
                    .header p { font-size: 14px; color: #666; }
                    table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
                    table td { padding: 8px 0; border-bottom: 1px dashed #eee; }
                    table tr:last-child td { border-bottom: none; }
                    .amount-box { margin-bottom: 20px; text-align: center; padding: 15px; background-color: #f8f9fa; border-radius: 5px; }
                    .amount-box h3 { color: #28a745; margin: 0; }
                    .footer-signature { text-align: right; margin-top: 40px; }
                    .footer-note { text-align: center; margin-top: 30px; font-size: 12px; color: #999; }
                    @media print {
                        body { background-color: #fff; }
                        .receipt-container { border: none; box-shadow: none; margin: 0; padding: 0; }
                    }
                `);
                    printWindow.document.write('</style>');
                    printWindow.document.write('</head><body>');
                    printWindow.document.write(receiptContent);
                    printWindow.document.write('</body></html>');
                    printWindow.document.close();
                    printWindow.print();
                })
                .catch(error => {
                    console.error('Error fetching payment details:', error);
                    alert('Gagal mengambil detail pembayaran untuk kuitansi.');
                });
        }

        function getStatusColor(status) {
            switch (status) {
                case 'pending':
                    return '#ffc107';
                case 'diterima':
                    return '#28a745';
                case 'gagal':
                    return '#dc3545';
                case 'ditolak':
                    return '#6c757d';
                default:
                    return '#17a2b8';
            }
        }
    </script>
@endpush
