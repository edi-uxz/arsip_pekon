<?php
session_start();
include '../koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['id_masyarakat'])) {
    header("Location: ../login.php");
    exit;
}

$id_masyarakat = intval($_SESSION['id_masyarakat']);

// Ambil daftar template surat dari database
$templateQuery = mysqli_query($conn, "SELECT * FROM template_surat ORDER BY nama_template ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jenis Surat | Sistem Digital</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary: #1e3a8a;
            --accent: #fbbf24;
            --bg-overlay: rgba(15, 23, 42, 0.92);
        }

        body {
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            padding: 40px 0;
            overflow-x: hidden;
        }

        .container-custom {
            max-width: 1000px;
            margin: auto;
            padding: 0 20px;
        }

        /* --- HEADER PPT STYLE --- */
        .header-section {
            text-align: center;
            margin-bottom: 50px;
        }

        .header-title {
            font-weight: 700;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        /* --- CARD TEMPLATE --- */
        .card-template {
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.98);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            height: 100%;
            border-bottom: 4px solid transparent;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .card-template:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            border-color: var(--accent);
        }

        .card-body-custom {
            padding: 35px 25px;
            text-align: center;
            color: #1e293b;
        }

        .icon-box {
            width: 75px;
            height: 75px;
            background: #f0f4ff;
            color: var(--primary);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            transition: 0.3s;
        }

        .card-template:hover .icon-box {
            background: var(--primary);
            color: var(--accent);
        }

        .template-name {
            font-weight: 700;
            font-size: 17px;
            color: var(--primary);
            min-height: 50px;
            margin-bottom: 15px;
        }

        .btn-choose {
            background: var(--primary);
            color: white;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            border: none;
        }

        .btn-choose:hover {
            background: #1e40af;
            color: var(--accent);
        }

        .btn-back {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            padding: 8px 18px;
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            transition: 0.3s;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .btn-back:hover {
            background: var(--accent);
            color: var(--primary);
        }

        .badge-template {
            font-size: 10px;
            font-weight: 700;
            color: #64748b;
            background: #f1f5f9;
            padding: 4px 12px;
            border-radius: 50px;
            margin-bottom: 15px;
            display: inline-block;
        }
    </style>
</head>
<body>

<div class="container-custom">
    <div class="animate__animated animate__fadeInLeft">
        <a href="index.php" class="btn-back">
            <i class="fas fa-chevron-left"></i> Dashboard Utama
        </a>
    </div>

    <div class="header-section animate__animated animate__fadeInDown">
        <h2 class="header-title">Layanan Surat Digital</h2>
        <p class="opacity-75">Pilih salah satu template di bawah untuk memulai pengajuan.</p>
    </div>

    <div class="row g-4">
        <?php 
        $i = 0;
        while ($row = mysqli_fetch_assoc($templateQuery)) : 
            $i++;
            // Membuat delay bertahap agar muncul satu per satu seperti slide PPT
            $delay_class = "animate__delay-" . ($i <= 5 ? $i : 1) . "s"; 
        ?>
            <div class="col-lg-4 col-md-6 animate__animated animate__fadeInUp <?= $delay_class ?>">
                <div class="card-template">
                    <div class="card-body-custom">
                        <span class="badge-template">PROSES OTOMATIS</span>
                        <div class="icon-box">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5 class="template-name"><?= htmlspecialchars($row['nama_template'] ?? '') ?></h5>
                        <p class="text-muted small mb-4">Lengkapi formulir singkat untuk mencetak surat ini secara instan.</p>
                        <a href="../template_gunakan.php?id=<?= $row['id'] ?>" class="btn-choose">
                            Gunakan Template <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>

        <?php if (mysqli_num_rows($templateQuery) == 0) : ?>
            <div class="col-12 text-center py-5 animate__animated animate__fadeInUp">
                <i class="fas fa-folder-open fa-3x text-white-50 mb-3"></i>
                <p class="text-white-50">Belum ada layanan surat yang tersedia saat ini.</p>
            </div>
        <?php endif; ?>
    </div>

    <footer class="text-center mt-5 pt-4 animate__animated animate__fadeIn">
        <p class="text-white-50 small">&copy; <?= date('Y') ?> Pemerintah Pekon Sukorejo</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>