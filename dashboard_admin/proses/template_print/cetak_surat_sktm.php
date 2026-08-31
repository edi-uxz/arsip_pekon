<?php
session_start();

/* ================= DATA ================= */
$nama_lengkap = $isi['nama_lengkap'] ?? '-';
$nik          = $isi['nik'] ?? '-';
$tempat_lahir = $isi['tempat_lahir'] ?? '-';
$tgl_lahir    = $isi['tgl_lahir'] ?? '-';
$agama        = $isi['agama'] ?? '-';
$pekerjaan    = $isi['pekerjaan'] ?? '-';
$alamat       = $isi['alamat'] ?? '-';
$keperluan    = $isi['keperluan'] ?? '-';

/* ================= NOMOR SURAT ================= */
$nomor_surat = $data['nomor_surat'] ?? '';

/* ================= PEJABAT ================= */
$pj_pejabat  = $_SESSION['nama'] ?? 'KEPALA PEKON';
$nip_pejabat = $_SESSION['nip'] ?? '-';

/* ================= TANGGAL ================= */
$tanggal_surat = date('Y-m-d');

/* ================= FORMAT TANGGAL ================= */
function tgl_indo($tanggal){
    if(!$tanggal || $tanggal == '-') return "-";

    $bulan=[
    1=>'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $pecah=explode('-', $tanggal);

    return $pecah[2].' '.$bulan[(int)$pecah[1]].' '.$pecah[0];
}

/* ================= DETEKSI JENIS ================= */
$jenis_kode = "SKTM";

if (stripos($keperluan, 'bpjs') !== false) {
    $jenis_kode = "SKTM-BPJS";
} elseif (stripos($keperluan, 'sekolah') !== false) {
    $jenis_kode = "SKTM-SKL";
}

/* ================= JUDUL ================= */
$judul = "SURAT KETERANGAN TIDAK MAMPU";

if ($jenis_kode == "SKTM-BPJS") {
    $judul .= " (BPJS)";
} elseif ($jenis_kode == "SKTM-SKL") {
    $judul .= " (SEKOLAH)";
}

/* ================= NOMOR OTOMATIS (JIKA KOSONG) ================= */
if (empty($nomor_surat) || $nomor_surat == '-') {
    $nomor_surat = $data['id']."/".$jenis_kode."/".date('Y');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $judul ?></title>

<style>
@page{
    size:A4;
    margin:2cm;
}

body{
    font-family:"Times New Roman";
    font-size:12pt;
    margin:0;
    line-height:1.5;
}

.kop{
    text-align:center;
    font-weight:bold;
    line-height:1.3;
}

.kop-line{
    border-top:3px solid black;
    border-bottom:1px solid black;
    height:5px;
    margin:10px 0 20px 0;
}

.judul{
    text-align:center;
    margin-bottom:20px;
}

table{
    border-collapse:collapse;
}

td{
    padding:3px 0;
    vertical-align:top;
}

.label{
    width:220px;
}

.ttd{
    position:relative;
    margin-top:80px;
    min-height:200px;
}
.ttd-kanan{
    position:absolute;
    right:0;
    text-align:center;
    width:250px;
}

@media print{
    body{
        margin:0;
    }
}
</style>
</head>

<body>

<!-- ================= KOP ================= -->
<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
<span style="font-size:16pt;">PEKON BUMIAYU</span><br>
<small>Jln. Veteran No 001 Pekon Bumiayu Kecamatan Pringsewu 35373</small>
</div>

<div class="kop-line"></div>

<!-- ================= JUDUL ================= -->
<div class="judul">
<strong><u><?= $judul ?></u></strong><br>
Nomor : <?= htmlspecialchars($nomor_surat) ?>
</div>

<p>Diberikan kepada :</p>

<!-- ================= DATA ================= -->
<table style="margin-left:40px">
<tr><td class="label">Nama Lengkap</td><td>: <?= htmlspecialchars($nama_lengkap) ?></td></tr>
<tr><td class="label">NIK</td><td>: <?= htmlspecialchars($nik) ?></td></tr>
<tr><td class="label">Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($tempat_lahir) ?>, <?= tgl_indo($tgl_lahir) ?></td></tr>
<tr><td class="label">Agama</td><td>: <?= htmlspecialchars($agama) ?></td></tr>
<tr><td class="label">Pekerjaan</td><td>: <?= htmlspecialchars($pekerjaan) ?></td></tr>
<tr><td class="label">Alamat</td><td>: <?= htmlspecialchars($alamat) ?></td></tr>
</table>

<br>

<!-- ================= KETERANGAN ================= -->
<table style="margin-left:40px">
<tr>
<td class="label">Keterangan</td>
<td>
: Orang tersebut di atas benar keluarga <b>TIDAK MAMPU</b> 
dan surat ini digunakan untuk 
<b><?= htmlspecialchars($keperluan) ?></b>.
</td>
</tr>
</table>

<p style="margin-top:20px">
Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.
</p>

<!-- ================= TTD ================= -->
<div class="ttd">
<div class="ttd-kanan">

Bumiayu, <?= tgl_indo($tanggal_surat) ?><br>
Kepala Pekon Bumiayu<br><br>

<!-- QR CODE -->
<?php if (!empty($data['qr_code'])): ?>
    <img src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
         width="130"
         style="image-rendering: crisp-edges;"><br>
    <small style="font-size:10px;">
        Scan QR untuk verifikasi
    </small>
<?php else: ?>
    <div style="height:120px;"></div>
<?php endif; ?>

<br>

<strong><u><?= strtoupper(htmlspecialchars($pj_pejabat)) ?></u></strong><br>
NIP. <?= htmlspecialchars($nip_pejabat) ?>

</div>
</div>

<!-- ================= PRINT ================= -->
<script>
window.print();
window.onafterprint = function(){
    window.close();
}
</script>

</body>
</html>