<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    // Tambahkan 'diskon' ke dalam allowedFields
    protected $allowedFields = ['nama_item', 'harga', 'harga_beli', 'stok', 'category_id', 'diskon'];

    /**
     * Mengambil item yang stoknya di bawah batas tertentu.
     * @param int $threshold Batas stok minimum.
     * @return array
     */
    public function getLowStockItems(int $threshold = 10): array
    {
        return $this->where('stok <=', $threshold)
                    ->orderBy('stok', 'ASC')
                    ->findAll();
    }
}
