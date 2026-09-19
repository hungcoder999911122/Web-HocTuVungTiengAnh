-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: db
-- Thời gian đã tạo: Th9 19, 2026 lúc 04:56 PM
-- Phiên bản máy phục vụ: 8.0.46
-- Phiên bản PHP: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `db_LexiLoop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Users`
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
-- Đang đổ dữ liệu cho bảng `Users`
--

INSERT INTO `Users` (`userID`, `email`, `password_hash`, `full_name`, `avatar_url`, `role`, `status`, `created_at`, `update_at`, `daily_reminder_enabled`, `reminder_time`, `daily_target_words`) VALUES
(3, 'hungkill146@gmail.com', '$2y$10$Z2/VG5nprFQcK/p6Gdaj0eYcCOx/q48FpHzysLHp04mFLgsJntsyC', 'Nguyễn Tuấn Hùng', NULL, 'user', 'active', '2026-09-06 00:49:49', '2026-09-08 14:21:59', 1, '20:00:00', 20),
(4, 'quana2406@gmail.com', '$2y$10$2LryIgqEinFeQUKhaPwCnOYNWjVdm3d7osIYrR5VDGw74Sl6vb4Ry', 'Lê Minh Quân', NULL, 'user', 'active', '2026-09-19 22:29:07', '2026-09-19 23:44:03', 1, '20:00:00', 20),
(5, 'Admin123@gmail.com', '$2y$10$DkMXgKEjAn8lwvW0uoRdz.St7bqdjSTe12/mQa7AHF..DrZbbpep2', 'Quản trị viên ', NULL, 'admin', 'active', '2026-09-19 23:02:15', '2026-09-19 16:02:46', 1, '20:00:00', 20);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
