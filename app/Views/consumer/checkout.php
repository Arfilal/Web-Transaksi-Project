<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card p-4">
            <h2 class="text-center mb-4">Ringkasan Pesanan</h2>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="table-responsive mb-4">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                        <tr>
                            <td><?= esc($item['nama_item']) ?></td>
                            <td class="text-end"><?= $item['quantity'] ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
                            <td class="text-end">Rp <?= number_format($item['harga'] * $item['quantity'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end">Grand Total</td>
                            <td class="text-end">Rp <?= number_format($grandTotal, 0, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="d-grid">
                <form action="<?= base_url('konsumen/pembelian/proses-checkout') ?>" method="post">
                <?= csrf_field() ?>
                <h4 class="mt-4 mb-3">Informasi Pelanggan</h4>
                <div class="mb-3">
                    <label for="customerName" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="customerName" name="customer_name" required>
                </div>
                <div class="mb-3">
                    <label for="customerPhone" class="form-label">Nomor Telepon</label>
                    <input type="tel" class="form-control" id="customerPhone" name="customer_phone" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Bayar Sekarang</button>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>