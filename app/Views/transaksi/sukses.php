<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="card shadow-lg p-4 text-center" style="max-width: 500px; border-radius: 20px;">
        <div class="mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="green" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.97 11.03a.75.75 0 0 0 1.08 0l3.992-3.992a.75.75 0 0 0-1.08-1.08L7.5 9.439 5.62 7.56a.75.75 0 0 0-1.08 1.06z"/>
            </svg>
        </div>
        <h3 class="fw-bold text-success">Pembayaran Berhasil 🎉</h3>
        <p class="text-muted">Terima kasih, transaksi Anda sudah diproses dengan sukses.</p>
        
        <div class="d-grid gap-2">
            <?php if (isset($transaction_id)): ?>
                <a href="<?= base_url('transaksi/struk/' . $transaction_id) ?>" target="_blank" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            <?php endif; ?>
            
            <a href="<?= base_url('konsumen/pembelian') ?>" class="btn btn-success">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
