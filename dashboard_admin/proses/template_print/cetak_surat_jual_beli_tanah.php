<?php
/****************************************
 * CETAK SURAT JUAL BELI TANAH
 ****************************************/

session_start();

$id = $_GET['id'] ?? 0;

$qs = mysqli_query($conn,"SELECT * FROM pengajuan_surat WHERE id='$id'");
$data = mysqli_fetch_assoc($qs);

if(!$data){
    echo "Data pengajuan tidak ditemukan.";
    exit;
}

$isi = json_decode($data['data_isi'], true);

/* ================= DATA PIHAK ================= */

$p1_nama          = $isi['p1_nama'] ?? '-';
$p1_tempat_lahir  = $isi['p1_tempat_lahir'] ?? '-';
$p1_tgl_lahir     = $isi['p1_tgl_lahir'] ?? '-';
$p1_pekerjaan     = $isi['p1_pekerjaan'] ?? '-';
$p1_alamat        = $isi['p1_alamat'] ?? '-';

$p2_nama          = $isi['p2_nama'] ?? '-';
$p2_tempat_lahir  = $isi['p2_tempat_lahir'] ?? '-';
$p2_tgl_lahir     = $isi['p2_tgl_lahir'] ?? '-';
$p2_pekerjaan     = $isi['p2_pekerjaan'] ?? '-';
$p2_alamat        = $isi['p2_alamat'] ?? '-';

$lokasi_tanah     = $isi['lokasi_tanah'] ?? '-';
$luas_tanah       = $isi['luas_tanah'] ?? '-';
$harga_tanah      = $isi['harga_tanah'] ?? '-';

$batas_utara      = $isi['batas_utara'] ?? '-';
$batas_selatan    = $isi['batas_selatan'] ?? '-';
$batas_barat      = $isi['batas_barat'] ?? '-';
$batas_timur      = $isi['batas_timur'] ?? '-';

$saksi1           = $isi['saksi1'] ?? '-';
$saksi2           = $isi['saksi2'] ?? '-';
$saksi3           = $isi['saksi3'] ?? '-';

/* ================= NOMOR SURAT ================= */

$nomor_surat = $data['nomor_surat'] ?? '-';

/* ================= ADMIN LOGIN ================= */

$pj_pejabat  = $_SESSION['nama'] ?? 'PEJABAT';
$nip_pejabat = $_SESSION['nip'] ?? '-';

/* ================= TANGGAL ================= */

$tanggal_surat = date('Y-m-d');

/* ================= UPDATE STATUS PRINT ================= */

mysqli_query($conn,"UPDATE pengajuan_surat SET is_printed=1 WHERE id='$id'");

/* ================= FORMAT TANGGAL ================= */

