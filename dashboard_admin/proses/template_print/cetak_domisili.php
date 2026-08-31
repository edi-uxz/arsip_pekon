<?php
// JANGAN sertakan koneksi.php di sini karena sudah ada di cetak_surat.php
// Variabel $data dan $isi sudah otomatis tersedia dari file induk (cetak_surat.php)

/*------------------  Ambil & decode isian  ------------------*/
// Gunakan variabel $isi yang sudah didecode di file induk
$nama          = $isi['nama']           ?? '-';
$tempat_lahir  = $isi['tempat_lahir']   ?? '-';
$tgl_lahir     = $isi['tgl_lahir']      ?? date('Y-m-d'); 
$jenis_kelamin = $isi['jenis_kelamin']  ?? '-';
$pekerjaan     = $isi['pekerjaan']      ?? '-';
$agama         = $isi['agama']          ?? '-';
$status_nikah  = $isi['status']         ?? '-';
$warga_negara  = $isi['warga_negara']   ?? '-';
$alamat        = $isi['alamat']         ?? '-';

/*------------------  Format Tanggal Indonesia  ------------------*/
if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal) {
        if (!$tanggal || $tanggal == '0000-00-00') return "-";
        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $pecahkan = explode('-', $tanggal);
        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Surat Keterangan Domisili</title>
<style>
    @page { size: A4; margin: 1.5cm; }
    body { font-family: "Times New Roman", Times, serif; margin:0; line-height: 1.5; font-size: 12pt; }
    .head { text-align:center; font-weight:bold; border-bottom: 2px solid black; padding-bottom: 5px; margin-bottom: 20px; }
    .judul { text-align:center; margin-bottom: 20px; }
    .judul u { font-weight:bold; font-size:14pt; }
    table.info { width: 100%; margin-left: 20px; }
    table.info td { padding: 2px 0; vertical-align: top; }
    .isi { text-align: justify; }
    .ttd { margin-top: 40px; float: right; width: 250px; text-align: center; }
    .spacer { height: 70px; }
</style>
</head>
<body>

<div class="head">
    PEMERINTAH KABUPATEN PRINGSEWU <br>
    KECAMATAN PARDASUKA <br>
    PEKON SUKOREJO
</div>

<div class="judul">
    <u>SURAT KETERANGAN DOMISILI</u><br>
    No. <?= $data['id']; ?>/SKD/<?= date('Y'); ?>
</div>

<div class="isi">
    <p>Yang bertanda tangan di bawah ini Kepala Pekon Sukorejo, Kecamatan Pardasuka, Kabupaten Pringsewu, menerangkan bahwa:</p>

    <table class="info">
        <tr><td width="180">Nama Lengkap</td><td>: <strong><?= strtoupper(htmlspecialchars($nama)); ?></strong></td></tr>
        <tr><td>Tempat, Tgl Lahir</td><td>: <?= htmlspecialchars($tempat_lahir).', '.tgl_indo($tgl_lahir); ?></td></tr>
        <tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jenis_kelamin); ?></td></tr>
        <tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($pekerjaan); ?></td></tr>
        <tr><td>Agama</td><td>: <?= htmlspecialchars($agama); ?></td></tr>
        <tr><td>Status Perkawinan</td><td>: <?= htmlspecialchars($status_nikah); ?></td></tr>
        <tr><td>Warga Negara</td><td>: <?= htmlspecialchars($warga_negara); ?></td></tr>
        <tr><td>Alamat</td><td>: <?= htmlspecialchars($alamat); ?></td></tr>
    </table>

    <p>Benar bahwa orang tersebut di atas adalah warga yang berdomisili di Pekon Sukorejo, Kecamatan Pardasuka, Kabupaten Pringsewu.</p>

    <p>Demikian surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
</div>

<div class="ttd">
    Sukorejo, <?= tgl_indo(date('Y-m-d')); ?><br>
    Kepala Pekon Sukorejo<br><br>

    <!-- QR CODE -->
    <?php if (!empty($data['qr_code'])): ?>
        <img src="../../assets/qrcode/generate/<?= $data['qr_code']; ?>" width="90"><br>
        <small>Scan QR untuk verifikasi</small>
    <?php else: ?>
        <div class="spacer"></div>
    <?php endif; ?>

    <br><br>
    <u><strong>NAMA PEJABAT</strong></u><br>
    NIP. ............................
</div>

<script>
    window.print();
    window.onafterprint = function() {
        window.close();
    };
</script>

</body>
</html>