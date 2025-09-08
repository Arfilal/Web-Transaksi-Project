<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<h1 class="text-center mb-4">Menu Pembelian</h1>

<form action="<?= base_url('konsumen/pembelian/add-selected') ?>" method="post">
    <?= csrf_field() ?>

    <!-- Tombol pindah ke atas -->
    <div class="text-start mb-3">
        <button type="submit" class="btn btn-success">
            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
        </button>
    </div>

    <table id="barangTable" class="table table-dark table-striped table-hover">
        <thead>
            <tr>
                <th>Pilih</th>
                <th>No.</th>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Qty</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; foreach ($items as $item): ?>
            <tr>
                <td><input type="checkbox" name="item_id[]" value="<?= $item['id']; ?>"></td>
                <td><?= $no++ ?></td>
                <td><?= esc($item['nama_item']) ?></td>
                <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
                <td><?= $item['stok'] ?></td>
                <td style="width:90px;">
                    <input type="number" 
                           name="qty[<?= $item['id']; ?>]" 
                           value="1" min="1" max="<?= (int)$item['stok']; ?>" 
                           class="form-control bg-dark text-light border-secondary">
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</form>

<!-- Keranjang Belanja -->
<h2 class="text-center mb-4">Keranjang Belanja</h2>
<div class="p-4 bg-dark text-white rounded">
    <table class="table table-dark table-hover text-center">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
    <?php if (!empty($cart_items)): ?>
        <?php foreach ($cart_items as $ci): ?>
        <tr>
            <td><?= esc($ci['nama_item']) ?></td>
            <td>Rp<?= number_format($ci['harga']) ?></td>
            <td><?= $ci['quantity'] ?></td>
            <td>Rp<?= number_format($ci['harga'] * $ci['quantity']) ?></td>
            <td>
                <a href="<?= base_url('konsumen/pembelian/remove/' . $ci['cart_item_id']) ?>" 
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Hapus barang ini dari keranjang?')">
                    <i class="bi bi-trash"></i> Hapus
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">Keranjang kosong</td>
        </tr>
    <?php endif; ?>
</tbody>

    </table>
    <div class="text-end mt-3">
        <h4>Grand Total: Rp<?= number_format($grandTotal) ?></h4>
        <a href="<?= base_url('konsumen/checkout') ?>" class="btn btn-primary">
            Proses Pembelian
        </a>
    </div>
</div>

<?= $this->endSection() ?>
