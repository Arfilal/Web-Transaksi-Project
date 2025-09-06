<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $user = session()->get('user');

        if (!$user) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        // Muat model
        $transactionModel = new TransactionModel();
        $itemModel = new ItemModel();

        // Siapkan data untuk ringkasan di dashboard
        $data = [
            'user'                       => $user,
            'total_penjualan_hari_ini'   => $transactionModel->getTotalSalesToday(),
            'transaksi_baru'             => $transactionModel->getNewTransactionsCount(),
            'stok_menipis'               => $itemModel->getLowStockItems(10), // Ambil item dengan stok <= 10
        ];
        
        // Data untuk grafik penjualan 7 hari terakhir
        $dailySales = $transactionModel->getDailySalesForLastWeek();

        // Proses data agar lengkap 7 hari (jika ada hari tanpa penjualan, diisi 0)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $chartData[$date] = 0;
        }

        foreach ($dailySales as $sale) {
            $chartData[$sale['date']] = (float) $sale['total'];
        }

        $data['daily_labels'] = array_keys($chartData);
        $data['daily_data']   = array_values($chartData);

        return view('dashboard', $data);
    }
}