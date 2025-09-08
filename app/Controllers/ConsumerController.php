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

        // ✅ Pagination untuk daftar barang
        $data['items'] = $this->itemModel->paginate(10, 'items');
        $data['pager'] = $this->itemModel->pager;

        // keranjang user
        $cart = $this->cartModel->where('user_id', $userId)->first();
        $data['cart_items'] = [];
        $grandTotal = 0;

        if ($cart) {
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

   // Menampilkan halaman ringkasan checkout dan form data pelanggan
    public function checkout()
    {
        $userId = $this->session->get('userId');
        $cart = $this->cartModel->where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang Anda kosong.');
        }

        $cartItems = $this->cartItemModel
            ->select('cart_items.*, items.harga, items.stok, items.nama_item')
            ->join('items', 'items.id = cart_items.item_id')
            ->where('cart_id', $cart['id'])
            ->findAll();

        if (empty($cartItems)) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang Anda kosong.');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        $data['cart_items'] = $cartItems;
        $data['grandTotal'] = $total;

        return view('consumer/checkout', $data);
    }

    // Memproses checkout setelah form diisi dan membuat invoice Xendit
    public function processCheckout()
    {
        $xenditApiKey = getenv('XENDIT_SECRET_KEY');

        $userId = $this->session->get('userId');
        $cart = $this->cartModel->where('user_id', $userId)->first();
        
        if (!$cart) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang Anda kosong.');
        }

        $cartItems = $this->cartItemModel
            ->select('cart_items.*, items.harga, items.stok, items.nama_item')
            ->join('items', 'items.id = cart_items.item_id')
            ->where('cart_id', $cart['id'])
            ->findAll();

        if (empty($cartItems)) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang Anda kosong.');
        }

        $total = 0;
        foreach ($cartItems as $item) {
            if ($item['quantity'] > $item['stok']) {
                return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Stok untuk barang '. $item['nama_item'] .' tidak mencukupi.');
            }
            $total += $item['harga'] * $item['quantity'];
        }

        // Ambil data pelanggan dari form
        $customerName = $this->request->getPost('customer_name');
        $customerPhone = $this->request->getPost('customer_phone');

        if (empty($customerName)) {
            return redirect()->back()->withInput()->with('error', 'Nama pelanggan wajib diisi.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $transactionCode = 'TRX-' . strtoupper(uniqid());
        $this->transactionModel->insert([
            'transaction_code' => $transactionCode,
            'customer_name'    => $customerName,
            'customer_phone'   => $customerPhone,
            'transaction_date' => date('Y-m-d H:i:s'),
            'total_amount'     => $total,
            'status'           => 'pending',
        ]);
        $transactionId = $this->transactionModel->getInsertID();

        foreach ($cartItems as $item) {
            $this->transactionDetailModel->insert([
                'transaction_id' => $transactionId,
                'item_id'        => $item['item_id'],
                'quantity'       => $item['quantity'],
                'price'          => $item['harga'],
            ]);
        }

        $data = [
            'external_id'          => $transactionCode,
            'amount'               => $total,
            'payer_email'          => 'customer@example.com', // Email generik
            'description'          => 'Pembelian a.n. ' . $customerName,
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
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = json_decode($result, true);
        
        if (isset($response['invoice_url'])) {
            $this->transactionModel->update($transactionId, [
                'xendit_invoice_id' => $response['id']
            ]);

            $db->transComplete();
            $this->cartItemModel->where('cart_id', $cart['id'])->delete();

            return redirect()->to($response['invoice_url']);
        } else {
            $db->transRollback();
            log_message('error', 'Xendit Invoice Error: ' . $result);
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Gagal membuat pembayaran. Silakan coba lagi. (Code: '.$httpcode.')');
        }
    }


    // --- Metode lain tetap sama ---
    public function history()
    {
        $data['transactions'] = $this->transactionModel->paginate(10, 'transactions');
        $data['pager'] = $this->transactionModel->pager;
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
