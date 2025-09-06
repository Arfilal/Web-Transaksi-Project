<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body text-center p-5">

                    <!-- Avatar -->
                    <img src="<?= $user['avatar'] ?>" 
                         alt="Avatar Google" 
                         class="rounded-circle border shadow-sm mb-3"
                         width="120" height="120"
                         onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['nama']) ?>&background=random';">

                    <!-- Nama & Email -->
                    <h3 class="fw-bold mb-1">Halo, <?= esc($user['nama']) ?> 👋</h3>
                    <p class="text-muted mb-3"><i class="bi bi-envelope-fill"></i> <?= esc($user['email']) ?></p>

                    <hr>

                    <!-- Tombol Logout -->
                    <a href="<?= base_url('logout') ?>" class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
