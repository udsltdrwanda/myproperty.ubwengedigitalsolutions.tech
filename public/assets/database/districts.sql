-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 26, 2025 at 02:27 PM
-- Server version: 10.6.21-MariaDB-cll-lve
-- PHP Version: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tuzaasse_TuzaAssets`
--

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

CREATE TABLE `districts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `province_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `name`, `province_id`) VALUES
(101, 'NYARUGENGE', 1),
(102, 'GASABO', 1),
(103, 'KICUKIRO', 1),
(201, 'NYANZA', 2),
(202, 'GISAGARA', 2),
(203, 'NYARUGURU', 2),
(204, 'HUYE', 2),
(205, 'NYAMAGABE', 2),
(206, 'RUHANGO', 2),
(207, 'MUHANGA', 2),
(208, 'KAMONYI', 2),
(301, 'KARONGI', 3),
(302, 'RUTSIRO', 3),
(303, 'RUBAVU', 3),
(304, 'NYABIHU', 3),
(305, 'NGORORERO', 3),
(306, 'RUSIZI', 3),
(307, 'NYAMASHEKE', 3),
(401, 'RULINDO', 4),
(402, 'GAKENKE', 4),
(403, 'MUSANZE', 4),
(404, 'BURERA', 4),
(405, 'GICUMBI', 4),
(501, 'RWAMAGANA', 5),
(502, 'NYAGATARE', 5),
(503, 'GATSIBO', 5),
(504, 'KAYONZA', 5),
(505, 'KIREHE', 5),
(506, 'NGOMA', 5),
(507, 'BUGESERA', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `districts`
--
ALTER TABLE `districts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `districts_province_id_foreign` (`province_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `districts`
--
ALTER TABLE `districts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=508;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `districts`
--
ALTER TABLE `districts`
  ADD CONSTRAINT `districts_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
