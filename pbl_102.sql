-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2025 at 08:14 AM
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
-- Database: `pbl_102`
--

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` int(11) NOT NULL,
  `ekspedisi` char(15) NOT NULL,
  `nomor_resi` varchar(20) NOT NULL,
  `nama_paket` varchar(50) NOT NULL,
  `nama_pemilik` varchar(25) NOT NULL,
  `no_hp_pemilik` varchar(15) NOT NULL,
  `tanggal_daftar` date NOT NULL,
  `tanggal_diambil` date DEFAULT NULL,
  `status` enum('tersedia','diambil','kadaluwarsa') NOT NULL,
  `tanggal_kadaluwarsa` date NOT NULL,
  `NIK` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `ekspedisi`, `nomor_resi`, `nama_paket`, `nama_pemilik`, `no_hp_pemilik`, `tanggal_daftar`, `tanggal_diambil`, `status`, `tanggal_kadaluwarsa`, `NIK`) VALUES
(1, 'JNE', '123456', 'rerte', 'habibi', '2147483647', '2024-11-25', NULL, 'kadaluwarsa', '2024-12-05', NULL),
(2, 'JNE', '55535d5f', 'rerte', 'habibi', '62895376448353', '2024-12-09', NULL, 'kadaluwarsa', '0000-00-00', NULL),
(3, 'JNE', '1', 'rerte', 'RAHAYU', '087742461212', '2024-12-01', NULL, 'kadaluwarsa', '2024-12-09', NULL),
(4, 'JNE', '489920we', 'rerte', 'w', '087784466578', '2024-12-02', NULL, 'kadaluwarsa', '2024-12-09', NULL),
(5, 'SiCepat', '184912aj', 'celana', 'sigit', '087784466578', '2024-12-02', NULL, 'kadaluwarsa', '2024-12-09', NULL),
(10, 'antaraja', '2834791084asd', 'Laptop', 'sugimin', '081294381294', '2024-12-09', NULL, 'kadaluwarsa', '2024-12-16', NULL),
(12, 'JNT', 'SPX232421213', 'Tuf gaming', 'berlian', '0895376448353', '2024-12-09', NULL, 'kadaluwarsa', '2024-12-16', NULL),
(13, 'shopee express', 'SPX632715381', 'Tank', 'Shandy', '081268851901', '2024-11-09', NULL, 'kadaluwarsa', '0000-00-00', NULL),
(14, 'antaraja', 'SPX212831283', 'baju hnm', 'hapiss', '08957691236173', '2024-12-09', '2024-12-12', 'kadaluwarsa', '2024-12-16', NULL),
(15, 'JNT', 'SPX4211515', 'IP 16', 'syarif', '08952382412', '2024-12-09', NULL, 'kadaluwarsa', '2024-12-16', NULL),
(16, 'AnterAja', 'SPX109389831338', 'helikopter', 'Noor Hidayah', '081346412732', '2024-12-12', '2024-12-19', 'diambil', '0000-00-00', NULL),
(17, 'POS Indonesia', 'SPXID04662792304B', 'kapa pesiar', 'sukijan', '087784466578', '2024-12-13', '2024-12-19', 'diambil', '0000-00-00', NULL),
(18, 'Lazada Express', 'SPXID04301086554B', 'Rahasia', 'sabet', '087784466578', '2024-12-16', '2024-12-19', 'diambil', '0000-00-00', NULL),
(61, 'Shopee Express', 'JP35151449590004', 'Rahasia', 'sabet', '087784466578', '2024-11-19', NULL, 'kadaluwarsa', '2024-11-26', NULL),
(62, 'JNT Express', 'JP35151449591', 'Stomp Scarves', 'Muhammad Syafiq', '087784466578', '2024-12-19', '2024-12-19', 'diambil', '0000-00-00', NULL),
(63, 'Shopee Express', 'SPXID0430108655sd4Be', 'Rahasia', '2', '087784466578', '2024-12-19', '2024-12-19', 'diambil', '0000-00-00', NULL),
(64, 'Shopee Express', 'JP3515144959000tt', 'Rahasia', 'et', '087784466578', '2024-12-19', '2024-12-19', 'diambil', '0000-00-00', NULL),
(65, 'Shopee Express', 'JP35151449590004272', '1', '2', '45782472488', '2024-12-19', '2024-12-19', 'diambil', '0000-00-00', NULL),
(66, 'Shopee Express', '61613F1F1254125', '1', '2', '41251516136', '2024-12-19', '2024-12-20', 'diambil', '0000-00-00', NULL),
(67, 'Shopee Express', '15136dg3151', 'Rahasia', '1', '1513647471', '2024-12-20', '2024-12-20', 'diambil', '0000-00-00', NULL),
(68, 'JNT', '363ydsgq46tewg', 'Rahasia', '5', '13631613747', '2024-12-20', '2024-12-20', 'diambil', '0000-00-00', NULL),
(69, 'Shopee Express', '352536tdg4qe', 'Rahasia', '1', '573582585857', '2024-12-20', '2024-12-20', 'diambil', '0000-00-00', NULL),
(70, 'Shopee Express', '4261367ndgwen', 'h', '4d', '86946406060', '2024-12-20', '2024-12-20', 'diambil', '0000-00-00', NULL),
(71, 'Shopee Express', 'SPXID04301086554B957', 'fjr6uue5', 'sabet', '087784466578', '2024-11-20', NULL, 'kadaluwarsa', '2024-11-27', NULL),
(72, 'JNT Express', 'JP3515144959', 'Stomp Scarves', 'Muhammad Syafiq', '087784466578', '2024-12-21', '2024-12-21', 'diambil', '0000-00-00', NULL),
(73, 'JNT', 'SPXID0430108655sd4B1', 'Rahasia', 'fulani', '087784466578', '2024-12-24', '2024-12-24', 'diambil', '0000-00-00', NULL),
(74, 'AntarAja', 'SPXID0430108655sd4B5', 'Rahasia', 'sabet', '41251514514', '2024-12-24', '2024-12-24', 'diambil', '0000-00-00', NULL),
(75, 'Shopee Express', 'SPXID04301086554B15', 'Rahasia', '2', '47134613513', '2024-12-24', '2024-12-24', 'diambil', '0000-00-00', NULL),
(76, 'Shopee Express', 'JP351514495900042342', 'Stomp Scarves', '1', '42352613634', '2024-12-24', '2024-12-30', 'diambil', '0000-00-00', NULL),
(77, 'jnt', 'JP35151449592643', 'Rahasia', 'Muhammad Syafiq', '48356675424', '2024-12-24', '2024-12-30', 'diambil', '0000-00-00', NULL),
(78, 'jnt', 'JP3515144959354y', 'Rahasia', 'Muhammad Syafiq', '124741884743', '2024-12-24', '2024-12-30', 'diambil', '0000-00-00', NULL),
(79, 'jnt', 'JP3515144959546rur', 'Rahasia', 'Muhammad Syafiq', '2487784466578', '2024-12-24', '2024-12-30', 'diambil', '0000-00-00', '21'),
(82, 'antaraja', '2222222eef', 'dsafs', 'dedw', '09876544321', '2024-12-30', '2024-12-31', 'diambil', '0000-00-00', '9'),
(83, 'ide', '2222222eefgdb', 'sfsfs', 'dedw', '09876544321', '2024-12-30', '2024-12-31', 'diambil', '0000-00-00', '1'),
(84, 'ninja', 'JP3515144959sfsgs', 'xcsvs', 'dedw', '09876544321', '2024-12-30', '2025-01-02', 'diambil', '0000-00-00', '9'),
(85, 'antaraja', '2222222ffrye', 'f', 'f', '09876544321', '2024-12-30', '2024-12-25', 'diambil', '0000-00-00', '9'),
(86, 'jne', '2222222fdvsdshbryn', 'r', 'f', '09876544321', '2024-12-30', '2025-01-02', 'diambil', '0000-00-00', '9'),
(87, 'jnt', '2222222csgarg2rwagvf', 'rd', 'r', '09876544321', '2024-12-30', '2025-01-01', 'diambil', '0000-00-00', '9'),
(88, 'spx', 'SPXID04301086556sfd', 'Rahasia', 'sabet', '1513531366426', '2025-01-02', '2025-01-02', 'diambil', '0000-00-00', '9'),
(89, 'ide', 'JP351514495900044afs', 'Rahasia', 'sabet', '111111111111111', '2025-01-02', '2025-01-02', 'diambil', '0000-00-00', '9'),
(90, 'antaraja', 'SPXID0430108655sd6', 'Rahasia', 'sabet', '087784466578', '2025-01-02', '2025-01-02', 'diambil', '0000-00-00', '1'),
(91, 'antaraja', 'SPXID04301086551241', 'Rahasia', 'sabet', '087784466578', '2025-01-03', '2025-01-03', 'diambil', '0000-00-00', '9'),
(92, 'antaraja', 'SPXID04301085ty', 'Rahasia', '2', '087784466578', '2025-01-03', '2025-01-03', 'diambil', '0000-00-00', '9'),
(93, 'ide', 'JP35151449590df', 'Rahasia', '2', '087784466578', '2025-01-03', '2025-01-04', 'diambil', '0000-00-00', '9'),
(94, 'jet', 'JP351514495935sdt', 'Rahasia', '1', '087784466578', '2025-01-03', '2025-01-04', 'diambil', '0000-00-00', '9'),
(95, 'jet', 'JP351514495935sd12', 'Rahasia', '1', '087784466578', '2025-01-03', '2025-01-04', 'diambil', '0000-00-00', '9'),
(96, 'jntcargo', 'SPXID0430108345tgf', 'Rahasia', 'sabet', '087784466578', '2025-01-03', '2025-01-04', 'diambil', '0000-00-00', '9'),
(97, 'jntcargo', 'SPXID0430108345t235', 'Rahasia', 'sabet', '087784466578', '2024-12-26', NULL, 'kadaluwarsa', '2025-01-02', NULL),
(98, 'jnt', 'SPXID04301087', 'Rahasia', 'sabet', '11111111111', '2025-01-04', '2025-01-04', 'diambil', '0000-00-00', '9'),
(99, 'jntcargo', 'SPXID043010872g', 'Rahasia', '2', '2222222222', '2025-01-04', '2025-01-04', 'diambil', '0000-00-00', '9'),
(100, 'antaraja', 'JP35151449590245d', 'Rahasia', '1', '087784466578', '2025-01-06', NULL, 'tersedia', '0000-00-00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pamdal`
--

CREATE TABLE `pamdal` (
  `kata_sandi` varchar(255) NOT NULL,
  `nama_pamdal` varchar(25) DEFAULT NULL,
  `NIK` varchar(25) NOT NULL,
  `role` enum('pamdal','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pamdal`
--

INSERT INTO `pamdal` (`kata_sandi`, `nama_pamdal`, `NIK`, `role`) VALUES
('$2y$10$M2JF/AowfIEzAspLEQUBuO23BOQTgcz/.jIoEn3eteNoh8VO0VUea', 'kaneeza', '4', 'pamdal'),
('$2y$10$XL.askiAuGOXsBpp5vipTuFA0psimo/7OkES7PrzRgBBVPdk8/Mb6', '87', '8', 'pamdal'),
('$2y$10$QcDkwB.N7SZfIJzEr5HIlO8S9qI9wW0/IfO2wT2jkD5ADQCOUWTJC', '0', '9', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`),
  ADD KEY `paket_ibfk_1` (`NIK`);

--
-- Indexes for table `pamdal`
--
ALTER TABLE `pamdal`
  ADD PRIMARY KEY (`NIK`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
