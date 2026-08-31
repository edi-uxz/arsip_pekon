<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/../koneksi.php';
date_default_timezone_set('Asia/Jakarta');

/* ================= CEK LOGIN ================= */
if (!isset($_SESSION['role'])) {
    die("Anda harus login terlebih dahulu.");
}

$role = $_SESSION['role'];
$id_masyarakat = $_SESSION['id_masyarakat'] ?? null;
$nik_login     = $_SESSION['nik'] ?? null;

/* ================= FIELD FORM (KHUSUS KEHILANGAN) ================= */
$fields = [
    'nama_pelapor', 'nik_pelapor', 'tempat_lahir_p', 'tgl_lahir_p', 
    'pekerjaan_p', 'alamat_p', 'barang_hilang', 'lokasi_hilang', 
    'tgl_hilang', 'alasan_hilang'
];

$data = [];
$userData = [];
$errors = [];

/* ================= AMBIL DATA PEJABAT (OTOMATIS) ================= */
$pj_pejabat = "";
$nip_pejabat = "";

$stmtAdmin = $conn->prepare("SELECT nama, nip FROM admin LIMIT 1");
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();
$adminData = $resultAdmin->fetch_assoc();

if ($adminData) {
    $pj_pejabat  = $adminData['nama'];
    $nip_pejabat = $adminData['nip']; 
}
$stmtAdmin->close();

/* ================= AUTO ISI JIKA MASYARAKAT ================= */
if ($role === 'masyarakat' && $id_masyarakat) {
    $stmtUser = $conn->prepare("SELECT * FROM masyarakat WHERE id = ?");
    $stmtUser->bind_param("i", $id_masyarakat);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
}

/* ================= AUTO FILL ================= */
foreach ($fields as $f) {
    // Mapping otomatis jika field pelapor cocok dengan data masyarakat di DB
    if (!isset($_POST[$f])) {
        if($f == 'nama_pelapor') $data[$f] = $userData['nama'] ?? '';
        elseif($f == 'nik_pelapor') $data[$f] = $userData['nik'] ?? '';
        elseif($f == 'tempat_lahir_p') $data[$f] = $userData['tempat_lahir'] ?? '';
        elseif($f == 'tgl_lahir_p') $data[$f] = $userData['tgl_lahir'] ?? '';
        elseif($f == 'pekerjaan_p') $data[$f] = $userData['pekerjaan'] ?? '';
        elseif($f == 'alamat_p') $data[$f] = $userData['alamat'] ?? '';
        else $data[$f] = '';
    } else {
        $data[$f] = $_POST[$f];
    }
}

$isPreview = isset($_POST['preview']);
$isAjukan  = isset($_POST['ajukan']);

