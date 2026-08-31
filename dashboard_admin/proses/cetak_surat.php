<?php
include '../../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ================= AMBIL ID ================= */
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    echo "ID tidak valid.";
    exit;
}

/* ================= AMBIL DATA SURAT ================= */
$q_pengajuan = mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE id='$id'");
$data = mysqli_fetch_assoc($q_pengajuan);

if (!$data) {
    echo "Data pengajuan tidak ditemukan.";
    exit;
}

/* ================= CEK STATUS ================= */
if (strtolower($data['status']) !== 'disetujui') {
    echo "Surat belum disetujui.";
    exit;
}

/* ================= DECODE JSON AMAN ================= */
$json_raw = $data['data_isi'] ?? '';
$isi = json_decode($json_raw, true);

if (!is_array($isi)) {
    $isi = [];
}

/* ================= AMBIL NOMOR SURAT ================= */
$nomor_surat = $data['nomor_surat'] ?? '-';

/* ================= AMBIL TEMPLATE ================= */
$id_template = intval($data['id_template']);

$qt = mysqli_query($conn, "SELECT file_path FROM template_surat WHERE id='$id_template'");
$t = mysqli_fetch_assoc($qt);

if ($t && !empty($t['file_path'])) {

    $fileName = pathinfo($t['file_path'], PATHINFO_FILENAME);
    $printFile = "template_print/cetak_{$fileName}.php";

    if (file_exists($printFile)) {

        // Variabel tersedia di template:
        // $data
        // $isi
        // $nomor_surat

        include $printFile;
        exit;

    } else {
        echo "File cetak tidak ditemukan: cetak_{$fileName}.php";
        exit;
    }
}

echo "Template tidak ditemukan.";
exit;