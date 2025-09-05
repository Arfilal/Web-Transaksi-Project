<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Riwayat Pembelian</h1>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Kode Transaksi</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transactions as $t): ?>
            <tr>
                <td><?= $t['transaction_code'] ?></td>
                <td><?= $t['transaction_date'] ?></td>
                <td>Rp. <?= number_format($t['total_amount']) ?></td>
                <td>
                    <a href="<?= base_url('konsumen/riwayat/' . $t['id']) ?>" 
                       class="btn btn-sm btn-purple">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
