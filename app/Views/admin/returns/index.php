<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Manajemen Pengembalian Barang</h1>
<div class="card p-4">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>ID Retur</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($returns as $retur): ?>
            <tr>
                <td><?= $retur['id'] ?></td>
                <td><?= $retur['status'] ?></td>
                <td>
                    <?php if ($retur['status'] === 'diproses'): ?>
                        <a href="<?= base_url('admin/returns/update-status/' . $retur['id']) ?>" class="btn btn-sm btn-purple" onclick="return confirm('Apakah Anda yakin ingin menyelesaikan pengembalian ini?')">Selesaikan</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
