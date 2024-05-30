-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2024 at 03:05 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_user`
--

CREATE TABLE `m_user` (
  `id_user` varchar(100) NOT NULL,
  `full_name` varchar(250) NOT NULL,
  `level` varchar(100) NOT NULL,
  `profile` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `status` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `m_user`
--

INSERT INTO `m_user` (`id_user`, `full_name`, `level`, `profile`, `email`, `password`, `status`) VALUES
('U0001', 'tarsem', 'pool', 'U0001.jpeg', 'tarsem@gmail.com', 'VlliTUhKQlpMT3lXdzBIaU0zcHV6QT09', 'activate'),
('U0002', 'heri', 'admin', 'U0002.jpeg', 'her1@gmail.com', 'VlliTUhKQlpMT3lXdzBIaU0zcHV6QT09', 'activate'),
('U0003', 'Hermawan Susanto', 'driver', 'U0003.jpeg', 'her@gmail.com', 'VlliTUhKQlpMT3lXdzBIaU0zcHV6QT09', 'activate'),
('U0004', 'nandaaa', 'driver', '', 'n@gmail.com', 'TTRybG9icDh0TUg0SHZHbkpObTF5Zz09', 'not_activate'),
('U0005', 'nandaa', 'driver', 'U0005.jpeg', 'nanda@gmail.com', 'TTRybG9icDh0TUg0SHZHbkpObTF5Zz09', 'activate');

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` varchar(100) NOT NULL,
  `jenis_kendaraan` varchar(250) NOT NULL,
  `tipe` varchar(250) NOT NULL,
  `bbm` varchar(250) NOT NULL,
  `jdwl_service` varchar(250) NOT NULL,
  `waktu_pemesanan` varchar(250) NOT NULL,
  `driver` varchar(250) NOT NULL,
  `pihak_penyetuju` varchar(250) NOT NULL,
  `notes` varchar(250) NOT NULL,
  `created_at` datetime(6) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `reason_cncl` varchar(250) NOT NULL,
  `img_kendaraan` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `jenis_kendaraan`, `tipe`, `bbm`, `jdwl_service`, `waktu_pemesanan`, `driver`, `pihak_penyetuju`, `notes`, `created_at`, `status`, `reason_cncl`, `img_kendaraan`) VALUES
('PMSN0001', 'truck', 'fuso', '100', '2024-05-14', '2024-05-29 11:05', 'U0003', 'U0001', '', '2024-05-29 00:00:00.000000', 1, '', ''),
('PMSN0002', '1', '2', '3', '2024-05-08', '2024-05-29 18:08', 'U0003', 'U0001', '23iiiii', '2024-05-29 00:00:00.000000', 0, '', 'PMSN0002.jpeg'),
('PMSN0004', 'avanza', 'veloz', '500', '2024-05-01', '2024-05-29 20:42', 'U0003', 'U0001', 'fgh', '2024-05-29 00:00:00.000000', 1, '', 'PMSN0004.jpeg'),
('PMSN0005', 'lc', 'lc', '45', '2024-05-24', '2024-05-29 20:46', 'U0003', 'U0001', 'try', '2024-05-29 00:00:00.000000', 1, '', 'PMSN0005.jpeg'),
('PMSN0006', 'dump truck', 'fuso big', '123', '2024-05-17', '2024-05-29 20:48', 'U0003', 'U0001', 'fft', '2024-05-29 00:00:00.000000', 1, '', 'PMSN0006.jpeg'),
('PMSN0007', 'innova', 'mobil', 'hhh', '2024-05-29', '2024-05-29 22:48', 'U0003', 'U0001', 'aku cantik', '2024-05-29 00:00:00.000000', 1, '', 'PMSN0007.jpeg'),
('PMSN0008', 'bmw', 'mobiles', 'p', '2024-05-30', '2024-05-30 00:21', 'U0003', 'U0001', 'aku cantiiieqq abies', '2024-05-30 00:00:00.000000', 0, '', 'PMSN0008.jpeg'),
('PMSN0009', 'bmw', 'mobiles', 'p', '2024-05-30', '2024-05-30 00:21', 'U0003', 'U0001', 'aku cantiiieqq abies', '2024-05-30 00:00:00.000000', 0, '', 'PMSN0009.jpeg'),
('PMSN0010', 'jeep', '123', '123', '2024-05-07', '2024-05-30 00:52', 'U0005', 'U0001', '', '2024-05-30 00:00:00.000000', 1, '', 'PMSN0010.jpeg'),
('PMSN0011', 'dump', 'dump', '23', '2024-05-06', '2024-05-30 00:57', 'U0005', 'U0001', 'test', '2024-05-30 00:00:00.000000', 1, '', 'PMSN0011.jpeg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
