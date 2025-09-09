<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="card p-4 mx-auto" style="max-width: 500px;">
    <h2 class="mb-3">Edit Kategori</h2>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('admin/categories/update/' . $category['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_kategori" class="form-label">Nama Kategori</label>
            <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="<?= esc($category['nama_kategori']) ?>" required>
        </div>
        <button type="submit" class="btn btn-purple">Simpan Perubahan</button>
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?= $this->endSection() ?>
