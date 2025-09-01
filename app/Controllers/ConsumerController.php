<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ReturnModel;
use App\Models\CartModel;
use App\Models\CartItemModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                ->select('cart_items.*, items.nama_item')
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
        $userId = $this->session->get('userId');
        $cart = $this->cartModel->where('user_id', $userId)->first();

        if (!$cart) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang belanja masih kosong.');
        }

        $cartItems = $this->cartItemModel->where('cart_id', $cart['id'])->findAll();
        if (empty($cartItems)) {
            return redirect()->to(base_url('konsumen/pembelian'))->with('error', 'Keranjang belanja masih kosong.');
        }

        $totalAmount = 0;
        foreach ($cartItems as $cartItem) {
            $item = $this->itemModel->find($cartItem['item_id']);
            $totalAmount += $item['harga'] * $cartItem['quantity'];
        }

        $transactionData = [
            'transaction_code' => 'TRX-' . time(),
            'transaction_date' => date('Y-m-d H:i:s'),
            'total_amount' => $totalAmount,
        ];
        $this->transactionModel->insert($transactionData);
        $transactionId = $this->transactionModel->getInsertID();

        foreach ($cartItems as $cartItem) {
            $detailData = [
                'transaction_id' => $transactionId,
                'item_id' => $cartItem['item_id'],
                'quantity' => $cartItem['quantity'],
                'price' => $this->itemModel->find($cartItem['item_id'])['harga']
            ];
            $this->transactionDetailModel->insert($detailData);

            $item = $this->itemModel->find($cartItem['item_id']);
            $newStok = $item['stok'] - $cartItem['quantity'];
            $this->itemModel->update($item['id'], ['stok' => $newStok]);
        }
        
        $this->cartItemModel->where('cart_id', $cart['id'])->delete();
        $this->cartModel->delete($cart['id']);

        return redirect()->to(base_url('konsumen/riwayat'))->with('success', 'Pembelian berhasil!');
    }

    // --- Metode-metode lain di bawah ini tetap sama ---

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
}
