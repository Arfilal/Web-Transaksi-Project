<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CategoryModel;

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
            'categories' => $this->categoryModel->paginate(10),
            'pager'      => $this->categoryModel->pager
        ];
        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/create');
    }

    public function store()
    {
        $data = $this->request->getPost();
        if ($this->categoryModel->save($data)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil ditambahkan.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kategori.');
    }

    public function edit($id)
    {
        $data = [
            'category' => $this->categoryModel->find($id)
        ];
        return view('admin/categories/edit', $data);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        if ($this->categoryModel->update($id, $data)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil diperbarui.');
        }
        return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kategori.');
    }

    public function delete($id)
    {
        if ($this->categoryModel->delete($id)) {
            return redirect()->to(base_url('admin/categories'))->with('success', 'Kategori berhasil dihapus.');
        }
        return redirect()->to(base_url('admin/categories'))->with('error', 'Gagal menghapus kategori.');
    }
}