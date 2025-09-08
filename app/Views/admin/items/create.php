<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Tambah Barang</h1>
<div class="card p-4 mx-auto" style="max-width: 500px;">
    <form action="<?= base_url('admin/items/create') ?>" method="post">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="nama_item" class="form-label">Nama Barang:</label>
            <input type="text" class="form-control" id="nama_item" name="nama_item" required>
        </div>
        <div class="mb-3">
            <label for="harga" class="form-label">Harga Jual:</label>
            <input type="number" class="form-control" id="harga" name="harga" required>
        </div>
        <div class="mb-3">
            <label for="harga_beli" class="form-label">Harga Beli:</label>
            <input type="number" class="form-control" id="harga_beli" name="harga_beli" required>
        </div>
        <div class="mb-3">
            <label for="stok" class="form-label">Stok:</label>
            <input type="number" class="form-control" id="stok" name="stok" required>
        </div>
        <button type="submit" class="btn btn-purple">Simpan</button>
        <a href="<?= base_url('admin/items') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>