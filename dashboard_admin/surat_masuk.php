<?php
session_start();
include '../koneksi.php';

date_default_timezone_set('Asia/Jakarta');

/* ================= CEK ROLE ADMIN ================= */
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

/* ================= QUERY DATA ================= */
$query = mysqli_query($conn, "
    SELECT 
        ps.id,
        ps.data_isi,
        ps.status,
        ps.tanggal_pengajuan,
        ps.nomor_surat,
        ts.nama_template AS nama_surat
    FROM pengajuan_surat ps
    LEFT JOIN template_surat ts ON ps.id_template = ts.id
    ORDER BY ps.tanggal_pengajuan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Masuk | Panel Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    background: linear-gradient(rgba(30,58,138,0.9),rgba(30,58,138,0.95));
    font-family:'Poppins',sans-serif;
    padding:40px 15px;
    min-height:100vh;
}

.container-main{max-width:1200px;margin:auto;}

.card-custom{
    border:none;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.3);
    background:white;
    overflow:hidden;
}

.table thead{
    background:#1e3a8a;
    color:white;
}

.status-badge{
    padding:6px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:600;
}

.status-diproses{background:#fff7ed;color:#c2410c;}
.status-acc{background:#d1fae5;color:#065f46;}
.status-ditolak{background:#fee2e2;color:#991b1b;}

.btn-action{
    border-radius:10px;
    font-size:13px;
    padding:6px 12px;
}

.btn-acc{background:#1e3a8a;color:white;border:none;}
.btn-print{background:#334155;color:white;border:none;}
</style>
</head>
<body>

<div class="container-main">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="text-white mb-0">
        <i class="fas fa-envelope-open-text me-2"></i> Surat Masuk
    </h3>

    <a href="index.php" class="btn btn-light shadow-sm">
        <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="card-custom">
<div class="table-responsive">
<table class="table table-hover align-middle mb-0">
<thead>
<tr class="text-center">
<th width="60">No</th>
<th class="text-start">Nama & Alamat</th>
<th class="text-start">Jenis Surat</th>
<th>Nomor Surat</th>
<th>Waktu</th>
<th>Status</th>
<th width="220">Aksi</th>
</tr>
</thead>
<tbody>

<?php
$no = 1;

if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        $id = (int)$row['id'];

        /* ================= DECODE JSON AMAN ================= */
        $dataIsi = !empty($row['data_isi'])
            ? json_decode($row['data_isi'], true)
            : [];

        $nama_dari_json   = $dataIsi['nama'] ?? '-';
        $alamat_dari_json = $dataIsi['alamat'] ?? '-';

        $status = strtolower(trim($row['status']));
        $timestamp = strtotime($row['tanggal_pengajuan']);
?>

<tr class="text-center">
<td><?= $no++; ?></td>

<td class="text-start">
    <div class="fw-bold"><?= htmlspecialchars($nama_dari_json); ?></div>
    <small class="text-muted"><?= htmlspecialchars($alamat_dari_json); ?></small><br>
    <small class="text-muted">ID: #SRT-<?= $id; ?></small>
</td>

<!-- ✅ FIX: Jenis Surat -->
<td class="text-start">
    <?= htmlspecialchars($row['nama_surat'] ?? '-'); ?>
</td>

<!-- ✅ Nomor Surat -->
<td>
    <?= !empty($row['nomor_surat']) 
        ? htmlspecialchars($row['nomor_surat']) 
        : '-'; ?>
</td>

<td>
    <?= date("d M Y", $timestamp); ?><br>
    <small class="text-muted">
        <?= date("H:i:s", $timestamp); ?> WIB
    </small>
</td>

<td>
<?php
$s_class = match($status){
    'menunggu','diproses' => 'status-diproses',
    'acc','disetujui' => 'status-acc',
    'ditolak' => 'status-ditolak',
    default => 'bg-secondary text-white'
};

$s_label = match($status){
    'menunggu','diproses' => 'Sedang Diproses',
    'acc','disetujui' => 'Disetujui',
    'ditolak' => 'Ditolak',
    default => 'Pending'
};
?>
<span class="status-badge <?= $s_class; ?>">
<?= $s_label; ?>
</span>
</td>

<td>

<?php if (in_array($status, ['menunggu','diproses'])): ?>

    <a href="proses/acc_surat.php?id=<?= $id; ?>" 
       class="btn-action btn-acc me-1"
       onclick="return confirm('Setujui surat ini?')">
       <i class="fas fa-check"></i>
    </a>

    <a href="proses/tolak_surat.php?id=<?= $id; ?>" 
       class="btn btn-sm btn-outline-danger me-1"
       onclick="return confirm('Tolak surat ini?')">
       <i class="fas fa-times"></i>
    </a>

<?php elseif (in_array($status, ['acc','disetujui'])): ?>

    <a href="proses/cetak_surat.php?id=<?= $id; ?>" 
       target="_blank"
       class="btn-action btn-print me-1">
       <i class="fas fa-print"></i>
    </a>

<?php endif; ?>

    <!-- Tombol Hapus -->
    <a href="proses/hapus_surat.php?id=<?= $id; ?>" 
       class="btn btn-sm btn-danger"
       onclick="return confirm('Yakin ingin menghapus surat ini secara permanen?')">
       <i class="fas fa-trash"></i>
    </a>

</td>

</tr>

<?php
    }
} else {
    echo '<tr><td colspan="7" class="text-center py-4 text-muted">Belum ada permohonan surat.</td></tr>';
}
?>

</tbody>
</table>
</div>
</div>

</div>

</body>
</html>