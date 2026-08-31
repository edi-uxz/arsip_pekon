<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../koneksi.php';
include __DIR__ . '/../helper/tanggal.php';

date_default_timezone_set('Asia/Jakarta');

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['role'])) {
    die("Anda harus login terlebih dahulu.");
}

$role = $_SESSION['role'];
$id_masyarakat = $_SESSION['id_masyarakat'] ?? null;
$nik_login     = $_SESSION['nik'] ?? null;

/* ================= FIELD ================= */
$fields = [
    'p1_nama','p1_tempat_lahir','p1_tgl_lahir','p1_pekerjaan','p1_alamat',
    'p2_nama','p2_tempat_lahir','p2_tgl_lahir','p2_pekerjaan','p2_alamat',
    'lokasi_tanah','luas_tanah','harga_tanah',
    'batas_utara','batas_selatan','batas_barat','batas_timur'
];

$data = [];
$userData = [];
$errors = [];

/* ================= AUTO ISI ================= */
if ($role === 'masyarakat' && $id_masyarakat) {
    $stmtUser = $conn->prepare("SELECT * FROM masyarakat WHERE id = ?");
    $stmtUser->bind_param("i", $id_masyarakat);
    $stmtUser->execute();
    $userData = $stmtUser->get_result()->fetch_assoc();
}

/* ================= AUTO FILL ================= */
foreach ($fields as $f) {
    $data[$f] = $_POST[$f] ?? ($userData[$f] ?? '');
}

$isPreview = isset($_POST['preview']);
$isAjukan  = isset($_POST['ajukan']);

/* ================= SIMPAN ================= */
$pesan = "";

