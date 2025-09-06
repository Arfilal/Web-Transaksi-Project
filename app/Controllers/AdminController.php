<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ReturnModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory; // Tambahkan ini

class AdminController extends Controller
{
    protected $itemModel;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $returnModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->returnModel = new ReturnModel();
    }

    // --- Menu Barang (CRUD) ---
    public function items()
{
    $data['items'] = $this->itemModel->paginate(10); // tampilkan 10 per halaman
    $data['pager'] = $this->itemModel->pager;       // kirim pager ke view
    return view('admin/items/index', $data);
}


    public function createItem()
    {
        if ($this->request->getPost()) {
            $data = $this->request->getPost();
            if ($this->itemModel->insert($data)) {
                return redirect()->to(base_url('admin/items'))->with('success', 'Barang berhasil ditambahkan.');
            } else {
                return redirect()->back()->withInput()->with('error', 'Gagal menambahkan barang.');
            }
        }
        return view('admin/items/create');
    }

    public function editItem($id)
    {
        if ($this->request->getMethod() === 'post') {
            $data = $this->request->getPost();
            $this->itemModel->update($id, $data);
            return redirect()->to(base_url('admin/items'))->with('success', 'Barang berhasil diupdate.');
        }
        $data['item'] = $this->itemModel->find($id);
        return view('admin/items/edit', $data);
    }

    public function deleteItem($id)
    {
        try {
            $this->itemModel->delete($id);
            return redirect()->to(base_url('admin/items'))->with('success', 'Barang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->to(base_url('admin/items'))->with('error', 'Gagal menghapus barang. Barang ini sudah terhubung dengan data transaksi.');
        }
    }

    // --- Menu Impor Barang ---
    public function showImportForm()
    {
        return view('admin/items/import');
    }

   public function importExcel()
{
    $file = $this->request->getFile('excel_file');

    if (!$file || !$file->isValid()) {
        return redirect()->back()->with('error', 'Silakan pilih file Excel yang valid.');
    }

    try {
        // Pakai PhpSpreadsheet
        $reader = IOFactory::createReaderForFile($file->getTempName());
        $spreadsheet = $reader->load($file->getTempName());
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $importedCount = 0;
        foreach ($sheetData as $key => $row) {
            if ($key === 1) { // Lewati header baris pertama
                continue;
            }

            $data = [
                'nama_item' => trim($row['A']),
                'harga'     => (int) $row['B'],
                'stok'      => (int) $row['C'],
            ];

            if (!empty($data['nama_item']) && $data['harga'] > 0 && $data['stok'] >= 0) {
                if ($this->itemModel->insert($data)) {
                    $importedCount++;
                }
            }
        }

        return redirect()->to(base_url('admin/items'))
            ->with('success', "$importedCount barang berhasil diimpor.");
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
    }
}


    // --- Menu Riwayat Transaksi ---
    public function transactions()
    {
        $data['transactions'] = $this->transactionModel->findAll();
        return view('admin/transactions/index', $data);
    }

    public function transactionDetail($id)
    {
        $data['transaction'] = $this->transactionModel->find($id);
        $data['details'] = $this->transactionDetailModel
                                ->select('transaction_details.*, items.nama_item')
                                ->join('items', 'items.id = transaction_details.item_id')
                                ->where('transaction_id', $id)
                                ->findAll();
        return view('admin/transactions/detail', $data);
    }

    // --- Menu Pengembalian ---
    public function returns()
    {
        $data['returns'] = $this->returnModel->findAll();
        return view('admin/returns/index', $data);
    }

    public function updateReturnStatus($id)
    {
        $retur = $this->returnModel->find($id);
        if ($retur) {
            $this->returnModel->update($id, ['status' => 'selesai']);

            $detail = $this->transactionDetailModel->find($retur['transaction_detail_id']);
            $item = $this->itemModel->find($detail['item_id']);
            $newStok = $item['stok'] + $detail['quantity'];
            $this->itemModel->update($item['id'], ['stok' => $newStok]);
        }
        return redirect()->to(base_url('admin/returns'))->with('success', 'Status pengembalian berhasil diubah.');
    }

    public function report()
{
    // Ambil data dari Model
    $data['daily_sales'] = $this->transactionModel->getDailySales();
    $data['weekly_sales'] = $this->transactionModel->getWeeklySales();
    $data['item_sales'] = $this->transactionDetailModel
                                ->select('items.nama_item, SUM(transaction_details.quantity) as total_quantity')
                                ->join('items', 'items.id = transaction_details.item_id')
                                ->groupBy('items.nama_item')
                                ->findAll();

    // Siapkan data untuk ChartJS
    $data['daily_labels'] = array_column($data['daily_sales'], 'date');
    $data['daily_data']   = array_column($data['daily_sales'], 'total');

    $data['weekly_labels'] = array_column($data['weekly_sales'], 'week');
    $data['weekly_data']   = array_column($data['weekly_sales'], 'total');

    $data['item_labels'] = array_column($data['item_sales'], 'nama_item');
    $data['item_data']   = array_column($data['item_sales'], 'total_quantity');

    return view('admin/reports/index', $data);
}

    public function exportPdf()
    {
        $data['transactions'] = $this->transactionModel->findAll();
        $html = view('admin/reports/pdf_template', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('laporan_penjualan.pdf', ['Attachment' => 1]);
    }

    public function exportExcel()
    {
        $transactions = $this->transactionModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Transaksi');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Total');
        $row = 2;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction['transaction_code']);
            $sheet->setCellValue('B' . $row, $transaction['transaction_date']);
            $sheet->setCellValue('C' . $row, $transaction['total_amount']);
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="laporan_penjualan.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

  public function reportTransaksi()
{
    $data['transaksi'] = $this->transactionModel
        ->select('transactions.*')
        ->paginate(10); // tampilkan 10 data per halaman

    $data['pager'] = $this->transactionModel->pager; // kirim pager ke view

    return view('admin/reports/transaksi', $data);
}

public function reportPengembalian()
{
    $data['pengembalian'] = $this->returnModel
        ->select('returns.*, items.nama_item, td.quantity, transactions.transaction_code')
        ->join('transaction_details td', 'td.id = returns.transaction_detail_id', 'left')
        ->join('items', 'items.id = td.item_id', 'left')
        ->join('transactions', 'transactions.id = td.transaction_id', 'left')
        ->paginate(10); // pagination juga

    $data['pager'] = $this->returnModel->pager; // kirim pager ke view

    return view('admin/reports/pengembalian', $data);
}

public function reportStok()
{
    $db = \Config\Database::connect();
    $builder = $db->table('restok r');
    $builder->select([
        'i.nama_item AS nama_item',
        'r.stok_dipesan AS jumlah',
        'r.tanggal_pesan AS tanggal',
        'rs.nama_restoker AS restoker',
    ]);
    $builder->join('items i', 'i.id = r.id_item', 'left');
    $builder->join('restokers rs', 'rs.id_restoker = r.id_restoker', 'left');

    // pagination manual
    $perPage = 10;
    $page    = (int)($this->request->getVar('page') ?? 1);
    $offset  = ($page - 1) * $perPage;

    $total   = $builder->countAllResults(false); // hitung total data
    $builder->limit($perPage, $offset);
    $data['restok'] = $builder->get()->getResultArray();

    // service pager
    $pager = \Config\Services::pager();
    $data['pager'] = $pager->makeLinks($page, $perPage, $total);

    return view('admin/reports/stok', $data);
}



}
