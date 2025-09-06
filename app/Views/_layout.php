<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Web Transaksi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        /* Animated gradient background */
        body {
            margin: 0;
            min-height: 100vh;
            background: linear-gradient(-45deg, #8A2BE2, #4B0082, #DA70D6, #6A5ACD);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: Arial, sans-serif;
            color: #E0E0E0;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            width: 250px;
            background-color: rgba(42,42,74,0.95);
            padding: 20px 15px;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
        }
        .sidebar h4 {
            color: #DA70D6;
            margin-bottom: 20px;
            text-shadow: 0 0 8px rgba(218,112,214,0.7);
        }
        .sidebar a {
            display: block;
            padding: 10px 15px;
            margin-bottom: 8px;
            color: #dcdcdc;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background-color: #8c7ae6;
            color: #fff;
            box-shadow: 0 0 15px rgba(140,122,230,0.8);
        }

        /* Dropdown sidebar */
        .sidebar .collapse a:hover {
            background-color: #6f5ed9;
            transform: translateX(3px);
        }
        .sidebar a[data-bs-toggle="collapse"] i.bi-chevron-down {
            transition: transform 0.3s ease;
        }

        /* Main Content Area */
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        /* Header Profile Card */
        .profile-card {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* General Card Style (untuk dashboard & halaman lain) */
        .card {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
        }

        /* Table Style */
        .table {
            color: #fff;
            background-color: rgba(0,0,0,0.2);
            border-radius: 12px;
            overflow: hidden;
        }
        .table th {
            background-color: rgba(0,0,0,0.3);
            color: #fff;
        }
        .table td {
             vertical-align: middle;
        }
        .table-hover tbody tr:hover {
             background-color: rgba(255, 255, 255, 0.05);
             color: #fff;
        }
    </style>
</head>
<body>

<?php
    // Logika untuk menentukan apakah ini halaman admin/dashboard
    $is_admin_area = (strpos(current_url(), '/admin') !== false || strpos(current_url(), 'dashboard') !== false);
?>

<div class="sidebar">
    <?php if ($is_admin_area): ?>
        <h4><i class="bi bi-speedometer2"></i> Admin Panel</h4>
        <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="<?= base_url('admin/items') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/items') !== false) ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i> Manajemen Barang
        </a>
        <div class="mb-2">
            <a class="d-flex justify-content-between align-items-center <?= (strpos(uri_string(), 'admin/restok') !== false || strpos(uri_string(), 'admin/restoker') !== false) ? 'active' : '' ?>" 
               data-bs-toggle="collapse" href="#stokDropdown" role="button">
                <span><i class="bi bi-arrow-repeat"></i> Stok</span> <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse <?= (strpos(uri_string(), 'admin/restok') !== false || strpos(uri_string(), 'admin/restoker') !== false) ? 'show' : '' ?>" id="stokDropdown">
                <a href="<?= base_url('admin/restoker') ?>" class="ps-4 d-block">Restoker</a>
                <a href="<?= base_url('admin/restok') ?>" class="ps-4 d-block">Restok</a>
                <a href="<?= base_url('admin/restok/history') ?>" class="ps-4 d-block">History Restok</a>
            </div>
        </div>
        <div class="mb-2">
            <a class="d-flex justify-content-between align-items-center <?= (strpos(uri_string(), 'admin/reports') !== false) ? 'active' : '' ?>" 
               data-bs-toggle="collapse" href="#laporanDropdown" role="button">
                <span><i class="bi bi-clipboard-data"></i> Laporan</span> <i class="bi bi-chevron-down"></i>
            </a>
            <div class="collapse <?= (strpos(uri_string(), 'admin/reports') !== false) ? 'show' : '' ?>" id="laporanDropdown">
                <a href="<?= base_url('admin/reports/stok') ?>" class="ps-4 d-block">Laporan Stok</a>
                <a href="<?= base_url('admin/reports/transaksi') ?>" class="ps-4 d-block">Laporan Transaksi</a>
                <a href="<?= base_url('admin/reports/pengembalian') ?>" class="ps-4 d-block">Laporan Pengembalian</a>
            </div>
        </div>
    <?php else: ?>
        <h4><i class="bi bi-person-circle"></i> Konsumen Panel</h4>
        <a href="<?= base_url('konsumen/pembelian') ?>" class="nav-link <?= (uri_string() == 'konsumen/pembelian') ? 'active' : '' ?>">
            <i class="bi bi-cart-check"></i> Pembelian
        </a>
        <a href="<?= base_url('konsumen/riwayat') ?>" class="nav-link <?= (uri_string() == 'konsumen/riwayat') ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> Riwayat
        </a>
        <a href="<?= base_url('konsumen/pengembalian') ?>" class="nav-link <?= (uri_string() == 'konsumen/pengembalian') ? 'active' : '' ?>">
            <i class="bi bi-arrow-counterclockwise"></i> Pengembalian
        </a>
    <?php endif; ?>
</div>

<div class="main-content">
    
    <?php if ($is_admin_area && isset($user)): ?>
        <header class="d-flex justify-content-end align-items-center mb-4">
            <div class="profile-card p-2 rounded d-flex align-items-center">
                <img src="<?= esc($user['avatar']) ?>" alt="Avatar" class="rounded-circle me-2" width="40" height="40">
                <div>
                    <strong><?= esc($user['nama']) ?></strong><br>
                    <small><?= esc($user['email']) ?></small>
                </div>
                <a href="<?= base_url('logout') ?>" class="btn btn-sm btn-danger ms-3">Logout</a>
            </div>
        </header>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.sidebar a[data-bs-toggle="collapse"]').forEach(function(drop) {
    const target = document.querySelector(drop.getAttribute('href'));
    const icon = drop.querySelector('i.bi-chevron-down');
    if(target && target.classList.contains('show')) {
        if(icon) icon.style.transform = 'rotate(180deg)';
    }
    if(target) {
        target.addEventListener('shown.bs.collapse', () => { if(icon) icon.style.transform = 'rotate(180deg)' });
        target.addEventListener('hidden.bs.collapse', () => { if(icon) icon.style.transform = 'rotate(0deg)' });
    }
});
</script>
</body>
</html>