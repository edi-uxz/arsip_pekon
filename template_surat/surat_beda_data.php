<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$fields = [
    // DATA KK
    'nama_kk','jk_kk','ttl_kk','pekerjaan_kk','nik_kk','alamat_kk',

    // DATA PEMBANDING
    'nama_beda','jk_beda','ttl_beda','pekerjaan_beda','nik_beda',

    // TAMBAHAN
    'dusun','rt','rw','keterangan_beda','sumber_data1','sumber_data2'
];

$data = [];

foreach ($fields as $f) {
    $data[$f] = $_POST[$f] ?? '';
}

$isPreview = isset($_POST['preview']);
$isAjukan  = isset($_POST['ajukan']);
$pesan = "";

/* ================= SIMPAN ================= */
if ($isAjukan) {

    $json = json_encode($data);

    $stmt = $conn->prepare("INSERT INTO pengajuan_surat
    (jenis_surat, data_isi, status, tanggal_pengajuan, id_template)
    VALUES ('Surat Keterangan Beda Data', ?, 'Menunggu', NOW(), 6)");

    $stmt->bind_param("s", $json);

    if ($stmt->execute()) {
        $pesan = "Surat berhasil diajukan!";
    } else {
        $pesan = "Gagal mengajukan surat!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Beda Data</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    background:#1e3a8a;
    padding:40px;
}
.container{max-width:1000px;margin:auto;}
.preview{
    width:210mm;
    min-height:297mm;
    background:white;
    padding:25mm;
    margin-top:40px;
    font-family:"Times New Roman";
}
</style>
</head>

<body>

<div class="container">

<div class="card">
<div class="card-header bg-primary text-white text-center">
<h4>SURAT KETERANGAN BEDA DATA</h4>
</div>

<div class="card-body">

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">

<h5 class="mt-3">DATA DI KARTU KELUARGA (KK)</h5>
<div class="row">
<?php foreach(['nama_kk','jk_kk','ttl_kk','pekerjaan_kk','nik_kk','alamat_kk'] as $f): ?>
<div class="col-md-6 mb-3">
<label class="form-label"><?= strtoupper(str_replace('_',' ',$f)) ?></label>

<?php if($f == 'alamat_kk'): ?>
<textarea name="<?= $f ?>" class="form-control"><?= $data[$f] ?></textarea>
<?php else: ?>
<input type="text" name="<?= $f ?>" class="form-control" value="<?= $data[$f] ?>">
<?php endif; ?>

</div>
<?php endforeach; ?>
</div>

<h5 class="mt-3">DATA PADA DOKUMEN LAIN</h5>

<input name="sumber_data1" class="form-control mb-2" placeholder="Contoh: KTP / Ijazah">

<div class="row">
<?php foreach(['nama_beda','jk_beda','ttl_beda','pekerjaan_beda','nik_beda'] as $f): ?>
<div class="col-md-6 mb-3">
<label class="form-label"><?= strtoupper(str_replace('_',' ',$f)) ?></label>
<input type="text" name="<?= $f ?>" class="form-control" value="<?= $data[$f] ?>">
</div>
<?php endforeach; ?>
</div>

<h5 class="mt-3">KETERANGAN TAMBAHAN</h5>

<input name="sumber_data2" class="form-control mb-2" placeholder="Contoh: KK / KTP">

<div class="row">
<div class="col-md-4 mb-3">
<label>Dusun</label>
<input name="dusun" class="form-control" value="<?= $data['dusun'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>RT</label>
<input name="rt" class="form-control" value="<?= $data['rt'] ?>">
</div>

<div class="col-md-4 mb-3">
<label>RW</label>
<input name="rw" class="form-control" value="<?= $data['rw'] ?>">
</div>
</div>

<textarea name="keterangan_beda" class="form-control mb-3" placeholder="Penjelasan perbedaan data"><?= $data['keterangan_beda'] ?></textarea>

<div class="text-end">
<button name="preview" class="btn btn-primary">Preview</button>
<button name="ajukan" class="btn btn-success">Ajukan</button>
</div>

</form>

</div>
</div>

<?php if($isPreview): ?>
<div class="preview">

<div style="text-align:center;font-weight:bold;">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
PEKON BUMIAYU
</div>

<hr>

<div style="text-align:center;">
<strong><u>SURAT KETERANGAN BEDA DATA</u></strong><br>
Nomor : 470/_____/<?= date('Y') ?>
</div>

<p>
Yang bertanda tangan dibawah ini Kepala Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, menerangkan dengan sebenarnya bahwa :
</p>

<p><b>Data di KK</b></p>

<table style="margin-left:40px;">
<tr><td>Nama</td><td>: <?= $data['nama_kk'] ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= $data['jk_kk'] ?></td></tr>
<tr><td>Tempat Tgl Lahir</td><td>: <?= $data['ttl_kk'] ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= $data['pekerjaan_kk'] ?></td></tr>
<tr><td>NIK</td><td>: <?= $data['nik_kk'] ?></td></tr>
<tr><td>Alamat</td><td>: <?= $data['alamat_kk'] ?></td></tr>
</table>

<p><b>Data di <?= $data['sumber_data1'] ?></b></p>

<table style="margin-left:40px;">
<tr><td>Nama</td><td>: <?= $data['nama_beda'] ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= $data['jk_beda'] ?></td></tr>
<tr><td>Tempat Tgl Lahir</td><td>: <?= $data['ttl_beda'] ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= $data['pekerjaan_beda'] ?></td></tr>
<tr><td>NIK</td><td>: <?= $data['nik_beda'] ?></td></tr>
</table>

<p style="text-align:justify;">
Bahwa nama tersebut diatas benar-benar penduduk yang berdomisili di Dusun <?= $data['dusun'] ?> RT <?= $data['rt'] ?> RW <?= $data['rw'] ?> Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, dan telah terdapat kesalahan penulisan data pada <?= $data['sumber_data1'] ?> dan <?= $data['sumber_data2'] ?>, akan tetapi data tersebut adalah orang yang sama.
</p>

<p>
Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
</p>

<div style="text-align:right;margin-top:60px;">
Bumiayu, <?= date('d-m-Y') ?><br>
Kepala Pekon Bumiayu<br><br><br><br>
<strong>HADIRIN</strong>
</div>

</div>
<?php endif; ?>

</div>
</body>
</html>