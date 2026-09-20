-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: db
-- Thời gian đã tạo: Th9 20, 2026 lúc 04:33 PM
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
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `learning_attempts`
--

CREATE TABLE `learning_attempts` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `activity_type` enum('flashcard','quiz') NOT NULL,
  `source_type` enum('topic','set','review') NOT NULL,
  `source_id` int DEFAULT NULL,
  `item_limit` varchar(10) NOT NULL DEFAULT '10',
  `state_json` json NOT NULL,
  `status` enum('in_progress','completed','abandoned') NOT NULL DEFAULT 'in_progress',
  `started_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `learning_attempts`
--

INSERT INTO `learning_attempts` (`id`, `user_id`, `activity_type`, `source_type`, `source_id`, `item_limit`, `state_json`, `status`, `started_at`, `updated_at`, `completed_at`) VALUES
(1, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"da_nho\"}, \"currentIndex\": 0, \"durationSeconds\": 11}', 'completed', '2026-09-13 03:55:28', '2026-09-13 03:55:46', '2026-09-13 03:55:46'),
(2, 3, 'quiz', 'set', 1, '10', '{\"questions\": [{\"id\": 210, \"dap_an\": [\"A. khoản đầu tư\", \"B. Công nghệ\", \"C. phát hiện\", \"D. tái chế\"], \"tu_vung\": \"Technology\", \"dap_an_dung\": 1}, {\"id\": 211, \"dap_an\": [\"A. máy tính\", \"B. phát hiện\", \"C. cơ sở dữ liệu\", \"D. hành khách\"], \"tu_vung\": \"Computer\", \"dap_an_dung\": 0}], \"userAnswers\": [1, 0], \"currentIndex\": 1, \"remainingTimes\": [7, 6], \"durationSeconds\": 20}', 'completed', '2026-09-13 03:55:52', '2026-09-13 04:26:01', '2026-09-13 04:26:01'),
(3, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"chua_nho\", \"22\": \"da_nho\", \"23\": \"chua_nho\", \"24\": \"da_nho\", \"25\": \"chua_nho\", \"26\": \"da_nho\", \"27\": \"chua_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 7}', 'completed', '2026-09-13 03:56:14', '2026-09-13 03:56:25', '2026-09-13 03:56:25'),
(4, 3, 'quiz', 'topic', 1, '10', '{\"questions\": [{\"id\": 29, \"dap_an\": [\"A. buổi hòa nhạc\", \"B. chim cánh cụt\", \"C. tự nhiên\", \"D. ẩm thực\"], \"tu_vung\": \"Penguin\", \"dap_an_dung\": 1}, {\"id\": 22, \"dap_an\": [\"A. chim cánh cụt\", \"B. hổ\", \"C. xe buýt\", \"D. ngon\"], \"tu_vung\": \"Tiger\", \"dap_an_dung\": 1}, {\"id\": 25, \"dap_an\": [\"A. giai điệu\", \"B. thiết bị\", \"C. Công nghệ\", \"D. cá heo\"], \"tu_vung\": \"Dolphin\", \"dap_an_dung\": 3}, {\"id\": 24, \"dap_an\": [\"A. ngon\", \"B. đợt giảm giá\", \"C. khỉ\", \"D. thiết bị\"], \"tu_vung\": \"Monkey\", \"dap_an_dung\": 2}, {\"id\": 27, \"dap_an\": [\"A. chiến thắng\", \"B. đại bàng\", \"C. ẩm thực\", \"D. nhà nghiên cứu\"], \"tu_vung\": \"Eagle\", \"dap_an_dung\": 1}, {\"id\": 23, \"dap_an\": [\"A. cá voi\", \"B. chiến thắng\", \"C. hươu cao cổ\", \"D. xu hướng\"], \"tu_vung\": \"Giraffe\", \"dap_an_dung\": 2}, {\"id\": 21, \"dap_an\": [\"A. con thỏ\", \"B. sư tử\", \"C. giai điệu\", \"D. thiết bị\"], \"tu_vung\": \"Lion\", \"dap_an_dung\": 1}, {\"id\": 26, \"dap_an\": [\"A. con thỏ\", \"B. quà lưu niệm\", \"C. xe buýt\", \"D. thời trang\"], \"tu_vung\": \"Rabbit\", \"dap_an_dung\": 0}, {\"id\": 1, \"dap_an\": [\"A. quà lưu niệm\", \"B. hành trình\", \"C. con voi\", \"D. cá heo\"], \"tu_vung\": \"Elephant\", \"dap_an_dung\": 2}, {\"id\": 28, \"dap_an\": [\"A. ẩm thực\", \"B. nhiệm vụ\", \"C. cá voi\", \"D. điểm đến\"], \"tu_vung\": \"Whale\", \"dap_an_dung\": 2}], \"userAnswers\": [3, 3, 1, 2, 0, 1, 2, 0, 2, 2], \"currentIndex\": 9, \"remainingTimes\": [13, 14, 14, 14, 14, 14, 15, 14, 14, 3], \"durationSeconds\": 25}', 'completed', '2026-09-13 03:56:26', '2026-09-13 03:57:23', '2026-09-13 03:57:23'),
(5, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 20}', 'completed', '2026-09-13 03:56:45', '2026-09-13 03:57:17', '2026-09-13 03:57:17'),
(6, 3, 'quiz', 'topic', 1, '10', '{\"questions\": [{\"id\": 27, \"dap_an\": [\"A. xu hướng\", \"B. hợp đồng\", \"C. đại bàng\", \"D. nhịp điệu\"], \"tu_vung\": \"Eagle\", \"dap_an_dung\": 2}, {\"id\": 29, \"dap_an\": [\"A. chim cánh cụt\", \"B. triệu chứng\", \"C. bộ phim\", \"D. đội\"], \"tu_vung\": \"Penguin\", \"dap_an_dung\": 0}, {\"id\": 1, \"dap_an\": [\"A. phân tích\", \"B. chim cánh cụt\", \"C. con voi\", \"D. cấu trúc, công trình\"], \"tu_vung\": \"Elephant\", \"dap_an_dung\": 2}, {\"id\": 28, \"dap_an\": [\"A. cá voi\", \"B. con voi\", \"C. mạng\", \"D. văn phòng\"], \"tu_vung\": \"Whale\", \"dap_an_dung\": 0}, {\"id\": 24, \"dap_an\": [\"A. nền móng\", \"B. khỉ\", \"C. mã hóa\", \"D. xu hướng\"], \"tu_vung\": \"Monkey\", \"dap_an_dung\": 1}, {\"id\": 21, \"dap_an\": [\"A. công thức nấu ăn\", \"B. sư tử\", \"C. nhà soạn nhạc\", \"D. hổ\"], \"tu_vung\": \"Lion\", \"dap_an_dung\": 1}, {\"id\": 22, \"dap_an\": [\"A. hổ\", \"B. điều trị\", \"C. bài tập\", \"D. cá voi\"], \"tu_vung\": \"Tiger\", \"dap_an_dung\": 0}, {\"id\": 25, \"dap_an\": [\"A. trình duyệt\", \"B. cá heo\", \"C. bệnh nhân\", \"D. phân tích\"], \"tu_vung\": \"Dolphin\", \"dap_an_dung\": 1}, {\"id\": 26, \"dap_an\": [\"A. khoản vay\", \"B. xu hướng\", \"C. sư tử\", \"D. con thỏ\"], \"tu_vung\": \"Rabbit\", \"dap_an_dung\": 3}, {\"id\": 23, \"dap_an\": [\"A. doanh thu\", \"B. bệnh nhân\", \"C. lượng mưa\", \"D. hươu cao cổ\"], \"tu_vung\": \"Giraffe\", \"dap_an_dung\": 3}], \"userAnswers\": [0, 3, 3, 1, 2, 1, 0, 2, 0, 2], \"currentIndex\": 9, \"remainingTimes\": [12, 15, 14, 14, 15, 15, 14, 14, 15, 15], \"durationSeconds\": 14}', 'completed', '2026-09-13 03:57:31', '2026-09-13 04:16:54', '2026-09-13 04:16:54'),
(7, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 16}', 'completed', '2026-09-13 03:58:14', '2026-09-13 04:01:51', '2026-09-13 04:01:51'),
(8, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"chua_nho\", \"21\": \"chua_nho\", \"22\": \"chua_nho\", \"23\": \"chua_nho\", \"24\": \"chua_nho\", \"25\": \"chua_nho\", \"26\": \"chua_nho\", \"27\": \"chua_nho\", \"28\": \"chua_nho\", \"29\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 6}', 'completed', '2026-09-13 04:01:53', '2026-09-13 04:04:10', '2026-09-13 04:04:10'),
(9, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"chua_nho\", \"211\": \"da_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 114}', 'completed', '2026-09-13 04:10:36', '2026-09-13 04:25:21', '2026-09-13 04:25:21'),
(10, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 6}', 'completed', '2026-09-13 04:16:03', '2026-09-13 04:16:09', '2026-09-13 04:16:09'),
(11, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"chua_nho\", \"21\": \"da_nho\", \"22\": \"chua_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 7, \"durationSeconds\": 34}', 'completed', '2026-09-13 04:16:15', '2026-09-13 04:18:08', '2026-09-13 04:18:08'),
(12, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 4}', 'completed', '2026-09-13 04:18:13', '2026-09-13 04:18:17', '2026-09-13 04:18:17'),
(13, 3, 'quiz', 'topic', 1, '10', '{\"questions\": [{\"id\": 29, \"dap_an\": [\"A. chim cánh cụt\", \"B. quần áo\", \"C. quà lưu niệm\", \"D. rác thải\"], \"tu_vung\": \"Penguin\", \"dap_an_dung\": 0}, {\"id\": 21, \"dap_an\": [\"A. cuộc thi đấu\", \"B. sư tử\", \"C. máy tính\", \"D. thương hiệu\"], \"tu_vung\": \"Lion\", \"dap_an_dung\": 1}, {\"id\": 28, \"dap_an\": [\"A. vải\", \"B. nhân viên\", \"C. máy tính\", \"D. cá voi\"], \"tu_vung\": \"Whale\", \"dap_an_dung\": 3}, {\"id\": 26, \"dap_an\": [\"A. con thỏ\", \"B. rừng\", \"C. cuộc họp\", \"D. bộ phim\"], \"tu_vung\": \"Rabbit\", \"dap_an_dung\": 0}, {\"id\": 22, \"dap_an\": [\"A. tàu điện ngầm\", \"B. cá voi\", \"C. hổ\", \"D. vải\"], \"tu_vung\": \"Tiger\", \"dap_an_dung\": 2}, {\"id\": 27, \"dap_an\": [\"A. rác thải\", \"B. cá voi\", \"C. đại bàng\", \"D. sự quan sát\"], \"tu_vung\": \"Eagle\", \"dap_an_dung\": 2}, {\"id\": 25, \"dap_an\": [\"A. văn phòng\", \"B. cá heo\", \"C. đàn ghi-ta\", \"D. thương hiệu\"], \"tu_vung\": \"Dolphin\", \"dap_an_dung\": 1}, {\"id\": 1, \"dap_an\": [\"A. sự quan sát\", \"B. đồng nghiệp\", \"C. chim cánh cụt\", \"D. con voi\"], \"tu_vung\": \"Elephant\", \"dap_an_dung\": 3}, {\"id\": 24, \"dap_an\": [\"A. thí nghiệm\", \"B. khỉ\", \"C. lời bài hát\", \"D. con voi\"], \"tu_vung\": \"Monkey\", \"dap_an_dung\": 1}, {\"id\": 23, \"dap_an\": [\"A. khỉ\", \"B. con voi\", \"C. quần áo\", \"D. hươu cao cổ\"], \"tu_vung\": \"Giraffe\", \"dap_an_dung\": 3}], \"userAnswers\": [], \"currentIndex\": 9, \"remainingTimes\": [13, 15, 15, 15, 15, 15, 15, 15, 15, 15], \"durationSeconds\": 5}', 'completed', '2026-09-13 04:18:48', '2026-09-13 04:18:54', '2026-09-13 04:18:54'),
(14, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 3}', 'completed', '2026-09-13 04:21:07', '2026-09-13 04:21:10', '2026-09-13 04:21:10'),
(15, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"chua_nho\", \"211\": \"da_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 121}', 'completed', '2026-09-13 04:25:25', '2026-09-13 04:25:34', '2026-09-13 04:25:34'),
(16, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"da_nho\", \"211\": \"chua_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 127}', 'completed', '2026-09-13 04:25:36', '2026-09-13 04:38:47', '2026-09-13 04:38:47'),
(17, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 5}', 'completed', '2026-09-13 04:28:45', '2026-09-13 04:28:51', '2026-09-13 04:28:51'),
(18, 3, 'flashcard', 'topic', 20, '20', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\", \"201\": \"da_nho\"}, \"currentIndex\": 10, \"durationSeconds\": 3}', 'completed', '2026-09-13 04:29:01', '2026-09-13 04:29:05', '2026-09-13 04:29:05'),
(19, 3, 'quiz', 'topic', 20, '20', '{\"questions\": [{\"id\": 200, \"dap_an\": [\"A. sự kiên nhẫn\", \"B. vốn\", \"C. có nắng\", \"D. tàu hỏa\"], \"tu_vung\": \"Patience\", \"dap_an_dung\": 0}, {\"id\": 195, \"dap_an\": [\"A. giai điệu\", \"B. nội thất, bên trong\", \"C. sự tức giận\", \"D. nỗi sợ\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 3}, {\"id\": 201, \"dap_an\": [\"A. nội thất, bên trong\", \"B. nỗi sợ\", \"C. Hạnh phúc\", \"D. ô nhiễm\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 2}, {\"id\": 197, \"dap_an\": [\"A. giao thông\", \"B. chương trình học\", \"C. sự tức giận\", \"D. sự tự tin\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 3}, {\"id\": 193, \"dap_an\": [\"A. thiết kế\", \"B. nỗi buồn\", \"C. mã hóa\", \"D. triệu chứng\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 1}, {\"id\": 20, \"dap_an\": [\"A. quần áo\", \"B. sự đồng cảm\", \"C. bệnh\", \"D. ngân sách\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 1}, {\"id\": 199, \"dap_an\": [\"A. phòng ban\", \"B. sự lo lắng\", \"C. nhà nghiên cứu\", \"D. chương trình học\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 1}, {\"id\": 194, \"dap_an\": [\"A. tàu hỏa\", \"B. sự tức giận\", \"C. sự phấn khích\", \"D. nội thất, bên trong\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 1}, {\"id\": 192, \"dap_an\": [\"A. hạnh phúc\", \"B. nguyên liệu\", \"C. sự lo lắng\", \"D. nhiệt độ\"], \"tu_vung\": \"Happiness\", \"dap_an_dung\": 0}, {\"id\": 198, \"dap_an\": [\"A. ngân sách\", \"B. ô nhiễm\", \"C. có nắng\", \"D. sự ngạc nhiên\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 3}, {\"id\": 196, \"dap_an\": [\"A. tàu hỏa\", \"B. sự phấn khích\", \"C. ngân sách\", \"D. thương hiệu\"], \"tu_vung\": \"Excitement\", \"dap_an_dung\": 1}], \"userAnswers\": [0, 3, 2, 3, 1, 1, 1, 1, 0, 3, 1], \"currentIndex\": 10, \"remainingTimes\": [12, 13, 12, 12, 14, 11, 13, 14, 13, 12, 13], \"durationSeconds\": 30}', 'completed', '2026-09-13 04:29:11', '2026-09-13 04:29:42', '2026-09-13 04:29:42'),
(20, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"chua_nho\", \"192\": \"chua_nho\", \"193\": \"chua_nho\", \"194\": \"chua_nho\", \"195\": \"chua_nho\", \"196\": \"chua_nho\", \"197\": \"chua_nho\", \"198\": \"chua_nho\", \"199\": \"chua_nho\", \"200\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 2}', 'completed', '2026-09-13 04:30:42', '2026-09-13 04:30:45', '2026-09-13 04:30:45'),
(21, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"chua_nho\", \"192\": \"chua_nho\", \"193\": \"chua_nho\", \"194\": \"chua_nho\", \"195\": \"chua_nho\", \"196\": \"chua_nho\", \"197\": \"chua_nho\", \"198\": \"chua_nho\", \"199\": \"chua_nho\", \"200\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 3}', 'completed', '2026-09-13 04:30:50', '2026-09-13 04:30:53', '2026-09-13 04:30:53'),
(22, 3, 'flashcard', 'topic', 20, '20', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201], \"cardStatuses\": {\"20\": \"chua_nho\", \"192\": \"chua_nho\", \"193\": \"chua_nho\", \"194\": \"chua_nho\", \"195\": \"chua_nho\", \"196\": \"chua_nho\", \"197\": \"chua_nho\", \"198\": \"chua_nho\", \"199\": \"chua_nho\", \"200\": \"chua_nho\", \"201\": \"chua_nho\"}, \"currentIndex\": 10, \"durationSeconds\": 3}', 'completed', '2026-09-13 04:31:01', '2026-09-13 04:31:04', '2026-09-13 04:31:04'),
(23, 3, 'quiz', 'topic', 20, '20', '{\"questions\": [{\"id\": 192, \"dap_an\": [\"A. sự tức giận\", \"B. hạnh phúc\", \"C. bài thuyết trình\", \"D. phòng thí nghiệm\"], \"tu_vung\": \"Happiness\", \"dap_an_dung\": 1}, {\"id\": 198, \"dap_an\": [\"A. sự ngạc nhiên\", \"B. bài thuyết trình\", \"C. độ ẩm\", \"D. xu hướng\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 0}, {\"id\": 199, \"dap_an\": [\"A. nỗi buồn\", \"B. sự lo lắng\", \"C. phần mềm\", \"D. phân tích\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 1}, {\"id\": 20, \"dap_an\": [\"A. máy tính\", \"B. nỗi sợ\", \"C. cay\", \"D. sự đồng cảm\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 3}, {\"id\": 195, \"dap_an\": [\"A. triệu chứng\", \"B. nỗi sợ\", \"C. máy tính\", \"D. nỗi buồn\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 1}, {\"id\": 201, \"dap_an\": [\"A. Hạnh phúc\", \"B. máy tính\", \"C. chương trình học\", \"D. màn trình diễn\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 0}, {\"id\": 200, \"dap_an\": [\"A. Hạnh phúc\", \"B. sự kiên nhẫn\", \"C. nhiều mây\", \"D. ẩm thực\"], \"tu_vung\": \"Patience\", \"dap_an_dung\": 1}, {\"id\": 193, \"dap_an\": [\"A. phát hiện\", \"B. bản thiết kế\", \"C. nỗi buồn\", \"D. ô nhiễm\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 2}, {\"id\": 197, \"dap_an\": [\"A. Hạnh phúc\", \"B. sự tự tin\", \"C. nhạc cụ\", \"D. lịch trình\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 1}, {\"id\": 194, \"dap_an\": [\"A. phòng thí nghiệm\", \"B. bản thiết kế\", \"C. màn trình diễn\", \"D. sự tức giận\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 3}, {\"id\": 196, \"dap_an\": [\"A. nguyên liệu\", \"B. máy chủ\", \"C. độ ẩm\", \"D. sự phấn khích\"], \"tu_vung\": \"Excitement\", \"dap_an_dung\": 3}], \"userAnswers\": [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], \"currentIndex\": 10, \"remainingTimes\": [13, 15, 15, 15, 15, 15, 15, 15, 15, 15, 15], \"durationSeconds\": 7}', 'completed', '2026-09-13 04:31:12', '2026-09-13 04:31:19', '2026-09-13 04:31:19'),
(24, 3, 'quiz', 'topic', 20, '10', '{\"questions\": [{\"id\": 197, \"dap_an\": [\"A. sự tự tin\", \"B. thể loại\", \"C. thuật toán\", \"D. khách hàng\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 0}, {\"id\": 193, \"dap_an\": [\"A. nỗi buồn\", \"B. sư tử\", \"C. sự tự tin\", \"D. hệ sinh thái\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 0}, {\"id\": 198, \"dap_an\": [\"A. sự ngạc nhiên\", \"B. phần mềm\", \"C. giả thuyết\", \"D. bệnh viện\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 0}, {\"id\": 194, \"dap_an\": [\"A. tái chế\", \"B. sự tức giận\", \"C. hộ chiếu\", \"D. vận động viên\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 1}, {\"id\": 200, \"dap_an\": [\"A. vận động viên\", \"B. đội\", \"C. bê tông\", \"D. sự kiên nhẫn\"], \"tu_vung\": \"Patience\", \"dap_an_dung\": 3}, {\"id\": 199, \"dap_an\": [\"A. sự ngạc nhiên\", \"B. sự tự tin\", \"C. sự lo lắng\", \"D. xe đạp\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 2}, {\"id\": 20, \"dap_an\": [\"A. sự đồng cảm\", \"B. đại bàng\", \"C. giả thuyết\", \"D. sự tự tin\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 0}, {\"id\": 201, \"dap_an\": [\"A. sự lo lắng\", \"B. Hạnh phúc\", \"C. hộ chiếu\", \"D. hành lý\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 1}, {\"id\": 195, \"dap_an\": [\"A. nhiệm vụ\", \"B. nỗi sợ\", \"C. khỉ\", \"D. xu hướng\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 1}, {\"id\": 192, \"dap_an\": [\"A. hạnh phúc\", \"B. đại bàng\", \"C. nhà soạn nhạc\", \"D. khách hàng\"], \"tu_vung\": \"Happiness\", \"dap_an_dung\": 0}], \"userAnswers\": [3, 3, 3, 3, 3, 3, 3, 3, 3, 3], \"currentIndex\": 9, \"remainingTimes\": [13, 15, 15, 15, 15, 15, 15, 15, 15, 15], \"durationSeconds\": 8}', 'completed', '2026-09-13 04:32:39', '2026-09-13 04:43:06', '2026-09-13 04:43:06'),
(25, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"da_nho\", \"211\": \"da_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 134}', 'completed', '2026-09-13 04:38:48', '2026-09-13 04:57:01', '2026-09-13 04:57:01'),
(26, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"chua_nho\", \"192\": \"chua_nho\", \"193\": \"chua_nho\", \"194\": \"chua_nho\", \"195\": \"chua_nho\", \"196\": \"chua_nho\", \"197\": \"chua_nho\", \"198\": \"chua_nho\", \"199\": \"chua_nho\", \"200\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 3}', 'completed', '2026-09-13 04:39:59', '2026-09-13 04:40:02', '2026-09-13 04:40:02'),
(27, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"chua_nho\", \"192\": \"chua_nho\", \"193\": \"da_nho\", \"194\": \"chua_nho\", \"195\": \"da_nho\", \"196\": \"chua_nho\", \"197\": \"da_nho\", \"198\": \"chua_nho\", \"199\": \"da_nho\", \"200\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 8}', 'completed', '2026-09-13 04:40:47', '2026-09-13 04:40:55', '2026-09-13 04:40:55'),
(28, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 7}', 'completed', '2026-09-13 04:42:56', '2026-09-13 05:09:10', '2026-09-13 05:09:10'),
(29, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 4}', 'completed', '2026-09-13 04:43:23', '2026-09-13 04:44:13', '2026-09-13 04:44:13'),
(30, 3, 'quiz', 'topic', 1, '10', '{\"questions\": [{\"id\": 21, \"dap_an\": [\"A. sư tử\", \"B. bệnh viện\", \"C. sự hồi phục\", \"D. thời trang\"], \"tu_vung\": \"Lion\", \"dap_an_dung\": 0}, {\"id\": 23, \"dap_an\": [\"A. nỗi buồn\", \"B. chim cánh cụt\", \"C. hươu cao cổ\", \"D. tàu hỏa\"], \"tu_vung\": \"Giraffe\", \"dap_an_dung\": 2}, {\"id\": 24, \"dap_an\": [\"A. phần mềm\", \"B. hạnh phúc\", \"C. khỉ\", \"D. cá voi\"], \"tu_vung\": \"Monkey\", \"dap_an_dung\": 2}, {\"id\": 27, \"dap_an\": [\"A. đại bàng\", \"B. nỗi buồn\", \"C. thể loại\", \"D. sự đến nơi\"], \"tu_vung\": \"Eagle\", \"dap_an_dung\": 0}, {\"id\": 1, \"dap_an\": [\"A. tài khoản\", \"B. con voi\", \"C. tàu hỏa\", \"D. hạnh phúc\"], \"tu_vung\": \"Elephant\", \"dap_an_dung\": 1}, {\"id\": 26, \"dap_an\": [\"A. nỗi buồn\", \"B. con thỏ\", \"C. nội thất, bên trong\", \"D. bệnh viện\"], \"tu_vung\": \"Rabbit\", \"dap_an_dung\": 1}, {\"id\": 22, \"dap_an\": [\"A. cá heo\", \"B. thể loại\", \"C. hổ\", \"D. lời bài hát\"], \"tu_vung\": \"Tiger\", \"dap_an_dung\": 2}, {\"id\": 29, \"dap_an\": [\"A. cá heo\", \"B. dự báo\", \"C. chim cánh cụt\", \"D. phần mềm\"], \"tu_vung\": \"Penguin\", \"dap_an_dung\": 2}, {\"id\": 25, \"dap_an\": [\"A. sự đến nơi\", \"B. bệnh viện\", \"C. cá heo\", \"D. sự hồi phục\"], \"tu_vung\": \"Dolphin\", \"dap_an_dung\": 2}, {\"id\": 28, \"dap_an\": [\"A. con thỏ\", \"B. cá voi\", \"C. cơ sở dữ liệu\", \"D. sự hồi phục\"], \"tu_vung\": \"Whale\", \"dap_an_dung\": 1}], \"userAnswers\": {\"1\": 3, \"2\": 3, \"3\": 3, \"4\": 1, \"5\": 1, \"6\": 3, \"7\": 1, \"8\": 3}, \"currentIndex\": 9, \"remainingTimes\": [11, 14, 15, 15, 15, 15, 15, 15, 15, 14], \"durationSeconds\": 16}', 'completed', '2026-09-13 04:43:26', '2026-09-13 04:44:46', '2026-09-13 04:44:46'),
(31, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 7}', 'completed', '2026-09-13 04:44:17', '2026-09-13 04:54:45', '2026-09-13 04:54:45'),
(32, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 9}', 'completed', '2026-09-13 04:54:49', '2026-09-13 04:56:36', '2026-09-13 04:56:36'),
(33, 3, 'quiz', 'topic', 1, '10', '{\"questions\": [{\"id\": 1, \"dap_an\": [\"A. xây dựng\", \"B. học thuật\", \"C. độ ẩm\", \"D. con voi\"], \"tu_vung\": \"Elephant\", \"dap_an_dung\": 3}, {\"id\": 21, \"dap_an\": [\"A. sư tử\", \"B. độ ẩm\", \"C. chim cánh cụt\", \"D. thuộc về nhạc cụ\"], \"tu_vung\": \"Lion\", \"dap_an_dung\": 0}, {\"id\": 24, \"dap_an\": [\"A. sân bay\", \"B. nhà thiết kế\", \"C. ẩm thực\", \"D. khỉ\"], \"tu_vung\": \"Monkey\", \"dap_an_dung\": 3}, {\"id\": 25, \"dap_an\": [\"A. con voi\", \"B. bệnh viện\", \"C. cá heo\", \"D. chi phí\"], \"tu_vung\": \"Dolphin\", \"dap_an_dung\": 2}, {\"id\": 28, \"dap_an\": [\"A. cơ sở dữ liệu\", \"B. bộ phim\", \"C. ngon\", \"D. cá voi\"], \"tu_vung\": \"Whale\", \"dap_an_dung\": 3}, {\"id\": 22, \"dap_an\": [\"A. sư tử\", \"B. đại bàng\", \"C. hổ\", \"D. con thỏ\"], \"tu_vung\": \"Tiger\", \"dap_an_dung\": 2}, {\"id\": 27, \"dap_an\": [\"A. giai điệu\", \"B. phòng trưng bày\", \"C. đại bàng\", \"D. bê tông\"], \"tu_vung\": \"Eagle\", \"dap_an_dung\": 2}, {\"id\": 26, \"dap_an\": [\"A. sân bay\", \"B. con thỏ\", \"C. đợt giảm giá\", \"D. học sinh, sinh viên\"], \"tu_vung\": \"Rabbit\", \"dap_an_dung\": 1}, {\"id\": 29, \"dap_an\": [\"A. lượng mưa\", \"B. chim cánh cụt\", \"C. chương trình học\", \"D. ẩm thực\"], \"tu_vung\": \"Penguin\", \"dap_an_dung\": 1}, {\"id\": 23, \"dap_an\": [\"A. hổ\", \"B. thời trang\", \"C. hươu cao cổ\", \"D. sân bay\"], \"tu_vung\": \"Giraffe\", \"dap_an_dung\": 2}], \"userAnswers\": [3, 0, 3, 2, 3, 2, 2, 1, 1, 0], \"currentIndex\": 9, \"remainingTimes\": [11, 11, 13, 13, 9, 14, 13, 14, 14, 13], \"durationSeconds\": 30}', 'completed', '2026-09-13 04:55:14', '2026-09-13 05:35:50', '2026-09-13 05:35:50'),
(34, 3, 'quiz', 'topic', 20, '20', '{\"questions\": [{\"id\": 199, \"dap_an\": [\"A. tự nhiên\", \"B. triệu chứng\", \"C. sự lo lắng\", \"D. hành trình\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 2}, {\"id\": 196, \"dap_an\": [\"A. sự phấn khích\", \"B. triệu chứng\", \"C. thuật toán\", \"D. cá voi\"], \"tu_vung\": \"Excitement\", \"dap_an_dung\": 0}, {\"id\": 194, \"dap_an\": [\"A. hành trình\", \"B. khối lượng công việc\", \"C. nỗi sợ\", \"D. sự tức giận\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 3}, {\"id\": 20, \"dap_an\": [\"A. lịch trình\", \"B. hành trình\", \"C. việc mua hàng\", \"D. sự đồng cảm\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 3}, {\"id\": 200, \"dap_an\": [\"A. sự kiên nhẫn\", \"B. học sinh, sinh viên\", \"C. sự tự tin\", \"D. điều trị\"], \"tu_vung\": \"Patience\", \"dap_an_dung\": 0}, {\"id\": 198, \"dap_an\": [\"A. sự đến nơi\", \"B. sự ngạc nhiên\", \"C. Hạnh phúc\", \"D. trận đấu\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 1}, {\"id\": 192, \"dap_an\": [\"A. sự tự tin\", \"B. hạnh phúc\", \"C. tuyến đường\", \"D. thuật toán\"], \"tu_vung\": \"Happiness\", \"dap_an_dung\": 1}, {\"id\": 195, \"dap_an\": [\"A. nỗi sợ\", \"B. thuật toán\", \"C. Công nghệ\", \"D. lịch trình\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 0}, {\"id\": 201, \"dap_an\": [\"A. nhà nghiên cứu\", \"B. sư tử\", \"C. Hạnh phúc\", \"D. sân bay\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 2}, {\"id\": 193, \"dap_an\": [\"A. bệnh\", \"B. sư tử\", \"C. nỗi buồn\", \"D. hành trình\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 2}, {\"id\": 197, \"dap_an\": [\"A. trận đấu\", \"B. hành trình\", \"C. sự tự tin\", \"D. sự phấn khích\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 2}], \"userAnswers\": [0, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3], \"currentIndex\": 10, \"remainingTimes\": [8, 8, 12, 11, 15, 15, 14, 15, 15, 15, 7], \"durationSeconds\": 44}', 'in_progress', '2026-09-13 04:57:43', '2026-09-13 05:23:04', NULL),
(35, 3, 'flashcard', 'topic', 20, '20', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\", \"201\": \"da_nho\"}, \"currentIndex\": 10, \"durationSeconds\": 91}', 'completed', '2026-09-13 04:58:01', '2026-09-13 05:00:34', '2026-09-13 05:00:34'),
(36, 3, 'flashcard', 'topic', 20, '20', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200, 201], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"chua_nho\", \"201\": \"da_nho\"}, \"currentIndex\": 10, \"durationSeconds\": 70}', 'in_progress', '2026-09-13 05:00:40', '2026-09-13 05:22:53', NULL),
(37, 3, 'flashcard', 'topic', 2, '10', '{\"cardIds\": [2, 30, 31, 32, 33, 34, 35, 36, 37, 38], \"cardStatuses\": {\"2\": \"chua_nho\", \"30\": \"chua_nho\", \"31\": \"chua_nho\", \"32\": \"chua_nho\", \"33\": \"chua_nho\", \"34\": \"chua_nho\", \"35\": \"chua_nho\", \"36\": \"chua_nho\", \"37\": \"chua_nho\", \"38\": \"chua_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 20}', 'in_progress', '2026-09-13 05:02:10', '2026-09-13 05:02:49', NULL),
(38, 3, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 2, \"durationSeconds\": 22}', 'completed', '2026-09-13 05:08:57', '2026-09-13 13:19:59', '2026-09-13 13:19:59'),
(39, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 20}', 'completed', '2026-09-13 05:09:20', '2026-09-13 05:12:24', '2026-09-13 05:12:24'),
(40, 3, 'flashcard', 'topic', 20, '10', '{\"cardIds\": [20, 192, 193, 194, 195, 196, 197, 198, 199, 200], \"cardStatuses\": {\"20\": \"da_nho\", \"192\": \"da_nho\", \"193\": \"da_nho\", \"194\": \"da_nho\", \"195\": \"da_nho\", \"196\": \"da_nho\", \"197\": \"da_nho\", \"198\": \"da_nho\", \"199\": \"da_nho\", \"200\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 36}', 'in_progress', '2026-09-13 05:12:26', '2026-09-13 05:22:38', NULL),
(41, 3, 'quiz', 'topic', 20, '10', '{\"questions\": [{\"id\": 192, \"dap_an\": [\"A. hạnh phúc\", \"B. kỳ thi\", \"C. cá heo\", \"D. con voi\"], \"tu_vung\": \"Happiness\", \"dap_an_dung\": 0}, {\"id\": 20, \"dap_an\": [\"A. quà lưu niệm\", \"B. nhà khoa học\", \"C. hạnh phúc\", \"D. sự đồng cảm\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 3}, {\"id\": 194, \"dap_an\": [\"A. nỗi sợ\", \"B. nền móng\", \"C. sự tức giận\", \"D. phát hiện\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 2}, {\"id\": 198, \"dap_an\": [\"A. khỏe mạnh\", \"B. sự ngạc nhiên\", \"C. sự phấn khích\", \"D. nhà khoa học\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 1}, {\"id\": 197, \"dap_an\": [\"A. sự tự tin\", \"B. khán giả\", \"C. thu nhập\", \"D. học thuật\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 0}, {\"id\": 195, \"dap_an\": [\"A. bảo tồn\", \"B. quà lưu niệm\", \"C. thiết bị\", \"D. nỗi sợ\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 3}, {\"id\": 201, \"dap_an\": [\"A. điểm đến\", \"B. sự kiên nhẫn\", \"C. Hạnh phúc\", \"D. thuật toán\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 2}, {\"id\": 199, \"dap_an\": [\"A. khán giả\", \"B. sự lo lắng\", \"C. máy chủ\", \"D. nguyên liệu\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 1}, {\"id\": 196, \"dap_an\": [\"A. bảo tồn\", \"B. quản lý\", \"C. sự phấn khích\", \"D. hạnh phúc\"], \"tu_vung\": \"Excitement\", \"dap_an_dung\": 2}, {\"id\": 193, \"dap_an\": [\"A. hoa văn\", \"B. phần mềm\", \"C. học thuật\", \"D. nỗi buồn\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 3}], \"userAnswers\": [0, 0, 0, 0, 1, 3, 1, 3, 2, 3], \"currentIndex\": 9, \"remainingTimes\": [4, 2, 11, 14, 14, 14, 15, 15, 14, -1], \"durationSeconds\": 69}', 'completed', '2026-09-13 05:14:08', '2026-09-13 05:35:59', '2026-09-13 05:35:59'),
(42, 3, 'quiz', 'topic', 20, '10', '{\"questions\": [{\"id\": 195, \"dap_an\": [\"A. nhà khoa học\", \"B. đồ uống\", \"C. nỗi sợ\", \"D. sự phấn khích\"], \"tu_vung\": \"Fear\", \"dap_an_dung\": 2}, {\"id\": 199, \"dap_an\": [\"A. cuộc thi đấu\", \"B. doanh thu\", \"C. sự lo lắng\", \"D. sân bay\"], \"tu_vung\": \"Anxiety\", \"dap_an_dung\": 2}, {\"id\": 201, \"dap_an\": [\"A. xây dựng\", \"B. Hạnh phúc\", \"C. đồ uống\", \"D. lợi nhuận\"], \"tu_vung\": \"Happy\", \"dap_an_dung\": 1}, {\"id\": 198, \"dap_an\": [\"A. sự hồi phục\", \"B. sự ngạc nhiên\", \"C. cuộc thi đấu\", \"D. giải đấu\"], \"tu_vung\": \"Surprise\", \"dap_an_dung\": 1}, {\"id\": 20, \"dap_an\": [\"A. Hạnh phúc\", \"B. sự kiên nhẫn\", \"C. sự ngạc nhiên\", \"D. sự đồng cảm\"], \"tu_vung\": \"Empathy\", \"dap_an_dung\": 3}, {\"id\": 200, \"dap_an\": [\"A. dự án\", \"B. sự kiên nhẫn\", \"C. sự ngạc nhiên\", \"D. sự tự tin\"], \"tu_vung\": \"Patience\", \"dap_an_dung\": 1}, {\"id\": 197, \"dap_an\": [\"A. doanh thu\", \"B. giải đấu\", \"C. hạnh phúc\", \"D. sự tự tin\"], \"tu_vung\": \"Confidence\", \"dap_an_dung\": 3}, {\"id\": 196, \"dap_an\": [\"A. trận đấu\", \"B. bệnh nhân\", \"C. sự phấn khích\", \"D. bài thuyết trình\"], \"tu_vung\": \"Excitement\", \"dap_an_dung\": 2}, {\"id\": 194, \"dap_an\": [\"A. vận động viên, người chơi\", \"B. Công nghệ\", \"C. sự ngạc nhiên\", \"D. sự tức giận\"], \"tu_vung\": \"Anger\", \"dap_an_dung\": 3}, {\"id\": 193, \"dap_an\": [\"A. nỗi buồn\", \"B. sự tức giận\", \"C. xây dựng\", \"D. nhà thiết kế\"], \"tu_vung\": \"Sadness\", \"dap_an_dung\": 0}], \"userAnswers\": [2, 2], \"currentIndex\": 1, \"remainingTimes\": [6, 13, 15, 15, 15, 15, 15, 15, 15, 15], \"durationSeconds\": 14}', 'in_progress', '2026-09-13 05:36:51', '2026-09-13 05:37:18', NULL),
(43, 3, 'flashcard', 'set', 1, '10', '{\"cardIds\": [210, 211], \"cardStatuses\": {\"210\": \"da_nho\", \"211\": \"da_nho\"}, \"currentIndex\": 1, \"durationSeconds\": 11}', 'completed', '2026-09-13 05:39:54', '2026-09-13 05:41:03', '2026-09-13 05:41:03'),
(44, 3, 'quiz', 'set', 1, '10', '{\"questions\": [{\"id\": 210, \"dap_an\": [\"A. môi trường sống\", \"B. Công nghệ\", \"C. máy chủ\", \"D. chi phí\"], \"tu_vung\": \"Technology\", \"dap_an_dung\": 1}, {\"id\": 211, \"dap_an\": [\"A. máy tính\", \"B. chi phí\", \"C. văn phòng\", \"D. điều trị\"], \"tu_vung\": \"Computer\", \"dap_an_dung\": 0}], \"userAnswers\": [1], \"currentIndex\": 0, \"remainingTimes\": [6, 15], \"durationSeconds\": 12}', 'completed', '2026-09-13 05:41:31', '2026-09-13 05:41:46', '2026-09-13 05:41:46'),
(45, 4, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 9}', 'completed', '2026-09-19 22:40:44', '2026-09-19 22:40:53', '2026-09-19 22:40:53'),
(46, 4, 'flashcard', 'topic', 1, '10', '{\"cardIds\": [1, 21, 22, 23, 24, 25, 26, 27, 28, 29], \"cardStatuses\": {\"1\": \"da_nho\", \"21\": \"da_nho\", \"22\": \"da_nho\", \"23\": \"da_nho\", \"24\": \"da_nho\", \"25\": \"da_nho\", \"26\": \"da_nho\", \"27\": \"da_nho\", \"28\": \"da_nho\", \"29\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 14}', 'in_progress', '2026-09-19 22:40:56', '2026-09-19 22:40:58', NULL),
(47, 4, 'flashcard', 'topic', 2, '10', '{\"cardIds\": [2, 30, 31, 32, 33, 34, 35, 36, 37, 38], \"cardStatuses\": {\"2\": \"da_nho\", \"30\": \"da_nho\", \"31\": \"da_nho\", \"32\": \"da_nho\", \"33\": \"da_nho\", \"34\": \"da_nho\", \"35\": \"da_nho\", \"36\": \"da_nho\", \"37\": \"da_nho\", \"38\": \"da_nho\"}, \"currentIndex\": 6, \"durationSeconds\": 50}', 'completed', '2026-09-19 22:49:15', '2026-09-19 22:50:05', '2026-09-19 22:50:05'),
(48, 4, 'flashcard', 'topic', 2, '10', '{\"cardIds\": [2, 30, 31, 32, 33, 34, 35, 36, 37, 38], \"cardStatuses\": {\"2\": \"da_nho\", \"30\": \"da_nho\", \"31\": \"da_nho\", \"32\": \"da_nho\", \"33\": \"da_nho\", \"34\": \"da_nho\", \"35\": \"da_nho\", \"36\": \"da_nho\", \"37\": \"da_nho\", \"38\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 71}', 'in_progress', '2026-09-19 22:50:12', '2026-09-19 22:50:26', NULL),
(49, 4, 'flashcard', 'topic', 3, '10', '{\"cardIds\": [3, 39, 40, 41, 42, 43, 44, 45, 46, 47], \"cardStatuses\": {\"3\": \"da_nho\", \"39\": \"da_nho\", \"40\": \"da_nho\", \"41\": \"da_nho\", \"42\": \"da_nho\", \"43\": \"da_nho\", \"44\": \"da_nho\", \"45\": \"da_nho\", \"46\": \"da_nho\", \"47\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 3}', 'completed', '2026-09-20 01:15:01', '2026-09-20 01:15:04', '2026-09-20 01:15:04'),
(50, 4, 'flashcard', 'topic', 5, '10', '{\"cardIds\": [5, 57, 58, 59, 60, 61, 62, 63, 64, 65], \"cardStatuses\": {\"5\": \"da_nho\", \"57\": \"da_nho\", \"58\": \"da_nho\", \"59\": \"da_nho\", \"60\": \"da_nho\", \"61\": \"da_nho\", \"62\": \"da_nho\", \"63\": \"da_nho\", \"64\": \"da_nho\", \"65\": \"da_nho\"}, \"currentIndex\": 9, \"durationSeconds\": 3}', 'completed', '2026-09-20 01:17:50', '2026-09-20 01:17:53', '2026-09-20 01:17:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `learning_sessions`
--

CREATE TABLE `learning_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `topic_id` int DEFAULT NULL,
  `vocabulary_set_id` int DEFAULT NULL,
  `session_type` enum('new_learning','review') NOT NULL DEFAULT 'new_learning',
  `session_date` date NOT NULL,
  `words_studied` int DEFAULT '0',
  `duration_seconds` int DEFAULT '0',
  `streak_count` int DEFAULT '0',
  `started_at` datetime DEFAULT NULL,
  `finished_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `learning_sessions`
--

INSERT INTO `learning_sessions` (`id`, `user_id`, `topic_id`, `vocabulary_set_id`, `session_type`, `session_date`, `words_studied`, `duration_seconds`, `streak_count`, `started_at`, `finished_at`) VALUES
(2, 3, NULL, 1, 'new_learning', '2026-09-12', 2, 5, 1, '2026-09-12 18:04:06', '2026-09-12 18:04:11'),
(3, 3, NULL, 1, 'new_learning', '2026-09-12', 2, 14, 1, '2026-09-12 18:06:45', '2026-09-12 18:06:59'),
(4, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 36, 2, '2026-09-13 02:04:33', '2026-09-13 02:05:09'),
(5, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 20, 2, '2026-09-13 02:36:05', '2026-09-13 02:36:25'),
(6, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 23, 2, '2026-09-13 02:36:18', '2026-09-13 02:36:41'),
(7, 3, 1, NULL, 'new_learning', '2026-09-13', 1, 6, 2, '2026-09-13 03:38:24', '2026-09-13 03:38:30'),
(8, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 10, 2, '2026-09-13 03:51:12', '2026-09-13 03:51:22'),
(9, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 35, 2, '2026-09-13 03:52:13', '2026-09-13 03:52:48'),
(10, 3, NULL, 1, 'new_learning', '2026-09-13', 1, 14, 2, '2026-09-13 03:55:32', '2026-09-13 03:55:46'),
(11, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 9, 2, '2026-09-13 03:56:16', '2026-09-13 03:56:25'),
(12, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 23, 2, '2026-09-13 03:56:54', '2026-09-13 03:57:17'),
(13, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 18, 2, '2026-09-13 04:01:33', '2026-09-13 04:01:51'),
(14, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 9, 2, '2026-09-13 04:04:01', '2026-09-13 04:04:10'),
(15, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 6, 2, '2026-09-13 04:16:03', '2026-09-13 04:16:09'),
(16, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 34, 2, '2026-09-13 04:17:34', '2026-09-13 04:18:08'),
(17, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 4, 2, '2026-09-13 04:18:12', '2026-09-13 04:18:16'),
(18, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 3, 2, '2026-09-13 04:21:06', '2026-09-13 04:21:09'),
(19, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 114, 2, '2026-09-13 04:23:27', '2026-09-13 04:25:21'),
(20, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 121, 2, '2026-09-13 04:23:33', '2026-09-13 04:25:34'),
(21, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 5, 2, '2026-09-13 04:28:46', '2026-09-13 04:28:51'),
(22, 3, 20, NULL, 'new_learning', '2026-09-13', 11, 3, 2, '2026-09-13 04:29:02', '2026-09-13 04:29:05'),
(23, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 2, 2, '2026-09-13 04:30:42', '2026-09-13 04:30:44'),
(24, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 3, 2, '2026-09-13 04:30:50', '2026-09-13 04:30:53'),
(25, 3, 20, NULL, 'new_learning', '2026-09-13', 11, 3, 2, '2026-09-13 04:31:01', '2026-09-13 04:31:04'),
(26, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 127, 2, '2026-09-13 04:36:40', '2026-09-13 04:38:47'),
(27, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 3, 2, '2026-09-13 04:39:59', '2026-09-13 04:40:02'),
(28, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 8, 2, '2026-09-13 04:40:47', '2026-09-13 04:40:55'),
(29, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 4, 2, '2026-09-13 04:44:09', '2026-09-13 04:44:13'),
(30, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 7, 2, '2026-09-13 04:54:38', '2026-09-13 04:54:45'),
(31, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 9, 2, '2026-09-13 04:56:26', '2026-09-13 04:56:35'),
(32, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 134, 2, '2026-09-13 04:54:47', '2026-09-13 04:57:01'),
(33, 3, 20, NULL, 'new_learning', '2026-09-13', 11, 92, 2, '2026-09-13 04:59:02', '2026-09-13 05:00:34'),
(34, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 7, 2, '2026-09-13 05:09:03', '2026-09-13 05:09:10'),
(35, 3, 20, NULL, 'new_learning', '2026-09-13', 10, 20, 2, '2026-09-13 05:12:04', '2026-09-13 05:12:24'),
(36, 3, NULL, 1, 'new_learning', '2026-09-13', 2, 11, 2, '2026-09-13 05:40:52', '2026-09-13 05:41:03'),
(37, 3, 1, NULL, 'new_learning', '2026-09-13', 10, 22, 2, '2026-09-13 13:19:37', '2026-09-13 13:19:59'),
(38, 4, 1, NULL, 'new_learning', '2026-09-19', 10, 9, 1, '2026-09-19 22:40:44', '2026-09-19 22:40:53'),
(39, 4, 2, NULL, 'new_learning', '2026-09-19', 10, 51, 1, '2026-09-19 22:49:14', '2026-09-19 22:50:05'),
(40, 4, 3, NULL, 'new_learning', '2026-09-20', 28, 3, 2, '2026-09-20 01:15:01', '2026-09-20 01:15:04'),
(41, 4, 5, NULL, 'new_learning', '2026-09-20', 10, 3, 2, '2026-09-20 01:17:50', '2026-09-20 01:17:53'),
(42, 5, NULL, NULL, 'new_learning', '2026-09-20', 10, 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monthly_rewards`
--

