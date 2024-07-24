-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2024 at 05:31 PM
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
-- Database: `electricity_payment`
--

-- --------------------------------------------------------

--
-- Table structure for table `g_akun`
--

CREATE TABLE `g_akun` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `g_akun`
--

INSERT INTO `g_akun` (`id`, `username`, `password`) VALUES
(1, 'Gholi A', '$2y$10$O1H9bhXs43m.VtYPOyQly.8/YOQMQaHj59kCGAYHQO4sV1MnmnLk6'),
(2, 'Alexa C', '$2y$10$KLWM1rJLmGYMfCb80rjNIO2NUT6rHo8wjsKTLoaB8/UGJiRyvD2dO');

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Customer');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id`, `user_id`, `address`) VALUES
(1, 1, 'Jl. Bhima '),
(2, 2, 'Jl Garuda');

-- --------------------------------------------------------

--
-- Table structure for table `penggunaan`
--

CREATE TABLE `penggunaan` (
  `id` int(11) NOT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `daya` int(11) DEFAULT NULL,
  `pemakaian_kwh` int(11) DEFAULT NULL,
  `nomor_meter` varchar(50) DEFAULT NULL,
  `total_dibayarkan` decimal(10,2) DEFAULT NULL,
  `metode_pembayaran` varchar(50) DEFAULT NULL,
  `status_pembayaran` varchar(20) DEFAULT NULL,
  `nomor_token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penggunaan`
--

INSERT INTO `penggunaan` (`id`, `pelanggan_id`, `bulan`, `tahun`, `daya`, `pemakaian_kwh`, `nomor_meter`, `total_dibayarkan`, `metode_pembayaran`, `status_pembayaran`, `nomor_token`) VALUES
(1, 2, 'Maret', 2024, 2200, 500, '12121212121', 12.00, 'transfer', NULL, NULL),
(2, 2, 'Mei', 2024, 1300, 250, '12121212121', 12500000.00, 'transfer', 'Lunas', '92094762617002968055'),
(3, 2, 'Februari', 2024, 900, 100, '12121212121', 1000000.00, 'cash', 'Lunas', '25476461911189911320'),
(4, 2, 'Juli', 2024, 450, 100, '12121212121', 120000.00, 'cash', 'Lunas', '99424276233577235708');

--
-- Triggers `penggunaan`
--
DELIMITER $$
CREATE TRIGGER `after_insert_penggunaan` AFTER INSERT ON `penggunaan` FOR EACH ROW BEGIN
    DECLARE total_usage INT;
    SET total_usage = (SELECT total_penggunaan_bulan(NEW.pelanggan_id, NEW.bulan, NEW.tahun));
    -- Assuming you have a billing table named 'tagihan'
    INSERT INTO tagihan (pelanggan_id, bulan, tahun, total_kwh)
    VALUES (NEW.pelanggan_id, NEW.bulan, NEW.tahun, total_usage);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `penggunaan_listrik`
-- (See below for the actual view)
--
CREATE TABLE `penggunaan_listrik` (
`pelanggan_id` int(11)
,`user_name` varchar(100)
,`address` varchar(255)
,`bulan` varchar(20)
,`tahun` int(11)
,`daya` int(11)
,`pemakaian_kwh` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `tagihan`
--

CREATE TABLE `tagihan` (
  `id` int(11) NOT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `bulan` varchar(20) DEFAULT NULL,
  `tahun` int(11) DEFAULT NULL,
  `total_kwh` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tagihan`
--

INSERT INTO `tagihan` (`id`, `pelanggan_id`, `bulan`, `tahun`, `total_kwh`) VALUES
(1, 2, 'Maret', 2024, 450),
(2, 2, 'Maret', 2024, 500),
(3, 2, 'Mei', 2024, 250),
(4, 2, 'Februari', 2024, 100),
(5, 2, 'Juli', 2024, 100);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `level_id` int(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `level_id`, `password`) VALUES
(1, 'Gholi A', 1, '$2y$10$O1H9bhXs43m.VtYPOyQly.8/YOQMQaHj59kCGAYHQO4sV1MnmnLk6'),
(2, 'Alexa C', 2, '$2y$10$KLWM1rJLmGYMfCb80rjNIO2NUT6rHo8wjsKTLoaB8/UGJiRyvD2dO');

-- --------------------------------------------------------

--
-- Structure for view `penggunaan_listrik`
--
DROP TABLE IF EXISTS `penggunaan_listrik`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `penggunaan_listrik`  AS SELECT `p`.`id` AS `pelanggan_id`, `u`.`name` AS `user_name`, `p`.`address` AS `address`, `pe`.`bulan` AS `bulan`, `pe`.`tahun` AS `tahun`, `pe`.`daya` AS `daya`, `pe`.`pemakaian_kwh` AS `pemakaian_kwh` FROM ((`penggunaan` `pe` join `pelanggan` `p` on(`pe`.`pelanggan_id` = `p`.`id`)) join `user` `u` on(`p`.`user_id` = `u`.`id`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `g_akun`
--
ALTER TABLE `g_akun`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pelanggan_id` (`pelanggan_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `level_id` (`level_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `g_akun`
--
ALTER TABLE `g_akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tagihan`
--
ALTER TABLE `tagihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD CONSTRAINT `pelanggan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`);

--
-- Constraints for table `tagihan`
--
ALTER TABLE `tagihan`
  ADD CONSTRAINT `tagihan_ibfk_1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggan` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
