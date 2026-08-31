<?php
include __DIR__ . '/../koneksi.php';

$isPreview = isset($_POST['preview']);
$isAjukan  = isset($_POST['ajukan']);

// Data almarhum/almarhumah
$nama_meninggal     = $_POST['nama_meninggal'] ?? '';
$nik_meninggal      = $_POST['nik_meninggal'] ?? '';
$tempat_lahir_m     = $_POST['tempat_lahir_m'] ?? '';
$tgl_lahir_m        = $_POST['tgl_lahir_m'] ?? '';
$jenis_kelamin_m    = $_POST['jenis_kelamin_m'] ?? '';
$alamat_meninggal   = $_POST['alamat_meninggal'] ?? '';
$hari_meninggal     = $_POST['hari_meninggal'] ?? '';
$tgl_meninggal      = $_POST['tgl_meninggal'] ?? '';
$penyebab           = $_POST['penyebab'] ?? '';
$tempat_pemakaman   = $_POST['tempat_pemakaman'] ?? '';

// Data pelapor
$nama_pelapor       = $_POST['nama_pelapor'] ?? '';
$jk_pelapor         = $_POST['jk_pelapor'] ?? '';
$tempat_lahir_p     = $_POST['tempat_lahir_p'] ?? '';
$tgl_lahir_p        = $_POST['tgl_lahir_p'] ?? '';
$agama_pelapor      = $_POST['agama_pelapor'] ?? '';
$alamat_pelapor     = $_POST['alamat_pelapor'] ?? '';
$hubungan           = $_POST['hubungan'] ?? '';

// Pejabat & surat
$pj_pejabat         = $_POST['pj_pejabat'] ?? '';
$nip_pejabat        = $_POST['nip_pejabat'] ?? '';
$tanggal_surat      = $_POST['tanggal_surat'] ?? '';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <div class="bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4 text-center">Form Surat Keterangan Kematian</h3>
        <form method="post">
            <h5>Data Almarhum / Almarhumah</h5>
            <div class="mb-2"><label>Nama</label>
                <input type="text" name="nama_meninggal" class="form-control" value="<?= htmlspecialchars($nama_meninggal) ?>" required>
            </div>
            <div class="mb-2"><label>NIK</label>
                <input type="text" name="nik_meninggal" class="form-control" value="<?= htmlspecialchars($nik_meninggal) ?>" required>
            </div>
            <div class="mb-2"><label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir_m" class="form-control" value="<?= htmlspecialchars($tempat_lahir_m) ?>" required>
            </div>
            <div class="mb-2"><label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir_m" class="form-control" value="<?= htmlspecialchars($tgl_lahir_m) ?>" required>
            </div>
            <div class="mb-2"><label>Jenis Kelamin</label>
                <select name="jenis_kelamin_m" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= $jenis_kelamin_m == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $jenis_kelamin_m == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-2"><label>Alamat</label>
                <textarea name="alamat_meninggal" class="form-control" rows="2" required><?= htmlspecialchars($alamat_meninggal) ?></textarea>
            </div>
            <div class="mb-2"><label>Hari Meninggal</label>
                <input type="text" name="hari_meninggal" class="form-control" value="<?= htmlspecialchars($hari_meninggal) ?>" required>
            </div>
            <div class="mb-2"><label>Tanggal Meninggal</label>
                <input type="date" name="tgl_meninggal" class="form-control" value="<?= htmlspecialchars($tgl_meninggal) ?>" required>
            </div>
            <div class="mb-2"><label>Penyebab Kematian</label>
                <input type="text" name="penyebab" class="form-control" value="<?= htmlspecialchars($penyebab) ?>" required>
            </div>
            <div class="mb-3"><label>Tempat Pemakaman</label>
                <input type="text" name="tempat_pemakaman" class="form-control" value="<?= htmlspecialchars($tempat_pemakaman) ?>" required>
            </div>

            <h5>Data Pelapor</h5>
            <div class="mb-2"><label>Nama Lengkap</label>
                <input type="text" name="nama_pelapor" class="form-control" value="<?= htmlspecialchars($nama_pelapor) ?>" required>
            </div>
            <div class="mb-2"><label>Jenis Kelamin</label>
                <select name="jk_pelapor" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" <?= $jk_pelapor == 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                    <option value="Perempuan" <?= $jk_pelapor == 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                </select>
            </div>
            <div class="mb-2"><label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir_p" class="form-control" value="<?= htmlspecialchars($tempat_lahir_p) ?>" required>
            </div>
            <div class="mb-2"><label>Tanggal Lahir</label>
                <input type="date" name="tgl_lahir_p" class="form-control" value="<?= htmlspecialchars($tgl_lahir_p) ?>" required>
            </div>
            <div class="mb-2"><label>Agama</label>
                <input type="text" name="agama_pelapor" class="form-control" value="<?= htmlspecialchars($agama_pelapor) ?>" required>
            </div>
            <div class="mb-2"><label>Alamat Lengkap</label>
                <textarea name="alamat_pelapor" class="form-control" rows="2" required><?= htmlspecialchars($alamat_pelapor) ?></textarea>
            </div>
            <div class="mb-3"><label>Hubungan Keluarga</label>
                <input type="text" name="hubungan" class="form-control" value="<?= htmlspecialchars($hubungan) ?>" required>
            </div>

            <div class="text-end">
                <button type="submit" name="preview" class="btn btn-primary me-2">
                    Preview
                </button>
                <button type="submit" name="ajukan" class="btn btn-success">
                    Ajukan Surat
                </button>
            </div>
        </form>
    </div>

