<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Ajukan Retur</h1>

<div class="card p-4 mx-auto" style="max-width: 600px;">
    <p><strong>Nama Barang:</strong> <?= $transaction_detail['nama_item'] ?></p>
    <p><strong>Jumlah:</strong> <?= $transaction_detail['quantity'] ?></p>

    <form action="<?= base_url('konsumen/retur/create') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="transaction_detail_id" value="<?= $transaction_detail['id'] ?>">
        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Informasi Penting!</h4>
            Permintaan retur akan diproses oleh admin. Silakan tunggu konfirmasi selanjutnya.
        </div>
        <button type="submit" class="btn btn-purple">Ajukan Retur</button>
        <a href="<?= base_url('konsumen/riwayat/' . $transaction_detail['transaction_id']) ?>" class="btn btn-secondary">Batal</a>
    </form>
</div>
<?= $this->endSection() ?>