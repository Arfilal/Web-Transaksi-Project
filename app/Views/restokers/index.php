<?= $this->extend('_layout'); ?>
<?= $this->section('content'); ?>

<div class="card p-4">
    <h2 class="mb-3">Daftar Restoker</h2>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Tombol Tambah -->
    <a href="<?= site_url('admin/restoker/create') ?>" class="btn btn-primary mb-3">+ Tambah Restoker</a>

    <!-- Tabel -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Restoker</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restokers)): ?>
                    <?php foreach ($restokers as $index => $restoker): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($restoker['nama_restoker']) ?></td>
                            <td><?= esc($restoker['kontak']) ?></td>
                            <td><?= esc($restoker['alamat']) ?></td>
                            <td>
                                <a href="<?= site_url('admin/restoker/edit/'.$restoker['id_restoker']) ?>" class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?= site_url('admin/restoker/delete/'.$restoker['id_restoker']) ?>" method="post" class="d-inline" onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada data Restoker</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
