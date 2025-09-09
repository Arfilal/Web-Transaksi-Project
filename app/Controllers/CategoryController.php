<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use CodeIgniter\Controller;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {
        $data = [
            'title'      => 'Manajemen Kategori',
            'categories' => $this->categoryModel->paginate(10),
            'pager'      => $this->categoryModel->pager
        ];

        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/create', ['title' => 'Tambah Kategori']);
    }

    public function store()
    {
        if (!$this->validate(['nama_kategori' => 'required'])) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori wajib diisi.');
        }

        $this->categoryModel->save(['nama_kategori' => $this->request->getPost('nama_kategori')]);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to(base_url('admin/categories'))->with('error', 'Kategori tidak ditemukan.');
        }

        return view('admin/categories/edit', [
            'title'    => 'Edit Kategori',
            'category' => $category
        ]);
    }

    public function update($id)
    {
        if (!$this->validate(['nama_kategori' => 'required'])) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori wajib diisi.');
        }

        $this->categoryModel->update($id, ['nama_kategori' => $this->request->getPost('nama_kategori')]);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil diupdate.');
    }

    public function delete($id)
    {
        $this->categoryModel->delete($id);
        return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dihapus.');
    }
}
