<?php
session_start();
include '../koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_masyarakat']) && !isset($_SESSION['id_admin'])) {
    echo "Anda belum login. <a href='../login.php'>Login di sini</a>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $id_template = $_POST['id_template'] ?? '';
    $nama = $_POST['nama'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $keperluan = $_POST['keperluan'] ?? '';

    $id_masyarakat = $_SESSION['id_masyarakat'] ?? null;
    $id_admin = $_SESSION['id_admin'] ?? null;

    // Validasi input
    if (empty($id_template) || empty($nama) || empty($alamat) || empty($keperluan)) {
        echo "Semua kolom harus diisi. <a href='javascript:history.back()'>Kembali</a>";
        exit;
    }

    // Format data_isi jadi JSON
    $data_isi = json_encode([
        'nama' => $nama,
        'alamat' => $alamat,
        'keperluan' => $keperluan
    ]);

    $tanggal_pengajuan = date('Y-m-d');
    $status = 'diajukan';
    $is_printed = 0;

    // Siapkan query berdasarkan role
    if ($id_masyarakat) {
        $query = "INSERT INTO pengajuan_surat 
        (id_masyarakat, id_template, tanggal_pengajuan, status, data_isi, is_printed) 
        VALUES 
        ('$id_masyarakat', '$id_template', '$tanggal_pengajuan', '$status', '$data_isi', '$is_printed')";
    } elseif ($id_admin) {
        $query = "INSERT INTO pengajuan_surat 
        (id_admin, id_template, tanggal_pengajuan, status, data_isi, is_printed) 
        VALUES 
        ('$id_admin', '$id_template', '$tanggal_pengajuan', '$status', '$data_isi', '$is_printed')";
    } else {
        echo "Sesi login tidak valid.";
        exit;
    }

    // Eksekusi query
    if (mysqli_query($conn, $query)) {
        echo "✅ Surat berhasil diajukan!";
        echo "<br><a href='index.php'>Kembali ke Dashboard</a>";
    } else {
        echo "❌ Gagal menyimpan pengajuan: " . mysqli_error($conn);
    }
} else {
    echo "Akses tidak valid.";
}
?>
