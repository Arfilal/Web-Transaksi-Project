<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Detail Transaksi</h1>
<div class="card p-4">
    <h3 class="card-title">Informasi Transaksi</h3>
    <p><strong>Kode Transaksi:</strong> <?= $transaction['transaction_code'] ?></p>
    <p><strong>Tanggal:</strong> <?= $transaction['transaction_date'] ?></p>
    <p><strong>Total:</strong> Rp. <?= number_format($transaction['total_amount']) ?></p>

    <h3 class="mt-4">Daftar Barang</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $d): ?>
            <tr>
                <td><?= $d['nama_item'] ?></td>
                <td><?= number_format($d['price']) ?></td>
                <td><?= $d['quantity'] ?></td>
                <td><?= number_format($d['price'] * $d['quantity']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="<?= base_url('admin/transactions') ?>" class="btn btn-secondary mt-3">Kembali</a>
</div>
<?= $this->endSection() ?>
