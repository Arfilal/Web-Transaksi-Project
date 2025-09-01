<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Manajemen Barang</h1>
<a href="<?= base_url('admin/items/create') ?>" class="btn btn-purple mb-4 me-2">Tambah Barang</a>
<a href="<?= base_url('admin/items/import') ?>" class="btn btn-purple mb-4">Impor dari Excel</a>

<div class="card p-4">
    <h2 class="text-center">Daftar Barang</h2>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['nama_item'] ?></td>
                <td><?= number_format($item['harga']) ?></td>
                <td><?= $item['stok'] ?></td>
                <td>
                    <a href="<?= base_url('admin/items/edit/' . $item['id']) ?>" class="btn btn-sm btn-info">Edit</a>
                    <a href="<?= base_url('admin/items/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
