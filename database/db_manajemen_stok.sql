-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 19, 2026 at 01:34 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_manajemen_stok`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `laporan_mutasi_stok` (IN `p_tanggal_mulai` DATE, IN `p_tanggal_akhir` DATE)   BEGIN
    -- Barang Masuk
    SELECT 
        'MASUK' AS tipe,
        bm.tanggal_masuk AS tanggal,
        b.kode_barang,
        b.nama_barang,
        bm.jumlah,
        bm.harga_beli_saat_ini AS harga,
        bm.total_harga,
        s.nama_supplier AS asal_tujuan,
        u.nama_lengkap AS petugas
    FROM barang_masuk bm
    JOIN barang b ON bm.id_barang = b.id_barang
    JOIN supplier s ON bm.id_supplier = s.id_supplier
    JOIN users u ON bm.id_user = u.id_user
    WHERE DATE(bm.tanggal_masuk) BETWEEN p_tanggal_mulai AND p_tanggal_akhir
    
    UNION ALL
    
    -- Barang Keluar
    SELECT 
        'KELUAR' AS tipe,
        bk.tanggal_keluar AS tanggal,
        b.kode_barang,
        b.nama_barang,
        bk.jumlah,
        bk.harga_jual_saat_ini AS harga,
        bk.total_harga,
        COALESCE(bk.customer, '-') AS asal_tujuan,
        u.nama_lengkap AS petugas
    FROM barang_keluar bk
    JOIN barang b ON bk.id_barang = b.id_barang
    JOIN users u ON bk.id_user = u.id_user
    WHERE DATE(bk.tanggal_keluar) BETWEEN p_tanggal_mulai AND p_tanggal_akhir
    
    ORDER BY tanggal DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `laporan_stok_menipis` ()   BEGIN
    SELECT 
        b.kode_barang,
        b.nama_barang,
        k.nama_kategori,
        b.stok_saat_ini,
        b.stok_minimum,
        (b.stok_minimum - b.stok_saat_ini) AS kekurangan,
        b.satuan
    FROM barang b
    LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
    WHERE b.stok_saat_ini <= b.stok_minimum 
        AND b.status = 'aktif'
    ORDER BY (b.stok_minimum - b.stok_saat_ini) DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `rekap_stok_per_kategori` ()   BEGIN
    SELECT 
        k.id_kategori,
        k.nama_kategori,
        COUNT(b.id_barang) AS jumlah_jenis_barang,
        SUM(b.stok_saat_ini) AS total_stok,
        SUM(b.stok_saat_ini * b.harga_beli) AS nilai_total_stok
    FROM kategori k
    LEFT JOIN barang b ON k.id_kategori = b.id_kategori AND b.status = 'aktif'
    GROUP BY k.id_kategori, k.nama_kategori
    ORDER BY total_stok DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int NOT NULL,
  `kode_barang` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nama_barang` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kategori` int DEFAULT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `stok_minimum` int NOT NULL DEFAULT '0',
  `stok_saat_ini` int NOT NULL DEFAULT '0',
  `letak_rak` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `barcode`, `nama_barang`, `id_kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok_minimum`, `stok_saat_ini`, `letak_rak`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BRG-001', '1234567890123', 'Kipas Angin', 1, 'unit', 150000.00, 200000.00, 5, 30, 'A-01', NULL, 'aktif', '2026-04-05 23:01:34', '2026-04-05 23:01:34'),
(2, 'BRG-002', '1234567890124', 'Keripik Singkong', 2, 'bungkus', 5000.00, 7500.00, 10, 58, 'B-02', NULL, 'nonaktif', '2026-04-05 23:01:34', '2026-04-15 15:55:44'),
(3, 'BRG-003', '1234567890125', 'Kemeja Putih', 3, 'pcs', 75000.00, 125000.00, 3, 1, 'C-03', NULL, 'aktif', '2026-04-05 23:01:34', '2026-04-05 23:01:34'),
(4, 'BRG-004', '1234567890126', 'Buku Tulis', 4, 'buah', 4000.00, 7000.00, 20, 20, 'D-04', NULL, 'aktif', '2026-04-05 23:01:34', '2026-04-05 23:01:34'),
(5, 'BRG-005', NULL, 'Kopi', 2, 'pcs', 3000.00, 5000.00, 10, 50, 'A-01', NULL, 'aktif', '2026-04-15 15:55:01', '2026-04-15 15:55:01');

-- --------------------------------------------------------

--
-- Table structure for table `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_keluar` int NOT NULL,
  `kode_transaksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` int NOT NULL,
  `id_user` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_jual_saat_ini` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) GENERATED ALWAYS AS ((`jumlah` * `harga_jual_saat_ini`)) STORED,
  `tanggal_keluar` datetime NOT NULL,
  `customer` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_keluar`, `kode_transaksi`, `id_barang`, `id_user`, `jumlah`, `harga_jual_saat_ini`, `tanggal_keluar`, `customer`, `keterangan`) VALUES
(1, 'TRX-OUT-001', 3, 2, 1, 125000.00, '2026-04-05 23:01:34', 'Andi', 'Penjualan retail'),
(2, 'TRX-OUT-002', 4, 2, 5, 7000.00, '2026-04-05 23:01:34', 'Sekolah Maju', 'Penjualan partai');

--
-- Triggers `barang_keluar`
--
DELIMITER $$
CREATE TRIGGER `cegah_stok_negatif` BEFORE INSERT ON `barang_keluar` FOR EACH ROW BEGIN
    DECLARE stok_saat_ini INT;
    
    SELECT stok_saat_ini INTO stok_saat_ini 
    FROM barang WHERE id_barang = NEW.id_barang;
    
    IF stok_saat_ini < NEW.jumlah THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Stok tidak mencukupi untuk transaksi keluar';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_stok_keluar` AFTER INSERT ON `barang_keluar` FOR EACH ROW BEGIN
    DECLARE stok_awal INT;
    
    -- Ambil stok sebelum update
    SELECT stok_saat_ini INTO stok_awal 
    FROM barang WHERE id_barang = NEW.id_barang;
    
    -- Update stok barang
    UPDATE barang 
    SET stok_saat_ini = stok_saat_ini - NEW.jumlah,
        updated_at = NOW()
    WHERE id_barang = NEW.id_barang;
    
    -- Insert ke log stok
    INSERT INTO log_stok (id_barang, stok_sebelum, stok_sesudah, perubahan, tipe_transaksi, referensi_id, waktu, id_user)
    VALUES (NEW.id_barang, stok_awal, stok_awal - NEW.jumlah, -NEW.jumlah, 'keluar', NEW.id_keluar, NOW(), NEW.id_user);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` int NOT NULL,
  `kode_transaksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_barang` int NOT NULL,
  `id_supplier` int NOT NULL,
  `id_user` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga_beli_saat_ini` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) GENERATED ALWAYS AS ((`jumlah` * `harga_beli_saat_ini`)) STORED,
  `tanggal_masuk` datetime NOT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `kode_transaksi`, `id_barang`, `id_supplier`, `id_user`, `jumlah`, `harga_beli_saat_ini`, `tanggal_masuk`, `keterangan`) VALUES
