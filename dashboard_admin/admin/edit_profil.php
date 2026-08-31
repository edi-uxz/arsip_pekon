<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin' || !isset($_SESSION['id'])) {
    header("Location: ../../login.php");
    exit;
}

$id = $_SESSION['id'];
$query = "SELECT * FROM admin WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    header("Location: ../../login.php");
    exit;
}
$admin = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim(htmlspecialchars($_POST['username']));
    $nama = trim(htmlspecialchars($_POST['nama']));
    $alamat = trim(htmlspecialchars($_POST['alamat']));
    $no_hp = trim(htmlspecialchars($_POST['no_hp']));
    $password = $_POST['password'];

    if (!preg_match('/^\d{9,}$/', $no_hp)) {
        $error = "Nomor HP harus berupa angka minimal 9 digit.";
    } else {
        if (!empty($password)) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $queryUpdate = "UPDATE admin SET username='$username', nama='$nama', alamat='$alamat', no_hp='$no_hp', password='$password_hash' WHERE id='$id'";
        } else {
            $queryUpdate = "UPDATE admin SET username='$username', nama='$nama', alamat='$alamat', no_hp='$no_hp' WHERE id='$id'";
        }

        $resultUpdate = mysqli_query($conn, $queryUpdate);

        if ($resultUpdate) {
            $_SESSION['username'] = $username;
            $_SESSION['nama'] = $nama;

            $result = mysqli_query($conn, "SELECT * FROM admin WHERE id='$id'");
            $admin = mysqli_fetch_assoc($result);

            $success = "Profil berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui profil: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>Edit Profil Admin</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

<style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;
        margin: 0;
    }
    .card {
        max-width: 480px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 16px 32px rgba(38, 50, 56, 0.2);
        background: #ffffffee;
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 2rem;
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-8px);
        box-shadow: 0 24px 48px rgba(38, 50, 56, 0.3);
    }
    .card-header {
        background: transparent;
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 1rem;
    }
    .card-header h4 {
        font-weight: 700;
        color: #2575fc;
        text-align: center;
    }
    label {
        font-weight: 600;
        color: #333;
    }
    input.form-control {
        border-radius: 12px;
        padding-left: 3rem !important;
        font-size: 1rem;
        height: 48px;
        transition: border-color 0.3s ease;
    }
    input.form-control:focus {
        border-color: #2575fc;
        box-shadow: 0 0 8px #2575fcaa;
        outline: none;
    }
    .input-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #2575fcaa;
        font-size: 1.2rem;
    }
    .position-relative {
        position: relative;
    }
    .btn-success {
        background: linear-gradient(90deg, #2575fc, #6a11cb);
        border: none;
        font-weight: 600;
        font-size: 1.1rem;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        transition: background 0.3s ease;
        box-shadow: 0 8px 16px rgba(38, 50, 56, 0.15);
    }
    .btn-success:hover {
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        box-shadow: 0 12px 24px rgba(38, 50, 56, 0.25);
    }
    .btn-secondary {
        border-radius: 12px;
        padding: 12px 20px;
        font-weight: 600;
        font-size: 1rem;
        width: auto;
    }
    .alert {
        border-radius: 12px;
        font-weight: 600;
    }
    @media (max-width: 576px) {
        .card {
            padding: 1.5rem 1rem;
            box-shadow: 0 8px 24px rgba(38, 50, 56, 0.15);
        }
        input.form-control {
            font-size: 0.9rem;
            height: 44px;
            padding-left: 2.8rem !important;
        }
        .btn-success {
            font-size: 1rem;
            padding: 10px;
        }
    }
</style>
</head>
<body>
<div class="card shadow-sm">
    <div class="card-header">
        <h4>Edit Profil Admin</h4>
    </div>
    <div class="card-body">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php elseif (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-4 position-relative">
                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                <i class="bi bi-person input-icon"></i>
                <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($admin['username']) ?>" required />
            </div>

            <div class="mb-4 position-relative">
                <label for="nama" class="form-label">Nama <span class="text-danger">*</span></label>
                <i class="bi bi-card-text input-icon"></i>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($admin['nama']) ?>" required />
            </div>

            <div class="mb-4 position-relative">
                <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                <i class="bi bi-geo-alt input-icon"></i>
                <input type="text" id="alamat" name="alamat" class="form-control" value="<?= htmlspecialchars($admin['alamat']) ?>" required />
            </div>

            <div class="mb-4 position-relative">
                <label for="no_hp" class="form-label">No. HP <span class="text-danger">*</span></label>
                <i class="bi bi-telephone input-icon"></i>
                <input type="tel" id="no_hp" name="no_hp" pattern="\d{9,}" class="form-control" value="<?= htmlspecialchars($admin['no_hp']) ?>" required />
                <small class="form-text text-muted">Hanya angka, minimal 9 digit.</small>
            </div>

            <div class="mb-4 position-relative">
                <label for="password" class="form-label">Password Baru <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                <i class="bi bi-key input-icon"></i>
                <input type="password" id="password" name="password" class="form-control" placeholder="Isi jika ingin ganti password" />
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="../admin_pekon.php" class="btn btn-secondary px-4">Batal</a>
                <button type="submit" class="btn btn-success px-5">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
