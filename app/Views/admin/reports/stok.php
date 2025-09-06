<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<h3 class="mb-3">📊 Laporan Stok</h3>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jumlah Restok</th>
                    <th>Tanggal Restok</th>
                    <th>Restoker</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restok)): ?>
                    <?php 
                        // Menghitung nomor urut berdasarkan halaman saat ini
                        $page = (int) (request()->getVar('page') ?? 1);
                        $perPage = 10; // Sesuaikan dengan jumlah item per halaman Anda
                        $no = 1 + ($perPage * ($page - 1));
                    ?>
                    <?php foreach ($restok as $r): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($r['nama_item']) ?></td>
                            <td><?= esc($r['jumlah']) ?></td>
                            <td><?= esc($r['tanggal']) ?></td>
                            <td><?= esc($r['restoker']) ?></td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data restok</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <?= $pager ?>
    </div>
</div>

<?= $this->endSection() ?>