<?php
session_start();
include '../../koneksi.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    mysqli_query($conn, "DELETE FROM pengajuan_surat WHERE id = $id");
}

header("Location: ../surat_masuk.php");
exit;