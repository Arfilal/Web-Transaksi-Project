<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Web Transaksi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1a1a2e; /* Warna ungu gelap soft */
            color: #dcdcdc;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            background-color: #2a2a4a;
            border: none;
            border-radius: 15px;
            color: #dcdcdc;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        }
        .btn-purple {
            background-color: #8c7ae6;
            border: none;
            color: #FFFFFF;
        }
        .btn-purple:hover {
            background-color: #7b68ee;
        }
        h1, h2, h3 {
            color: #c9a0dc;
        }
        .table {
            color: #dcdcdc;
        }
        .table th {
            border-bottom-color: #c9a0dc;
        }
        .navbar {
            background-color: #2a2a4a !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .nav-link {
            color: #dcdcdc !important;
        }
        .nav-link.active, .nav-link:hover, 
        .dropdown-item.active, .dropdown-item:hover {
            color: #c9a0dc !important;
            font-weight: bold;
            background-color: transparent !important;
        }
        .dropdown-menu {
            background-color: #2a2a4a;
        }
        .dropdown-item {
            color: #dcdcdc;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= base_url('/') ?>">Web Transaksi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <?php if (strpos(current_url(), '/admin') !== false): ?>
                <!-- Navigasi untuk Admin -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'admin/items') ? 'active' : '' ?>" href="<?= base_url('admin/items') ?>">Manajemen Barang</a>
                    </li>

                    <!-- Dropdown Menu Stok -->
                    <!-- Dropdown Menu Stok -->
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle <?= (uri_string() == 'admin/restok' || uri_string() == 'admin/restoker' || uri_string() == 'admin/restok/history') ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Stok
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item <?= (uri_string() == 'admin/restoker') ? 'active' : '' ?>" href="<?= base_url('admin/restoker') ?>">Restoker</a></li>
        <li><a class="dropdown-item <?= (uri_string() == 'admin/restok') ? 'active' : '' ?>" href="<?= base_url('admin/restok') ?>">Restok</a></li>
        <li><a class="dropdown-item <?= (uri_string() == 'admin/restok/history') ? 'active' : '' ?>" href="<?= base_url('admin/restok/history') ?>">History Restok</a></li>
    </ul>
</li>

                    <!-- Dropdown Menu Laporan -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= (strpos(uri_string(), 'admin/reports') !== false) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Laporan
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= (uri_string() == 'admin/reports/stok') ? 'active' : '' ?>" href="<?= base_url('admin/reports/stok') ?>">Laporan Stok</a></li>
                            <li><a class="dropdown-item <?= (uri_string() == 'admin/reports/transaksi') ? 'active' : '' ?>" href="<?= base_url('admin/reports/transaksi') ?>">Laporan Transaksi</a></li>
                            <li><a class="dropdown-item <?= (uri_string() == 'admin/reports/pengembalian') ? 'active' : '' ?>" href="<?= base_url('admin/reports/pengembalian') ?>">Laporan Pengembalian</a></li>
                        </ul>
                    </li>
                </ul>
            <?php elseif (strpos(current_url(), '/konsumen') !== false): ?>
                <!-- Navigasi untuk Konsumen -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'konsumen/pembelian') ? 'active' : '' ?>" href="<?= base_url('konsumen/pembelian') ?>">Pembelian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'konsumen/riwayat') ? 'active' : '' ?>" href="<?= base_url('konsumen/riwayat') ?>">Riwayat Pembelian</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (uri_string() == 'konsumen/pengembalian') ? 'active' : '' ?>" href="<?= base_url('konsumen/pengembalian') ?>">Pengembalian</a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container" style="margin-top: 80px;">

    
    <?= $this->renderSection('content') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
