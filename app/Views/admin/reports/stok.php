<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <h3 class="mb-3">📊 Laporan Stok</h3>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Restok</th>
                    <th>Tanggal Restok</th>
                    <th>Restoker</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restok)): ?>
                    <?php foreach ($restok as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= esc($r['nama_item']) ?></td>
                            <td><?= esc($r['jumlah']) ?></td>
                            <td><?= esc($r['tanggal']) ?></td>
                            <td><?= esc($r['restoker']) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data restok</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            <?= $pager ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
