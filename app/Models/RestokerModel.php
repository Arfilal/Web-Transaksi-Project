<?php

namespace App\Models;

use CodeIgniter\Model;

class RestokerModel extends Model
{
    protected $table            = 'restokers';
    protected $primaryKey       = 'id_restoker';
    protected $useAutoIncrement = true;

    protected $allowedFields    = [
        'nama_restoker',
        'kontak',
        'alamat'    
    ];

    protected $useTimestamps = true; // aktifkan created_at & updated_at
}
