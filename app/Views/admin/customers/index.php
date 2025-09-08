<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3 class="mb-3">👤 Manajemen Pelanggan</h3>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('admin/customers/create') ?>" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Tambah Pelanggan
    </a>

    <div class="table-responsive">
        <table class="table table-dark table-bordered table-striped text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php $no = 1 + (10 * ($pager->getCurrentPage('customers') - 1)); ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($customer['name']) ?></td>
                            <td><?= esc($customer['phone']) ?></td>
                            <td><?= esc($customer['address']) ?></td>
                            <td>
                                <a href="<?= site_url('admin/customers/edit/'.$customer['id']) ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="<?= site_url('admin/customers/delete/'.$customer['id']) ?>" 
                                      method="post" class="d-inline"
                                      onsubmit="return confirm('Yakin mau hapus pelanggan ini?')">
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
                        <td colspan="5" class="text-center">Belum ada data pelanggan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <?= $pager->links('customers') ?>
    </div>
</div>

<?= $this->endSection() ?>