(1, 'TRX-IN-001', 1, 1, 1, 20, 145000.00, '2026-04-05 23:01:34', 'Pembelian awal'),
(2, 'TRX-IN-002', 2, 2, 2, 50, 4800.00, '2026-04-05 23:01:34', 'Stok awal');

--
-- Triggers `barang_masuk`
--
DELIMITER $$
CREATE TRIGGER `update_stok_masuk` AFTER INSERT ON `barang_masuk` FOR EACH ROW BEGIN
    DECLARE stok_awal INT;
    
    -- Ambil stok sebelum update
    SELECT stok_saat_ini INTO stok_awal 
    FROM barang WHERE id_barang = NEW.id_barang;
    
    -- Update stok barang
    UPDATE barang 
    SET stok_saat_ini = stok_saat_ini + NEW.jumlah,
        updated_at = NOW()
    WHERE id_barang = NEW.id_barang;
    
    -- Insert ke log stok
    INSERT INTO log_stok (id_barang, stok_sebelum, stok_sesudah, perubahan, tipe_transaksi, referensi_id, waktu, id_user)
    VALUES (NEW.id_barang, stok_awal, stok_awal + NEW.jumlah, NEW.jumlah, 'masuk', NEW.id_masuk, NOW(), NEW.id_user);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int NOT NULL,
  `nama_kategori` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Produk elektronik seperti TV, kipas, dll', '2026-04-05 23:01:33', NULL),
