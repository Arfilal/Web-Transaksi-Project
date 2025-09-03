<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RestokModel;

class ReportsController extends BaseController
{
    protected $restokModel;

    public function __construct()
    {
        $this->restokModel = new RestokModel();
    }

    // Laporan stok
    public function stok()
    {
        $data = [
            'title' => 'Laporan Stok',
            'restok' => $this->restokModel->getRestok() // Ambil data restok
        ];

        return view('admin/reports/stok', $data);
    }
}
