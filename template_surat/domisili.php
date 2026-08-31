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

/* ================= FIELD FORM ================= */
$fields = [
    'nama','nik','tempat_lahir','tgl_lahir',
    'jenis_kelamin','pekerjaan','agama',
    'status_perkawinan','warga_negara','alamat'
];

$data = [];
$userData = [];
$errors = [];

/* ================= AMBIL DATA PEJABAT ================= */
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
    $data[$f] = $_POST[$f] ?? $userData[$f] ?? '';
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

        $jenis      = "Surat Keterangan Domisili";
        $status     = "Menunggu";
        $keterangan = "-";
        $id_template = 4;

        $nik_simpan = $role === 'masyarakat' ? $nik_login : $data['nik'];
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
            $pesan = "Surat berhasil diajukan dan menunggu persetujuan admin.";
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
<title>Form Surat Domisili</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#1e3a8a;padding:40px;">

<div class="container" style="max-width:1000px;margin:auto;">

<div class="card shadow">
<div class="card-header bg-primary text-white text-center">
<h4>SURAT KETERANGAN DOMISILI</h4>
</div>

<div class="card-body">

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">
<div class="row">
<?php foreach($fields as $f): ?>
<div class="col-md-6 mb-3">
<label class="form-label"><?= strtoupper(str_replace('_',' ',$f)) ?></label>

<?php if(strpos($f,'alamat') !== false): ?>
<textarea name="<?= $f ?>" class="form-control" required><?= htmlspecialchars($data[$f] ?? '') ?></textarea>
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
<div style="width:210mm;min-height:297mm;padding:25mm;margin:40px auto;background:white;font-family:'Times New Roman';box-shadow:0 0 20px rgba(0,0,0,0.4);">

<div style="text-align:center;font-weight:bold;">
PEMERINTAH KABUPATEN PRINGSEWU<br>
KECAMATAN PARDASUKA<br>
PEKON SUKOREJO
</div>

<hr style="border-top:3px solid black">

<div style="text-align:center;margin-bottom:20px;">
<strong><u>SURAT KETERANGAN DOMISILI</u></strong><br>
Nomor : 300/_____/<?= date('Y') ?>
</div>

<p>Yang bertanda tangan di bawah ini menerangkan bahwa :</p>

<table style="margin-left:40px;">
<tr><td width="200">Nama</td><td>: <?= htmlspecialchars($data['nama'] ?? '') ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($data['nik'] ?? '') ?></td></tr>
<tr><td>Tempat/Tgl Lahir</td><td>: <?= htmlspecialchars($data['tempat_lahir'] ?? '') ?>, <?= tgl_indo($data['tgl_lahir'] ?? '') ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($data['pekerjaan'] ?? '') ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($data['alamat'] ?? '') ?></td></tr>
</table>

<p style="margin-top:20px;">
Benar bahwa yang bersangkutan berdomisili di alamat tersebut di atas.
Surat ini dibuat untuk dipergunakan sebagaimana mestinya.
</p>

<div style="text-align:right;margin-top:60px;">
Sukorejo, <?= tgl_indo($tanggal_surat) ?><br>
Kepala Pekon Sukorejo<br><br><br><br>
<strong><u><?= htmlspecialchars($pj_pejabat ?? '') ?></u></strong><br>
NIK. <?= htmlspecialchars($nip_pejabat ?? '') ?>
</div>

</div>

<div class="text-center mb-5">
<button onclick="window.print()" class="btn btn-dark">Cetak A4</button>
</div>
<?php endif; ?>

</div>
</body>
</html>