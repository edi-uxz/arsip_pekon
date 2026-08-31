-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2026 at 08:45 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_arsip`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nip` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `nip`, `alamat`, `no_hp`) VALUES
(1, 'Edy Purnomo', 'purnomo', '', '12345', 'Bumi Ayu', '08123456789'),
(2, 'Edi Purnomo', 'purnomo@gmail.com', '$2y$10$Hr.TWRVfDV3eGeb8oA094ey9pfhkZU/c3Vynt9CfEQmjjZqlZPPCC', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `masyarakat`
--

CREATE TABLE `masyarakat` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'aktif',
  `password` varchar(255) NOT NULL,
  `alamat` text,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `masyarakat`
--

INSERT INTO `masyarakat` (`id`, `nama`, `username`, `nik`, `status`, `password`, `alamat`, `no_hp`) VALUES
(2, 'Edi Purnomo', 'rou@gmail.com', '54321', 'aktif', '$2y$10$Etve7r36oYMWTth8xxn6uecHqVyqaXaeGTMyr7R8p763qlrLX4C4G', 'sukorejo', '082378559127'),
(3, 'Paisah', '1212@gmail.com', '1810271410040002', 'aktif', '$2y$10$yEQ0fYz5ujlJmAXtGJvATex6YgvBw9TYMgXlUzBnQxD/CpfmicaOS', 'pemda', '082378559127');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id` int NOT NULL,
  `jenis_surat` varchar(100) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `id_masyarakat` int DEFAULT NULL,
  `id_template` int DEFAULT NULL,
  `data_isi` text,
  `status` enum('diproses','disetujui','ditolak') DEFAULT 'diproses',
  `is_printed` tinyint(1) DEFAULT '0',
  `tanggal_pengajuan` date NOT NULL,
  `keterangan` text,
  `nomor_surat` varchar(50) DEFAULT NULL,
  `admin_acc` int DEFAULT NULL,
  `tanggal_acc` datetime DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `kode_unik` varchar(100) DEFAULT NULL,
  `id_admin` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id`, `jenis_surat`, `nik`, `id_masyarakat`, `id_template`, `data_isi`, `status`, `is_printed`, `tanggal_pengajuan`, `keterangan`, `nomor_surat`, `admin_acc`, `tanggal_acc`, `qr_code`, `kode_unik`, `id_admin`) VALUES
