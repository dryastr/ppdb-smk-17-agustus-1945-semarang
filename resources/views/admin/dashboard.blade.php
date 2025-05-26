@extends('layouts.main')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard Admin</h1>

        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-primary text-white p-3">
                    <h5>Total User Registrasi</h5>
                    <h2>{{ $totalUsers }}</h2>
                </div>
            </div>

            <div class="col-md-9 mb-3">
                <div class="card p-3">
                    <h5>Status Registrasi User</h5>
                    <div class="d-flex justify-content-around mt-3">
                        <div class="text-center">
                            <span class="badge bg-warning text-dark fs-5">Menunggu</span>
                            <h3>{{ $registrationStatuses['menunggu'] ?? 0 }}</h3>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success fs-5">Diterima</span>
                            <h3>{{ $registrationStatuses['diterima'] ?? 0 }}</h3>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-danger fs-5">Ditolak</span>
                            <h3>{{ $registrationStatuses['ditolak'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3 mb-3">
                <div class="card bg-info text-white p-3">
                    <h5>Total Pembayaran Diterima</h5>
                    <h2>{{ $totalPayments }}</h2>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card bg-success text-white p-3">
                    <h5>Total Nominal Pembayaran</h5>
                    <h2>Rp {{ number_format($totalAmount, 0, ',', '.') }}</h2>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>Status Pembayaran</h5>
                    <div class="d-flex justify-content-around mt-3">
                        <div class="text-center">
                            <span class="badge bg-secondary fs-5">Pending</span>
                            <h3>{{ $paymentStatuses['pending'] ?? 0 }}</h3>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-success fs-5">Diterima</span>
                            <h3>{{ $paymentStatuses['diterima'] ?? 0 }}</h3>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-danger fs-5">Ditolak</span>
                            <h3>{{ $paymentStatuses['ditolak'] ?? 0 }}</h3>
                        </div>
                        <div class="text-center">
                            <span class="badge bg-dark fs-5">Gagal</span>
                            <h3>{{ $paymentStatuses['gagal'] ?? 0 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4 p-3 d-none">
            <h5>Pembayaran Bulanan Tahun {{ date('Y') }}</h5>
            <canvas id="paymentsChart" height="100"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('paymentsChart').getContext('2d');

        const monthlyData = @json(array_values($monthlyData));

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: monthlyData,
                    fill: true,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4, // smooth curve
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: value => 'Rp ' + value.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    </script>
@endsection

@section('scripts')
@endsection
