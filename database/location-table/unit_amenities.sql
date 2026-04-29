-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2025 at 11:16 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mbg_property_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `unit_amenities`
--

CREATE TABLE `unit_amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unit_amenities`
--

INSERT INTO `unit_amenities` (`id`, `name`, `unit_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(30, 'Kitchen', 10, '2024-07-03 16:21:04', '2024-07-03 16:21:04', NULL),
(31, 'Cleaning Material', 10, '2024-07-03 16:22:05', '2024-07-03 16:22:05', NULL),
(32, 'Dining Room', 10, '2024-07-03 16:23:06', '2024-07-03 16:23:06', NULL),
(33, 'Toilent', 10, '2024-07-03 16:24:15', '2024-07-03 16:24:15', NULL),
(34, 'Internet', 10, '2024-07-03 16:25:02', '2024-07-03 16:25:02', NULL),
(36, 'Bedroom ', 10, '2024-07-03 16:26:36', '2024-07-03 16:26:36', NULL),
(37, 'washing room', 10, '2024-07-03 16:26:51', '2024-07-03 16:26:51', NULL),
(39, 'Parking', 10, '2024-07-03 16:27:51', '2024-07-03 16:27:51', NULL),
(40, 'Office Tables', 10, '2024-07-03 16:28:29', '2024-07-03 16:28:29', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `unit_amenities`
--
ALTER TABLE `unit_amenities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_amenities_unit_id_foreign` (`unit_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `unit_amenities`
--
ALTER TABLE `unit_amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
