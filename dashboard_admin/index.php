<?php
session_start();
include '../koneksi.php';

// Cek apakah user login dan merupakan admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$adminNama = $_SESSION['nama'];
$adminUsername = $_SESSION['username'];

// Sapaan waktu
date_default_timezone_set("Asia/Jakarta");
$hour = date('H');
if ($hour >= 5 && $hour < 11) {
    $greeting = "Selamat pagi";
} elseif ($hour >= 11 && $hour < 15) {
    $greeting = "Selamat siang";
} elseif ($hour >= 15 && $hour < 18) {
    $greeting = "Selamat sore";
} else {
    $greeting = "Selamat malam";
}

// Ringkasan jumlah surat (Dynamic Query)
$suratMasuk     = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE status = 'diproses'"));
$suratDisetujui = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE status = 'disetujui'"));
$suratDitolak   = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE status = 'ditolak'"));

$aktivitas = mysqli_query($conn, "
SELECT 
    p.id,
    p.jenis_surat,
    p.status,
    p.tanggal_pengajuan,

    m.nama AS nama_masyarakat,
    a.nama AS nama_admin

FROM pengajuan_surat p

LEFT JOIN masyarakat m ON p.id_masyarakat = m.id
LEFT JOIN admin a ON p.id_admin = a.id

ORDER BY p.tanggal_pengajuan DESC
LIMIT 10
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | Arsip Pekon Suka Agung Barat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-color: #1e3a8a; /* Deep Blue Konsisten */
            --accent-color: #fbbf24;  /* Amber Gold */
            --bg-light: #f3f4f6;
            --white: #ffffff;
            --text-dark: #1f2937;
            --shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        * {
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(30, 58, 138, 0.8), rgba(30, 58, 138, 0.9)), 
                        url('https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            margin: 0;
            padding: 40px 20px;
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* Opening Curtain Effect */
        #shutter {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--primary-color);
            z-index: 9999;
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1);
        }
        #shutter.v-hide { transform: scaleX(0); }

        .container {
            max-width: 1100px;
            margin: auto;
            position: relative;
            z-index: 10;
        }

        /* --- HEADER --- */
        header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 30px;
            color: var(--white);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        header p { margin: 5px 0 0; opacity: 0.8; font-size: 14px; }

        /* --- STATS CARDS --- */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 20px;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .bg-blue { background: #3b82f6; }
        .bg-green { background: #10b981; }
        .bg-red { background: #ef4444; }

        .stat-info h2 { margin: 0; font-size: 28px; color: var(--text-dark); }
        .stat-info p { margin: 0; font-size: 13px; color: #6b7280; font-weight: 600; text-transform: uppercase; }

        /* --- MENU NAVIGATION --- */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }

        .menu-btn {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 15px;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow);
        }

        .menu-btn i { font-size: 18px; }

        .menu-btn:hover {
            background: var(--accent-color);
            color: var(--primary-color);
            transform: scale(1.03);
        }

        .menu-btn.logout {
            background: #fee2e2;
            color: #b91c1c;
        }

        .menu-btn.logout:hover { background: #ef4444; color: white; }

        /* --- TABLE SECTION --- */
        .table-wrapper {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .table-header {
            padding: 20px 25px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h3 { margin: 0; font-size: 16px; color: var(--primary-color); }

        table { width: 100%; border-collapse: collapse; }
        th {
            text-align: left;
            padding: 15px 25px;
            background: #f8fafc;
            font-size: 12px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
        }

        td { padding: 15px 25px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }

        @media (max-width: 768px) {
            header { flex-direction: column; text-align: center; gap: 15px; }
            .menu-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>
</head>
<body>

<div id="shutter"></div>

<div class="container animate__animated animate__fadeIn">
    
    <header>
        <div>
            <h1><?= $greeting ?>, <?= htmlspecialchars($adminNama ?: $adminUsername) ?>!</h1>
            <p>Panel Kontrol Arsip Pekon Bumi Ayu</p>
        </div>
        <div style="font-size: 24px; opacity: 0.5;">
            <i class="fas fa-shield-halved"></i>
        </div>
    </header>

    <div class="cards-grid">
        <div class="stat-card animate__animated animate__zoomIn" style="animation-delay: 0.1s;">
            <div class="icon-box bg-blue">
                <i class="fas fa-envelope-open-text"></i>
            </div>
            <div class="stat-info">
                <h2><?= $suratMasuk ?></h2>
                <p>Permohonan Baru</p>
            </div>
        </div>
        <div class="stat-card animate__animated animate__zoomIn" style="animation-delay: 0.2s;">
            <div class="icon-box bg-green">
                <i class="fas fa-file-circle-check"></i>
            </div>
            <div class="stat-info">
                <h2><?= $suratDisetujui ?></h2>
                <p>Surat Disetujui</p>
            </div>
        </div>
        <div class="stat-card animate__animated animate__zoomIn" style="animation-delay: 0.3s;">
            <div class="icon-box bg-red">
                <i class="fas fa-file-circle-xmark"></i>
            </div>
            <div class="stat-info">
                <h2><?= $suratDitolak ?></h2>
                <p>Surat Ditolak</p>
            </div>
        </div>
    </div>

    <div class="menu-grid">
        <a href="masyarakat/masyarakat.php" class="menu-btn">
            <i class="fas fa-users"></i> Data Masyarakat
        </a>
        <a href="surat_masuk.php" class="menu-btn">
            <i class="fas fa-inbox"></i> Kelola Surat
        </a>
        <a href="template/template.php" class="menu-btn">
            <i class="fas fa-file-lines"></i> Template Arsip
        </a>
        <a href="admin_pekon.php" class="menu-btn">
            <i class="fas fa-user-gear"></i> Akun Admin
        </a>
        <a href="logout.php" class="menu-btn logout">
            <i class="fas fa-power-off"></i> Keluar
        </a>
    </div>

    <div class="table-wrapper animate__animated animate__fadeInUp">
        <div class="table-header">
            <h3><i class="fas fa-clock-rotate-left"></i> Aktivitas Terbaru</h3>
            <span style="font-size: 12px; color: #94a3b8;">Real-time update</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Nama Pemohon</th>
                    <th>Jenis Layanan</th>
                    <th>Status</th>
                    <th>Waktu Masuk</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($aktivitas)) : ?>

                <?php
                // =====================
                // AMBIL NAMA PEMOHON
                // =====================
                $nama = 'Tidak diketahui';

                if (!empty($row['nama_masyarakat'])) {
                    $nama = $row['nama_masyarakat'];
                } elseif (!empty($row['nama_admin'])) {
                    $nama = $row['nama_admin'];
                }

                // =====================
                // FORMAT WAKTU
                // =====================
                $waktu = !empty($row['tanggal_pengajuan']) 
                    ? date('d M Y, H:i', strtotime($row['tanggal_pengajuan']))
                    : '-';

                // =====================
                // STATUS BADGE
                // =====================
                $status = strtolower($row['status'] ?? '');

                if ($status == 'diproses') {
                    $badge = "badge-pending";
                    $label = "Diproses";
                } elseif ($status == 'disetujui') {
                    $badge = "badge-success";
                    $label = "Disetujui";
                } elseif ($status == 'ditolak') {
                    $badge = "badge-danger";
                    $label = "Ditolak";
                } else {
                    $badge = "badge-pending";
                    $label = "Proses";
                }
                ?>

                <tr>
                    <td><strong><?= htmlspecialchars($nama) ?></strong></td>
                    <td><?= htmlspecialchars($row['jenis_surat'] ?? '-') ?></td>
                    <td><span class="badge <?= $badge ?>"><?= $label ?></span></td>
                    <td><?= $waktu ?></td>
                </tr>

                <?php endwhile; ?>
                </tbody>
        </table>
    </div>

</div>

<script>
    // Shutter effect
    window.addEventListener('load', function() {
        const shutter = document.getElementById('shutter');
        setTimeout(() => {
            shutter.classList.add('v-hide');
        }, 300);
    });
</script>

</body>
</html>