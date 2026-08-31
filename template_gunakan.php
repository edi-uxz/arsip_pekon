<?php
session_start();
include 'koneksi.php';

// ===================
// DETEKSI ROLE
// ===================
function detect_role_from_session() {
    if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'masyarakat'])) {
        return $_SESSION['role'];
    }
    if (isset($_SESSION['id_admin']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'admin')) {
        return 'admin';
    }
    if (isset($_SESSION['id_masyarakat']) || (isset($_SESSION['role']) && $_SESSION['role'] === 'masyarakat')) {
        return 'masyarakat';
    }
    return null;
}

$role = detect_role_from_session();
if ($role === null) {
    echo "<div style='padding: 20px; font-family: sans-serif; text-align: center;'>
            <div style='color: #842029; background-color: #f8d7da; padding: 15px; border-radius: 8px; display: inline-block;'>
                Sesi login tidak valid. Silakan <a href='login.php'>Login kembali</a>.
            </div>
          </div>";
    exit;
}

// ===================
// AMBIL TEMPLATE
// ===================
$template = null;
$id_template = null;
$id_url = isset($_GET['id']) ? intval($_GET['id']) : null;

if (isset($_GET['template'])) {
    $template = basename($_GET['template']);
} elseif ($id_url !== null) {
    $id_template = $id_url;
    $q = mysqli_query($conn, "SELECT file_path FROM template_surat WHERE id = $id_template");
    $row = mysqli_fetch_assoc($q);
    if ($row && !empty($row['file_path'])) {
        $template = basename($row['file_path']);
    }
}

if (!$template) {
    die("<div style='text-align:center; margin-top:50px;'>Parameter template tidak ditemukan.</div>");
}

$templatePath = __DIR__ . "/template_surat/" . $template;
if (!file_exists($templatePath)) {
    die("<div style='text-align:center; margin-top:50px;'>File fisik <b>$template</b> tidak ditemukan di folder /template_surat/.</div>");
}

$backUrl = ($role === 'admin') ? 'dashboard_admin/template/template.php' : 'dashboard_user/ajukan_surat.php';

// ===================
// PROSES SUBMIT
// ===================
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajukan'])) {

    $jenis_surat = ucwords(str_replace(['_', '.php'], [' ', ''], $template));
    $form_data = $_POST;
    unset($form_data['ajukan']);

    $data_isi = json_encode($form_data, JSON_UNESCAPED_UNICODE);
    $keterangan = "Pengajuan Surat $jenis_surat";
    $nik = $form_data['nik'] ?? '';

    // ambil id template
    if ($id_template === null) {
        $q = mysqli_query($conn, "SELECT id FROM template_surat WHERE file_path = '" . mysqli_real_escape_string($conn, $template) . "'");
        $row = mysqli_fetch_assoc($q);
        $id_template = $row['id'] ?? 0;
    }

    /* ================= ROLE LOGIC ================= */
    if ($role === 'masyarakat') {

        $id_masyarakat = $_SESSION['id_masyarakat'] ?? null;

        $stmt = $conn->prepare("
            INSERT INTO pengajuan_surat 
            (jenis_surat, nik, id_masyarakat, id_admin, id_template, tanggal_pengajuan, status, keterangan, data_isi, is_printed)
            VALUES (?, ?, ?, NULL, ?, CURDATE(), 'diproses', ?, ?, 0)
        ");

        $stmt->bind_param("ssisss",
            $jenis_surat,
            $nik,
            $id_masyarakat,
            $id_template,
            $keterangan,
            $data_isi
        );

    } else { // ADMIN

        $id_admin = $_SESSION['id_admin'] ?? null;

        $stmt = $conn->prepare("
            INSERT INTO pengajuan_surat 
            (jenis_surat, nik, id_masyarakat, id_admin, id_template, tanggal_pengajuan, status, keterangan, data_isi, is_printed)
            VALUES (?, NULL, NULL, ?, ?, CURDATE(), 'diproses', ?, ?, 0)
        ");

        $stmt->bind_param("siiss",
            $jenis_surat,
            $id_admin,
            $id_template,
            $keterangan,
            $data_isi
        );
    }

    if ($stmt->execute()) {
        $msg = "success";
    } else {
        $msg = "error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <title>Formulir Pengajuan Surat | Sistem Arsip</title>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    
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
        }

        body { 
            background: linear-gradient(rgba(30, 58, 138, 0.9), rgba(30, 58, 138, 0.95)), 
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif; 
            color: var(--text-dark);
            min-height: 100vh;
            padding: 40px 15px;
        }

        .container-form { max-width: 800px; margin: auto; }

        /* --- HEADER --- */
        .page-header h4 {
            font-weight: 700;
            color: var(--white);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 10px 20px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: var(--accent-color);
            color: var(--primary-color);
        }

        /* --- CARD --- */
        .card-form {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: none;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            padding: 40px;
        }

        .form-info-box {
            background: #f1f5f9;
            padding: 18px;
            border-radius: 15px;
            margin-bottom: 30px;
            border-left: 6px solid var(--accent-color);
            color: var(--primary-color);
            font-weight: 500;
        }

        /* --- DINAMIS STYLING UNTUK INPUT --- */
        .card-form input, .card-form select, .card-form textarea {
            width: 100%;
            padding: 14px 18px;
            margin-top: 8px;
            margin-bottom: 22px;
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .card-form label {
            font-weight: 600;
            font-size: 13px;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .card-form input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        /* --- BUTTON SUBMIT --- */
        .btn-submit {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 15px;
            font-weight: 700;
            font-size: 16px;
            width: 100%;
            box-shadow: 0 10px 20px rgba(30, 58, 138, 0.2);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #1e40af;
            transform: translateY(-3px);
            box-shadow: 0 15px 25px rgba(30, 58, 138, 0.3);
        }
    </style>
</head>
<body>

<div class="container container-form">
    
    <div class="page-header d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeInDown">
        <h4><i class="fas fa-file-signature text-warning"></i> Formulir Pengajuan</h4>
        <a href="<?= htmlspecialchars($backUrl) ?>" class="btn-back">
            <i class="fas fa-chevron-left me-2"></i> Kembali
        </a>
    </div>

    <?php if ($msg === "success"): ?>
        <div class="card-form text-center animate__animated animate__zoomIn">
            <div class="mb-4">
                <i class="fas fa-check-circle fa-5x text-success"></i>
            </div>
            <h3 class="fw-bold text-dark">Berhasil Terkirim!</h3>
            <p class="text-muted">Pengajuan Anda sedang diproses oleh admin. Silakan cek status secara berkala di dashboard.</p>
            <a href="<?= $backUrl ?>" class="btn btn-submit mt-3">Ke Dashboard Saya</a>
        </div>
        <?php exit; ?>
    <?php endif; ?>

    <div class="card-form animate__animated animate__zoomIn">
        <div class="form-info-box animate__animated animate__fadeIn animate__delay-1s">
            <i class="fas fa-info-circle me-2"></i> Anda sedang mengisi: <strong><?= htmlspecialchars(str_replace('.php', '', $template)) ?></strong>
        </div>

        <form method="POST" action="" class="animate__animated animate__fadeInUp animate__delay-1s">
            <?php 
                $currentCwd = getcwd();
                $tplDir = dirname($templatePath);
                chdir($tplDir);

                include basename($templatePath);

                chdir($currentCwd);
            ?>
            
            <div class="mt-4 pt-4 border-top">
                <!-- <button type="submit" name="ajukan" class="btn-submit">
                    <i class="fas fa-paper-plane me-2"></i> Kirim Pengajuan Sekarang
                </button> -->
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>