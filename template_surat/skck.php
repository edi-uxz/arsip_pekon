<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['role'])) {
    die("Anda harus login terlebih dahulu.");
}

$role = $_SESSION['role'];
$id_masyarakat = $_SESSION['id_masyarakat'] ?? null;
$nik_login     = $_SESSION['nik'] ?? null;

/* ================= FIELD FORM ================= */
$fields = [
    'nama','nik','tempat_lahir','tgl_lahir',
    'jenis_kelamin','agama','suku',
    'pekerjaan','alamat','keperluan'
];

$data = [];
$userData = [];
$errors = [];

/* ================= AUTO ISI JIKA MASYARAKAT ================= */
if ($role === 'masyarakat' && $id_masyarakat) {

    $stmtUser = $conn->prepare("SELECT * FROM masyarakat WHERE id = ?");
    $stmtUser->bind_param("i", $id_masyarakat);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
}

/* ================= AUTO FILL ================= */
foreach ($fields as $f) {
    if (isset($_POST[$f])) {
        $data[$f] = $_POST[$f];
    } else {
        $data[$f] = $userData[$f] ?? '';
    }
}

$isPreview = isset($_POST['preview']);
$isAjukan  = isset($_POST['ajukan']);

/* ================= FORMAT TANGGAL ================= */
function tgl_indo($tanggal){
    if(!$tanggal) return '';
    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];
    $t = strtotime($tanggal);
    return date('d',$t).' '.$bulan[(int)date('m',$t)].' '.date('Y',$t);
}

/* ================= SIMPAN DATABASE ================= */
$pesan = "";

if ($isAjukan) {

    foreach ($fields as $f) {
        if (trim($data[$f]) === '') {
            $errors[$f] = "Field wajib diisi";
        }
    }

    if (empty($errors)) {

        $detailJson = json_encode($data);

        $jenis      = "Surat Pengantar SKCK";
        $status     = "Menunggu";
        $keterangan = "-";
        $id_template = 4; // sesuaikan dengan ID template SKCK di database

        $nik_simpan = $role === 'masyarakat' ? $nik_login : ($data['nik'] ?? '');
        $id_simpan  = $role === 'masyarakat' ? $id_masyarakat : null;

        $stmt = $conn->prepare("INSERT INTO pengajuan_surat
            (jenis_surat, nik, id_masyarakat, id_template, data_isi, status, is_printed, tanggal_pengajuan, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), ?)");

        $stmt->bind_param("ssiisss",
            $jenis,
            $nik_simpan,
            $id_simpan,
            $id_template,
            $detailJson,
            $status,
            $keterangan
        );

        if($stmt->execute()){
            $pesan = "SKCK berhasil diajukan dan menunggu persetujuan admin.";
        } else {
            $pesan = "Gagal mengajukan SKCK.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Form SKCK</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(rgba(30,58,138,0.9),rgba(30,58,138,0.95));
    font-family:'Poppins',sans-serif;
    padding:40px 15px;
    min-height:100vh;
}

.container-form{ max-width:1000px; margin:auto; }

.card-custom{
    background:#fff;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
    overflow:hidden;
}

.card-header{
    background:#1e3a8a;
    color:white;
    padding:25px;
    text-align:center;
}

.a4-preview{
    width:210mm;
    min-height:297mm;
    padding:25mm;
    margin:40px auto;
    background:white;
    color:black;
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

<div class="card-custom">
<div class="card-header">
<h4>SURAT PENGANTAR SKCK</h4>
<small>Silakan lengkapi formulir</small>
</div>

<div class="p-4">

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">
<div class="row">
<?php foreach($fields as $f): ?>
<div class="col-md-6 mb-3">
<label class="form-label"><?= strtoupper(str_replace('_',' ',$f)) ?></label>

<?php if($f == 'alamat'): ?>
<textarea name="<?= $f ?>" class="form-control" required><?= htmlspecialchars($data[$f]) ?></textarea>

<?php elseif($f == 'tgl_lahir'): ?>
<input type="date" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f]) ?>" required>

<?php else: ?>
<input type="text" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f]) ?>" required>
<?php endif; ?>

</div>
<?php endforeach; ?>
</div>

<div class="text-end mt-3">
<button type="submit" name="preview" class="btn btn-primary me-2">Preview</button>
<button type="submit" name="ajukan" class="btn btn-success">Ajukan</button>
</div>

</form>
</div>
</div>

<?php if($isPreview): ?>
<div class="a4-preview">

<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PARDASUKA<br>
PEKON SUKOREJO
</div>

<div class="kop-line"></div>

<div style="text-align:center;margin-bottom:20px;">
<strong><u>SURAT PENGANTAR SKCK</u></strong><br>
Nomor : 300/_____/<?= date('Y') ?>
</div>

<p>Yang bertanda tangan di bawah ini menerangkan bahwa :</p>

<table style="margin-left:40px;">
<tr><td width="200">Nama</td><td>: <?= htmlspecialchars($data['nama']) ?></td></tr>
<tr><td>Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($data['tempat_lahir']) ?>, <?= tgl_indo($data['tgl_lahir']) ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($data['nik']) ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($data['alamat']) ?></td></tr>
</table>

<p style="margin-top:20px;">
Surat ini dibuat sebagai pengantar untuk keperluan <?= htmlspecialchars($data['keperluan']) ?>.
</p>

<div style="text-align:right;margin-top:60px;">
Sukorejo, <?= tgl_indo(date('Y-m-d')) ?><br>
Kepala Pekon Sukorejo<br><br><br><br>
<strong><u>_____________________</u></strong>
</div>

</div>

<div class="text-center mb-5 btn-print">
<button onclick="window.print()" class="btn btn-dark">Cetak A4</button>
</div>
<?php endif; ?>

</div>
</body>
</html>