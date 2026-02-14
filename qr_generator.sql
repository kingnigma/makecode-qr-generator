-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2026 at 09:12 PM
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
-- Database: `qr_generator`
--

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

CREATE TABLE `file_uploads` (
  `id` int(11) NOT NULL,
  `qr_code_id` int(11) DEFAULT NULL,
  `original_filename` varchar(255) NOT NULL,
  `stored_filename` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(50) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_codes`
--

CREATE TABLE `qr_codes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `qr_type` enum('qr','barcode') DEFAULT 'qr',
  `data_type` varchar(50) NOT NULL,
  `content` text NOT NULL,
  `qr_image_path` varchar(255) DEFAULT NULL,
  `update_later` tinyint(1) DEFAULT 0,
  `dynamic_tracking` tinyint(1) DEFAULT 0,
  `scan_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `qr_codes`
--

INSERT INTO `qr_codes` (`id`, `user_id`, `qr_type`, `data_type`, `content`, `qr_image_path`, `update_later`, `dynamic_tracking`, `scan_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', '/uploads/qr-codes/qr_698fb26beb8c2.png', 1, 1, 0, 1, '2026-02-13 23:23:23', '2026-02-13 23:23:23'),
(2, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', '/uploads/qr-codes/qr_69904ff47acdb.png', 1, 1, 0, 1, '2026-02-14 10:35:32', '2026-02-14 10:35:32'),
(3, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', '/uploads/qr-codes/qr_69905e5e38fb5.png', 1, 1, 0, 1, '2026-02-14 11:37:02', '2026-02-14 11:37:02'),
(4, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', '/uploads/qr-codes/qr_69906bbac8da2.png', 1, 1, 0, 1, '2026-02-14 12:34:02', '2026-02-14 12:34:02'),
(5, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', 'uploads/qr-codes/qr_69906c2cc934a.png', 1, 1, 0, 1, '2026-02-14 12:35:56', '2026-02-14 12:35:56'),
(6, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', 'uploads/qr-codes/qr_69906c7549842.png', 1, 1, 0, 1, '2026-02-14 12:37:09', '2026-02-14 12:37:09'),
(7, NULL, 'qr', 'url', 'https://wa.me/+2347031261063?text=I\'m%20interested%20in%20your%20purchasing%20a%20wholesale%20quantity%20of%20your%20chips', 'uploads/qr-codes/qr_69906e93263fb.png', 1, 1, 0, 1, '2026-02-14 12:46:11', '2026-02-14 12:46:11'),
(8, NULL, 'qr', 'sms', 'SMSTO:+2348148287596:Connect with new development ideas and tips on the UpdateHub with designer and developer Mathew Kings.', 'uploads/qr-codes/qr_69907958ab7d1.png', 1, 1, 0, 1, '2026-02-14 13:32:08', '2026-02-14 13:32:08'),
(9, NULL, 'qr', 'text', 'creat check boost train fill another set other push just intro battery hours', 'uploads/qr-codes/qr_699080bb32685.png', 1, 1, 0, 1, '2026-02-14 14:03:39', '2026-02-14 14:03:39'),
(10, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', 'uploads/qr-codes/qr_6990812b3e46e.png', 1, 1, 0, 1, '2026-02-14 14:05:31', '2026-02-14 14:05:31'),
(11, NULL, 'qr', 'url', 'https://mkportfolio.crestdigico.com', 'uploads/qr-codes/qr_699082c5130f8.png', 1, 1, 0, 1, '2026-02-14 14:12:21', '2026-02-14 14:12:21'),
(12, NULL, 'barcode', 'code128', 'ABC123', '/uploads/bar-codes/barcode_ABC123_699098d2d81fe.png', 0, 0, 0, 1, '2026-02-14 15:46:26', '2026-02-14 15:46:26'),
(13, NULL, 'barcode', 'code128', 'TST1000', '/uploads/bar-codes/barcode_TST1000_6990991ad77b5.png', 0, 0, 0, 1, '2026-02-14 15:47:38', '2026-02-14 15:47:38'),
(14, NULL, 'barcode', 'code128', 'TST1001', '/uploads/bar-codes/barcode_TST1001_6990991ada44f.png', 0, 0, 0, 1, '2026-02-14 15:47:38', '2026-02-14 15:47:38'),
(15, NULL, 'barcode', 'code128', 'TST1002', '/uploads/bar-codes/barcode_TST1002_6990991adc866.png', 0, 0, 0, 1, '2026-02-14 15:47:38', '2026-02-14 15:47:38'),
(16, NULL, 'barcode', 'code128', 'TST1000', '/uploads/bar-codes/barcode_TST1000_6990997a4fa9d.png', 0, 0, 0, 1, '2026-02-14 15:49:14', '2026-02-14 15:49:14'),
(17, NULL, 'barcode', 'code128', 'TST1001', '/uploads/bar-codes/barcode_TST1001_6990997a53e3d.png', 0, 0, 0, 1, '2026-02-14 15:49:14', '2026-02-14 15:49:14'),
(18, NULL, 'barcode', 'code128', 'TST1002', '/uploads/bar-codes/barcode_TST1002_6990997a55d48.png', 0, 0, 0, 1, '2026-02-14 15:49:14', '2026-02-14 15:49:14'),
(19, NULL, 'barcode', 'code128', 'ACE1001', '/uploads/bar-codes/barcode_ACE1001_69909ac26ae0c.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(20, NULL, 'barcode', 'code128', 'ACE1002', '/uploads/bar-codes/barcode_ACE1002_69909ac26e4a8.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(21, NULL, 'barcode', 'code128', 'ACE1003', '/uploads/bar-codes/barcode_ACE1003_69909ac26f60a.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(22, NULL, 'barcode', 'code128', 'ACE1004', '/uploads/bar-codes/barcode_ACE1004_69909ac270a62.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(23, NULL, 'barcode', 'code128', 'ACE1005', '/uploads/bar-codes/barcode_ACE1005_69909ac271e87.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(24, NULL, 'barcode', 'code128', 'ACE1006', '/uploads/bar-codes/barcode_ACE1006_69909ac273020.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(25, NULL, 'barcode', 'code128', 'ACE1007', '/uploads/bar-codes/barcode_ACE1007_69909ac2742d9.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(26, NULL, 'barcode', 'code128', 'ACE1008', '/uploads/bar-codes/barcode_ACE1008_69909ac275713.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(27, NULL, 'barcode', 'code128', 'ACE1009', '/uploads/bar-codes/barcode_ACE1009_69909ac276aa4.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(28, NULL, 'barcode', 'code128', 'ACE1010', '/uploads/bar-codes/barcode_ACE1010_69909ac277f0f.png', 0, 0, 0, 1, '2026-02-14 15:54:42', '2026-02-14 15:54:42'),
(29, NULL, 'barcode', 'code128', 'ABC123', 'uploads/bar-codes/barcode_ABC123_69909c92a9e0e.png', 0, 0, 0, 1, '2026-02-14 16:02:26', '2026-02-14 16:02:26'),
(30, NULL, 'barcode', 'code128', 'TST1000', 'uploads/bar-codes/barcode_TST1000_69909caa20111.png', 0, 0, 0, 1, '2026-02-14 16:02:50', '2026-02-14 16:02:50'),
(31, NULL, 'barcode', 'code128', 'TST1001', 'uploads/bar-codes/barcode_TST1001_69909caa23e49.png', 0, 0, 0, 1, '2026-02-14 16:02:50', '2026-02-14 16:02:50'),
(32, NULL, 'barcode', 'code128', 'TST1002', 'uploads/bar-codes/barcode_TST1002_69909caa29cb8.png', 0, 0, 0, 1, '2026-02-14 16:02:50', '2026-02-14 16:02:50'),
(33, NULL, 'barcode', 'code128', 'ACE1010', 'uploads/bar-codes/barcode_ACE1010_69909d8eba338.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(34, NULL, 'barcode', 'code128', 'ACE1011', 'uploads/bar-codes/barcode_ACE1011_69909d8ebd422.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(35, NULL, 'barcode', 'code128', 'ACE1012', 'uploads/bar-codes/barcode_ACE1012_69909d8ebe901.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(36, NULL, 'barcode', 'code128', 'ACE1013', 'uploads/bar-codes/barcode_ACE1013_69909d8ebfd4f.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(37, NULL, 'barcode', 'code128', 'ACE1014', 'uploads/bar-codes/barcode_ACE1014_69909d8ec10fa.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(38, NULL, 'barcode', 'code128', 'ACE1015', 'uploads/bar-codes/barcode_ACE1015_69909d8ec264d.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(39, NULL, 'barcode', 'code128', 'ACE1016', 'uploads/bar-codes/barcode_ACE1016_69909d8ec3a34.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(40, NULL, 'barcode', 'code128', 'ACE1017', 'uploads/bar-codes/barcode_ACE1017_69909d8ec6bcd.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(41, NULL, 'barcode', 'code128', 'ACE1018', 'uploads/bar-codes/barcode_ACE1018_69909d8ecb6d2.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(42, NULL, 'barcode', 'code128', 'ACE1019', 'uploads/bar-codes/barcode_ACE1019_69909d8eccbec.png', 0, 0, 0, 1, '2026-02-14 16:06:38', '2026-02-14 16:06:38'),
(43, NULL, 'barcode', 'code128', '9181001', 'uploads/bar-codes/barcode_9181001_69909ee1a6fcd.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(44, NULL, 'barcode', 'code128', '9181002', 'uploads/bar-codes/barcode_9181002_69909ee1ad4c0.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(45, NULL, 'barcode', 'code128', '9181003', 'uploads/bar-codes/barcode_9181003_69909ee1b1c8a.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(46, NULL, 'barcode', 'code128', '9181004', 'uploads/bar-codes/barcode_9181004_69909ee1bb2bd.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(47, NULL, 'barcode', 'code128', '9181005', 'uploads/bar-codes/barcode_9181005_69909ee1bfc90.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(48, NULL, 'barcode', 'code128', '9181006', 'uploads/bar-codes/barcode_9181006_69909ee1c40fa.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(49, NULL, 'barcode', 'code128', '9181007', 'uploads/bar-codes/barcode_9181007_69909ee1c8d80.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(50, NULL, 'barcode', 'code128', '9181008', 'uploads/bar-codes/barcode_9181008_69909ee1cd02d.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(51, NULL, 'barcode', 'code128', '9181009', 'uploads/bar-codes/barcode_9181009_69909ee1d131c.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(52, NULL, 'barcode', 'code128', '9181010', 'uploads/bar-codes/barcode_9181010_69909ee1d56e2.png', 0, 0, 0, 1, '2026-02-14 16:12:17', '2026-02-14 16:12:17'),
(53, NULL, 'barcode', 'code128', '9191001', 'uploads/bar-codes/barcode_9191001_69909fb96fc1a.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(54, NULL, 'barcode', 'code128', '9191002', 'uploads/bar-codes/barcode_9191002_69909fb9757df.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(55, NULL, 'barcode', 'code128', '9191003', 'uploads/bar-codes/barcode_9191003_69909fb979361.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(56, NULL, 'barcode', 'code128', '9191004', 'uploads/bar-codes/barcode_9191004_69909fb97fc62.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(57, NULL, 'barcode', 'code128', '9191005', 'uploads/bar-codes/barcode_9191005_69909fb98381e.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(58, NULL, 'barcode', 'code128', '9191006', 'uploads/bar-codes/barcode_9191006_69909fb987d54.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(59, NULL, 'barcode', 'code128', '9191007', 'uploads/bar-codes/barcode_9191007_69909fb98bce2.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(60, NULL, 'barcode', 'code128', '9191008', 'uploads/bar-codes/barcode_9191008_69909fb98fa71.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(61, NULL, 'barcode', 'code128', '9191009', 'uploads/bar-codes/barcode_9191009_69909fb993df3.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(62, NULL, 'barcode', 'code128', '9191010', 'uploads/bar-codes/barcode_9191010_69909fb998243.png', 0, 0, 0, 1, '2026-02-14 16:15:53', '2026-02-14 16:15:53'),
(63, NULL, 'barcode', 'code128', 'CHI1001', 'uploads/bar-codes/barcode_CHI1001_6990a155e4567.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(64, NULL, 'barcode', 'code128', 'CHI1002', 'uploads/bar-codes/barcode_CHI1002_6990a155e7b60.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(65, NULL, 'barcode', 'code128', 'CHI1003', 'uploads/bar-codes/barcode_CHI1003_6990a155e9a51.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(66, NULL, 'barcode', 'code128', 'CHI1004', 'uploads/bar-codes/barcode_CHI1004_6990a155ebe4e.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(67, NULL, 'barcode', 'code128', 'CHI1005', 'uploads/bar-codes/barcode_CHI1005_6990a155ed882.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(68, NULL, 'barcode', 'code128', 'CHI1006', 'uploads/bar-codes/barcode_CHI1006_6990a155ef340.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(69, NULL, 'barcode', 'code128', 'CHI1007', 'uploads/bar-codes/barcode_CHI1007_6990a155f13c9.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(70, NULL, 'barcode', 'code128', 'CHI1008', 'uploads/bar-codes/barcode_CHI1008_6990a155f3187.png', 0, 0, 0, 1, '2026-02-14 16:22:45', '2026-02-14 16:22:45'),
(71, NULL, 'barcode', 'code128', 'CHI1009', 'uploads/bar-codes/barcode_CHI1009_6990a156004e4.png', 0, 0, 0, 1, '2026-02-14 16:22:46', '2026-02-14 16:22:46'),
(72, NULL, 'barcode', 'code128', 'CHI1010', 'uploads/bar-codes/barcode_CHI1010_6990a15601b17.png', 0, 0, 0, 1, '2026-02-14 16:22:46', '2026-02-14 16:22:46'),
(73, NULL, 'barcode', 'code128', 'ACECH1001', 'uploads/bar-codes/barcode_ACECH1001_6990a185d6abc.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(74, NULL, 'barcode', 'code128', 'ACECH1002', 'uploads/bar-codes/barcode_ACECH1002_6990a185da6c6.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(75, NULL, 'barcode', 'code128', 'ACECH1003', 'uploads/bar-codes/barcode_ACECH1003_6990a185dcb51.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(76, NULL, 'barcode', 'code128', 'ACECH1004', 'uploads/bar-codes/barcode_ACECH1004_6990a185def30.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(77, NULL, 'barcode', 'code128', 'ACECH1005', 'uploads/bar-codes/barcode_ACECH1005_6990a185e110f.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(78, NULL, 'barcode', 'code128', 'ACECH1006', 'uploads/bar-codes/barcode_ACECH1006_6990a185e709a.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(79, NULL, 'barcode', 'code128', 'ACECH1007', 'uploads/bar-codes/barcode_ACECH1007_6990a185e9041.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(80, NULL, 'barcode', 'code128', 'ACECH1008', 'uploads/bar-codes/barcode_ACECH1008_6990a185ee8cf.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(81, NULL, 'barcode', 'code128', 'ACECH1009', 'uploads/bar-codes/barcode_ACECH1009_6990a185f09b6.png', 0, 0, 0, 1, '2026-02-14 16:23:33', '2026-02-14 16:23:33'),
(82, NULL, 'barcode', 'code128', 'ACECH1010', 'uploads/bar-codes/barcode_ACECH1010_6990a185f2df9.png', 0, 0, 0, 1, '2026-02-14 16:23:34', '2026-02-14 16:23:34'),
(83, NULL, 'barcode', 'code39', 'ACE1001', 'uploads/bar-codes/barcode_ACE1001_6990a1bc55d99.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(84, NULL, 'barcode', 'code39', 'ACE1002', 'uploads/bar-codes/barcode_ACE1002_6990a1bc57c26.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(85, NULL, 'barcode', 'code39', 'ACE1003', 'uploads/bar-codes/barcode_ACE1003_6990a1bc58ec5.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(86, NULL, 'barcode', 'code39', 'ACE1004', 'uploads/bar-codes/barcode_ACE1004_6990a1bc5a3b2.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(87, NULL, 'barcode', 'code39', 'ACE1005', 'uploads/bar-codes/barcode_ACE1005_6990a1bc5b981.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(88, NULL, 'barcode', 'code39', 'ACE1006', 'uploads/bar-codes/barcode_ACE1006_6990a1bc5cf98.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(89, NULL, 'barcode', 'code39', 'ACE1007', 'uploads/bar-codes/barcode_ACE1007_6990a1bc5e422.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(90, NULL, 'barcode', 'code39', 'ACE1008', 'uploads/bar-codes/barcode_ACE1008_6990a1bc5f86e.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(91, NULL, 'barcode', 'code39', 'ACE1009', 'uploads/bar-codes/barcode_ACE1009_6990a1bc60d36.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(92, NULL, 'barcode', 'code39', 'ACE1010', 'uploads/bar-codes/barcode_ACE1010_6990a1bc6474b.png', 0, 0, 0, 1, '2026-02-14 16:24:28', '2026-02-14 16:24:28'),
(93, NULL, 'barcode', 'code128', '9875641001', 'uploads/bar-codes/barcode_9875641001_6990a1f16a85b.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(94, NULL, 'barcode', 'code128', '9875641002', 'uploads/bar-codes/barcode_9875641002_6990a1f16e08a.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(95, NULL, 'barcode', 'code128', '9875641003', 'uploads/bar-codes/barcode_9875641003_6990a1f16fa50.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(96, NULL, 'barcode', 'code128', '9875641004', 'uploads/bar-codes/barcode_9875641004_6990a1f1715b2.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(97, NULL, 'barcode', 'code128', '9875641005', 'uploads/bar-codes/barcode_9875641005_6990a1f17314e.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(98, NULL, 'barcode', 'code128', '9875641006', 'uploads/bar-codes/barcode_9875641006_6990a1f174e79.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(99, NULL, 'barcode', 'code128', '9875641007', 'uploads/bar-codes/barcode_9875641007_6990a1f1768bb.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(100, NULL, 'barcode', 'code128', '9875641008', 'uploads/bar-codes/barcode_9875641008_6990a1f1785c0.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(101, NULL, 'barcode', 'code128', '9875641009', 'uploads/bar-codes/barcode_9875641009_6990a1f17a489.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(102, NULL, 'barcode', 'code128', '9875641010', 'uploads/bar-codes/barcode_9875641010_6990a1f17bd87.png', 0, 0, 0, 1, '2026-02-14 16:25:21', '2026-02-14 16:25:21'),
(103, NULL, 'barcode', 'code128', '9875651001', 'uploads/bar-codes/barcode_9875651001_6990a516d07d6.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(104, NULL, 'barcode', 'code128', '9875651002', 'uploads/bar-codes/barcode_9875651002_6990a516d3bc0.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(105, NULL, 'barcode', 'code128', '9875651003', 'uploads/bar-codes/barcode_9875651003_6990a516d61db.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(106, NULL, 'barcode', 'code128', '9875651004', 'uploads/bar-codes/barcode_9875651004_6990a516d8bef.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(107, NULL, 'barcode', 'code128', '9875651005', 'uploads/bar-codes/barcode_9875651005_6990a516db4d5.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(108, NULL, 'barcode', 'code128', '9875651006', 'uploads/bar-codes/barcode_9875651006_6990a516ddb1d.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(109, NULL, 'barcode', 'code128', '9875651007', 'uploads/bar-codes/barcode_9875651007_6990a516e584d.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(110, NULL, 'barcode', 'code128', '9875651008', 'uploads/bar-codes/barcode_9875651008_6990a516e8013.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(111, NULL, 'barcode', 'code128', '9875651009', 'uploads/bar-codes/barcode_9875651009_6990a516eac79.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(112, NULL, 'barcode', 'code128', '9875651010', 'uploads/bar-codes/barcode_9875651010_6990a516ed5e7.png', 0, 0, 0, 1, '2026-02-14 16:38:46', '2026-02-14 16:38:46'),
(113, NULL, 'barcode', 'code128', 'ACE1001', 'uploads/bar-codes/barcode_ACE1001_6990a530cda75.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(114, NULL, 'barcode', 'code128', 'ACE1002', 'uploads/bar-codes/barcode_ACE1002_6990a530d25d4.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(115, NULL, 'barcode', 'code128', 'ACE1003', 'uploads/bar-codes/barcode_ACE1003_6990a530d5a0d.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(116, NULL, 'barcode', 'code128', 'ACE1004', 'uploads/bar-codes/barcode_ACE1004_6990a530db809.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(117, NULL, 'barcode', 'code128', 'ACE1005', 'uploads/bar-codes/barcode_ACE1005_6990a530deb30.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(118, NULL, 'barcode', 'code128', 'ACE1006', 'uploads/bar-codes/barcode_ACE1006_6990a530e2019.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(119, NULL, 'barcode', 'code128', 'ACE1007', 'uploads/bar-codes/barcode_ACE1007_6990a530ea9eb.png', 0, 0, 0, 1, '2026-02-14 16:39:12', '2026-02-14 16:39:12'),
(120, NULL, 'barcode', 'code128', 'ACE1008', 'uploads/bar-codes/barcode_ACE1008_6990a530ee0be.png', 0, 0, 0, 1, '2026-02-14 16:39:13', '2026-02-14 16:39:13'),
(121, NULL, 'barcode', 'code128', 'ACE1009', 'uploads/bar-codes/barcode_ACE1009_6990a53101f1f.png', 0, 0, 0, 1, '2026-02-14 16:39:13', '2026-02-14 16:39:13'),
(122, NULL, 'barcode', 'code128', 'ACE1010', 'uploads/bar-codes/barcode_ACE1010_6990a53105611.png', 0, 0, 0, 1, '2026-02-14 16:39:13', '2026-02-14 16:39:13'),
(123, NULL, 'barcode', 'code128', 'CHI1001', 'uploads/bar-codes/barcode_CHI1001_6990a55604dee.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(124, NULL, 'barcode', 'code128', 'CHI1002', 'uploads/bar-codes/barcode_CHI1002_6990a5560780a.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(125, NULL, 'barcode', 'code128', 'CHI1003', 'uploads/bar-codes/barcode_CHI1003_6990a55608d20.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(126, NULL, 'barcode', 'code128', 'CHI1004', 'uploads/bar-codes/barcode_CHI1004_6990a55609e9f.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(127, NULL, 'barcode', 'code128', 'CHI1005', 'uploads/bar-codes/barcode_CHI1005_6990a5560b0fa.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(128, NULL, 'barcode', 'code128', 'CHI1006', 'uploads/bar-codes/barcode_CHI1006_6990a5560c751.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(129, NULL, 'barcode', 'code128', 'CHI1007', 'uploads/bar-codes/barcode_CHI1007_6990a5560dca6.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(130, NULL, 'barcode', 'code128', 'CHI1008', 'uploads/bar-codes/barcode_CHI1008_6990a5560f200.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(131, NULL, 'barcode', 'code128', 'CHI1009', 'uploads/bar-codes/barcode_CHI1009_6990a556106fa.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(132, NULL, 'barcode', 'code128', 'CHI1010', 'uploads/bar-codes/barcode_CHI1010_6990a55611d6c.png', 0, 0, 0, 1, '2026-02-14 16:39:50', '2026-02-14 16:39:50'),
(133, NULL, 'barcode', 'code128', '9875641001', 'uploads/bar-codes/barcode_9875641001_6990a60f35553.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(134, NULL, 'barcode', 'code128', '9875641002', 'uploads/bar-codes/barcode_9875641002_6990a60f390cc.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(135, NULL, 'barcode', 'code128', '9875641003', 'uploads/bar-codes/barcode_9875641003_6990a60f3b6d2.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(136, NULL, 'barcode', 'code128', '9875641004', 'uploads/bar-codes/barcode_9875641004_6990a60f3e312.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(137, NULL, 'barcode', 'code128', '9875641005', 'uploads/bar-codes/barcode_9875641005_6990a60f40db4.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(138, NULL, 'barcode', 'code128', '9875641006', 'uploads/bar-codes/barcode_9875641006_6990a60f43cb9.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(139, NULL, 'barcode', 'code128', '9875641007', 'uploads/bar-codes/barcode_9875641007_6990a60f464d3.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(140, NULL, 'barcode', 'code128', '9875641008', 'uploads/bar-codes/barcode_9875641008_6990a60f49221.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(141, NULL, 'barcode', 'code128', '9875641009', 'uploads/bar-codes/barcode_9875641009_6990a60f4bf95.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(142, NULL, 'barcode', 'code128', '9875641010', 'uploads/bar-codes/barcode_9875641010_6990a60f4ea8b.png', 0, 0, 0, 1, '2026-02-14 16:42:55', '2026-02-14 16:42:55'),
(143, NULL, 'barcode', 'code128', '9191001', 'uploads/bar-codes/barcode_9191001_6990a7ba81481.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(144, NULL, 'barcode', 'code128', '9191002', 'uploads/bar-codes/barcode_9191002_6990a7ba8a4a9.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(145, NULL, 'barcode', 'code128', '9191003', 'uploads/bar-codes/barcode_9191003_6990a7ba91e86.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(146, NULL, 'barcode', 'code128', '9191004', 'uploads/bar-codes/barcode_9191004_6990a7ba94421.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(147, NULL, 'barcode', 'code128', '9191005', 'uploads/bar-codes/barcode_9191005_6990a7ba96fd5.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(148, NULL, 'barcode', 'code128', '9191006', 'uploads/bar-codes/barcode_9191006_6990a7ba9d4c2.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(149, NULL, 'barcode', 'code128', '9191007', 'uploads/bar-codes/barcode_9191007_6990a7baa4d0a.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(150, NULL, 'barcode', 'code128', '9191008', 'uploads/bar-codes/barcode_9191008_6990a7baa770f.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(151, NULL, 'barcode', 'code128', '9191009', 'uploads/bar-codes/barcode_9191009_6990a7baac761.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(152, NULL, 'barcode', 'code128', '9191010', 'uploads/bar-codes/barcode_9191010_6990a7bab4605.png', 0, 0, 0, 1, '2026-02-14 16:50:02', '2026-02-14 16:50:02'),
(153, NULL, 'barcode', 'code128', 'CHI1051', 'uploads/bar-codes/barcode_CHI1051_6990a8952144d.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(154, NULL, 'barcode', 'code128', 'CHI1052', 'uploads/bar-codes/barcode_CHI1052_6990a89529942.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(155, NULL, 'barcode', 'code128', 'CHI1053', 'uploads/bar-codes/barcode_CHI1053_6990a8952d274.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(156, NULL, 'barcode', 'code128', 'CHI1054', 'uploads/bar-codes/barcode_CHI1054_6990a8952e317.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(157, NULL, 'barcode', 'code128', 'CHI1055', 'uploads/bar-codes/barcode_CHI1055_6990a8952f4e8.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(158, NULL, 'barcode', 'code128', 'CHI1056', 'uploads/bar-codes/barcode_CHI1056_6990a89530846.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(159, NULL, 'barcode', 'code128', 'CHI1057', 'uploads/bar-codes/barcode_CHI1057_6990a89531b95.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(160, NULL, 'barcode', 'code128', 'CHI1058', 'uploads/bar-codes/barcode_CHI1058_6990a89532e64.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(161, NULL, 'barcode', 'code128', 'CHI1059', 'uploads/bar-codes/barcode_CHI1059_6990a89534147.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(162, NULL, 'barcode', 'code128', 'CHI1060', 'uploads/bar-codes/barcode_CHI1060_6990a89535505.png', 0, 0, 0, 1, '2026-02-14 16:53:41', '2026-02-14 16:53:41'),
(163, NULL, 'barcode', 'code128', 'ACE13301', 'uploads/bar-codes/barcode_ACE13301_6990aab445a15.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(164, NULL, 'barcode', 'code128', 'ACE13302', 'uploads/bar-codes/barcode_ACE13302_6990aab448d0b.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(165, NULL, 'barcode', 'code128', 'ACE13303', 'uploads/bar-codes/barcode_ACE13303_6990aab44ab8e.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(166, NULL, 'barcode', 'code128', 'ACE13304', 'uploads/bar-codes/barcode_ACE13304_6990aab44bcfa.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(167, NULL, 'barcode', 'code128', 'ACE13305', 'uploads/bar-codes/barcode_ACE13305_6990aab44cfe3.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(168, NULL, 'barcode', 'code128', 'ACE13306', 'uploads/bar-codes/barcode_ACE13306_6990aab44e170.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(169, NULL, 'barcode', 'code128', 'ACE13307', 'uploads/bar-codes/barcode_ACE13307_6990aab44f4db.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(170, NULL, 'barcode', 'code128', 'ACE13308', 'uploads/bar-codes/barcode_ACE13308_6990aab450681.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(171, NULL, 'barcode', 'code128', 'ACE13309', 'uploads/bar-codes/barcode_ACE13309_6990aab451843.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(172, NULL, 'barcode', 'code128', 'ACE13310', 'uploads/bar-codes/barcode_ACE13310_6990aab452b4b.png', 0, 0, 0, 1, '2026-02-14 17:02:44', '2026-02-14 17:02:44'),
(173, NULL, 'barcode', 'code128', '987564155121', 'uploads/bar-codes/barcode_987564155121_6990aad42bc0c.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(174, NULL, 'barcode', 'code128', '987564155122', 'uploads/bar-codes/barcode_987564155122_6990aad42e638.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(175, NULL, 'barcode', 'code128', '987564155123', 'uploads/bar-codes/barcode_987564155123_6990aad437641.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(176, NULL, 'barcode', 'code128', '987564155124', 'uploads/bar-codes/barcode_987564155124_6990aad43a895.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(177, NULL, 'barcode', 'code128', '987564155125', 'uploads/bar-codes/barcode_987564155125_6990aad43d928.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(178, NULL, 'barcode', 'code128', '987564155126', 'uploads/bar-codes/barcode_987564155126_6990aad440c3c.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(179, NULL, 'barcode', 'code128', '987564155127', 'uploads/bar-codes/barcode_987564155127_6990aad444479.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(180, NULL, 'barcode', 'code128', '987564155128', 'uploads/bar-codes/barcode_987564155128_6990aad4470c0.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(181, NULL, 'barcode', 'code128', '987564155129', 'uploads/bar-codes/barcode_987564155129_6990aad44e5bd.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(182, NULL, 'barcode', 'code128', '987564155130', 'uploads/bar-codes/barcode_987564155130_6990aad451271.png', 0, 0, 0, 1, '2026-02-14 17:03:16', '2026-02-14 17:03:16'),
(183, NULL, 'barcode', 'code128', '918156798', 'uploads/bar-codes/barcode_918156798_6990ab2217f58.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(184, NULL, 'barcode', 'code128', '918156799', 'uploads/bar-codes/barcode_918156799_6990ab2220d41.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(185, NULL, 'barcode', 'code128', '918156800', 'uploads/bar-codes/barcode_918156800_6990ab22266c6.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(186, NULL, 'barcode', 'code128', '918156801', 'uploads/bar-codes/barcode_918156801_6990ab222b7ed.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(187, NULL, 'barcode', 'code128', '918156802', 'uploads/bar-codes/barcode_918156802_6990ab2230401.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(188, NULL, 'barcode', 'code128', '918156803', 'uploads/bar-codes/barcode_918156803_6990ab2235179.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(189, NULL, 'barcode', 'code128', '918156804', 'uploads/bar-codes/barcode_918156804_6990ab223f127.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(190, NULL, 'barcode', 'code128', '918156805', 'uploads/bar-codes/barcode_918156805_6990ab22438d1.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(191, NULL, 'barcode', 'code128', '918156806', 'uploads/bar-codes/barcode_918156806_6990ab224a63b.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(192, NULL, 'barcode', 'code128', '918156807', 'uploads/bar-codes/barcode_918156807_6990ab224ea38.png', 0, 0, 0, 1, '2026-02-14 17:04:34', '2026-02-14 17:04:34'),
(193, NULL, 'barcode', 'code128', 'CHI1031', 'uploads/bar-codes/barcode_CHI1031_6990ab617f9ed.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(194, NULL, 'barcode', 'code128', 'CHI1032', 'uploads/bar-codes/barcode_CHI1032_6990ab618139c.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(195, NULL, 'barcode', 'code128', 'CHI1033', 'uploads/bar-codes/barcode_CHI1033_6990ab6186577.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(196, NULL, 'barcode', 'code128', 'CHI1034', 'uploads/bar-codes/barcode_CHI1034_6990ab61873ea.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(197, NULL, 'barcode', 'code128', 'CHI1035', 'uploads/bar-codes/barcode_CHI1035_6990ab61882a3.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(198, NULL, 'barcode', 'code128', 'CHI1036', 'uploads/bar-codes/barcode_CHI1036_6990ab618e13a.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(199, NULL, 'barcode', 'code128', 'CHI1037', 'uploads/bar-codes/barcode_CHI1037_6990ab618f0aa.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(200, NULL, 'barcode', 'code128', 'CHI1038', 'uploads/bar-codes/barcode_CHI1038_6990ab6190262.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(201, NULL, 'barcode', 'code128', 'CHI1039', 'uploads/bar-codes/barcode_CHI1039_6990ab619133c.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(202, NULL, 'barcode', 'code128', 'CHI1040', 'uploads/bar-codes/barcode_CHI1040_6990ab6192295.png', 0, 0, 0, 1, '2026-02-14 17:05:37', '2026-02-14 17:05:37'),
(203, NULL, 'barcode', 'code128', 'BGT1001', 'uploads/bar-codes/barcode_BGT1001_6990aca2c6b78.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(204, NULL, 'barcode', 'code128', 'BGT1002', 'uploads/bar-codes/barcode_BGT1002_6990aca2c9349.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(205, NULL, 'barcode', 'code128', 'BGT1003', 'uploads/bar-codes/barcode_BGT1003_6990aca2ca7e6.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(206, NULL, 'barcode', 'code128', 'BGT1004', 'uploads/bar-codes/barcode_BGT1004_6990aca2cb75a.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(207, NULL, 'barcode', 'code128', 'BGT1005', 'uploads/bar-codes/barcode_BGT1005_6990aca2cc599.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(208, NULL, 'barcode', 'code128', 'BGT1006', 'uploads/bar-codes/barcode_BGT1006_6990aca2cd911.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(209, NULL, 'barcode', 'code128', 'BGT1007', 'uploads/bar-codes/barcode_BGT1007_6990aca2ce884.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(210, NULL, 'barcode', 'code128', 'BGT1008', 'uploads/bar-codes/barcode_BGT1008_6990aca2cf766.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(211, NULL, 'barcode', 'code128', 'BGT1009', 'uploads/bar-codes/barcode_BGT1009_6990aca2d0733.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(212, NULL, 'barcode', 'code128', 'BGT1010', 'uploads/bar-codes/barcode_BGT1010_6990aca2d189a.png', 0, 0, 0, 1, '2026-02-14 17:10:58', '2026-02-14 17:10:58'),
(213, NULL, 'barcode', 'code128', 'CHCK1001', 'uploads/bar-codes/barcode_CHCK1001_6990ad3caf6f4.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(214, NULL, 'barcode', 'code128', 'CHCK1002', 'uploads/bar-codes/barcode_CHCK1002_6990ad3cb0b69.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(215, NULL, 'barcode', 'code128', 'CHCK1003', 'uploads/bar-codes/barcode_CHCK1003_6990ad3cb1e8e.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(216, NULL, 'barcode', 'code128', 'CHCK1004', 'uploads/bar-codes/barcode_CHCK1004_6990ad3cb3170.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(217, NULL, 'barcode', 'code128', 'CHCK1005', 'uploads/bar-codes/barcode_CHCK1005_6990ad3cb4aa9.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(218, NULL, 'barcode', 'code128', 'CHCK1006', 'uploads/bar-codes/barcode_CHCK1006_6990ad3cb5f08.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(219, NULL, 'barcode', 'code128', 'CHCK1007', 'uploads/bar-codes/barcode_CHCK1007_6990ad3cb726e.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(220, NULL, 'barcode', 'code128', 'CHCK1008', 'uploads/bar-codes/barcode_CHCK1008_6990ad3cb8806.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(221, NULL, 'barcode', 'code128', 'CHCK1009', 'uploads/bar-codes/barcode_CHCK1009_6990ad3cb9af8.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(222, NULL, 'barcode', 'code128', 'CHCK1010', 'uploads/bar-codes/barcode_CHCK1010_6990ad3cbaee5.png', 0, 0, 0, 1, '2026-02-14 17:13:32', '2026-02-14 17:13:32'),
(223, NULL, 'barcode', 'code128', 'ACE1001', 'uploads/bar-codes/barcode_ACE1001_6990ad59309e9.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(224, NULL, 'barcode', 'code128', 'ACE1002', 'uploads/bar-codes/barcode_ACE1002_6990ad5933b01.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(225, NULL, 'barcode', 'code128', 'ACE1003', 'uploads/bar-codes/barcode_ACE1003_6990ad59350c8.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(226, NULL, 'barcode', 'code128', 'ACE1004', 'uploads/bar-codes/barcode_ACE1004_6990ad5936a3c.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(227, NULL, 'barcode', 'code128', 'ACE1005', 'uploads/bar-codes/barcode_ACE1005_6990ad5938331.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(228, NULL, 'barcode', 'code128', 'ACE1006', 'uploads/bar-codes/barcode_ACE1006_6990ad59395ce.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(229, NULL, 'barcode', 'code128', 'ACE1007', 'uploads/bar-codes/barcode_ACE1007_6990ad593a8e1.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(230, NULL, 'barcode', 'code128', 'ACE1008', 'uploads/bar-codes/barcode_ACE1008_6990ad593b9f8.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(231, NULL, 'barcode', 'code128', 'ACE1009', 'uploads/bar-codes/barcode_ACE1009_6990ad59423be.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(232, NULL, 'barcode', 'code128', 'ACE1010', 'uploads/bar-codes/barcode_ACE1010_6990ad59432bf.png', 0, 0, 0, 1, '2026-02-14 17:14:01', '2026-02-14 17:14:01'),
(233, NULL, 'barcode', 'code128', 'CHI1001', 'uploads/bar-codes/barcode_CHI1001_6990ad6d51eb0.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(234, NULL, 'barcode', 'code128', 'CHI1002', 'uploads/bar-codes/barcode_CHI1002_6990ad6d54185.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(235, NULL, 'barcode', 'code128', 'CHI1003', 'uploads/bar-codes/barcode_CHI1003_6990ad6d55240.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(236, NULL, 'barcode', 'code128', 'CHI1004', 'uploads/bar-codes/barcode_CHI1004_6990ad6d564b0.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(237, NULL, 'barcode', 'code128', 'CHI1005', 'uploads/bar-codes/barcode_CHI1005_6990ad6d57587.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(238, NULL, 'barcode', 'code128', 'CHI1006', 'uploads/bar-codes/barcode_CHI1006_6990ad6d588a0.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(239, NULL, 'barcode', 'code128', 'CHI1007', 'uploads/bar-codes/barcode_CHI1007_6990ad6d59c17.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(240, NULL, 'barcode', 'code128', 'CHI1008', 'uploads/bar-codes/barcode_CHI1008_6990ad6d5b039.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(241, NULL, 'barcode', 'code128', 'CHI1009', 'uploads/bar-codes/barcode_CHI1009_6990ad6d5c685.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(242, NULL, 'barcode', 'code128', 'CHI1010', 'uploads/bar-codes/barcode_CHI1010_6990ad6d5da3b.png', 0, 0, 0, 1, '2026-02-14 17:14:21', '2026-02-14 17:14:21'),
(243, NULL, 'barcode', 'code128', 'FIRE1001', 'uploads/bar-codes/barcode_FIRE1001_6990ada246223.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(244, NULL, 'barcode', 'code128', 'FIRE1002', 'uploads/bar-codes/barcode_FIRE1002_6990ada2480ca.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(245, NULL, 'barcode', 'code128', 'FIRE1003', 'uploads/bar-codes/barcode_FIRE1003_6990ada249001.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(246, NULL, 'barcode', 'code128', 'FIRE1004', 'uploads/bar-codes/barcode_FIRE1004_6990ada2501d5.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(247, NULL, 'barcode', 'code128', 'FIRE1005', 'uploads/bar-codes/barcode_FIRE1005_6990ada253ee8.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(248, NULL, 'barcode', 'code128', 'FIRE1006', 'uploads/bar-codes/barcode_FIRE1006_6990ada257ee8.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(249, NULL, 'barcode', 'code128', 'FIRE1007', 'uploads/bar-codes/barcode_FIRE1007_6990ada258da0.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(250, NULL, 'barcode', 'code128', 'FIRE1008', 'uploads/bar-codes/barcode_FIRE1008_6990ada25f406.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(251, NULL, 'barcode', 'code128', 'FIRE1009', 'uploads/bar-codes/barcode_FIRE1009_6990ada26010c.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(252, NULL, 'barcode', 'code128', 'FIRE1010', 'uploads/bar-codes/barcode_FIRE1010_6990ada260e34.png', 0, 0, 0, 1, '2026-02-14 17:15:14', '2026-02-14 17:15:14'),
(253, NULL, 'barcode', 'code128', 'FIRE1001', 'uploads/bar-codes/barcode_FIRE1001_6990adb9d6ea9.png', 0, 0, 0, 1, '2026-02-14 17:15:37', '2026-02-14 17:15:37'),
(254, NULL, 'barcode', 'code128', 'FIRE1002', 'uploads/bar-codes/barcode_FIRE1002_6990adb9e0418.png', 0, 0, 0, 1, '2026-02-14 17:15:37', '2026-02-14 17:15:37'),
(255, NULL, 'barcode', 'code128', 'FIRE1003', 'uploads/bar-codes/barcode_FIRE1003_6990adb9e4693.png', 0, 0, 0, 1, '2026-02-14 17:15:37', '2026-02-14 17:15:37'),
(256, NULL, 'barcode', 'code128', 'FIRE1004', 'uploads/bar-codes/barcode_FIRE1004_6990adb9e8f58.png', 0, 0, 0, 1, '2026-02-14 17:15:37', '2026-02-14 17:15:37'),
(257, NULL, 'barcode', 'code128', 'FIRE1005', 'uploads/bar-codes/barcode_FIRE1005_6990adb9ed4b2.png', 0, 0, 0, 1, '2026-02-14 17:15:37', '2026-02-14 17:15:37'),
(258, NULL, 'barcode', 'code128', 'FIRE1006', 'uploads/bar-codes/barcode_FIRE1006_6990adb9f19fd.png', 0, 0, 0, 1, '2026-02-14 17:15:38', '2026-02-14 17:15:38'),
(259, NULL, 'barcode', 'code128', 'FIRE1007', 'uploads/bar-codes/barcode_FIRE1007_6990adba01e48.png', 0, 0, 0, 1, '2026-02-14 17:15:38', '2026-02-14 17:15:38'),
(260, NULL, 'barcode', 'code128', 'FIRE1008', 'uploads/bar-codes/barcode_FIRE1008_6990adba06016.png', 0, 0, 0, 1, '2026-02-14 17:15:38', '2026-02-14 17:15:38'),
(261, NULL, 'barcode', 'code128', 'FIRE1009', 'uploads/bar-codes/barcode_FIRE1009_6990adba0e539.png', 0, 0, 0, 1, '2026-02-14 17:15:38', '2026-02-14 17:15:38'),
(262, NULL, 'barcode', 'code128', 'FIRE1010', 'uploads/bar-codes/barcode_FIRE1010_6990adba125d9.png', 0, 0, 0, 1, '2026-02-14 17:15:38', '2026-02-14 17:15:38'),
(263, 1, 'qr', 'url', 'https://mkportfolio.crestdigico.com', 'uploads/qr-codes/qr_6990c5041e970.png', 1, 1, 0, 1, '2026-02-14 18:55:00', '2026-02-14 18:55:00'),
(264, 1, 'barcode', 'code128', 'DRG1001', 'uploads/bar-codes/barcode_DRG1001_6990d0be034b7.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(265, 1, 'barcode', 'code128', 'DRG1002', 'uploads/bar-codes/barcode_DRG1002_6990d0be0a758.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(266, 1, 'barcode', 'code128', 'DRG1003', 'uploads/bar-codes/barcode_DRG1003_6990d0be0e569.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(267, 1, 'barcode', 'code128', 'DRG1004', 'uploads/bar-codes/barcode_DRG1004_6990d0be124a7.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(268, 1, 'barcode', 'code128', 'DRG1005', 'uploads/bar-codes/barcode_DRG1005_6990d0be1a868.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(269, 1, 'barcode', 'code128', 'DRG1006', 'uploads/bar-codes/barcode_DRG1006_6990d0be1e1f0.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(270, 1, 'barcode', 'code128', 'DRG1007', 'uploads/bar-codes/barcode_DRG1007_6990d0be21c46.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(271, 1, 'barcode', 'code128', 'DRG1008', 'uploads/bar-codes/barcode_DRG1008_6990d0be257e0.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(272, 1, 'barcode', 'code128', 'DRG1009', 'uploads/bar-codes/barcode_DRG1009_6990d0be2961b.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(273, 1, 'barcode', 'code128', 'DRG1010', 'uploads/bar-codes/barcode_DRG1010_6990d0be2d6e1.png', 0, 0, 0, 1, '2026-02-14 19:45:02', '2026-02-14 19:45:02'),
(274, 1, 'qr', 'url', 'https://wa.me/+2347031261063?text=I\'m%20interested%20in%20your%20purchasing%20a%20wholesale%20quantity%20of%20your%20chips', 'uploads/qr-codes/qr_6990d5c0cbce6.png', 1, 1, 0, 1, '2026-02-14 20:06:24', '2026-02-14 20:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `qr_customizations`
--

CREATE TABLE `qr_customizations` (
  `id` int(11) NOT NULL,
  `qr_code_id` int(11) NOT NULL,
  `foreground_color` varchar(7) DEFAULT '#000000',
  `background_color` varchar(7) DEFAULT '#FFFFFF',
  `logo_path` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT 300,
  `error_correction` varchar(1) DEFAULT 'M'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `qr_scans`
--

CREATE TABLE `qr_scans` (
  `id` int(11) NOT NULL,
  `qr_code_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `scanned_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'mkleo', 'miketaylorisgood@gmail.com', '$2y$10$b5xhb4aqG.UZIHMXYaY9Ge0RHuyvrNs88kfuKxW/O.fASkpDLbfCS', '2026-02-14 18:53:13', '2026-02-14 18:53:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qr_code_id` (`qr_code_id`);

--
-- Indexes for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_data_type` (`data_type`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `qr_customizations`
--
ALTER TABLE `qr_customizations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qr_code_id` (`qr_code_id`);

--
-- Indexes for table `qr_scans`
--
ALTER TABLE `qr_scans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_qr_code_id` (`qr_code_id`),
  ADD KEY `idx_scanned_at` (`scanned_at`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_uploads`
--
ALTER TABLE `file_uploads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_codes`
--
ALTER TABLE `qr_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `qr_customizations`
--
ALTER TABLE `qr_customizations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `qr_scans`
--
ALTER TABLE `qr_scans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_uploads`
--
ALTER TABLE `file_uploads`
  ADD CONSTRAINT `file_uploads_ibfk_1` FOREIGN KEY (`qr_code_id`) REFERENCES `qr_codes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_codes`
--
ALTER TABLE `qr_codes`
  ADD CONSTRAINT `qr_codes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_customizations`
--
ALTER TABLE `qr_customizations`
  ADD CONSTRAINT `qr_customizations_ibfk_1` FOREIGN KEY (`qr_code_id`) REFERENCES `qr_codes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `qr_scans`
--
ALTER TABLE `qr_scans`
  ADD CONSTRAINT `qr_scans_ibfk_1` FOREIGN KEY (`qr_code_id`) REFERENCES `qr_codes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
