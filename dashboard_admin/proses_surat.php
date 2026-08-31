<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['id_admin'])) {
    echo "Akses ditolak.";
    exit;
}

$id = $_GET['id'] ?? '';
$aksi = $_GET['aksi'] ?? '';

if ($id && in_array($aksi, ['acc', 'tolak'])) {
    $status_baru = $aksi == 'acc' ? 'acc' : 'ditolak';
    $query = "UPDATE pengajuan_surat SET status = '$status_baru' WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: surat_masuk.php");
        exit;
    } else {
        echo "Gagal memperbarui status.";
    }
} else {
    echo "Permintaan tidak valid.";
}
