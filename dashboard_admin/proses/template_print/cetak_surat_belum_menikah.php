<?php
session_start();

/* data surat sudah dikirim dari cetak_surat.php */
$nama            = $isi['nama'] ?? '';
$tempat_lahir    = $isi['tempat_lahir'] ?? '';
$tgl_lahir       = $isi['tgl_lahir'] ?? '';
$jk              = $isi['jk'] ?? '';
$nik             = $isi['nik'] ?? '';
$agama           = $isi['agama'] ?? '';
$kewarganegaraan = $isi['kewarganegaraan'] ?? '';
$pekerjaan       = $isi['pekerjaan'] ?? '';
$status          = $isi['status'] ?? '';
$alamat          = $isi['alamat'] ?? '';
$keperluan       = $isi['keperluan'] ?? '';
$berlaku         = $isi['berlaku'] ?? '';

/* ================= NOMOR SURAT ================= */
$nomor_surat = $data['nomor_surat'] ?? '-';

/* ================= ADMIN LOGIN ================= */
$pj_pejabat  = $_SESSION['nama'] ?? 'PEJABAT';
$nip_pejabat = $_SESSION['nip'] ?? '-';

/* ================= FORMAT TANGGAL ================= */

function tgl_indo($tanggal){

    if(!$tanggal) return "";

    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $pecah = explode('-', $tanggal);

    return $pecah[2].' '.$bulan[(int)$pecah[1]].' '.$pecah[0];
}

$tanggal_surat = date('Y-m-d');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Cetak Surat Belum Menikah</title>

<style>

@page{
size: A4 portrait;
margin: 2cm;
}

body{
font-family:"Times New Roman";
font-size:12pt;
margin:0;
background:white;
}

.a4-preview{
width:210mm;
min-height:297mm;
padding:20mm;
margin:auto;
box-sizing:border-box;
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

.judul-surat{
text-align:center;
margin-bottom:25px;
font-weight:bold;
text-decoration:underline;
}

table{
border-collapse:collapse;
}

td{
padding:3px 0;
vertical-align:top;
}

</style>
</head>

<body>

<div class="a4-preview">

<div class="kop">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
<span style="font-size:16pt;">PEKON BUMIAYU</span><br>
<small>Alamat : Jln. Veteran No.001 Pekon Bumiayu Kecamatan Pringsewu</small>
</div>

<div class="kop-line"></div>

<div class="judul-surat">
SURAT KETERANGAN BELUM MENIKAH<br>
<span style="font-weight:normal;font-size:11pt;">
Nomor : <?= htmlspecialchars($nomor_surat) ?>
</span>
</div>

<p style="text-align:justify">
Yang bertanda tangan dibawah ini Kepala Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, menerangkan dengan sebenarnya bahwa :
</p>

<table style="margin-left:40px">
<tr><td width="220">Nama Lengkap</td><td>: <?= htmlspecialchars($nama) ?></td></tr>
<tr><td>Tempat/Tanggal Lahir</td><td>: <?= htmlspecialchars($tempat_lahir) ?>, <?= tgl_indo($tgl_lahir) ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jk) ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($nik) ?></td></tr>
<tr><td>Agama</td><td>: <?= htmlspecialchars($agama) ?></td></tr>
<tr><td>Kewarganegaraan</td><td>: <?= htmlspecialchars($kewarganegaraan) ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($pekerjaan) ?></td></tr>
<tr><td>Status</td><td>: <?= htmlspecialchars($status) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($alamat) ?></td></tr>
</table>

<p style="text-align:justify;margin-top:20px">
Adalah benar orang tersebut diatas warga Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, dan berdasarkan data yang ada di Kantor Pekon Bumiayu serta keterangan yang bersangkutan hingga saat ini belum pernah menikah / belum menikah dan surat keterangan ini diberikan untuk :
</p>

<table style="margin-left:40px">
<tr><td width="220">Keperluan</td><td>: <?= htmlspecialchars($keperluan) ?></td></tr>
<tr><td>Berlaku</td><td>: <?= htmlspecialchars($berlaku) ?></td></tr>
</table>

<p style="margin-top:20px">
Demikian surat keterangan ini diberikan untuk dapat dipergunakan seperlunya.
</p>

<div style="position:relative;margin-top:80px;min-height:200px;">

    <div style="position:absolute;right:0;text-align:center;width:250px;">

        Bumiayu, <?= tgl_indo($tanggal_surat) ?><br>
        Kepala Pekon Bumiayu<br><br>

        <!-- QR CODE -->
        <?php if (!empty($data['qr_code'])): ?>
            <img src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
                 style="width:140px; height:140px;"><br>
            <small style="font-size:10px;">
                Dokumen ini ditandatangani secara elektronik
            </small>
        <?php else: ?>
            <br><br><br><br>
        <?php endif; ?>

        <br><br>

        <strong><u><?= strtoupper(htmlspecialchars($pj_pejabat)) ?></u></strong><br>
        NIP. <?= htmlspecialchars($nip_pejabat) ?>

    </div>

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