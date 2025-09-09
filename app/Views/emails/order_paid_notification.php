<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pembayaran Berhasil</title>
<style>
body { font-family: sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
.container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
.header { text-align: center; border-bottom: 1px solid #eeeeee; padding-bottom: 20px; margin-bottom: 20px; }
.header img { width: 50px; height: 50px; }
.header h1 { color: #28a745; margin: 0; font-size: 24px; }
.content p { line-height: 1.6; color: #555555; }
.details { background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 20px; }
.details h3 { color: #333333; margin-top: 0; }
.details table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.details th, .details td { padding: 8px; border-bottom: 1px solid #dddddd; text-align: left; }
.details th { background-color: #f1f1f1; }
.footer { text-align: center; margin-top: 20px; font-size: 12px; color: #999999; }
</style>
</head>
<body>

<div class="container">
<div class="header">
<h1 style="color:#28a745">Pembayaran Berhasil 🎉</h1>
</div>
<div class="content">
<p>Halo <?= esc($transaction['customer_name']) ?>,</p>
<p>Terima kasih telah berbelanja di toko kami. Pembayaran Anda dengan kode transaksi <?= esc($transaction['transaction_code']) ?> telah berhasil diproses.</p>
<div class="details">
<h3>Rincian Pesanan</h3>
<p><strong>Kode Transaksi:</strong> <?= esc($transaction['transaction_code']) ?></p>
<p><strong>Tanggal:</strong> <?= esc(date('d M Y, H:i', strtotime($transaction['transaction_date']))) ?></p>
<p><strong>Total Pembayaran:</strong> Rp. <?= esc(number_format($transaction['total_amount'])) ?></p>
<table>
<thead>
<tr>
<th>Item</th>
<th>Qty</th>
<th>Harga</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>
<?php foreach ($details as $item): ?>
<tr>
<td><?= esc($item['nama_item']) ?></td>
<td><?= esc($item['quantity']) ?></td>
<td>Rp. <?= esc(number_format($item['price'])) ?></td>
<td>Rp. <?= esc(number_format($item['quantity'] * $item['price'])) ?></td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<p style="margin-top:20px;">Anda dapat mencetak struk Anda di sini:</p>
<p><a href="<?= base_url('transaksi/struk/' . $transaction['id']) ?>" style="display:inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Cetak Struk</a></p>
</div>
<div class="footer">
<p>&copy; <?= date('Y') ?> Web Transaksi. All rights reserved.</p>
</div>
</div>

</body>
</html>