<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>
    <h3>📋 Manajemen Barang</h3>

    <div class="mb-3 d-flex">
        <a href="<?= base_url('admin/items/import') ?>" class="btn btn-success me-2">
            <i class="bi bi-file-earmark-excel"></i> Import
        </a>
        <a href="<?= base_url('admin/items/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah
        </a>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
    <?php if (!empty($items)) : ?>
        <?php foreach($items as $item): ?>
            <tr>
                <td><?= esc($item['nama_item']) ?></td>
                <td><?= esc($item['nama_kategori']) ?></td>
                <td><?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= esc($item['stok']) ?></td>
               <td>
    <a href="<?= base_url('admin/items/edit/'.$item['id']) ?>" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil-square"></i>
    </a>
    <a href="<?= base_url('admin/items/delete/'.$item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus barang ini?')">
        <i class="bi bi-trash"></i>
    </a>
</td>
            </tr>
        <?php endforeach; ?>
    <?php else : ?>
        <tr>
            <td colspan="5" class="text-center">Belum ada data barang</td> </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links() ?>
        </div>
    </div>
<?= $this->endSection() ?>
