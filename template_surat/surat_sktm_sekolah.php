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

/* ================= FIELD ================= */
$fields = [
    'jenis_sktm', // TAMBAHAN
    'nama_lengkap','nik','tempat_lahir','tgl_lahir',
    'agama','pekerjaan','alamat','keperluan'
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

/* ================= AUTO KEPERLUAN BPJS ================= */
if (($data['jenis_sktm'] ?? '') == 'bpjs') {
    $data['keperluan'] = 'Pengurusan BPJS Kesehatan';
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

        $jenis = ($data['jenis_sktm'] == 'bpjs') 
            ? "Surat Keterangan Tidak Mampu (BPJS)" 
            : "Surat Keterangan Tidak Mampu (Sekolah)";

        $status     = "Menunggu";
        $keterangan = "-";
        $id_template = 4;

        $nik_simpan = $role === 'masyarakat' ? $nik_login : $data['nik'];
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
            $pesan = "Surat berhasil diajukan dan menunggu persetujuan admin.";
        } else {
            $pesan = "Gagal mengajukan surat.";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Form SKTM</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background: linear-gradient(rgba(30,58,138,0.9),rgba(30,58,138,0.95));
    padding:40px 15px;
}
.container-form{ max-width:900px; margin:auto; }
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

<h4 class="text-center mb-4">SURAT KETERANGAN TIDAK MAMPU</h4>

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">

<div class="section-title">Jenis Surat</div>
<div class="mb-3">
<select name="jenis_sktm" class="form-control" required>
    <option value="">-- Pilih --</option>
    <option value="sekolah" <?= ($data['jenis_sktm'] ?? '')=='sekolah'?'selected':'' ?>>Sekolah</option>
    <option value="bpjs" <?= ($data['jenis_sktm'] ?? '')=='bpjs'?'selected':'' ?>>BPJS</option>
</select>
</div>

<div class="section-title">Data Pemohon</div>

<div class="row">
<?php foreach($fields as $f): if($f=='jenis_sktm') continue; ?>
<div class="col-md-6 mb-3">
<label><?= strtoupper(str_replace('_',' ',$f)) ?></label>

<?php if($f=='alamat'): ?>
<textarea name="<?= $f ?>" class="form-control" required><?= htmlspecialchars($data[$f]) ?></textarea>
<?php elseif(strpos($f,'tgl')!==false): ?>
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

<?php if($isPreview): ?>
<div class="a4-preview">

<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
PEKON BUMIAYU
</div>

<div class="kop-line"></div>

<div style="text-align:center;margin-bottom:20px;">
<strong><u>
SURAT KETERANGAN TIDAK MAMPU <?= strtoupper($data['jenis_sktm']=='bpjs'?'(BPJS)':'(SEKOLAH)') ?>
</u></strong><br>
Nomor : 400/_____/<?= date('Y') ?>
</div>

<p>Diberikan kepada :</p>

<table style="margin-left:40px;">
<tr><td width="200">Nama</td><td>: <?= htmlspecialchars($data['nama_lengkap']) ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($data['nik']) ?></td></tr>
<tr><td>TTL</td><td>: <?= htmlspecialchars($data['tempat_lahir']) ?>, <?= tgl_indo($data['tgl_lahir']) ?></td></tr>
<tr><td>Agama</td><td>: <?= htmlspecialchars($data['agama']) ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($data['alamat']) ?></td></tr>
</table>

<br>

<p style="margin-left:40px;">
<?php if($data['jenis_sktm']=='bpjs'): ?>
Orang tersebut di atas benar merupakan keluarga <b>TIDAK MAMPU</b> dan layak mendapatkan bantuan pelayanan BPJS.
<?php else: ?>
Orang tersebut di atas benar merupakan keluarga <b>TIDAK MAMPU</b> dan surat ini digunakan untuk <?= htmlspecialchars($data['keperluan']) ?>.
<?php endif; ?>
</p>

<p style="margin-top:20px;">
Demikian surat ini dibuat untuk dipergunakan sebagaimana mestinya.
</p>

<div style="text-align:right;margin-top:60px;">
Bumiayu, <?= tgl_indo(date('Y-m-d')) ?><br>
Kepala Pekon Bumiayu<br><br><br><br>

<strong><u>............................</u></strong><br>
NIP. ............................
</div>

</div>
<?php endif; ?>

</div>
</body>
</html>