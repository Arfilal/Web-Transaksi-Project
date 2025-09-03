<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-header">
        <h3>Tambah Restok</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/restok/store') ?>" method="post">
            <div class="mb-3">
                <label for="id_item" class="form-label">Item</label>
                <select name="id_item" id="id_item" class="form-control" required>
                    <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= esc($item['nama_item']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="stok_dipesan" class="form-label">Jumlah Stok</label>
                <input type="number" name="stok_dipesan" id="stok_dipesan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="tanggal_pesan" class="form-label">Tanggal Pesan</label>
                <input type="date" name="tanggal_pesan" id="tanggal_pesan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="id_restoker" class="form-label">Restoker</label>
                <select name="id_restoker" id="id_restoker" class="form-control" required>
                    <?php foreach ($restokers as $r): ?>
                        <option value="<?= $r['id_restoker'] ?>"><?= esc($r['nama_restoker']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="<?= base_url('admin/restok') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
