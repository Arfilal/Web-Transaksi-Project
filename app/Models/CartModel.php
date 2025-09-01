<?php

namespace App\Models;

use CodeIgniter\Model;

class CartModel extends Model
{
    protected $table = 'carts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id'];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
}
