<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// Lấy trực tiếp ID tài khoản từ Session
$user_id = $_SESSION['user_id'] 
    ?? $_SESSION['userID'] 
    ?? $_SESSION['id'] 
    ?? $_SESSION['user']['userID'] 
    ?? $_SESSION['user']['id'] 
    ?? 2; // Dự phòng user 2 khi mở test link trực tiếp

// Khởi tạo các giá trị mặc định
$diem_so      = 0;
$tong_cau     = 10;
$thoi_gian    = "0:00";
$cau_sai      = [];
$quiz_result_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['quiz_id']) ? intval($_GET['quiz_id']) : 0);

// Hàm định dạng số giây thành "Phút:Giây"
if (!function_exists('dinhDangThoiGianLam')) {
    function dinhDangThoiGianLam($seconds) {
        $seconds = max(0, intval($seconds));
        $m = floor($seconds / 60);
        $s = $seconds % 60;
        return sprintf("%d:%02d", $m, $s);
    }
}

try {
    if (isset($link) && $link) {
        // Quét danh sách bảng thực tế tránh lỗi phân biệt hoa/thường trên Linux
        $tables_res = @mysqli_query($link, "SHOW TABLES");
        $db_tables = [];
        if ($tables_res) {
            while ($tbl_row = mysqli_fetch_array($tables_res)) {
                $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
            }
        }

        $tbl_quiz   = isset($db_tables['quiz_results']) ? "`" . $db_tables['quiz_results'] . "`" : "`quiz_results`";
        $tbl_topics = isset($db_tables['topics']) ? "`" . $db_tables['topics'] . "`" : "`Topics`";

        // --- XỬ LÝ LƯU KẾT QUẢ NẾU CÓ DỮ LIỆU SUBMIT TỪ TRANG QUIZ (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($db_tables['quiz_results'])) {
            $p_correct = isset($_POST['correct_answers']) ? intval($_POST['correct_answers']) : (isset($_POST['diem_so']) ? intval($_POST['diem_so']) : null);
            $p_total   = isset($_POST['total_questions']) ? intval($_POST['total_questions']) : (isset($_POST['tong_cau']) ? intval($_POST['tong_cau']) : 10);
            $p_topic   = isset($_POST['topic_id']) ? intval($_POST['topic_id']) : (isset($_POST['id_chude']) ? intval($_POST['id_chude']) : 1);
            $p_seconds = isset($_POST['duration_seconds']) ? intval($_POST['duration_seconds']) : (isset($_POST['thoi_gian_giay']) ? intval($_POST['thoi_gian_giay']) : 120);

            if ($p_correct !== null && $p_total > 0) {
                $sql_ins = "
                    INSERT INTO $tbl_quiz (user_id, topic_id, total_questions, correct_answers, started_at, finished_at) 
                    VALUES (?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? SECOND), NOW())
                ";
                if ($stmt_ins = @mysqli_prepare($link, $sql_ins)) {
                    mysqli_stmt_bind_param($stmt_ins, "iiiii", $user_id, $p_topic, $p_total, $p_correct, $p_seconds);
                    if (mysqli_stmt_execute($stmt_ins)) {
                        $quiz_result_id = mysqli_insert_id($link);
                    }
                    mysqli_stmt_close($stmt_ins);
                }

                // Nhận danh sách câu sai nếu từ giao diện gửi lên
                if (!empty($_POST['cau_sai'])) {
                    $cau_sai = is_array($_POST['cau_sai']) ? $_POST['cau_sai'] : json_decode($_POST['cau_sai'], true);
                }
            }
        }

        // --- TRUY VẤN KẾT QUẢ BÀI QUIZ TỪ CSDL ---
        if (isset($db_tables['quiz_results'])) {
            if ($quiz_result_id > 0) {
                // Truy vấn theo ID cụ thể
                $sql_get = "
                    SELECT 
                        correct_answers, 
                        total_questions, 
                        TIMESTAMPDIFF(SECOND, started_at, finished_at) AS thoi_gian_giay 
                    FROM $tbl_quiz 
                    WHERE id = ? AND user_id = ? 
                    LIMIT 1
                ";
                if ($stmt_get = @mysqli_prepare($link, $sql_get)) {
                    mysqli_stmt_bind_param($stmt_get, "ii", $quiz_result_id, $user_id);
                    mysqli_stmt_execute($stmt_get);
                    $res = mysqli_stmt_get_result($stmt_get);
                    if ($row = mysqli_fetch_assoc($res)) {
                        $diem_so   = (int)$row['correct_answers'];
                        $tong_cau  = (int)$row['total_questions'];
                        $thoi_gian = dinhDangThoiGianLam($row['thoi_gian_giay'] ?? 180);
                    }
                    mysqli_stmt_close($stmt_get);
                }
            } else {
                // Lấy kết quả bài thi gần nhất của người dùng
                $sql_latest = "
                    SELECT 
                        correct_answers, 
                        total_questions, 
                        TIMESTAMPDIFF(SECOND, started_at, finished_at) AS thoi_gian_giay 
                    FROM $tbl_quiz 
                    WHERE user_id = ? 
                    ORDER BY COALESCE(finished_at, started_at) DESC, id DESC 
                    LIMIT 1
                ";
                if ($stmt_lat = @mysqli_prepare($link, $sql_latest)) {
                    mysqli_stmt_bind_param($stmt_lat, "i", $user_id);
                    mysqli_stmt_execute($stmt_lat);
                    $res = mysqli_stmt_get_result($stmt_lat);
                    if ($row = mysqli_fetch_assoc($res)) {
                        $diem_so   = (int)$row['correct_answers'];
                        $tong_cau  = (int)$row['total_questions'];
                        $thoi_gian = dinhDangThoiGianLam($row['thoi_gian_giay'] ?? 180);
                    }
                    mysqli_stmt_close($stmt_lat);
                }
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi Kết quả Quiz: " . $e->getMessage());
}

// --- TÍNH TOÁN TỶ LỆ VÀ XẾP LOẠI ---
$phan_tram = ($tong_cau > 0) ? round(($diem_so / $tong_cau) * 100) : 0;
$do_chinh_xac = $phan_tram . "%";

if ($phan_tram >= 90) {
    $xep_hang = "Xuất sắc";
    $feedback = "Tuyệt vời! Bạn nắm từ vựng rất vững!";
} elseif ($phan_tram >= 70) {
    $xep_hang = "Khá";
    $feedback = "Bạn làm rất tốt! Cố gắng phát huy nhé!";
} elseif ($phan_tram >= 50) {
    $xep_hang = "Trung bình";
    $feedback = "Khá ổn! Hãy ôn tập thêm để cải thiện phản xạ nhé!";
} else {
    $xep_hang = "Yếu";
    $feedback = "Hãy ôn lại các từ chưa nhớ và thử lại nhé!";
}

// Nếu đúng 100% thì danh sách câu sai là rỗng
if ($diem_so >= $tong_cau) {
    $cau_sai = [];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả Quiz - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_KetquaQuiz.css">
</head>
<body class="C_KetquaQuiz_body">

    <!-- Header Focus Mode -->
    <header class="C_KetquaQuiz_header">
        <h1 class="C_KetquaQuiz_logo">Kết quả Quiz</h1>
        <button type="button" id="C_KetquaQuiz_btnDong" class="C_KetquaQuiz_btnClose" title="Đóng">
            &times; Thoát
        </button>
    </header>

    <main class="C_KetquaQuiz_main">
        
        <!-- Vòng tròn hiển thị điểm -->
        <div class="C_KetquaQuiz_scoreCircle" id="C_KetquaQuiz_scoreCircle">
            <span class="C_KetquaQuiz_scoreText" id="C_KetquaQuiz_scoreText">
                <?php echo "{$diem_so}/{$tong_cau}"; ?>
            </span>
        </div>

        <!-- Lời khen / Nhận xét -->
        <p class="C_KetquaQuiz_feedback" id="C_KetquaQuiz_feedback">
            <?php echo $feedback; ?>
        </p>

        <!-- 3 Thẻ thống kê chi tiết -->
        <section class="C_KetquaQuiz_statsContainer">
            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Thời gian</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statTime"><?php echo $thoi_gian; ?></span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Độ chính xác</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statAccuracy"><?php echo $do_chinh_xac; ?></span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Xếp hạng</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statRank"><?php echo $xep_hang; ?></span>
            </div>
        </section>

        <!-- Cụm 3 nút hành động -->
        <div class="C_KetquaQuiz_btnGroup">
            <button type="button" id="C_KetquaQuiz_btnXemLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Xem lại câu sai
            </button>
            <button type="button" id="C_KetquaQuiz_btnLamLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnPrimary">
                Làm lại Quiz
            </button>
            <button type="button" id="C_KetquaQuiz_btnDashboard" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Về Dashboard
            </button>
        </div>

        <!-- Khối hiển thị chi tiết câu sai (có thể bấm mở rộng) -->
        <footer class="C_KetquaQuiz_wrongBoxContainer">
            <div class="C_KetquaQuiz_wrongBox" id="C_KetquaQuiz_wrongBox">
                <span class="wrong-icon">⚠️</span>
                <span><strong>Câu cần xem lại:</strong> Câu 3 (Software), Câu 7 (Meeting) - <em>Nhấn để xem chi tiết</em></span>
            </div>
            
            <div class="C_KetquaQuiz_wrongDetail" id="C_KetquaQuiz_wrongDetail" style="display: none;">
                <ul>
                    <?php foreach ($cau_sai as $item): ?>
                        <li><strong>Câu <?php echo $item['cau']; ?>:</strong> <?php echo $item['tu']; ?> &rarr; Nghĩa đúng: <span class="correct-text"><?php echo $item['nghia_dung']; ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </footer>

    </main>

    <script src="../../JS/C_KetquaQuiz.js"></script>
</body>
</html>