<?php if ($isPreview): ?>
<style>
    .watermark {
        position: absolute;
        top: 40%;
        left: 50%;
        opacity: 0.08;
        transform: translate(-50%, -50%);
        z-index: 0;
    }
    .surat {
        position: relative;
        padding: 40px;
        background: white;
    }
</style>
<div class="surat mt-5 p-4 rounded shadow-sm">
    <img src="../arsip/logo_pringsewu.png" class="watermark" width="300">
    <div class="text-center fw-bold">
        PEMERINTAH KABUPATEN PRINGSEWU<br>
        KECAMATAN PARDASUKA<br>
        PEKON SUKOREJO<br>
        <small>Jalan Utama Pekon Sukorejo Kecamatan Pardasuka Kabupaten Pringsewu Kode Pos 35382</small>
        <hr style="border: 2px solid black; margin: 4px 0;">
        <div class="mt-2"><u>SURAT KETERANGAN KEMATIAN</u><br>
        NOMOR: .... / .... / <?= date('Y') ?></div>
    </div>
    <p class="mt-4">
        Yang bertanda tangan di bawah ini, Kepala Pekon Sukorejo Kecamatan Pardasuka Kabupaten Pringsewu, menerangkan bahwa:
    </p>
    <table style="margin-left: 20px;">
        <tr><td style="width:200px;">Nama</td><td>: <?= htmlspecialchars($nama_meninggal) ?></td></tr>
        <tr><td>NIK</td><td>: <?= htmlspecialchars($nik_meninggal) ?></td></tr>
        <tr><td>Tempat Tanggal Lahir</td><td>: <?= htmlspecialchars($tempat_lahir_m) ?>, <?= date('j F Y', strtotime($tgl_lahir_m)) ?></td></tr>
        <tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jenis_kelamin_m) ?></td></tr>
        <tr><td>Alamat</td><td>: <?= htmlspecialchars($alamat_meninggal) ?></td></tr>
    </table>
    <p class="mt-3">Nama tersebut di atas telah meninggal dunia pada:</p>
    <table style="margin-left: 20px;">
        <tr><td style="width:200px;">Hari</td><td>: <?= htmlspecialchars($hari_meninggal) ?></td></tr>
        <tr><td>Tanggal</td><td>: <?= date('j F Y', strtotime($tgl_meninggal)) ?></td></tr>
        <tr><td>Penyebab Kematian</td><td>: <?= htmlspecialchars($penyebab) ?></td></tr>
        <tr><td>Tempat Pemakaman</td><td>: <?= htmlspecialchars($tempat_pemakaman) ?></td></tr>
    </table>
    <p class="mt-3">Surat keterangan ini dibuat berdasarkan keterangan pelapor:</p>
    <table style="margin-left: 20px;">
        <tr><td style="width:200px;">Nama Lengkap</td><td>: <?= htmlspecialchars($nama_pelapor) ?></td></tr>
        <tr><td>Jenis Kelamin</td><td>: <?= htmlspecialchars($jk_pelapor) ?></td></tr>
        <tr><td>Tempat Tanggal Lahir</td><td>: <?= htmlspecialchars($tempat_lahir_p) ?>, <?= date('j F Y', strtotime($tgl_lahir_p)) ?></td></tr>
        <tr><td>Agama</td><td>: <?= htmlspecialchars($agama_pelapor) ?></td></tr>
        <tr><td>Alamat Lengkap</td><td>: <?= htmlspecialchars($alamat_pelapor) ?></td></tr>
        <tr><td>Hubungan Keluarga</td><td>: <?= htmlspecialchars($hubungan) ?></td></tr>
    </table>
    <p class="mt-3">
        Demikian Surat Keterangan Kematian ini dibuat dengan sebenarnya dan untuk digunakan sebagaimana mestinya.
    </p>
    <div class="text-end mt-5">
    Sukorejo, .........................<br>
    Kepala Pekon Sukorejo<br><br><br><br>
    <strong><u>...............................</u></strong><br>
    NIP. ............................
    </div>
</div>
<?php endif; ?>
</div>
