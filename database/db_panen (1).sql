-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 22, 2025 at 02:23 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_panen`
--

-- --------------------------------------------------------

--
-- Table structure for table `distribusi`
--

CREATE TABLE `distribusi` (
  `id_distribusi` int NOT NULL,
  `tanggal_distribusi` date NOT NULL,
  `tujuan` varchar(255) NOT NULL,
  `no_kendaraan` varchar(50) NOT NULL,
  `supir` varchar(100) NOT NULL,
  `jumlah_distribusi` int NOT NULL,
  `status_pengiriman` varchar(50) NOT NULL,
  `create_by` int NOT NULL,
  `id_peron` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `distribusi`
--

INSERT INTO `distribusi` (`id_distribusi`, `tanggal_distribusi`, `tujuan`, `no_kendaraan`, `supir`, `jumlah_distribusi`, `status_pengiriman`, `create_by`, `id_peron`) VALUES
(5, '2025-01-22', 'Aceh1', 'BA 1010 ABC', 'Hendra', 2, 'Dalam Perjalanan', 6, 2),
(6, '2025-01-22', 'Aceh2', 'BA 1010 ABC', 'Hendra', 2, 'Dalam Perjalanan', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `panen`
--

CREATE TABLE `panen` (
  `id_panen` int NOT NULL,
  `tanggal_panen` date NOT NULL,
  `kebun` varchar(100) NOT NULL,
  `berat_hasil` decimal(10,2) NOT NULL,
  `jumlah_tandan` int NOT NULL,
  `kondisi_panen` varchar(30) NOT NULL,
  `catatan` text,
  `create_by` int NOT NULL,
  `id_peron` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `panen`
--

INSERT INTO `panen` (`id_panen`, `tanggal_panen`, `kebun`, `berat_hasil`, `jumlah_tandan`, `kondisi_panen`, `catatan`, `create_by`, `id_peron`) VALUES
(7, '2025-01-22', 'Aceh', '2.00', 2, '', 'Bagus', 7, 2),
(8, '2025-01-22', 'Aceh2', '2.00', 2, '', 'Bagus', 7, 2),
(9, '2025-01-22', 'Aceh3', '2.00', 2, '', 'Bagus', 7, 2),
(10, '2025-01-22', 'Aceh 4', '2.00', 2, '', 'Bagus', 7, 2),
(11, '2025-01-22', 'Aceh5', '2.00', 2, '', 'Bagus', 7, 2),
(12, '2025-01-22', 'Aceh6', '2.00', 2, '', 'Bagus', 7, 2),
(13, '2025-01-22', 'Aceh7', '2.00', 2, '', 'Bagus', 7, 2),
(14, '2025-01-22', 'Aceh8', '2.00', 2, '', 'Bagus', 7, 2),
(15, '2025-01-22', 'Aceh9', '2.00', 2, '', '0', 7, 2),
(19, '2025-01-22', 'Aceh8', '2.00', 2, 'Bagus', 'aa', 7, 3);

-- --------------------------------------------------------

--
-- Table structure for table `peron`
--

CREATE TABLE `peron` (
  `id_peron` int NOT NULL,
  `nama_peron` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `peron`
--

INSERT INTO `peron` (`id_peron`, `nama_peron`) VALUES
(2, 'Peron 1'),
(3, 'Peron 2');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `id_peron` int DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `name`, `username`, `id_peron`, `password`, `level`) VALUES
(2, 'pimpinan', 'pimpinan', NULL, '59335c9f58c78597ff73f6706c6c8fa278e08b3a', '2'),
(6, 'admin1', 'admin1', 2, '6c7ca345f63f835cb353ff15bd6c5e052ec08e7a', '1'),
(7, 'petugas1', 'petugas1', 2, '2158ff877fab5522711af28b273283033302c577', '3'),
(8, 'admin2', 'admin2', 3, '315f166c5aca63a157f7d41007675cb44a948b33', '1'),
(9, 'petugas2', 'petugas2', 3, 'b37db86413cd76093be82f93f9cdeccb6de0e730', '3');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id_distribusi`),
  ADD KEY `create_by` (`create_by`),
  ADD KEY `fk_distribusi_peron` (`id_peron`);

--
-- Indexes for table `panen`
--
ALTER TABLE `panen`
  ADD PRIMARY KEY (`id_panen`),
  ADD KEY `fk_create_by` (`create_by`),
  ADD KEY `fk_panen_peron` (`id_peron`);

--
-- Indexes for table `peron`
--
ALTER TABLE `peron`
  ADD PRIMARY KEY (`id_peron`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `fk_user_peron` (`id_peron`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id_distribusi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `panen`
--
ALTER TABLE `panen`
  MODIFY `id_panen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `peron`
--
ALTER TABLE `peron`
  MODIFY `id_peron` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD CONSTRAINT `distribusi_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_distribusi_peron` FOREIGN KEY (`id_peron`) REFERENCES `peron` (`id_peron`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `panen`
--
ALTER TABLE `panen`
  ADD CONSTRAINT `fk_create_by` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_panen_peron` FOREIGN KEY (`id_peron`) REFERENCES `peron` (`id_peron`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `panen_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_peron` FOREIGN KEY (`id_peron`) REFERENCES `peron` (`id_peron`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