/* ================= FORMAT TANGGAL ================= */
function tgl_indo($tanggal){
    if(!$tanggal) return '';
    $bulan = [
        1=>'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];
    $t = strtotime($tanggal);
    return date('d',$t).' '.$bulan[(int)date('m',$t)].' '.date('Y',$t);
}

/* ================= OTOMATIS TANGGAL SURAT ================= */
$tanggal_surat = date('Y-m-d');

/* ================= SIMPAN DATABASE ================= */
$pesan = "";

if ($isAjukan) {
    foreach ($fields as $f) {
        if (trim($data[$f]) === '') {
            $errors[$f] = "Field wajib diisi";
        }
    }

    if (empty($errors)) {
        $data['tanggal_surat'] = $tanggal_surat;
        $data['pj_pejabat']    = $pj_pejabat;
        $data['nip_pejabat']   = $nip_pejabat;

        $detailJson = json_encode($data);

        $jenis      = "Surat Keterangan Kehilangan";
        $status     = "Menunggu";
        $keterangan = "-";
        $id_template = 5; // Sesuaikan ID template di database Anda

        $nik_simpan = $role === 'masyarakat' ? $nik_login : $data['nik_pelapor'];
        $id_simpan  = $role === 'masyarakat' ? $id_masyarakat : null;

        $stmt = $conn->prepare("INSERT INTO pengajuan_surat
            (jenis_surat, nik, id_masyarakat, id_template, data_isi, status, is_printed, tanggal_pengajuan, keterangan)
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW(), ?)");

        $stmt->bind_param("ssiisss",
            $jenis,
            $nik_simpan,
            $id_simpan,
            $id_template,
            $detailJson,
            $status,
            $keterangan
        );

        if($stmt->execute()){
            $pesan = "Surat kehilangan berhasil diajukan.";
        } else {
            $pesan = "Gagal mengajukan surat.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Surat Kehilangan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#1e3a8a;padding:40px;">

<div class="container" style="max-width:1000px;margin:auto;">

    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h4>SURAT KETERANGAN KEHILANGAN</h4>
        </div>

        <div class="card-body">

            <?php if($pesan): ?>
                <div class="alert alert-success"><?= $pesan ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="row">
                    <?php foreach($fields as $f): ?>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><?= strtoupper(str_replace(['_p','_m','_'],['','',' '],$f)) ?></label>

                            <?php if(strpos($f,'alamat') !== false || $f == 'barang_hilang'): ?>
                                <textarea name="<?= $f ?>" class="form-control" rows="2" required><?= htmlspecialchars($data[$f] ?? '') ?></textarea>
                            <?php elseif(strpos($f,'tgl') !== false): ?>
                                <input type="date" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f] ?? '') ?>" required>
                            <?php else: ?>
                                <input type="text" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f] ?? '') ?>" required>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="text-end">
                    <button type="submit" name="preview" class="btn btn-primary">Preview</button>
                    <button type="submit" name="ajukan" class="btn btn-success">Ajukan</button>
                </div>
            </form>

        </div>
    </div>

    <?php if($isPreview): ?>
    <div style="width:210mm;min-height:297mm;padding:25mm;margin:40px auto;background:white;font-family:'Times New Roman';box-shadow:0 0 20px rgba(0,0,0,0.4);color:black;">

        <div style="text-align:center;font-weight:bold;">
            PEMERINTAH KABUPATEN PRINGSEWU<br>
            KECAMATAN PRINGSEWU<br>
            PEKON BUMI AYU
        </div>

        <hr style="border-top:3px solid black">

        <div style="text-align:center;margin-bottom:20px;">
            <strong><u>SURAT KETERANGAN KEHILANGAN</u></strong><br>
            Nomor : 140/_____/<?= date('Y') ?>
        </div>

        <p>Yang bertanda tangan di bawah ini, Kepala Pekon Bumi Ayu Kecamatan Pringsewu Kabupaten Pringsewu menerangkan bahwa :</p>

        <table style="margin-left:40px;">
            <tr><td width="200" height="25">Nama</td><td>: <?= htmlspecialchars($data['nama_pelapor'] ?? '') ?></td></tr>
            <tr><td height="25">NIK</td><td>: <?= htmlspecialchars($data['nik_pelapor'] ?? '') ?></td></tr>
            <tr><td height="25">Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($data['tempat_lahir_p'] ?? '') ?>, <?= tgl_indo($data['tgl_lahir_p'] ?? '') ?></td></tr>
            <tr><td height="25">Pekerjaan</td><td>: <?= htmlspecialchars($data['pekerjaan_p'] ?? '') ?></td></tr>
            <tr><td height="25" valign="top">Alamat</td><td>: <?= htmlspecialchars($data['alamat_p'] ?? '') ?></td></tr>
        </table>

        <p style="margin-top:20px; text-align:justify; text-indent: 40px;">
            Orang tersebut di atas benar adalah warga Pekon Bumi Ayu yang datang melaporkan bahwa telah kehilangan 
            <strong><?= htmlspecialchars($data['barang_hilang'] ?? '') ?></strong>. 
            Kejadian tersebut terjadi di <?= htmlspecialchars($data['lokasi_hilang'] ?? '') ?> pada tanggal <?= tgl_indo($data['tgl_hilang'] ?? '') ?> 
            dengan alasan <?= htmlspecialchars($data['alasan_hilang'] ?? '') ?>.
        </p>

        <p style="margin-top:10px; text-indent: 40px;">
            Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.
        </p>

        <div style="text-align:right;margin-top:60px;">
            Bumi Ayu, <?= tgl_indo($tanggal_surat) ?><br>
            Kepala Pekon Bumi Ayu<br><br><br><br>
            <strong><u><?= htmlspecialchars($pj_pejabat ?? '') ?></u></strong><br>
            NIP. <?= htmlspecialchars($nip_pejabat ?? '') ?>
        </div>

    </div>

    <div class="text-center mb-5">
        <a href="../../cetak_kehilangan.php?id=<?= $stmt->insert_id ?? 0 ?>" target="_blank" class="btn btn-dark">
            Cetak Surat
        </a>
    </div>
    <?php endif; ?>

</div>
</body>
</html>