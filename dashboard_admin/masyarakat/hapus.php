<?php
session_start();
require_once '../../koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $hapus = mysqli_query($conn, "DELETE FROM masyarakat WHERE id = '$id'");
    if ($hapus) {
        header("Location: masyarakat.php?pesan=hapus_berhasil");
    } else {
        echo "Gagal menghapus data.";
    }
} else {
    header("Location: masyarakat.php");
}
