-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 18, 2025 at 01:51 PM
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
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `distribusi`
--

INSERT INTO `distribusi` (`id_distribusi`, `tanggal_distribusi`, `tujuan`, `no_kendaraan`, `supir`, `jumlah_distribusi`, `status_pengiriman`, `create_by`) VALUES
(4, '2025-01-18', '2', '2', '2', 2, 'Dalam Perjalanan', 1);

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
  `kondisi_panen` varchar(50) DEFAULT NULL,
  `catatan` text,
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `panen`
--

INSERT INTO `panen` (`id_panen`, `tanggal_panen`, `kebun`, `berat_hasil`, `jumlah_tandan`, `kondisi_panen`, `catatan`, `create_by`) VALUES
(4, '2025-01-18', 'tes1', '2.00', 2, '2', '2', 1),
(5, '2025-01-18', 'peron 2', '2.00', 2, '0', 'Baik', 4);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `tanggal_terdaftar` date NOT NULL,
  `jenis_pelanggan` enum('individu','perusahaan') NOT NULL,
  `status_aktif` enum('aktif','non aktif') NOT NULL DEFAULT 'aktif',
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_lengkap`, `alamat`, `no_hp`, `tanggal_terdaftar`, `jenis_pelanggan`, `status_aktif`, `create_by`) VALUES
(1, 'Budi1', 'Padang', '082165443677', '2025-01-19', 'perusahaan', 'aktif', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `id_pembelian` int NOT NULL,
  `tanggal_pembelian` date NOT NULL,
  `nama_pembeli` varchar(255) NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `jumlah` int NOT NULL,
  `harga_satuan` decimal(10,2) NOT NULL,
  `total_harga` decimal(15,2) GENERATED ALWAYS AS ((`jumlah` * `harga_satuan`)) STORED,
  `metode_pembayaran` varchar(50) NOT NULL,
  `status_pembayaran` enum('selesai','dp','pending') NOT NULL DEFAULT 'pending',
  `catatan` text,
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`id_pembelian`, `tanggal_pembelian`, `nama_pembeli`, `nama_produk`, `jumlah`, `harga_satuan`, `metode_pembayaran`, `status_pembayaran`, `catatan`, `create_by`) VALUES
(2, '2025-01-18', 'Budi', 'Micellar Water', 10, '5000.00', 'tunai', 'selesai', 'Done', 1);

-- --------------------------------------------------------

--
-- Table structure for table `petani`
--

CREATE TABLE `petani` (
  `id_petani` int NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `tgl_gabung` date NOT NULL,
  `status_keanggotaan` enum('Aktif','Non Aktif') NOT NULL DEFAULT 'Aktif',
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `petani`
--

INSERT INTO `petani` (`id_petani`, `nama_lengkap`, `alamat`, `no_hp`, `tgl_gabung`, `status_keanggotaan`, `create_by`) VALUES
(2, 'Khairul Huda1', 'Lhoksuemawe\r\nBlang Pulo', '082165443677', '2025-01-18', 'Aktif', 1);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int NOT NULL,
  `nama_supplier` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` enum('aktif','non aktif') NOT NULL DEFAULT 'aktif',
  `create_by` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `peron` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `level` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `name`, `username`, `peron`, `password`, `level`) VALUES
(1, 'admin peron 1', 'admin', '1', 'd033e22ae348aeb5660fc2140aec35850c4da997', '1'),
(2, 'pimpinan', 'pimpinan', NULL, '59335c9f58c78597ff73f6706c6c8fa278e08b3a', '2'),
(4, 'admin peron 2', 'admin2', '2', '315f166c5aca63a157f7d41007675cb44a948b33', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD PRIMARY KEY (`id_distribusi`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `panen`
--
ALTER TABLE `panen`
  ADD PRIMARY KEY (`id_panen`),
  ADD KEY `fk_create_by` (`create_by`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`id_pembelian`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `petani`
--
ALTER TABLE `petani`
  ADD PRIMARY KEY (`id_petani`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`),
  ADD KEY `create_by` (`create_by`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `distribusi`
--
ALTER TABLE `distribusi`
  MODIFY `id_distribusi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `panen`
--
ALTER TABLE `panen`
  MODIFY `id_panen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `id_pembelian` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `petani`
--
ALTER TABLE `petani`
  MODIFY `id_petani` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `distribusi`
--
ALTER TABLE `distribusi`
  ADD CONSTRAINT `distribusi_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `panen`
--
ALTER TABLE `panen`
  ADD CONSTRAINT `fk_create_by` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `panen_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `petani`
--
ALTER TABLE `petani`
  ADD CONSTRAINT `petani_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `supplier`
--
ALTER TABLE `supplier`
  ADD CONSTRAINT `supplier_ibfk_1` FOREIGN KEY (`create_by`) REFERENCES `user` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
