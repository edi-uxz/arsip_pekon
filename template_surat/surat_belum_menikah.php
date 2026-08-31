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

/* ================= AMBIL DATA PEJABAT (ADMIN) ================= */
$pj_pejabat  = '';
$nip_pejabat = '';

$stmtAdmin = $conn->prepare("SELECT nama, nip FROM admin LIMIT 1");
$stmtAdmin->execute();
$resultAdmin = $stmtAdmin->get_result();
$adminData = $resultAdmin->fetch_assoc();

if ($adminData) {
    $pj_pejabat  = $adminData['nama'] ?? '';
    $nip_pejabat = $adminData['nik'] ?? '';
}
$stmtAdmin->close();

/* ================= TANGGAL OTOMATIS ================= */
$tanggal_surat = date('Y-m-d');

/* ================= FIELD FORM ================= */
$fields = [
    'nama','tempat_lahir','tgl_lahir','jk','nik','agama',
    'kewarganegaraan','pekerjaan','status','alamat',
    'keperluan','berlaku'
];

$data = [];
$userData = [];

/* ================= AUTO ISI JIKA MASYARAKAT ================= */
if ($role === 'masyarakat' && $id_masyarakat) {

    $stmtUser = $conn->prepare("SELECT * FROM masyarakat WHERE id = ?");
    $stmtUser->bind_param("i", $id_masyarakat);
    $stmtUser->execute();
    $resultUser = $stmtUser->get_result();
    $userData = $resultUser->fetch_assoc();
    $stmtUser->close();
}

