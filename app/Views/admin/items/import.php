<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Impor Barang dari Excel</h1>
<div class="card p-4 mx-auto" style="max-width: 600px;">
    <p>Silakan unggah file Excel (.xlsx) dengan format kolom berikut:</p>
    <ul>
        <li>Kolom A: Nama Barang (String)</li>
        <li>Kolom B: Harga (Angka)</li>
        <li>Kolom C: Stok (Angka)</li>
    </ul>
    
    <form action="<?= base_url('admin/items/import') ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="excel_file" class="form-label">Pilih File Excel:</label>
            <input type="file" class="form-control" id="excel_file" name="excel_file" required>
        </div>
        <button type="submit" class="btn btn-purple">Unggah dan Impor</button>
        <a href="<?= base_url('admin/items') ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>
