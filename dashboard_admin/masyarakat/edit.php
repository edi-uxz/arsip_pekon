<?php
session_start();
require_once '../../koneksi.php';

// Cek khusus admin saja
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: masyarakat.php");
    exit;
}

$query = mysqli_query($conn, "SELECT * FROM masyarakat WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: masyarakat.php?pesan=data_tidak_ditemukan");
    exit;
}

if (isset($_POST['submit'])) {
    $nik = mysqli_real_escape_string($conn, $_POST['nik']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);

    $update = mysqli_query($conn, "UPDATE masyarakat SET nik='$nik', nama='$nama' WHERE id='$id'");

    if ($update) {
        header("Location: masyarakat.php?pesan=update_berhasil");
        exit;
    } else {
        $error = "Gagal memperbarui data.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Masyarakat | Arsip Suka Agung Barat</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        :root {
            --primary-color: #1e3a8a;
            --accent-color: #fbbf24;
            --text-dark: #1f2937;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(rgba(30, 58, 138, 0.85), rgba(30, 58, 138, 0.95)), 
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
            overflow: hidden; /* Mencegah scroll saat animasi PPT berjalan */
        }

        .edit-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            width: 100%;
            max-width: 500px;
            padding: 40px;
            position: relative;
        }

        .header-icon {
            width: 70px;
            height: 70px;
            background: var(--primary-color);
            color: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: -75px auto 20px auto;
            border: 5px solid var(--white);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        h4 {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        /* Tombol PPT Style */
        .btn-save {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            background: #1e40af;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(30, 58, 138, 0.3);
        }

        .btn-back {
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: var(--text-dark);
        }

        /* PPT Slide-In Delays */
        .delay-1 { animation-delay: 0.3s; }
        .delay-2 { animation-delay: 0.5s; }
        .delay-3 { animation-delay: 0.7s; }
        .delay-4 { animation-delay: 0.9s; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="edit-card animate__animated animate__zoomIn">
        
        <div class="header-icon animate__animated animate__bounceInDown animate__delay-1s">
            <i class="fas fa-user-edit"></i>
        </div>

        <h4 class="text-center animate__animated animate__fadeInDown delay-1">Update Data Warga</h4>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger animate__animated animate__shakeX"><?= $error ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3 animate__animated animate__fadeInRight delay-2">
                <label for="nik" class="form-label">Nomor Induk Kependudukan (NIK)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-id-card"></i></span>
                    <input type="text" class="form-control" id="nik" name="nik" 
                           value="<?= htmlspecialchars($data['nik']); ?>" required>
                </div>
            </div>

            <div class="mb-4 animate__animated animate__fadeInRight delay-3">
                <label for="nama" class="form-label">Nama Lengkap Sesuai KTP</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" id="nama" name="nama" 
                           value="<?= htmlspecialchars($data['nama']); ?>" required>
                </div>
            </div>

            <div class="d-grid gap-2 animate__animated animate__fadeInUp delay-4">
                <button type="submit" name="submit" class="btn btn-save">
                    <i class="fas fa-check-circle me-2"></i> Perbarui Data
                </button>
                <a href="masyarakat.php" class="btn btn-back">
                    <i class="fas fa-arrow-left me-2"></i> Batalkan
                </a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>