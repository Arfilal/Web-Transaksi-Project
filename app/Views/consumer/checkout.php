<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <!-- Pakai client key sandbox kamu -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" 
            data-client-key="SB-Mid-client-W9NItkFt079JFomg"></script>
</head>
<body>
    <h2>Checkout</h2>

    <p>Total Belanja: Rp <?= number_format(array_sum(array_map(fn($i) => $i['harga'] * $i['quantity'], $items)), 0, ',', '.') ?></p>

    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            snap.pay('<?= $snapToken ?>', {
                onSuccess: function(result){
                    console.log("Sukses:", result);
                    alert("Pembayaran sukses!");
                },
                onPending: function(result){
                    console.log("Pending:", result);
                    alert("Menunggu pembayaran...");
                },
                onError: function(result){
                    console.log("Error:", result);
                    alert("Pembayaran gagal!");
                }
            });
        };
    </script>
</body>
</html>
