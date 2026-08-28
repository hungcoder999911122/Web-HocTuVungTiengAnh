-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Aug 28, 2026 at 04:44 PM
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
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `vocabulary_id`, `created_at`) VALUES
(1, 2, 1, '2026-08-28 16:40:44'),
(2, 2, 2, '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `learning_sessions`
--

CREATE TABLE `learning_sessions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `session_date` date NOT NULL,
  `words_studied` int DEFAULT '0',
  `duration_seconds` int DEFAULT '0',
  `streak_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `learning_sessions`
--

INSERT INTO `learning_sessions` (`id`, `user_id`, `session_date`, `words_studied`, `duration_seconds`, `streak_count`) VALUES
(1, 2, '2026-08-28', 15, 900, 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `otp_code`, `expires_at`, `created_at`) VALUES
(1, 'quan@gmail.com', '123456', '2026-08-28 16:55:44', '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `topic_id` int DEFAULT NULL,
  `total_questions` int NOT NULL,
  `correct_answers` int NOT NULL,
  `score` float GENERATED ALWAYS AS (((`correct_answers` / `total_questions`) * 100)) STORED,
  `started_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `finished_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `topic_id`, `total_questions`, `correct_answers`, `started_at`, `finished_at`) VALUES
(1, 2, 1, 10, 10, '2026-08-28 16:30:44', '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Table structure for table `review_logs`
--

CREATE TABLE `review_logs` (
  `id` int NOT NULL,
  `progress_id` int NOT NULL,
  `review_date` date NOT NULL,
  `quality_rating` tinyint NOT NULL,
  `response_time_ms` int DEFAULT NULL
) ;

--
-- Dumping data for table `review_logs`
--

INSERT INTO `review_logs` (`id`, `progress_id`, `review_date`, `quality_rating`, `response_time_ms`) VALUES
(1, 1, '2026-08-23', 4, 2500),
(2, 1, '2026-08-28', 5, 1800),
(3, 2, '2026-08-28', 4, 3200);

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE `Topics` (
  `topicID` int NOT NULL,
  `topicName` varchar(100) NOT NULL,
  `topicDescription` text,
  `created_by` int DEFAULT NULL,
  `topicCreated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Topics`
--

INSERT INTO `Topics` (`topicID`, `topicName`, `topicDescription`, `created_by`, `topicCreated_at`) VALUES
(1, 'Animals', 'Từ vựng về các loài động vật', 1, '2026-08-28 16:40:44'),
(2, 'Technology', 'Từ vựng về công nghệ và kỹ thuật số', 1, '2026-08-28 16:40:44'),
(3, 'Food & Drink', 'Từ vựng về ẩm thực và đồ uống', 2, '2026-08-28 16:40:44'),
(4, 'Travel', 'Từ vựng liên quan đến du lịch và di chuyển', 2, '2026-08-28 16:40:44'),
(5, 'Business', 'Từ vựng trong môi trường kinh doanh', 1, '2026-08-28 16:40:44'),
(6, 'Health & Medical', 'Từ vựng về sức khỏe, y tế và chăm sóc cơ thể', 1, '2026-08-28 16:40:44'),
(7, 'Education', 'Từ vựng về giáo dục và học thuật', 1, '2026-08-28 16:40:44'),
(8, 'Environment', 'Từ vựng về môi trường và thiên nhiên', 2, '2026-08-28 16:40:44'),
(9, 'Entertainment', 'Từ vựng về giải trí, nghệ thuật và sở thích', 2, '2026-08-28 16:40:44'),
(10, 'Shopping', 'Từ vựng về mua sắm và giao dịch', 1, '2026-08-28 16:40:44'),
(11, 'Sports', 'Từ vựng về các thể loại thể thao và vận động', 1, '2026-08-28 16:40:44'),
(12, 'Music', 'Từ vựng về âm nhạc và dụng cụ âm nhạc', 1, '2026-08-28 16:40:44'),
(13, 'Weather', 'Từ vựng về thời tiết và khí hậu', 2, '2026-08-28 16:40:44'),
(14, 'Fashion', 'Từ vựng về thời trang và trang phục', 2, '2026-08-28 16:40:44'),
(15, 'Workplace', 'Từ vựng về văn phòng và công việc hàng ngày', 1, '2026-08-28 16:40:44'),
(16, 'Finance', 'Từ vựng về tài chính và ngân hàng', 1, '2026-08-28 16:40:44'),
(17, 'Transportation', 'Từ vựng về phương tiện giao thông', 1, '2026-08-28 16:40:44'),
(18, 'Science', 'Từ vựng về khoa học và nghiên cứu', 2, '2026-08-28 16:40:44'),
(19, 'Architecture', 'Từ vựng về kiến trúc và xây dựng', 2, '2026-08-28 16:40:44'),
(20, 'Emotions', 'Từ vựng mô tả cảm xúc và tâm lý', 1, '2026-08-28 16:40:44');

-- --------------------------------------------------------

--
-- Stand-in structure for view `tu_vung`
-- (See below for the actual view)
--
CREATE TABLE `tu_vung` (
`id` int
,`topic_id` int
,`word` varchar(100)
,`pronunciation` varchar(100)
,`part_of_speech` varchar(30)
,`meaning` text
,`example_sentence` text
,`created_by` int
,`created_at` datetime
,`audio_url` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `userID` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(50) NOT NULL,
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

INSERT INTO `Users` (`userID`, `username`, `email`, `password_hash`, `full_name`, `avatar_url`, `role`, `status`, `created_at`, `update_at`, `daily_reminder_enabled`, `reminder_time`, `daily_target_words`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFXx...hash...', 'Quản Trị Viên', NULL, 'admin', 'active', '2026-08-28 16:40:44', '2026-08-28 16:40:44', 1, '20:00:00', 20),
(2, 'quan_le', 'quan@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFXx...hash...', 'Lê Quân', NULL, 'user', 'active', '2026-08-28 16:40:44', '2026-08-28 16:40:44', 1, '21:00:00', 15);

-- --------------------------------------------------------

--
-- Table structure for table `user_login_sessions`
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
-- Table structure for table `user_vocab_progress`
--

CREATE TABLE `user_vocab_progress` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `status` enum('new','learning','mastered') DEFAULT 'new',
  `ease_factor` float DEFAULT '2.5',
  `interval_days` int DEFAULT '0',
  `repetitions` int DEFAULT '0',
  `next_review_date` date DEFAULT NULL,
  `last_reviewed_at` datetime DEFAULT NULL,
  `last_quality_rating` tinyint DEFAULT NULL
) ;

--
-- Dumping data for table `user_vocab_progress`
--

INSERT INTO `user_vocab_progress` (`id`, `user_id`, `vocabulary_id`, `status`, `ease_factor`, `interval_days`, `repetitions`, `next_review_date`, `last_reviewed_at`, `last_quality_rating`) VALUES
(1, 2, 1, 'mastered', 2.5, 21, 5, '2026-09-18', '2026-08-28 16:40:44', 5),
(2, 2, 2, 'learning', 2.4, 6, 2, '2026-09-03', '2026-08-28 16:40:44', 4);

-- --------------------------------------------------------

--
-- Table structure for table `vocabulary`
--

CREATE TABLE `vocabulary` (
  `id` int NOT NULL,
  `topic_id` int NOT NULL,
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
-- Dumping data for table `vocabulary`
--

INSERT INTO `vocabulary` (`id`, `topic_id`, `word`, `pronunciation`, `part_of_speech`, `meaning`, `example_sentence`, `created_by`, `created_at`, `audio_url`) VALUES
(1, 1, 'elephant', '/ˈɛlɪfənt/', 'noun', 'con voi', 'The elephant is the largest land animal.', 1, '2026-08-28 16:40:44', NULL),
(2, 2, 'algorithm', '/ˈælɡərɪðəm/', 'noun', 'thuật toán', 'The search algorithm finds results quickly.', 1, '2026-08-28 16:40:44', NULL),
(3, 3, 'cuisine', '/kwɪˈziːn/', 'noun', 'ẩm thực', 'Vietnamese cuisine is famous worldwide.', 2, '2026-08-28 16:40:44', NULL),
(4, 4, 'itinerary', '/aɪˈtɪnərəri/', 'noun', 'lịch trình', 'We planned our itinerary in advance.', 2, '2026-08-28 16:40:44', NULL),
(5, 5, 'revenue', '/ˈrɛvənjuː/', 'noun', 'doanh thu', 'Company revenue grew significantly this year.', 1, '2026-08-28 16:40:44', NULL),
(6, 6, 'symptom', '/ˈsɪmptəm/', 'noun', 'triệu chứng', 'Fever is a common symptom of flu.', 1, '2026-08-28 16:40:44', NULL),
(7, 7, 'curriculum', '/kəˈrɪkjələm/', 'noun', 'chương trình học', 'The school updated its computer science curriculum.', 1, '2026-08-28 16:40:44', NULL),
(8, 8, 'ecosystem', '/ˈiːkəʊsɪstəm/', 'noun', 'hệ sinh thái', 'Forests play a crucial role in maintaining the ecosystem.', 2, '2026-08-28 16:40:44', NULL),
(9, 9, 'exhibition', '/ˌɛksɪˈbɪʃn/', 'noun', 'cuộc triển lãm', 'They visited an modern art exhibition.', 2, '2026-08-28 16:40:44', NULL),
(10, 10, 'discount', '/ˈdɪskaʊnt/', 'noun', 'giảm giá', 'The store offers a 20% discount today.', 1, '2026-08-28 16:40:44', NULL),
(11, 11, 'tournament', '/ˈtʊənəmənt/', 'noun', 'giải đấu', 'He played well in the tennis tournament.', 1, '2026-08-28 16:40:44', NULL),
(12, 12, 'melody', '/ˈmɛlədi/', 'noun', 'giai điệu', 'That song has a catchy melody.', 1, '2026-08-28 16:40:44', NULL),
(13, 13, 'humidity', '/hjuːˈmɪdɪti/', 'noun', 'độ ẩm', 'The high humidity makes it feel warmer.', 2, '2026-08-28 16:40:44', NULL),
(14, 14, 'accessory', '/əkˈsɛsəri/', 'noun', 'phụ kiện', 'A leather belt is a good accessory.', 2, '2026-08-28 16:40:44', NULL),
(15, 15, 'colleague', '/ˈkɒliːɡ/', 'noun', 'đồng nghiệp', 'She works closely with her colleague.', 1, '2026-08-28 16:40:44', NULL),
(16, 16, 'investment', '/ɪnˈvɛstmənt/', 'noun', 'khoản đầu tư', 'Real estate is a long-term investment.', 1, '2026-08-28 16:40:44', NULL),
(17, 17, 'vehicle', '/ˈviːək(əl)/', 'noun', 'phương tiện', 'Electric vehicles are getting popular.', 1, '2026-08-28 16:40:44', NULL),
(18, 18, 'hypothesis', '/haɪˈpɒθɪsɪs/', 'noun', 'giả thuyết', 'The experiment proved the hypothesis right.', 2, '2026-08-28 16:40:44', NULL),
(19, 19, 'blueprint', '/ˈbluːprɪnt/', 'noun', 'bản thiết kế', 'The architect showed us the house blueprint.', 2, '2026-08-28 16:40:44', NULL),
(20, 20, 'empathy', '/ˈɛmpəθi/', 'noun', 'sự đồng cảm', 'Great leaders show empathy towards others.', 1, '2026-08-28 16:40:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `vocabulary_images`
--

CREATE TABLE `vocabulary_images` (
  `id` int NOT NULL,
  `vocabulary_id` int NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `uploaded_by` int DEFAULT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vocabulary_images`
--

INSERT INTO `vocabulary_images` (`id`, `vocabulary_id`, `image_url`, `uploaded_by`, `uploaded_at`) VALUES
(1, 1, 'https://images.unsplash.com/photo-elephant.jpg', 1, '2026-08-28 16:40:44'),
(2, 2, 'https://images.unsplash.com/photo-algorithm.jpg', 1, '2026-08-28 16:40:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`vocabulary_id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`);

--
-- Indexes for table `learning_sessions`
--
ALTER TABLE `learning_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- Indexes for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_id` (`progress_id`);

--
-- Indexes for table `Topics`
--
ALTER TABLE `Topics`
  ADD PRIMARY KEY (`topicID`),
  ADD KEY `users` (`created_by`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`vocabulary_id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`);

--
-- Indexes for table `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD PRIMARY KEY (`id`),
  ADD KEY `topic_id` (`topic_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vocabulary_id` (`vocabulary_id`),
  ADD KEY `uploaded_by` (`uploaded_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `learning_sessions`
--
ALTER TABLE `learning_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review_logs`
--
ALTER TABLE `review_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Topics`
--
ALTER TABLE `Topics`
  MODIFY `topicID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vocabulary`
--
ALTER TABLE `vocabulary`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Structure for view `tu_vung`
--
DROP TABLE IF EXISTS `tu_vung`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `tu_vung`  AS SELECT `vocabulary`.`id` AS `id`, `vocabulary`.`topic_id` AS `topic_id`, `vocabulary`.`word` AS `word`, `vocabulary`.`pronunciation` AS `pronunciation`, `vocabulary`.`part_of_speech` AS `part_of_speech`, `vocabulary`.`meaning` AS `meaning`, `vocabulary`.`example_sentence` AS `example_sentence`, `vocabulary`.`created_by` AS `created_by`, `vocabulary`.`created_at` AS `created_at`, `vocabulary`.`audio_url` AS `audio_url` FROM `vocabulary` ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `learning_sessions`
--
ALTER TABLE `learning_sessions`
  ADD CONSTRAINT `learning_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_results_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE SET NULL;

--
-- Constraints for table `review_logs`
--
ALTER TABLE `review_logs`
  ADD CONSTRAINT `review_logs_ibfk_1` FOREIGN KEY (`progress_id`) REFERENCES `user_vocab_progress` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `Topics`
--
ALTER TABLE `Topics`
  ADD CONSTRAINT `Topics_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;

--
-- Constraints for table `user_login_sessions`
--
ALTER TABLE `user_login_sessions`
  ADD CONSTRAINT `user_login_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `user_vocab_progress`
--
ALTER TABLE `user_vocab_progress`
  ADD CONSTRAINT `user_vocab_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_vocab_progress_ibfk_2` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `vocabulary`
--
ALTER TABLE `vocabulary`
  ADD CONSTRAINT `vocabulary_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE CASCADE,
  ADD CONSTRAINT `vocabulary_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;

--
-- Constraints for table `vocabulary_images`
--
ALTER TABLE `vocabulary_images`
  ADD CONSTRAINT `vocabulary_images_ibfk_1` FOREIGN KEY (`vocabulary_id`) REFERENCES `vocabulary` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `vocabulary_images_ibfk_2` FOREIGN KEY (`uploaded_by`) REFERENCES `Users` (`userID`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
