<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Edit Kategori</h1>
<div class="card p-4 mx-auto" style="max-width: 500px;">
    <form action="<?= base_url('admin/categories/update/' . $category['id']) ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="category_name" class="form-label">Nama Kategori:</label>
            <input type="text" class="form-control" id="category_name" name="category_name" value="<?= esc($category['category_name']) ?>" required>
        </div>
        <button type="submit" class="btn btn-purple">Update</button>
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>