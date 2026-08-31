<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include '../../../koneksi.php';

$id = $_GET['id'] ?? 0;

/* =========================
   AMBIL DATA PENGAJUAN
========================= */
$q = mysqli_query($conn,"SELECT * FROM pengajuan_surat WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

if(!$data){
    echo "Data pengajuan tidak ditemukan";
    exit;
}

$isi = json_decode($data['data_isi'], true);

/* =========================
   DATA SURAT
========================= */

$nama_meninggal    = $isi['nama_meninggal'] ?? '-';
$nik_meninggal     = $isi['nik_meninggal'] ?? '-';
$tempat_lahir_m    = $isi['tempat_lahir_m'] ?? '-';
$tgl_lahir_m       = $isi['tgl_lahir_m'] ?? '-';
$jenis_kelamin_m   = $isi['jenis_kelamin_m'] ?? '-';
$alamat_meninggal  = $isi['alamat_meninggal'] ?? '-';

$hari_meninggal    = $isi['hari_meninggal'] ?? '-';
$tgl_meninggal     = $isi['tgl_meninggal'] ?? '-';
$penyebab          = $isi['penyebab'] ?? '-';
$tempat_pemakaman  = $isi['tempat_pemakaman'] ?? '-';

/* =========================
   DATA PELAPOR
========================= */

$nama_pelapor      = $isi['nama_pelapor'] ?? '-';
$jk_pelapor        = $isi['jk_pelapor'] ?? '-';
$tempat_lahir_p    = $isi['tempat_lahir_p'] ?? '-';
$tgl_lahir_p       = $isi['tgl_lahir_p'] ?? '-';
$agama_pelapor     = $isi['agama_pelapor'] ?? '-';
$alamat_pelapor    = $isi['alamat_pelapor'] ?? '-';
$hubungan          = $isi['hubungan'] ?? '-';

/* =========================
   NOMOR SURAT
========================= */

$nomor_surat = $data['nomor_surat'] ?? '____';

/* =========================
   DATA ADMIN LOGIN
========================= */

$username = $_SESSION['username'] ?? '';

$q_admin = mysqli_query($conn,"SELECT * FROM admin WHERE username='$username'");
$admin = mysqli_fetch_assoc($q_admin);

$pj_pejabat = $admin['nama'] ?? '-';
$nip_pejabat = $admin['nik'] ?? '-';

/* =========================
   TANGGAL SURAT
========================= */

$tanggal_surat = date('Y-m-d');

/* =========================
   UPDATE STATUS PRINT
========================= */

mysqli_query($conn,"UPDATE pengajuan_surat SET is_printed=1 WHERE id='$id'");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Surat Kematian</title>

<style>

@page{
size:A4;
margin:20mm;
}

body{
font-family:"Times New Roman", serif;
font-size:14px;
line-height:1.6;
}

.kop{
text-align:center;
}

.kop img{
width:70px;
position:absolute;
left:40px;
top:30px;
}

.judul{
text-align:center;
font-weight:bold;
text-decoration:underline;
margin-top:10px;
}

.nomor{
text-align:center;
margin-bottom:20px;
}

table.data{
width:100%;
border-collapse:collapse;
margin-top:5px;
}

table.data td{
padding:2px 4px;
vertical-align:top;
}

table.data td:first-child{
width:170px;
}

.ttd{
margin-top:30px;
width:100%;
}

</style>

</head>

<body>

<!-- KOP SURAT -->

<div class="kop">

<img src="../../../logo_pringsewu.png">

<div style="font-size:16px;font-weight:bold;">
PEMERINTAH KABUPATEN PRINGSEWU
</div>

<div style="font-size:15px;font-weight:bold;">
KECAMATAN PARDASUKA
</div>

<div style="font-size:15px;font-weight:bold;">
PEKON BUMIAYU
</div>

<div style="font-size:12px;">
Jalan Utama Pekon Bumiayu Kecamatan Pardasuka Kabupaten Pringsewu Kode Pos 35372
</div>

<hr style="border:2px solid black;margin-top:6px;">

</div>

<!-- JUDUL -->

<div class="judul">
SURAT KETERANGAN KEMATIAN
</div>

<div class="nomor">
NOMOR : 475 / <?= $nomor_surat ?> / <?= date('Y') ?>
</div>

<p>
Yang bertandatangan di bawah ini Kepala Pekon Bumiayu Kecamatan Pardasuka Kabupaten Pringsewu menerangkan bahwa :
</p>

<table class="data">

<tr>
<td>Nama</td>
<td>: <?= htmlspecialchars($nama_meninggal) ?></td>
</tr>

<tr>
<td>NIK</td>
<td>: <?= htmlspecialchars($nik_meninggal) ?></td>
</tr>

<tr>
<td>Tempat / Tgl Lahir</td>
<td>: <?= htmlspecialchars($tempat_lahir_m) ?>, <?= date('d F Y',strtotime($tgl_lahir_m)) ?></td>
</tr>

<tr>
<td>Jenis Kelamin</td>
<td>: <?= htmlspecialchars($jenis_kelamin_m) ?></td>
</tr>

<tr>
<td>Alamat</td>
<td>: <?= htmlspecialchars($alamat_meninggal) ?></td>
</tr>

</table>

<p>
Nama tersebut diatas benar telah meninggal dunia pada :
</p>

<table class="data">

<tr>
<td>Hari</td>
<td>: <?= htmlspecialchars($hari_meninggal) ?></td>
</tr>

<tr>
<td>Tanggal</td>
<td>: <?= date('d F Y',strtotime($tgl_meninggal)) ?></td>
</tr>

<tr>
<td>Penyebab Kematian</td>
<td>: <?= htmlspecialchars($penyebab) ?></td>
</tr>

<tr>
<td>Tempat Pemakaman</td>
<td>: <?= htmlspecialchars($tempat_pemakaman) ?></td>
</tr>

</table>

<p>
Surat keterangan ini dibuat berdasarkan keterangan pelapor :
</p>

<table class="data">

<tr>
<td>Nama Lengkap</td>
<td>: <?= htmlspecialchars($nama_pelapor) ?></td>
</tr>

<tr>
<td>Jenis Kelamin</td>
<td>: <?= htmlspecialchars($jk_pelapor) ?></td>
</tr>

<tr>
<td>Tempat / Tgl Lahir</td>
<td>: <?= htmlspecialchars($tempat_lahir_p) ?>, <?= date('d F Y',strtotime($tgl_lahir_p)) ?></td>
</tr>

<tr>
<td>Agama</td>
<td>: <?= htmlspecialchars($agama_pelapor) ?></td>
</tr>

<tr>
<td>Alamat Lengkap</td>
<td>: <?= htmlspecialchars($alamat_pelapor) ?></td>
</tr>

<tr>
<td>Hubungan Keluarga</td>
<td>: <?= htmlspecialchars($hubungan) ?></td>
</tr>

</table>

<p>
Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
</p>

<table class="ttd">

<tr>

<td style="width:50%"></td>

<td style="text-align:center">

Bumiayu, <?= date('d F Y',strtotime($tanggal_surat)) ?><br>
Pj. Kepala Pekon Bumiayu<br><br>

<!-- QR CODE -->
<?php if (!empty($data['qr_code'])): ?>
    <img src="../../../assets/qrcode/generate/<?= $data['qr_code'] ?>" width="90"><br>
    <small>Scan QR untuk verifikasi</small>
<?php else: ?>
    <br><br><br><br>
<?php endif; ?>

<br>
<strong><u><?= htmlspecialchars($pj_pejabat) ?></u></strong><br>
NIP. <?= htmlspecialchars($nip_pejabat) ?>

</td>

</tr>

</table>

<script>

window.print();

setTimeout(function(){
window.close();
},1000);

</script>

</body>
</html>