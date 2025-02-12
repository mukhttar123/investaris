-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 12, 2025 at 04:31 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id` int UNSIGNED NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `stok` int UNSIGNED NOT NULL DEFAULT '0',
  `satuan` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('masuk','keluar') NOT NULL DEFAULT 'masuk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id`, `nama_barang`, `stok`, `satuan`, `created_at`, `status`) VALUES
(47, 'Barang1', 35, 'Pcs', '2025-01-13 01:39:46', 'masuk'),
(48, 'Barang2', 40, 'KG', '2025-01-13 01:40:07', 'masuk'),
(51, 'Barang4', 90, 'Pcs', '2025-01-15 02:20:43', 'masuk'),
(52, 'Barang5', 6, 'Rim', '2025-01-15 02:38:12', 'masuk'),
(54, 'Barang7', 0, 'Satuan', '2025-02-12 01:41:58', 'masuk'),
(55, 'Barang8', 0, 'Satuan', '2025-02-12 01:54:00', 'masuk'),
(56, 'Barang9', 0, 'Satuan', '2025-02-12 01:55:29', 'masuk'),
(57, 'Barang10', 0, 'Satuan', '2025-02-12 01:57:17', 'masuk');

-- --------------------------------------------------------

--
-- Table structure for table `history_barang`
--

CREATE TABLE `history_barang` (
  `id` int NOT NULL,
  `id_barang` int NOT NULL,
  `stok` int NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `status` enum('masuk','keluar') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `history_barang`
--

INSERT INTO `history_barang` (`id`, `id_barang`, `stok`, `satuan`, `status`, `created_at`) VALUES
(1, 47, 10, 'Pcs', 'masuk', '2025-01-13 01:39:46'),
(2, 48, 50, 'KG', 'masuk', '2025-01-13 01:40:07'),
(3, 47, 20, 'Pcs', 'masuk', '2025-01-13 01:40:27'),
(4, 47, 5, 'Pcs', 'masuk', '2025-01-13 01:47:31'),
(5, 48, 10, 'KG', 'keluar', '2025-01-13 01:47:54'),
(6, 51, 90, 'Pcs', 'masuk', '2025-01-15 02:20:43'),
(7, 52, 0, 'Rim', 'masuk', '2025-01-15 02:38:12'),
(8, 52, 6, 'Rim', 'masuk', '2025-01-15 02:38:39'),
(9, 53, 0, 'Satuan', 'masuk', '2025-02-12 01:32:57'),
(10, 54, 0, 'Satuan', 'masuk', '2025-02-12 01:41:58'),
(11, 55, 0, 'Satuan', 'masuk', '2025-02-12 01:54:00'),
(12, 56, 0, 'Satuan', 'masuk', '2025-02-12 01:55:29'),
(13, 57, 0, 'Satuan', 'masuk', '2025-02-12 01:57:17');

-- --------------------------------------------------------

--
-- Table structure for table `pengambilan`
--

CREATE TABLE `pengambilan` (
  `id` int NOT NULL,
  `tgl_ambil` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `jumlah_ambil` int NOT NULL,
  `id_barang` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengambilan`
--

INSERT INTO `pengambilan` (`id`, `tgl_ambil`, `jumlah_ambil`, `id_barang`) VALUES
(11, '2025-01-05 19:00:26', 10, 41),
(12, '2025-01-05 19:18:08', 10, 43),
(13, '2025-01-12 18:47:54', 10, 48);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`) VALUES
(2, 'admin', 'admin123', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_barang`
--
ALTER TABLE `history_barang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengambilan`
--
ALTER TABLE `pengambilan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `history_barang`
--
ALTER TABLE `history_barang`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengambilan`
--
ALTER TABLE `pengambilan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
