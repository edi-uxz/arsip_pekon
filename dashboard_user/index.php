<?php
session_start();
include '../koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['id_masyarakat'])) {
    header("Location: ../login.php");
    exit;
}

$id_masyarakat = intval($_SESSION['id_masyarakat']);

// Ambil data masyarakat
$query = mysqli_query($conn, "SELECT * FROM masyarakat WHERE id = '$id_masyarakat'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    session_destroy();
    header("Location: ../login.php");
    exit;
}

// Ambil jumlah surat
$q_jumlah = mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_surat WHERE id_masyarakat = '$id_masyarakat'");
$jumlah = mysqli_fetch_assoc($q_jumlah);

// Logika sapaan
$jam = date("H");
if ($jam >= 5 && $jam <= 10) {
    $salam = "Selamat pagi";
    $icon_time = "fa-sun";
} elseif ($jam > 10 && $jam <= 14) {
    $salam = "Selamat siang";
    $icon_time = "fa-cloud-sun";
} elseif ($jam > 14 && $jam <= 18) {
    $salam = "Selamat sore";
    $icon_time = "fa-cloud-moon";
} else {
    $salam = "Selamat malam";
    $icon_time = "fa-moon";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Masyarakat | Sistem Digital</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary: #1e3a8a;
            --accent: #fbbf24;
            --bg-overlay: rgba(15, 23, 42, 0.9);
        }

        /* --- EFEK TIRAI --- */
        .curtain {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            z-index: 9999;
            display: flex;
        }
        .curtain-panel {
            flex: 1;
            background: var(--primary);
            transition: transform 1s cubic-bezier(0.7, 0, 0.3, 1);
        }
        .curtain-left { transform: translateX(0); border-right: 2px solid var(--accent); }
        .curtain-right { transform: translateX(0); border-left: 2px solid var(--accent); }
        
        body.loaded .curtain-left { transform: translateX(-100%); }
        body.loaded .curtain-right { transform: translateX(100%); }
        body.loaded .curtain { visibility: hidden; pointer-events: none; }

        body {
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            padding: 40px 0;
            overflow-x: hidden;
        }

        .container-custom {
            max-width: 600px;
            margin: auto;
            padding: 0 20px;
        }

        /* --- HERO GREETING --- */
        .hero-box {
            background: var(--primary);
            border-radius: 25px;
            padding: 30px;
            border-bottom: 5px solid var(--accent);
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }

        .hero-box h3 { font-weight: 700; margin: 5px 0; color: var(--accent); }

        /* --- CARD STYLE --- */
        .card-profile {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 25px;
            overflow: hidden;
            color: #1e293b;
        }

        .card-header-custom {
            background: #f8fafc;
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
            color: var(--primary);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* --- INFO ROW --- */
        .info-group { padding: 10px 25px; }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-row:last-child { border: none; }
        .info-label { color: #64748b; font-size: 12px; font-weight: 600; }
        .info-value { font-weight: 600; color: var(--primary); font-size: 14px; }

        /* --- STATS BOX --- */
        .stats-section {
            background: #f0f4ff;
            border-radius: 15px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            margin: 15px 25px;
            border: 1px solid #dbeafe;
        }
        .stats-icon {
            width: 45px; height: 45px;
            background: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--accent);
        }

        /* --- BUTTONS --- */
        .action-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 15px; padding: 0 25px 25px;
        }
        .btn-modern {
            padding: 12px; border-radius: 12px;
            font-weight: 700; font-size: 13px;
            transition: 0.3s; text-decoration: none;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-primary-custom { background: var(--primary); color: #fff; }
        .btn-primary-custom:hover { background: #1e40af; color: #fff; transform: translateY(-3px); }
        .btn-outline-custom { background: #fff; color: #64748b; border: 1px solid #e2e8f0; }
        .btn-outline-custom:hover { border-color: var(--primary); color: var(--primary); }

        .btn-logout { color: var(--accent); font-weight: 600; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>

<div class="curtain">
    <div class="curtain-panel curtain-left"></div>
    <div class="curtain-panel curtain-right"></div>
</div>

<div class="container-custom">
    <div class="hero-box animate__animated animate__fadeInDown animate__delay-1s">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="mb-0 opacity-75 small"><i class="fas <?= $icon_time ?> me-1"></i> <?= $salam ?>,</p>
                <h3><?= htmlspecialchars($data['nama'] ?? 'Warga') ?> 👋</h3>
                <span class="badge bg-warning text-dark rounded-pill px-3" style="font-size: 10px;">WARGA DESA SUKOREJO</span>
            </div>
            <i class="fas fa-id-badge fa-3x opacity-25"></i>
        </div>
    </div>

    <div class="card-profile animate__animated animate__zoomIn animate__delay-1s">
        <div class="card-header-custom">
            <i class="fas fa-fingerprint me-2 text-warning"></i> Data Identitas
        </div>
        <div class="info-group">
            <div class="info-row">
                <span class="info-label">NIK</span>
                <span class="info-value"><?= htmlspecialchars($data['nik'] ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">WhatsApp</span>
                <span class="info-value"><?= htmlspecialchars($data['no_hp'] ?? '-') ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat</span>
                <span class="info-value text-end"><?= htmlspecialchars($data['alamat'] ?? '-') ?></span>
            </div>
        </div>
    </div>

    <div class="card-profile animate__animated animate__zoomIn animate__delay-1s">
        <div class="card-header-custom">
            <i class="fas fa-tasks me-2 text-warning"></i> Layanan Surat
        </div>
        
        <div class="stats-section">
            <div class="stats-icon"><i class="fas fa-file-alt"></i></div>
            <div>
                <h4 class="mb-0 fw-bold text-primary"><?= $jumlah['total'] ?? 0 ?></h4>
                <p class="mb-0 text-muted small">Total Pengajuan Anda</p>
            </div>
        </div>

        <div class="action-grid">
            <a href="ajukan_surat.php" class="btn-modern btn-primary-custom">
                <i class="fas fa-paper-plane"></i> Ajukan
            </a>
            <a href="riwayat_surat.php" class="btn-modern btn-outline-custom">
                <i class="fas fa-history"></i> Riwayat
            </a>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 px-2 animate__animated animate__fadeIn animate__delay-2s">
        <a href="logout.php" class="btn-logout" onclick="return confirm('Keluar dari aplikasi?')">
            <i class="fas fa-power-off me-1"></i> Logout
        </a>
        <span class="text-white-50 small">&copy; <?= date('Y') ?> Desa Sukorejo</span>
    </div>
</div>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.body.classList.add('loaded');
        }, 300); // Tirai terbuka setelah 300ms
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>