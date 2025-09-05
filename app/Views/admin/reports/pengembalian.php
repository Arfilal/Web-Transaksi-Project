<?= $this->extend('_layout') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-3">Laporan Pengembalian</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Kode Transaksi</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Return</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pengembalian as $p): ?>
            <tr>
                <td><?= $p['transaction_code'] ?></td>
                <td><?= $p['nama_item'] ?></td>
                <td><?= $p['quantity'] ?></td>
                <td><?= $p['return_date'] ?></td>
                <td><span class="badge bg-success"><?= $p['status'] ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>