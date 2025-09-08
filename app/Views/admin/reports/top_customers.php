<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h3 class="mb-3">🏆 Laporan Pelanggan Terbaik</h3>
<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelanggan</th>
                    <th>Total Transaksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($top_customers)): ?>
                    <?php $no = 1; foreach ($top_customers as $customer): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($customer['nama']) ?></td>
                        <td><?= esc($customer['total_transactions']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada data transaksi dari pelanggan yang login.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
