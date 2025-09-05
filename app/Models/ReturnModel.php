<?php

namespace App\Models;

use CodeIgniter\Model;

class ReturnModel extends Model
{
   protected $table = 'returns';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_detail_id',
        'return_date',
        'status'
    ];
}