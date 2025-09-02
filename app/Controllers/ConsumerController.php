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
        if ($cart) {
            $data['cart_items'] = $this->cartItemModel
                ->select('cart_items.*, items.nama_item, items.harga')
                ->join('items', 'items.id = cart_items.item_id')
                ->where('cart_id', $cart['id'])
                ->findAll();
        }

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
    // --- Debug log ---
    log_message('info', 'Header token: ' . $this->request->getHeaderLine('X-CALLBACK-TOKEN'));
    log_message('info', 'ENV token: ' . getenv('XENDIT_CALLBACK_TOKEN'));

    // --- Validasi token ---
    $callbackToken = $this->request->getHeaderLine('X-CALLBACK-TOKEN');
    $expectedToken = getenv('XENDIT_CALLBACK_TOKEN');

    if ($callbackToken !== $expectedToken) {
        return $this->response->setStatusCode(403)
            ->setJSON(['message' => 'Invalid token']);
    }

    // --- Ambil payload ---
    $json = $this->request->getJSON(true);
    log_message('info', 'Webhook payload: ' . json_encode($json));

    return $this->response->setJSON(['message' => 'Webhook received']);
}

public function sukses()
{
    return view('transaksi/sukses'); 
}

public function gagal()
{
    return view('transaksi/gagal');
}

}

