<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>History Restok</h3>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Jumlah Dipesan</th>
                <th>Jumlah Diterima</th>
                <th>Jumlah Diretur</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($history)) : ?>
                <?php $no = 1; foreach ($history as $h) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($h['nama_item']) ?></td>
                        <td><?= esc($h['nama_restoker']) ?></td>
                        <td><?= esc($h['stok_dipesan']) ?></td>
                        <td><?= esc($h['stok_sampai']) ?></td>
                        <td><?= esc($h['stok_retur']) ?></td>
                        <td><?= date('d-m-Y', strtotime($h['tanggal_pesan'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">Belum ada data restok</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
