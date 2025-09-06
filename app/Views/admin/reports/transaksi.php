<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>
    <h2 class="mb-3">📑 Laporan Transaksi</h2>

    <div class="card p-3">
        <table class="table table-dark table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($transaksi)): ?>
                    <?php foreach ($transaksi as $t): ?>
                    <tr>
                        <td><?= $t['transaction_code'] ?></td>
                        <td><?= $t['transaction_date'] ?></td>
                        <td><?= number_format($t['total_amount'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data transaksi</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            <?= $pager->links() ?>
        </div>
    </div>
<?= $this->endSection() ?>