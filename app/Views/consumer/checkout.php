<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 shadow">
                <h2 class="text-center mb-4">Ringkasan Pesanan</h2>

                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <table class="table table-dark table-hover mb-4">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart_items as $item): ?>
                            <tr>
                                <td><?= esc($item['nama_item']) ?></td>
                                <td class="text-center"><?= esc($item['quantity']) ?></td>
                                <td class="text-end">Rp<?= number_format($item['harga'] * $item['quantity']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="2" class="text-end">Grand Total</td>
                            <td class="text-end">Rp<?= number_format($grandTotal) ?></td>
                        </tr>
                    </tfoot>
                </table>

                <hr class="border-secondary">

                <h3 class="text-center my-4">Data Pelanggan</h3>

                <form action="<?= base_url('konsumen/proses-checkout') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nama Pelanggan</label>
                        <input type="text" name="customer_name" id="customer_name" class="form-control" placeholder="Masukkan nama pelanggan" required>
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">No. Telepon (Opsional)</label>
                        <input type="text" name="customer_phone" id="customer_phone" class="form-control" placeholder="Masukkan nomor telepon">
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-shield-check"></i> Bayar Sekarang
                        </button>
                        <a href="<?= base_url('konsumen/pembelian') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

