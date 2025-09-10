<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\CategoryModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ReturnModel;
use App\Models\CustomerModel;
use App\Models\UserModel; // Tambahkan import UserModel
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class AdminController extends Controller
{
    protected $itemModel;
    protected $categoryModel;
    protected $transactionModel;
    protected $transactionDetailModel;
    protected $returnModel;
    protected $customerModel;
    protected $userModel; // Deklarasikan UserModel

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new CategoryModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionDetailModel = new TransactionDetailModel();
        $this->returnModel = new ReturnModel();
        $this->customerModel = new CustomerModel();
        $this->userModel = new UserModel(); // Inisialisasi UserModel
    }

    // --- FUNGSI BARU UNTUK MENGUNGGAH KE GOOGLE DRIVE ---
    private function uploadToGoogleDrive(string $filePath, string $mimeType, string $fileName)
    {
        $session = session();
        $user = $session->get('user');

        // Pastikan ada pengguna yang login dengan Google
        if (empty($user) || empty($user['id'])) {
            return false;
        }

        $userData = $this->userModel->find($user['id']);

        if (empty($userData['google_refresh_token'])) {
            return false;
        }

        $client = new Google_Client();
        $client->setClientId(getenv('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));

        // Gunakan refresh token untuk mendapatkan akses token baru
        $client->fetchAccessTokenWithRefreshToken($userData['google_refresh_token']);

        // Pastikan akses token berhasil didapat
        if ($client->getAccessToken()) {
            $service = new Google_Service_Drive($client);
            $file = new Google_Service_Drive_DriveFile();
            $file->setName($fileName);
            $file->setMimeType($mimeType);

            $data = file_get_contents($filePath);

            try {
                $createdFile = $service->files->create($file, [
                    'data'       => $data,
                    'mimeType'   => $mimeType,
                    'uploadType' => 'multipart',
                ]);

                // Hapus file lokal setelah diunggah
                unlink($filePath);

                return $createdFile->id;
            } catch (\Exception $e) {
                log_message('error', 'Gagal mengunggah ke Google Drive: ' . $e->getMessage());
                return false;
            }
        }

        return false;
    }

    // --- Menu Barang (CRUD) ---
    public function items()
    {
        // Perbarui query untuk JOIN ke tabel categories
        $data['items'] = $this->itemModel
            ->select('items.*, categories.nama_kategori')
            ->join('categories', 'categories.id = items.category_id', 'left')
            ->paginate(10);
        $data['pager'] = $this->itemModel->pager;
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
        $data['categories'] = $this->categoryModel->findAll();
        return view('admin/items/create', $data);
    }

    public function editItem($id)
{
    $data['item'] = $this->itemModel->find($id);

    // Jika item tidak ditemukan, kembalikan ke halaman daftar dengan error
    if (!$data['item']) {
        return redirect()->to(base_url('admin/items'))->with('error', 'Barang tidak ditemukan.');
    }

    // Jika ada data POST, proses update
    if ($this->request->getPost()) {
        $rules = [
            'nama_item'   => 'required',
            'harga'       => 'required|numeric|greater_than_equal_to[0]',
            'harga_beli'  => 'required|numeric|greater_than_equal_to[0]',
            'stok'        => 'required|numeric|greater_than_equal_to[0]',
            'category_id' => 'required|integer',
            'diskon'      => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ];
        
        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke form dengan input dan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Ambil data dari POST
        $postData = $this->request->getPost();
        
        // Lakukan update ke database
        $this->itemModel->update($id, $postData);
        
        // Redirect ke halaman daftar barang dengan pesan sukses
        return redirect()->to(base_url('admin/items'))->with('success', 'Barang berhasil diupdate.');
    }

    // Jika tidak ada data POST, tampilkan form edit
    $data['categories'] = $this->categoryModel->findAll();
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
            $reader = IOFactory::createReaderForFile($file->getTempName());
            $spreadsheet = $reader->load($file->getTempName());
            $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

            $importedCount = 0;
            foreach ($sheetData as $key => $row) {
                if ($key === 1) {
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
        $data['daily_sales'] = $this->transactionModel->getDailySales();
        $data['weekly_sales'] = $this->transactionModel->getWeeklySales();
        $data['item_sales'] = $this->transactionDetailModel
                                     ->select('items.nama_item, SUM(transaction_details.quantity) as total_quantity')
                                     ->join('items', 'items.id = transaction_details.item_id')
                                     ->groupBy('items.nama_item')
                                     ->findAll();

        $data['daily_labels'] = array_column($data['daily_sales'], 'date');
        $data['daily_data']   = array_column($data['daily_sales'], 'total');

        $data['weekly_labels'] = array_column($data['weekly_sales'], 'week');
        $data['weekly_data']   = array_column($data['weekly_sales'], 'total');

        $data['item_labels'] = array_column($data['item_sales'], 'nama_item');
        $data['item_data']   = array_column($data['item_sales'], 'total_quantity');

        return view('admin/reports/index', $data);
    }

    // --- FUNGSI YANG DIMODIFIKASI UNTUK MENGUNGGAH KE GOOGLE DRIVE ---
    public function exportPdf()
    {
        $data['transactions'] = $this->transactionModel->findAll();
        $html = view('admin/reports/pdf_template', $data);
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $output = $dompdf->output();
        $fileName = 'laporan_penjualan_' . date('Ymd_His') . '.pdf';
        $filePath = WRITEPATH . 'uploads/' . $fileName;
        file_put_contents($filePath, $output);

        // Unggah ke Google Drive
        $fileId = $this->uploadToGoogleDrive($filePath, 'application/pdf', $fileName);

        if ($fileId) {
            session()->setFlashdata('success', 'Laporan PDF berhasil diunggah ke Google Drive! <a href="https://drive.google.com/file/d/'.$fileId.'/view" target="_blank">Lihat di sini</a>');
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah laporan PDF ke Google Drive.');
        }

        return redirect()->to(base_url('admin/reports'));
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
        $fileName = 'laporan_penjualan_' . date('Ymd_His') . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;
        $writer->save($filePath);

        // Unggah ke Google Drive
        $fileId = $this->uploadToGoogleDrive($filePath, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', $fileName);

        if ($fileId) {
            session()->setFlashdata('success', 'Laporan Excel berhasil diunggah ke Google Drive! <a href="https://drive.google.com/file/d/'.$fileId.'/view" target="_blank">Lihat di sini</a>');
        } else {
            session()->setFlashdata('error', 'Gagal mengunggah laporan Excel ke Google Drive.');
        }

        return redirect()->to(base_url('admin/reports'));
    }

    public function reportTransaksi()
    {
        $data['transaksi'] = $this->transactionModel
            ->select('transactions.*')
            ->paginate(10);

        $data['pager'] = $this->transactionModel->pager;

        return view('admin/reports/transaksi', $data);
    }

    public function reportPengembalian()
    {
        $data['pengembalian'] = $this->returnModel
            ->select('returns.*, items.nama_item, td.quantity, transactions.transaction_code')
            ->join('transaction_details td', 'td.id = returns.transaction_detail_id', 'left')
            ->join('items', 'items.id = td.item_id', 'left')
            ->join('transactions', 'transactions.id = td.transaction_id', 'left')
            ->orderBy('returns.return_date', 'DESC')
            ->paginate(10);

        $data['pager'] = $this->returnModel->pager;

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

        $perPage = 10;
        $page    = (int)($this->request->getVar('page') ?? 1);
        $offset  = ($page - 1) * $perPage;

        $total   = $builder->countAllResults(false);
        $builder->limit($perPage, $offset);
        $data['restok'] = $builder->get()->getResultArray();

        $pager = \Config\Services::pager();
        $data['pager'] = $pager->makeLinks($page, $perPage, $total);

        return view('admin/reports/stok', $data);
    }

    public function reportProfitLoss()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transaction_details td');
        $builder->select('t.transaction_code, t.transaction_date, i.nama_item, td.quantity, td.price, i.harga_beli, (td.price - i.harga_beli) * td.quantity as profit');
        $builder->join('transactions t', 't.id = td.transaction_id');
        $builder->join('items i', 'i.id = td.item_id');
        $builder->where('t.status', 'paid');
        $data['profits'] = $builder->get()->getResultArray();

        return view('admin/reports/profit_loss', $data);
    }

    public function reportBestSellingProducts()
    {
        $data['best_selling'] = $this->transactionModel->getBestSellingProducts();
        return view('admin/reports/best_selling_products', $data);
    }

    public function reportTopCustomers()
    {
        $data['top_customers'] = $this->transactionModel->getTopCustomers();
        return view('admin/reports/top_customers', $data);
    }
}