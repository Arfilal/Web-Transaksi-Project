<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', function () {
    return view('pilihan');
});

// Rute untuk Admin
$routes->group('admin', function ($routes) {
    $routes->get('items', 'AdminController::items');
    $routes->get('items/create', 'AdminController::createItem');
    $routes->post('items/create', 'AdminController::createItem');
    $routes->get('items/edit/(:num)', 'AdminController::editItem/$1');
    $routes->post('items/edit/(:num)', 'AdminController::editItem/$1');
    $routes->get('items/delete/(:num)', 'AdminController::deleteItem/$1');
    
    // Rute untuk Impor Barang
    $routes->get('items/import', 'AdminController::showImportForm');
    $routes->post('items/import', 'AdminController::importExcel');

    $routes->get('transactions', 'AdminController::transactions');
    $routes->get('transactions/(:num)', 'AdminController::transactionDetail/$1');

    $routes->get('returns', 'AdminController::returns');
    $routes->get('returns/update-status/(:num)', 'AdminController::updateReturnStatus/$1');
    
    // Rute untuk Laporan
    $routes->get('reports', 'AdminController::report');
    $routes->get('reports/export-pdf', 'AdminController::exportPdf');
    $routes->get('reports/export-excel', 'AdminController::exportExcel');
});

// Rute untuk Konsumen
$routes->group('konsumen', function ($routes) {
    $routes->get('pembelian', 'ConsumerController::index');
    $routes->get('pembelian/add/(:num)', 'ConsumerController::addToCart/$1');
    $routes->get('pembelian/checkout', 'ConsumerController::checkout');

    $routes->get('riwayat', 'ConsumerController::history');
    $routes->get('riwayat/(:num)', 'ConsumerController::historyDetail/$1');

    // Rute untuk Pengembalian dan Retur
    $routes->get('pengembalian', 'ConsumerController::returns');
    $routes->get('retur/(:num)', 'ConsumerController::showReturnForm/$1');
    $routes->post('retur/create', 'ConsumerController::createReturn');
});
