<?php

namespace App\Models;

use CodeIgniter\Model;

class CartItemModel extends Model
{
    protected $table = 'cart_items';   // nama tabel di database
    protected $primaryKey = 'id';
    protected $allowedFields = ['cart_id', 'item_id', 'quantity'];
    protected $useTimestamps = true;   // kalau tabel punya created_at & updated_at
    protected $dateFormat    = 'datetime';
}
