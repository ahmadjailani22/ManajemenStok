-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Bulan Mei 2026 pada 03.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

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
-- Prosedur
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
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id_barang` int(11) NOT NULL,
  `kode_barang` varchar(50) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `satuan` varchar(20) NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `stok_minimum` int(11) NOT NULL DEFAULT 0,
  `stok_saat_ini` int(11) NOT NULL DEFAULT 0,
  `letak_rak` varchar(50) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `barcode`, `nama_barang`, `id_kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok_minimum`, `stok_saat_ini`, `letak_rak`, `gambar`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BRG-001', '1234567890123', 'Kipas Angin', 1, 'unit', 150000.00, 200000.00, 5, 30, 'A-01', NULL, 'aktif', '2026-04-05 23:01:34', '2026-04-05 23:01:34'),
(2, 'BRG-002', '1234567890124', 'Keripik Singkong', 2, 'bungkus', 5000.00, 7500.00, 10, 58, 'B-02', NULL, 'aktif', '2026-04-05 23:01:34', '2026-05-04 01:36:25'),
(3, 'BRG-003', '1234567890125', 'Kemeja Putih', 3, 'pcs', 75000.00, 125000.00, 3, 0, 'C-03', NULL, 'aktif', '2026-04-05 23:01:34', '2026-05-04 01:36:32'),
(4, 'BRG-004', '1234567890126', 'Buku Tulis', 4, 'buah', 4000.00, 7000.00, 20, 30, 'D-04', NULL, 'aktif', '2026-04-05 23:01:34', '2026-05-04 01:36:06'),
(5, 'BRG-005', NULL, 'Kopi', 2, 'pcs', 3000.00, 5000.00, 10, 40, 'A-01', NULL, 'aktif', '2026-04-15 15:55:01', '2026-04-28 05:41:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang_keluar`
--

