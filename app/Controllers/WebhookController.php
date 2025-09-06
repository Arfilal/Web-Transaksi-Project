<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ItemModel;

class WebhookController extends BaseController
{
    public function xendit()
    {
        $json = $this->request->getJSON(true);
        log_message('info', 'Webhook data: ' . json_encode($json));

        // ==============> TAMBAHKAN BARIS INI <==============
        log_message('error', 'WEBHOOK PAYLOAD DITERIMA: ' . json_encode($json));
        // =====================================================


        $callbackToken = $this->request->getHeaderLine('X-CALLBACK-TOKEN');
        if ($callbackToken !== getenv('XENDIT_CALLBACK_TOKEN')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['message' => 'Invalid token']);
        }

        $externalId = $json['external_id'] ?? null; // ini adalah transaction_code kita
        $status     = strtoupper($json['status'] ?? '');
        $invoiceId  = $json['id'] ?? null;

        if (!$externalId || !$status) {
            return $this->response->setJSON(['message' => 'Invalid payload']);
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->where('transaction_code', $externalId)->first();

        if ($transaction && $transaction['status'] === 'pending') { // Hanya proses jika status masih pending
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

            // Jika pembayaran berhasil (PAID/SETTLED), kurangi stok barang
            if ($newStatus === 'paid') {
                $this->reduceStock($transaction['id']);
            }

            return $this->response->setJSON(['message' => "Transaction {$newStatus}"]);
        }

        return $this->response->setJSON(['message' => 'Transaction not found or already processed']);
    }

    /**
     * Mengurangi stok barang setelah transaksi berhasil.
     */
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
