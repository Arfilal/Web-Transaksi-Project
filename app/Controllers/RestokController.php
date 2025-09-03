<?php

namespace App\Controllers;

use App\Models\RestokModel;
use App\Models\RestokerModel;
use App\Models\ItemModel;
use CodeIgniter\Controller;

class RestokController extends Controller
{
    protected $restokModel;
    protected $restokerModel;
    protected $itemModel;

    public function __construct()
    {
        $this->restokModel   = new RestokModel();
        $this->restokerModel = new RestokerModel();
        $this->itemModel     = new ItemModel();
    }

    // Tampilkan semua restok
    public function index()
    {
        $data['restoks'] = $this->restokModel->getRestok();
        return view('restok/index', $data);
    }

    // Form tambah data
    public function create()
    {
        $data['restokers'] = $this->restokerModel->findAll();
        $data['items']     = $this->itemModel->findAll();
        return view('restok/create', $data);
    }

    // Simpan data baru
    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'id_restoker'   => 'required',
            'id_item'       => 'required',
            'stok_dipesan'  => 'required|integer|greater_than[0]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        $this->restokModel->save([
            'id_restoker'   => $this->request->getPost('id_restoker'),
            'id_item'       => $this->request->getPost('id_item'),
            'stok_dipesan'  => $this->request->getPost('stok_dipesan'),
            'tanggal_pesan' => $this->request->getPost('tanggal_pesan'),
        ]);

        return redirect()->to(base_url('admin/restok'))->with('success', 'Data Restok berhasil ditambahkan!');
    }

    // Form edit
    public function edit($id)
    {
        $data['restok']     = $this->restokModel->find($id);
        $data['restokers']  = $this->restokerModel->findAll();
        $data['items']      = $this->itemModel->findAll();

        return view('restok/edit', $data);
    }

    // Konfirmasi restok (update stok diterima)
    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'stok_sampai'    => 'required|integer|greater_than_equal_to[0]',
            'tanggal_sampai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $validation->listErrors());
        }

        $restok = $this->restokModel->find($id);
        if (!$restok) {
            return redirect()->back()->with('error', 'Data restok tidak ditemukan');
        }

        $stokSampai = (int) $this->request->getPost('stok_sampai');
        $tanggalSampai = $this->request->getPost('tanggal_sampai');

        // update tabel restok
        $this->restokModel->update($id, [
            'stok_sampai'   => $stokSampai,
            'tanggal_sampai'=> $tanggalSampai,
        ]);

        // update stok di tabel items
        $this->itemModel->where('id', $restok['id_item'])
            ->increment('stok', $stokSampai);

        return redirect()->to(base_url('admin/restok'))
            ->with('success', 'Restok berhasil dikonfirmasi & stok barang ditambahkan!');
    }

    // Form retur
    public function retur($id)
{
    $restok = $this->restokModel->find($id);
    if (!$restok) {
        return redirect()->to(base_url('admin/restok'))->with('error', 'Data restok tidak ditemukan.');
    }

    // ambil item & restoker berdasarkan ID di restok
    $item     = $this->itemModel->find($restok['id_item']);
    $restoker = $this->restokerModel->find($restok['id_restoker']);

    $data = [
        'restok'   => $restok,
        'item'     => $item,
        'restoker' => $restoker,
    ];

    return view('restok/retur', $data);
}


    // Proses retur
   public function processRetur($id)
{
    $restok = $this->restokModel->find($id);
    if (!$restok) {
        return redirect()->to(base_url('admin/restok'))->with('error', 'Data restok tidak ditemukan.');
    }

    $stokRetur    = (int) $this->request->getPost('stok_retur');
    $tanggalRetur = $this->request->getPost('tanggal_retur');

    // ambil stok retur lama (kalau null isi 0)
    $stokReturLama = isset($restok['stok_retur']) ? (int)$restok['stok_retur'] : 0;

    // total retur (akumulasi)
    $totalRetur = $stokReturLama + $stokRetur;

    // status
    if ($totalRetur >= $restok['stok_sampai']) {
        $status = 'Diretur';
    } else {
        $status = 'Sebagian Diretur';
    }

    // update tabel restok
    $this->restokModel->update($id, [
        'stok_retur'    => $totalRetur,
        'tanggal_retur' => $tanggalRetur,
        'status'        => $status
    ]);

    // kurangi stok barang di item
    $this->itemModel->where('id', $restok['id_item'])
        ->decrement('stok', $stokRetur);

    return redirect()->to(base_url('admin/restok'))->with('success', 'Data restok berhasil diretur.');
}

public function history()
{
    $restokModel = new \App\Models\RestokModel();

    // Ambil data restok + join items + join restokers
    $data['history'] = $restokModel
        ->select('restok.id_restok, items.nama_item as nama_item, restok.stok_dipesan, restok.stok_sampai, restok.stok_retur, restokers.nama_restoker as nama_restoker, restok.tanggal_pesan, restok.tanggal_sampai, restok.tanggal_retur')
        ->join('items', 'items.id = restok.id_item')
        ->join('restokers', 'restokers.id_restoker = restok.id_restoker')
        ->orderBy('restok.id_restok', 'DESC')
        ->findAll();

    return view('restok/history', $data);
}


}
