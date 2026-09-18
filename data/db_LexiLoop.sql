-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Sep 18, 2026 at 10:46 AM
-- Server version: 8.0.46
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hoc_ngoai_ngu`
--

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `avatar_url` varchar(250) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','locked') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `update_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `daily_reminder_enabled` tinyint DEFAULT '1',
  `reminder_time` time DEFAULT '20:00:00',
  `daily_target_words` int DEFAULT '20'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`userID`, `email`, `password_hash`, `full_name`, `avatar_url`, `role`, `status`, `created_at`, `update_at`, `daily_reminder_enabled`, `reminder_time`, `daily_target_words`) VALUES
(1, 'admin@example.com', 'e10adc3949ba59abbe56e057f20f883e', 'Quản Trị Viên', NULL, 'admin', 'active', '2026-08-28 16:40:44', '2026-09-13 05:06:54', 1, '20:00:00', 20),
(4, 'quana2406@gmail.com', '$2y$10$UZDIvvyYTPZYsQ114e9YoOYbbO2wdhK5O9JjQihk/zVJekdIz6aEe', 'Lê Minh Quân', NULL, 'admin', 'active', '2026-09-12 12:43:27', '2026-09-15 04:31:55', 1, '20:00:00', 20),
(8, 'admin2@example.com', '$2y$10$e8MYzXyjpJS7Pd0RVvHwHeFXx9r7Xw4M7xHnJ8mP2vL0Qz7w7XbGy', 'Quản Trị Viên 2', NULL, 'admin', 'active', '2026-09-13 05:13:17', '2026-09-13 05:19:23', 1, '20:00:00', 20);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
