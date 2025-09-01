<?php
require __DIR__ . '/vendor/autoload.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-Kfl6U9d2GDMXF3hXgDZzMS5U';
\Midtrans\Config::$isProduction = false;

try {
    $status = \Midtrans\Transaction::status("test");
    var_dump($status);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
