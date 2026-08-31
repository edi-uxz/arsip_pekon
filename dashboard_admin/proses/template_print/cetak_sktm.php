<?php
session_start();

$id = $_GET['id'] ?? 0;

$qs = mysqli_query($conn,"SELECT * FROM pengajuan_surat WHERE id='$id'");
$data = mysqli_fetch_assoc($qs);

if(!$data){
    echo "Data pengajuan tidak ditemukan";
    exit;
}

$isi = json_decode($data['data_isi'], true);

/* ================= DATA ================= */
$nama_meninggal   = $isi['nama_meninggal'] ?? '-';
$nik_meninggal    = $isi['nik_meninggal'] ?? '-';
$tempat_lahir_m   = $isi['tempat_lahir_m'] ?? '-';
$tgl_lahir_m      = $isi['tgl_lahir_m'] ?? '-';
$jenis_kelamin_m  = $isi['jenis_kelamin_m'] ?? '-';
$alamat_meninggal = $isi['alamat_meninggal'] ?? '-';

$hari_meninggal   = $isi['hari_meninggal'] ?? '-';
$tgl_meninggal    = $isi['tgl_meninggal'] ?? '-';
$penyebab         = $isi['penyebab'] ?? '-';
$tempat_pemakaman = $isi['tempat_pemakaman'] ?? '-';

$nama_pelapor     = $isi['nama_pelapor'] ?? '-';
$jk_pelapor       = $isi['jk_pelapor'] ?? '-';
$tempat_lahir_p   = $isi['tempat_lahir_p'] ?? '-';
$tgl_lahir_p      = $isi['tgl_lahir_p'] ?? '-';
$agama_pelapor    = $isi['agama_pelapor'] ?? '-';
$alamat_pelapor   = $isi['alamat_pelapor'] ?? '-';
$hubungan         = $isi['hubungan'] ?? '-';

$nomor_surat = $data['nomor_surat'] ?? '-';

$pj_pejabat  = $_SESSION['nama'] ?? 'PEJABAT';
$nip_pejabat = $_SESSION['nip'] ?? '-';

$tanggal_surat = date('Y-m-d');

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
<title>Surat Keterangan Kematian</title>

<style>
@page{
    size:A4;
    margin:1.5cm;
}

body{
    font-family:"Times New Roman";
    font-size:11.5pt;
    margin:0;
    line-height:1.35;
}

/* KOP */
.kop{
    text-align:center;
    font-weight:bold;
    line-height:1.2;
}

.kop img{
    position:absolute;
    left:30px;
    top:20px;
    width:65px;
}

.kop-line{
    border-top:2px solid #000;
    border-bottom:1px solid #000;
    height:4px;
    margin:8px 0 15px 0;
}

/* JUDUL */
.judul{
    text-align:center;
    margin-bottom:15px;
}

.judul u{
    font-size:13pt;
}

/* TABLE */
table{
    border-collapse:collapse;
    width:100%;
}

td{
    padding:2px 0;
    vertical-align:top;
}

.label{
    width:200px;
}

/* TTD */
.ttd{
    position:relative;
    margin-top:50px;
    min-height:150px;
}

.ttd-kanan{
    position:absolute;
    right:0;
    text-align:center;
    width:250px;
}
</style>
</head>

<body>

<!-- KOP -->
<div class="kop">
<img src="../../../logo_pringsewu.png">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PRINGSEWU<br>
<span style="font-size:15pt;">PEKON BUMIAYU</span><br>
<small>Jln. Veteran No.001 Pekon Bumiayu Kecamatan Pringsewu</small>
</div>

<div class="kop-line"></div>

<!-- JUDUL -->
<div class="judul">
<strong><u>SURAT KETERANGAN KEMATIAN</u></strong><br>
Nomor : <?= htmlspecialchars($nomor_surat) ?>
</div>

<p style="text-align:justify">
Yang bertanda tangan dibawah ini Kepala Pekon Bumiayu menerangkan bahwa:
</p>

<table>
<tr><td class="label">Nama</td><td>: <?= htmlspecialchars($nama_meninggal) ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($nik_meninggal) ?></td></tr>
<tr><td>Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($tempat_lahir_m) ?>, <?= tgl_indo($tgl_lahir_m) ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jenis_kelamin_m) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($alamat_meninggal) ?></td></tr>
</table>

<p style="margin-top:10px">
Telah meninggal dunia pada:
</p>

<table>
<tr><td class="label">Hari</td><td>: <?= htmlspecialchars($hari_meninggal) ?></td></tr>
<tr><td>Tanggal</td><td>: <?= tgl_indo($tgl_meninggal) ?></td></tr>
<tr><td>Penyebab</td><td>: <?= htmlspecialchars($penyebab) ?></td></tr>
<tr><td>Tempat Pemakaman</td><td>: <?= htmlspecialchars($tempat_pemakaman) ?></td></tr>
</table>

<p style="margin-top:10px">
Berdasarkan keterangan pelapor:
</p>

<table>
<tr><td class="label">Nama</td><td>: <?= htmlspecialchars($nama_pelapor) ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jk_pelapor) ?></td></tr>
<tr><td>Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($tempat_lahir_p) ?>, <?= tgl_indo($tgl_lahir_p) ?></td></tr>
<tr><td>Agama</td><td>: <?= htmlspecialchars($agama_pelapor) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($alamat_pelapor) ?></td></tr>
<tr><td>Hubungan</td><td>: <?= htmlspecialchars($hubungan) ?></td></tr>
</table>

<p style="margin-top:10px">
Demikian surat ini dibuat agar dapat dipergunakan sebagaimana mestinya.
</p>

<!-- TTD -->
<div class="ttd">
<div class="ttd-kanan">

Bumiayu, <?= tgl_indo($tanggal_surat) ?><br>
Kepala Pekon Bumiayu<br><br>

<?php if (!empty($data['qr_code'])): ?>
<img src="/arsip/assets/qrcode/generate/<?= $data['qr_code']; ?>" 
     width="110"
     style="image-rendering: crisp-edges;"><br>
<small style="font-size:10px;">Scan QR untuk verifikasi</small>
<?php else: ?>
<div style="height:100px;"></div>
<?php endif; ?>

<br>

<strong><u><?= strtoupper(htmlspecialchars($pj_pejabat)) ?></u></strong><br>
NIP. <?= htmlspecialchars($nip_pejabat) ?>

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