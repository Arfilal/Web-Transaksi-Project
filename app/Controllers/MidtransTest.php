<?php

namespace App\Controllers;

class MidtransTest extends BaseController
{
    public function index()
    {
        $serverKey = "SB-Mid-server-Kfl6U9d2GDMXF3hXgDZzMS5U"; // ganti dengan Server Key kamu

        $orderId = "TEST-" . rand();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.midtrans.com/v2/charge");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $serverKey . ":");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => 20000,
            ],
            'bank_transfer' => [
                'bank' => 'bca'
            ]
        ]));

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'cURL Error: ' . curl_error($ch);
        }
        curl_close($ch);

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
}
