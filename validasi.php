<?php
include "koneksi.php";

$kode = isset($_GET['kode']) ? $_GET['kode'] : '';

// Query gabung masyarakat & admin
$data = mysqli_query($conn, "
SELECT p.*, 
       m.nama AS nama_masyarakat, 
       a.nama AS nama_admin
FROM pengajuan_surat p
LEFT JOIN masyarakat m ON p.id_masyarakat = m.id
LEFT JOIN admin a ON p.id_admin = a.id
WHERE p.kode_unik='$kode'
");

$d = mysqli_fetch_assoc($data);

// Tentukan nama pengaju
$nama = '-';
if ($d) {
    $nama = $d['nama_masyarakat'] ?: $d['nama_admin'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Validasi Surat</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f6f9;
    margin:0;
}

.container{
    max-width:500px;
    margin:50px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
    text-align:center;
}

.valid{
    color:green;
    font-size:22px;
    font-weight:bold;
}

.invalid{
    color:red;
    font-size:22px;
    font-weight:bold;
}

.data{
    text-align:left;
    margin-top:20px;
    font-size:14px;
}

.logo{
    width:80px;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="container">

<img src="logo_pringsewu.png" class="logo">

<h2>VALIDASI SURAT RESMI</h2>

<?php if($d){ ?>

    <div class="valid">✅ SURAT VALID</div>

    <div class="data">
        <p><b>Nomor Surat:</b><br><?= htmlspecialchars($d['nomor_surat'] ?? '-') ?></p>
        <p><b>Nama Pemohon:</b><br><?= htmlspecialchars($nama) ?></p>
        <p><b>Jenis Surat:</b><br><?= htmlspecialchars($d['jenis_surat']) ?></p>
        <p><b>Tanggal Pengajuan:</b><br><?= htmlspecialchars($d['tanggal_pengajuan']) ?></p>
        <p><b>Status:</b><br><?= strtoupper(htmlspecialchars($d['status'])) ?></p>
    </div>

<?php } else { ?>

    <div class="invalid">❌ SURAT TIDAK VALID</div>
    <p>Data tidak ditemukan dalam sistem</p>

<?php } ?>

</div>

</body>
</html>