function tgl_indo($tanggal){

    if(!$tanggal) return "-";

    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $pecah = explode('-', $tanggal);

    return $pecah[2].' '.$bulan[(int)$pecah[1]].' '.$pecah[0];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Surat Jual Beli Tanah</title>

<style>

@page{
    size:A4;
    margin:2cm;
}

body{
    font-family:"Times New Roman", serif;
    font-size:12pt;
    margin:0;
    line-height:1.5;
}

.container{
    width:210mm;
    min-height:297mm;
    margin:auto;
}

.kop{
    text-align:center;
    line-height:1.3;
    font-weight:bold;
}

.kop-line{
    border-top:3px solid #000;
    border-bottom:1px solid #000;
    height:5px;
    margin:10px 0 20px 0;
}

.judul{
    text-align:center;
    margin-bottom:20px;
    font-weight:bold;
    text-decoration:underline;
}

p{
    text-align:justify;
}

table{
    border-collapse:collapse;
    width:100%;
    page-break-inside:avoid;
}

tr{
    page-break-inside:avoid;
}

td{
    padding:3px 0;
    vertical-align:top;
}

.label{
    width:220px;
}

.ttd{
    margin-top:40px;
    page-break-inside:avoid;
}

.ttd td{
    text-align:center;
    padding-top:60px;
}

.saksi{
    margin-top:25px;
    page-break-inside:avoid;
}

</style>
</head>

<body>

<div class="container">

<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
<span style="font-size:16pt;">PEKON BUMIAYU</span><br>
<small>Alamat : Jln. Veteran No.001 Pekon Bumiayu Kecamatan Pringsewu</small>
</div>

<div class="kop-line"></div>

<div class="judul">
SURAT KETERANGAN JUAL BELI TANAH<br>
<span style="font-weight:normal;font-size:11pt;">
Nomor : <?= htmlspecialchars($nomor_surat) ?>
</span>
</div>

<p>Yang bertanda tangan di bawah ini:</p>

<table>
<tr><td colspan="2"><strong>I. PIHAK PERTAMA (PENJUAL)</strong></td></tr>
<tr><td class="label">Nama Lengkap</td><td>: <?= htmlspecialchars($p1_nama) ?></td></tr>
<tr><td class="label">Tempat / Tgl Lahir</td><td>: <?= htmlspecialchars($p1_tempat_lahir) ?>, <?= tgl_indo($p1_tgl_lahir) ?></td></tr>
<tr><td class="label">Pekerjaan</td><td>: <?= htmlspecialchars($p1_pekerjaan) ?></td></tr>
<tr><td class="label">Alamat</td><td>: <?= htmlspecialchars($p1_alamat) ?></td></tr>
</table>

<br>

<table>
<tr><td colspan="2"><strong>II. PIHAK KEDUA (PEMBELI)</strong></td></tr>
<tr><td class="label">Nama Lengkap</td><td>: <?= htmlspecialchars($p2_nama) ?></td></tr>
<tr><td class="label">Tempat / Tgl Lahir</td><td>: <?= htmlspecialchars($p2_tempat_lahir) ?>, <?= tgl_indo($p2_tgl_lahir) ?></td></tr>
<tr><td class="label">Pekerjaan</td><td>: <?= htmlspecialchars($p2_pekerjaan) ?></td></tr>
<tr><td class="label">Alamat</td><td>: <?= htmlspecialchars($p2_alamat) ?></td></tr>
</table>

<p>
Pada hari ini kedua belah pihak sepakat melakukan jual beli sebidang tanah dengan rincian sebagai berikut:
</p>

<table>
<tr><td class="label">Luas Tanah</td><td>: <?= htmlspecialchars($luas_tanah) ?> m²</td></tr>
<tr><td class="label">Lokasi Tanah</td><td>: <?= htmlspecialchars($lokasi_tanah) ?></td></tr>
<tr><td class="label">Harga Tanah</td><td>: Rp <?= number_format((float)str_replace('.','',$harga_tanah),0,',','.') ?></td></tr>
<tr><td class="label">Batas Utara</td><td>: <?= htmlspecialchars($batas_utara) ?></td></tr>
<tr><td class="label">Batas Selatan</td><td>: <?= htmlspecialchars($batas_selatan) ?></td></tr>
<tr><td class="label">Batas Barat</td><td>: <?= htmlspecialchars($batas_barat) ?></td></tr>
<tr><td class="label">Batas Timur</td><td>: <?= htmlspecialchars($batas_timur) ?></td></tr>
</table>

<p>
Demikian surat keterangan ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.
</p>

<table class="ttd">
<tr>
<td>
Pihak Kedua / Pembeli<br><br><br><br>
<u><?= htmlspecialchars($p2_nama) ?></u>
</td>

<td>
Pihak Pertama / Penjual<br><br><br><br>
<u><?= htmlspecialchars($p1_nama) ?></u>
</td>
</tr>

<tr>
<td colspan="2" style="position:relative;height:220px;">

    <div style="position:absolute;right:0;text-align:center;width:250px;">

        Mengetahui,<br>
        Kepala Pekon Bumiayu<br><br>

        <!-- QR CODE -->
        <?php if (!empty($data['qr_code'])): ?>
            <img src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
                 style="width:140px; height:140px;"><br>
            <small style="font-size:10px;">
                Disahkan secara elektronik
            </small>
        <?php else: ?>
            <br><br><br><br>
        <?php endif; ?>

        <br><br>

        <strong><u><?= strtoupper(htmlspecialchars($pj_pejabat)) ?></u></strong><br>
        NIP. <?= htmlspecialchars($nip_pejabat) ?>

    </div>

</td>
</tr>
</table>

<div class="saksi">

<strong>Saksi-saksi:</strong>

<table>
<tr><td>1. <?= htmlspecialchars($saksi1) ?></td></tr>
<tr><td>2. <?= htmlspecialchars($saksi2) ?></td></tr>
<tr><td>3. <?= htmlspecialchars($saksi3) ?></td></tr>
</table>

</div>

</div>

<script>

window.print();

window.onafterprint = function(){
    window.close();
}

</script>

</body>
</html>