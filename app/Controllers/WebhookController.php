<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class WebhookController extends BaseController
{
    public function xendit()
    {
        $json = $this->request->getJSON(true);
        log_message('info', 'Webhook data: ' . json_encode($json));

        $callbackToken = $this->request->getHeaderLine('X-CALLBACK-TOKEN');
        if ($callbackToken !== getenv('XENDIT_CALLBACK_TOKEN')) {
            return $this->response->setStatusCode(403)
                ->setJSON(['message' => 'Invalid token']);
        }

        $externalId = $json['external_id'] ?? null; // harus sama dengan transaction_code
        $status     = strtoupper($json['status'] ?? '');
        $invoiceId  = $json['id'] ?? null;

        if (!$externalId || !$status) {
            return $this->response->setJSON(['message' => 'Invalid payload']);
        }

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->where('transaction_code', $externalId)->first();

        if ($transaction) {
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

            return $this->response->setJSON(['message' => "Transaction {$newStatus}"]);
        }

        return $this->response->setJSON(['message' => 'Transaction not found']);
    }
}
