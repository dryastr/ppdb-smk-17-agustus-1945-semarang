<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\StudentRegistration;
use App\Models\Rekening;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class ManageHistoriPaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['user', 'studentRegistration', 'rekening'])
            ->latest()
            ->get();

        $registrations = StudentRegistration::whereDoesntHave('payment', function ($query) {
            $query->whereIn('status', ['pending', 'dibayar', 'diterima']);
        })->orWhereHas('payment', function ($query) {
            $query->whereIn('status', ['ditolak', 'gagal']);
        })->get();
        $rekenings = Rekening::all();

        return view('admin.manage-history.index', compact('payments', 'registrations', 'rekenings'));
    }

    public function exportExcel()
    {
        // Ambil data untuk setiap status
        $paymentsPending = Payment::with(['studentRegistration', 'rekening', 'user'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $paymentsDibayar = Payment::with(['studentRegistration', 'rekening', 'user'])
            ->where('status', 'dibayar')
            ->latest()
            ->get();

        $paymentsDitolak = Payment::with(['studentRegistration', 'rekening', 'user'])
            ->where('status', 'ditolak')
            ->latest()
            ->get();

        $paymentsGagal = Payment::with(['studentRegistration', 'rekening', 'user'])
            ->where('status', 'gagal')
            ->latest()
            ->get();

        // Hitung total jumlah untuk pembayaran 'dibayar'
        $totalAmountDibayar = $paymentsDibayar->sum('amount');

        $spreadsheet = new Spreadsheet();

        // --- SHEET: PENDING ---
        $sheetPending = $spreadsheet->getActiveSheet();
        $sheetPending->setTitle('Pending');
        $this->writePaymentDataToSheet($sheetPending, $paymentsPending, false); // false = tidak ada total

        // --- SHEET: DIBAYAR ---
        $sheetDibayar = $spreadsheet->createSheet(); // Buat sheet baru
        $sheetDibayar->setTitle('Dibayar');
        $this->writePaymentDataToSheet($sheetDibayar, $paymentsDibayar, true, $totalAmountDibayar); // true = ada total

        // --- SHEET: DITOLAK ---
        $sheetDitolak = $spreadsheet->createSheet(); // Buat sheet baru
        $sheetDitolak->setTitle('Ditolak');
        $this->writePaymentDataToSheet($sheetDitolak, $paymentsDitolak, false);

        // --- SHEET: GAGAL ---
        $sheetGagal = $spreadsheet->createSheet(); // Buat sheet baru
        $sheetGagal->setTitle('Gagal');
        $this->writePaymentDataToSheet($sheetGagal, $paymentsGagal, false);

        // Hapus sheet default yang kosong jika ada (biasanya sheet 0)
        // Periksa apakah sheet default masih ada sebelum dihapus,
        // karena sheet pertama yang dibuat dengan getActiveSheet() bisa saja sudah digunakan.
        // Jika Anda ingin memastikan sheet "Pending" selalu yang pertama, ini sudah benar.

        $writer = new Xlsx($spreadsheet);
        $fileName = 'data_pembayaran_' . date('Ymd_His') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($tempFile);

        return Response::download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Helper function to write payment data to a given sheet.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \Illuminate\Support\Collection $payments
     * @param bool $includeTotal
     * @param float $totalAmount = 0
     * @return void
     */
    private function writePaymentDataToSheet($sheet, $payments, $includeTotal, $totalAmount = 0)
    {
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'No. Pendaftaran');
        $sheet->setCellValue('C1', 'Nama Pendaftar');
        $sheet->setCellValue('D1', 'Email Pendaftar');
        $sheet->setCellValue('E1', 'Jumlah Pembayaran');
        $sheet->setCellValue('F1', 'Bank Tujuan');
        $sheet->setCellValue('G1', 'No. Rekening Tujuan');
        $sheet->setCellValue('H1', 'Nama Pemilik Rekening');
        $sheet->setCellValue('I1', 'Status');
        $sheet->setCellValue('J1', 'Metode Pembayaran');
        $sheet->setCellValue('K1', 'Keterangan');
        $sheet->setCellValue('L1', 'Tanggal Transaksi');

        $sheet->getStyle('A1:L1')->getFont()->setBold(true);

        $row = 2;
        foreach ($payments as $index => $payment) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $payment->studentRegistration->no_pendaftaran ?? '-');
            $sheet->setCellValue('C' . $row, $payment->studentRegistration->nama ?? '-');
            $sheet->setCellValue('D' . $row, $payment->studentRegistration->email ?? '-');
            $sheet->setCellValue('E' . $row, $payment->amount);
            $sheet->setCellValue('F' . $row, $payment->rekening->nama_bank ?? '-');
            $sheet->setCellValue('G' . $row, $payment->rekening->nomor_rekening ?? '-');
            $sheet->setCellValue('H' . $row, $payment->rekening->nama_pemilik ?? '-');
            $sheet->setCellValue('I' . $row, ucfirst($payment->status));
            $sheet->setCellValue('J' . $row, $payment->metode_pembayaran ?? '-');
            $sheet->setCellValue('K' . $row, $payment->keterangan ?? '-');
            $sheet->setCellValue('L' . $row, $payment->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        if ($includeTotal) {
            $row++;
            $sheet->setCellValue('A' . $row, 'Catatan: Total jumlah pembayaran di bawah ini dihitung berdasarkan pembayaran dengan status "Dibayar" saja.');
            $sheet->mergeCells('A' . $row . ':L' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED);

            $sheet->setCellValue('A' . $row, 'TOTAL PEMBAYARAN (DIBAYAR):');
            $sheet->setCellValue('B' . $row, 'Rp ' . number_format($totalAmount, 0, ',', '.'));
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true)->setSize(12);
            $sheet->getStyle('A' . $row . ':B' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFCC00'); // Warna kuning
            $sheet->getStyle('A' . $row . ':B' . $row)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }
}