/* ================= AUTO FILL ================= */
foreach ($fields as $f) {
    $data[$f] = $_POST[$f] ?? ($userData[$f] ?? '');
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

/* ================= SIMPAN DATABASE ================= */
$pesan = "";

if ($isAjukan) {

    // Tambahkan data otomatis
    $data['tanggal_surat'] = $tanggal_surat;
    $data['pj_pejabat']    = $pj_pejabat;
    $data['nip_pejabat']   = $nip_pejabat;

    $detailJson = json_encode($data);

    $jenis      = "Surat Keterangan Belum Menikah";
    $status     = "Menunggu";
    $keterangan = "-";
    $id_template = 3;

    $nik_simpan = $role === 'masyarakat' ? $nik_login : ($data['nik'] ?? '');
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
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Form Surat Digital | Pekon Bumiayu</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body { 
    background: linear-gradient(rgba(15,23,42,0.92), rgba(15,23,42,0.92)), 
                url('https://images.unsplash.com/photo-1562654501-a0ccc0af3fb1?auto=format&fit=crop&q=80&w=1920');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    font-family: 'Poppins', sans-serif; 
    color: #fff;
    padding: 40px 0;
}

.container-form { max-width: 900px; }

.bg-custom-card { 
    background: rgba(255, 255, 255, 0.98); 
    border-radius: 25px; 
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.4);
}

.card-header-form {
    background: #1e3a8a;
    color: white;
    padding: 25px;
    border-bottom: 4px solid #fbbf24;
    text-align: center;
}

.form-label { color: #475569; font-weight: 600; font-size: 13px; }

.form-control, .form-select {
    border-radius: 10px;
    padding: 10px 15px;
    border: 1px solid #e2e8f0;
    background: #f8fafc;
}

.btn-preview { background: #1e3a8a; color: white; border: none; }
.btn-ajukan { background: #059669; color: white; border: none; }

.a4-preview {
    width: 210mm;
    min-height: 297mm;
    padding: 25mm;
    margin: 40px auto;
    background: white;
    color: black;
    font-family: "Times New Roman";
    font-size: 12pt;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
}

.kop { text-align: center; line-height: 1.2; font-weight: bold; }
.kop-line { border-top: 3px solid #000; border-bottom: 1px solid #000; height: 5px; margin: 10px 0 20px 0; }
.judul-surat { text-align: center; margin-bottom: 25px; font-weight: bold; text-decoration: underline; }

@media print {
    body { background: white !important; padding: 0; }
    .container-form, .btn-print-trigger { display: none !important; }
    .a4-preview { margin: 0; box-shadow: none; width: 100%; padding: 15mm; }
}
</style>
</head>
<body>

<div class="container container-form">

<div class="bg-custom-card animate__animated animate__fadeInUp">
<div class="card-header-form">
<h4 class="mb-0 fw-bold">SURAT KETERANGAN BELUM MENIKAH</h4>
<small>Silakan lengkapi formulir di bawah ini dengan benar</small>
</div>

<div class="p-4 p-md-5 text-dark">

<?php if($pesan): ?>
<div class="alert alert-success"><?= $pesan ?></div>
<?php endif; ?>

<form method="post">
<div class="row">
<?php foreach($fields as $f): ?>
<div class="col-md-6 mb-3">
<label class="form-label"><?= strtoupper(str_replace('_',' ',$f)) ?></label>

<?php if($f == 'alamat'): ?>
<textarea name="<?= $f ?>" class="form-control" required><?= htmlspecialchars($data[$f]) ?></textarea>

<?php elseif($f == 'tgl_lahir'): ?>
<input type="date" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f]) ?>" required>

<?php elseif($f == 'jk'): ?>
<select name="jk" class="form-select" required>
<option value="">Pilih...</option>
<option value="Laki-laki" <?= $data['jk']=="Laki-laki"?'selected':'' ?>>Laki-laki</option>
<option value="Perempuan" <?= $data['jk']=="Perempuan"?'selected':'' ?>>Perempuan</option>
</select>

<?php else: ?>
<input type="text" name="<?= $f ?>" class="form-control" value="<?= htmlspecialchars($data[$f]) ?>" required>
<?php endif; ?>

</div>
<?php endforeach; ?>
</div>

<div class="text-end border-top pt-4 mt-3">
<button type="submit" name="preview" class="btn btn-preview px-4 py-2 me-2 rounded-pill">
<i class="bi bi-eye"></i> Preview
</button>
<button type="submit" name="ajukan" class="btn btn-ajukan px-4 py-2 rounded-pill">
<i class="bi bi-send-check"></i> Ajukan
</button>
</div>
</form>

</div>
</div>

<?php if($isPreview): ?>
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
Nomor : _____ / _____ / <?= date('m/Y') ?>
</span>
</div>

<p style="text-align:justify">
Yang bertanda tangan dibawah ini Kepala Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, menerangkan dengan sebenarnya bahwa :
</p>

<table style="margin-left:40px">
<tr><td width="220">Nama Lengkap</td><td>: <?= htmlspecialchars($data['nama']) ?></td></tr>
<tr><td>Tempat/Tanggal Lahir</td><td>: <?= htmlspecialchars($data['tempat_lahir']) ?>, <?= tgl_indo($data['tgl_lahir']) ?></td></tr>
<tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($data['jk']) ?></td></tr>
<tr><td>NIK</td><td>: <?= htmlspecialchars($data['nik']) ?></td></tr>
<tr><td>Agama</td><td>: <?= htmlspecialchars($data['agama']) ?></td></tr>
<tr><td>Kewarganegaraan</td><td>: <?= htmlspecialchars($data['kewarganegaraan']) ?></td></tr>
<tr><td>Pekerjaan</td><td>: <?= htmlspecialchars($data['pekerjaan']) ?></td></tr>
<tr><td>Status</td><td>: <?= htmlspecialchars($data['status']) ?></td></tr>
<tr><td>Alamat</td><td>: <?= htmlspecialchars($data['alamat']) ?></td></tr>
</table>

<p style="text-align:justify;margin-top:20px">
Adalah benar orang tersebut diatas warga Pekon Bumiayu Kecamatan Pringsewu Kabupaten Pringsewu, dan berdasarkan data yang ada di Kantor Pekon Bumiayu serta keterangan yang bersangkutan hingga saat ini belum pernah menikah / belum menikah dan surat keterangan ini diberikan untuk :
</p>

<table style="margin-left:40px">
<tr><td width="220">Keperluan</td><td>: <?= htmlspecialchars($data['keperluan']) ?></td></tr>
<tr><td>Berlaku</td><td>: <?= htmlspecialchars($data['berlaku']) ?></td></tr>
</table>

<p style="margin-top:20px">
Demikian surat keterangan ini diberikan untuk dapat dipergunakan seperlunya.
</p>

<div style="text-align:right;margin-top:60px">
Bumiayu, <?= tgl_indo($tanggal_surat) ?><br>
Kepala Pekon Bumiayu<br><br><br><br>
<strong><u><?= htmlspecialchars($pj_pejabat) ?></u></strong><br>
NIP. <?= htmlspecialchars($nip_pejabat) ?>
</div>

</div>
<?php endif; ?>

</div>
</body>
</html>