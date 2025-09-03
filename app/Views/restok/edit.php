<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<h2>Edit Restok</h2>

<form action="<?= base_url('admin/restok/update/' . $restok['id_restok']) ?>" method="post">
    <?= csrf_field() ?>

    <div class="mb-3">
        <label for="id_item" class="form-label">Pilih Item</label>
        <select name="id_item" id="id_item" class="form-control" required>
            <?php foreach ($items as $item): ?>
                <option value="<?= $item['id'] ?>"
                    <?= (int)$item['id'] === (int)$restok['id_item'] ? 'selected' : '' ?>>
                    <?= esc($item['nama_item']) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="id_restoker" class="form-label">Restoker</label>
        <select name="id_restoker" id="id_restoker" class="form-control" required>
            <?php foreach ($restokers as $r): ?>
                <option value="<?= $r['id_restoker'] ?>"
                    <?= (int)$r['id_restoker'] === (int)$restok['id_restoker'] ? 'selected' : '' ?>>
                    <?= esc($r['nama_restoker']) ?>
                </option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="mb-3">
    <label for="stok_sampai" class="form-label">Stok Sampai</label>
    <input type="number" name="stok_sampai" id="stok_sampai" class="form-control"
           value="<?= esc($restok['stok_sampai']) ?>" required min="0">
</div>

<div class="mb-3">
    <label for="tanggal_sampai" class="form-label">Tanggal Sampai</label>
    <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control"
           value="<?= isset($restok['tanggal_sampai']) ? substr($restok['tanggal_sampai'],0,10) : '' ?>" required>
</div>

    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    <a href="<?= base_url('admin/restok') ?>" class="btn btn-secondary">Batal</a>
</form>

<?= $this->endSection() ?>
