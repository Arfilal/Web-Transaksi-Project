<?= $this->extend('_layout'); ?>
<?= $this->section('content'); ?>

<div class="card p-4 mx-auto" style="max-width: 600px;">
    <h2 class="mb-3">Tambah Pelanggan Baru</h2>

    <?php if (session()->has('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session('errors') as $error) : ?>
                <p><?= esc($error) ?></p>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <form action="<?= site_url('admin/customers/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="name" class="form-label">Nama Pelanggan</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Nomor Telepon</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?= old('phone') ?>">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Alamat</label>
            <textarea class="form-control" id="address" name="address" rows="3"><?= old('address') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?= site_url('admin/customers') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?= $this->endSection(); ?>
