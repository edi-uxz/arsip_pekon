<?php
// index.php
// Halaman utama sebelum login
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Informasi Pelayanan Surat Desa | Desa Bumi Ayu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #1e3a8a; /* Deep Blue */
            --accent-color: #fbbf24;  /* Amber/Gold */
            --bg-light: #f3f4f6;
            --text-dark: #1f2937;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* --- PRELOADER / OPENING CURTAIN --- */
        #shutter {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--primary-color);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.8s cubic-bezier(0.77, 0, 0.175, 1);
        }
        #shutter.v-hide { transform: translateY(-100%); }

        /* --- HERO SECTION --- */
        .hero {
            background: linear-gradient(rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.8)), 
                        url('https://images.unsplash.com/photo-1516001511917-f504ed8149af?auto=format&fit=crop&q=80&w=1920') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 120px 20px;
            clip-path: polygon(0 0, 100% 0, 100% 90%, 0% 100%);
        }

        .hero h1 {
            font-weight: 700;
            font-size: 3rem;
            letter-spacing: -1px;
        }

        /* --- CARDS & INTERACTION --- */
        .icon-box {
            border: none;
            border-radius: 20px;
            background: white;
            padding: 35px;
            transition: var(--transition);
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .icon-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 4px; height: 100%;
            background: var(--accent-color);
            transition: var(--transition);
        }

        /* Hover Effect: Lift up and glow */
        .icon-box:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .icon-box:hover::before {
            width: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        /* --- FLOW SECTION (Steps) --- */
        .step-number {
            width: 50px; height: 50px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-weight: bold;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* --- BUTTONS --- */
        .btn-custom {
            padding: 12px 35px;
            border-radius: 50px;
            font-weight: 600;
            transition: var(--transition);
        }
        .btn-light:hover {
            background: var(--accent-color);
            border-color: var(--accent-color);
            color: white;
        }

        /* --- FOOTER --- */
        footer {
            background: var(--primary-color);
            color: rgba(255,255,255,0.8);
            padding: 50px 0;
            margin-top: 50px;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            margin: 15px auto;
            border-radius: 2px;
        }
    </style>
</head>
<body>

<div id="shutter">
    <div class="text-center text-white">
        <div class="spinner-border mb-3" role="status"></div>
        <h4 class="animate__animated animate__fadeInUp">Desa Bumi Ayu</h4>
    </div>
</div>

<section class="hero text-center animate__animated animate__fadeIn">
    <div class="container">
        <h1 class="animate__animated animate__zoomIn">Sistem Informasi Administrasi <br> Pelayanan Surat Desa</h1>
        <p class="lead mt-3 opacity-75">
            Pelayanan Mandiri, Transparan, dan Modern untuk Masyarakat
        </p>

        <div class="mt-5">
            <a href="login.php" class="btn btn-light btn-lg btn-custom me-2 shadow-sm">Login</a>
            <!-- <a href="daftar.php" class="btn btn-outline-light btn-lg btn-custom shadow-sm">Register</a> -->
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h3 class="section-title">Tentang Sistem</h3>
                <p class="text-muted fs-5">
                    Dirancang untuk mendigitalisasi birokrasi desa, memberikan efisiensi waktu baik bagi warga maupun perangkat desa Bumi Ayu.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="icon-box text-center">
                    <div class="mb-3 text-primary"><i class="bi bi-speedometer2 fs-1"></i></div>
                    <h5>Pelayanan Cepat</h5>
                    <p class="text-muted small">Proses pengajuan hingga selesai kini hanya dalam hitungan menit, bukan hari.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="icon-box text-center">
                    <h5>Data Aman</h5>
                    <p class="text-muted small">Seluruh arsip kependudukan terlindungi dengan sistem enkripsi database terbaru.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="icon-box text-center">
                    <h5>Efisiensi Kerja</h5>
                    <p class="text-muted small">Otomatisasi pembuatan surat mengurangi beban kerja administratif staf desa.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white">
    <div class="container">
        <h3 class="section-title text-center">Alur Pelayanan</h3>
        <div class="row g-4 text-center mt-4">
            <?php 
            $steps = ["Login", "Ajukan Surat", "Verifikasi", "Persetujuan", "Selesai"];
            foreach($steps as $index => $step): ?>
            <div class="col-md">
                <div class="step-number"><?php echo $index + 1; ?></div>
                <h6 class="fw-bold"><?php echo $step; ?></h6>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h3 class="section-title text-center">Hak Akses</h3>
        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <div class="icon-box">
                    <h5 class="fw-bold text-primary">Masyarakat</h5>
                    <hr>
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Akses mudah via NIK</li>
                        <li class="mb-2">✓ Pengajuan surat mandiri</li>
                        <li class="mb-2">✓ Pantau status secara Real-time</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="icon-box">
                    <h5 class="fw-bold text-primary">Admin Desa</h5>
                    <hr>
                    <ul class="list-unstyled">
                        <li class="mb-2">✓ Manajemen data penduduk</li>
                        <li class="mb-2">✓ Validasi dokumen digital</li>
                        <li class="mb-2">✓ Laporan administrasi otomatis</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="text-center">
    <div class="container">
        <p class="mb-1 fw-bold text-white">&copy; <?php echo date('Y'); ?> Desa Bumi Ayu</p>
        <p class="small opacity-50">Kecamatan Pringsewu, Kabupaten Pringsewu, Lampung</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Curtain Opening Animation
    window.addEventListener('load', function() {
        const shutter = document.getElementById('shutter');
        setTimeout(() => {
            shutter.classList.add('v-hide');
        }, 1000); // 1 detik loading
    });
</script>
</body>
</html>