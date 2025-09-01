<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilihan Peran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #E0E0E0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            flex-direction: column;
        }
        .container {
            text-align: center;
        }
        .card {
            background-color: #1E1E1E;
            border: none;
            border-radius: 15px;
            color: #E0E0E0;
            padding: 40px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .btn-purple {
            background-color: #8A2BE2;
            border: none;
            color: #FFFFFF;
            font-size: 1.25rem;
            padding: 15px 30px;
        }
        .btn-purple:hover {
            background-color: #9932CC;
        }
        h1 {
            color: #DA70D6;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>Pilih Peran Anda</h1>
        <div class="d-flex justify-content-center">
            <a href="<?= base_url('admin/items') ?>" class="btn btn-purple me-4">Admin</a>
            <a href="<?= base_url('konsumen/pembelian') ?>" class="btn btn-purple">Konsumen</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>