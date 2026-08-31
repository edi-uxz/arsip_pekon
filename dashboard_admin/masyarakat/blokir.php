<?php
session_start();
require_once '../../koneksi.php';

// Cek khusus admin saja
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
$aksi = $_GET['aksi'] ?? null;

if ($id && in_array($aksi, ['blokir', 'buka'])) {
    $statusBaru = ($aksi === 'blokir') ? 'blokir' : 'aktif';
    $update = mysqli_query($conn, "UPDATE masyarakat SET status='$statusBaru' WHERE id='$id'");
    if ($update) {
        header("Location: masyarakat.php?pesan=status_diubah");
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Gagal mengubah status.</div>";
    }
} else {
    header("Location: masyarakat.php");
    exit;
}
