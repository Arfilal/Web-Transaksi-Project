<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>
    <h2 class="mb-3">♻️ Laporan Pengembalian</h2>

    <div class="card p-3">
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Return</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pengembalian)): ?>
                    <?php foreach ($pengembalian as $p): ?>
                    <tr>
                        <td><?= $p['transaction_code'] ?></td>
                        <td><?= $p['nama_item'] ?></td>
                        <td><?= $p['quantity'] ?></td>
                        <td><?= $p['return_date'] ?></td>
                        <td><span class="badge bg-success"><?= $p['status'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data pengembalian</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links() ?>
        </div>
    </div>
<?= $this->endSection() ?>