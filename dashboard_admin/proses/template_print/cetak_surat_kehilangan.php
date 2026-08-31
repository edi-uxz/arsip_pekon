<?php
include __DIR__ . '/../../../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ================= AMBIL DATA ================= */
$id = $_GET['id'] ?? 0;
$q = mysqli_query($conn, "SELECT * FROM pengajuan_surat WHERE id=$id");
$data = mysqli_fetch_assoc($q);

if (!$data) die("Data tidak ditemukan");
if (strtolower($data['status']) !== 'disetujui') die("Belum disetujui");

/* ================= JSON ================= */
$isi = json_decode($data['data_isi'], true) ?? [];

/* ================= HELPER ================= */
function get($k){
    global $isi;
    return htmlspecialchars($isi[$k] ?? '-');
}

/* ================= TANGGAL ================= */
function tgl_indo($tgl){
    if(!$tgl) return '-';
    $bulan = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $t = strtotime($tgl);
    return date('d',$t).' '.$bulan[(int)date('m',$t)].' '.date('Y',$t);
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Surat Kehilangan</title>
<style>
body{
    font-family:"Times New Roman";
    font-size:12pt;
    line-height:1.5;
}
.kop{text-align:center;font-weight:bold;}
hr{border:0;border-top:2px solid black;margin:5px 0 15px;}
table td{padding:3px;vertical-align:top;}
</style>
</head>

<body>

<!-- KOP -->
<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
PEKON BUMIAYU<br>
<span style="font-size:10pt;font-weight:normal;">
Jln Veteran No.001 Pekon Bumiayu Kecamatan Pringsewu 35373
</span>
</div>

<hr>

<!-- JUDUL -->
<div style="text-align:center;margin-bottom:20px;">
<strong><u>SURAT KETERANGAN KEHILANGAN</u></strong><br>
Nomor : <?= $data['nomor_surat'] ?? '-' ?>
</div>

<p>
Yang bertanda tangan di bawah ini Kepala Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu menerangkan bahwa :
</p>

<!-- DATA -->
<table style="margin-left:60px;">
<tr><td width="200">Nama</td><td>: <?= get('nama_pelapor') ?></td></tr>
<tr>
<td>Tempat Tgl/Lahir</td>
<td>: <?= get('tempat_lahir_p') ?>, <?= tgl_indo($isi['tgl_lahir_p'] ?? '') ?></td>
</tr>
<tr><td>NIK</td><td>: <?= get('nik_pelapor') ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= get('pekerjaan_p') ?></td></tr>
<tr><td>Alamat</td><td>: <?= get('alamat_p') ?></td></tr>
</table>

<br>

<!-- ISI SURAT -->
<p style="text-align:justify;">
Menerangkan bahwa benar adanya orang tersebut diatas telah kehilangan 
<strong><?= get('barang_hilang') ?></strong>, 
menurut keterangan yang bersangkutan kejadian tersebut terjadi di 
<?= get('lokasi_hilang') ?> pada tanggal <?= tgl_indo($isi['tgl_hilang'] ?? '') ?> 
dengan alasan <?= get('alasan_hilang') ?>.
</p>

<br>

<p>
Demikian Surat Keterangan ini dibuat, agar dapat ditindak lanjuti oleh pihak yang berwajib.
</p>

<br><br><br>

<!-- TTD -->
<table width="100%">
<tr>
<td width="60%"></td>
<td style="text-align:center;">
Dibuat di Bumiayu<br>
Pada Tanggal : <?= tgl_indo($isi['tanggal_surat'] ?? date('Y-m-d')) ?><br><br>

Kepala Pekon Bumiayu<br><br>

<!-- QR CODE -->
<?php if (!empty($data['qr_code'])): ?>
    <img 
        src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
        width="140"
        style="image-rendering: crisp-edges;"
    ><br>
    <small style="font-size:10px;">
        Scan QR untuk verifikasi keaslian surat
    </small>
<?php else: ?>
    <div style="height:140px;"></div>
<?php endif; ?>

<br>
<strong><?= strtoupper($isi['pj_pejabat'] ?? 'KEPALA PEKON') ?></strong>
</td>
</tr>
</table>

<script>
window.print();
setTimeout(()=>window.close(),1200);
</script>

</body>
</html>