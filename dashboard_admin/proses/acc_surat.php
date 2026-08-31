<?php
session_start();
include '../../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ================= LIBRARY QR ================= */
include "../../assets/qrcode/qrlib.php";

/* ================= CEK ROLE ADMIN ================= */
$is_admin = false;

if (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin') {
    $is_admin = true;
}
if (isset($_SESSION['admin']) || isset($_SESSION['admin_id'])) {
    $is_admin = true;
}

if (!$is_admin) {
    echo "Akses ditolak.";
    exit;
}

/* ================= VALIDASI ID ================= */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "ID tidak valid.";
    exit;
}

/* ================= CEK DATA ================= */
$cek = mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE id = $id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    echo "Data surat tidak ditemukan.";
    exit;
}

/* ================= JIKA SUDAH ADA NOMOR ================= */
if (!empty($data['nomor_surat'])) {
    mysqli_query($conn, "UPDATE pengajuan_surat SET status='disetujui' WHERE id=$id");
    header("Location: ../surat_masuk.php");
    exit;
}

/* ================= HITUNG NOMOR ================= */
$queryCount = mysqli_query($conn, "
    SELECT COUNT(*) as total 
    FROM pengajuan_surat 
    WHERE MONTH(tanggal_pengajuan) = MONTH(NOW())
    AND YEAR(tanggal_pengajuan) = YEAR(NOW())
    AND status IN ('disetujui','acc')
");

$dataCount = mysqli_fetch_assoc($queryCount);
$urutan = $dataCount['total'] + 1;

/* ================= FORMAT NOMOR ================= */
$kode_surat = "300";
$kode_pekon = "PBK";

$bulan = date("n");
$tahun = date("Y");

$bulan_romawi = [
    1=>"I",2=>"II",3=>"III",4=>"IV",5=>"V",6=>"VI",
    7=>"VII",8=>"VIII",9=>"IX",10=>"X",11=>"XI",12=>"XII"
];

$nomor_surat = $kode_surat . "/" .
               str_pad($urutan, 3, "0", STR_PAD_LEFT) . "/" .
               $kode_pekon . "/" .
               $bulan_romawi[$bulan] . "/" .
               $tahun;

/* ================= KODE UNIK ================= */
$kode_unik = md5($id . time());

/* ================= GENERATE QR ================= */
$link = "http://localhost/arsip_pekon/validasi.php?kode=" . $kode_unik;

$folder = "../../assets/qrcode/generate/";

// pastikan folder ada
if (!file_exists($folder)) {
    mkdir($folder, 0777, true);
}

$nama_file = "qr_" . $kode_unik . ".png";
$path = $folder . $nama_file;

// HAPUS FILE LAMA JIKA ADA
if (file_exists($path)) {
    unlink($path);
}

// generate QR (lebih jelas)
QRcode::png($link, $path, QR_ECLEVEL_H, 20, 2);

/* ================= UPDATE DATABASE ================= */
mysqli_query($conn, "
    UPDATE pengajuan_surat 
    SET status='disetujui',
        nomor_surat='$nomor_surat',
        kode_unik='$kode_unik',
        qr_code='$nama_file'
    WHERE id=$id
");

/* ================= REDIRECT ================= */
header("Location: ../surat_masuk.php");
exit;