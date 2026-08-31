<?php
// Variabel $data dan $isi otomatis tersedia dari file induk cetak_surat.php

// Ambil data dari JSON $isi (disesuaikan dengan inputan form SKCK Anda)
$nama          = $isi['nama']           ?? '-';
$jenis_kelamin = $isi['jenis_kelamin']  ?? '-';
$tempat_lahir  = $isi['tempat_lahir']   ?? '-';
$tgl_lahir     = $isi['tgl_lahir']      ?? null;
$suku          = $isi['suku']           ?? 'Jawa / Indonesia';
$agama         = $isi['agama']          ?? '-';
$pekerjaan     = $isi['pekerjaan']      ?? '-';
$nik           = $isi['nik']            ?? '-';
$alamat        = $isi['alamat']         ?? '-';
$keperluan     = $isi['keperluan']      ?? 'PERSYARATAN MELAMAR PEKERJAAN';

// Pengaturan Masa Berlaku (Contoh: 3 bulan dari tanggal pengajuan)
$tgl_cetak     = $data['tanggal_pengajuan'] ?? date('Y-m-d');
$tgl_expired   = date('Y-m-d', strtotime('+3 month', strtotime($tgl_cetak)));

// Fungsi Format Tanggal Indonesia
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
    <title>Cetak SKCK - <?= htmlspecialchars($nama) ?></title>
    <style>
        @page { size: A4; margin: 1.2cm; }
        body { font-family: "Courier New", Courier, monospace; margin: 0; line-height: 1.2; font-size: 11pt; }
        .header { width: 100%; border-bottom: 2px solid black; padding-bottom: 5px; margin-bottom: 10px; }
        .header-left { width: 50%; text-align: left; font-weight: bold; font-size: 10pt; }
        .judul-container { text-align: center; margin-bottom: 15px; }
        .judul-container h3 { margin: 0; text-decoration: underline; font-size: 13pt; }
        .judul-container p { margin: 0; font-weight: bold; }
        .isi { text-align: justify; }
        table.data-diri { width: 100%; margin-left: 20px; margin-bottom: 10px; }
        table.data-diri td { vertical-align: top; padding: 2px 0; }
        .footer-table { width: 100%; margin-top: 30px; }
        .foto-box { width: 3cm; height: 4cm; border: 1px solid black; text-align: center; line-height: 4cm; font-size: 8pt; float: left; margin-left: 50px; }
        .ttd-box { float: right; width: 300px; text-align: center; }
        .spacer { height: 60px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            KEPOLISIAN DAERAH JAWA TENGAH<br>
            RESOR BREBES<br>
            SEKTOR BREBES<br>
            <span style="font-weight: normal; font-size: 9pt;">Jalan Veteran No. 2 Brebes 52212</span>
        </div>
    </div>

    <div class="judul-container">
        <h3>SURAT KETERANGAN CATATAN KEPOLISIAN</h3>
        <p>NO.POL. : SKCK / <?= $data['id'] ?> / <?= date('m/Y', strtotime($tgl_cetak)) ?> / Sek.Bbs</p>
    </div>

    <div class="isi">
        <p>Kepala Kepolisian Sektor Brebes dengan ini menerangkan bahwa :</p>
        
        <table class="data-diri">
            <tr><td width="30%">Nama Lengkap</td><td width="2%">:</td><td><strong><?= strtoupper(htmlspecialchars($nama)) ?></strong></td></tr>
            <tr><td>Jenis Kelamin</td><td>:</td><td><?= htmlspecialchars($jenis_kelamin) ?></td></tr>
            <tr><td>Tempat/Tgl. Lahir</td><td>:</td><td><?= htmlspecialchars($tempat_lahir) ?>, <?= tgl_indo($tgl_lahir) ?></td></tr>
            <tr><td>Suku / Bangsa</td><td>:</td><td><?= htmlspecialchars($suku) ?></td></tr>
            <tr><td>Agama</td><td>:</td><td><?= htmlspecialchars($agama) ?></td></tr>
            <tr><td>Pekerjaan</td><td>:</td><td><?= htmlspecialchars($pekerjaan) ?></td></tr>
            <tr><td>Nomor KTP</td><td>:</td><td><?= htmlspecialchars($nik) ?></td></tr>
            <tr><td>Alamat</td><td>:</td><td><?= htmlspecialchars($alamat) ?></td></tr>
        </table>

        <p>Setelah diadakan penelitian hingga saat dikeluarkan keterangan ini yang didasarkan kepada:</p>
        <ol style="margin-top: -10px;">
            <li>Catatan Kriminal yang ada.</li>
            <li>Surat keterangan dari aparat Desa / Kelurahan</li>
            <li>Daftar pelaku/anggota organisasi atau gerakan terlarang</li>
        </ol>

        <p>Maka yang bersangkutan : <strong>Merupakan orang yang tidak tercatat dalam tindak kejahatan maupun berperilaku menyimpang dari norma-norma sosial yang berlaku di masyarakat.</strong></p>

        <table class="data-diri">
            <tr><td width="35%">Keterangan ini diberikan untuk</td><td width="2%">:</td><td><?= strtoupper(htmlspecialchars($keperluan)) ?></td></tr>
            <tr><td>Berlaku dari tanggal</td><td>:</td><td><?= tgl_indo($tgl_cetak) ?></td></tr>
            <tr><td>Sampai dengan tanggal</td><td>:</td><td><?= tgl_indo($tgl_expired) ?></td></tr>
        </table>

        <p>Demikianlah surat keterangan ini kami buat dengan sebenar-benarnya, agar yang berwajib dapat mengetahui dan menjadikan bahan periksa seperlunya.</p>
    </div>

    <div class="footer-table">
        <div class="foto-box">FOTO 4X6</div>
        
        <div class="ttd-box">
    Dikeluarkan di : Brebes<br>
    Pada tanggal   : <?= tgl_indo($tgl_cetak) ?><br>
    <p style="font-weight: bold; margin-bottom: 5px;">KEPALA KEPOLISISAN SEKTOR BREBES</p>

    <!-- QR CODE -->
    <?php if (!empty($data['qr_code'])): ?>
        <img src="../../assets/qrcode/generate/<?= $data['qr_code']; ?>" width="80"><br>
        <small>Scan QR untuk verifikasi</small>
    <?php else: ?>
        <div class="spacer"></div>
    <?php endif; ?>

    <br>
    <p style="text-decoration: underline; font-weight: bold; margin-bottom: 0;">NAMA KAPOLSEK ANDA</p>
    <p style="margin-top: 0;">PANGKAT / NRP</p>
</div>
    </div>

    <script>
        window.print();
        window.onafterprint = function() { window.close(); };
    </script>
</body>
</html>