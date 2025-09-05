<?php

namespace App\Controllers;

use App\Models\RestokModel;
use App\Models\RestokerModel;
use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\ReturnModel;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RestokController extends Controller
{
    protected $restokModel;
    protected $restokerModel;
    protected $itemModel;
    protected $transactionModel;
    protected $returnModel;

    public function __construct()
    {
        $this->restokModel      = new RestokModel();
        $this->restokerModel    = new RestokerModel();
        $this->itemModel        = new ItemModel();
        $this->transactionModel = new TransactionModel();
        $this->returnModel      = new ReturnModel();
    }

    // List Restok (pagination)
    public function index()
    {
        $data = [
            'restoks' => $this->restokModel
                ->select('restok.*, items.nama_item, restokers.nama_restoker')
                ->join('items', 'items.id = restok.id_item')
                ->join('restokers', 'restokers.id_restoker = restok.id_restoker')
                ->orderBy('restok.id_restok', 'DESC')
                ->paginate(10),
            'pager' => $this->restokModel->pager
        ];

        return view('restok/index', $data);
    }

    public function create()
    {
        $data = [
            'restokers' => $this->restokerModel->findAll(),
            'items'     => $this->itemModel->findAll(),
        ];
        return view('restok/create', $data);
    }

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

    public function edit($id)
    {
        $data = [
            'restok'    => $this->restokModel->find($id),
            'restokers' => $this->restokerModel->findAll(),
            'items'     => $this->itemModel->findAll(),
        ];
        return view('restok/edit', $data);
    }

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
            return redirect()->to(base_url('admin/restok'))->with('error', 'Data restok tidak ditemukan');
        }

        $stokSampai    = (int) $this->request->getPost('stok_sampai');
        $tanggalSampai = $this->request->getPost('tanggal_sampai');

        $this->restokModel->update($id, [
            'stok_sampai'    => $stokSampai,
            'tanggal_sampai' => $tanggalSampai,
        ]);

        $this->itemModel->where('id', $restok['id_item'])->increment('stok', $stokSampai);

        return redirect()->to(base_url('admin/restok'))
            ->with('success', 'Restok berhasil dikonfirmasi & stok barang ditambahkan!');
    }

    public function retur($id)
    {
        $restok = $this->restokModel->find($id);
        if (!$restok) {
            return redirect()->to(base_url('admin/restok'))->with('error', 'Data restok tidak ditemukan.');
        }

        $data = [
            'restok'   => $restok,
            'item'     => $this->itemModel->find($restok['id_item']),
            'restoker' => $this->restokerModel->find($restok['id_restoker']),
        ];

        return view('restok/retur', $data);
    }

    public function processRetur($id)
    {
        $restok = $this->restokModel->find($id);
        if (!$restok) {
            return redirect()->to(base_url('admin/restok'))->with('error', 'Data restok tidak ditemukan.');
        }

        $stokRetur    = (int) $this->request->getPost('stok_retur');
        $tanggalRetur = $this->request->getPost('tanggal_retur');

        $stokReturLama = isset($restok['stok_retur']) ? (int)$restok['stok_retur'] : 0;
        $totalRetur    = $stokReturLama + $stokRetur;

        $status = ($totalRetur >= $restok['stok_sampai']) ? 'Diretur' : 'Sebagian Diretur';

        $this->restokModel->update($id, [
            'stok_retur'    => $totalRetur,
            'tanggal_retur' => $tanggalRetur,
            'status'        => $status
        ]);

        $this->itemModel->where('id', $restok['id_item'])->decrement('stok', $stokRetur);

        return redirect()->to(base_url('admin/restok'))->with('success', 'Data restok berhasil diretur.');
    }

    public function history()
    {
        $data = [
            'history' => $this->restokModel
                ->select('restok.id_restok, items.nama_item as nama_item, restok.stok_dipesan, restok.stok_sampai, restok.stok_retur, restokers.nama_restoker as nama_restoker, restok.tanggal_pesan, restok.tanggal_sampai, restok.tanggal_retur')
                ->join('items', 'items.id = restok.id_item')
                ->join('restokers', 'restokers.id_restoker = restok.id_restoker')
                ->orderBy('restok.id_restok', 'DESC')
                ->paginate(10),
            'pager' => $this->restokModel->pager
        ];
        return view('restok/history', $data);
    }

    public function exportHistoryExcel()
    {
        $history = $this->restokModel
            ->select('restok.id_restok, items.nama_item as nama_item, restok.stok_dipesan, restok.stok_sampai, restok.stok_retur, restokers.nama_restoker as nama_restoker, restok.tanggal_pesan, restok.tanggal_sampai, restok.tanggal_retur')
            ->join('items', 'items.id = restok.id_item')
            ->join('restokers', 'restokers.id_restoker = restok.id_restoker')
            ->orderBy('restok.id_restok', 'DESC')
            ->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('History Restok');

        $sheet->setCellValue('A1', 'No')
              ->setCellValue('B1', 'Nama Item')
              ->setCellValue('C1', 'Supplier')
              ->setCellValue('D1', 'Jumlah Dipesan')
              ->setCellValue('E1', 'Jumlah Diterima')
              ->setCellValue('F1', 'Jumlah Diretur')
              ->setCellValue('G1', 'Tanggal Pesan')
              ->setCellValue('H1', 'Tanggal Sampai')
              ->setCellValue('I1', 'Tanggal Retur');

        $row = 2; $no = 1;
        foreach ($history as $h) {
            $sheet->setCellValue('A'.$row, $no++)
                  ->setCellValue('B'.$row, $h['nama_item'])
                  ->setCellValue('C'.$row, $h['nama_restoker'])
                  ->setCellValue('D'.$row, $h['stok_dipesan'])
                  ->setCellValue('E'.$row, $h['stok_sampai'] ?? 0)
                  ->setCellValue('F'.$row, $h['stok_retur'] ?? 0)
                  ->setCellValue('G'.$row, !empty($h['tanggal_pesan']) ? date('d-m-Y', strtotime($h['tanggal_pesan'])) : '-')
                  ->setCellValue('H'.$row, !empty($h['tanggal_sampai']) ? date('d-m-Y', strtotime($h['tanggal_sampai'])) : '-')
                  ->setCellValue('I'.$row, !empty($h['tanggal_retur']) ? date('d-m-Y', strtotime($h['tanggal_retur'])) : '-');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="history_restok.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    // ========= REPORTS =========

    // Laporan Stok
  public function stok()
{
    $data = [
        'title' => 'Laporan Stok',
        'stok'  => $this->itemModel
                        ->orderBy('nama_item','ASC')
                        ->findAll(),
    ];
    return view('admin/reports/stok', $data);
}

public function transaksi()
{
    $data = [
        'title'     => 'Laporan Transaksi',
        'transaksi' => $this->transactionModel
                            ->orderBy('transaction_date','DESC')
                            ->findAll(),
    ];
    return view('admin/reports/transaksi', $data);
}

public function pengembalian()
{
    $data = [
        'title'        => 'Laporan Pengembalian',
        'pengembalian' => $this->returnModel
                               ->orderBy('return_date','DESC')
                               ->findAll(),
    ];
    return view('admin/reports/pengembalian', $data);
}

}
