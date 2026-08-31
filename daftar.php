<?php
session_start();
include 'koneksi.php';

// Validasi akses admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if (isset($_POST['register'])) {
    $nik      = mysqli_real_escape_string($conn, $_POST['nik']);
    $username = mysqli_real_escape_string($conn, $_POST['username']); 
    $nama     = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat   = mysqli_real_escape_string($conn, $_POST['alamat']);
    $nohp     = mysqli_real_escape_string($conn, $_POST['nohp']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

    if ($role == 'admin') {
        $cek = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Username Admin sudah terdaftar!');</script>";
        } else {
            $sql = "INSERT INTO admin (nama, username, password) VALUES ('$nama', '$username', '$password')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Admin berhasil disimpan!'); window.location='dashboard_admin/index.php';</script>";
            }
        }
    } else if ($role == 'masyarakat') {
        $cek = mysqli_query($conn, "SELECT * FROM masyarakat WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Username Masyarakat sudah terdaftar!');</script>";
        } else {
            $sql = "INSERT INTO masyarakat (nama, username, nik, status, password, alamat, no_hp) 
                    VALUES ('$nama', '$username', '$nik', 'aktif', '$password', '$alamat', '$nohp')";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Masyarakat berhasil disimpan!'); window.location='dashboard_admin/masyarakat/masyarakat.php';</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah User | Sistem Arsip Pekon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-color: #1e3a8a; /* Deep Blue Konsisten */
            --accent-color: #fbbf24;  /* Amber Gold */
            --white: #ffffff;
            --text-dark: #1f2937;
            --shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        * {
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Background Image Konsisten dengan Login */
            background: linear-gradient(rgba(30, 58, 138, 0.75), rgba(30, 58, 138, 0.85)), 
                        url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 20px;
        }

        /* Opening Curtain Effect */
        #shutter {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--primary-color);
            z-index: 9999;
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1);
        }
        #shutter.v-hide { transform: scaleY(0); }

        .form-container {
            width: 100%;
            max-width: 550px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 10;
        }

        .form-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.3);
        }

        .header-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-form i {
            font-size: 45px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .header-form h2 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .header-form p {
            color: #6b7280;
            font-size: 14px;
        }

        .section-label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--primary-color);
            margin: 20px 0 10px 5px;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--accent-color);
            width: fit-content;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 15px;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
            background: #fff;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        button {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        button:hover {
            background: #1e40af;
            transform: scale(1.02);
        }

        .link-back {
            display: block;
            text-align: center;
            margin-top: 25px;
            text-decoration: none;
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
        }

        .link-back:hover {
            color: var(--primary-color);
        }

        /* Custom styling for Select option placeholder */
        select:invalid { color: #9ca3af; }
    </style>
</head>
<body>

<div id="shutter"></div>

<div class="form-container animate__animated animate__fadeInUp">
    <div class="header-form">
        <i class="fas fa-user-plus"></i>
        <h2>Tambah User Baru</h2>
        <p>Registrasi Admin Pekon & Masyarakat</p>
    </div>

    <form method="post" action="">
        <span class="section-label">Identitas Diri</span>
        <div class="input-wrapper">
            <i class="fas fa-id-card"></i>
            <input type="text" name="nik" placeholder="Masukkan 16 Digit NIK" required>
        </div>
        
        <div class="input-wrapper">
            <i class="fas fa-user"></i>
            <input type="text" name="nama" placeholder="Nama Lengkap Sesuai KTP" required>
        </div>

        <span class="section-label">Kredensial Akun</span>
        <div class="row" style="display: flex; gap: 15px;">
            <div class="input-wrapper" style="flex: 1;">
                <i class="fas fa-at"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-wrapper" style="flex: 1;">
                <i class="fas fa-key"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
        </div>

        <span class="section-label">Kontak & Peran</span>
        <div class="input-wrapper">
            <i class="fas fa-phone"></i>
            <input type="text" name="nohp" placeholder="Nomor WhatsApp Aktif" required>
        </div>

        <div class="input-wrapper">
            <i class="fas fa-map-marker-alt"></i>
            <input type="text" name="alamat" placeholder="Alamat Domisili" required>
        </div>

        <div class="input-wrapper">
            <i class="fas fa-user-tag"></i>
            <select name="role" required>
                <option value="" disabled selected>Pilih Hak Akses User</option>
                <option value="masyarakat">Masyarakat Umum</option>
                <option value="admin">Admin Pekon (Perangkat)</option>
            </select>
        </div>
        
        <button type="submit" name="register">
            <i class="fas fa-save"></i> Daftarkan User Sekarang
        </button>
    </form>

    <a href="dashboard_admin/index.php" class="link-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Panel Admin
    </a>
</div>

<script>
    // Curtain Effect
    window.addEventListener('load', function() {
        const shutter = document.getElementById('shutter');
        setTimeout(() => {
            shutter.classList.add('v-hide');
        }, 400);
    });

    // Validasi sederhana untuk NIK (hanya angka)
    document.querySelector('input[name="nik"]').addEventListener('input', function (e) {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>

</body>
</html>