<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 300px; /* Lebar umum kertas struk */
            margin: 0 auto;
            padding: 20px;
            font-size: 14px;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            font-size: 18px;
        }
        .info {
            margin-bottom: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .info p {
            margin: 2px 0;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
        }
        .items th, .items td {
            padding: 5px 0;
        }
        .items .price {
            text-align: right;
        }
        .total {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
            text-align: right;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h3>Toko Serbaguna</h3>
        <p>Jl. CodeIgniter No. 4, Jakarta</p>
    </div>

    <div class="info">
        <p>No: <?= esc($transaction['transaction_code']) ?></p>
        <p>Tgl: <?= date('d/m/Y H:i', strtotime($transaction['transaction_date'])) ?></p>
        <p>Pelanggan: <?= esc($transaction['customer_name'] ?? 'Umum') ?></p>
    </div>

    <div class="items">
        <table>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php foreach($details as $item): ?>
                <?php $subtotal = $item['quantity'] * $item['price']; $grandTotal += $subtotal; ?>
                <tr>
                    <td colspan="2"><?= esc($item['nama_item']) ?></td>
                </tr>
                <tr>
                    <td><?= $item['quantity'] ?> x <?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td class="price"><?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="total">
        <p>TOTAL : Rp <?= number_format($grandTotal, 0, ',', '.') ?></p>
    </div>

    <div class="footer">
        <p>Terima Kasih Telah Berbelanja!</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()">Cetak Ulang</button>
    </div>

</body>
</html>
