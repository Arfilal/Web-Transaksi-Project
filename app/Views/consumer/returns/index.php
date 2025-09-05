<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Pengembalian Barang</h1>

<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Tanggal Retur</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($returns as $retur): ?>
            <tr>
                <td><?= $retur['nama_item'] ?></td>
                <td><?= $retur['return_date'] ?></td>
                <td><?= $retur['status'] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
