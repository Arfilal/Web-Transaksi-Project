<?= $this->extend('_layout'); ?>
<?= $this->section('content'); ?>

<div class="card p-4">
    <h2 class="mb-3">Tambah Restoker</h2>

    <form action="<?= site_url('admin/restoker/store') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="nama_restoker" class="form-label">Nama Restoker</label>
            <input type="text" class="form-control" id="nama_restoker" name="nama_restoker" required>
        </div>

        <div class="mb-3">
            <label for="kontak" class="form-label">Kontak</label>
            <input type="text" class="form-control" id="kontak" name="kontak" required>
        </div>

        <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-purple">Simpan</button>
        <a href="<?= site_url('admin/restoker') ?>" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?= $this->endSection(); ?>
