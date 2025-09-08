<?= $this->extend('_layout') ?>
<?= $this->section('content') ?>
<h3 class="mb-3">💰 Laporan Laba/Rugi</h3>

<div class="card p-3">
    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover">
            <thead>
                <tr>
                    <th>Kode Transaksi</th>
                    <th>Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Harga Jual</th>
                    <th>Harga Beli</th>
                    <th>Laba</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($profits)): ?>
                    <?php
                    $totalProfit = 0;
                    foreach ($profits as $profit):
                        $totalProfit += $profit['profit'];
                    ?>
                    <tr>
                        <td><?= esc($profit['transaction_code']) ?></td>
                        <td><?= esc(date('d M Y, H:i', strtotime($profit['transaction_date']))) ?></td>
                        <td><?= esc($profit['nama_item']) ?></td>
                        <td><?= esc($profit['quantity']) ?></td>
                        <td>Rp. <?= number_format($profit['price'], 0, ',', '.') ?></td>
                        <td>Rp. <?= number_format($profit['harga_beli'], 0, ',', '.') ?></td>
                        <td>Rp. <?= number_format($profit['profit'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data transaksi yang bisa dihitung labanya. Pastikan harga beli sudah diisi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($profits)): ?>
            <tfoot class="table-light">
                <tr>
                    <th colspan="6" class="text-end fw-bold">Total Laba</th>
                    <th class="fw-bold">Rp. <?= number_format($totalProfit, 0, ',', '.') ?></th>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
