<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>History Restok</h3>

    <a href="<?= base_url('admin/restok/history/export-excel') ?>" class="btn btn-success mb-3">
        Export Excel
    </a>


    <table class="table table-dark table-bordered table-striped text-center align-middle mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Item</th>
                <th>Supplier</th>
                <th>Jumlah Dipesan</th>
                <th>Jumlah Diterima</th>
                <th>Jumlah Diretur</th>
                <th>Tanggal Pesan</th>
                <th>Tanggal Sampai</th>
                <th>Tanggal Retur</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($history)) : ?>
                <?php 
                    // ✅ Nomor urut sesuai halaman
                    $no = 1 + (10 * ($pager->getCurrentPage() - 1)); 
                ?>
                <?php foreach ($history as $h) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($h['nama_item']) ?></td>
                        <td><?= esc($h['nama_restoker']) ?></td>
                        <td><?= esc($h['stok_dipesan']) ?></td>
                        <td><?= ($h['stok_sampai'] !== null) ? esc($h['stok_sampai']) : '-' ?></td>
                        <td><?= ($h['stok_retur'] !== null) ? esc($h['stok_retur']) : 0 ?></td>
                        <td><?= !empty($h['tanggal_pesan']) ? date('d-m-Y', strtotime($h['tanggal_pesan'])) : '-' ?></td>
                        <td><?= !empty($h['tanggal_sampai']) ? date('d-m-Y', strtotime($h['tanggal_sampai'])) : '-' ?></td>
                        <td><?= !empty($h['tanggal_retur']) ? date('d-m-Y', strtotime($h['tanggal_retur'])) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="text-center">Belum ada data restok</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ✅ Pagination -->
    <div class="d-flex justify-content-center mt-3">
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection() ?>