CREATE TABLE `barang_keluar` (
  `id_keluar` int(11) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_jual_saat_ini` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) GENERATED ALWAYS AS (`jumlah` * `harga_jual_saat_ini`) STORED,
  `tanggal_keluar` datetime NOT NULL,
  `customer` varchar(100) DEFAULT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_keluar`
--

INSERT INTO `barang_keluar` (`id_keluar`, `kode_transaksi`, `id_barang`, `id_user`, `jumlah`, `harga_jual_saat_ini`, `tanggal_keluar`, `customer`, `keterangan`) VALUES
(1, 'TRX-OUT-001', 3, 2, 1, 125000.00, '2026-04-05 23:01:34', 'Andi', 'Penjualan retail'),
(2, 'TRX-OUT-002', 4, 2, 5, 7000.00, '2026-04-05 23:01:34', 'Sekolah Maju', 'Penjualan partai'),
(3, 'TRX-OUT-003', 4, 1, 1, 7000.00, '2026-04-28 00:00:00', NULL, NULL),
(4, 'TRX-OUT-004', 5, 1, 5, 5000.00, '2026-04-28 00:00:00', NULL, NULL);

--
-- Trigger `barang_keluar`
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
-- Struktur dari tabel `barang_masuk`
--

CREATE TABLE `barang_masuk` (
  `id_masuk` int(11) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli_saat_ini` decimal(12,2) NOT NULL,
  `total_harga` decimal(12,2) GENERATED ALWAYS AS (`jumlah` * `harga_beli_saat_ini`) STORED,
  `tanggal_masuk` datetime NOT NULL,
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `barang_masuk`
--

INSERT INTO `barang_masuk` (`id_masuk`, `kode_transaksi`, `id_barang`, `id_supplier`, `id_user`, `jumlah`, `harga_beli_saat_ini`, `tanggal_masuk`, `keterangan`) VALUES
(1, 'TRX-IN-001', 1, 1, 1, 20, 145000.00, '2026-04-05 23:01:34', 'Pembelian awal'),
(2, 'TRX-IN-002', 2, 2, 2, 50, 4800.00, '2026-04-05 23:01:34', 'Stok awal'),
(3, 'TRX-IN-003', 3, 1, 1, 1, 75000.00, '2026-04-28 00:00:00', NULL),
(4, 'TRX-IN-004', 3, 2, 1, 1, 75000.00, '2026-04-28 00:00:00', NULL);

--
-- Trigger `barang_masuk`
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
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Elektronik', 'Produk elektronik seperti TV, kipas, dll', '2026-04-05 23:01:33', NULL),
(2, 'Makanan', 'Produk makanan ringan dan minuman', '2026-04-05 23:01:33', NULL),
(3, 'Pakaian', 'Pakaian pria, wanita, anak', '2026-04-05 23:01:33', NULL),
(4, 'Alat Tulis', 'Peralatan kantor dan sekolah', '2026-04-05 23:01:33', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_stok`
--

CREATE TABLE `log_stok` (
  `id_log` bigint(20) NOT NULL,
  `id_barang` int(11) NOT NULL,
  `stok_sebelum` int(11) NOT NULL,
  `stok_sesudah` int(11) NOT NULL,
  `perubahan` int(11) NOT NULL,
  `tipe_transaksi` enum('masuk','keluar','penyesuaian') NOT NULL,
  `referensi_id` int(11) DEFAULT NULL,
  `waktu` datetime NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `log_stok`
--

INSERT INTO `log_stok` (`id_log`, `id_barang`, `stok_sebelum`, `stok_sesudah`, `perubahan`, `tipe_transaksi`, `referensi_id`, `waktu`, `id_user`) VALUES
(1, 1, 10, 30, 20, 'masuk', 1, '2026-04-05 23:01:34', 1),
(2, 2, 8, 58, 50, 'masuk', 2, '2026-04-05 23:01:34', 2),
(3, 3, 2, 1, -1, 'keluar', 1, '2026-04-05 23:01:34', 2),
(4, 4, 25, 20, -5, 'keluar', 2, '2026-04-05 23:01:34', 2),
(5, 4, 20, 19, -1, 'keluar', 3, '2026-04-28 12:41:30', 1),
(6, 5, 50, 45, -5, 'keluar', 4, '2026-04-28 12:41:40', 1),
(7, 3, 1, 2, 1, 'masuk', 3, '2026-04-28 12:45:35', 1),
(8, 3, 3, 4, 1, 'masuk', 4, '2026-04-28 12:45:45', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000002_create_jobs_table', 1),
(2, '2026_04_06_134924_create_sessions_table', 2),
(3, '2026_04_20_140956_create_suppliers_table', 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('qlRZ2IatwMDowfB3CAE41cQ0nykhz2qjDqkjv3mg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJLQnJkbUtNUThFOE1zUVVXYVBJWWltUkN5cEJtcW9PaXFCbno0cjRmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9iYXJhbmciLCJyb3V0ZSI6ImJhcmFuZy5pbmRleCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1777859719);

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `kode_supplier` varchar(20) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `kontak_person` varchar(100) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `kode_supplier`, `nama_supplier`, `kontak_person`, `telepon`, `alamat`, `email`, `status`, `created_at`, `updated_at`) VALUES
(1, 'SUP-001', 'PT Elektronik Jaya', 'Budi Santoso', '081234567890', 'Jl. Raya No. 1 Jakarta', NULL, 'aktif', '2026-04-05 23:01:33', NULL),
(2, 'SUP-002', 'CV Sumber Makanan', 'Siti Aminah', '081298765432', 'Jl. Pangan No. 2 Bandung', NULL, 'aktif', '2026-04-05 23:01:33', NULL),
(3, 'SUP-003', 'UD Sandang Murah', 'Ahmad Fauzi', '081355577788', 'Jl. Garmen No. 3 Surabaya', NULL, 'aktif', '2026-04-05 23:01:33', NULL),
(4, 'SUP-004', 'pt laguna guna', NULL, '085182474887', 'simpang mangga', 'arielgaming3632r@gmail.com', 'aktif', '2026-05-04 01:37:28', '2026-05-04 01:37:28');

-- --------------------------------------------------------

--
-- Struktur dari tabel `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode_supplier` varchar(20) NOT NULL,
  `nama_supplier` varchar(150) NOT NULL,
  `alamat` text DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','staff','manager') NOT NULL DEFAULT 'staff',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator', 'admin@stok.com', 'admin', '2026-04-05 23:01:34', NULL),
(2, 'staff1', 'de9bf5643eabf80f4a56fda3bbb84483', 'Staff Gudang 1', 'staff1@stok.com', 'staff', '2026-04-05 23:01:34', NULL),
(3, 'manager', '0795151defba7a4b5dfa89170de46277', 'Manajer Operasional', 'manager@stok.com', 'manager', '2026-04-05 23:01:34', NULL);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_dashboard_summary`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_dashboard_summary` (
`total_barang` bigint(21)
,`stok_menipis` bigint(21)
,`total_supplier` bigint(21)
,`transaksi_masuk_hari_ini` bigint(21)
,`transaksi_keluar_hari_ini` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_stok_terkini`
-- (Lihat di bawah untuk tampilan aktual)
--
CREATE TABLE `v_stok_terkini` (
`id_barang` int(11)
,`kode_barang` varchar(50)
,`nama_barang` varchar(100)
,`nama_kategori` varchar(50)
,`satuan` varchar(20)
,`stok_saat_ini` int(11)
,`stok_minimum` int(11)
,`status_stok` varchar(8)
,`harga_beli` decimal(12,2)
,`harga_jual` decimal(12,2)
,`letak_rak` varchar(50)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_dashboard_summary`
--
DROP TABLE IF EXISTS `v_dashboard_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_dashboard_summary`  AS SELECT (select count(0) from `barang` where `barang`.`status` = 'aktif') AS `total_barang`, (select count(0) from `barang` where `barang`.`stok_saat_ini` <= `barang`.`stok_minimum` and `barang`.`status` = 'aktif') AS `stok_menipis`, (select count(0) from `supplier` where `supplier`.`status` = 'aktif') AS `total_supplier`, (select count(0) from `barang_masuk` where cast(`barang_masuk`.`tanggal_masuk` as date) = curdate()) AS `transaksi_masuk_hari_ini`, (select count(0) from `barang_keluar` where cast(`barang_keluar`.`tanggal_keluar` as date) = curdate()) AS `transaksi_keluar_hari_ini` ;

-- --------------------------------------------------------

--
-- Struktur untuk view `v_stok_terkini`
--
DROP TABLE IF EXISTS `v_stok_terkini`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stok_terkini`  AS SELECT `b`.`id_barang` AS `id_barang`, `b`.`kode_barang` AS `kode_barang`, `b`.`nama_barang` AS `nama_barang`, `k`.`nama_kategori` AS `nama_kategori`, `b`.`satuan` AS `satuan`, `b`.`stok_saat_ini` AS `stok_saat_ini`, `b`.`stok_minimum` AS `stok_minimum`, CASE WHEN `b`.`stok_saat_ini` <= `b`.`stok_minimum` THEN 'Menipis' WHEN `b`.`stok_saat_ini` <= `b`.`stok_minimum` * 2 THEN 'Menengah' ELSE 'Aman' END AS `status_stok`, `b`.`harga_beli` AS `harga_beli`, `b`.`harga_jual` AS `harga_jual`, `b`.`letak_rak` AS `letak_rak` FROM (`barang` `b` left join `kategori` `k` on(`b`.`id_kategori` = `k`.`id_kategori`)) WHERE `b`.`status` = 'aktif' ;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `barang`
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
-- Indeks untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD UNIQUE KEY `uk_kode_transaksi_keluar` (`kode_transaksi`),
  ADD KEY `idx_tanggal_keluar` (`tanggal_keluar`),
  ADD KEY `fk_keluar_barang` (`id_barang`),
  ADD KEY `fk_keluar_user` (`id_user`);

--
-- Indeks untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD UNIQUE KEY `uk_kode_transaksi_masuk` (`kode_transaksi`),
  ADD KEY `idx_tanggal_masuk` (`tanggal_masuk`),
  ADD KEY `fk_masuk_barang` (`id_barang`),
  ADD KEY `fk_masuk_supplier` (`id_supplier`),
  ADD KEY `fk_masuk_user` (`id_user`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`),
  ADD UNIQUE KEY `uk_nama_kategori` (`nama_kategori`);

--
-- Indeks untuk tabel `log_stok`
--
ALTER TABLE `log_stok`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `idx_waktu` (`waktu`),
  ADD KEY `idx_tipe_transaksi` (`tipe_transaksi`),
  ADD KEY `fk_log_barang` (`id_barang`),
  ADD KEY `fk_log_user` (`id_user`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD UNIQUE KEY `uk_kode_supplier` (`kode_supplier`);

--
-- Indeks untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `suppliers_kode_supplier_unique` (`kode_supplier`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `uk_username` (`username`),
  ADD UNIQUE KEY `uk_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `log_stok`
--
ALTER TABLE `log_stok`
  MODIFY `id_log` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `fk_barang_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_keluar`
--
ALTER TABLE `barang_keluar`
  ADD CONSTRAINT `fk_keluar_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_keluar_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `barang_masuk`
--
ALTER TABLE `barang_masuk`
  ADD CONSTRAINT `fk_masuk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_masuk_supplier` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_masuk_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `log_stok`
--
ALTER TABLE `log_stok`
  ADD CONSTRAINT `fk_log_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_log_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
