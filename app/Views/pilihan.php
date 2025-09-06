    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pilihan Peran</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- ✅ Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

        <style>
            /* Animated Gradient Background */
            body {
                margin: 0;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: linear-gradient(-45deg, #8A2BE2, #4B0082, #DA70D6, #6A5ACD);
                background-size: 400% 400%;
                animation: gradientBG 15s ease infinite;
                color: #E0E0E0;
                flex-direction: column;
            }
            @keyframes gradientBG {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .container {
                text-align: center;
            }

            .card {
                background-color: rgba(30,30,30,0.9);
                border: none;
                border-radius: 15px;
                color: #E0E0E0;
                padding: 40px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.6);
                opacity: 0;
                transform: translateY(50px);
                animation: cardFadeIn 0.8s forwards;
            }
            @keyframes cardFadeIn {
                to { opacity: 1; transform: translateY(0); }
            }

            .btn-purple {
                position: relative;
                overflow: hidden;
                background-color: #8A2BE2;
                border: none;
                color: #FFFFFF;
                font-size: 1.25rem;
                padding: 15px 30px;
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 0 10px;
                transition: transform 0.2s ease, background-color 0.2s ease, box-shadow 0.3s ease, opacity 0.3s ease;
                opacity: 0;
                animation: btnFadeIn 0.6s forwards;
            }
            .btn-purple:nth-child(1) { animation-delay: 0.5s; }
            .btn-purple:nth-child(2) { animation-delay: 0.7s; }

            @keyframes btnFadeIn {
                to { opacity: 1; }
            }

            /* Hover + glow effect */
            .btn-purple:hover {
                background-color: #9932CC;
                transform: translateY(-3px);
                box-shadow: 0 0 15px #DA70D6, 0 0 30px #8A2BE2, 0 0 45px #6A5ACD;
            }

            .btn-purple i {
                font-size: 1.3rem;
                transition: transform 0.2s ease;
            }
            .btn-purple:hover i {
                transform: translateY(-2px) scale(1.1);
            }

            h1 {
                color: #DA70D6;
                margin-bottom: 2rem;
                text-shadow: 0 0 10px rgba(218,112,214,0.7);
            }

            /* Ripple Effect */
            .ripple {
                position: absolute;
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                background-color: rgba(255, 255, 255, 0.4);
                pointer-events: none;
            }
            @keyframes ripple {
                to { transform: scale(4); opacity: 0; }
            }
        </style>
    </head>
    <body>

    <div class="container">
        <div class="card">
            <h1>Pilih Peran Anda</h1>
            <div class="d-flex justify-content-center">
                <a href="<?= base_url('admin/items') ?>" class="btn btn-purple">
                    <i class="bi bi-speedometer2"></i> Admin
                </a>
                <a href="<?= base_url('konsumen/pembelian') ?>" class="btn btn-purple">
                    <i class="bi bi-cart3"></i> Konsumen
                </a>    
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ripple Effect
        document.querySelectorAll('.btn-purple').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const circle = document.createElement('span');
                circle.classList.add('ripple');
                this.appendChild(circle);

                const rect = this.getBoundingClientRect();
                circle.style.left = `${e.clientX - rect.left}px`;
                circle.style.top = `${e.clientY - rect.top}px`;

                setTimeout(() => circle.remove(), 600);
            });
        });
    </script>

    </body>
    </html>
