<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Edit Barang</h1>
<div class="card p-4 mx-auto" style="max-width: 500px;">

    <!-- TAMBAHKAN BLOK KODE UNTUK MENAMPILKAN ERROR -->
    <?php if (isset($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error) : ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach ?>
            </ul>
        </div>
    <?php endif ?>
    
    <form action="<?= base_url('admin/items/edit/' . $item['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <!-- Sisa form tetap sama -->
        <div class="mb-3">
            <label for="nama_item" class="form-label">Nama Barang:</label>
            <input type="text" class="form-control" id="nama_item" name="nama_item" value="<?= old('nama_item', $item['nama_item']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori:</label>
            <select name="category_id" id="category_id" class="form-control">
                <option value="">-- Pilih Kategori --</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?= $category['id'] ?>" <?= (old('category_id', $item['category_id']) == $category['id']) ? 'selected' : '' ?>>
                        <?= esc($category['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga:</label>
            <input type="number" class="form-control" id="harga" name="harga" value="<?= old('harga', $item['harga']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok:</label>
            <input type="number" class="form-control" id="stok" name="stok" value="<?= old('stok', $item['stok']) ?>" required>
        </div>
        <button type="submit" class="btn btn-purple">Update</button>
        <a href="<?= base_url('admin/items') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>