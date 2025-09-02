<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_code',
        'transaction_date',
        'total_amount',
        'status',
        'external_id',
        'xendit_invoice_id'
    ];

    // Laporan Harian
    public function getDailySales()
    {
        return $this->select("DATE(transaction_date) as date, SUM(total_amount) as total")
                    ->groupBy("DATE(transaction_date)")
                    ->orderBy("date", "ASC")
                    ->findAll();
    }

    // Laporan Mingguan
    public function getWeeklySales()
    {
        return $this->select("YEARWEEK(transaction_date, 1) as week, SUM(total_amount) as total")
                    ->groupBy("YEARWEEK(transaction_date, 1)")
                    ->orderBy("week", "ASC")
                    ->findAll();
    }

    // Laporan per item (kalau ada tabel detail transaksi + items)
    public function getItemSales()
    {
        return $this->db->table('transaction_details td')
                    ->select("items.nama_item, SUM(td.quantity) as total_quantity")
                    ->join("items", "items.id = td.item_id")
                    ->groupBy("items.nama_item")
                    ->orderBy("total_quantity", "DESC")
                    ->get()
                    ->getResultArray();
    }
}
