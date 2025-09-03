<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Data Restok</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <a href="<?= base_url('admin/restok/create') ?>" class="btn btn-primary mb-3">+ Tambah Restok</a>

    <table class="table table-dark table-striped align-middle text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Supplier</th>
                <th>Produk</th>
                <th>Dipesan</th>
                <th>Diterima</th>
                <th>Diretur</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($restoks)): ?>
                <?php $no = 1; foreach ($restoks as $r): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($r['nama_restoker']) ?></td>
                        <td><?= esc($r['nama_item']) ?></td>
                        <td><?= esc($r['stok_dipesan']) ?></td>

                        <!-- diterima -->
                        <td>
                            <?= ($r['stok_sampai'] !== null) ? esc($r['stok_sampai']) : '-' ?>
                        </td>

                        <!-- retur (default 0 untuk restok) -->
                        <td>0</td>

                        <!-- status -->
                        <td>
                            <?php if ($r['stok_sampai'] === null): ?>
                                <span class="badge bg-secondary">-</span>
                            <?php elseif ($r['stok_sampai'] == $r['stok_dipesan']): ?>
                                <span class="badge bg-success">Diterima</span>
                            <?php elseif ($r['stok_sampai'] < $r['stok_dipesan']): ?>
                                <span class="badge bg-warning">
                                    Sebagian (<?= esc($r['stok_sampai']) ?>/<?= esc($r['stok_dipesan']) ?>)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">Error</span>
                            <?php endif; ?>
                        </td>

                        <!-- aksi -->
                        <td>
                            <?php if ($r['stok_sampai'] === null): ?>
                                <a href="<?= base_url('admin/restok/edit/'.$r['id_restok']) ?>" 
                                   class="btn btn-sm btn-primary">Konfirmasi</a>
                            <?php else: ?>
                                <span class="text-muted">Selesai</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">Belum ada data restok</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
