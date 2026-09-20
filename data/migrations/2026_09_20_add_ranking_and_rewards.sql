-- ============================================================
-- LexiLoop: Hệ thống điểm thưởng & xếp hạng người dùng theo tháng.
-- Học xong (mastered) toàn bộ từ vựng của 1 chủ đề => +10 điểm.
-- Cuối tháng, Admin xét Top 5 điểm cao nhất để trao thưởng.
--
-- Cách chạy: vào phpMyAdmin, chọn ĐÚNG database ứng dụng đang dùng
-- (kiểm tra lại trong Connect.php, thường là db_LexiLoop), vào tab
-- SQL, dán toàn bộ nội dung file này rồi bấm "Thực hiện". Chỉ cần
-- chạy MỘT LẦN.
-- ============================================================

CREATE TABLE IF NOT EXISTS `user_points` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `topic_id` INT NOT NULL,
    `points` INT NOT NULL DEFAULT 10,
    `earned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    -- Đảm bảo 1 user chỉ được cộng điểm 1 LẦN cho mỗi chủ đề,
    -- kể cả khi học lại/ôn tập nhiều lần sau khi đã mastered hết.
    UNIQUE KEY `uniq_user_topic` (`user_id`, `topic_id`),
    KEY `idx_user_points_user` (`user_id`),
    KEY `idx_user_points_earned` (`earned_at`),
    CONSTRAINT `fk_user_points_user`
        FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE,
    CONSTRAINT `fk_user_points_topic`
        FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topicID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `monthly_rewards` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    -- Định dạng 'YYYY-MM', ví dụ '2026-09'
    `year_month` CHAR(7) NOT NULL,
    `rank_position` TINYINT NOT NULL,
    `points` INT NOT NULL,
    `reward_name` VARCHAR(255) DEFAULT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_user_month` (`user_id`, `year_month`),
    CONSTRAINT `fk_monthly_rewards_user`
        FOREIGN KEY (`user_id`) REFERENCES `Users` (`userID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Kiểm tra sau khi chạy, mỗi dòng SHOW TABLES phải trả về kết quả.
SHOW TABLES LIKE 'user_points';
SHOW TABLES LIKE 'monthly_rewards';
