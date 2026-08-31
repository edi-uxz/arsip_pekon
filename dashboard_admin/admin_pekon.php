<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id_admin = $_SESSION['id'];
$stmt = $conn->prepare("SELECT * FROM admin WHERE id = ?");
$stmt->bind_param("i", $id_admin);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>Data tidak ditemukan.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Admin | Sistem Informasi Pekon</title>
    
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

        body {
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .profile-container {
            width: 100%;
            max-width: 550px; /* Diperkecil agar lebih ramping */
        }

        .profile-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        /* --- HEADER PROFILE --- */
        .profile-header {
            background: var(--primary);
            padding: 30px 20px; /* Padding dikurangi */
            text-align: center;
            position: relative;
            border-bottom: 4px solid var(--accent);
        }

        .avatar-wrapper img {
            width: 90px; /* Ukuran foto diperkecil */
            height: 90px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            background: white;
            margin-bottom: 10px;
        }

        .profile-header h4 {
            color: white;
            font-weight: 700;
            margin: 0;
            font-size: 1.2rem;
        }

        .badge-role {
            background: var(--accent);
            color: var(--primary);
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 10px;
            text-transform: uppercase;
        }

        /* --- INFO BODY --- */
        .info-grid {
            padding: 25px; /* Padding dikurangi agar hemat ruang */
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-radius: 12px;
            background: #f8fafc;
            margin-bottom: 12px;
            border: 1px solid #edf2f7;
            transition: all 0.2s;
        }

        .info-item:hover {
            transform: translateX(5px);
            background: #fff;
            border-color: var(--primary);
        }

        .info-icon {
            width: 38px;
            height: 38px;
            background: var(--primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--accent);
            font-size: 16px;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .info-label {
            display: block;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 700;
            color: #94a3b8;
        }

        .info-value {
            font-weight: 600;
            font-size: 14px;
            color: var(--primary);
            word-break: break-word;
        }

        /* --- BUTTONS --- */
        .btn-action {
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            transition: 0.3s;
        }

        .btn-edit {
            background: var(--primary);
            color: white;
            border: none;
            width: 100%;
        }

        .btn-dashboard {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
            width: 100%;
        }

        .btn-edit:hover { background: #1e40af; color: white; }
    </style>
</head>
<body>

<div class="profile-container">
    <div class="profile-card animate__animated animate__zoomIn">
        <div class="profile-header">
            <div class="avatar-wrapper animate__animated animate__bounceIn">
                <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Avatar" class="rounded-circle shadow">
            </div>
            <h4><?= htmlspecialchars($data['nama']) ?></h4>
            <span class="badge-role">Administrator</span>
        </div>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-icon"><i class="fas fa-id-card"></i></div>
                <div>
                    <span class="info-label">NIK Admin</span>
                    <span class="info-value"><?= htmlspecialchars($data['nip']) ?></span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-at"></i></div>
                <div>
                    <span class="info-label">Username</span>
                    <span class="info-value"><?= htmlspecialchars($data['username']) ?></span>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <span class="info-label">WhatsApp</span>
                    <span class="info-value"><?= htmlspecialchars($data['no_hp']) ?></span>
                </div>
            </div>

            <div class="info-item mb-0">
                <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <span class="info-label">Alamat</span>
                    <span class="info-value"><?= htmlspecialchars($data['alamat']) ?></span>
                </div>
            </div>

            <div class="row g-2 mt-4">
                <div class="col-4">
                    <a href="index.php" class="btn btn-dashboard btn-action">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
                <div class="col-8">
                    <a href="edit_profil.php" class="btn btn-edit btn-action">
                        <i class="fas fa-user-edit me-1"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>