(2, 'Makanan', 'Produk makanan ringan dan minuman', '2026-04-05 23:01:33', NULL),
(3, 'Pakaian', 'Pakaian pria, wanita, anak', '2026-04-05 23:01:33', NULL),
(4, 'Alat Tulis', 'Peralatan kantor dan sekolah', '2026-04-05 23:01:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `log_stok`
--

CREATE TABLE `log_stok` (
  `id_log` bigint NOT NULL,
  `id_barang` int NOT NULL,
  `stok_sebelum` int NOT NULL,
  `stok_sesudah` int NOT NULL,
  `perubahan` int NOT NULL,
  `tipe_transaksi` enum('masuk','keluar','penyesuaian') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referensi_id` int DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `id_user` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_stok`
--

INSERT INTO `log_stok` (`id_log`, `id_barang`, `stok_sebelum`, `stok_sesudah`, `perubahan`, `tipe_transaksi`, `referensi_id`, `waktu`, `id_user`) VALUES
(1, 1, 10, 30, 20, 'masuk', 1, '2026-04-05 23:01:34', 1),
(2, 2, 8, 58, 50, 'masuk', 2, '2026-04-05 23:01:34', 2),
(3, 3, 2, 1, -1, 'keluar', 1, '2026-04-05 23:01:34', 2),
(4, 4, 25, 20, -5, 'keluar', 2, '2026-04-05 23:01:34', 2);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000002_create_jobs_table', 1),
(2, '2026_04_06_134924_create_sessions_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4dTDDUt1xABf4iA6Kkd7NuuLkXOgM799lYVnDgP5', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiJ4MzBYVzRXdW5mbHl0ZmEwSHpEYXFCMXVZU2hHS1N1ckFVRm5UR0JtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1775665889),
('5si7NqLvAPVQDfyLUG6Xn7zrMKPaUHgWNSzI8oPU', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiJXN1h2Y2JySGExc3M4UDF1cnZPTkxNMnp5VThTZW54alBKbkZVN1ZBIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1776054002),
('9RhtSEAe728bscf7yZImv2pwDmdgR9No19Fn1Z3W', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiJwcEZlZ2VQNHR3dXpOYldHTThPV0tOc0ZrWW0xQzFQUmQ0OTdtNm1OIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1775998188),
('Id9uBcmLjCkHaDCjbmayS50VEhtebZa3xbvbf69z', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiJqRnpNSnFOOWhhWk41OXZ2aURNRXVzM0RMZVVqOEdHUUUzcDZHVE1yIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1775839406),
('Kf0iQs1RfuTgGTdeC2S4YA7JijewiYLNxP2HwEA6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJ0QkszeDY4d2FXOVdKd2trU0pFdE0yYVZoZzhhTEpUTzkyM3hBbmFUIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1776268589),
('MiGZKKRhOAvlctLxucB8vcbj2nMl7rIIkRUUkg7s', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiJIOE1HcXVUODdqN3FBaEVVU1RqdDNndHZCRjJvejZFRzN4ZFIyNXFHIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1776013136),
('VS6bBYaAz0oJpsbCM5W2K1kMIBeMIz6vEeA8jY7l', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'eyJfdG9rZW4iOiI5N3VFUGRXbFI0dWpZWklubHBlMW1iZGR2M0xxU3ZuSDhNQ211dmFCIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1775484547);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int NOT NULL,
  `kode_supplier` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_supplier` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak_person` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('aktif','nonaktif') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `kode_supplier`, `nama_supplier`, `kontak_person`, `telepon`, `alamat`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SUP-001', 'PT Elektronik Jaya', 'Budi Santoso', '081234567890', 'Jl. Raya No. 1 Jakarta', NULL, 'aktif', '2026-04-05 23:01:33', NULL),
(2, 'SUP-002', 'CV Sumber Makanan', 'Siti Aminah', '081298765432', 'Jl. Pangan No. 2 Bandung', NULL, 'aktif', '2026-04-05 23:01:33', NULL),
(3, 'SUP-003', 'UD Sandang Murah', 'Ahmad Fauzi', '081355577788', 'Jl. Garmen No. 3 Surabaya', NULL, 'aktif', '2026-04-05 23:01:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','staff','manager') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin@stok.com', 'admin', '2026-04-05 23:01:34', NULL),
(2, 'staff1', 'de9bf5643eabf80f4a56fda3bbb84483', 'Staff Gudang 1', 'staff1@stok.com', 'staff', '2026-04-05 23:01:34', NULL),
(3, 'manager', '0795151defba7a4b5dfa89170de46277', 'Manajer Operasional', 'manager@stok.com', 'manager', '2026-04-05 23:01:34', NULL);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_dashboard_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_dashboard_summary` (
`total_barang` bigint
,`stok_menipis` bigint
,`total_supplier` bigint
,`transaksi_masuk_hari_ini` bigint
,`transaksi_keluar_hari_ini` bigint
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_stok_terkini`
-- (See below for the actual view)
--
CREATE TABLE `v_stok_terkini` (
`id_barang` int
,`kode_barang` varchar(50)
,`nama_barang` varchar(100)
,`nama_kategori` varchar(50)
,`satuan` varchar(20)
,`stok_saat_ini` int
,`stok_minimum` int
,`status_stok` varchar(8)
,`harga_beli` decimal(12,2)
,`harga_jual` decimal(12,2)
,`letak_rak` varchar(50)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD UNIQUE KEY `uk_kode_barang` (`kode_barang`),
  ADD UNIQUE KEY `uk_barcode` (`barcode`),
  ADD KEY `idx_kode_barang` (`kode_barang`),
  ADD KEY `idx_nama_barang` (`nama_barang`),
  ADD KEY `idx_stok_saat_ini` (`stok_saat_ini`),
  ADD KEY `fk_barang_kategori` (`id_kategori`);

--
-- Indexes for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD UNIQUE KEY `uk_kode_transaksi_keluar` (`kode_transaksi`),
  ADD KEY `idx_tanggal_keluar` (`tanggal_keluar`),
  ADD KEY `fk_keluar_barang` (`id_barang`),
  ADD KEY `fk_keluar_user` (`id_user`);

--
-- Indexes for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD UNIQUE KEY `uk_kode_transaksi_masuk` (`kode_transaksi`),
  ADD KEY `idx_tanggal_masuk` (`tanggal_masuk`),
  ADD KEY `fk_masuk_barang` (`id_barang`),
  ADD KEY `fk_masuk_supplier` (`id_supplier`),
  ADD KEY `fk_masuk_user` (`id_user`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `uk_nama_kategori` (`nama_kategori`);

--
-- Indexes for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_waktu` (`waktu`),
  ADD KEY `idx_tipe_transaksi` (`tipe_transaksi`),
  ADD KEY `fk_log_barang` (`id_barang`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD UNIQUE KEY `uk_kode_supplier` (`kode_supplier`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uk_username` (`username`),
  ADD UNIQUE KEY `uk_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_keluar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_masuk` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `log_stok`
--
ALTER TABLE `log_stok`
  MODIFY `id_log` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Structure for view `v_dashboard_summary`
--
DROP TABLE IF EXISTS `v_dashboard_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_dashboard_summary`  AS SELECT (select count(0) from `barang` where (`barang`.`status` = 'aktif')) AS `total_barang`, (select count(0) from `barang` where ((`barang`.`stok_saat_ini` <= `barang`.`stok_minimum`) and (`barang`.`status` = 'aktif'))) AS `stok_menipis`, (select count(0) from `supplier` where (`supplier`.`status` = 'aktif')) AS `total_supplier`, (select count(0) from `barang_masuk` where (cast(`barang_masuk`.`tanggal_masuk` as date) = curdate())) AS `transaksi_masuk_hari_ini`, (select count(0) from `barang_keluar` where (cast(`barang_keluar`.`tanggal_keluar` as date) = curdate())) AS `transaksi_keluar_hari_ini` ;

-- --------------------------------------------------------

--
-- Structure for view `v_stok_terkini`
--
DROP TABLE IF EXISTS `v_stok_terkini`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok_terkini`  AS SELECT `b`.`id_barang` AS `id_barang`, `b`.`kode_barang` AS `kode_barang`, `b`.`nama_barang` AS `nama_barang`, `k`.`nama_kategori` AS `nama_kategori`, `b`.`satuan` AS `satuan`, `b`.`stok_saat_ini` AS `stok_saat_ini`, `b`.`stok_minimum` AS `stok_minimum`, (case when (`b`.`stok_saat_ini` <= `b`.`stok_minimum`) then 'Menipis' when (`b`.`stok_saat_ini` <= (`b`.`stok_minimum` * 2)) then 'Menengah' else 'Aman' end) AS `status_stok`, `b`.`harga_beli` AS `harga_beli`, `b`.`harga_jual` AS `harga_jual`, `b`.`letak_rak` AS `letak_rak` FROM (`barang` `b` left join `kategori` `k` on((`b`.`id_kategori` = `k`.`id_kategori`))) WHERE (`b`.`status` = 'aktif') ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `fk_keluar_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_keluar_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `fk_masuk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_masuk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_masuk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `log_stok`
--
ALTER TABLE `log_stok`
  ADD CONSTRAINT `fk_log_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