(23, 'Surat Sktm Sekolah', '54321', 2, 5, '{\"nama_lengkap\":\"joni priyadi\",\"nik\":\"54321\",\"tempat_lahir\":\"senimbang\",\"tgl_lahir\":\"1212-12-12\",\"agama\":\"kristen\",\"pekerjaan\":\"pengangguran\",\"alamat\":\"sukorejo\",\"keperluan\":\"nikah muda\"}', 'disetujui', 0, '2026-04-10', 'Pengajuan Surat Surat Sktm Sekolah', '300/002/PBK/IV/2026', NULL, NULL, 'qr_1c80c83755e0b4ff85b80c030ef4fe87.png', '1c80c83755e0b4ff85b80c030ef4fe87', NULL),
(31, 'Surat Jual Beli Tanah', '', 2, 6, '{\"p1_nama\":\"rido\",\"p1_pekerjaan\":\"petani\",\"p1_tempat_lahir\":\"skg\",\"p1_tgl_lahir\":\"1223-12-12\",\"p1_alamat\":\"tj a\",\"p2_nama\":\"alia\",\"p2_pekerjaan\":\"honor\",\"p2_tempat_lahir\":\"bbs\",\"p2_tgl_lahir\":\"12223-12-12\",\"p2_alamat\":\"fdada\",\"lokasi_tanah\":\"gunung terang\",\"luas_tanah\":\"12\",\"harga_tanah\":\"12\",\"batas_utara\":\"12\",\"batas_selatan\":\"12\",\"batas_barat\":\"12\",\"batas_timur\":\"12\"}', 'disetujui', 1, '2026-04-10', 'Pengajuan Surat Surat Jual Beli Tanah', '300/002/PBK/IV/2026', NULL, NULL, 'qr_fabc745e800f5e6e1f3522110a89f812.png', 'fabc745e800f5e6e1f3522110a89f812', NULL),
(37, 'Surat Belum Menikah', NULL, NULL, 8, '{\"nama\":\"adgfafgfa\",\"tempat_lahir\":\"iugiug\",\"tgl_lahir\":\"2026-04-22\",\"jk\":\"Laki-laki\",\"nik\":\"g\",\"agama\":\"igi\",\"kewarganegaraan\":\"iugigigi\",\"pekerjaan\":\"gs\",\"status\":\"giug\",\"alamat\":\"igi\",\"keperluan\":\"gi\",\"berlaku\":\"gig\"}', 'disetujui', 0, '2026-04-10', 'Pengajuan Surat Surat Belum Menikah', '300/003/PBK/IV/2026', NULL, NULL, 'qr_b1ec14573729d7c733f2d109cbedd14f.png', 'b1ec14573729d7c733f2d109cbedd14f', NULL),
(38, 'Skck', NULL, NULL, 2, '{\"nama\":\"indriyani\",\"nik\":\"jadfdklfalk\",\"tempat_lahir\":\"jlajdflL\",\"tgl_lahir\":\"2026-04-25\",\"jenis_kelamin\":\"FDLADSKLFL\",\"agama\":\"JDFLAJL\",\"suku\":\"JJ\",\"pekerjaan\":\"JLJGFA\",\"alamat\":\"JJLKGFJA\",\"keperluan\":\"LJLKJGFJLQJ\"}', 'disetujui', 0, '2026-04-10', 'Pengajuan Surat Skck', '300/004/PBK/IV/2026', NULL, NULL, 'qr_8ff530af6192ee70c1f5f9a074634b46.png', '8ff530af6192ee70c1f5f9a074634b46', NULL),
(39, 'Sktm', NULL, NULL, 3, '{\"nama_meninggal\":\"DFSSG\",\"nik_meninggal\":\"HLHO\",\"tempat_lahir_m\":\"GHUH\",\"tgl_lahir_m\":\"0324-02-13\",\"jenis_kelamin_m\":\"Laki-laki\",\"alamat_meninggal\":\"GKLSKLN\",\"hari_meninggal\":\"LJKLJL\",\"tgl_meninggal\":\"0001-05-31\",\"penyebab\":\";LJG;LDFLJJ\",\"tempat_pemakaman\":\"JLJLJLJ\",\"nama_pelapor\":\"J;LJ\",\"jk_pelapor\":\"Laki-laki\",\"tempat_lahir_p\":\";L\",\"tgl_lahir_p\":\"0134-04-01\",\"agama_pelapor\":\"JLGJEJL\",\"alamat_pelapor\":\"JLJKLGEJLK\",\"hubungan\":\"LKJKLJLWJ\"}', 'disetujui', 1, '2026-04-10', 'Pengajuan Surat Sktm', '300/005/PBK/IV/2026', NULL, NULL, 'qr_b17e9cfc7a9a12538ba6f699dd1dc13f.png', 'b17e9cfc7a9a12538ba6f699dd1dc13f', NULL),
(40, 'Surat Kehilangan', NULL, NULL, 7, '{\"nama_pelapor\":\"edi kurniawan \",\"tempat_lahir_p\":\"suka agung barat\",\"tgl_lahir_p\":\"2004-10-14\",\"pekerjaan_p\":\"mahasiswa\",\"alamat_p\":\"tj anom\",\"barang_hilang\":\"stnk\",\"lokasi_hilang\":\"pringsewu\",\"tgl_hilang\":\"2026-04-22\",\"alasan_hilang\":\"terjatuh\",\"pj_pejabat\":\"yono\",\"nip_pejabat\":\"12313135\",\"tanggal_surat\":\"2026-04-22\"}', 'disetujui', 0, '2026-04-22', 'Pengajuan Surat Surat Kehilangan', '300/006/PBK/IV/2026', NULL, NULL, 'qr_43e7246c2c7bd6a41456d089ef6a7c4e.png', '43e7246c2c7bd6a41456d089ef6a7c4e', NULL),
(41, 'Surat Beda Data', NULL, NULL, 9, '{\"nama_kk\":\"edi kurniawan\",\"jk_kk\":\"laki laki\",\"ttl_kk\":\"suka agung barat 14 oktober 204\",\"pekerjaan_kk\":\"mahasiswa\",\"nik_kk\":\"1810271410040001\",\"alamat_kk\":\"suka agung barat\",\"nama_beda\":\"rusman\",\"jk_beda\":\"perempuan\",\"ttl_beda\":\"madidi\",\"pekerjaan_beda\":\"buruh\",\"nik_beda\":\"292y82723091\",\"dusun\":\"surakatar\",\"rt\":\"01\",\"rw\":\"01\",\"keterangan_beda\":\"menje menje\"}', 'disetujui', 0, '2026-04-22', 'Pengajuan Surat Surat Beda Data', '300/007/PBK/IV/2026', NULL, NULL, 'qr_a39c179effc0528b87cbd7b7ce57dd8a.png', 'a39c179effc0528b87cbd7b7ce57dd8a', NULL),
(42, 'Surat Sktm Sekolah', NULL, NULL, 5, '{\"jenis_sktm\":\"bpjs\",\"nama_lengkap\":\"joni priyadi\",\"nik\":\"51351365\",\"tempat_lahir\":\"suka mara\",\"tgl_lahir\":\"2026-04-02\",\"agama\":\"Islam\",\"pekerjaan\":\"Buruh\",\"alamat\":\"gfgad\",\"keperluan\":\"sakit\"}', 'disetujui', 0, '2026-04-22', 'Pengajuan Surat Surat Sktm Sekolah', '300/008/PBK/IV/2026', NULL, NULL, 'qr_d256e7ce969ce1bd22be7a65d93269b7.png', 'd256e7ce969ce1bd22be7a65d93269b7', NULL),
(43, 'Surat Kehilangan', NULL, NULL, 7, '{\"nama_pelapor\":\"ucup\",\"nik_pelapor\":\"986678998\",\"tempat_lahir_p\":\"bumi ayu\",\"tgl_lahir_p\":\"1999-10-14\",\"pekerjaan_p\":\"mandor proyek\",\"alamat_p\":\"bumi ayu\",\"barang_hilang\":\"sepeda motor\",\"lokasi_hilang\":\"res area pringsewu\",\"tgl_hilang\":\"2014-05-12\",\"alasan_hilang\":\"ya hilang\"}', 'diproses', 0, '2026-04-27', 'Pengajuan Surat Surat Kehilangan', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `template_surat`
--

CREATE TABLE `template_surat` (
  `id` int NOT NULL,
  `nama_template` varchar(255) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `template_surat`
--

INSERT INTO `template_surat` (`id`, `nama_template`, `file_path`) VALUES
(1, 'Surat Keterangan Domisili', 'domisili.php'),
(2, 'Surat Pengantar SKCK', 'skck.php'),
(3, 'Surat Keterangan Mati', 'sktm.php'),
(5, 'Surat Keterangan Tidak Mampu', 'surat_sktm.php'),
(6, 'Surat Jual beli Tanah', 'surat_jual_beli_tanah.php'),
(7, 'Surat Kehilangan', 'surat_kehilangan.php'),
(8, 'Surat Keterangan belum Menikah', 'surat_belum_menikah.php'),
(9, 'Surat Keterangan Beda Data', 'surat_beda_data.php');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `masyarakat`
--
ALTER TABLE `masyarakat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengajuan_masyarakat` (`id_masyarakat`),
  ADD KEY `fk_pengajuan_template` (`id_template`);

--
-- Indexes for table `template_surat`
--
ALTER TABLE `template_surat`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `masyarakat`
--
ALTER TABLE `masyarakat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `template_surat`
--
ALTER TABLE `template_surat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `fk_pengajuan_masyarakat` FOREIGN KEY (`id_masyarakat`) REFERENCES `masyarakat` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pengajuan_template` FOREIGN KEY (`id_template`) REFERENCES `template_surat` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
