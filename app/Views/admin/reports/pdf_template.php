<style>
    body { font-family: sans-serif; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid black; padding: 8px; text-align: left; }
</style>

<h1>Laporan Penjualan</h1>
<table>
    <thead>
        <tr>
            <th>Kode Transaksi</th>
            <th>Tanggal</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?= $transaction['transaction_code'] ?></td>
            <td><?= $transaction['transaction_date'] ?></td>
            <td>Rp. <?= number_format($transaction['total_amount']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>