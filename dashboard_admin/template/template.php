<?php
session_start();
include '../../koneksi.php';

// Validasi hanya untuk admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../dashboard_admin/login.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM template_surat ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Template Surat | Panel Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #1e3a8a; /* Deep Blue */
            --accent-color: #fbbf24;  /* Amber Gold */
            --white: #ffffff;
            --text-dark: #1e293b;
            --shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(30, 58, 138, 0.85), rgba(30, 58, 138, 0.95)), 
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 40px 15px;
        }

        .container-main {
            max-width: 1100px;
            margin: auto;
        }

        /* --- CARD PPT STYLE --- */
        .card-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* --- HEADER --- */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .header-section h3 {
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-back {
            background-color: #f1f5f9;
            color: var(--primary-color);
            border-radius: 12px;
            padding: 10px 22px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateX(-5px);
        }

        /* --- TABLE --- */
        .table-responsive {
            border-radius: 18px;
            overflow: hidden;
            background: white;
            border: 1px solid #e5e7eb;
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
            padding: 20px;
            border: none;
        }

        .staggered-row { opacity: 0; }

        .table td {
            padding: 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        /* --- BADGES & BUTTONS --- */
        .file-badge {
            background-color: #f8fafc;
            color: #475569;
            padding: 8px 14px;
            border-radius: 10px;
            font-family: 'Consolas', monospace;
            font-size: 13px;
            border: 1px solid #e2e8f0;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gunakan {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-gunakan:hover {
            background-color: #1e40af;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.2);
            color: white;
        }

        .empty-state {
            padding: 60px 0;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="container-main">
    <div class="card-custom animate__animated animate__zoomIn">
        
        <div class="header-section animate__animated animate__fadeInDown animate__delay-1s">
            <h3>
                <i class="fas fa-layer-group text-warning"></i> Manajemen Template
            </h3>
            <a href="../index.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr class="text-center">
                        <th width="80">No</th>
                        <th class="text-start">Nama Template Surat</th>
                        <th class="text-start">File Konfigurasi</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    $delay = 0.5; // Delay awal baris pertama
                    while ($row = mysqli_fetch_assoc($query)): 
                    ?>
                    <tr class="staggered-row animate__animated animate__fadeInRight" 
                        style="animation-delay: <?= $delay; ?>s; opacity: 1;">
                        
                        <td class="text-center fw-bold text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 16px;"><?= htmlspecialchars($row['nama_template']) ?></div>
                            <small class="text-muted"><i class="fas fa-barcode me-1"></i> ID: #<?= $row['id'] ?></small>
                        </td>
                        <td>
                            <span class="file-badge">
                                <i class="fas fa-file-code text-primary"></i>
                                <?= htmlspecialchars($row['file_path'] ?? 'belum_diatur.php') ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="../../template_gunakan.php?template=<?= urlencode($row['file_path']) ?>" 
                               class="btn btn-gunakan">
                                <i class="fas fa-file-signature"></i> Gunakan
                            </a>
                        </td>
                    </tr>
                    <?php 
                        $delay += 0.1; // Tambahan delay per baris (efek mengalir)
                    endwhile; 
                    ?>
                    
                    <?php if ($no == 1): ?>
                    <tr>
                        <td colspan="4">
                            <div class="empty-state animate__animated animate__fadeIn">
                                <img src="https://illustrations.popsy.co/gray/empty-folder.svg" alt="Empty" style="width: 120px; opacity: 0.5; margin-bottom: 20px;">
                                <h5 class="text-dark fw-bold">Belum Ada Template</h5>
                                <p class="text-muted">Silakan hubungi tim IT untuk registrasi template surat baru.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>