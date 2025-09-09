<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'customer_name',    // Menggunakan kolom yang sudah ada
        'customer_phone',   // Menggunakan kolom yang sudah ada
        'transaction_code',
        'transaction_date',
        'total_amount',
        'status',
        'external_id',
        'xendit_invoice_id'
    ];

    /**
     * Mengambil total penjualan HARI INI yang statusnya 'paid'.
     */
    public function getTotalSalesToday(): float
    {
        $result = $this->selectSum('total_amount', 'total_sales')
                       ->where('status', 'paid')
                       ->where('DATE(transaction_date) = CURDATE()')
                       ->get()
                       ->getRow();

        if ($result && $result->total_sales) {
            return (float) $result->total_sales;
        }

        return 0.0;
    }

    /**
     * Menghitung jumlah transaksi HARI INI yang statusnya 'paid'.
     */
    public function getNewTransactionsCount(): int
    {
        return $this->where('status', 'paid')
                    ->where('DATE(transaction_date) = CURDATE()')
                    ->countAllResults();
    }

    /**
     * Mengambil data penjualan untuk grafik 7 hari terakhir yang statusnya 'paid'.
     */
    public function getDailySalesForLastWeek()
    {
        return $this->select("DATE(transaction_date) as date, SUM(total_amount) as total")
                    ->where('status', 'paid')
                    ->where('transaction_date >=', 'CURDATE() - INTERVAL 6 DAY', false)
                    ->groupBy("DATE(transaction_date)")
                    ->orderBy("date", "ASC")
                    ->findAll();
    }

    public function getBestSellingProducts($limit = 5)
    {
        return $this->db->table('transaction_details')
            ->select('items.nama_item, SUM(transaction_details.quantity) as total_quantity')
            ->join('items', 'items.id = transaction_details.item_id')
            ->groupBy('items.nama_item')
            ->orderBy('total_quantity', 'DESC')
            ->limit($limit)
            ->get()->getResultArray();
    }

    // Metode yang diperbarui agar sesuai dengan struktur tabel Anda
   public function getTopCustomers($limit = 5)
{
    return $this->select('customer_name as name, COUNT(id) as total_transactions')
        ->where('customer_name IS NOT NULL')
        ->where('customer_name !=', '')
        ->groupBy('customer_name')
        ->orderBy('total_transactions', 'DESC')
        ->limit($limit)
        ->findAll();
}
}
