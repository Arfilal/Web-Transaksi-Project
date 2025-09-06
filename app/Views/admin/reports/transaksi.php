<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-3">📑 Laporan Transaksi</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>