CREATE TABLE `monthly_rewards` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `year_month` char(7) NOT NULL,
  `rank_position` tinyint NOT NULL,
  `points` int NOT NULL,
  `reward_name` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `otp_code`, `expires_at`, `created_at`) VALUES
(1, 'quan@gmail.com', '123456', '2026-08-28 16:55:44', '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_answer_details`
--

CREATE TABLE `quiz_answer_details` (
  `id` int NOT NULL,
  `quiz_result_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `question_order` int NOT NULL,
  `selected_answer` text,
  `correct_answer` text NOT NULL,
  `is_correct` tinyint(1) NOT NULL DEFAULT '0',
  `response_time_ms` int DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `quiz_answer_details`
--

INSERT INTO `quiz_answer_details` (`id`, `quiz_result_id`, `vocabulary_id`, `question_order`, `selected_answer`, `correct_answer`, `is_correct`, `response_time_ms`, `created_at`) VALUES
(1, 2, 211, 1, 'nhà nghiên cứu', 'máy tính', 0, NULL, '2026-09-12 18:04:16'),
(2, 2, 210, 2, 'sự ngạc nhiên', 'Công nghệ', 0, NULL, '2026-09-12 18:04:16'),
(3, 3, 210, 1, 'Công nghệ', 'Công nghệ', 1, NULL, '2026-09-12 18:06:12'),
(4, 3, 211, 2, 'máy tính', 'máy tính', 1, NULL, '2026-09-12 18:06:12'),
(5, 4, 211, 1, 'nhà nghiên cứu', 'máy tính', 0, NULL, '2026-09-12 18:10:17'),
(6, 4, 210, 2, 'Công nghệ', 'Công nghệ', 1, NULL, '2026-09-12 18:10:17'),
(7, 5, 27, 1, '', 'đại bàng', 0, NULL, '2026-09-12 18:15:30'),
(8, 5, 21, 2, '', 'sư tử', 0, NULL, '2026-09-12 18:15:30'),
(9, 5, 26, 3, '', 'con thỏ', 0, NULL, '2026-09-12 18:15:30'),
(10, 5, 1, 4, '', 'con voi', 0, NULL, '2026-09-12 18:15:30'),
(11, 5, 29, 5, '', 'chim cánh cụt', 0, NULL, '2026-09-12 18:15:30'),
(12, 5, 23, 6, '', 'hươu cao cổ', 0, NULL, '2026-09-12 18:15:30'),
(13, 5, 25, 7, '', 'cá heo', 0, NULL, '2026-09-12 18:15:30'),
(14, 5, 24, 8, '', 'khỉ', 0, NULL, '2026-09-12 18:15:30'),
(15, 5, 22, 9, '', 'hổ', 0, NULL, '2026-09-12 18:15:30'),
(16, 5, 28, 10, '', 'cá voi', 0, NULL, '2026-09-12 18:15:30'),
(17, 6, 24, 1, 'tái chế', 'khỉ', 0, NULL, '2026-09-12 18:15:45'),
(18, 6, 22, 2, 'sự đến nơi', 'hổ', 0, NULL, '2026-09-12 18:15:45'),
(19, 6, 21, 3, 'sư tử', 'sư tử', 1, NULL, '2026-09-12 18:15:45'),
(20, 6, 28, 4, 'cá voi', 'cá voi', 1, NULL, '2026-09-12 18:15:45'),
(21, 6, 1, 5, 'con voi', 'con voi', 1, NULL, '2026-09-12 18:15:45'),
(22, 6, 23, 6, 'hươu cao cổ', 'hươu cao cổ', 1, NULL, '2026-09-12 18:15:45'),
(23, 6, 27, 7, 'đại bàng', 'đại bàng', 1, NULL, '2026-09-12 18:15:45'),
(24, 6, 25, 8, 'kiến thức', 'cá heo', 0, NULL, '2026-09-12 18:15:45'),
(25, 6, 29, 9, 'thiết kế', 'chim cánh cụt', 0, NULL, '2026-09-12 18:15:45'),
(26, 6, 26, 10, 'khách du lịch', 'con thỏ', 0, NULL, '2026-09-12 18:15:45'),
(27, 7, 21, 1, 'sư tử', 'sư tử', 1, NULL, '2026-09-13 02:05:40'),
(28, 7, 1, 2, 'con voi', 'con voi', 1, NULL, '2026-09-13 02:05:40'),
(29, 7, 28, 3, 'cá voi', 'cá voi', 1, NULL, '2026-09-13 02:05:40'),
(30, 7, 29, 4, 'chim cánh cụt', 'chim cánh cụt', 1, NULL, '2026-09-13 02:05:40'),
(31, 7, 23, 5, 'hươu cao cổ', 'hươu cao cổ', 1, NULL, '2026-09-13 02:05:40'),
(32, 7, 27, 6, 'đại bàng', 'đại bàng', 1, NULL, '2026-09-13 02:05:40'),
(33, 7, 24, 7, 'khỉ', 'khỉ', 1, NULL, '2026-09-13 02:05:40'),
(34, 7, 26, 8, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 02:05:40'),
(35, 7, 22, 9, 'hổ', 'hổ', 1, NULL, '2026-09-13 02:05:40'),
(36, 7, 25, 10, 'cá heo', 'cá heo', 1, NULL, '2026-09-13 02:05:40'),
(37, 8, 29, 1, '', 'chim cánh cụt', 0, NULL, '2026-09-13 03:38:44'),
(38, 8, 26, 2, '', 'con thỏ', 0, NULL, '2026-09-13 03:38:44'),
(39, 8, 23, 3, '', 'hươu cao cổ', 0, NULL, '2026-09-13 03:38:44'),
(40, 8, 22, 4, '', 'hổ', 0, NULL, '2026-09-13 03:38:44'),
(41, 8, 24, 5, '', 'khỉ', 0, NULL, '2026-09-13 03:38:44'),
(42, 8, 1, 6, '', 'con voi', 0, NULL, '2026-09-13 03:38:44'),
(43, 8, 25, 7, '', 'cá heo', 0, NULL, '2026-09-13 03:38:44'),
(44, 8, 28, 8, '', 'cá voi', 0, NULL, '2026-09-13 03:38:44'),
(45, 8, 27, 9, '', 'đại bàng', 0, NULL, '2026-09-13 03:38:44'),
(46, 8, 21, 10, '', 'sư tử', 0, NULL, '2026-09-13 03:38:44'),
(47, 9, 210, 1, '', 'Công nghệ', 0, NULL, '2026-09-13 03:51:25'),
(48, 9, 211, 2, '', 'máy tính', 0, NULL, '2026-09-13 03:51:25'),
(49, 10, 28, 1, 'giải đấu', 'cá voi', 0, NULL, '2026-09-13 03:53:04'),
(50, 10, 22, 2, 'cá voi', 'hổ', 0, NULL, '2026-09-13 03:53:04'),
(51, 10, 23, 3, 'khỉ', 'hươu cao cổ', 0, NULL, '2026-09-13 03:53:04'),
(52, 10, 29, 4, 'bài thuyết trình', 'chim cánh cụt', 0, NULL, '2026-09-13 03:53:04'),
(53, 10, 26, 5, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 03:53:04'),
(54, 10, 21, 6, 'giáo viên', 'sư tử', 0, NULL, '2026-09-13 03:53:04'),
(55, 10, 24, 7, 'khỉ', 'khỉ', 1, NULL, '2026-09-13 03:53:04'),
(56, 10, 25, 8, 'triệu chứng', 'cá heo', 0, NULL, '2026-09-13 03:53:04'),
(57, 10, 27, 9, 'trận đấu', 'đại bàng', 0, NULL, '2026-09-13 03:53:04'),
(58, 10, 1, 10, 'hợp đồng', 'con voi', 0, NULL, '2026-09-13 03:53:04'),
(59, 11, 29, 1, 'ẩm thực', 'chim cánh cụt', 0, NULL, '2026-09-13 03:57:23'),
(60, 11, 22, 2, 'ngon', 'hổ', 0, NULL, '2026-09-13 03:57:23'),
(61, 11, 25, 3, 'thiết bị', 'cá heo', 0, NULL, '2026-09-13 03:57:23'),
(62, 11, 24, 4, 'khỉ', 'khỉ', 1, NULL, '2026-09-13 03:57:23'),
(63, 11, 27, 5, 'chiến thắng', 'đại bàng', 0, NULL, '2026-09-13 03:57:23'),
(64, 11, 23, 6, 'chiến thắng', 'hươu cao cổ', 0, NULL, '2026-09-13 03:57:23'),
(65, 11, 21, 7, 'giai điệu', 'sư tử', 0, NULL, '2026-09-13 03:57:23'),
(66, 11, 26, 8, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 03:57:23'),
(67, 11, 1, 9, 'con voi', 'con voi', 1, NULL, '2026-09-13 03:57:23'),
(68, 11, 28, 10, 'cá voi', 'cá voi', 1, NULL, '2026-09-13 03:57:23'),
(69, 12, 27, 1, 'xu hướng', 'đại bàng', 0, NULL, '2026-09-13 04:16:54'),
(70, 12, 29, 2, 'đội', 'chim cánh cụt', 0, NULL, '2026-09-13 04:16:54'),
(71, 12, 1, 3, 'cấu trúc, công trình', 'con voi', 0, NULL, '2026-09-13 04:16:54'),
(72, 12, 28, 4, 'con voi', 'cá voi', 0, NULL, '2026-09-13 04:16:54'),
(73, 12, 24, 5, 'mã hóa', 'khỉ', 0, NULL, '2026-09-13 04:16:54'),
(74, 12, 21, 6, 'sư tử', 'sư tử', 1, NULL, '2026-09-13 04:16:54'),
(75, 12, 22, 7, 'hổ', 'hổ', 1, NULL, '2026-09-13 04:16:54'),
(76, 12, 25, 8, 'bệnh nhân', 'cá heo', 0, NULL, '2026-09-13 04:16:54'),
(77, 12, 26, 9, 'khoản vay', 'con thỏ', 0, NULL, '2026-09-13 04:16:54'),
(78, 12, 23, 10, 'lượng mưa', 'hươu cao cổ', 0, NULL, '2026-09-13 04:16:54'),
(79, 13, 29, 1, '', 'chim cánh cụt', 0, NULL, '2026-09-13 04:18:54'),
(80, 13, 21, 2, '', 'sư tử', 0, NULL, '2026-09-13 04:18:54'),
(81, 13, 28, 3, '', 'cá voi', 0, NULL, '2026-09-13 04:18:54'),
(82, 13, 26, 4, '', 'con thỏ', 0, NULL, '2026-09-13 04:18:54'),
(83, 13, 22, 5, '', 'hổ', 0, NULL, '2026-09-13 04:18:54'),
(84, 13, 27, 6, '', 'đại bàng', 0, NULL, '2026-09-13 04:18:54'),
(85, 13, 25, 7, '', 'cá heo', 0, NULL, '2026-09-13 04:18:54'),
(86, 13, 1, 8, '', 'con voi', 0, NULL, '2026-09-13 04:18:54'),
(87, 13, 24, 9, '', 'khỉ', 0, NULL, '2026-09-13 04:18:54'),
(88, 13, 23, 10, '', 'hươu cao cổ', 0, NULL, '2026-09-13 04:18:54'),
(89, 14, 210, 1, 'Công nghệ', 'Công nghệ', 1, NULL, '2026-09-13 04:26:01'),
(90, 14, 211, 2, 'máy tính', 'máy tính', 1, NULL, '2026-09-13 04:26:01'),
(91, 15, 200, 1, 'sự kiên nhẫn', 'sự kiên nhẫn', 1, NULL, '2026-09-13 04:29:41'),
(92, 15, 195, 2, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 04:29:41'),
(93, 15, 201, 3, 'Hạnh phúc', 'Hạnh phúc', 1, NULL, '2026-09-13 04:29:41'),
(94, 15, 197, 4, 'sự tự tin', 'sự tự tin', 1, NULL, '2026-09-13 04:29:41'),
(95, 15, 193, 5, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 04:29:41'),
(96, 15, 20, 6, 'sự đồng cảm', 'sự đồng cảm', 1, NULL, '2026-09-13 04:29:41'),
(97, 15, 199, 7, 'sự lo lắng', 'sự lo lắng', 1, NULL, '2026-09-13 04:29:41'),
(98, 15, 194, 8, 'sự tức giận', 'sự tức giận', 1, NULL, '2026-09-13 04:29:41'),
(99, 15, 192, 9, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 04:29:41'),
(100, 15, 198, 10, 'sự ngạc nhiên', 'sự ngạc nhiên', 1, NULL, '2026-09-13 04:29:41'),
(101, 15, 196, 11, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 04:29:41'),
(102, 16, 192, 1, 'phòng thí nghiệm', 'hạnh phúc', 0, NULL, '2026-09-13 04:31:19'),
(103, 16, 198, 2, 'xu hướng', 'sự ngạc nhiên', 0, NULL, '2026-09-13 04:31:19'),
(104, 16, 199, 3, 'phân tích', 'sự lo lắng', 0, NULL, '2026-09-13 04:31:19'),
(105, 16, 20, 4, 'sự đồng cảm', 'sự đồng cảm', 1, NULL, '2026-09-13 04:31:19'),
(106, 16, 195, 5, 'nỗi buồn', 'nỗi sợ', 0, NULL, '2026-09-13 04:31:19'),
(107, 16, 201, 6, 'màn trình diễn', 'Hạnh phúc', 0, NULL, '2026-09-13 04:31:19'),
(108, 16, 200, 7, 'ẩm thực', 'sự kiên nhẫn', 0, NULL, '2026-09-13 04:31:19'),
(109, 16, 193, 8, 'ô nhiễm', 'nỗi buồn', 0, NULL, '2026-09-13 04:31:19'),
(110, 16, 197, 9, 'lịch trình', 'sự tự tin', 0, NULL, '2026-09-13 04:31:19'),
(111, 16, 194, 10, 'sự tức giận', 'sự tức giận', 1, NULL, '2026-09-13 04:31:19'),
(112, 16, 196, 11, '', 'sự phấn khích', 0, NULL, '2026-09-13 04:31:19'),
(113, 17, 197, 1, 'khách hàng', 'sự tự tin', 0, NULL, '2026-09-13 04:43:06'),
(114, 17, 193, 2, 'hệ sinh thái', 'nỗi buồn', 0, NULL, '2026-09-13 04:43:06'),
(115, 17, 198, 3, 'bệnh viện', 'sự ngạc nhiên', 0, NULL, '2026-09-13 04:43:06'),
(116, 17, 194, 4, 'vận động viên', 'sự tức giận', 0, NULL, '2026-09-13 04:43:06'),
(117, 17, 200, 5, 'sự kiên nhẫn', 'sự kiên nhẫn', 1, NULL, '2026-09-13 04:43:06'),
(118, 17, 199, 6, 'xe đạp', 'sự lo lắng', 0, NULL, '2026-09-13 04:43:06'),
(119, 17, 20, 7, 'sự tự tin', 'sự đồng cảm', 0, NULL, '2026-09-13 04:43:06'),
(120, 17, 201, 8, 'hành lý', 'Hạnh phúc', 0, NULL, '2026-09-13 04:43:06'),
(121, 17, 195, 9, 'xu hướng', 'nỗi sợ', 0, NULL, '2026-09-13 04:43:06'),
(122, 17, 192, 10, 'khách hàng', 'hạnh phúc', 0, NULL, '2026-09-13 04:43:06'),
(123, 18, 21, 1, '', 'sư tử', 0, NULL, '2026-09-13 04:44:46'),
(124, 18, 23, 2, 'tàu hỏa', 'hươu cao cổ', 0, NULL, '2026-09-13 04:44:46'),
(125, 18, 24, 3, 'cá voi', 'khỉ', 0, NULL, '2026-09-13 04:44:46'),
(126, 18, 27, 4, 'sự đến nơi', 'đại bàng', 0, NULL, '2026-09-13 04:44:46'),
(127, 18, 1, 5, 'con voi', 'con voi', 1, NULL, '2026-09-13 04:44:46'),
(128, 18, 26, 6, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 04:44:46'),
(129, 18, 22, 7, 'lời bài hát', 'hổ', 0, NULL, '2026-09-13 04:44:46'),
(130, 18, 29, 8, 'dự báo', 'chim cánh cụt', 0, NULL, '2026-09-13 04:44:46'),
(131, 18, 25, 9, 'sự hồi phục', 'cá heo', 0, NULL, '2026-09-13 04:44:46'),
(132, 18, 28, 10, '', 'cá voi', 0, NULL, '2026-09-13 04:44:46'),
(133, 19, 1, 1, 'con voi', 'con voi', 1, NULL, '2026-09-13 04:55:43'),
(134, 19, 21, 2, 'sư tử', 'sư tử', 1, NULL, '2026-09-13 04:55:43'),
(135, 19, 24, 3, 'khỉ', 'khỉ', 1, NULL, '2026-09-13 04:55:43'),
(136, 19, 25, 4, 'cá heo', 'cá heo', 1, NULL, '2026-09-13 04:55:43'),
(137, 19, 28, 5, 'cá voi', 'cá voi', 1, NULL, '2026-09-13 04:55:43'),
(138, 19, 22, 6, 'hổ', 'hổ', 1, NULL, '2026-09-13 04:55:43'),
(139, 19, 27, 7, 'đại bàng', 'đại bàng', 1, NULL, '2026-09-13 04:55:43'),
(140, 19, 26, 8, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 04:55:43'),
(141, 19, 29, 9, 'chim cánh cụt', 'chim cánh cụt', 1, NULL, '2026-09-13 04:55:43'),
(142, 19, 23, 10, 'hổ', 'hươu cao cổ', 0, NULL, '2026-09-13 04:55:43'),
(143, 20, 199, 1, 'tự nhiên', 'sự lo lắng', 0, NULL, '2026-09-13 05:13:51'),
(144, 20, 196, 2, 'cá voi', 'sự phấn khích', 0, NULL, '2026-09-13 05:13:51'),
(145, 20, 194, 3, 'sự tức giận', 'sự tức giận', 1, NULL, '2026-09-13 05:13:51'),
(146, 20, 20, 4, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:13:51'),
(147, 20, 200, 5, '', 'sự kiên nhẫn', 0, NULL, '2026-09-13 05:13:51'),
(148, 20, 198, 6, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:13:51'),
(149, 20, 192, 7, '', 'hạnh phúc', 0, NULL, '2026-09-13 05:13:51'),
(150, 20, 195, 8, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:13:51'),
(151, 20, 201, 9, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:13:51'),
(152, 20, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:13:51'),
(153, 20, 197, 11, '', 'sự tự tin', 0, NULL, '2026-09-13 05:13:51'),
(154, 21, 192, 1, '', 'hạnh phúc', 0, NULL, '2026-09-13 05:14:19'),
(155, 21, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:14:19'),
(156, 21, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:14:19'),
(157, 21, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:14:19'),
(158, 21, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:14:19'),
(159, 21, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:14:19'),
(160, 21, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:14:19'),
(161, 21, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:14:19'),
(162, 21, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:14:19'),
(163, 21, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:14:19'),
(164, 22, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:14:29'),
(165, 22, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:14:29'),
(166, 22, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:14:29'),
(167, 22, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:14:29'),
(168, 22, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:14:29'),
(169, 22, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:14:29'),
(170, 22, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:14:29'),
(171, 22, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:14:29'),
(172, 22, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:14:29'),
(173, 22, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:14:29'),
(174, 23, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:17:01'),
(175, 23, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:17:01'),
(176, 23, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:17:01'),
(177, 23, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:17:01'),
(178, 23, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:17:01'),
(179, 23, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:17:01'),
(180, 23, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:17:01'),
(181, 23, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:17:01'),
(182, 23, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:17:01'),
(183, 23, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:17:01'),
(184, 24, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:17:06'),
(185, 24, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:17:06'),
(186, 24, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:17:06'),
(187, 24, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:17:06'),
(188, 24, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:17:06'),
(189, 24, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:17:06'),
(190, 24, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:17:06'),
(191, 24, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:17:06'),
(192, 24, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:17:06'),
(193, 24, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:17:06'),
(194, 25, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:18:04'),
(195, 25, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:18:04'),
(196, 25, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:18:04'),
(197, 25, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:18:04'),
(198, 25, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:18:04'),
(199, 25, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:18:04'),
(200, 25, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:18:04'),
(201, 25, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:18:04'),
(202, 25, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:18:04'),
(203, 25, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:18:04'),
(204, 26, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:18:10'),
(205, 26, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:18:10'),
(206, 26, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:18:10'),
(207, 26, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:18:10'),
(208, 26, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:18:10'),
(209, 26, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:18:10'),
(210, 26, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:18:10'),
(211, 26, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:18:10'),
(212, 26, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:18:10'),
(213, 26, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:18:10'),
(214, 27, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:18:12'),
(215, 27, 20, 2, '', 'sự đồng cảm', 0, NULL, '2026-09-13 05:18:12'),
(216, 27, 194, 3, '', 'sự tức giận', 0, NULL, '2026-09-13 05:18:12'),
(217, 27, 198, 4, '', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:18:12'),
(218, 27, 197, 5, '', 'sự tự tin', 0, NULL, '2026-09-13 05:18:12'),
(219, 27, 195, 6, '', 'nỗi sợ', 0, NULL, '2026-09-13 05:18:12'),
(220, 27, 201, 7, '', 'Hạnh phúc', 0, NULL, '2026-09-13 05:18:12'),
(221, 27, 199, 8, '', 'sự lo lắng', 0, NULL, '2026-09-13 05:18:12'),
(222, 27, 196, 9, '', 'sự phấn khích', 0, NULL, '2026-09-13 05:18:12'),
(223, 27, 193, 10, '', 'nỗi buồn', 0, NULL, '2026-09-13 05:18:12'),
(224, 28, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:19:47'),
(225, 28, 20, 2, 'quà lưu niệm', 'sự đồng cảm', 0, NULL, '2026-09-13 05:19:47'),
(226, 28, 194, 3, 'nỗi sợ', 'sự tức giận', 0, NULL, '2026-09-13 05:19:47'),
(227, 28, 198, 4, 'khỏe mạnh', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:19:47'),
(228, 28, 197, 5, 'khán giả', 'sự tự tin', 0, NULL, '2026-09-13 05:19:47'),
(229, 28, 195, 6, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 05:19:47'),
(230, 28, 201, 7, 'sự kiên nhẫn', 'Hạnh phúc', 0, NULL, '2026-09-13 05:19:47'),
(231, 28, 199, 8, 'nguyên liệu', 'sự lo lắng', 0, NULL, '2026-09-13 05:19:47'),
(232, 28, 196, 9, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 05:19:47'),
(233, 28, 193, 10, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 05:19:47'),
(234, 29, 199, 1, 'tự nhiên', 'sự lo lắng', 0, NULL, '2026-09-13 05:21:12'),
(235, 29, 196, 2, 'cá voi', 'sự phấn khích', 0, NULL, '2026-09-13 05:21:12'),
(236, 29, 194, 3, 'sự tức giận', 'sự tức giận', 1, NULL, '2026-09-13 05:21:12'),
(237, 29, 20, 4, 'sự đồng cảm', 'sự đồng cảm', 1, NULL, '2026-09-13 05:21:12'),
(238, 29, 200, 5, 'điều trị', 'sự kiên nhẫn', 0, NULL, '2026-09-13 05:21:12'),
(239, 29, 198, 6, 'trận đấu', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:21:12'),
(240, 29, 192, 7, 'thuật toán', 'hạnh phúc', 0, NULL, '2026-09-13 05:21:12'),
(241, 29, 195, 8, 'lịch trình', 'nỗi sợ', 0, NULL, '2026-09-13 05:21:12'),
(242, 29, 201, 9, 'sân bay', 'Hạnh phúc', 0, NULL, '2026-09-13 05:21:12'),
(243, 29, 193, 10, 'hành trình', 'nỗi buồn', 0, NULL, '2026-09-13 05:21:12'),
(244, 29, 197, 11, 'sự phấn khích', 'sự tự tin', 0, NULL, '2026-09-13 05:21:12'),
(245, 30, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:21:24'),
(246, 30, 20, 2, 'quà lưu niệm', 'sự đồng cảm', 0, NULL, '2026-09-13 05:21:24'),
(247, 30, 194, 3, 'nỗi sợ', 'sự tức giận', 0, NULL, '2026-09-13 05:21:24'),
(248, 30, 198, 4, 'khỏe mạnh', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:21:24'),
(249, 30, 197, 5, 'khán giả', 'sự tự tin', 0, NULL, '2026-09-13 05:21:24'),
(250, 30, 195, 6, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 05:21:24'),
(251, 30, 201, 7, 'sự kiên nhẫn', 'Hạnh phúc', 0, NULL, '2026-09-13 05:21:24'),
(252, 30, 199, 8, 'nguyên liệu', 'sự lo lắng', 0, NULL, '2026-09-13 05:21:24'),
(253, 30, 196, 9, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 05:21:24'),
(254, 30, 193, 10, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 05:21:24'),
(255, 31, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:21:28'),
(256, 31, 20, 2, 'quà lưu niệm', 'sự đồng cảm', 0, NULL, '2026-09-13 05:21:28'),
(257, 31, 194, 3, 'nỗi sợ', 'sự tức giận', 0, NULL, '2026-09-13 05:21:28'),
(258, 31, 198, 4, 'khỏe mạnh', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:21:28'),
(259, 31, 197, 5, 'khán giả', 'sự tự tin', 0, NULL, '2026-09-13 05:21:28'),
(260, 31, 195, 6, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 05:21:28'),
(261, 31, 201, 7, 'sự kiên nhẫn', 'Hạnh phúc', 0, NULL, '2026-09-13 05:21:28'),
(262, 31, 199, 8, 'nguyên liệu', 'sự lo lắng', 0, NULL, '2026-09-13 05:21:28'),
(263, 31, 196, 9, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 05:21:28'),
(264, 31, 193, 10, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 05:21:28'),
(265, 32, 199, 1, 'tự nhiên', 'sự lo lắng', 0, NULL, '2026-09-13 05:23:04'),
(266, 32, 196, 2, 'cá voi', 'sự phấn khích', 0, NULL, '2026-09-13 05:23:04'),
(267, 32, 194, 3, 'sự tức giận', 'sự tức giận', 1, NULL, '2026-09-13 05:23:04'),
(268, 32, 20, 4, 'sự đồng cảm', 'sự đồng cảm', 1, NULL, '2026-09-13 05:23:04'),
(269, 32, 200, 5, 'điều trị', 'sự kiên nhẫn', 0, NULL, '2026-09-13 05:23:04'),
(270, 32, 198, 6, 'trận đấu', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:23:04'),
(271, 32, 192, 7, 'thuật toán', 'hạnh phúc', 0, NULL, '2026-09-13 05:23:04'),
(272, 32, 195, 8, 'lịch trình', 'nỗi sợ', 0, NULL, '2026-09-13 05:23:04'),
(273, 32, 201, 9, 'sân bay', 'Hạnh phúc', 0, NULL, '2026-09-13 05:23:04'),
(274, 32, 193, 10, 'hành trình', 'nỗi buồn', 0, NULL, '2026-09-13 05:23:04'),
(275, 32, 197, 11, 'sự phấn khích', 'sự tự tin', 0, NULL, '2026-09-13 05:23:04'),
(276, 33, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:23:17'),
(277, 33, 20, 2, 'quà lưu niệm', 'sự đồng cảm', 0, NULL, '2026-09-13 05:23:17'),
(278, 33, 194, 3, 'nỗi sợ', 'sự tức giận', 0, NULL, '2026-09-13 05:23:17'),
(279, 33, 198, 4, 'khỏe mạnh', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:23:17'),
(280, 33, 197, 5, 'khán giả', 'sự tự tin', 0, NULL, '2026-09-13 05:23:17'),
(281, 33, 195, 6, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 05:23:17'),
(282, 33, 201, 7, 'sự kiên nhẫn', 'Hạnh phúc', 0, NULL, '2026-09-13 05:23:17'),
(283, 33, 199, 8, 'nguyên liệu', 'sự lo lắng', 0, NULL, '2026-09-13 05:23:17'),
(284, 33, 196, 9, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 05:23:17'),
(285, 33, 193, 10, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 05:23:17'),
(286, 34, 1, 1, 'con voi', 'con voi', 1, NULL, '2026-09-13 05:35:50'),
(287, 34, 21, 2, 'sư tử', 'sư tử', 1, NULL, '2026-09-13 05:35:50'),
(288, 34, 24, 3, 'khỉ', 'khỉ', 1, NULL, '2026-09-13 05:35:50'),
(289, 34, 25, 4, 'cá heo', 'cá heo', 1, NULL, '2026-09-13 05:35:50'),
(290, 34, 28, 5, 'cá voi', 'cá voi', 1, NULL, '2026-09-13 05:35:50'),
(291, 34, 22, 6, 'hổ', 'hổ', 1, NULL, '2026-09-13 05:35:50'),
(292, 34, 27, 7, 'đại bàng', 'đại bàng', 1, NULL, '2026-09-13 05:35:50'),
(293, 34, 26, 8, 'con thỏ', 'con thỏ', 1, NULL, '2026-09-13 05:35:50'),
(294, 34, 29, 9, 'chim cánh cụt', 'chim cánh cụt', 1, NULL, '2026-09-13 05:35:50'),
(295, 34, 23, 10, 'hổ', 'hươu cao cổ', 0, NULL, '2026-09-13 05:35:50'),
(296, 35, 192, 1, 'hạnh phúc', 'hạnh phúc', 1, NULL, '2026-09-13 05:35:59'),
(297, 35, 20, 2, 'quà lưu niệm', 'sự đồng cảm', 0, NULL, '2026-09-13 05:35:59'),
(298, 35, 194, 3, 'nỗi sợ', 'sự tức giận', 0, NULL, '2026-09-13 05:35:59'),
(299, 35, 198, 4, 'khỏe mạnh', 'sự ngạc nhiên', 0, NULL, '2026-09-13 05:35:59'),
(300, 35, 197, 5, 'khán giả', 'sự tự tin', 0, NULL, '2026-09-13 05:35:59'),
(301, 35, 195, 6, 'nỗi sợ', 'nỗi sợ', 1, NULL, '2026-09-13 05:35:59'),
(302, 35, 201, 7, 'sự kiên nhẫn', 'Hạnh phúc', 0, NULL, '2026-09-13 05:35:59'),
(303, 35, 199, 8, 'nguyên liệu', 'sự lo lắng', 0, NULL, '2026-09-13 05:35:59'),
(304, 35, 196, 9, 'sự phấn khích', 'sự phấn khích', 1, NULL, '2026-09-13 05:35:59'),
(305, 35, 193, 10, 'nỗi buồn', 'nỗi buồn', 1, NULL, '2026-09-13 05:35:59'),
(306, 36, 210, 1, 'bản thiết kế', 'Công nghệ', 0, NULL, '2026-09-13 05:39:25'),
(307, 36, 211, 2, 'bác sĩ', 'máy tính', 0, NULL, '2026-09-13 05:39:25'),
(308, 37, 211, 1, '', 'máy tính', 0, NULL, '2026-09-13 05:41:09'),
(309, 37, 210, 2, '', 'Công nghệ', 0, NULL, '2026-09-13 05:41:09'),
(310, 38, 210, 1, 'Công nghệ', 'Công nghệ', 1, NULL, '2026-09-13 05:41:46'),
(311, 38, 211, 2, 'chi phí', 'máy tính', 0, NULL, '2026-09-13 05:41:46'),
(312, 39, 27, 1, 'đại bàng', 'đại bàng', 1, NULL, '2026-09-19 22:41:29'),
(313, 39, 29, 2, 'nhạc cụ', 'chim cánh cụt', 0, NULL, '2026-09-19 22:41:29'),
(314, 39, 23, 3, 'tỉ số', 'hươu cao cổ', 0, NULL, '2026-09-19 22:41:29'),
(315, 39, 1, 4, 'con voi', 'con voi', 1, NULL, '2026-09-19 22:41:29'),
(316, 39, 28, 5, 'cá voi', 'cá voi', 1, NULL, '2026-09-19 22:41:29'),
(317, 39, 25, 6, 'cá heo', 'cá heo', 1, NULL, '2026-09-19 22:41:29'),
(318, 39, 24, 7, 'thuật toán', 'khỉ', 0, NULL, '2026-09-19 22:41:29'),
(319, 39, 26, 8, 'khách du lịch', 'con thỏ', 0, NULL, '2026-09-19 22:41:29'),
(320, 39, 21, 9, 'sư tử', 'sư tử', 1, NULL, '2026-09-19 22:41:29'),
(321, 39, 22, 10, 'cá heo', 'hổ', 0, NULL, '2026-09-19 22:41:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `topic_id` int DEFAULT NULL,
  `vocabulary_set_id` int DEFAULT NULL,
  `total_questions` int NOT NULL,
  `correct_answers` int NOT NULL,
  `score` float GENERATED ALWAYS AS (((`correct_answers` / `total_questions`) * 100)) STORED,
  `started_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `finished_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `topic_id`, `vocabulary_set_id`, `total_questions`, `correct_answers`, `started_at`, `finished_at`) VALUES
(2, 3, NULL, 1, 2, 0, '2026-09-12 18:04:11', '2026-09-12 18:04:16'),
(3, 3, NULL, 1, 2, 2, '2026-09-12 18:06:07', '2026-09-12 18:06:12'),
(4, 3, NULL, 1, 2, 1, '2026-09-12 18:10:11', '2026-09-12 18:10:17'),
(5, 3, 1, NULL, 10, 0, '2026-09-12 18:15:26', '2026-09-12 18:15:30'),
(6, 3, 1, NULL, 10, 5, '2026-09-12 18:15:32', '2026-09-12 18:15:45'),
(7, 3, 1, NULL, 10, 10, '2026-09-13 02:05:10', '2026-09-13 02:05:40'),
(8, 3, 1, NULL, 10, 0, '2026-09-13 03:38:39', '2026-09-13 03:38:44'),
(9, 3, NULL, 1, 2, 0, '2026-09-13 03:51:22', '2026-09-13 03:51:25'),
(10, 3, 1, NULL, 10, 2, '2026-09-13 03:52:48', '2026-09-13 03:53:04'),
(11, 3, 1, NULL, 10, 4, '2026-09-13 03:56:57', '2026-09-13 03:57:23'),
(12, 3, 1, NULL, 10, 2, '2026-09-13 04:16:40', '2026-09-13 04:16:54'),
(13, 3, 1, NULL, 10, 0, '2026-09-13 04:18:48', '2026-09-13 04:18:54'),
(14, 3, NULL, 1, 2, 2, '2026-09-13 04:25:40', '2026-09-13 04:26:01'),
(15, 3, 20, NULL, 11, 11, '2026-09-13 04:29:11', '2026-09-13 04:29:41'),
(16, 3, 20, NULL, 11, 2, '2026-09-13 04:31:12', '2026-09-13 04:31:19'),
(17, 3, 20, NULL, 10, 1, '2026-09-13 04:42:58', '2026-09-13 04:43:06'),
(18, 3, 1, NULL, 10, 2, '2026-09-13 04:44:30', '2026-09-13 04:44:46'),
(19, 3, 1, NULL, 10, 9, '2026-09-13 04:55:14', '2026-09-13 04:55:43'),
(20, 3, 20, NULL, 11, 1, '2026-09-13 05:13:21', '2026-09-13 05:13:51'),
(21, 3, 20, NULL, 10, 0, '2026-09-13 05:14:08', '2026-09-13 05:14:19'),
(22, 3, 20, NULL, 10, 1, '2026-09-13 05:14:11', '2026-09-13 05:14:29'),
(23, 3, 20, NULL, 10, 1, '2026-09-13 05:16:30', '2026-09-13 05:17:01'),
(24, 3, 20, NULL, 10, 1, '2026-09-13 05:16:34', '2026-09-13 05:17:06'),
(25, 3, 20, NULL, 10, 1, '2026-09-13 05:17:31', '2026-09-13 05:18:04'),
(26, 3, 20, NULL, 10, 1, '2026-09-13 05:17:36', '2026-09-13 05:18:10'),
(27, 3, 20, NULL, 10, 1, '2026-09-13 05:17:37', '2026-09-13 05:18:12'),
(28, 3, 20, NULL, 10, 4, '2026-09-13 05:18:41', '2026-09-13 05:19:47'),
(29, 3, 20, NULL, 11, 2, '2026-09-13 05:20:32', '2026-09-13 05:21:12'),
(30, 3, 20, NULL, 10, 4, '2026-09-13 05:20:17', '2026-09-13 05:21:24'),
(31, 3, 20, NULL, 10, 4, '2026-09-13 05:20:20', '2026-09-13 05:21:28'),
(32, 3, 20, NULL, 11, 2, '2026-09-13 05:22:20', '2026-09-13 05:23:04'),
(33, 3, 20, NULL, 10, 4, '2026-09-13 05:22:08', '2026-09-13 05:23:17'),
(34, 3, 1, NULL, 10, 9, '2026-09-13 05:35:18', '2026-09-13 05:35:50'),
(35, 3, 20, NULL, 10, 4, '2026-09-13 05:34:49', '2026-09-13 05:35:59'),
(36, 3, NULL, 1, 2, 0, '2026-09-13 05:39:22', '2026-09-13 05:39:25'),
(37, 3, NULL, 1, 2, 0, '2026-09-13 05:41:05', '2026-09-13 05:41:09'),
(38, 3, NULL, 1, 2, 1, '2026-09-13 05:41:31', '2026-09-13 05:41:46'),
(39, 4, 1, NULL, 10, 5, '2026-09-19 22:41:05', '2026-09-19 22:41:29');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review_logs`
--

CREATE TABLE `review_logs` (
  `id` int NOT NULL,
  `progress_id` int NOT NULL,
  `review_date` date NOT NULL,
  `quality_rating` tinyint NOT NULL,
  `response_time_ms` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `review_logs`
--

INSERT INTO `review_logs` (`id`, `progress_id`, `review_date`, `quality_rating`, `response_time_ms`) VALUES
(4, 48, '2026-09-12', 5, NULL),
(5, 60, '2026-09-12', 5, NULL),
(6, 48, '2026-09-12', 2, NULL),
(7, 60, '2026-09-12', 2, NULL),
(8, 65, '2026-09-13', 5, NULL),
(9, 66, '2026-09-13', 5, NULL),
(10, 67, '2026-09-13', 5, NULL),
(11, 68, '2026-09-13', 5, NULL),
(12, 69, '2026-09-13', 5, NULL),
(13, 70, '2026-09-13', 5, NULL),
(14, 71, '2026-09-13', 5, NULL),
(15, 72, '2026-09-13', 5, NULL),
(16, 73, '2026-09-13', 5, NULL),
(17, 74, '2026-09-13', 5, NULL),
(18, 65, '2026-09-13', 5, NULL),
(19, 66, '2026-09-13', 5, NULL),
(20, 67, '2026-09-13', 5, NULL),
(21, 68, '2026-09-13', 5, NULL),
(22, 69, '2026-09-13', 5, NULL),
(23, 70, '2026-09-13', 5, NULL),
(24, 71, '2026-09-13', 5, NULL),
(25, 72, '2026-09-13', 5, NULL),
(26, 73, '2026-09-13', 5, NULL),
(27, 74, '2026-09-13', 5, NULL),
(28, 65, '2026-09-13', 5, NULL),
(29, 66, '2026-09-13', 5, NULL),
(30, 67, '2026-09-13', 5, NULL),
(31, 68, '2026-09-13', 5, NULL),
(32, 69, '2026-09-13', 5, NULL),
(33, 70, '2026-09-13', 5, NULL),
(34, 71, '2026-09-13', 5, NULL),
(35, 72, '2026-09-13', 5, NULL),
(36, 73, '2026-09-13', 5, NULL),
(37, 74, '2026-09-13', 5, NULL),
(38, 65, '2026-09-13', 2, NULL),
(39, 74, '2026-09-13', 2, NULL),
(40, 71, '2026-09-13', 2, NULL),
(41, 68, '2026-09-13', 2, NULL),
(42, 67, '2026-09-13', 2, NULL),
(43, 69, '2026-09-13', 2, NULL),
(44, 65, '2026-09-13', 2, NULL),
(45, 70, '2026-09-13', 2, NULL),
(46, 73, '2026-09-13', 2, NULL),
(47, 72, '2026-09-13', 2, NULL),
(48, 66, '2026-09-13', 2, NULL),
(49, 48, '2026-09-13', 5, NULL),
(50, 60, '2026-09-13', 5, NULL),
(51, 48, '2026-09-13', 2, NULL),
(52, 60, '2026-09-13', 2, NULL),
(53, 65, '2026-09-13', 5, NULL),
(54, 66, '2026-09-13', 5, NULL),
(55, 67, '2026-09-13', 2, NULL),
(56, 68, '2026-09-13', 5, NULL),
(57, 69, '2026-09-13', 2, NULL),
(58, 70, '2026-09-13', 5, NULL),
(59, 71, '2026-09-13', 2, NULL),
(60, 72, '2026-09-13', 5, NULL),
(61, 73, '2026-09-13', 2, NULL),
(62, 74, '2026-09-13', 5, NULL),
(63, 73, '2026-09-13', 2, NULL),
(64, 67, '2026-09-13', 2, NULL),
(65, 68, '2026-09-13', 2, NULL),
(66, 74, '2026-09-13', 2, NULL),
(67, 71, '2026-09-13', 5, NULL),
(68, 66, '2026-09-13', 2, NULL),
(69, 69, '2026-09-13', 5, NULL),
(70, 70, '2026-09-13', 2, NULL),
(71, 72, '2026-09-13', 2, NULL),
(72, 65, '2026-09-13', 2, NULL),
(73, 48, '2026-09-13', 5, NULL),
(74, 65, '2026-09-13', 5, NULL),
(75, 66, '2026-09-13', 2, NULL),
(76, 67, '2026-09-13', 5, NULL),
(77, 68, '2026-09-13', 2, NULL),
(78, 69, '2026-09-13', 5, NULL),
(79, 70, '2026-09-13', 2, NULL),
(80, 71, '2026-09-13', 5, NULL),
(81, 72, '2026-09-13', 2, NULL),
(82, 73, '2026-09-13', 5, NULL),
(83, 74, '2026-09-13', 5, NULL),
(84, 65, '2026-09-13', 5, NULL),
(85, 66, '2026-09-13', 5, NULL),
(86, 67, '2026-09-13', 5, NULL),
(87, 68, '2026-09-13', 5, NULL),
(88, 69, '2026-09-13', 5, NULL),
(89, 70, '2026-09-13', 5, NULL),
(90, 71, '2026-09-13', 5, NULL),
(91, 72, '2026-09-13', 5, NULL),
(92, 73, '2026-09-13', 5, NULL),
(93, 74, '2026-09-13', 5, NULL),
(94, 74, '2026-09-13', 2, NULL),
(95, 67, '2026-09-13', 2, NULL),
(96, 70, '2026-09-13', 2, NULL),
(97, 69, '2026-09-13', 5, NULL),
(98, 72, '2026-09-13', 2, NULL),
(99, 68, '2026-09-13', 2, NULL),
(100, 66, '2026-09-13', 2, NULL),
(101, 71, '2026-09-13', 5, NULL),
(102, 65, '2026-09-13', 5, NULL),
(103, 73, '2026-09-13', 5, NULL),
(104, 65, '2026-09-13', 5, NULL),
(105, 66, '2026-09-13', 5, NULL),
(106, 67, '2026-09-13', 5, NULL),
(107, 68, '2026-09-13', 5, NULL),
(108, 69, '2026-09-13', 5, NULL),
(109, 70, '2026-09-13', 5, NULL),
(110, 71, '2026-09-13', 5, NULL),
(111, 72, '2026-09-13', 5, NULL),
(112, 73, '2026-09-13', 5, NULL),
(113, 74, '2026-09-13', 5, NULL),
(114, 65, '2026-09-13', 2, NULL),
(115, 66, '2026-09-13', 2, NULL),
(116, 67, '2026-09-13', 2, NULL),
(117, 68, '2026-09-13', 2, NULL),
(118, 69, '2026-09-13', 2, NULL),
(119, 70, '2026-09-13', 2, NULL),
(120, 71, '2026-09-13', 2, NULL),
(121, 72, '2026-09-13', 2, NULL),
(122, 73, '2026-09-13', 2, NULL),
(123, 74, '2026-09-13', 2, NULL),
(124, 65, '2026-09-13', 5, NULL),
(125, 66, '2026-09-13', 5, NULL),
(126, 67, '2026-09-13', 5, NULL),
(127, 68, '2026-09-13', 5, NULL),
(128, 69, '2026-09-13', 5, NULL),
(129, 70, '2026-09-13', 5, NULL),
(130, 71, '2026-09-13', 5, NULL),
(131, 72, '2026-09-13', 5, NULL),
(132, 73, '2026-09-13', 5, NULL),
(133, 74, '2026-09-13', 5, NULL),
(134, 72, '2026-09-13', 2, NULL),
(135, 74, '2026-09-13', 2, NULL),
(136, 65, '2026-09-13', 2, NULL),
(137, 73, '2026-09-13', 2, NULL),
(138, 69, '2026-09-13', 2, NULL),
(139, 66, '2026-09-13', 5, NULL),
(140, 67, '2026-09-13', 5, NULL),
(141, 70, '2026-09-13', 2, NULL),
(142, 71, '2026-09-13', 2, NULL),
(143, 68, '2026-09-13', 2, NULL),
(144, 65, '2026-09-13', 2, NULL),
(145, 66, '2026-09-13', 5, NULL),
(146, 67, '2026-09-13', 2, NULL),
(147, 68, '2026-09-13', 5, NULL),
(148, 69, '2026-09-13', 5, NULL),
(149, 70, '2026-09-13', 5, NULL),
(150, 71, '2026-09-13', 5, NULL),
(151, 72, '2026-09-13', 5, NULL),
(152, 73, '2026-09-13', 5, NULL),
(153, 74, '2026-09-13', 5, NULL),
(154, 65, '2026-09-13', 5, NULL),
(155, 66, '2026-09-13', 5, NULL),
(156, 67, '2026-09-13', 5, NULL),
(157, 68, '2026-09-13', 5, NULL),
(158, 69, '2026-09-13', 5, NULL),
(159, 70, '2026-09-13', 5, NULL),
(160, 71, '2026-09-13', 5, NULL),
(161, 72, '2026-09-13', 5, NULL),
(162, 73, '2026-09-13', 5, NULL),
(163, 74, '2026-09-13', 5, NULL),
(164, 74, '2026-09-13', 2, NULL),
(165, 66, '2026-09-13', 2, NULL),
(166, 73, '2026-09-13', 2, NULL),
(167, 71, '2026-09-13', 2, NULL),
(168, 67, '2026-09-13', 2, NULL),
(169, 72, '2026-09-13', 2, NULL),
(170, 70, '2026-09-13', 2, NULL),
(171, 65, '2026-09-13', 2, NULL),
(172, 69, '2026-09-13', 2, NULL),
(173, 68, '2026-09-13', 2, NULL),
(174, 65, '2026-09-13', 5, NULL),
(175, 66, '2026-09-13', 5, NULL),
(176, 67, '2026-09-13', 5, NULL),
(177, 68, '2026-09-13', 5, NULL),
(178, 69, '2026-09-13', 5, NULL),
(179, 70, '2026-09-13', 5, NULL),
(180, 71, '2026-09-13', 5, NULL),
(181, 72, '2026-09-13', 5, NULL),
(182, 73, '2026-09-13', 5, NULL),
(183, 74, '2026-09-13', 5, NULL),
(184, 48, '2026-09-13', 2, NULL),
(185, 60, '2026-09-13', 5, NULL),
(186, 48, '2026-09-13', 2, NULL),
(187, 60, '2026-09-13', 5, NULL),
(188, 48, '2026-09-13', 5, NULL),
(189, 60, '2026-09-13', 5, NULL),
(190, 250, '2026-09-13', 5, NULL),
(191, 251, '2026-09-13', 5, NULL),
(192, 252, '2026-09-13', 5, NULL),
(193, 253, '2026-09-13', 5, NULL),
(194, 254, '2026-09-13', 5, NULL),
(195, 255, '2026-09-13', 5, NULL),
(196, 256, '2026-09-13', 5, NULL),
(197, 257, '2026-09-13', 5, NULL),
(198, 258, '2026-09-13', 5, NULL),
(199, 259, '2026-09-13', 5, NULL),
(200, 250, '2026-09-13', 5, NULL),
(201, 251, '2026-09-13', 5, NULL),
(202, 252, '2026-09-13', 5, NULL),
(203, 253, '2026-09-13', 5, NULL),
(204, 254, '2026-09-13', 5, NULL),
(205, 255, '2026-09-13', 5, NULL),
(206, 256, '2026-09-13', 5, NULL),
(207, 257, '2026-09-13', 5, NULL),
(208, 258, '2026-09-13', 5, NULL),
(209, 259, '2026-09-13', 5, NULL),
(210, 270, '2026-09-13', 5, NULL),
(211, 259, '2026-09-13', 5, NULL),
(212, 254, '2026-09-13', 5, NULL),
(213, 270, '2026-09-13', 5, NULL),
(214, 256, '2026-09-13', 5, NULL),
(215, 252, '2026-09-13', 5, NULL),
(216, 250, '2026-09-13', 5, NULL),
(217, 258, '2026-09-13', 5, NULL),
(218, 253, '2026-09-13', 5, NULL),
(219, 251, '2026-09-13', 5, NULL),
(220, 257, '2026-09-13', 5, NULL),
(221, 255, '2026-09-13', 5, NULL),
(222, 250, '2026-09-13', 2, NULL),
(223, 251, '2026-09-13', 2, NULL),
(224, 252, '2026-09-13', 2, NULL),
(225, 253, '2026-09-13', 2, NULL),
(226, 254, '2026-09-13', 2, NULL),
(227, 255, '2026-09-13', 2, NULL),
(228, 256, '2026-09-13', 2, NULL),
(229, 257, '2026-09-13', 2, NULL),
(230, 258, '2026-09-13', 2, NULL),
(231, 259, '2026-09-13', 2, NULL),
(232, 250, '2026-09-13', 2, NULL),
(233, 251, '2026-09-13', 2, NULL),
(234, 252, '2026-09-13', 2, NULL),
(235, 253, '2026-09-13', 2, NULL),
(236, 254, '2026-09-13', 2, NULL),
(237, 255, '2026-09-13', 2, NULL),
(238, 256, '2026-09-13', 2, NULL),
(239, 257, '2026-09-13', 2, NULL),
(240, 258, '2026-09-13', 2, NULL),
(241, 259, '2026-09-13', 2, NULL),
(242, 250, '2026-09-13', 2, NULL),
(243, 251, '2026-09-13', 2, NULL),
(244, 252, '2026-09-13', 2, NULL),
(245, 253, '2026-09-13', 2, NULL),
(246, 254, '2026-09-13', 2, NULL),
(247, 255, '2026-09-13', 2, NULL),
(248, 256, '2026-09-13', 2, NULL),
(249, 257, '2026-09-13', 2, NULL),
(250, 258, '2026-09-13', 2, NULL),
(251, 259, '2026-09-13', 2, NULL),
(252, 270, '2026-09-13', 2, NULL),
(253, 251, '2026-09-13', 2, NULL),
(254, 257, '2026-09-13', 2, NULL),
(255, 258, '2026-09-13', 2, NULL),
(256, 250, '2026-09-13', 5, NULL),
(257, 254, '2026-09-13', 2, NULL),
(258, 270, '2026-09-13', 2, NULL),
(259, 259, '2026-09-13', 2, NULL),
(260, 252, '2026-09-13', 2, NULL),
(261, 256, '2026-09-13', 2, NULL),
(262, 253, '2026-09-13', 5, NULL),
(263, 255, '2026-09-13', 2, NULL),
(264, 48, '2026-09-13', 5, NULL),
(265, 60, '2026-09-13', 2, NULL),
(266, 250, '2026-09-13', 2, NULL),
(267, 251, '2026-09-13', 2, NULL),
(268, 252, '2026-09-13', 2, NULL),
(269, 253, '2026-09-13', 2, NULL),
(270, 254, '2026-09-13', 2, NULL),
(271, 255, '2026-09-13', 2, NULL),
(272, 256, '2026-09-13', 2, NULL),
(273, 257, '2026-09-13', 2, NULL),
(274, 258, '2026-09-13', 2, NULL),
(275, 259, '2026-09-13', 2, NULL),
(276, 250, '2026-09-13', 2, NULL),
(277, 251, '2026-09-13', 2, NULL),
(278, 252, '2026-09-13', 5, NULL),
(279, 253, '2026-09-13', 2, NULL),
(280, 254, '2026-09-13', 5, NULL),
(281, 255, '2026-09-13', 2, NULL),
(282, 256, '2026-09-13', 5, NULL),
(283, 257, '2026-09-13', 2, NULL),
(284, 258, '2026-09-13', 5, NULL),
(285, 259, '2026-09-13', 2, NULL),
(286, 256, '2026-09-13', 2, NULL),
(287, 252, '2026-09-13', 2, NULL),
(288, 257, '2026-09-13', 2, NULL),
(289, 253, '2026-09-13', 2, NULL),
(290, 259, '2026-09-13', 5, NULL),
(291, 258, '2026-09-13', 2, NULL),
(292, 250, '2026-09-13', 2, NULL),
(293, 270, '2026-09-13', 2, NULL),
(294, 254, '2026-09-13', 2, NULL),
(295, 251, '2026-09-13', 2, NULL),
(296, 65, '2026-09-13', 5, NULL),
(297, 66, '2026-09-13', 5, NULL),
(298, 67, '2026-09-13', 5, NULL),
(299, 68, '2026-09-13', 5, NULL),
(300, 69, '2026-09-13', 5, NULL),
(301, 70, '2026-09-13', 5, NULL),
(302, 71, '2026-09-13', 5, NULL),
(303, 72, '2026-09-13', 5, NULL),
(304, 73, '2026-09-13', 5, NULL),
(305, 74, '2026-09-13', 5, NULL),
(306, 66, '2026-09-13', 2, NULL),
(307, 68, '2026-09-13', 2, NULL),
(308, 69, '2026-09-13', 2, NULL),
(309, 72, '2026-09-13', 2, NULL),
(310, 65, '2026-09-13', 5, NULL),
(311, 71, '2026-09-13', 5, NULL),
(312, 67, '2026-09-13', 2, NULL),
(313, 74, '2026-09-13', 2, NULL),
(314, 70, '2026-09-13', 2, NULL),
(315, 73, '2026-09-13', 2, NULL),
(316, 65, '2026-09-13', 5, NULL),
(317, 66, '2026-09-13', 5, NULL),
(318, 67, '2026-09-13', 5, NULL),
(319, 68, '2026-09-13', 5, NULL),
(320, 69, '2026-09-13', 5, NULL),
(321, 70, '2026-09-13', 5, NULL),
(322, 71, '2026-09-13', 5, NULL),
(323, 72, '2026-09-13', 5, NULL),
(324, 73, '2026-09-13', 5, NULL),
(325, 74, '2026-09-13', 5, NULL),
(326, 65, '2026-09-13', 5, NULL),
(327, 66, '2026-09-13', 5, NULL),
(328, 69, '2026-09-13', 5, NULL),
(329, 70, '2026-09-13', 5, NULL),
(330, 73, '2026-09-13', 5, NULL),
(331, 67, '2026-09-13', 5, NULL),
(332, 72, '2026-09-13', 5, NULL),
(333, 71, '2026-09-13', 5, NULL),
(334, 74, '2026-09-13', 5, NULL),
(335, 68, '2026-09-13', 2, NULL),
(336, 65, '2026-09-13', 5, NULL),
(337, 66, '2026-09-13', 5, NULL),
(338, 67, '2026-09-13', 5, NULL),
(339, 68, '2026-09-13', 5, NULL),
(340, 69, '2026-09-13', 5, NULL),
(341, 70, '2026-09-13', 5, NULL),
(342, 71, '2026-09-13', 5, NULL),
(343, 72, '2026-09-13', 5, NULL),
(344, 73, '2026-09-13', 5, NULL),
(345, 74, '2026-09-13', 5, NULL),
(346, 48, '2026-09-13', 5, NULL),
(347, 60, '2026-09-13', 5, NULL),
(348, 250, '2026-09-13', 5, NULL),
(349, 251, '2026-09-13', 5, NULL),
(350, 252, '2026-09-13', 5, NULL),
(351, 253, '2026-09-13', 5, NULL),
(352, 254, '2026-09-13', 5, NULL),
(353, 255, '2026-09-13', 5, NULL),
(354, 256, '2026-09-13', 5, NULL),
(355, 257, '2026-09-13', 5, NULL),
(356, 258, '2026-09-13', 5, NULL),
(357, 259, '2026-09-13', 5, NULL),
(358, 270, '2026-09-13', 5, NULL),
(359, 250, '2026-09-13', 5, NULL),
(360, 251, '2026-09-13', 5, NULL),
(361, 252, '2026-09-13', 5, NULL),
(362, 253, '2026-09-13', 5, NULL),
(363, 254, '2026-09-13', 5, NULL),
(364, 255, '2026-09-13', 5, NULL),
(365, 256, '2026-09-13', 5, NULL),
(366, 257, '2026-09-13', 5, NULL),
(367, 258, '2026-09-13', 5, NULL),
(368, 259, '2026-09-13', 5, NULL),
(369, 250, '2026-09-13', 5, NULL),
(370, 251, '2026-09-13', 5, NULL),
(371, 252, '2026-09-13', 5, NULL),
(372, 253, '2026-09-13', 5, NULL),
(373, 254, '2026-09-13', 5, NULL),
(374, 255, '2026-09-13', 5, NULL),
(375, 256, '2026-09-13', 5, NULL),
(376, 257, '2026-09-13', 5, NULL),
(377, 258, '2026-09-13', 5, NULL),
(378, 259, '2026-09-13', 5, NULL),
(379, 258, '2026-09-13', 2, NULL),
(380, 255, '2026-09-13', 2, NULL),
(381, 253, '2026-09-13', 5, NULL),
(382, 250, '2026-09-13', 2, NULL),
(383, 259, '2026-09-13', 2, NULL),
(384, 257, '2026-09-13', 2, NULL),
(385, 251, '2026-09-13', 2, NULL),
(386, 254, '2026-09-13', 2, NULL),
(387, 270, '2026-09-13', 2, NULL),
(388, 252, '2026-09-13', 2, NULL),
(389, 256, '2026-09-13', 2, NULL),
(390, 251, '2026-09-13', 2, NULL),
(391, 250, '2026-09-13', 2, NULL),
(392, 253, '2026-09-13', 2, NULL),
(393, 257, '2026-09-13', 2, NULL),
(394, 256, '2026-09-13', 2, NULL),
(395, 254, '2026-09-13', 2, NULL),
(396, 270, '2026-09-13', 2, NULL),
(397, 258, '2026-09-13', 2, NULL),
(398, 255, '2026-09-13', 2, NULL),
(399, 252, '2026-09-13', 2, NULL),
(400, 251, '2026-09-13', 5, NULL),
(401, 250, '2026-09-13', 2, NULL),
(402, 253, '2026-09-13', 2, NULL),
(403, 257, '2026-09-13', 2, NULL),
(404, 256, '2026-09-13', 2, NULL),
(405, 254, '2026-09-13', 2, NULL),
(406, 270, '2026-09-13', 2, NULL),
(407, 258, '2026-09-13', 2, NULL),
(408, 255, '2026-09-13', 2, NULL),
(409, 252, '2026-09-13', 2, NULL),
(410, 251, '2026-09-13', 5, NULL),
(411, 250, '2026-09-13', 2, NULL),
(412, 253, '2026-09-13', 2, NULL),
(413, 257, '2026-09-13', 2, NULL),
(414, 256, '2026-09-13', 2, NULL),
(415, 254, '2026-09-13', 2, NULL),
(416, 270, '2026-09-13', 2, NULL),
(417, 258, '2026-09-13', 2, NULL),
(418, 255, '2026-09-13', 2, NULL),
(419, 252, '2026-09-13', 2, NULL),
(420, 251, '2026-09-13', 5, NULL),
(421, 250, '2026-09-13', 2, NULL),
(422, 253, '2026-09-13', 2, NULL),
(423, 257, '2026-09-13', 2, NULL),
(424, 256, '2026-09-13', 2, NULL),
(425, 254, '2026-09-13', 2, NULL),
(426, 270, '2026-09-13', 2, NULL),
(427, 258, '2026-09-13', 2, NULL),
(428, 255, '2026-09-13', 2, NULL),
(429, 252, '2026-09-13', 2, NULL),
(430, 251, '2026-09-13', 5, NULL),
(431, 250, '2026-09-13', 2, NULL),
(432, 253, '2026-09-13', 2, NULL),
(433, 257, '2026-09-13', 2, NULL),
(434, 256, '2026-09-13', 2, NULL),
(435, 254, '2026-09-13', 2, NULL),
(436, 270, '2026-09-13', 2, NULL),
(437, 258, '2026-09-13', 2, NULL),
(438, 255, '2026-09-13', 2, NULL),
(439, 252, '2026-09-13', 2, NULL),
(440, 251, '2026-09-13', 5, NULL),
(441, 250, '2026-09-13', 2, NULL),
(442, 253, '2026-09-13', 2, NULL),
(443, 257, '2026-09-13', 2, NULL),
(444, 256, '2026-09-13', 2, NULL),
(445, 254, '2026-09-13', 2, NULL),
(446, 270, '2026-09-13', 2, NULL),
(447, 258, '2026-09-13', 2, NULL),
(448, 255, '2026-09-13', 2, NULL),
(449, 252, '2026-09-13', 2, NULL),
(450, 251, '2026-09-13', 5, NULL),
(451, 250, '2026-09-13', 2, NULL),
(452, 253, '2026-09-13', 2, NULL),
(453, 257, '2026-09-13', 2, NULL),
(454, 256, '2026-09-13', 2, NULL),
(455, 254, '2026-09-13', 2, NULL),
(456, 270, '2026-09-13', 2, NULL),
(457, 258, '2026-09-13', 2, NULL),
(458, 255, '2026-09-13', 2, NULL),
(459, 252, '2026-09-13', 2, NULL),
(460, 251, '2026-09-13', 5, NULL),
(461, 250, '2026-09-13', 2, NULL),
(462, 253, '2026-09-13', 2, NULL),
(463, 257, '2026-09-13', 2, NULL),
(464, 256, '2026-09-13', 2, NULL),
(465, 254, '2026-09-13', 5, NULL),
(466, 270, '2026-09-13', 2, NULL),
(467, 258, '2026-09-13', 2, NULL),
(468, 255, '2026-09-13', 5, NULL),
(469, 252, '2026-09-13', 5, NULL),
(470, 258, '2026-09-13', 2, NULL),
(471, 255, '2026-09-13', 2, NULL),
(472, 253, '2026-09-13', 5, NULL),
(473, 250, '2026-09-13', 5, NULL),
(474, 259, '2026-09-13', 2, NULL),
(475, 257, '2026-09-13', 2, NULL),
(476, 251, '2026-09-13', 2, NULL),
(477, 254, '2026-09-13', 2, NULL),
(478, 270, '2026-09-13', 2, NULL),
(479, 252, '2026-09-13', 2, NULL),
(480, 256, '2026-09-13', 2, NULL),
(481, 251, '2026-09-13', 5, NULL),
(482, 250, '2026-09-13', 2, NULL),
(483, 253, '2026-09-13', 2, NULL),
(484, 257, '2026-09-13', 2, NULL),
(485, 256, '2026-09-13', 2, NULL),
(486, 254, '2026-09-13', 5, NULL),
(487, 270, '2026-09-13', 2, NULL),
(488, 258, '2026-09-13', 2, NULL),
(489, 255, '2026-09-13', 5, NULL),
(490, 252, '2026-09-13', 5, NULL),
(491, 251, '2026-09-13', 5, NULL),
(492, 250, '2026-09-13', 2, NULL),
(493, 253, '2026-09-13', 2, NULL),
(494, 257, '2026-09-13', 2, NULL),
(495, 256, '2026-09-13', 2, NULL),
(496, 254, '2026-09-13', 5, NULL),
(497, 270, '2026-09-13', 2, NULL),
(498, 258, '2026-09-13', 2, NULL),
(499, 255, '2026-09-13', 5, NULL),
(500, 252, '2026-09-13', 5, NULL),
(501, 258, '2026-09-13', 2, NULL),
(502, 255, '2026-09-13', 2, NULL),
(503, 253, '2026-09-13', 5, NULL),
(504, 250, '2026-09-13', 5, NULL),
(505, 259, '2026-09-13', 2, NULL),
(506, 257, '2026-09-13', 2, NULL),
(507, 251, '2026-09-13', 2, NULL),
(508, 254, '2026-09-13', 2, NULL),
(509, 270, '2026-09-13', 2, NULL),
(510, 252, '2026-09-13', 2, NULL),
(511, 256, '2026-09-13', 2, NULL),
(512, 251, '2026-09-13', 5, NULL),
(513, 250, '2026-09-13', 2, NULL),
(514, 253, '2026-09-13', 2, NULL),
(515, 257, '2026-09-13', 2, NULL),
(516, 256, '2026-09-13', 2, NULL),
(517, 254, '2026-09-13', 5, NULL),
(518, 270, '2026-09-13', 2, NULL),
(519, 258, '2026-09-13', 2, NULL),
(520, 255, '2026-09-13', 5, NULL),
(521, 252, '2026-09-13', 5, NULL),
(522, 48, '2026-09-13', 5, NULL),
(523, 60, '2026-09-13', 5, NULL),
(524, 65, '2026-09-13', 5, NULL),
(525, 66, '2026-09-13', 5, NULL),
(526, 67, '2026-09-13', 5, NULL),
(527, 68, '2026-09-13', 5, NULL),
(528, 69, '2026-09-13', 5, NULL),
(529, 70, '2026-09-13', 5, NULL),
(530, 71, '2026-09-13', 5, NULL),
(531, 72, '2026-09-13', 5, NULL),
(532, 73, '2026-09-13', 5, NULL),
(533, 74, '2026-09-13', 5, NULL),
(534, 639, '2026-09-19', 5, NULL),
(535, 640, '2026-09-19', 5, NULL),
(536, 641, '2026-09-19', 5, NULL),
(537, 642, '2026-09-19', 5, NULL),
(538, 643, '2026-09-19', 5, NULL),
(539, 644, '2026-09-19', 5, NULL),
(540, 645, '2026-09-19', 5, NULL),
(541, 646, '2026-09-19', 5, NULL),
(542, 647, '2026-09-19', 5, NULL),
(543, 648, '2026-09-19', 5, NULL),
(544, 649, '2026-09-19', 5, NULL),
(545, 650, '2026-09-19', 5, NULL),
(546, 651, '2026-09-19', 5, NULL),
(547, 652, '2026-09-19', 5, NULL),
(548, 653, '2026-09-19', 5, NULL),
(549, 654, '2026-09-19', 5, NULL),
(550, 655, '2026-09-19', 5, NULL),
(551, 656, '2026-09-19', 5, NULL),
(552, 657, '2026-09-19', 5, NULL),
(553, 658, '2026-09-19', 5, NULL),
(554, 659, '2026-09-20', 5, NULL),
(555, 660, '2026-09-20', 5, NULL),
(556, 661, '2026-09-20', 5, NULL),
(557, 662, '2026-09-20', 5, NULL),
(558, 663, '2026-09-20', 5, NULL),
(559, 664, '2026-09-20', 5, NULL),
(560, 665, '2026-09-20', 5, NULL),
(561, 666, '2026-09-20', 5, NULL),
(562, 667, '2026-09-20', 5, NULL),
(563, 668, '2026-09-20', 5, NULL),
(564, 669, '2026-09-20', 5, NULL),
(565, 670, '2026-09-20', 5, NULL),
(566, 671, '2026-09-20', 5, NULL),
(567, 672, '2026-09-20', 5, NULL),
(568, 673, '2026-09-20', 5, NULL),
(569, 674, '2026-09-20', 5, NULL),
(570, 675, '2026-09-20', 5, NULL),
(571, 676, '2026-09-20', 5, NULL),
(572, 677, '2026-09-20', 5, NULL),
(573, 678, '2026-09-20', 5, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `Topics`
--

CREATE TABLE `Topics` (
  `topicID` int NOT NULL,
  `topicName` varchar(100) NOT NULL,
  `topicDescription` text,
  `category` varchar(20) NOT NULL DEFAULT 'common',
  `created_by` int DEFAULT NULL,
  `topicCreated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `Topics`
--

INSERT INTO `Topics` (`topicID`, `topicName`, `topicDescription`, `category`, `created_by`, `topicCreated_at`) VALUES
(1, 'Animals', 'Từ vựng về các loài động vật', 'common', NULL, '2026-08-28 16:40:44'),
(2, 'Technology', 'Từ vựng về công nghệ và kỹ thuật số', 'common', NULL, '2026-08-28 16:40:44'),
(3, 'Food & Drink', 'Từ vựng về ẩm thực và đồ uống', 'common', NULL, '2026-08-28 16:40:44'),
(4, 'Travel', 'Từ vựng liên quan đến du lịch và di chuyển', 'common', NULL, '2026-08-28 16:40:44'),
(5, 'Business', 'Từ vựng trong môi trường kinh doanh', 'common', NULL, '2026-08-28 16:40:44'),
(6, 'Health & Medical', 'Từ vựng về sức khỏe, y tế và chăm sóc cơ thể', 'common', NULL, '2026-08-28 16:40:44'),
(7, 'Education', 'Từ vựng về giáo dục và học thuật', 'common', NULL, '2026-08-28 16:40:44'),
(8, 'Environment', 'Từ vựng về môi trường và thiên nhiên', 'common', NULL, '2026-08-28 16:40:44'),
(9, 'Entertainment', 'Từ vựng về giải trí, nghệ thuật và sở thích', 'common', NULL, '2026-08-28 16:40:44'),
(10, 'Shopping', 'Từ vựng về mua sắm và giao dịch', 'common', NULL, '2026-08-28 16:40:44'),
(11, 'Sports', 'Từ vựng về các thể loại thể thao và vận động', 'common', NULL, '2026-08-28 16:40:44'),
(12, 'Music', 'Từ vựng về âm nhạc và dụng cụ âm nhạc', 'common', NULL, '2026-08-28 16:40:44'),
(13, 'Weather', 'Từ vựng về thời tiết và khí hậu', 'common', NULL, '2026-08-28 16:40:44'),
(14, 'Fashion', 'Từ vựng về thời trang và trang phục', 'common', NULL, '2026-08-28 16:40:44'),
(15, 'Workplace', 'Từ vựng về văn phòng và công việc hàng ngày', 'common', NULL, '2026-08-28 16:40:44'),
(16, 'Finance', 'Từ vựng về tài chính và ngân hàng', 'common', NULL, '2026-08-28 16:40:44'),
(17, 'Transportation', 'Từ vựng về phương tiện giao thông', 'common', NULL, '2026-08-28 16:40:44'),
(18, 'Science', 'Từ vựng về khoa học và nghiên cứu', 'common', NULL, '2026-08-28 16:40:44'),
(19, 'Architecture', 'Từ vựng về kiến trúc và xây dựng', 'common', NULL, '2026-08-28 16:40:44'),
(20, 'Emotions', 'Từ vựng mô tả cảm xúc và tâm lý', 'common', NULL, '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Cấu trúc đóng vai cho view `tu_vung`
-- (See below for the actual view)
--
CREATE TABLE `tu_vung` (
`audio_url` varchar(255)
,`created_at` datetime
,`created_by` int
,`example_sentence` text
,`id` int
,`meaning` text
,`part_of_speech` varchar(30)
,`pronunciation` varchar(100)
,`topic_id` int
,`word` varchar(100)
);

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
(3, 'hungkill146@gmail.com', '$2y$10$Z2/VG5nprFQcK/p6Gdaj0eYcCOx/q48FpHzysLHp04mFLgsJntsyC', 'Nguyễn Tuấn Hùng', NULL, 'user', 'active', '2026-09-06 00:49:49', '2026-09-20 02:03:34', 1, '20:00:00', 20),
(4, 'quana2406@gmail.com', '$2y$10$2LryIgqEinFeQUKhaPwCnOYNWjVdm3d7osIYrR5VDGw74Sl6vb4Ry', 'Lê Minh Quân', NULL, 'user', 'active', '2026-09-19 22:29:07', '2026-09-19 23:44:03', 1, '20:00:00', 20),
(5, 'Admin123@gmail.com', '$2y$10$DkMXgKEjAn8lwvW0uoRdz.St7bqdjSTe12/mQa7AHF..DrZbbpep2', 'Quản trị viên ', NULL, 'admin', 'active', '2026-09-19 23:02:15', '2026-09-19 16:02:46', 1, '20:00:00', 20);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_login_sessions`
--

CREATE TABLE `user_login_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_points`
--

CREATE TABLE `user_points` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `topic_id` int NOT NULL,
  `points` int NOT NULL DEFAULT '10',
  `earned_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_points`
--

INSERT INTO `user_points` (`id`, `user_id`, `topic_id`, `points`, `earned_at`) VALUES
(1, 4, 5, 10, '2026-09-20 01:17:53'),
(2, 4, 1, 10, '2026-09-20 23:25:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_vocab_progress`
--

CREATE TABLE `user_vocab_progress` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `level` tinyint NOT NULL DEFAULT '1' COMMENT 'Giai đoạn 1 đến 5',
  `status` enum('new','learning','mastered') DEFAULT 'new',
  `ease_factor` float DEFAULT '2.5',
  `interval_days` int DEFAULT '0',
  `repetitions` int DEFAULT '0',
  `next_review_date` date DEFAULT NULL,
  `last_reviewed_at` datetime DEFAULT NULL,
  `last_quality_rating` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_vocab_progress`
--

INSERT INTO `user_vocab_progress` (`id`, `user_id`, `vocabulary_id`, `level`, `status`, `ease_factor`, `interval_days`, `repetitions`, `next_review_date`, `last_reviewed_at`, `last_quality_rating`) VALUES
(48, 3, 210, 5, 'mastered', 2.5, 7, 11, '2026-09-20', '2026-09-13 05:41:03', 5),
(60, 3, 211, 5, 'mastered', 2.5, 7, 10, '2026-09-20', '2026-09-13 05:41:03', 5),
(65, 3, 1, 5, 'mastered', 2.5, 7, 24, '2026-09-20', '2026-09-13 13:19:59', 5),
(66, 3, 21, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(67, 3, 22, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(68, 3, 23, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(69, 3, 24, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(70, 3, 25, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(71, 3, 26, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(72, 3, 27, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(73, 3, 28, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(74, 3, 29, 5, 'mastered', 2.5, 7, 23, '2026-09-20', '2026-09-13 13:19:59', 5),
(250, 3, 20, 3, 'learning', 2.5, 1, 27, '2026-09-14', '2026-09-13 05:23:17', 2),
(251, 3, 192, 5, 'mastered', 2.5, 7, 27, '2026-09-20', '2026-09-13 05:23:17', 5),
(252, 3, 193, 5, 'mastered', 2.5, 7, 27, '2026-09-20', '2026-09-13 05:23:17', 5),
(253, 3, 194, 3, 'learning', 2.5, 1, 27, '2026-09-14', '2026-09-13 05:23:17', 2),
(254, 3, 195, 5, 'mastered', 2.5, 7, 27, '2026-09-20', '2026-09-13 05:23:17', 5),
(255, 3, 196, 5, 'mastered', 2.5, 7, 26, '2026-09-20', '2026-09-13 05:23:17', 5),
(256, 3, 197, 3, 'learning', 2.5, 1, 27, '2026-09-14', '2026-09-13 05:23:17', 2),
(257, 3, 198, 3, 'learning', 2.5, 1, 27, '2026-09-14', '2026-09-13 05:23:17', 2),
(258, 3, 199, 3, 'learning', 2.5, 1, 27, '2026-09-14', '2026-09-13 05:23:17', 2),
(259, 3, 200, 3, 'learning', 2.5, 1, 16, '2026-09-14', '2026-09-13 05:23:04', 2),
(270, 3, 201, 3, 'learning', 2.5, 1, 20, '2026-09-14', '2026-09-13 05:23:17', 2),
(639, 4, 1, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:57', 5),
(640, 4, 21, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:57', 5),
(641, 4, 22, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:57', 5),
(642, 4, 23, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:57', 5),
(643, 4, 24, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:58', 5),
(644, 4, 25, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:58', 5),
(645, 4, 26, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:58', 5),
(646, 4, 27, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:58', 5),
(647, 4, 28, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:59', 5),
(648, 4, 29, 5, 'mastered', 2.5, 7, 1, '2026-10-20', '2026-09-20 23:25:59', 5),
(649, 4, 2, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(650, 4, 30, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(651, 4, 31, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(652, 4, 32, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(653, 4, 33, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(654, 4, 34, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(655, 4, 35, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(656, 4, 36, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(657, 4, 37, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(658, 4, 38, 5, 'mastered', 2.5, 7, 1, '2026-09-26', '2026-09-19 22:50:05', 5),
(659, 4, 3, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(660, 4, 39, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(661, 4, 40, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(662, 4, 41, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(663, 4, 42, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(664, 4, 43, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(665, 4, 44, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(666, 4, 45, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(667, 4, 46, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(668, 4, 47, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:15:04', 5),
(669, 4, 5, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(670, 4, 57, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(671, 4, 58, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(672, 4, 59, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(673, 4, 60, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(674, 4, 61, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(675, 4, 62, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(676, 4, 63, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(677, 4, 64, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(678, 4, 65, 5, 'mastered', 2.5, 7, 1, '2026-09-27', '2026-09-20 01:17:53', 5),
(679, 5, 1, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:03', NULL),
(680, 5, 21, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:04', NULL),
(681, 5, 22, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:04', NULL),
(682, 5, 23, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:04', NULL),
(683, 5, 24, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:04', NULL),
(684, 5, 25, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:04', NULL),
(685, 5, 26, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:05', NULL),
(686, 5, 27, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:05', NULL),
(687, 5, 28, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:05', NULL),
(688, 5, 29, 2, 'learning', 2.5, 0, 0, '2026-09-23', '2026-09-20 23:10:06', NULL),
(689, 4, 4, 3, 'learning', 2.5, 0, 0, '2026-09-27', '2026-09-20 23:26:39', NULL),
(690, 4, 48, 4, 'learning', 2.5, 0, 0, '2026-10-04', '2026-09-20 23:26:45', NULL),
(691, 4, 49, 3, 'learning', 2.5, 0, 0, '2026-09-27', '2026-09-20 23:26:45', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary`
--

CREATE TABLE `vocabulary` (
  `id` int NOT NULL,
  `topic_id` int DEFAULT NULL,
  `word` varchar(100) NOT NULL,
  `pronunciation` varchar(100) DEFAULT NULL,
  `part_of_speech` varchar(30) DEFAULT NULL,
  `meaning` text NOT NULL,
  `example_sentence` text,
  `created_by` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `audio_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary`
--

INSERT INTO `vocabulary` (`id`, `topic_id`, `word`, `pronunciation`, `part_of_speech`, `meaning`, `example_sentence`, `created_by`, `created_at`, `audio_url`) VALUES
(1, 1, 'elephant', '/ˈɛlɪfənt/', 'noun', 'con voi', 'The elephant is the largest land animal.', NULL, '2026-08-28 16:40:44', NULL),
(2, 2, 'algorithm', '/ˈælɡərɪðəm/', 'noun', 'thuật toán', 'The search algorithm finds results quickly.', NULL, '2026-08-28 16:40:44', NULL),
(3, 3, 'cuisine', '/kwɪˈziːn/', 'noun', 'ẩm thực', 'Vietnamese cuisine is famous worldwide.', NULL, '2026-08-28 16:40:44', NULL),
(4, 4, 'itinerary', '/aɪˈtɪnərəri/', 'noun', 'lịch trình', 'We planned our itinerary in advance.', NULL, '2026-08-28 16:40:44', NULL),
(5, 5, 'revenue', '/ˈrɛvənjuː/', 'noun', 'doanh thu', 'Company revenue grew significantly this year.', NULL, '2026-08-28 16:40:44', NULL),
(6, 6, 'symptom', '/ˈsɪmptəm/', 'noun', 'triệu chứng', 'Fever is a common symptom of flu.', NULL, '2026-08-28 16:40:44', NULL),
(7, 7, 'curriculum', '/kəˈrɪkjələm/', 'noun', 'chương trình học', 'The school updated its computer science curriculum.', NULL, '2026-08-28 16:40:44', NULL),
(8, 8, 'ecosystem', '/ˈiːkəʊsɪstəm/', 'noun', 'hệ sinh thái', 'Forests play a crucial role in maintaining the ecosystem.', NULL, '2026-08-28 16:40:44', NULL),
(9, 9, 'exhibition', '/ˌɛksɪˈbɪʃn/', 'noun', 'cuộc triển lãm', 'They visited an modern art exhibition.', NULL, '2026-08-28 16:40:44', NULL),
(10, 10, 'discount', '/ˈdɪskaʊnt/', 'noun', 'giảm giá', 'The store offers a 20% discount today.', NULL, '2026-08-28 16:40:44', NULL),
(11, 11, 'tournament', '/ˈtʊənəmənt/', 'noun', 'giải đấu', 'He played well in the tennis tournament.', NULL, '2026-08-28 16:40:44', NULL),
(12, 12, 'melody', '/ˈmɛlədi/', 'noun', 'giai điệu', 'That song has a catchy melody.', NULL, '2026-08-28 16:40:44', NULL),
(13, 13, 'humidity', '/hjuːˈmɪdɪti/', 'noun', 'độ ẩm', 'The high humidity makes it feel warmer.', NULL, '2026-08-28 16:40:44', NULL),
(14, 14, 'accessory', '/əkˈsɛsəri/', 'noun', 'phụ kiện', 'A leather belt is a good accessory.', NULL, '2026-08-28 16:40:44', NULL),
(15, 15, 'colleague', '/ˈkɒliːɡ/', 'noun', 'đồng nghiệp', 'She works closely with her colleague.', NULL, '2026-08-28 16:40:44', NULL),
(16, 16, 'investment', '/ɪnˈvɛstmənt/', 'noun', 'khoản đầu tư', 'Real estate is a long-term investment.', NULL, '2026-08-28 16:40:44', NULL),
(17, 17, 'vehicle', '/ˈviːək(əl)/', 'noun', 'phương tiện', 'Electric vehicles are getting popular.', NULL, '2026-08-28 16:40:44', NULL),
(18, 18, 'hypothesis', '/haɪˈpɒθɪsɪs/', 'noun', 'giả thuyết', 'The experiment proved the hypothesis right.', NULL, '2026-08-28 16:40:44', NULL),
(19, 19, 'blueprint', '/ˈbluːprɪnt/', 'noun', 'bản thiết kế', 'The architect showed us the house blueprint.', NULL, '2026-08-28 16:40:44', NULL),
(20, 20, 'empathy', '/ˈɛmpəθi/', 'noun', 'sự đồng cảm', 'Great leaders show empathy towards others.', NULL, '2026-08-28 16:40:44', NULL),
(21, 1, 'lion', '/ˈlaɪən/', 'noun', 'sư tử', 'The lion is known as the king of the jungle.', NULL, '2026-08-28 16:40:44', NULL),
(22, 1, 'tiger', '/ˈtaɪɡər/', 'noun', 'hổ', 'The tiger lives mainly in forests and grasslands.', NULL, '2026-08-28 16:40:44', NULL),
(23, 1, 'giraffe', '/dʒəˈræf/', 'noun', 'hươu cao cổ', 'The giraffe has a very long neck.', NULL, '2026-08-28 16:40:44', NULL),
(24, 1, 'monkey', '/ˈmʌŋki/', 'noun', 'khỉ', 'The monkey climbed the tree quickly.', NULL, '2026-08-28 16:40:44', NULL),
(25, 1, 'dolphin', '/ˈdɒlfɪn/', 'noun', 'cá heo', 'The dolphin swam beside the boat.', NULL, '2026-08-28 16:40:44', NULL),
(26, 1, 'rabbit', '/ˈræbɪt/', 'noun', 'con thỏ', 'The rabbit is eating a carrot.', NULL, '2026-08-28 16:40:44', NULL),
(27, 1, 'eagle', '/ˈiːɡəl/', 'noun', 'đại bàng', 'The eagle flew high above the mountains.', NULL, '2026-08-28 16:40:44', NULL),
(28, 1, 'whale', '/weɪl/', 'noun', 'cá voi', 'The whale is one of the largest animals in the ocean.', NULL, '2026-08-28 16:40:44', NULL),
(29, 1, 'penguin', '/ˈpeŋɡwɪn/', 'noun', 'chim cánh cụt', 'The penguin cannot fly but it can swim very well.', NULL, '2026-08-28 16:40:44', NULL),
(30, 2, 'software', '/ˈsɒftweər/', 'noun', 'phần mềm', 'The company develops software for small businesses.', NULL, '2026-08-28 16:40:44', NULL),
(31, 2, 'hardware', '/ˈhɑːrdweər/', 'noun', 'phần cứng', 'The computer hardware needs to be upgraded.', NULL, '2026-08-28 16:40:44', NULL),
(32, 2, 'database', '/ˈdeɪtəbeɪs/', 'noun', 'cơ sở dữ liệu', 'The application stores user information in a database.', NULL, '2026-08-28 16:40:44', NULL),
(33, 2, 'network', '/ˈnetwɜːrk/', 'noun', 'mạng', 'The computers are connected to the same network.', NULL, '2026-08-28 16:40:44', NULL),
(34, 2, 'browser', '/ˈbraʊzər/', 'noun', 'trình duyệt', 'You can open the website in any modern browser.', NULL, '2026-08-28 16:40:44', NULL),
(35, 2, 'server', '/ˈsɜːrvər/', 'noun', 'máy chủ', 'The web server processes the request quickly.', NULL, '2026-08-28 16:40:44', NULL),
(36, 2, 'encryption', '/ɪnˈkrɪpʃən/', 'noun', 'mã hóa', 'Encryption helps protect sensitive information.', NULL, '2026-08-28 16:40:44', NULL),
(37, 2, 'interface', '/ˈɪntərfeɪs/', 'noun', 'giao diện', 'The application has a simple user interface.', NULL, '2026-08-28 16:40:44', NULL),
(38, 2, 'device', '/dɪˈvaɪs/', 'noun', 'thiết bị', 'This device can connect to the internet.', NULL, '2026-08-28 16:40:44', NULL),
(39, 3, 'ingredient', '/ɪnˈɡriːdiənt/', 'noun', 'nguyên liệu', 'Fresh ingredients make the dish taste better.', NULL, '2026-08-28 16:40:44', NULL),
(40, 3, 'recipe', '/ˈresəpi/', 'noun', 'công thức nấu ăn', 'My mother gave me a recipe for vegetable soup.', NULL, '2026-08-28 16:40:44', NULL),
(41, 3, 'flavor', '/ˈfleɪvər/', 'noun', 'hương vị', 'The sauce has a strong garlic flavor.', NULL, '2026-08-28 16:40:44', NULL),
(42, 3, 'spicy', '/ˈspaɪsi/', 'adjective', 'cay', 'This soup is too spicy for me.', NULL, '2026-08-28 16:40:44', NULL),
(43, 3, 'delicious', '/dɪˈlɪʃəs/', 'adjective', 'ngon', 'The food at this restaurant is delicious.', NULL, '2026-08-28 16:40:44', NULL),
(44, 3, 'beverage', '/ˈbevərɪdʒ/', 'noun', 'đồ uống', 'The restaurant offers a wide range of beverages.', NULL, '2026-08-28 16:40:44', NULL),
(45, 3, 'dessert', '/dɪˈzɜːrt/', 'noun', 'món tráng miệng', 'We ordered ice cream for dessert.', NULL, '2026-08-28 16:40:44', NULL),
(46, 3, 'portion', '/ˈpɔːrʃən/', 'noun', 'khẩu phần', 'The restaurant serves large portions.', NULL, '2026-08-28 16:40:44', NULL),
(47, 3, 'appetizer', '/ˈæpɪtaɪzər/', 'noun', 'món khai vị', 'We ordered an appetizer before the main course.', NULL, '2026-08-28 16:40:44', NULL),
(48, 4, 'destination', '/ˌdestɪˈneɪʃən/', 'noun', 'điểm đến', 'Paris is a popular tourist destination.', NULL, '2026-08-28 16:40:44', NULL),
(49, 4, 'journey', '/ˈdʒɜːrni/', 'noun', 'hành trình', 'The journey took more than five hours.', NULL, '2026-08-28 16:40:44', NULL),
(50, 4, 'passport', '/ˈpɑːspɔːrt/', 'noun', 'hộ chiếu', 'Make sure you have your passport before leaving.', NULL, '2026-08-28 16:40:44', NULL),
(51, 4, 'luggage', '/ˈlʌɡɪdʒ/', 'noun', 'hành lý', 'My luggage was too heavy to carry.', NULL, '2026-08-28 16:40:44', NULL),
(52, 4, 'departure', '/dɪˈpɑːrtʃər/', 'noun', 'sự khởi hành', 'The departure time is six in the morning.', NULL, '2026-08-28 16:40:44', NULL),
(53, 4, 'arrival', '/əˈraɪvəl/', 'noun', 'sự đến nơi', 'The train arrival time has changed.', NULL, '2026-08-28 16:40:44', NULL),
(54, 4, 'accommodation', '/əˌkɒməˈdeɪʃən/', 'noun', 'chỗ ở', 'We booked our accommodation two months in advance.', NULL, '2026-08-28 16:40:44', NULL),
(55, 4, 'tourist', '/ˈtʊərɪst/', 'noun', 'khách du lịch', 'The city attracts millions of tourists every year.', NULL, '2026-08-28 16:40:44', NULL),
(56, 4, 'souvenir', '/ˌsuːvəˈnɪər/', 'noun', 'quà lưu niệm', 'She bought a souvenir for her family.', NULL, '2026-08-28 16:40:44', NULL),
(57, 5, 'profit', '/ˈprɒfɪt/', 'noun', 'lợi nhuận', 'The company made a large profit this year.', NULL, '2026-08-28 16:40:44', NULL),
(58, 5, 'customer', '/ˈkʌstəmər/', 'noun', 'khách hàng', 'The company always listens to its customers.', NULL, '2026-08-28 16:40:44', NULL),
(59, 5, 'market', '/ˈmɑːrkɪt/', 'noun', 'thị trường', 'The company wants to enter the international market.', NULL, '2026-08-28 16:40:44', NULL),
(60, 5, 'strategy', '/ˈstrætədʒi/', 'noun', 'chiến lược', 'The manager developed a new marketing strategy.', NULL, '2026-08-28 16:40:44', NULL),
(61, 5, 'contract', '/ˈkɒntrækt/', 'noun', 'hợp đồng', 'They signed a contract with a new partner.', NULL, '2026-08-28 16:40:44', NULL),
(62, 5, 'manager', '/ˈmænɪdʒər/', 'noun', 'quản lý', 'The manager organized a meeting for the team.', NULL, '2026-08-28 16:40:44', NULL),
(63, 5, 'employee', '/ɪmˈplɔɪiː/', 'noun', 'nhân viên', 'Every employee must follow company policies.', NULL, '2026-08-28 16:40:44', NULL),
(64, 5, 'meeting', '/ˈmiːtɪŋ/', 'noun', 'cuộc họp', 'The meeting starts at nine o’clock.', NULL, '2026-08-28 16:40:44', NULL),
(65, 5, 'deadline', '/ˈdedlaɪn/', 'noun', 'hạn chót', 'We must finish the project before the deadline.', NULL, '2026-08-28 16:40:44', NULL),
(66, 6, 'patient', '/ˈpeɪʃənt/', 'noun', 'bệnh nhân', 'The doctor examined the patient carefully.', NULL, '2026-08-28 16:40:44', NULL),
(67, 6, 'treatment', '/ˈtriːtmənt/', 'noun', 'điều trị', 'The patient received treatment at the hospital.', NULL, '2026-08-28 16:40:44', NULL),
(68, 6, 'medicine', '/ˈmedɪsɪn/', 'noun', 'thuốc', 'The doctor prescribed some medicine.', NULL, '2026-08-28 16:40:44', NULL),
(69, 6, 'disease', '/dɪˈziːz/', 'noun', 'bệnh', 'Scientists are studying the disease.', NULL, '2026-08-28 16:40:44', NULL),
(70, 6, 'hospital', '/ˈhɒspɪtəl/', 'noun', 'bệnh viện', 'The hospital is near the city center.', NULL, '2026-08-28 16:40:44', NULL),
(71, 6, 'doctor', '/ˈdɒktər/', 'noun', 'bác sĩ', 'The doctor gave him some useful advice.', NULL, '2026-08-28 16:40:44', NULL),
(72, 6, 'healthy', '/ˈhelθi/', 'adjective', 'khỏe mạnh', 'Regular exercise helps people stay healthy.', NULL, '2026-08-28 16:40:44', NULL),
(73, 6, 'recovery', '/rɪˈkʌvəri/', 'noun', 'sự hồi phục', 'She made a quick recovery after the operation.', NULL, '2026-08-28 16:40:44', NULL),
(74, 6, 'exercise', '/ˈeksərsaɪz/', 'noun', 'tập thể dục', 'Daily exercise is good for your health.', NULL, '2026-08-28 16:40:44', NULL),
(75, 7, 'student', '/ˈstjuːdənt/', 'noun', 'học sinh, sinh viên', 'Every student has access to the online library.', NULL, '2026-08-28 16:40:44', NULL),
(76, 7, 'teacher', '/ˈtiːtʃər/', 'noun', 'giáo viên', 'The teacher explained the lesson clearly.', NULL, '2026-08-28 16:40:44', NULL),
(77, 7, 'assignment', '/əˈsaɪnmənt/', 'noun', 'bài tập', 'The students completed their assignment on time.', NULL, '2026-08-28 16:40:44', NULL),
(78, 7, 'lecture', '/ˈlektʃər/', 'noun', 'bài giảng', 'The professor gave a lecture about modern science.', NULL, '2026-08-28 16:40:44', NULL),
(79, 7, 'exam', '/ɪɡˈzæm/', 'noun', 'kỳ thi', 'The students are preparing for their final exam.', NULL, '2026-08-28 16:40:44', NULL),
(80, 7, 'knowledge', '/ˈnɒlɪdʒ/', 'noun', 'kiến thức', 'Reading helps students gain knowledge.', NULL, '2026-08-28 16:40:44', NULL),
(81, 7, 'academic', '/ˌækəˈdemɪk/', 'adjective', 'học thuật', 'She has a strong academic background.', NULL, '2026-08-28 16:40:44', NULL),
(82, 7, 'scholarship', '/ˈskɒlərʃɪp/', 'noun', 'học bổng', 'He received a scholarship to study abroad.', NULL, '2026-08-28 16:40:44', NULL),
(83, 7, 'research', '/rɪˈsɜːrtʃ/', 'noun', 'nghiên cứu', 'The students conducted research on climate change.', NULL, '2026-08-28 16:40:44', NULL),
(84, 8, 'pollution', '/pəˈluːʃən/', 'noun', 'ô nhiễm', 'Air pollution is a serious problem in large cities.', NULL, '2026-08-28 16:40:44', NULL),
(85, 8, 'climate', '/ˈklaɪmət/', 'noun', 'khí hậu', 'The climate is changing rapidly around the world.', NULL, '2026-08-28 16:40:44', NULL),
(86, 8, 'forest', '/ˈfɒrɪst/', 'noun', 'rừng', 'Many animals live in the forest.', NULL, '2026-08-28 16:40:44', NULL),
(87, 8, 'recycle', '/ˌriːˈsaɪkəl/', 'verb', 'tái chế', 'We should recycle plastic bottles whenever possible.', NULL, '2026-08-28 16:40:44', NULL),
(88, 8, 'waste', '/weɪst/', 'noun', 'rác thải', 'The city is trying to reduce household waste.', NULL, '2026-08-28 16:40:44', NULL),
(89, 8, 'natural', '/ˈnætʃərəl/', 'adjective', 'tự nhiên', 'The park protects many natural habitats.', NULL, '2026-08-28 16:40:44', NULL),
(90, 8, 'conservation', '/ˌkɒnsərˈveɪʃən/', 'noun', 'bảo tồn', 'Wildlife conservation is important for future generations.', NULL, '2026-08-28 16:40:44', NULL),
(91, 8, 'habitat', '/ˈhæbɪtæt/', 'noun', 'môi trường sống', 'The forest is an important habitat for many species.', NULL, '2026-08-28 16:40:44', NULL),
(92, 8, 'sustainable', '/səˈsteɪnəbəl/', 'adjective', 'bền vững', 'We need more sustainable sources of energy.', NULL, '2026-08-28 16:40:44', NULL),
(93, 9, 'movie', '/ˈmuːvi/', 'noun', 'bộ phim', 'We watched a movie at the cinema last night.', NULL, '2026-08-28 16:40:44', NULL),
(94, 9, 'actor', '/ˈæktər/', 'noun', 'diễn viên nam', 'The actor played the main character.', NULL, '2026-08-28 16:40:44', NULL),
(95, 9, 'artist', '/ˈɑːrtɪst/', 'noun', 'nghệ sĩ', 'The artist displayed her paintings at the gallery.', NULL, '2026-08-28 16:40:44', NULL),
(96, 9, 'performance', '/pərˈfɔːrməns/', 'noun', 'màn trình diễn', 'The performance received a lot of applause.', NULL, '2026-08-28 16:40:44', NULL),
(97, 9, 'concert', '/ˈkɒnsərt/', 'noun', 'buổi hòa nhạc', 'Thousands of people attended the concert.', NULL, '2026-08-28 16:40:44', NULL),
(98, 9, 'audience', '/ˈɔːdiəns/', 'noun', 'khán giả', 'The audience enjoyed the show.', NULL, '2026-08-28 16:40:44', NULL),
(99, 9, 'gallery', '/ˈɡæləri/', 'noun', 'phòng trưng bày', 'The gallery displays modern artwork.', NULL, '2026-08-28 16:40:44', NULL),
(100, 9, 'festival', '/ˈfestɪvəl/', 'noun', 'lễ hội', 'The city holds a music festival every summer.', NULL, '2026-08-28 16:40:44', NULL),
(101, 9, 'creative', '/kriˈeɪtɪv/', 'adjective', 'sáng tạo', 'She has a very creative approach to painting.', NULL, '2026-08-28 16:40:44', NULL),
(102, 10, 'price', '/praɪs/', 'noun', 'giá', 'The price of this product is reasonable.', NULL, '2026-08-28 16:40:44', NULL),
(103, 10, 'customer', '/ˈkʌstəmər/', 'noun', 'khách hàng', 'The customer asked for a different size.', NULL, '2026-08-28 16:40:44', NULL),
(104, 10, 'purchase', '/ˈpɜːrtʃəs/', 'noun', 'việc mua hàng', 'The purchase was completed online.', NULL, '2026-08-28 16:40:44', NULL),
(105, 10, 'receipt', '/rɪˈsiːt/', 'noun', 'hóa đơn', 'Please keep your receipt after the purchase.', NULL, '2026-08-28 16:40:44', NULL),
(106, 10, 'brand', '/brænd/', 'noun', 'thương hiệu', 'This is a popular clothing brand.', NULL, '2026-08-28 16:40:44', NULL),
(107, 10, 'product', '/ˈprɒdʌkt/', 'noun', 'sản phẩm', 'The company launched a new product.', NULL, '2026-08-28 16:40:44', NULL),
(108, 10, 'sale', '/seɪl/', 'noun', 'đợt giảm giá', 'The store has a big sale this weekend.', NULL, '2026-08-28 16:40:44', NULL),
(109, 10, 'refund', '/ˈriːfʌnd/', 'noun', 'khoản hoàn tiền', 'The customer requested a refund.', NULL, '2026-08-28 16:40:44', NULL),
(110, 10, 'cashier', '/kæˈʃɪər/', 'noun', 'nhân viên thu ngân', 'The cashier gave me my receipt.', NULL, '2026-08-28 16:40:44', NULL),
(111, 11, 'player', '/ˈpleɪər/', 'noun', 'vận động viên, người chơi', 'The player scored the winning goal.', NULL, '2026-08-28 16:40:44', NULL),
(112, 11, 'team', '/tiːm/', 'noun', 'đội', 'Our team won the final match.', NULL, '2026-08-28 16:40:44', NULL),
(113, 11, 'match', '/mætʃ/', 'noun', 'trận đấu', 'The football match starts at seven.', NULL, '2026-08-28 16:40:44', NULL),
(114, 11, 'coach', '/koʊtʃ/', 'noun', 'huấn luyện viên', 'The coach gave the players useful advice.', NULL, '2026-08-28 16:40:44', NULL),
(115, 11, 'competition', '/ˌkɒmpəˈtɪʃən/', 'noun', 'cuộc thi đấu', 'She won first place in the competition.', NULL, '2026-08-28 16:40:44', NULL),
(116, 11, 'athlete', '/ˈæθliːt/', 'noun', 'vận động viên', 'The athlete trains every morning.', NULL, '2026-08-28 16:40:44', NULL),
(117, 11, 'score', '/skɔːr/', 'noun', 'tỉ số', 'The final score was three to two.', NULL, '2026-08-28 16:40:44', NULL),
(118, 11, 'victory', '/ˈvɪktəri/', 'noun', 'chiến thắng', 'The team celebrated its victory.', NULL, '2026-08-28 16:40:44', NULL),
(119, 11, 'training', '/ˈtreɪnɪŋ/', 'noun', 'luyện tập', 'The players have training every afternoon.', NULL, '2026-08-28 16:40:44', NULL),
(120, 12, 'rhythm', '/ˈrɪðəm/', 'noun', 'nhịp điệu', 'The song has a strong rhythm.', NULL, '2026-08-28 16:40:44', NULL),
(121, 12, 'instrument', '/ˈɪnstrəmənt/', 'noun', 'nhạc cụ', 'She plays a musical instrument.', NULL, '2026-08-28 16:40:44', NULL),
(122, 12, 'guitar', '/ɡɪˈtɑːr/', 'noun', 'đàn ghi-ta', 'He plays the guitar very well.', NULL, '2026-08-28 16:40:44', NULL),
(123, 12, 'piano', '/piˈænoʊ/', 'noun', 'đàn piano', 'My sister is learning to play the piano.', NULL, '2026-08-28 16:40:44', NULL),
(124, 12, 'singer', '/ˈsɪŋər/', 'noun', 'ca sĩ', 'The singer performed three songs.', NULL, '2026-08-28 16:40:44', NULL),
(125, 12, 'lyrics', '/ˈlɪrɪks/', 'noun', 'lời bài hát', 'I like the lyrics of this song.', NULL, '2026-08-28 16:40:44', NULL),
(126, 12, 'composer', '/kəmˈpoʊzər/', 'noun', 'nhà soạn nhạc', 'The composer created several famous pieces.', NULL, '2026-08-28 16:40:44', NULL),
(127, 12, 'genre', '/ˈʒɒnrə/', 'noun', 'thể loại', 'Jazz is my favorite music genre.', NULL, '2026-08-28 16:40:44', NULL),
(128, 12, 'instrumental', '/ˌɪnstrəˈmentəl/', 'adjective', 'thuộc về nhạc cụ', 'The album contains several instrumental tracks.', NULL, '2026-08-28 16:40:44', NULL),
(129, 13, 'temperature', '/ˈtemprətʃər/', 'noun', 'nhiệt độ', 'The temperature reached thirty degrees today.', NULL, '2026-08-28 16:40:44', NULL),
(130, 13, 'forecast', '/ˈfɔːrkæst/', 'noun', 'dự báo', 'The weather forecast says it will rain tomorrow.', NULL, '2026-08-28 16:40:44', NULL),
(131, 13, 'rainfall', '/ˈreɪnfɔːl/', 'noun', 'lượng mưa', 'The region receives heavy rainfall during summer.', NULL, '2026-08-28 16:40:44', NULL),
(132, 13, 'storm', '/stɔːrm/', 'noun', 'bão', 'The storm damaged several buildings.', NULL, '2026-08-28 16:40:44', NULL),
(133, 13, 'thunder', '/ˈθʌndər/', 'noun', 'sấm', 'We heard loud thunder during the storm.', NULL, '2026-08-28 16:40:44', NULL),
(134, 13, 'lightning', '/ˈlaɪtnɪŋ/', 'noun', 'tia chớp', 'The lightning lit up the sky.', NULL, '2026-08-28 16:40:44', NULL),
(135, 13, 'sunny', '/ˈsʌni/', 'adjective', 'có nắng', 'It will be sunny this afternoon.', NULL, '2026-08-28 16:40:44', NULL),
(136, 13, 'cloudy', '/ˈklaʊdi/', 'adjective', 'nhiều mây', 'The sky is cloudy today.', NULL, '2026-08-28 16:40:44', NULL),
(137, 13, 'windy', '/ˈwɪndi/', 'adjective', 'nhiều gió', 'It is too windy to go sailing today.', NULL, '2026-08-28 16:40:44', NULL),
(138, 14, 'clothing', '/ˈkloʊðɪŋ/', 'noun', 'quần áo', 'The store sells fashionable clothing.', NULL, '2026-08-28 16:40:44', NULL),
(139, 14, 'outfit', '/ˈaʊtfɪt/', 'noun', 'bộ trang phục', 'She chose a simple outfit for the party.', NULL, '2026-08-28 16:40:44', NULL),
(140, 14, 'fashionable', '/ˈfæʃənəbəl/', 'adjective', 'thời trang', 'These shoes are very fashionable this year.', NULL, '2026-08-28 16:40:44', NULL),
(141, 14, 'designer', '/dɪˈzaɪnər/', 'noun', 'nhà thiết kế', 'The designer created a new collection.', NULL, '2026-08-28 16:40:44', NULL),
(142, 14, 'fabric', '/ˈfæbrɪk/', 'noun', 'vải', 'This shirt is made from soft fabric.', NULL, '2026-08-28 16:40:44', NULL),
(143, 14, 'sleeve', '/sliːv/', 'noun', 'tay áo', 'The shirt has long sleeves.', NULL, '2026-08-28 16:40:44', NULL),
(144, 14, 'jacket', '/ˈdʒækɪt/', 'noun', 'áo khoác', 'He wore a black jacket to work.', NULL, '2026-08-28 16:40:44', NULL),
(145, 14, 'pattern', '/ˈpætərn/', 'noun', 'hoa văn', 'The dress has a beautiful floral pattern.', NULL, '2026-08-28 16:40:44', NULL),
(146, 14, 'trend', '/trend/', 'noun', 'xu hướng', 'This style is becoming a popular fashion trend.', NULL, '2026-08-28 16:40:44', NULL),
(147, 15, 'office', '/ˈɒfɪs/', 'noun', 'văn phòng', 'Our office is located in the city center.', NULL, '2026-08-28 16:40:44', NULL),
(148, 15, 'project', '/ˈprɒdʒekt/', 'noun', 'dự án', 'The team is working on an important project.', NULL, '2026-08-28 16:40:44', NULL),
(149, 15, 'schedule', '/ˈskedʒuːl/', 'noun', 'lịch trình', 'I checked my schedule before the meeting.', NULL, '2026-08-28 16:40:44', NULL),
(150, 15, 'manager', '/ˈmænɪdʒər/', 'noun', 'quản lý', 'The manager approved the new plan.', NULL, '2026-08-28 16:40:44', NULL),
(151, 15, 'department', '/dɪˈpɑːrtmənt/', 'noun', 'phòng ban', 'She works in the marketing department.', NULL, '2026-08-28 16:40:44', NULL),
(152, 15, 'employee', '/ɪmˈplɔɪiː/', 'noun', 'nhân viên', 'The company has more than one hundred employees.', NULL, '2026-08-28 16:40:44', NULL),
(153, 15, 'task', '/tɑːsk/', 'noun', 'nhiệm vụ', 'I finished the task before lunch.', NULL, '2026-08-28 16:40:44', NULL),
(154, 15, 'workload', '/ˈwɜːrkloʊd/', 'noun', 'khối lượng công việc', 'Her workload increased during the busy season.', NULL, '2026-08-28 16:40:44', NULL),
(155, 15, 'presentation', '/ˌprezənˈteɪʃən/', 'noun', 'bài thuyết trình', 'He prepared a presentation for the meeting.', NULL, '2026-08-28 16:40:44', NULL),
(156, 16, 'bank', '/bæŋk/', 'noun', 'ngân hàng', 'I opened a new account at the bank.', NULL, '2026-08-28 16:40:44', NULL),
(157, 16, 'account', '/əˈkaʊnt/', 'noun', 'tài khoản', 'She transferred money to her bank account.', NULL, '2026-08-28 16:40:44', NULL),
(158, 16, 'budget', '/ˈbʌdʒɪt/', 'noun', 'ngân sách', 'We need to create a budget for the project.', NULL, '2026-08-28 16:40:44', NULL),
(159, 16, 'income', '/ˈɪnkʌm/', 'noun', 'thu nhập', 'His monthly income is enough to cover his expenses.', NULL, '2026-08-28 16:40:44', NULL),
(160, 16, 'expense', '/ɪkˈspens/', 'noun', 'chi phí', 'Travel expenses were higher than expected.', NULL, '2026-08-28 16:40:44', NULL),
(161, 16, 'loan', '/loʊn/', 'noun', 'khoản vay', 'The company applied for a business loan.', NULL, '2026-08-28 16:40:44', NULL),
(162, 16, 'interest', '/ˈɪntrəst/', 'noun', 'lãi suất', 'The bank offers a low interest rate.', NULL, '2026-08-28 16:40:44', NULL),
(163, 16, 'capital', '/ˈkæpɪtəl/', 'noun', 'vốn', 'The company needs more capital to expand.', NULL, '2026-08-28 16:40:44', NULL),
(164, 16, 'financial', '/faɪˈnænʃəl/', 'adjective', 'thuộc về tài chính', 'The company is facing financial difficulties.', NULL, '2026-08-28 16:40:44', NULL),
(165, 17, 'bus', '/bʌs/', 'noun', 'xe buýt', 'I take the bus to school every day.', NULL, '2026-08-28 16:40:44', NULL),
(166, 17, 'train', '/treɪn/', 'noun', 'tàu hỏa', 'The train leaves at eight o’clock.', NULL, '2026-08-28 16:40:44', NULL),
(167, 17, 'airport', '/ˈeərpɔːrt/', 'noun', 'sân bay', 'We arrived at the airport two hours early.', NULL, '2026-08-28 16:40:44', NULL),
(168, 17, 'traffic', '/ˈtræfɪk/', 'noun', 'giao thông', 'There was heavy traffic this morning.', NULL, '2026-08-28 16:40:44', NULL),
(169, 17, 'subway', '/ˈsʌbweɪ/', 'noun', 'tàu điện ngầm', 'The subway is the fastest way to travel across the city.', NULL, '2026-08-28 16:40:44', NULL),
(170, 17, 'bicycle', '/ˈbaɪsɪkəl/', 'noun', 'xe đạp', 'He rides his bicycle to work.', NULL, '2026-08-28 16:40:44', NULL),
(171, 17, 'passenger', '/ˈpæsɪndʒər/', 'noun', 'hành khách', 'All passengers must wear a seat belt.', NULL, '2026-08-28 16:40:44', NULL),
(172, 17, 'route', '/ruːt/', 'noun', 'tuyến đường', 'This bus route passes through the city center.', NULL, '2026-08-28 16:40:44', NULL),
(173, 17, 'station', '/ˈsteɪʃən/', 'noun', 'nhà ga', 'The train station is close to the hotel.', NULL, '2026-08-28 16:40:44', NULL),
(174, 18, 'experiment', '/ɪkˈsperɪmənt/', 'noun', 'thí nghiệm', 'The students conducted a science experiment.', NULL, '2026-08-28 16:40:44', NULL),
(175, 18, 'theory', '/ˈθɪəri/', 'noun', 'lý thuyết', 'The theory has been tested by many scientists.', NULL, '2026-08-28 16:40:44', NULL),
(176, 18, 'laboratory', '/ləˈbɒrətɔːri/', 'noun', 'phòng thí nghiệm', 'The researchers work in a modern laboratory.', NULL, '2026-08-28 16:40:44', NULL),
(177, 18, 'researcher', '/rɪˈsɜːrtʃər/', 'noun', 'nhà nghiên cứu', 'The researcher collected data from the experiment.', NULL, '2026-08-28 16:40:44', NULL),
(178, 18, 'evidence', '/ˈevɪdəns/', 'noun', 'bằng chứng', 'The researchers found strong evidence to support the theory.', NULL, '2026-08-28 16:40:44', NULL),
(179, 18, 'discovery', '/dɪˈskʌvəri/', 'noun', 'phát hiện', 'The discovery changed our understanding of the disease.', NULL, '2026-08-28 16:40:44', NULL),
(180, 18, 'scientist', '/ˈsaɪəntɪst/', 'noun', 'nhà khoa học', 'The scientist published the results of the study.', NULL, '2026-08-28 16:40:44', NULL),
(181, 18, 'analysis', '/əˈnæləsɪs/', 'noun', 'phân tích', 'The analysis of the data took several weeks.', NULL, '2026-08-28 16:40:44', NULL),
(182, 18, 'observation', '/ˌɒbzərˈveɪʃən/', 'noun', 'sự quan sát', 'Careful observation is important during a scientific experiment.', NULL, '2026-08-28 16:40:44', NULL),
(183, 19, 'building', '/ˈbɪldɪŋ/', 'noun', 'tòa nhà', 'The building was designed by a famous architect.', NULL, '2026-08-28 16:40:44', NULL),
(184, 19, 'architect', '/ˈɑːrkɪtekt/', 'noun', 'kiến trúc sư', 'The architect designed a modern office building.', NULL, '2026-08-28 16:40:44', NULL),
(185, 19, 'structure', '/ˈstrʌktʃər/', 'noun', 'cấu trúc, công trình', 'The structure can withstand strong winds.', NULL, '2026-08-28 16:40:44', NULL),
(186, 19, 'design', '/dɪˈzaɪn/', 'noun', 'thiết kế', 'The building has a modern design.', NULL, '2026-08-28 16:40:44', NULL),
(187, 19, 'construction', '/kənˈstrʌkʃən/', 'noun', 'xây dựng', 'Construction of the new bridge starts next month.', NULL, '2026-08-28 16:40:44', NULL),
(188, 19, 'concrete', '/ˈkɒŋkriːt/', 'noun', 'bê tông', 'The walls are made of reinforced concrete.', NULL, '2026-08-28 16:40:44', NULL),
(189, 19, 'foundation', '/faʊnˈdeɪʃən/', 'noun', 'nền móng', 'The workers are preparing the foundation of the building.', NULL, '2026-08-28 16:40:44', NULL),
(190, 19, 'floor', '/flɔːr/', 'noun', 'tầng, sàn', 'The office is located on the fifth floor.', NULL, '2026-08-28 16:40:44', NULL),
(191, 19, 'interior', '/ɪnˈtɪəriər/', 'noun', 'nội thất, bên trong', 'The interior of the house is bright and spacious.', NULL, '2026-08-28 16:40:44', NULL),
(192, 20, 'happiness', '/ˈhæpinəs/', 'noun', 'hạnh phúc', 'Spending time with family brings her happiness.', NULL, '2026-08-28 16:40:44', NULL),
(193, 20, 'sadness', '/ˈsædnəs/', 'noun', 'nỗi buồn', 'Music can sometimes express feelings of sadness.', NULL, '2026-08-28 16:40:44', NULL),
(194, 20, 'anger', '/ˈæŋɡər/', 'noun', 'sự tức giận', 'He tried to control his anger.', NULL, '2026-08-28 16:40:44', NULL),
(195, 20, 'fear', '/fɪər/', 'noun', 'nỗi sợ', 'She overcame her fear of public speaking.', NULL, '2026-08-28 16:40:44', NULL),
(196, 20, 'excitement', '/ɪkˈsaɪtmənt/', 'noun', 'sự phấn khích', 'The children were full of excitement before the trip.', NULL, '2026-08-28 16:40:44', NULL),
(197, 20, 'confidence', '/ˈkɒnfɪdəns/', 'noun', 'sự tự tin', 'Practice helped him build confidence.', NULL, '2026-08-28 16:40:44', NULL),
(198, 20, 'surprise', '/sərˈpraɪz/', 'noun', 'sự ngạc nhiên', 'The birthday party was a complete surprise.', NULL, '2026-08-28 16:40:44', NULL),
(199, 20, 'anxiety', '/æŋˈzaɪəti/', 'noun', 'sự lo lắng', 'Preparing well can reduce anxiety before an exam.', NULL, '2026-08-28 16:40:44', NULL),
(200, 20, 'patience', '/ˈpeɪʃəns/', 'noun', 'sự kiên nhẫn', 'Learning a new language requires patience.', NULL, '2026-08-28 16:40:44', NULL),
(201, 20, 'happy', '[ˈhapi]', 'adj', 'Hạnh phúc', 'I\'m so happy to see you', NULL, '2026-09-13 03:11:38', NULL),
(210, NULL, 'technology', '/tek\'nɔlədʤi/', 'noun', 'Công nghệ', '', 3, '2026-09-10 11:26:17', NULL),
(211, NULL, 'computer', '/kəm\'pju:tə/', 'noun', 'máy tính', 'electronic computer (máy tính điện tử)', 3, '2026-09-12 17:26:40', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary_images`
--

CREATE TABLE `vocabulary_images` (
  `id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `uploaded_by` int DEFAULT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary_images`
--

INSERT INTO `vocabulary_images` (`id`, `vocabulary_id`, `image_url`, `uploaded_by`, `uploaded_at`) VALUES
(1, 1, 'https://images.unsplash.com/photo-elephant.jpg', NULL, '2026-08-28 16:40:44'),
(2, 2, 'https://images.unsplash.com/photo-algorithm.jpg', NULL, '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary_sets`
--

CREATE TABLE `vocabulary_sets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary_sets`
--

INSERT INTO `vocabulary_sets` (`id`, `user_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 3, 'IELTS1', '', '2026-09-10 10:36:37', '2026-09-12 16:33:40'),
(2, 3, 'IELST3', '', '2026-09-12 16:32:15', '2026-09-12 16:32:15'),
(3, 3, 'IELTS5', '', '2026-09-12 16:32:32', '2026-09-12 16:33:14'),
(4, 3, 'IELST5', '', '2026-09-12 16:33:14', '2026-09-12 16:33:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vocabulary_set_items`
--

CREATE TABLE `vocabulary_set_items` (
  `id` int NOT NULL,
  `vocabulary_set_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `display_order` int NOT NULL DEFAULT '0',
  `added_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `vocabulary_set_items`
--

INSERT INTO `vocabulary_set_items` (`id`, `vocabulary_set_id`, `vocabulary_id`, `display_order`, `added_at`) VALUES
(1, 1, 210, 0, '2026-09-10 11:26:17'),
(2, 1, 211, 0, '2026-09-12 17:26:40');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`vocabulary_id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`);

--
-- Chỉ mục cho bảng `learning_attempts`
--
ALTER TABLE `learning_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_attempt_resume` (`user_id`,`activity_type`,`source_type`,`source_id`,`status`,`updated_at`);

--
-- Chỉ mục cho bảng `learning_sessions`
--
ALTER TABLE `learning_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_learning_sessions_user_date` (`user_id`,`session_date`),
  ADD KEY `idx_learning_sessions_topic` (`topic_id`),
  ADD KEY `idx_learning_sessions_set` (`vocabulary_set_id`);

--
-- Chỉ mục cho bảng `monthly_rewards`
--
ALTER TABLE `monthly_rewards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_month` (`user_id`,`year_month`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `quiz_answer_details`
--
ALTER TABLE `quiz_answer_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_quiz_vocab` (`quiz_result_id`,`vocabulary_id`),
  ADD KEY `idx_quiz_result_id` (`quiz_result_id`),
  ADD KEY `idx_vocabulary_id` (`vocabulary_id`);

--
-- Chỉ mục cho bảng `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `idx_quiz_results_set` (`vocabulary_set_id`);

--
-- Chỉ mục cho bảng `review_logs`
--
ALTER TABLE `review_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_id` (`progress_id`);

--
-- Chỉ mục cho bảng `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_key`);

--
-- Chỉ mục cho bảng `Topics`
--
ALTER TABLE `Topics`
  ADD PRIMARY KEY (`topicID`),
  ADD KEY `users` (`created_by`);

--
-- Chỉ mục cho bảng `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `user_points`
--
ALTER TABLE `user_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_topic` (`user_id`,`topic_id`),
  ADD KEY `idx_user_points_user` (`user_id`),
  ADD KEY `idx_user_points_earned` (`earned_at`),
  ADD KEY `fk_user_points_topic` (`topic_id`);

--
-- Chỉ mục cho bảng `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`vocabulary_id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`);

--
-- Chỉ mục cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- Chỉ mục cho bảng `vocabulary_sets`
--
ALTER TABLE `vocabulary_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_vocabulary_sets_user_id` (`user_id`);

--
-- Chỉ mục cho bảng `vocabulary_set_items`
--
ALTER TABLE `vocabulary_set_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_set_vocabulary` (`vocabulary_set_id`,`vocabulary_id`),
  ADD KEY `idx_set_items_set_id` (`vocabulary_set_id`),
  ADD KEY `idx_set_items_vocabulary_id` (`vocabulary_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `learning_attempts`
--
ALTER TABLE `learning_attempts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `learning_sessions`
--
ALTER TABLE `learning_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT cho bảng `monthly_rewards`
--
ALTER TABLE `monthly_rewards`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `quiz_answer_details`
--
ALTER TABLE `quiz_answer_details`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=322;

--
-- AUTO_INCREMENT cho bảng `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT cho bảng `review_logs`
--
ALTER TABLE `review_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=574;

--
-- AUTO_INCREMENT cho bảng `Topics`
--
ALTER TABLE `Topics`
  MODIFY `topicID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT cho bảng `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user_points`
--
ALTER TABLE `user_points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=692;

--
-- AUTO_INCREMENT cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT cho bảng `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `vocabulary_sets`
--
ALTER TABLE `vocabulary_sets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `vocabulary_set_items`
--
ALTER TABLE `vocabulary_set_items`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Cấu trúc cho view `tu_vung`
--
DROP TABLE IF EXISTS `tu_vung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `tu_vung`  AS SELECT `vocabulary`.`id` AS `id`, `vocabulary`.`topic_id` AS `topic_id`, `vocabulary`.`word` AS `word`, `vocabulary`.`pronunciation` AS `pronunciation`, `vocabulary`.`part_of_speech` AS `part_of_speech`, `vocabulary`.`meaning` AS `meaning`, `vocabulary`.`example_sentence` AS `example_sentence`, `vocabulary`.`created_by` AS `created_by`, `vocabulary`.`created_at` AS `created_at`, `vocabulary`.`audio_url` AS `audio_url` FROM `vocabulary` ;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `learning_attempts`
--
ALTER TABLE `learning_attempts`
  ADD CONSTRAINT `fk_learning_attempts_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `learning_sessions`
--
ALTER TABLE `learning_sessions`
  ADD CONSTRAINT `fk_learning_sessions_set` FOREIGN KEY (`vocabulary_set_id`) REFERENCES `vocabulary_sets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_learning_sessions_topic` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE SET NULL,
  ADD CONSTRAINT `learning_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `monthly_rewards`
--
ALTER TABLE `monthly_rewards`
  ADD CONSTRAINT `fk_monthly_rewards_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `quiz_answer_details`
--
ALTER TABLE `quiz_answer_details`
  ADD CONSTRAINT `fk_quiz_answer_details_result` FOREIGN KEY (`quiz_result_id`) REFERENCES `quiz_results` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_quiz_answer_details_vocabulary` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `fk_quiz_results_set` FOREIGN KEY (`vocabulary_set_id`) REFERENCES `vocabulary_sets` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `review_logs`
--
ALTER TABLE `review_logs`
  ADD CONSTRAINT `review_logs_ibfk_1` FOREIGN KEY (`progress_id`) REFERENCES `user_vocab_progress` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `Topics`
--
ALTER TABLE `Topics`
  ADD CONSTRAINT `Topics_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD CONSTRAINT `user_login_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_points`
--
ALTER TABLE `user_points`
  ADD CONSTRAINT `fk_user_points_topic` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_points_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  ADD CONSTRAINT `user_vocab_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vocab_progress_ibfk_2` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD CONSTRAINT `vocabulary_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE CASCADE,
  ADD CONSTRAINT `vocabulary_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  ADD CONSTRAINT `vocabulary_images_ibfk_1` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vocabulary_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `vocabulary_sets`
--
ALTER TABLE `vocabulary_sets`
  ADD CONSTRAINT `fk_vocabulary_sets_user` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `vocabulary_set_items`
--
ALTER TABLE `vocabulary_set_items`
  ADD CONSTRAINT `fk_set_items_set` FOREIGN KEY (`vocabulary_set_id`) REFERENCES `vocabulary_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_set_items_vocabulary` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
