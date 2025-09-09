<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class WebhookController extends BaseController
{
    public function xendit()
    {
        $json = $this->request->getJSON(true);
        log_message('info', 'Webhook data: ' . json_encode($json));

        $callbackToken = $this->request->getHeaderLine('X-CALLBACK-TOKEN');
        if ($callbackToken !== getenv('XENDIT_CALLBACK_TOKEN')) {
            return $this->response->setStatusCode(403)->setJSON(['message' => 'Invalid token']);
        }

        $externalId = $json['external_id'] ?? null;
        $status     = strtoupper($json['status'] ?? '');
        $invoiceId  = $json['id'] ?? null;

        if (!$externalId || !$status) {
            return $this->response->setJSON(['message' => 'Invalid payload']);
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->where('transaction_code', $externalId)->first();

        if ($transaction && $transaction['status'] === 'pending') {
            $newStatus = match ($status) {
                'PAID', 'SETTLED' => 'paid',
                'EXPIRED'         => 'expired',
                'FAILED'          => 'failed',
                default           => 'pending',
            };

            $transactionModel->update($transaction['id'], [
                'status'            => $newStatus,
                'xendit_invoice_id' => $invoiceId,
            ]);

            // Jika pembayaran berhasil, kurangi stok dan KIRIM NOTIFIKASI
            if ($newStatus === 'paid') {
                $this->reduceStock($transaction['id']);
                
                // Ambil ulang data transaksi yang sudah di-update
                $updatedTransaction = $transactionModel->find($transaction['id']);

                // Pastikan ada email pelanggan sebelum mengirim
                if (!empty($updatedTransaction['customer_email'])) {
                    $email = \Config\Services::email();
                    $email->setTo($updatedTransaction['customer_email']);
                    $email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
                    $email->setSubject('Pembayaran Berhasil untuk Pesanan ' . $updatedTransaction['transaction_code']);
                    
                    // Ambil detail item untuk email
                    $detailModel = new TransactionDetailModel();
                    $details = $detailModel->select('transaction_details.*, items.nama_item')
                                           ->join('items', 'items.id = transaction_details.item_id')
                                           ->where('transaction_id', $updatedTransaction['id'])
                                           ->findAll();

                    $message = view('emails/order_paid_notification', [
                        'transaction' => $updatedTransaction,
                        'details'     => $details,
                    ]);
                    $email->setMessage($message);
                    
                    if ($email->send()) {
                        log_message('info', 'Email notifikasi pembayaran berhasil dikirim untuk transaksi ' . $updatedTransaction['id']);
                    } else {
                        log_message('error', 'Gagal mengirim email notifikasi: ' . $email->printDebugger(['headers']));
                    }
                }
            }
            return $this->response->setJSON(['message' => "Transaction {$newStatus}"]);
        }
        return $this->response->setJSON(['message' => 'Transaction not found or already processed']);
    }

    private function reduceStock(int $transactionId)
    {
        $detailModel = new TransactionDetailModel();
        $itemModel = new ItemModel();
        $details = $detailModel->where('transaction_id', $transactionId)->findAll();
        foreach ($details as $detail) {
            $itemModel->where('id', $detail['item_id'])
                      ->set('stok', 'stok - ' . $detail['quantity'], false)
                      ->update();
        }
    }
}