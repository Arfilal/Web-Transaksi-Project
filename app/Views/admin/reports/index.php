<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Laporan Penjualan</h1>
    
<div class="d-flex justify-content-center mb-4">
    <button class="btn btn-dark me-2" onclick="showReport('harian')">Laporan Harian</button>
    <button class="btn btn-dark" onclick="showReport('mingguan')">Laporan Mingguan</button>
    <a href="<?= base_url('admin/reports/export-pdf') ?>" class="btn btn-danger ms-4">Export PDF</a>
    <a href="<?= base_url('admin/reports/export-excel') ?>" class="btn btn-success ms-2">Export Excel</a>
</div>

<div id="harian-report">
    <div class="card p-4">
        <h2 class="text-center">Penjualan Harian</h2>
        <div class="d-flex justify-content-around">
            <div style="width: 400px; height: 400px;">
                <canvas id="dailyBarChart"></canvas>
            </div>
            <div style="width: 400px; height: 400px;">
                <canvas id="itemPieChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div id="mingguan-report" style="display:none;">
    <div class="card p-4">
        <h2 class="text-center">Penjualan Mingguan</h2>
        <div class="d-flex justify-content-around">
            <div style="width: 400px; height: 400px;">
                <canvas id="weeklyBarChart"></canvas>
            </div>
            <div style="width: 400px; height: 400px;">
                <canvas id="itemPieChart2"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function showReport(reportType) {
        document.getElementById('harian-report').style.display = 'none';
        document.getElementById('mingguan-report').style.display = 'none';
        
        if (reportType === 'harian') {
            document.getElementById('harian-report').style.display = 'block';
        } else {
            document.getElementById('mingguan-report').style.display = 'block';
        }
    }

    window.onload = function() {
        showReport('harian');
    };

    // Ambil data dari controller
    const dailyLabels = <?= json_encode($daily_labels) ?>;
    const dailyData = <?= json_encode($daily_data) ?>;
    const weeklyLabels = <?= json_encode($weekly_labels) ?>;
    const weeklyData = <?= json_encode($weekly_data) ?>;
    const itemLabels = <?= json_encode($item_labels) ?>;
    const itemData = <?= json_encode($item_data) ?>;

    // --- Chart Penjualan Harian ---
    new Chart(document.getElementById('dailyBarChart'), {
        type: 'bar',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Total Penjualan Harian',
                data: dailyData,
                backgroundColor: 'rgba(154, 114, 255, 0.7)',
                borderColor: 'rgba(128, 77, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });
    
    // --- Pie Chart Item (Harian) ---
    new Chart(document.getElementById('itemPieChart'), {
        type: 'pie',
        data: {
            labels: itemLabels,
            datasets: [{
                label: 'Jumlah Pembelian per Barang',
                data: itemData,
                backgroundColor: [
                    'rgba(154, 114, 255, 0.7)', 'rgba(170, 130, 255, 0.7)',
                    'rgba(186, 146, 255, 0.7)', 'rgba(202, 162, 255, 0.7)',
                    'rgba(218, 178, 255, 0.7)', 'rgba(234, 194, 255, 0.7)',
                    'rgba(250, 210, 255, 0.7)'
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { position: 'right' } }
        }
    });

    // --- Chart Penjualan Mingguan ---
    new Chart(document.getElementById('weeklyBarChart'), {
        type: 'bar',
        data: {
            labels: weeklyLabels,
            datasets: [{
                label: 'Total Penjualan Mingguan',
                data: weeklyData,
                backgroundColor: 'rgba(154, 114, 255, 0.7)',
                borderColor: 'rgba(128, 77, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // --- Pie Chart Item (Mingguan) ---
    new Chart(document.getElementById('itemPieChart2'), {
        type: 'pie',
        data: {
            labels: itemLabels,
            datasets: [{
                label: 'Jumlah Pembelian per Barang',
                data: itemData,
                backgroundColor: [
                    'rgba(154, 114, 255, 0.7)', 'rgba(170, 130, 255, 0.7)',
                    'rgba(186, 146, 255, 0.7)', 'rgba(202, 162, 255, 0.7)',
                    'rgba(218, 178, 255, 0.7)', 'rgba(234, 194, 255, 0.7)',
                    'rgba(250, 210, 255, 0.7)'
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            responsive: true,
            plugins: { legend: { position: 'right' } }
        }
    });
</script>
<?= $this->endSection() ?>
