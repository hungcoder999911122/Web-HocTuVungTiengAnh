-- ============================================================
-- LexiLoop: Đồng bộ cột `level` (thang 1-5) cho user_vocab_progress
-- theo logic Flashcard mới của đồng đội, KHÔNG xóa cột cũ,
-- KHÔNG đụng tới user_points/monthly_rewards/vocabulary_sets.
-- Chạy MỘT LẦN trên đúng database ứng dụng đang dùng.
-- ============================================================

-- Chỉ thêm cột nếu chưa có (tránh lỗi nếu đã từng chạy rồi)
SET @colExists := (
    SELECT COUNT(*) FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'user_vocab_progress'
      AND COLUMN_NAME = 'level'
);

SET @sql := IF(@colExists = 0,
    'ALTER TABLE user_vocab_progress
        ADD COLUMN level TINYINT NOT NULL DEFAULT 1 COMMENT ''Giai đoạn 1 đến 5'' AFTER vocabulary_id',
    'SELECT ''Cột level đã tồn tại, bỏ qua.'''
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Gán tạm level = 5 cho những từ đã "mastered" theo dữ liệu cũ,
-- để không bị coi là mới học lại từ đầu.
UPDATE user_vocab_progress SET level = 5 WHERE status = 'mastered' AND level = 1;
UPDATE user_vocab_progress SET level = 3 WHERE status = 'learning' AND level = 1;

-- Kiểm tra kết quả
SHOW COLUMNS FROM user_vocab_progress LIKE 'level';
