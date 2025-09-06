<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom border-secondary">
    <h1 class="h2">Dashboard</h1>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Penjualan Hari Ini</h6>
                    <h2 class="fw-bold">Rp. <?= number_format($total_penjualan_hari_ini, 0, ',', '.') ?></h2>
                </div>
                <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="card p-3 shadow-sm text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase">Transaksi Baru</h6>
                    <h2 class="fw-bold"><?= $transaksi_baru ?></h2>
                </div>
                 <i class="bi bi-cart-check" style="font-size: 3rem; opacity: 0.5;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card p-3 shadow-sm text-white">
            <h5 class="card-title">Grafik Penjualan (7 Hari Terakhir)</h5>
            <canvas id="salesChart" height="150"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 shadow-sm text-white">
            <h5 class="card-title"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Stok Produk Menipis</h5>
            <?php if (!empty($stok_menipis)): ?>
                <ul class="list-group list-group-flush">
                    <?php foreach ($stok_menipis as $item): ?>
                        <li class="list-group-item bg-transparent text-light d-flex justify-content-between align-items-center border-secondary">
                            <?= esc($item['nama_item']) ?>
                            <span class="badge bg-danger rounded-pill"><?= $item['stok'] ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert mt-3" style="background-color: rgba(40, 167, 69, 0.2); border: none;">
                    <i class="bi bi-check-circle-fill"></i> Stok semua produk aman.
                </div>
            <?php endif; ?>
             <a href="<?= base_url('admin/items') ?>" class="btn btn-outline-light btn-sm mt-3">Lihat Semua Produk &rarr;</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById('salesChart')) {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_map(fn($date) => date('d M', strtotime($date)), $daily_labels)) ?>,
                    datasets: [{
                        label: 'Penjualan',
                        data: <?= json_encode($daily_data) ?>,
                        backgroundColor: 'rgba(218, 112, 214, 0.2)',
                        borderColor: 'rgba(218, 112, 214, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#E0E0E0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        },
                        x: {
                            ticks: { color: '#E0E0E0' },
                            grid: { color: 'rgba(255, 255, 255, 0.1)' }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>