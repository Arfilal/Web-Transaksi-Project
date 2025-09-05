<?php

namespace App\Controllers;

use App\Models\RestokerModel;

class RestokerController extends BaseController
{
    protected $restokerModel;

    public function __construct()
    {
        $this->restokerModel = new RestokerModel();
    }

    public function index()
    {
        $data = [
            'title'     => 'Daftar Restoker',
            'restokers' => $this->restokerModel->paginate(10),
            'pager'     => $this->restokerModel->pager
        ];

        return view('restokers/index', $data);
    }

    public function create()
    {
        $data = ['title' => 'Tambah Restoker'];
        return view('restokers/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_restoker' => 'required',
            'kontak'        => 'required',
            'alamat'        => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi');
        }

        $this->restokerModel->save([
            'nama_restoker' => $this->request->getPost('nama_restoker'),
            'kontak'        => $this->request->getPost('kontak'),
            'alamat'        => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/admin/restoker')->with('success', 'Data Restoker berhasil ditambahkan');
    }

    public function edit($id)
    {
        $restoker = $this->restokerModel->find($id);

        if (!$restoker) {
            return redirect()->to('/admin/restoker')->with('error', 'Data Restoker tidak ditemukan');
        }

        $data = [
            'title'    => 'Edit Restoker',
            'restoker' => $restoker
        ];

        return view('restokers/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_restoker' => 'required',
            'kontak'        => 'required',
            'alamat'        => 'required'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Semua field wajib diisi');
        }

        $this->restokerModel->update($id, [
            'nama_restoker' => $this->request->getPost('nama_restoker'),
            'kontak'        => $this->request->getPost('kontak'),
            'alamat'        => $this->request->getPost('alamat'),
        ]);

        return redirect()->to('/admin/restoker')->with('success', 'Data Restoker berhasil diperbarui');
    }

    public function delete($id)
    {
        $this->restokerModel->delete($id);
        return redirect()->to('/admin/restoker')->with('success', 'Data Restoker berhasil dihapus');
    }
}
