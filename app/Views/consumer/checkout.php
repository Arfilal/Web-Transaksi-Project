<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<h1 class="text-center mb-4">Checkout</h1>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h4 class="mb-3">Ringkasan Pesanan</h4>
            <table class="table table-dark table-hover">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cart_items)): ?>
                        <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= esc($item['nama_item']) ?></td>
                            <td class="text-center"><?= $item['quantity'] ?></td>
                            <td class="text-end">Rp<?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <th colspan="2" class="text-end fw-bold">Grand Total</th>
                        <th class="text-end fw-bold">Rp<?= number_format($grandTotal, 0, ',', '.') ?></th>
                    </tr>
                </tfoot>
            </table>

            <hr class="my-4">

            <h4 class="mb-3">Data Pelanggan</h4>
            <form action="<?= base_url('konsumen/pembelian/proses-checkout') ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Nama Pelanggan</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                </div>
                 <div class="mb-3">
                    <label for="customer_phone" class="form-label">No. Telepon (Opsional)</label>
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone">
                </div>
                <div class="d-grid gap-2">
                     <button type="submit" class="btn btn-primary btn-lg">Bayar Sekarang</button>
                     <a href="<?= base_url('konsumen/pembelian') ?>" class="btn btn-secondary">Kembali ke Pembelian</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
