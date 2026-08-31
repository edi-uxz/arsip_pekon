<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username  = $_POST['username'];
    $nik_input = $_POST['password'];
    $role      = $_POST['role'];

    if ($role === 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultAdmin = $stmt->get_result();
        $dataAdmin = $resultAdmin->fetch_assoc();

        if ($dataAdmin && $nik_input === $dataAdmin['nip']) {
            $_SESSION['role'] = 'admin';
            $_SESSION['nama'] = $dataAdmin['nama'];
            $_SESSION['id']   = $dataAdmin['id'];
            $_SESSION['username'] = $dataAdmin['username'];
            $_SESSION['nip']  = $dataAdmin['nip'];
            header("Location: dashboard_admin/index.php");
            exit();
        }
    }

    if ($role === 'masyarakat') {
        $stmt = $conn->prepare("SELECT * FROM masyarakat WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $resultUser = $stmt->get_result();
        $dataUser = $resultUser->fetch_assoc();

        if ($dataUser) {
            $status = strtolower(trim($dataUser['status'] ?? 'aktif'));

            if ($nik_input === $dataUser['nik'] && $status === 'aktif') {
                $_SESSION['role'] = 'masyarakat';
                $_SESSION['nama'] = $dataUser['nama'];
                $_SESSION['nik']  = $dataUser['nik'];   // TAMBAHAN
                $_SESSION['id_masyarakat'] = $dataUser['id'];
                $_SESSION['username'] = $dataUser['username']; 
                header("Location: dashboard_user/index.php");
                exit();
            }
        }
    }

    echo "<script>alert('Username, NIK salah atau status tidak aktif!'); window.location='login.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Arsip Surat Pekon Suka Agung Barat</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary-color: #1e3a8a; /* Consistent Deep Blue */
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
            /* Background Image dengan Overlay */
            background: linear-gradient(rgba(30, 58, 138, 0.7), rgba(30, 58, 138, 0.8)), 
                        url('https://images.unsplash.com/photo-1568667256549-094345857637?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        /* Opening Curtain Effect (Small Version) */
        #shutter {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: var(--primary-color);
            z-index: 9999;
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1);
        }
        #shutter.v-hide { transform: scaleX(0); }

        .login-container {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            padding: 50px 40px;
            border-radius: 24px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            z-index: 10;
        }

        /* Hover interaction for the card */
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 45px rgba(0,0,0,0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header h2 {
            margin: 0;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 24px;
        }

        .login-header p {
            color: #6b7280;
            font-size: 14px;
            margin-top: 8px;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            margin-left: 5px;
        }

        input, select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-family: inherit;
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--primary-color);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(30, 58, 138, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            filter: grayscale(1);
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
            margin-top: 10px;
        }

        button:hover {
            background: #1e40af;
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(30, 58, 138, 0.4);
        }

        .btn-back {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #6b7280;
            font-size: 13px;
        }

        .btn-back:hover {
            color: var(--primary-color);
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            font-size: 11px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>

<div id="shutter"></div>

<div class="login-container animate__animated animate__zoomIn">
    <div class="login-header">
        <h2>Portal Layanan</h2>
        <p>Arsip Surat Bumi Ayu</p>
    </div>

    <form method="POST" action="">
        <div class="input-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" required>
        </div>

        <div class="input-group">
            <label>Password (NIK/NIP)</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Masukkan 16 digit NIK" required>
                <span class="toggle-password" id="toggleIcon" onclick="togglePassword()">👁️</span>
            </div>
        </div>

        <div class="input-group">
            <label>Login Sebagai</label>
            <select name="role" required>
                <option value="" disabled selected>Pilih hak akses</option>
                <option value="admin">Administrator (Perangkat Desa)</option>
                <option value="masyarakat">Masyarakat Umum</option>
            </select>
        </div>

        <button type="submit" name="login">Masuk ke Sistem</button>
    </form>

    <a href="index.php" class="btn-back">← Kembali ke Beranda</a>

    <div class="footer-text">
        &copy; <?php echo date('Y'); ?> Digital Arsip - Desa Bumi Ayu
    </div>
</div>

<script>
    // Curtain Effect
    window.addEventListener('load', function() {
        const shutter = document.getElementById('shutter');
        setTimeout(() => {
            shutter.classList.add('v-hide');
        }, 300);
    });

    function togglePassword() {
        const passField = document.getElementById("password");
        const icon = document.getElementById("toggleIcon");
        
        if (passField.type === "password") {
            passField.type = "text";
            icon.textContent = "🙈";
        } else {
            passField.type = "password";
            icon.textContent = "👁️";
        }
    }
</script>

</body>
</html>