if ($isAjukan) {

    foreach ($fields as $f) {
        if (trim($data[$f]) === '') {
            $errors[$f] = "Field wajib diisi";
        }
    }

    if (empty($errors)) {

        $detailJson = json_encode($data);

        $jenis      = "Surat Keterangan Jual Beli Tanah";
        $status     = "diproses";
        $keterangan = "-";
        $id_template = 3;

        /* ================= DETEKSI ROLE ================= */
        $id_admin_fix = null;
        $id_masyarakat_fix = null;
        $nik_fix = null;

        if ($role === 'masyarakat') {
            $id_masyarakat_fix = $id_masyarakat;
            $nik_fix = $nik_login;
        } elseif ($role === 'admin') {
            $id_admin_fix = $_SESSION['id_admin'] ?? null;
        }

        /* ================= INSERT ================= */
        $stmt = $conn->prepare("INSERT INTO pengajuan_surat
            (jenis_surat, nik, id_masyarakat, id_admin, id_template, data_isi, status, is_printed, tanggal_pengajuan, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0, NOW(), ?)");

        $stmt->bind_param("ssiissss",
            $jenis,
            $nik_fix,
            $id_masyarakat_fix,
            $id_admin_fix,
            $id_template,
            $detailJson,
            $status,
            $keterangan
        );

        if ($stmt->execute()) {
            $pesan = "Surat berhasil diajukan dan menunggu persetujuan admin.";
        } else {
            $pesan = "Gagal: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Form Surat Jual Beli Tanah</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(rgba(30,58,138,0.9),rgba(30,58,138,0.95));
    padding:40px 15px;
}
.container-form{ max-width:950px; margin:auto; }
.card-custom{
    background:#fff;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
}
.section-title{
    background:#1e3a8a;
    color:white;
    padding:8px 15px;
    border-radius:10px;
    margin-bottom:15px;
    font-weight:600;
}
.a4-preview{
    width:210mm;
    min-height:297mm;
    padding:25mm;
    margin:40px auto;
    background:white;
    font-family:"Times New Roman";
    font-size:12pt;
    box-shadow:0 0 20px rgba(0,0,0,0.4);
}
.kop{text-align:center;font-weight:bold;}
.kop-line{border-top:3px solid #000;border-bottom:1px solid #000;height:5px;margin:10px 0 20px 0;}
@media print{
    body{background:white;}
    .container-form, .btn-print{display:none;}
}
</style>
</head>
<body>

<div class="container container-form">

<div class="card-custom p-4">

<h4 class="text-center mb-4">SURAT KETERANGAN JUAL BELI TANAH</h4>

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">

<div class="section-title">Pihak I (Penjual)</div>
<div class="row">
<div class="col-md-6 mb-3">
<label>Nama Lengkap</label>
<input type="text" name="p1_nama" class="form-control" value="<?= htmlspecialchars($data['p1_nama']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Pekerjaan</label>
<input type="text" name="p1_pekerjaan" class="form-control" value="<?= htmlspecialchars($data['p1_pekerjaan']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Tempat Lahir</label>
<input type="text" name="p1_tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['p1_tempat_lahir']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="p1_tgl_lahir" class="form-control" value="<?= htmlspecialchars($data['p1_tgl_lahir']) ?>" required>
</div>
<div class="col-12 mb-3">
<label>Alamat</label>
<textarea name="p1_alamat" class="form-control" required><?= htmlspecialchars($data['p1_alamat']) ?></textarea>
</div>
</div>

<div class="section-title">Pihak II (Pembeli)</div>
<div class="row">
<div class="col-md-6 mb-3">
<label>Nama Lengkap</label>
<input type="text" name="p2_nama" class="form-control" value="<?= htmlspecialchars($data['p2_nama']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Pekerjaan</label>
<input type="text" name="p2_pekerjaan" class="form-control" value="<?= htmlspecialchars($data['p2_pekerjaan']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Tempat Lahir</label>
<input type="text" name="p2_tempat_lahir" class="form-control" value="<?= htmlspecialchars($data['p2_tempat_lahir']) ?>" required>
</div>
<div class="col-md-6 mb-3">
<label>Tanggal Lahir</label>
<input type="date" name="p2_tgl_lahir" class="form-control" value="<?= htmlspecialchars($data['p2_tgl_lahir']) ?>" required>
</div>
<div class="col-12 mb-3">
<label>Alamat</label>
<textarea name="p2_alamat" class="form-control" required><?= htmlspecialchars($data['p2_alamat']) ?></textarea>
</div>
</div>

<div class="section-title">Detail Tanah</div>
<div class="row">
<div class="col-md-6 mb-3">
<label>Lokasi Tanah</label>
<input type="text" name="lokasi_tanah" class="form-control" value="<?= htmlspecialchars($data['lokasi_tanah']) ?>" required>
</div>
<div class="col-md-3 mb-3">
<label>Luas (m²)</label>
<input type="text" name="luas_tanah" class="form-control" value="<?= htmlspecialchars($data['luas_tanah']) ?>" required>
</div>
<div class="col-md-3 mb-3">
<label>Harga (Rp)</label>
<input type="text" name="harga_tanah" class="form-control" value="<?= htmlspecialchars($data['harga_tanah']) ?>" required>
</div>
</div>

<div class="section-title">Batas Tanah</div>
<div class="row">
<div class="col-md-6 mb-3"><input type="text" name="batas_utara" class="form-control" placeholder="Utara" value="<?= htmlspecialchars($data['batas_utara']) ?>" required></div>
<div class="col-md-6 mb-3"><input type="text" name="batas_selatan" class="form-control" placeholder="Selatan" value="<?= htmlspecialchars($data['batas_selatan']) ?>" required></div>
<div class="col-md-6 mb-3"><input type="text" name="batas_barat" class="form-control" placeholder="Barat" value="<?= htmlspecialchars($data['batas_barat']) ?>" required></div>
<div class="col-md-6 mb-3"><input type="text" name="batas_timur" class="form-control" placeholder="Timur" value="<?= htmlspecialchars($data['batas_timur']) ?>" required></div>
</div>

<div class="text-end mt-3">
<button type="submit" name="preview" class="btn btn-primary me-2">Preview</button>
<button type="submit" name="ajukan" class="btn btn-success">Ajukan</button>
</div>

</form>
</div>
<?php if($isPreview): ?>
<div class="a4-preview">

<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
PEKON BUMIAYU
</div>

<div class="kop-line"></div>

<div style="text-align:center;margin-bottom:20px;">
<strong><u>SURAT KETERANGAN JUAL BELI TANAH</u></strong><br>
Nomor : 300/_____/<?= date('Y') ?>
</div>

<p>Yang bertanda tangan di bawah ini menerangkan bahwa telah terjadi jual beli tanah sebagai berikut:</p>

<p><strong>Pihak I (Penjual)</strong><br>
Nama: <?= htmlspecialchars($data['p1_nama']) ?><br>
TTL: <?= htmlspecialchars($data['p1_tempat_lahir']) ?>, <?= tgl_indo($data['p1_tgl_lahir']) ?><br>
Pekerjaan: <?= htmlspecialchars($data['p1_pekerjaan']) ?><br>
Alamat: <?= htmlspecialchars($data['p1_alamat']) ?></p>

<p><strong>Pihak II (Pembeli)</strong><br>
Nama: <?= htmlspecialchars($data['p2_nama']) ?><br>
TTL: <?= htmlspecialchars($data['p2_tempat_lahir']) ?>, <?= tgl_indo($data['p2_tgl_lahir']) ?><br>
Pekerjaan: <?= htmlspecialchars($data['p2_pekerjaan']) ?><br>
Alamat: <?= htmlspecialchars($data['p2_alamat']) ?></p>

<p>Lokasi Tanah: <?= htmlspecialchars($data['lokasi_tanah']) ?><br>
Luas: <?= htmlspecialchars($data['luas_tanah']) ?> m²<br>
Harga: Rp <?= htmlspecialchars($data['harga_tanah']) ?></p>

<p>Batas Tanah:<br>
Utara: <?= htmlspecialchars($data['batas_utara']) ?><br>
Selatan: <?= htmlspecialchars($data['batas_selatan']) ?><br>
Barat: <?= htmlspecialchars($data['batas_barat']) ?><br>
Timur: <?= htmlspecialchars($data['batas_timur']) ?></p>

<div style="text-align:right;margin-top:60px;">
Bumiayu, <?= tgl_indo(date('Y-m-d')) ?><br>
Kepala Pekon Bumiayu<br><br><br><br>
<strong><u>HADIRIN</u></strong>
</div>

</div>

<div class="text-center mb-5 btn-print">
<button onclick="window.print()" class="btn btn-dark">Cetak A4</button>
</div>
<?php endif; ?>
</div>
</body>
</html>