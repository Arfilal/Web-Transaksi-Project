<?php

namespace App\Controllers;

require_once FCPATH . '../vendor/autoload.php';   // <--- tambahkan ini

use CodeIgniter\Controller;
use Xendit\Xendit;


class PaymentController extends Controller
{
    public function createInvoice()
{
    $xenditApiKey = getenv('XENDIT_SECRET_KEY');

    // --- Ambil total belanja user dari keranjang ---
    $userId = session()->get('userId');
    $cartModel = new \App\Models\CartModel();
    $cartItemModel = new \App\Models\CartItemModel();

    $cart = $cartModel->where('user_id', $userId)->first();
    $amount = 0;

    if ($cart) {
        $cartItems = $cartItemModel
            ->select('cart_items.*, items.harga')
            ->join('items', 'items.id = cart_items.item_id')
            ->where('cart_id', $cart['id'])
            ->findAll();

        foreach ($cartItems as $item) {
            $amount += $item['harga'] * $item['quantity'];
        }
    }

    // --- bikin invoice ---
    $externalId = 'order-' . uniqid();

    $data = [
        'external_id' => $externalId,
        'amount' => $amount,
        'payer_email' => 'customer@email.com',
        'description' => 'Pembelian Produk dari Web POS',
        'success_redirect_url' => base_url('payment/success'),
        'failure_redirect_url' => base_url('payment/failed'),
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.xendit.co/v2/invoices');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $xenditApiKey . ':');
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result, true);

    if (isset($response['id'])) {
        $transactionModel = new \App\Models\TransactionModel();
        $transactionModel->insert([
            'transaction_code' => $externalId,
            'transaction_date' => date('Y-m-d H:i:s'),
            'total_amount' => $amount,
            'status' => $response['status'],
            'external_id' => $response['external_id'],
            'xendit_invoice_id' => $response['id']
        ]);

        // kosongkan keranjang biar nggak dobel transaksi
        $cartItemModel->where('cart_id', $cart['id'])->delete();

        return redirect()->to($response['invoice_url']);
    } else {
        return $this->response->setJSON($response);
    }
}

}
