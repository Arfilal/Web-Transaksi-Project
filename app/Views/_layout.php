<!DOCTYPE html>
<html lang="en">
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

    /* Hover ikon animasi */
    .sidebar a i {
        transition: transform 0.3s ease, color 0.3s ease, text-shadow 0.3s ease;
    }
    .sidebar a:hover i {
        transform: translateY(-3px) scale(1.2);
        color: #fff;
        text-shadow: 0 0 8px rgba(255,255,255,0.6);
    }

    /* Dropdown sidebar */
    .sidebar .collapse a {
        margin-bottom: 0;
        transition: 0.2s;
    }
    .sidebar .collapse a:hover {
        background-color: #6f5ed9;
        font-weight: 600;
        transform: translateX(3px);
    }
    .sidebar a[data-bs-toggle="collapse"] i.bi-chevron-down {
        transition: transform 0.3s ease;
    }

    /* Content */
    .content {
        margin-left: 260px;
        padding: 20px;
        animation: contentFade 0.8s ease forwards;
        opacity: 0;
    }
    @keyframes contentFade { to { opacity: 1; } }

    /* Page Title */
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #c9a0dc;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-shadow: 0 0 8px rgba(201,160,220,0.7);
    }

    /* Table */
    .table {
        color: #fff;
        background-color: #212529;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    }
    .table th {
        background-color: #1f1f1f;
        border-bottom: 2px solid #343A40;
        color: #c9a0dc;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 14px;
    }
    .table td {
        background-color: #2c2c2c;
        color: #f1f1f1;
        vertical-align: middle;
    }
    .table tr:hover td {
        background-color: #343A40;
    }

    /* Pagination */
    .pagination {
        justify-content: center;
        margin-top: 25px;
        gap: 8px;
    }
    .pagination .page-link {
        background-color: #3a3f5c;
        color: #f1f1f1;
        border: none;
        border-radius: 10px;
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
        box-shadow: 0 3px 8px rgba(0,0,0,0.4);
    }
    .pagination .page-link:hover {
        background-color: #8c7ae6;
        color: #fff;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 5px 14px rgba(0,0,0,0.6);
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #c9a0dc, #8c7ae6);
        color: #1a1a2e;
        font-weight: bold;
        transform: scale(1.15);
        box-shadow: 0 6px 16px rgba(201,160,220,0.8);
    }
    .pagination .page-item.disabled .page-link {
        background-color: #6c757d;
        color: #bbb;
        cursor: not-allowed;
        box-shadow: none;
    }
</style>
</head>
<body>

<?php if (strpos(current_url(), '/admin') !== false): ?>
<!-- Sidebar Admin -->
<div class="sidebar">
    <h4><i class="bi bi-speedometer2"></i> Admin Panel</h4>

    <a href="<?= base_url('admin/items') ?>" class="<?= (uri_string() == 'admin/items') ? 'active' : '' ?>">
        <i class="bi bi-box-seam"></i> Manajemen Barang
    </a>

    <!-- Dropdown Stok -->
    <div class="mb-2">
        <a class="d-flex justify-content-between align-items-center <?= (strpos(uri_string(), 'admin/restoker') !== false || strpos(uri_string(), 'admin/restok') !== false) ? 'active' : '' ?>" 
           data-bs-toggle="collapse" href="#stokDropdown" role="button" aria-expanded="false" aria-controls="stokDropdown">
            <span><i class="bi bi-arrow-repeat"></i> Stok</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse <?= (strpos(uri_string(), 'admin/restoker') !== false || strpos(uri_string(), 'admin/restok') !== false) ? 'show' : '' ?>" id="stokDropdown">
            <a href="<?= base_url('admin/restoker') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/restoker') ? 'active' : '' ?>"><i class="bi bi-person-lines-fill"></i> Restoker</a>
            <a href="<?= base_url('admin/restok') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/restok') ? 'active' : '' ?>"><i class="bi bi-arrow-repeat"></i> Restok</a>
            <a href="<?= base_url('admin/restok/history') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/restok/history') ? 'active' : '' ?>"><i class="bi bi-clock-history"></i> History Restok</a>
        </div>
    </div>

    <!-- Dropdown Laporan -->
    <div class="mb-2">
        <a class="d-flex justify-content-between align-items-center <?= (strpos(uri_string(), 'admin/reports') !== false) ? 'active' : '' ?>" 
           data-bs-toggle="collapse" href="#laporanDropdown" role="button" aria-expanded="false" aria-controls="laporanDropdown">
            <span><i class="bi bi-clipboard-data"></i> Laporan</span>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div class="collapse <?= (strpos(uri_string(), 'admin/reports') !== false) ? 'show' : '' ?>" id="laporanDropdown">
            <a href="<?= base_url('admin/reports/stok') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/reports/stok') ? 'active' : '' ?>"><i class="bi bi-clipboard-data"></i> Laporan Stok</a>
            <a href="<?= base_url('admin/reports/transaksi') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/reports/transaksi') ? 'active' : '' ?>"><i class="bi bi-cash-stack"></i> Laporan Transaksi</a>
            <a href="<?= base_url('admin/reports/pengembalian') ?>" class="ps-4 d-block <?= (uri_string() == 'admin/reports/pengembalian') ? 'active' : '' ?>"><i class="bi bi-arrow-counterclockwise"></i> Laporan Pengembalian</a>
        </div>
    </div>
</div>

<div class="content">
    <h3 class="page-title">
        <i class="bi bi-speedometer2"></i>
        <?= $title ?? 'Dashboard Admin' ?>
    </h3>
    <?= $this->renderSection('content') ?>
</div>

<?php else: ?>
<!-- Sidebar Konsumen -->
<div class="sidebar">
    <h4><i class="bi bi-person-circle"></i> Konsumen Panel</h4>
    <a href="<?= base_url('konsumen/pembelian') ?>" class="<?= (uri_string() == 'konsumen/pembelian') ? 'active' : '' ?>">
        <i class="bi bi-cart-check"></i> Pembelian
    </a>
    <a href="<?= base_url('konsumen/riwayat') ?>" class="<?= (uri_string() == 'konsumen/riwayat') ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i> Riwayat
    </a>
    <a href="<?= base_url('konsumen/pengembalian') ?>" class="<?= (uri_string() == 'konsumen/pengembalian') ? 'active' : '' ?>">
        <i class="bi bi-arrow-counterclockwise"></i> Pengembalian
    </a>
</div>

<div class="content">
    <h3 class="page-title">
        <i class="bi bi-person-circle"></i>
        <?= $title ?? 'Dashboard Konsumen' ?>
    </h3>
    <?= $this->renderSection('content') ?>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.sidebar a[data-bs-toggle="collapse"]').forEach(function(drop) {
    const target = document.querySelector(drop.getAttribute('href'));
    const icon = drop.querySelector('i.bi-chevron-down');
    if(target.classList.contains('show')) icon.style.transform = 'rotate(180deg)';

    target.addEventListener('shown.bs.collapse', () => icon.style.transform = 'rotate(180deg)');
    target.addEventListener('hidden.bs.collapse', () => icon.style.transform = 'rotate(0deg)');
});
</script>
</body>
</html>
