<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kuitansi Pembayaran #{{ $payment->id }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .receipt-container {
            width: 100%;
            max-width: 700px;
            background-color: #fff;
            border: 1px solid #ddd;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5em;
            color: #212529;
            margin-bottom: 5px;
        }

        .header p {
            color: #6c757d;
            font-size: 0.9em;
        }

        .receipt-details {
            margin-bottom: 30px;
            border-top: 1px dashed #ced4da;
            padding-top: 20px;
        }

        .receipt-details strong {
            color: #343a40;
        }

        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .receipt-info div {
            width: 48%;
        }

        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .item-table th,
        .item-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }

        .item-table th {
            background-color: #f8f9fa;
            color: #495057;
        }

        .total-section {
            text-align: right;
            margin-top: 20px;
            font-size: 1.2em;
            font-weight: bold;
            border-top: 2px solid #343a40;
            padding-top: 15px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 0.85em;
            color: #6c757d;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 5em;
            color: rgba(0, 123, 255, 0.1);
            font-weight: bold;
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .print-button-container {
            text-align: center;
            margin-top: 30px;
        }

        @media print {
            body {
                background-color: #fff;
            }

            .receipt-container {
                border: none;
                box-shadow: none;
                padding: 0;
                max-width: none;
                width: 100%;
            }

            .print-button-container {
                display: none;
            }

            body {
                font-size: 11pt;
            }

            h1 {
                font-size: 2em !important;
            }

            .receipt-details,
            .item-table,
            .total-section,
            .footer {
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="watermark">LUNAS</div>
        <div class="header">
            <h1>KUITANSI PEMBAYARAN</h1>
            <p><strong>Sekolah SMK 17 Agustus 1945 Semarang</strong></p>
        </div>

        <div class="receipt-details">
            <div class="receipt-info">
                <div>
                    <p>No. Kuitansi: <strong>#{{ $payment->id }}</strong></p>
                    <p>Tanggal Pembayaran:
                        <strong>{{ \Carbon\Carbon::parse($payment->created_at)->locale('id')->isoFormat('D MMMM Y') }}</strong>
                    </p>
                </div>
                <div>
                    <p>Dibayarkan Kepada: <strong>{{ $payment->user->name }}</strong></p>
                    <p>ID Pendaftaran: <strong>{{ $payment->studentRegistration->no_pendaftaran ?? 'N/A' }}</strong>
                    </p>
                </div>
            </div>
            <p>Untuk Pembayaran: <strong>{{ $payment->description ?? 'Biaya Pendaftaran PPDB' }}</strong></p>
        </div>

        <table class="item-table">
            <thead>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->description ?? 'Biaya Pendaftaran PPDB' }}</td>
                    <td>{{ $payment->formatted_amount }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            Total Pembayaran: {{ $payment->formatted_amount }}
        </div>

        <div class="footer">
            <p>Kuitansi ini adalah bukti sah pembayaran Anda. Terima kasih.</p>
            <p>Status Pembayaran: <strong class="text-success">{{ $payment->status_label }}</strong></p>
            <p>Dicetak pada: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y, HH:mm') }}</p>
        </div>
    </div>

    <div class="print-button-container">
        <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
            <i class="bi bi-printer"></i> Cetak Kuitansi Ini
        </button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</body>

</html>
