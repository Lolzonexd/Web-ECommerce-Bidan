-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 08, 2026 at 01:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbbidan`
--

-- --------------------------------------------------------

--
-- Table structure for table `biodata`
--

CREATE TABLE `biodata` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nik` varchar(16) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `biodata`
--

INSERT INTO `biodata` (`id`, `user_id`, `nama_lengkap`, `nik`, `tanggal_lahir`, `jenis_kelamin`, `no_hp`, `alamat`, `created_at`, `updated_at`) VALUES
(0, 3, 'Al-Hakim', '1321453254536', '2025-12-30', 'Laki-Laki', '082147621751', 'jalan-jalan', '2025-12-25 15:50:21', '2025-12-25 15:50:21');

-- --------------------------------------------------------

--
-- Table structure for table `janji`
--

CREATE TABLE `janji` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `layanan_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `status` enum('pending','dibayar','selesai','batal') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `janji`
--

INSERT INTO `janji` (`id`, `user_id`, `layanan_id`, `tanggal`, `jam`, `status`, `created_at`) VALUES
(4, 7, 1, '2025-12-18', '16:00:00', 'selesai', '2025-12-18 03:55:48'),
(8, 8, 4, '2026-01-08', '12:10:00', 'pending', '2026-01-08 00:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `layanan`
--

CREATE TABLE `layanan` (
  `id` int(11) NOT NULL,
  `nama_layanan` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga` int(11) NOT NULL,
  `aktif` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `layanan`
--

INSERT INTO `layanan` (`id`, `nama_layanan`, `deskripsi`, `harga`, `aktif`) VALUES
(1, 'Cek Kehamilan', NULL, 50000, 1),
(2, 'Persalinan', NULL, 1500000, 1),
(3, 'Imunisasi', NULL, 75000, 1),
(4, 'Mom & Baby Spa', NULL, 100000, 1),
(5, 'KB', NULL, 60000, 1),
(6, 'Pelayanan Ibu Nifas', '', 100000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `janji_id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `gross_amount` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `janji_id`, `order_id`, `gross_amount`, `status`, `created_at`) VALUES
(11, 8, 'TRX-8-1767830664', 100000, 'pending', '2026-01-08 00:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('admin','user','bidan','') NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `remember_expire` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `email`, `password`, `level`, `remember_token`, `remember_expire`) VALUES
(4, 'hanif', 'hanif@gmail.com', '$2y$10$ec/5hIFs9Soi2SRFPBbCg.PkwLpjveIIejs9089/E.XXWmQbjhzoK', 'user', NULL, NULL),
(5, 'hanif2', 'hanif2@gmail.com', '$2y$10$RruHprEDUvdqEvZtZlrLQ.xUcsl6.H/64gghVxnTrwJLRJJI6MsbO', 'user', NULL, NULL),
(6, 'lolzonexd', 'lolzonexd@gmail.com', '$2y$10$eGub7fKn/eChIaCgovfL7u5Jw.OoZaa00l3zKBrUeD8G1x5.7KkVK', 'admin', NULL, NULL),
(7, 'Hakim', 'hakim2@gmail.com', '$2y$10$6l0BpE7wsQaVyXqD08mArO6UWFqdQZ7UbsF55C4uW0Qfd6WrX/TWC', 'user', NULL, NULL),
(8, 'Falaki', 'falakigeming@gmail.com', '$2y$10$Y2KDHe79iOCli5TvTk1hNea6Zu3LrDJmgzP.C6SbddwGWogPnIhVq', 'user', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `janji`
--
ALTER TABLE `janji`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `layanan_id` (`layanan_id`);

--
-- Indexes for table `layanan`
--
ALTER TABLE `layanan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `janji_id` (`janji_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `Email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `janji`
--
ALTER TABLE `janji`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `janji`
--
ALTER TABLE `janji`
  ADD CONSTRAINT `janji_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `janji_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`janji_id`) REFERENCES `janji` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
