<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['transaction_code', 'transaction_date', 'total_amount'];

    // Ambil data total penjualan harian
    public function getDailySales()
    {
        return $this->select('DATE(transaction_date) as date, SUM(total_amount) as total')
                    ->groupBy('date')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }

    // Ambil data total penjualan mingguan
    public function getWeeklySales()
    {
        return $this->select("YEARWEEK(transaction_date, 1) as week, SUM(total_amount) as total")
                    ->groupBy('week')
                    ->orderBy('week', 'ASC')
                    ->findAll();
    }
}