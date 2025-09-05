<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ReturnModel;
use App\Models\CartModel;
use App\Models\CartItemModel;
use CodeIgniter\Controller;

class ConsumerController extends Controller
{
    protected $itemModel;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $returnModel;
    protected $cartModel;
    protected $cartItemModel;
    protected $session;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->returnModel = new ReturnModel();
        $this->cartModel = new CartModel();
        $this->cartItemModel = new CartItemModel();
        $this->session = \Config\Services::session();
    }

    // --- Menu Pembelian ---
    public function index()
{
    $userId = $this->session->get('userId') ?? 'guest_' . session_id();
    $this->session->set('userId', $userId);

    $data['items'] = $this->itemModel->findAll();

    $cart = $this->cartModel->where('user_id', $userId)->first();
    $data['cart_items'] = [];
    $grandTotal = 0;

    if ($cart) {
        // ambil cart_items.id sebagai cart_item_id
        $data['cart_items'] = $this->cartItemModel
            ->select('cart_items.id as cart_item_id, cart_items.quantity, items.nama_item, items.harga')
            ->join('items', 'items.id = cart_items.item_id')
            ->where('cart_id', $cart['id'])
            ->findAll();

        foreach ($data['cart_items'] as $ci) {
            $grandTotal += $ci['harga'] * $ci['quantity'];
        }
    }

    $data['grandTotal'] = $grandTotal;

    return view('consumer/items/index', $data);
}


    public function addToCart($id)
    {
        $item = $this->itemModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Barang tidak ditemukan.');
        }
        
        $userId = $this->session->get('userId');
        $cart = $this->cartModel->where('user_id', $userId)->first();
        if (!$cart) {
            $this->cartModel->insert(['user_id' => $userId]);
            $cartId = $this->cartModel->getInsertID();
            $cart = $this->cartModel->find($cartId);
        }

        $cartItem = $this->cartItemModel->where(['cart_id' => $cart['id'], 'item_id' => $id])->first();
        if ($cartItem) {
            if ($cartItem['quantity'] + 1 > $item['stok']) {
                return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Stok barang tidak mencukupi.');
            }
            $this->cartItemModel->update($cartItem['id'], ['quantity' => $cartItem['quantity'] + 1]);
        } else {
            $this->cartItemModel->insert([
                'cart_id' => $cart['id'],
                'item_id' => $id,
                'quantity' => 1
            ]);
        }
        
        return redirect()->to(base_url('konsumen/pembelian'))->with('success', 'Barang berhasil ditambahkan ke keranjang!');
    }

   public function checkout()
{
    $xenditApiKey = 'xnd_development_kXAZ6Qo0ucZcLrd7OTzcOaOZqisI3n5uP0iB185G9oKzizPPT5dWyCwyzgC'; // ganti dengan API key kamu

    // Ambil userId dari session
    $userId = $this->session->get('userId');
    $cart = $this->cartModel->where('user_id', $userId)->first();
    $total = 0;

    if ($cart) {
        $cartItems = $this->cartItemModel
            ->select('cart_items.*, items.harga')
            ->join('items', 'items.id = cart_items.item_id')
            ->where('cart_id', $cart['id'])
            ->findAll();

        foreach ($cartItems as $item) {
            $total += $item['harga'] * $item['quantity'];
        }
    }

    if ($total <= 0) {
        return redirect()->to(base_url('konsumen/pembelian'))
            ->with('error', 'Keranjang masih kosong!');
    }

    $data = [
        'external_id' => 'order-' . time(),
        'amount' => $total,
        'payer_email' => 'test@example.com', // bisa diganti email user
        'description' => 'Pembelian Produk dari Web POS',
        'success_redirect_url' => base_url('transaksi/sukses'),
        'failure_redirect_url' => base_url('transaksi/gagal'),
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

    if (isset($response['invoice_url'])) {
        // kosongkan keranjang setelah checkout
        $this->cartItemModel->where('cart_id', $cart['id'])->delete();

        return redirect()->to($response['invoice_url']);
    } else {
        // tampilkan error dari API Xendit
        return $this->response->setJSON($response);
    }
}


    // --- Metode lain tetap sama ---
    public function history()
    {
        $data['transactions'] = $this->transactionModel->findAll(); 
        return view('consumer/history/index', $data);
    }

    public function historyDetail($id)
    {
        $data['transaction'] = $this->transactionModel->find($id);
        $data['details'] = $this->transactionDetailModel
                                ->select('transaction_details.*, items.nama_item')
                                ->join('items', 'items.id = transaction_details.item_id')
                                ->where('transaction_id', $id)
                                ->findAll();
        return view('consumer/history/detail', $data);
    }

    public function returns()
    {
        $data['returns'] = $this->returnModel
                                ->select('returns.*, items.nama_item')
                                ->join('transaction_details', 'transaction_details.id = returns.transaction_detail_id')
                                ->join('items', 'items.id = transaction_details.item_id')
                                ->findAll();
        return view('consumer/returns/index', $data);
    }

    public function showReturnForm($transactionDetailId)
    {
        $data['transaction_detail'] = $this->transactionDetailModel
                                         ->select('transaction_details.*, items.nama_item')
                                         ->join('items', 'items.id = transaction_details.item_id')
                                         ->find($transactionDetailId);
        if (!$data['transaction_detail']) {
            return redirect()->back()->with('error', 'Detail transaksi tidak ditemukan.');
        }
        return view('consumer/returns/form', $data);
    }

    public function createReturn()
    {
        $transactionDetailId = $this->request->getPost('transaction_detail_id');
        $this->returnModel->insert([
            'transaction_detail_id' => $transactionDetailId,
            'return_date' => date('Y-m-d H:i:s'),
            'status' => 'diproses'
        ]);
        return redirect()->to(base_url('konsumen/pengembalian'))->with('success', 'Permintaan retur berhasil diajukan. Status akan diperbarui oleh admin.');
    }

    public function webhook()
    {
        $callbackToken = $this->request->getHeaderLine('X-CALLBACK-TOKEN');
        $expectedToken = getenv('XENDIT_CALLBACK_TOKEN');

        // Validasi token callback dari Xendit
        if ($callbackToken !== $expectedToken) {
            log_message('error', 'Invalid callback token from Xendit');
            return $this->failUnauthorized('Invalid callback token');
        }

        $payload = $this->request->getJSON(true);

        if (!$payload || !isset($payload['id']) || !isset($payload['status'])) {
            log_message('error', 'Invalid callback payload: ' . json_encode($payload));
            return $this->fail('Invalid payload', 400);
        }

        $xenditInvoiceId = $payload['id'];
        $status = $payload['status'];

        $transactionModel = new TransactionModel();
        $transaction = $transactionModel->where('xendit_invoice_id', $xenditInvoiceId)->first();

        if (!$transaction) {
            log_message('error', 'Transaction not found for invoice: ' . $xenditInvoiceId);
            return $this->failNotFound('Transaction not found');
        }

        // Update status transaksi
        $transactionModel->update($transaction['id'], [
            'status' => $status
        ]);

        log_message('info', 'Transaction ' . $transaction['id'] . ' updated to status ' . $status);

        return $this->respond(['message' => 'Callback processed successfully']);
    }


public function sukses()
{
    return view('transaksi/sukses'); 
}

public function gagal()
{
    return view('transaksi/gagal');
}

public function addSelected()
{
    $userId = $this->session->get('userId') ?? 'guest_' . session_id();
    $this->session->set('userId', $userId);

    // cari/buat keranjang
    $cart = $this->cartModel->where('user_id', $userId)->first();
    if (!$cart) {
        $this->cartModel->insert(['user_id' => $userId]);
        $cartId = $this->cartModel->getInsertID();
    } else {
        $cartId = $cart['id'];
    }

    $selectedIds = (array) $this->request->getPost('item_id');
    $qtyMap      = (array) $this->request->getPost('qty');

    if (empty($selectedIds)) {
        return redirect()->back()->with('error', 'Pilih minimal satu barang.');
    }

    foreach ($selectedIds as $itemId) {
        $item = $this->itemModel->find($itemId);
        if (!$item) {
            continue;
        }

        $qty = isset($qtyMap[$itemId]) ? max(1, (int)$qtyMap[$itemId]) : 1;

        // validasi stok
        if ($qty > $item['stok']) {
            $qty = $item['stok'];
        }

        // cek apakah barang sudah ada di keranjang
        $cartItem = $this->cartItemModel
            ->where(['cart_id' => $cartId, 'item_id' => $itemId])
            ->first();

        if ($cartItem) {
            $newQty = $cartItem['quantity'] + $qty;
            if ($newQty > $item['stok']) {
                $newQty = $item['stok'];
            }
            $this->cartItemModel->update($cartItem['id'], ['quantity' => $newQty]);
        } else {
            $this->cartItemModel->insert([
                'cart_id'  => $cartId,
                'item_id'  => $itemId,
                'quantity' => $qty
            ]);
        }
    }

    return redirect()->to(base_url('konsumen/pembelian'))
                     ->with('success', 'Barang terpilih berhasil masuk keranjang.');
}

public function remove($id)
{
    $userId = $this->session->get('userId');

    // cari keranjang user
    $cart = $this->cartModel->where('user_id', $userId)->first();
    if (!$cart) {
        return redirect()->to(base_url('konsumen/pembelian'))
                         ->with('error', 'Keranjang tidak ditemukan.');
    }

    // hapus item berdasarkan id cart_items
    $cartItem = $this->cartItemModel->where([
        'id' => $id,
        'cart_id' => $cart['id']
    ])->first();

    if ($cartItem) {
        $this->cartItemModel->delete($id);
        return redirect()->to(base_url('konsumen/pembelian'))
                         ->with('success', 'Barang berhasil dihapus dari keranjang.');
    }

    return redirect()->to(base_url('konsumen/pembelian'))
                     ->with('error', 'Barang tidak ditemukan di keranjang.');
}



}

