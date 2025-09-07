<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h3>📂 Manajemen Kategori</h3>

<div class="mb-3">
    <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Kategori
    </a>
</div>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categories)) : ?>
                    <?php $no = 1 + (10 * ($pager->getCurrentPage('default', 1) - 1)); ?>
                    <?php foreach($categories as $category): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($category['category_name']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/categories/edit/'.$category['id']) ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="<?= base_url('admin/categories/delete/'.$category['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus kategori ini? Menghapus kategori tidak akan menghapus barang di dalamnya.')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data kategori</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-3">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>