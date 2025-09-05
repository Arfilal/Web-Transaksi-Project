<?php

namespace App\Models;

use CodeIgniter\Model;

class RestokModel extends Model
{
    protected $table            = 'restok';
    protected $primaryKey       = 'id_restok';
    protected $useAutoIncrement = true;

   protected $allowedFields = [
        'id_restoker',
        'id_item',
        'stok_dipesan',
        'stok_sampai',
        'stok_retur',
        'tanggal_retur',
        'tanggal_pesan',    
        'tanggal_sampai',
        'status'
    ];


    protected $useTimestamps = true; // otomatis created_at & updated_at

    // Custom query untuk ambil restok dengan join
    public function getRestok($perPage = 10)
{
    return $this->select('restok.*, restokers.nama_restoker, items.nama_item')
                ->join('restokers', 'restok.id_restoker = restokers.id_restoker')
                ->join('items', 'restok.id_item = items.id')
                ->paginate($perPage);
}


}
