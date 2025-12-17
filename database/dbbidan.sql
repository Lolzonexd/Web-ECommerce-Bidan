-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 17, 2025 at 04:15 PM
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
(6, 'Pelayanan Ibu Nifas', NULL, 80000, 1);

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
(1, 'Admin1', 'Admin@gmail.com', '123', 'admin', NULL, NULL),
(2, '062430701386', 'tukangdab102@gmail.com', '$2y$10$H7gZ4GzjMJTNU.DJEfJ/ce3exGXqlMlIl2pTr.y3cd68zvpu5CDTO', 'admin', NULL, NULL),
(3, 'ajax', 'pok@gmail.com', '$2y$10$ZtwLMEgBiAskmLthqjJt8e0PYTKHPe8t9FmlnhSfmvzL1QU1UwCFO', 'user', NULL, NULL),
(4, 'hanif', 'hanif@gmail.com', '$2y$10$ec/5hIFs9Soi2SRFPBbCg.PkwLpjveIIejs9089/E.XXWmQbjhzoK', 'user', NULL, NULL),
(5, 'hanif2', 'hanif2@gmail.com', '$2y$10$RruHprEDUvdqEvZtZlrLQ.xUcsl6.H/64gghVxnTrwJLRJJI6MsbO', 'user', NULL, NULL),
(6, 'lolzonexd', 'lolzonexd@gmail.com', '$2y$10$eGub7fKn/eChIaCgovfL7u5Jw.OoZaa00l3zKBrUeD8G1x5.7KkVK', 'admin', NULL, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `layanan`
--
ALTER TABLE `layanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `janji`
--
ALTER TABLE `janji`
  ADD CONSTRAINT `janji_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `janji_ibfk_2` FOREIGN KEY (`layanan_id`) REFERENCES `layanan` (`id`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`janji_id`) REFERENCES `janji` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
