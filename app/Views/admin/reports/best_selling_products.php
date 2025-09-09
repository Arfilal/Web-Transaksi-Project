<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h3 class="mb-3">⭐ Laporan Produk Terlaris</h3>
<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Produk</th>
                    <th>Total Terjual</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($best_selling)): ?>
                    <?php $no = 1; foreach ($best_selling as $product): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($product['nama_item']) ?></td>
                        <td><?= esc($product['total_quantity']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data penjualan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>