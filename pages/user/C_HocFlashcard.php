<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// Đồng bộ biến kết nối DB
if (isset($link) && !isset($conn)) {
    $conn = $link;
}

// Lấy ID người dùng từ Session
$user_id = $_SESSION['user_id'] 
    ?? $_SESSION['userID'] 
    ?? $_SESSION['id'] 
    ?? $_SESSION['user']['userID'] 
    ?? $_SESSION['user']['id'] 
    ?? 2; // Dự phòng khi test mở link trực tiếp

// Lấy ID chủ đề từ URL
$id_chu_de = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 1);
if ($id_chu_de <= 0) {
    $id_chu_de = 1;
}

$mode = $_GET['mode'] ?? ''; // Chế độ: 'review' (ôn tập) hoặc học theo chủ đề
$ten_chu_de = ($mode === 'review') ? "Từ vựng cần ôn tập" : "Chủ đề học";
$danh_sach_tu = [];

try {
    if (isset($link) && $link) {
        // Quét danh sách bảng thực tế tránh lỗi hoa/thường trên Docker
        $tables_res = @mysqli_query($link, "SHOW TABLES");
        $db_tables = [];
        if ($tables_res) {
            while ($tbl_row = mysqli_fetch_array($tables_res)) {
                $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
            }
        }

        $tbl_vocab    = isset($db_tables['vocabulary']) ? "`" . $db_tables['vocabulary'] . "`" : "`vocabulary`";
        $tbl_topics   = isset($db_tables['topics']) ? "`" . $db_tables['topics'] . "`" : "`Topics`";
        $tbl_progress = isset($db_tables['user_vocab_progress']) ? "`" . $db_tables['user_vocab_progress'] . "`" : "`user_vocab_progress`";

        // Lấy tên chủ đề
        if ($mode !== 'review' && isset($db_tables['topics'])) {
            $sql_topic = "SELECT topicName FROM $tbl_topics WHERE topicID = ? LIMIT 1";
            if ($stmt = @mysqli_prepare($link, $sql_topic)) {
                mysqli_stmt_bind_param($stmt, "i", $id_chu_de);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $ten_chu_de = $row['topicName'];
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Lấy danh sách từ vựng theo ID người dùng đang đăng nhập
        if (isset($db_tables['vocabulary'])) {
            if ($mode === 'review' && isset($db_tables['user_vocab_progress'])) {
                // Chế độ ôn tập: Từ vựng đến hạn của người dùng
                $sql_words = "
                    SELECT 
                        v.id,
                        v.word AS tu_vung,
                        v.meaning AS nghia,
                        v.pronunciation AS phien_am,
                        v.part_of_speech AS loai_tu,
                        v.example_sentence AS vi_du,
                        v.audio_url,
                        p.next_review_date
                    FROM $tbl_vocab v
                    INNER JOIN $tbl_progress p ON v.id = p.vocabulary_id
                    WHERE p.user_id = ? AND p.next_review_date <= CURDATE()
                    ORDER BY p.next_review_date ASC
                ";
                $stmt = @mysqli_prepare($link, $sql_words);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $danh_sach_tu[] = [
                            "id"        => (int)$row['id'],
                            "tu_vung"   => $row['tu_vung'],
                            "nghia"     => $row['nghia'],
                            "phien_am"  => $row['phien_am'] ?? '',
                            "loai_tu"   => $row['loai_tu'] ?? '',
                            "vi_du"     => $row['vi_du'] ?? '',
                            "audio_url" => $row['audio_url'] ?? '',
                            "is_review" => true
                        ];
                    }
                    mysqli_stmt_close($stmt);
                }
            } else {
                // Chế độ học từ mới theo Chủ đề
                $sql_words = "
                    SELECT 
                        v.id,
                        v.word AS tu_vung,
                        v.meaning AS nghia,
                        v.pronunciation AS phien_am,
                        v.part_of_speech AS loai_tu,
                        v.example_sentence AS vi_du,
                        v.audio_url,
                        p.next_review_date
                    FROM $tbl_vocab v
                    LEFT JOIN $tbl_progress p ON v.id = p.vocabulary_id AND p.user_id = ?
                    WHERE v.topic_id = ?
                    ORDER BY v.id ASC
                ";
                $stmt = @mysqli_prepare($link, $sql_words);
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $user_id, $id_chu_de);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $today = date('Y-m-d');
                    while ($row = mysqli_fetch_assoc($result)) {
                        $is_review = (!empty($row['next_review_date']) && $row['next_review_date'] <= $today);
                        $danh_sach_tu[] = [
                            "id"        => (int)$row['id'],
                            "tu_vung"   => $row['tu_vung'],
                            "nghia"     => $row['nghia'],
                            "phien_am"  => $row['phien_am'] ?? '',
                            "loai_tu"   => $row['loai_tu'] ?? '',
                            "vi_du"     => $row['vi_du'] ?? '',
                            "audio_url" => $row['audio_url'] ?? '',
                            "is_review" => $is_review
                        ];
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi Flashcard: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học FlashCard - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_HocFlashcard.css">
</head>
<body class="C_HocFlashcard_body">

    <!-- Header -->
    <header class="C_HocFlashcard_header">
        <h1 class="C_HocFlashcard_logo">Học FlashCard</h1>
        <span class="C_HocFlashcard_progressText" id="C_HocFlashcard_progressText">Thẻ 1/5</span>
    </header>

    <!-- Thanh tiến độ học -->
    <div class="C_HocFlashcard_progressBarWrapper">
        <div class="C_HocFlashcard_progressBar">
            <div class="C_HocFlashcard_progressFill" id="C_HocFlashcard_progressFill"></div>
        </div>
    </div>

    <!-- Khu vực thẻ học và điều hướng -->
    <main class="C_HocFlashcard_main">
        <div class="C_HocFlashcard_cardArea">
            <!-- Nút từ trước -->
            <button type="button" id="C_HocFlashcard_btnPrev" class="C_HocFlashcard_navBtn" title="Thẻ trước">&larr;</button>

            <!-- Hộp thẻ Flashcard -->
            <div class="C_HocFlashcard_cardBox" id="C_HocFlashcard_cardBox">
                <!-- Badge R (Ôn tập - Review) -->
                <div class="C_HocFlashcard_badgeR" id="C_HocFlashcard_badgeR" title="Thẻ cần ôn tập">R</div>
                
                <h2 class="C_HocFlashcard_word" id="C_HocFlashcard_word">Software</h2>
                <p class="C_HocFlashcard_hint" id="C_HocFlashcard_hint">Nhấn để xem nghĩa</p>
            </div>

            <!-- Nút từ tiếp theo -->
            <button type="button" id="C_HocFlashcard_btnNext" class="C_HocFlashcard_navBtn" title="Thẻ tiếp theo">&rarr;</button>
        </div>

        <!-- 2 Nút Đánh Giá -->
        <div class="C_HocFlashcard_btnGroup">
            <button type="button" id="C_HocFlashcard_btnChuaNho" class="C_HocFlashcard_btn C_HocFlashcard_btnWhite">
                Chưa nhớ
            </button>
            <button type="button" id="C_HocFlashcard_btnDaNho" class="C_HocFlashcard_btn C_HocFlashcard_btnGray">
                Đã nhớ
            </button>
        </div>
    </main>

    <!-- Footer thống kê và Kết thúc sớm -->
    <footer class="C_HocFlashcard_footerWrapper">
        <div class="C_HocFlashcard_footerBox">
            <div class="C_HocFlashcard_stats" id="C_HocFlashcard_stats">
                Đã học: 0 &nbsp;&bull;&nbsp; Đã nhớ: 0 &nbsp;&bull;&nbsp; Chưa nhớ: 0
            </div>
            <button type="button" id="C_HocFlashcard_btnKetThuc" class="C_HocFlashcard_btnKetThuc">
                Kết thúc sớm
            </button>
        </div>
    </footer>

    <script>
        const flashcardsData = <?php echo json_encode($danh_sach_tu, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="../../JS/C_HocFlashcard.js"></script>
</body>
</html>