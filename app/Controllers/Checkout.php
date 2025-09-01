<?php

namespace App\Controllers;
use Midtrans\Config;
use Midtrans\Snap;

class Checkout extends BaseController
{
    public function index()
    {
        // Set Server Key (HARUS server key sandbox, bukan client key!)
        Config::$serverKey = trim('SB-Mid-server-Kfl6U9d2GDMXF3hXgDZzMS5U');
        Config::$isProduction = false;

        // 🔎 Debugging: cek key yang dipakai
        echo "ServerKey in PHP: " . Config::$serverKey . "<br>";

        $params = [
            'transaction_details' => [
                'order_id' => rand(),
                'gross_amount' => 20000,
            ],
            'customer_details' => [
                'first_name' => 'Budi',
                'email' => 'budi@example.com',
                'phone' => '081234567890',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            echo "Snap Token: " . $snapToken; // tampilkan hasilnya
            return;
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
            return;
        }
    }
}
