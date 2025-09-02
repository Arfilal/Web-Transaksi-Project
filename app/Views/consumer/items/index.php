<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>

<h1 class="text-center mb-4">Menu Pembelian</h1>

<div class="card p-4">
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

    <table id="barangTable" class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1; 
        foreach ($items as $item): 
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($item['nama_item']) ?></td>
            <td>Rp<?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['stok'] ?></td>
            <td>
                <a href="<?= base_url('konsumen/pembelian/add/' . $item['id']) ?>" 
                   class="btn btn-sm btn-success">Beli</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</div>

<!-- Keranjang Belanja -->
<div class="card p-4 mt-4">
    <h3 class="text-center mb-3">Keranjang Belanja</h3>
    <?php if (!empty($cart_items)): ?>
        <table class="table table-hover">
            <thead class="table-secondary">
                <tr>
                    <th>Nama Barang</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $grandTotal = 0;
                foreach ($cart_items as $itemInCart): 
                    $subtotal = $itemInCart['harga'] * $itemInCart['quantity'];
                    $grandTotal += $subtotal;
                ?>
                <tr>
                    <td><?= esc($itemInCart['nama_item']) ?></td>
                    <td>Rp<?= number_format($itemInCart['harga'], 0, ',', '.') ?></td>
                    <td><?= $itemInCart['quantity'] ?></td>
                    <td>Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
                <tr>
                    <th colspan="3" class="text-end">Grand Total</th>
                    <th>Rp<?= number_format($grandTotal, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
        </table>
        <div class="text-center mt-3">
            <a href="<?= base_url('konsumen/pembelian/checkout') ?>" class="btn btn-primary">
                Proses Pembelian
            </a>
        </div>
    <?php else: ?>
        <p class="text-center">Keranjang masih kosong.</p>
    <?php endif; ?>
</div>

<!-- Optional: aktifkan DataTables biar tabel lebih interaktif -->
<script>
    $(document).ready(function() {
        $('#barangTable').DataTable({
            "language": {
                "search": "Cari Barang:",
                "lengthMenu": "Tampilkan _MENU_ data",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
            }
        });
    });
</script>

<?= $this->endSection() ?>
