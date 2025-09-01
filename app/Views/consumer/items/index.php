<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h1 class="text-center mb-4">Menu Pembelian</h1>

<div class="card p-4 mb-4">
    <h3 class="text-center">Daftar Barang</h3>
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item['nama_item'] ?></td>
                <td><?= number_format($item['harga']) ?></td>
                <td><?= $item['stok'] ?></td>
                <td><a href="<?= base_url('konsumen/pembelian/add/' . $item['id']) ?>" class="btn btn-sm btn-purple">Beli</a></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card p-4">
    <h3 class="text-center">Keranjang Belanja</h3>
    <?php if (!empty($cart_items)): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $itemInCart): ?>
                <tr>
                    <td><?= $itemInCart['nama_item'] ?></td>
                    <td><?= number_format($itemInCart['harga']) ?></td> <!-- ✅ ganti dari price ke harga -->
                    <td><?= $itemInCart['quantity'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="text-center mt-3">
            <a href="<?= base_url('konsumen/pembelian/checkout') ?>" class="btn btn-purple">Proses Pembelian</a>
        </div>
    <?php else: ?>
        <p class="text-center">Keranjang masih kosong.</p>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
