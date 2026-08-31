<?php
session_start();
include '../koneksi.php';

// ✅ Validasi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$id_admin = $_SESSION['id'];
$msg = "";

// --- PROSES UPDATE ---
if (isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $nik      = mysqli_real_escape_string($conn, $_POST['nik']);
    $no_hp    = mysqli_real_escape_string($conn, $_POST['no_hp']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);

    $sql = "UPDATE admin SET nama='$nama', username='$username', nik='$nik', no_hp='$no_hp', alamat='$alamat' WHERE id=$id_admin";
    
    if (mysqli_query($conn, $sql)) {
        $msg = "success";
    } else {
        $msg = "error";
    }
}

// --- AMBIL DATA TERBARU ---
$query = mysqli_query($conn, "SELECT * FROM admin WHERE id = $id_admin");
$data  = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil Admin | Sistem Informasi Pekon</title>
    
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

        .edit-container {
            width: 100%;
            max-width: 550px;
        }

        .card-edit {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
        }

        .card-header-custom {
            background: var(--primary);
            padding: 25px;
            text-align: center;
            color: white;
            border-bottom: 4px solid var(--accent);
        }

        .card-header-custom h5 {
            font-weight: 700;
            margin: 0;
            letter-spacing: 1px;
        }

        .form-body {
            padding: 30px;
        }

        /* --- STYLING INPUT --- */
        .input-group-text {
            background: #f8fafc;
            border-right: none;
            color: var(--primary);
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            border-left: none;
            border-radius: 0 12px 12px 0;
            padding: 12px;
            font-size: 14px;
            background: #f8fafc;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #dee2e6;
            background: #fff;
        }

        label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 5px;
            display: block;
        }

        /* --- BUTTONS --- */
        .btn-save {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-save:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3);
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #64748b;
            text-decoration: none;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            display: block;
            text-align: center;
            font-size: 14px;
        }

        .btn-cancel:hover { background: #e2e8f0; color: #1e293b; }
    </style>
</head>
<body>

<div class="edit-container">
    <div class="card-edit animate__animated animate__fadeInUp">
        <div class="card-header-custom">
            <i class="fas fa-user-cog fa-2x mb-2 text-warning"></i>
            <h5>PENGATURAN PROFIL</h5>
        </div>

        <div class="form-body">
            <?php if ($msg === "success"): ?>
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate__animated animate__fadeIn">
                    <i class="fas fa-check-circle me-2"></i> Profil berhasil diperbarui!
                </div>
            <?php elseif ($msg === "error"): ?>
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
                    <i class="fas fa-times-circle me-2"></i> Gagal memperbarui profil.
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-3">
                    <label>Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['nama'] ?? "") ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>NIK</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                        <input type="text" name="nik" class="form-control" value="<?= htmlspecialchars($data['nik'] ?? "") ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username'] ?? "") ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                            <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($data['no_hp'] ?? "") ?>" required>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label>Alamat</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                        <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($data['alamat'] ?? "") ?></textarea>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-8">
                        <button type="submit" name="update" class="btn-save">
                            <i class="fas fa-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                    <div class="col-4">
                        <a href="admin_pekon.php" class="btn-cancel">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>