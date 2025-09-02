<!DOCTYPE html>
<html>
<head>
    <title>Checkout Test</title>
</head>
<body>
    <h2>Form Checkout</h2>
    <form method="post" action="<?= base_url('konsumen/pembelian/checkout') ?>">
        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Amount:</label>
        <input type="number" name="amount" required><br><br>

        <button type="submit">Bayar dengan Xendit</button>
    </form>
</body>
</html>
