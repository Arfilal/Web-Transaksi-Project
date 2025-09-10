<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return view('pilihan');
});

// ======================
// Rute untuk Webhook Xendit (ditempatkan di atas untuk prioritas)
// ======================
$routes->post('webhook/xendit', 'WebhookController::xendit');


// ======================
// Rute untuk Login & Logout
// ======================
$routes->get('login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::login'); // opsional kalau mau login manual
$routes->get('logout', 'AuthController::logout');

// ======================
// Rute untuk Google Login
// ======================
$routes->get('auth/google', 'AuthController::redirectToGoogle');
$routes->get('auth/google/callback', 'AuthController::handleGoogleCallback');

// ======================
// Dashboard setelah login
// ======================
$routes->get('dashboard', 'DashboardController::index');

// ======================
// Rute untuk Admin
// ======================
$routes->group('admin', function ($routes) {
    // ✅ Rute untuk Manajemen Kategori
    $routes->group('categories', function($routes) {
        $routes->get('/', 'CategoryController::index');
        $routes->get('create', 'CategoryController::create');
        $routes->post('store', 'CategoryController::store');
        $routes->get('edit/(:num)', 'CategoryController::edit/$1');
        $routes->post('update/(:num)', 'CategoryController::update/$1');
        $routes->get('delete/(:num)', 'CategoryController::delete/$1');
    });

    // CRUD Items
    $routes->get('items', 'AdminController::items');
    $routes->get('items/create', 'AdminController::createItem');
    $routes->post('items/create', 'AdminController::createItem');
    $routes->get('items/edit/(:num)', 'AdminController::editItem/$1');
    $routes->post('items/edit/(:num)', 'AdminController::editItem/$1');
    $routes->post('items/delete/(:num)', 'AdminController::deleteItem/$1');

    // Impor Barang
    $routes->get('items/import', 'AdminController::showImportForm');
    $routes->post('items/import', 'AdminController::importExcel');

    // Transaksi
    $routes->get('transactions', 'AdminController::transactions');
    $routes->get('transactions/(:num)', 'AdminController::transactionDetail/$1');

    // Pengembalian
    $routes->get('returns', 'AdminController::returns');
    $routes->get('returns/update-status/(:num)', 'AdminController::updateReturnStatus/$1');

    // Laporan
    $routes->get('reports', 'AdminController::report');
    $routes->get('reports/export-pdf', 'AdminController::exportPdf');
    $routes->get('reports/export-excel', 'AdminController::exportExcel');

    // Detail laporan
    $routes->get('reports/transaksi', 'AdminController::reportTransaksi');
    $routes->get('reports/pengembalian', 'AdminController::reportPengembalian');
    $routes->get('reports/stok', 'AdminController::reportStok');
    
    // Rute Laporan Baru
    $routes->get('reports/profit-loss', 'AdminController::reportProfitLoss');
    $routes->get('reports/best-selling-products', 'AdminController::reportBestSellingProducts');
    $routes->get('reports/top-customers', 'AdminController::reportTopCustomers');


    // ✅ CRUD Restok
    $routes->group('restok', function($routes) {
        $routes->get('/', 'RestokController::index');
        $routes->get('create', 'RestokController::create');
        $routes->post('store', 'RestokController::store');
        $routes->get('edit/(:num)', 'RestokController::edit/$1');
        $routes->post('update/(:num)', 'RestokController::update/$1');
        $routes->post('delete/(:num)', 'RestokController::delete/$1');

        // ✅ Retur
        $routes->get('retur/(:num)', 'RestokController::retur/$1');
        $routes->post('retur/(:num)', 'RestokController::retur/$1');
        $routes->post('processRetur/(:num)', 'RestokController::processRetur/$1');

        // ✅ History Restok
        $routes->get('history', 'RestokController::history');
        // Export History Restok ke Excel
        $routes->get('history/export-excel', 'RestokController::exportHistoryExcel');
    });

    // ✅ CRUD Restoker
    $routes->group('restoker', function($routes) {
        $routes->get('/', 'RestokerController::index');
        $routes->get('create', 'RestokerController::create');
        $routes->post('store', 'RestokerController::store');
        $routes->get('edit/(:num)', 'RestokerController::edit/$1');
        $routes->post('update/(:num)', 'RestokerController::update/$1');
        $routes->post('delete/(:num)', 'RestokerController::delete/$1');
    });
});

// ======================
// Rute untuk Konsumen
// ======================
$routes->group('konsumen', function ($routes) {
    $routes->get('pembelian', 'ConsumerController::index');
    $routes->get('pembelian/add/(:num)', 'ConsumerController::addToCart/$1');

    // ✅ Tambah barang banyak sekaligus
    $routes->post('pembelian/add-selected', 'ConsumerController::addSelected');
    
    // Checkout (sekarang menampilkan form)
    $routes->get('checkout', 'ConsumerController::checkoutForm');
    // Rute untuk memproses form checkout dan mengarahkan ke Xendit
    $routes->post('pembelian/proses-checkout', 'ConsumerController::processCheckout');

    // ✅ Hapus item dari keranjang
    $routes->get('pembelian/remove/(:num)', 'ConsumerController::remove/$1');

    $routes->get('riwayat', 'ConsumerController::history');
    $routes->get('riwayat/(:num)', 'ConsumerController::historyDetail/$1');

    $routes->get('pengembalian', 'ConsumerController::returns');
    $routes->get('retur/(:num)', 'ConsumerController::showReturnForm/$1');
    $routes->post('retur/create', 'ConsumerController::createReturn');
});

// ======================
// Halaman transaksi sukses/gagal
// ======================
$routes->get('transaksi/sukses', 'ConsumerController::sukses');
$routes->get('transaksi/gagal', 'ConsumerController::gagal');

// Rute untuk struk (didefinisikan di sini untuk akses publik)
$routes->get('transaksi/struk/(:num)', 'ConsumerController::struk/$1');
