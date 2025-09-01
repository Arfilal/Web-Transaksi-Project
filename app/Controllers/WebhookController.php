<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ItemModel;

class WebhookController extends Controller
{
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $itemModel;

    public function __construct()
    {
        // Panggil helper yang diperlukan jika ada
        helper('url');
        
        // Inisialisasi model
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->itemModel = new ItemModel();
    }

    // Fungsi untuk menerima notifikasi dari Midtrans
    public function handle()
    {
        // Masukkan kelas Midtrans\Config di sini
        // Pastikan Anda sudah menginstal Midtrans PHP SDK via Composer
        // composer require midtrans/midtrans-php
        
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$serverKey = config('Midtrans')->serverKey;
        
        // Ambil data notifikasi mentah dari Midtrans
        $notif = new \Midtrans\Notification();
        
        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $transactionRecord = $this->transactionModel->where('transaction_code', $order_id)->first();
        
        // Periksa apakah notifikasi valid dan order_id ditemukan di database
        if (!$transactionRecord) {
            log_message('error', 'Notification received for unknown order_id: ' . $order_id);
            return $this->response->setStatusCode(404)->setBody('Order not found');
        }

        // --- Logika untuk menangani status notifikasi dari Midtrans ---
        
        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    // Update status transaksi menjadi 'Challenge'
                    $this->transactionModel->update($transactionRecord['id'], ['status' => 'challenge']);
                } else {
                    // Update status transaksi menjadi 'Success'
                    $this->transactionModel->update($transactionRecord['id'], ['status' => 'success']);
                }
            }
        } elseif ($transaction == 'settlement') {
            // Update status transaksi menjadi 'Success'
            $this->transactionModel->update($transactionRecord['id'], ['status' => 'success']);
            
            // Logika untuk memproses pembayaran yang berhasil
            $cartItems = $this->transactionDetailModel->where('transaction_id', $transactionRecord['id'])->findAll();
            
            foreach ($cartItems as $cartItem) {
                $item = $this->itemModel->find($cartItem['item_id']);
                if ($item) {
                    $newStok = $item['stok'] - $cartItem['quantity'];
                    // Perbarui stok barang
                    $this->itemModel->update($item['id'], ['stok' => $newStok]);
                }
            }
            
            // Hapus keranjang setelah pembayaran selesai
            $this->cartItemModel->where('cart_id', $transactionRecord['cart_id'])->delete();
            $this->cartModel->delete($transactionRecord['cart_id']);

        } elseif ($transaction == 'pending') {
            // Update status transaksi menjadi 'Pending'
            $this->transactionModel->update($transactionRecord['id'], ['status' => 'pending']);
        } elseif ($transaction == 'deny') {
            // Update status transaksi menjadi 'Deny'
            $this->transactionModel->update($transactionRecord['id'], ['status' => 'deny']);
        } elseif ($transaction == 'expire') {
            // Update status transaksi menjadi 'Expired'
            $this->transactionModel->update($transactionRecord['id'], ['status' => 'expired']);
        } elseif ($transaction == 'cancel') {
            // Update status transaksi menjadi 'Cancelled'
            $this->transactionModel->update($transactionRecord['id'], ['status' => 'cancelled']);
        }

        // Berikan respons 200 OK ke Midtrans
        return $this->response->setStatusCode(200)->setBody('OK');
    }
}
