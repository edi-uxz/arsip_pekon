<?php
session_start();
include '../koneksi.php';

// Proteksi halaman
if (!isset($_SESSION['id_masyarakat'])) {
    header("Location: ../login.php");
    exit;
}

$id_masyarakat = intval($_SESSION['id_masyarakat']);

// Ambil data riwayat surat user
$query = mysqli_query($conn, "
    SELECT ps.*, ts.nama_template 
    FROM pengajuan_surat ps 
    JOIN template_surat ts ON ps.id_template = ts.id 
    WHERE ps.id_masyarakat = $id_masyarakat 
    ORDER BY ps.tanggal_pengajuan DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengajuan | Sistem Digital</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        :root {
            --primary: #1e3a8a;
            --accent: #fbbf24;
            --bg-overlay: rgba(15, 23, 42, 0.92);
        }

        body {
            background: linear-gradient(var(--bg-overlay), var(--bg-overlay)), 
                        url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            padding: 40px 0;
        }

        .container-custom {
            max-width: 900px;
            margin: auto;
            padding: 0 20px;
        }

        .btn-back {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding: 8px 18px;
            background: rgba(255,255,255,0.1);
            border-radius: 50px;
            transition: 0.3s;
            border: 1px solid rgba(251, 191, 36, 0.3);
        }

        .btn-back:hover {
            background: var(--accent);
            color: var(--primary);
        }

        .card-main {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 25px;
            border: none;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: #1e293b;
        }

        .card-header-custom {
            background: var(--primary);
            padding: 20px 30px;
            border-bottom: 4px solid var(--accent);
            color: white;
        }

        .table { margin-bottom: 0; }
        .table thead th {
            background: #f8fafc;
            padding: 18px 20px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b;
            border: none;
        }

        .table tbody td {
            padding: 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .status-pill {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .status-approved { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-pending { background: #fef9c3; color: #854d0e; }

        @media (max-width: 768px) {
            .table thead { display: none; }
            .table tbody td { 
                display: block; 
                width: 100%; 
                text-align: left; 
                padding: 10px 25px;
                border: none;
            }
            .table tbody td:first-child { background: #f8fafc; font-weight: bold; padding-top: 15px; }
            .table tbody tr { border-bottom: 4px solid #f1f5f9; display: block; }
        }
    </style>
</head>
<body>

<div class="container-custom">
    <div class="animate__animated animate__fadeInLeft">
        <a href="index.php" class="btn-back">
            <i class="fas fa-chevron-left"></i> Dashboard
        </a>
    </div>

    <div class="card-main animate__animated animate__fadeInUp">
        <div class="card-header-custom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold">RIWAYAT PENGAJUAN</h5>
                <small class="opacity-75">Daftar surat yang telah Anda proses</small>
            </div>
            <i class="fas fa-history fa-2x opacity-25"></i>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th width="10%">No</th>
                        <th width="45%">Jenis Surat</th>
                        <th width="25%">Waktu Pengajuan</th>
                        <th width="20%">Status Akhir</th>
                        <th width="20%">Aksi</th> <!-- TAMBAHAN -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1; 
                    if (mysqli_num_rows($query) > 0) :
                        while ($row = mysqli_fetch_assoc($query)) : 
                    ?>
                        <tr class="animate__animated animate__fadeInUp animate__delay-1s">
                            <td class="text-muted fw-bold">#<?= $no++ ?></td>
                            <td>
                                <div class="fw-bold text-primary"><?= htmlspecialchars($row['nama_template'] ?? '') ?></div>
                                <div class="text-muted small">ID: PS-<?= $row['id'] ?></div>
                            </td>
                            <td>
                                <div class="fw-medium small"><?= date('d M Y', strtotime($row['tanggal_pengajuan'])) ?></div>
                                <div class="text-muted" style="font-size: 11px;"><?= date('H:i', strtotime($row['tanggal_pengajuan'])) ?> WIB</div>
                            </td>
                            <td>
                                <?php if ($row['status'] == 'disetujui') : ?>
                                    <span class="status-pill status-approved">
                                        <i class="fas fa-check-circle"></i> Selesai
                                    </span>
                                <?php elseif ($row['status'] == 'ditolak') : ?>
                                    <span class="status-pill status-rejected">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                <?php else : ?>
                                    <span class="status-pill status-pending">
                                        <i class="fas fa-spinner fa-spin"></i> Proses
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- TOMBOL CETAK (TAMBAHAN) -->
                            <td>
                                <?php if ($row['status'] == 'disetujui') : ?>
                                    <a href="../dashboard_admin/proses/cetak_surat.php?id=<?= $row['id'] ?>" 
                                       target="_blank"
                                       class="btn btn-success btn-sm rounded-pill">
                                       <i class="fas fa-print"></i> Cetak
                                    </a>
                                <?php else : ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php 
                        endwhile; 
                    else : 
                    ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" width="80" class="mb-3 opacity-50">
                                <p class="text-muted fw-medium">Belum ada aktivitas pengajuan surat.</p>
                                <a href="ajukan_surat.php" class="btn btn-primary btn-sm rounded-pill px-4">Buat Sekarang</a>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="text-center mt-5 animate__animated animate__fadeIn">
        <p class="text-white-50 small">&copy; <?= date('Y') ?> Pemerintah Pekon Sukorejo • Pelayanan Digital</p>
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>