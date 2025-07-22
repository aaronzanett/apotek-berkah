<?php
$data['saldoOutlet'] = $this->model('BukuKasModel')->getSaldoOutletById($_SESSION['outlet_id'])['total_nominal'];
$data['todaySupply'] = $this->model('SupplyModel')->getTodaySupplyOutlet($_SESSION['outlet_id']);
$data['todayPenjualan'] = $this->model('PenjualanModel')->getTodayPenjualanOutlet($_SESSION['outlet_id']);
$data['monthData'] = $this->model('DashboardMonthlyModel')->getMonthTransactionOutlet($_SESSION['outlet_id']);
?>

<section id="dashboard">
    <div class="stats">
        <div class="dashboard-card">
            <img src="<?= BASEURL ?>/assets/img/icons/keuangan.png" alt="keuangan">
            <h3>Rp. <?= number_format($data['saldoOutlet'], 0, ',', '.') ?></h3>
            <p>Saldo Outlet</p>
        </div>
        <div class="dashboard-card">
            <img src="<?= BASEURL ?>/assets/img/icons/supply.png" alt="supply">
            <h3>Rp. <?= number_format($data['todaySupply'], 0, ',', '.') ?></h3>
            <p>Nominal Supply Hari Ini</p>
        </div>
        <div class="dashboard-card">
            <img src="<?= BASEURL ?>/assets/img/icons/penjualan.png" alt="penjualan">
            <h3>Rp. <?= number_format($data['todayPenjualan'], 0, ',', '.') ?></h3>
            <p>Nominal Penjualan Hari Ini</p>
        </div>
    </div>
    <div class="dashboard-chart">
        <canvas id="dashboardMonthData"></canvas>
    </div>
</section>

<!-- chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const chartData = <?= json_encode($data['monthData']); ?>;

    // Siapkan data untuk chart
    const labels = chartData.map(data => data.tanggal); // Tanggal transaksi
    const penjualanData = chartData.map(data => parseFloat(data.total_penjualan)); // Nominal penjualan
    const supplyData = chartData.map(data => parseFloat(data.total_supply)); // Nominal supply
    const pembelianData = chartData.map(data => parseFloat(data.total_pembelian)); // Nominal pembelian

    // Konfigurasi Chart.js
    const ctx = document.getElementById('dashboardMonthData').getContext('2d');
    new Chart(ctx, {
        type: 'line', // Tipe chart
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Supply',
                    data: supplyData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.2,
                },
                {
                    label: 'Penjualan',
                    data: penjualanData,
                    borderColor: 'rgb(73, 194, 103)', // Warna garis
                    backgroundColor: 'rgba(75, 192, 163, 0.2)', // Warna area
                    tension: 0.2, // Tingkat kelengkungan garis
                },
            ],
        },
        options: {
            responsive: true, // Responsif
            plugins: {
                title: {
                    display: true,
                    text: 'Transaksi dalam 1 Bulan Terakhir' // Judul chart
                },
                legend: {
                    position: 'top', // Posisi legenda
                },
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Tanggal', // Label sumbu X
                    },
                },
                y: {
                    title: {
                        display: true,
                        text: 'Nominal (Rp)', // Label sumbu Y
                    },
                    beginAtZero: true, // Mulai dari 0
                },
            },
        },
    });
</script>