<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "Akses tidak diizinkan.";
    exit;
}

// Ambil data dari form
$judul_surat = htmlspecialchars($_POST['judul_surat']);
$nama = htmlspecialchars($_POST['nama']);
$nik = htmlspecialchars($_POST['nik']);
$tempat_lahir = htmlspecialchars($_POST['tempat_lahir']);
$tanggal_lahir = htmlspecialchars($_POST['tanggal_lahir']);
$alamat_asal = htmlspecialchars($_POST['alamat_asal']);
$alamat_domisili = htmlspecialchars($_POST['alamat_domisili']);
$tanggal_surat = date("d F Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul_surat ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 50px;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
        }
        .isi-surat {
            margin-top: 30px;
            font-size: 18px;
        }
        .ttd {
            margin-top: 60px;
            text-align: right;
        }
        .btn-print {
            position: fixed;
            top: 10px;
            right: 10px;
        }
        @media print {
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<button class="btn btn-success btn-print" onclick="window.print()">Cetak Surat</button>

<div class="kop">
    <h5>PEMERINTAH PEKON ABCD</h5>
    <h5>KECAMATAN XYZ, KABUPATEN KLMNOP</h5>
    <p><em>Alamat: Jl. Contoh No.123, Pekon ABCD</em></p>
</div>

<div class="text-center mb-4">
    <h5><u><?= strtoupper($judul_surat) ?></u></h5>
    <p>Nomor: 123/PKN/<?= date('Y') ?></p>
</div>

<div class="isi-surat">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
    <table class="table-borderless" style="margin-left: 20px;">
        <tr>
            <td>Nama</td>
            <td>: <?= $nama ?></td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>: <?= $nik ?></td>
        </tr>
        <tr>
            <td>Tempat/Tanggal Lahir</td>
            <td>: <?= $tempat_lahir ?>, <?= date("d-m-Y", strtotime($tanggal_lahir)) ?></td>
        </tr>
        <tr>
            <td>Alamat Asal</td>
            <td>: <?= $alamat_asal ?></td>
        </tr>
        <tr>
            <td>Alamat Domisili</td>
            <td>: <?= $alamat_domisili ?></td>
        </tr>
    </table>

    <p>Adalah benar yang bersangkutan berdomisili di alamat tersebut di atas dan merupakan warga Pekon ABCD.</p>

    <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
</div>

<div class="ttd">
    <p>Pekon ABCD, <?= $tanggal_surat ?></p>
    <p><strong>Kepala Pekon</strong></p>
    <br><br><br>
    <p><strong><u>Nama Kepala Pekon</u></strong></p>
</div>

</body>
</html>
