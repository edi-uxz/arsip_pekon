<?php
session_start();
require_once '../../koneksi.php';

// Validasi hanya untuk admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: /arsip/dashboard_admin/login.php");
    exit;
}

$sql = "SELECT * FROM masyarakat ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Masyarakat | Arsip Pekon Suka Agung Barat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #1e3a8a; 
            --accent-color: #fbbf24;  
            --white: #ffffff;
            --text-dark: #1f2937;
            --shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(30, 58, 138, 0.85), rgba(30, 58, 138, 0.95)), 
                        url('https://images.unsplash.com/photo-1577412647305-991150c7d163?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            padding: 40px 15px;
            overflow-x: hidden;
        }

        .container-main {
            max-width: 1100px;
            margin: auto;
        }

        /* Card Utama dengan efek Glassmorphism */
        .card-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Animasi Header */
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 35px;
            gap: 20px;
        }

        h1 {
            font-size: 26px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .btn-action-top {
            background-color: var(--primary-color);
            color: white;
            padding: 12px 22px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
        }

        .btn-action-top:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
            color: white;
        }

        /* Tabel & Baris yang bisa beranimasi */
        .table-responsive {
            border-radius: 18px;
            background: white;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .table thead { background-color: var(--primary-color); color: white; }
        .table thead th { padding: 18px; text-transform: uppercase; font-size: 13px; letter-spacing: 1px; border: none; }
        
        /* Baris Tabel Tersembunyi sebelum animasi jalan */
        .staggered-row {
            opacity: 0;
        }

        .table td { padding: 18px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

        .badge-custom {
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .bg-aktif { background-color: #d1fae5; color: #065f46; }
        .bg-blokir { background-color: #fee2e2; color: #991b1b; }

        .btn-icon {
            width: 38px; height: 38px;
            display: inline-flex;
            align-items: center; justify-content: center;
            border-radius: 10px; color: white; margin: 0 3px;
            transition: transform 0.2s;
        }
        .btn-icon:hover { transform: scale(1.2); }
        .btn-edit { background-color: #10b981; }
        .btn-lock { background-color: #f59e0b; }
        .btn-unlock { background-color: var(--primary-color); }
    </style>
</head>
<body>

<div class="container-main">
    <div class="card-custom animate__animated animate__zoomIn">
        
        <div class="header-section animate__animated animate__fadeInDown animate__delay-1s">
            <h1><i class="fas fa-id-badge"></i> Data Masyarakat</h1>
            <div class="d-flex gap-2">
                <a href="../index.php" class="btn-action-top" style="background: #f1f5f9; color: var(--primary-color);">
                    <i class="fas fa-chevron-left"></i> Panel Admin
                </a>
                <a href="../../daftar.php" class="btn-action-top">
                    <i class="fas fa-plus-circle"></i> Tambah Warga
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="animate__animated animate__fadeIn">
                    <tr class="text-center">
                        <th width="80">No</th>
                        <th>Identitas (NIK)</th>
                        <th class="text-start">Nama Lengkap</th>
                        <th>Status Akun</th>
                        <th width="180">Kelola</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php 
                    $no = 1; 
                    $delay = 0.2; // Delay awal untuk baris pertama
                    while ($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <tr class="text-center staggered-row animate__animated animate__fadeInUp" 
                            style="animation-delay: <?= $delay; ?>s; opacity: 1;">
                            
                            <td><span class="text-muted fw-bold"><?= $no++; ?></span></td>
                            <td><code class="fw-bold" style="color: var(--primary-color);"><?= htmlspecialchars($row['nik']); ?></code></td>
                            <td class="text-start">
                                <div class="fw-bold">
                                    <?= htmlspecialchars($row['nama'] ?? ''); ?>
                                </div>
                                <small class="text-muted">
                                    <?= htmlspecialchars($row['alamat'] ?? '-'); ?>
                                </small>
                            </td>
                            <td>
                                <span class="badge-custom <?= $row['status'] == 'blokir' ? 'bg-blokir' : 'bg-aktif' ?>">
                                    <?= $row['status'] == 'blokir' ? 'Terblokir' : 'Aktif'; ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a class="btn-icon btn-edit" href="edit.php?id=<?= $row['id']; ?>"><i class="fas fa-user-pen"></i></a>
                                    <a class="btn-icon <?= $row['status'] == 'blokir' ? 'btn-unlock' : 'btn-lock' ?>" 
                                       href="blokir.php?id=<?= $row['id']; ?>&aksi=<?= $row['status'] == 'blokir' ? 'buka' : 'blokir' ?>">
                                       <i class="fas <?= $row['status'] == 'blokir' ? 'fa-key' : 'fa-ban' ?>"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        $delay += 0.1; // Menambah delay 0.1 detik untuk baris berikutnya
                    endwhile; 
                    ?>
                <?php else: ?>
                    <tr class="animate__animated animate__fadeIn">
                        <td colspan="5" class="py-5 text-center text-muted">Belum ada data.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>