@extends('layouts.app') @section('content')
<div class="container">
    <h2 class="mb-4">Laporan Omzet Penjualan</h2>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6>Hari Ini</h6>
                    <h3>Rp {{ number_format($omzetHarian) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6>Minggu Ini</h6>
                    <h3>Rp {{ number_format($omzetMingguan) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h6>Bulan Ini</h6>
                    <h3>Rp {{ number_format($omzetBulanan) }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <h6>Tahun Ini</h6>
                    <h3>Rp {{ number_format($omzetTahunan) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">Grafik Penjualan (30 Hari Terakhir)</div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!}, // Mengambil data tanggal dari controller
            datasets: [{
                label: 'Omzet (Rp)',
                data: {!! json_encode($totals) !!}, // Mengambil data total dari controller
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3 // Membuat garis agak melengkung
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection