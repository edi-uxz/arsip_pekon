<?php
include '../../koneksi.php';
session_start();

if (!isset($_SESSION['admin'])) {
    echo "Akses ditolak."; exit;
}

$id = $_GET['id'];
mysqli_query($conn, "UPDATE pengajuan_surat SET status = 'ditolak' WHERE id = '$id'");
header("Location: ../surat_masuk.php");
