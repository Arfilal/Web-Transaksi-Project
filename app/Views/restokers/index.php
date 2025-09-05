<?= $this->extend('_layout'); ?>
<?= $this->section('content'); ?>

<div class="container mt-4">
    <h3>Daftar Restoker</h3>

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

    <a href="<?= site_url('admin/restoker/create') ?>" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah
    </a>

    <div class="table-responsive">
        <table class="table table-dark table-bordered table-striped text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Restoker</th>
                    <th>Kontak</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($restokers)): ?>
                    <?php $no = 1 + (10 * ($pager->getCurrentPage() - 1)); ?>
                    <?php foreach ($restokers as $restoker): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($restoker['nama_restoker']) ?></td>
                            <td><?= esc($restoker['kontak']) ?></td>
                            <td><?= esc($restoker['alamat']) ?></td>
                            <td>
                                <a href="<?= site_url('admin/restoker/edit/'.$restoker['id_restoker']) ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= site_url('admin/restoker/delete/'.$restoker['id_restoker']) ?>" 
                                      method="post" class="d-inline"
                                      onsubmit="return confirm('Yakin mau hapus data ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
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

    <div class="d-flex justify-content-center mt-3">
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection(); ?>
