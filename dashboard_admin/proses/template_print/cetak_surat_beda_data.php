<?php
// Tidak perlu koneksi.php karena sudah dari cetak_surat.php

/* ================= AMBIL DATA ================= */
$isi = $isi ?? [];

/* ================= AMBIL FIELD ================= */
function getv($key){
    global $isi;
    return htmlspecialchars($isi[$key] ?? '-');
}

/* ================= FORMAT TANGGAL ================= */
if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal){
        if(!$tanggal) return '';
        $bulan = [
            1=>'Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'
        ];
        $t = strtotime($tanggal);
        return date('d',$t).' '.$bulan[(int)date('m',$t)].' '.date('Y',$t);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Surat Keterangan Beda Data</title>

<style>
@page { size: A4; margin: 1.2cm; }

body {
    font-family: "Times New Roman", serif;
    font-size: 11pt; /* DIPERKECIL */
    line-height: 1.3; /* DIPADATKAN */
    margin: 0;
}

.head {
    text-align:center;
    font-weight:bold;
    border-bottom:2px solid black;
    padding-bottom:4px;
    margin-bottom:10px; /* DIPERKECIL */
}

.judul {
    text-align:center;
    margin-bottom:10px; /* DIPERKECIL */
}

.judul u {
    font-size:13pt;
    font-weight:bold;
}

table.info {
    width:100%;
    margin-left:10px; /* DIPERKECIL */
}

table.info td {
    padding:1px 0; /* DIPERKECIL */
    vertical-align:top;
}

.isi p {
    margin:6px 0; /* DIPERKECIL */
    text-align:justify;
}

.ttd {
    margin-top:20px; /* DIPERKECIL */
    float:right;
    width:230px;
    text-align:center;
}

.spacer {
    height:70px; /* SESUAI QR */
}

@media print {
    body {
        margin: 0;
    }
}
</style>
</head>

<body>

<!-- KOP -->
<div class="head">
    PEMERINTAH KABUPATEN PRINGSEWU <br>
    KECAMATAN PARDASUKA <br>
    PEKON SUKOREJO
</div>

<!-- JUDUL -->
<div class="judul">
    <u>SURAT KETERANGAN BEDA DATA</u><br>
    No. <?= $data['id']; ?>/SKBD/<?= date('Y'); ?>
</div>

<div class="isi">

<p>
Yang bertanda tangan di bawah ini Kepala Pekon Sukorejo, Kecamatan Pardasuka,
Kabupaten Pringsewu, menerangkan bahwa:
</p>

<!-- DATA KK -->
<p><strong>Data sesuai Kartu Keluarga:</strong></p>
<table class="info">
<tr><td width="180">Nama</td><td>: <?= getv('nama_kk') ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= getv('jk_kk') ?></td></tr>
<tr><td>Tempat, Tgl Lahir</td><td>: <?= getv('ttl_kk') ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= getv('pekerjaan_kk') ?></td></tr>
<tr><td>NIK</td><td>: <?= getv('nik_kk') ?></td></tr>
<tr><td>Alamat</td><td>: <?= getv('alamat_kk') ?></td></tr>
</table>

<br>

<!-- DATA PEMBANDING -->
<p><strong>Data pembanding yang benar:</strong></p>
<table class="info">
<tr><td width="180">Nama</td><td>: <?= getv('nama_beda') ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= getv('jk_beda') ?></td></tr>
<tr><td>Tempat, Tgl Lahir</td><td>: <?= getv('ttl_beda') ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= getv('pekerjaan_beda') ?></td></tr>
<tr><td>NIK</td><td>: <?= getv('nik_beda') ?></td></tr>
</table>

<br>

<!-- NARASI -->
<p>
Berdasarkan data tersebut di atas, benar terdapat perbedaan data pada identitas yang bersangkutan, yaitu:
</p>

<p>
<?= getv('keterangan_beda') ?>
</p>

<p>
Yang bersangkutan merupakan warga Pekon Sukorejo yang berdomisili di Dusun <?= getv('dusun') ?> RT <?= getv('rt') ?> RW <?= getv('rw') ?>.
</p>

<p>
Demikian surat keterangan ini dibuat dengan sebenarnya agar dapat dipergunakan sebagaimana mestinya.
</p>

</div>

<!-- TTD + QR -->
<div class="ttd">

Sukorejo, <?= tgl_indo(date('Y-m-d')) ?><br>
Kepala Pekon Sukorejo<br><br>

<!-- QR CODE -->
<?php if (!empty($data['qr_code'])): ?>
    <img src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
         width="130"
         style="image-rendering: crisp-edges;"><br>
    <small>Scan QR untuk verifikasi</small>
<?php else: ?>
    <div class="spacer"></div>
<?php endif; ?>

<br><br>

<u><strong><?= strtoupper($isi['pj_pejabat'] ?? 'NAMA PEJABAT'); ?></strong></u><br>
NIP. <?= $isi['nip_pejabat'] ?? '.....................'; ?>

</div>

<script>
window.print();
window.onafterprint = function() {
    window.close();
};
</script>

</body>
</html>