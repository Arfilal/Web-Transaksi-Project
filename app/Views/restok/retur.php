<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h3>Form Retur Restok</h3>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php elseif (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('admin/restok/processRetur/'.$restok['id_restok']) ?>" method="post">

        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Item</label>
            <input type="text" class="form-control" value="<?= esc($item['nama_item']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Restoker</label>
            <input type="text" class="form-control" value="<?= esc($restoker['nama_restoker']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Stok Diretur</label>
            <input type="number" name="stok_retur" class="form-control" min="0" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Retur</label>
            <input type="date" name="tanggal_retur" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">Simpan Retur</button>
        <a href="<?= base_url('admin/restok') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
