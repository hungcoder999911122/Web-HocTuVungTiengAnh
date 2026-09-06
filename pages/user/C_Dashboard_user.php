<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// Kiểm tra session đăng nhập
$user_id = $_SESSION['user_id'] 
    ?? $_SESSION['userID'] 
    ?? $_SESSION['id'] 
    ?? $_SESSION['user']['userID'] 
    ?? $_SESSION['user']['id'] 
    ?? null;

$user_email = $_SESSION['email'] 
    ?? $_SESSION['user']['email'] 
    ?? null;

// NẾU CHƯA ĐĂNG NHẬP: Chuyển hướng ngay về trang Đăng nhập và dừng thực thi
if (empty($user_id) && empty($user_email)) {
    header("Location: ../auth/A_DangNhap.php");
    exit();
}

// Lấy tên trực tiếp từ session nếu lúc đăng nhập đã lưu sẵn
$user_name = $_SESSION['full_name'] 
    ?? $_SESSION['user_name'] 
    ?? $_SESSION['name'] 
    ?? $_SESSION['user']['full_name'] 
    ?? $_SESSION['user']['name'] 
    ?? '';

// Khởi tạo các biến thống kê mặc định
$chuoi_ngay      = 0;
$tong_tu_hoc     = 0;
$tu_can_on_tap   = 0;
$diem_quiz_tb    = "0%";
$lich_su_gan_day = [];

// Hàm định dạng mốc thời gian
if (!function_exists('dinhDangThoiGian')) {
    function dinhDangThoiGian($datetime_str) {
        if (!$datetime_str) return '';
        $time = strtotime($datetime_str);
        $now = time();
        $diff = $now - $time;
        
        $date = date('Y-m-d', $time);
        $today = date('Y-m-d', $now);
        $yesterday = date('Y-m-d', strtotime('-1 day', $now));

        if ($date === $today) {
            return "Hôm nay, " . date('H:i', $time);
        } elseif ($date === $yesterday) {
            return "Hôm qua, " . date('H:i', $time);
        } elseif ($diff > 0 && $diff < 7 * 86400) {
            $days = floor($diff / 86400);
            return ($days > 0 ? $days : 1) . " ngày trước";
        } else {
            return date('d/m/Y H:i', $time);
        }
    }
}

if (isset($link) && $link) {
    // Quét danh sách bảng thực tế trong Database để tránh lỗi phân biệt hoa/thường trên Linux
    $tables_res = @mysqli_query($link, "SHOW TABLES");
    $db_tables = [];
    if ($tables_res) {
        while ($tbl_row = mysqli_fetch_array($tables_res)) {
            $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
        }
    }

    $tbl_users    = isset($db_tables['users']) ? "`" . $db_tables['users'] . "`" : "`Users`";
    $tbl_topics   = isset($db_tables['topics']) ? "`" . $db_tables['topics'] . "`" : "`Topics`";
    $tbl_sessions = isset($db_tables['learning_sessions']) ? "`" . $db_tables['learning_sessions'] . "`" : "`learning_sessions`";
    $tbl_progress = isset($db_tables['user_vocab_progress']) ? "`" . $db_tables['user_vocab_progress'] . "`" : "`user_vocab_progress`";
    $tbl_quiz     = isset($db_tables['quiz_results']) ? "`" . $db_tables['quiz_results'] . "`" : "`quiz_results`";

    // --- 1. LẤY THÔNG TIN NGƯỜI DÙNG TỪ CSDL ---
    if (isset($db_tables['users'])) {
        try {
            $col_id = 'userID';
            $check_col = @mysqli_query($link, "SHOW COLUMNS FROM $tbl_users LIKE 'userID'");
            if (!$check_col || mysqli_num_rows($check_col) === 0) {
                $col_id = 'id';
            }

            if (!empty($user_id)) {
                $sql_user = "SELECT `$col_id` AS uid, full_name FROM $tbl_users WHERE `$col_id` = ? LIMIT 1";
                if ($stmt = @mysqli_prepare($link, $sql_user)) {
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($res)) {
                        if (!empty($row['full_name'])) {
                            $user_name = $row['full_name'];
                        }
                    } else {
                        // User ID trong session không tồn tại trong DB -> Đẩy ra đăng nhập
                        header("Location: ../auth/A_DangNhap.php");
                        exit();
                    }
                    mysqli_stmt_close($stmt);
                }
            } elseif (!empty($user_email)) {
                $sql_user = "SELECT `$col_id` AS uid, full_name FROM $tbl_users WHERE email = ? LIMIT 1";
                if ($stmt = @mysqli_prepare($link, $sql_user)) {
                    mysqli_stmt_bind_param($stmt, "s", $user_email);
                    mysqli_stmt_execute($stmt);
                    $res = mysqli_stmt_get_result($stmt);
                    if ($row = mysqli_fetch_assoc($res)) {
                        $user_id = $row['uid'];
                        if (!empty($row['full_name'])) {
                            $user_name = $row['full_name'];
                        }
                    } else {
                        // Email không tồn tại trong DB -> Đẩy ra đăng nhập
                        header("Location: ../auth/A_DangNhap.php");
                        exit();
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        } catch (\Throwable $e) {}
    }

    if (empty($user_name)) {
        $user_name = "Người dùng";
    }

    // --- LẤY CHUỖI NGÀY HỌC (STREAK) CỦA USER ĐĂNG NHẬP ---
    if (isset($db_tables['learning_sessions']) && !empty($user_id)) {
        try {
            $sql_streak = "SELECT streak_count FROM $tbl_sessions WHERE user_id = ? ORDER BY session_date DESC, id DESC LIMIT 1";
            if ($stmt = @mysqli_prepare($link, $sql_streak)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $chuoi_ngay = (int)($row['streak_count'] ?? 0);
                }
                mysqli_stmt_close($stmt);
            }
        } catch (\Throwable $e) {}
    }

    // --- LẤY TỔNG SỐ TỪ ĐÃ HỌC & CẦN ÔN TẬP CỦA USER ĐĂNG NHẬP ---
    if (isset($db_tables['user_vocab_progress']) && !empty($user_id)) {
        try {
            // Tổng từ đang học hoặc đã thuộc
            $sql_total = "SELECT COUNT(*) AS total FROM $tbl_progress WHERE user_id = ? AND status IN ('learning', 'mastered')";
            if ($stmt = @mysqli_prepare($link, $sql_total)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $tong_tu_hoc = (int)($row['total'] ?? 0);
                }
                mysqli_stmt_close($stmt);
            }

            // Từ đến hạn ôn tập hôm nay
            $sql_review = "SELECT COUNT(*) AS total FROM $tbl_progress WHERE user_id = ? AND next_review_date <= CURDATE()";
            if ($stmt = @mysqli_prepare($link, $sql_review)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $tu_can_on_tap = (int)($row['total'] ?? 0);
                }
                mysqli_stmt_close($stmt);
            }
        } catch (\Throwable $e) {}
    }

    // --- LẤY ĐIỂM QUIZ TRUNG BÌNH CỦA USER ĐĂNG NHẬP ---
    if (isset($db_tables['quiz_results']) && !empty($user_id)) {
        try {
            $sql_quiz = "SELECT AVG(score) AS avg_score FROM $tbl_quiz WHERE user_id = ?";
            if ($stmt = @mysqli_prepare($link, $sql_quiz)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $diem_quiz_tb = $row['avg_score'] !== null ? round($row['avg_score']) . "%" : "0%";
                }
                mysqli_stmt_close($stmt);
            }
        } catch (\Throwable $e) {}
    }

    // --- LẤY LỊCH SỬ HOẠT ĐỘNG GẦN ĐÂY CỦA USER ĐĂNG NHẬP ---
    if (isset($db_tables['quiz_results']) && isset($db_tables['learning_sessions']) && !empty($user_id)) {
        try {
            $sql_history = "
                (
                    SELECT 
                        CONCAT('Hoàn thành Quiz \"', COALESCE(t.topicName, 'Tổng hợp'), '\" (', q.correct_answers, '/', q.total_questions, ' câu)') AS hanh_dong,
                        'quiz' AS loai,
                        COALESCE(q.finished_at, q.started_at) AS thoi_gian_raw
                    FROM $tbl_quiz q
                    LEFT JOIN $tbl_topics t ON q.topic_id = t.topicID
                    WHERE q.user_id = ?
                )
                UNION ALL
                (
                    SELECT 
                        CONCAT('Học ', s.words_studied, ' từ vựng trong phiên') AS hanh_dong,
                        'flashcard' AS loai,
                        CAST(CONCAT(s.session_date, ' 12:00:00') AS DATETIME) AS thoi_gian_raw
                    FROM $tbl_sessions s
                    WHERE s.user_id = ? AND s.words_studied > 0
                )
                ORDER BY thoi_gian_raw DESC
                LIMIT 4
            ";

            if ($stmt = @mysqli_prepare($link, $sql_history)) {
                mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                while ($row = mysqli_fetch_assoc($res)) {
                    $lich_su_gan_day[] = [
                        "hanh_dong" => $row['hanh_dong'],
                        "loai"      => $row['loai'],
                        "thoi_gian" => dinhDangThoiGian($row['thoi_gian_raw'])
                    ];
                }
                mysqli_stmt_close($stmt);
            }
        } catch (\Throwable $e) {}
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Dashboard_user.css">
</head>
<body class="C_Dashboard_user_body">

    <!-- =========================================
         SIDEBAR
         ========================================= -->
    <aside class="sidebar">
        <!-- Logo -->
        <a href="C_Dashboard_user.php" class="sidebar-logo">
            <span class="logo-badge">🌿</span> LexiLoop
        </a>

        <!-- Danh sách menu chính -->
        <nav class="sidebar-nav">
            <!-- 1. Dashboard (Đang Active) -->
            <a href="C_Dashboard_user.php" class="sidebar-link active">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                </span>
                <span>Dashboard</span>
            </a>

            <!-- 2. Danh sách chủ đề -->
            <a href="../main/B_DanhSachChuDe.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </span>
                <span>Danh sách chủ đề</span>
            </a>

            <!-- 3. Từ vựng của tôi -->
            <a href="C_Tuvungcuatoi.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                </span>
                <span>Từ vựng của tôi</span>
            </a>

            <!-- 4. Lịch sử ôn tập -->
            <a href="C_Lichsuontap.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </span>
                <span>Lịch sử ôn tập</span>
            </a>

            <!-- 5. Hồ sơ -->
            <a href="C_Hosocanhan.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <span>Hồ sơ</span>
            </a>

            <!-- 6. Cài đặt -->
            <a href="../auth/A_Caidattaikhoan.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                </span>
                <span>Cài đặt</span>
            </a>
        </nav>

        <!-- Nút Đăng xuất ở chân Sidebar -->
        <div class="sidebar-bottom">
            <a href="../auth/A_DangNhap.php" class="sidebar-link sidebar-logout" id="C_Dashboard_user_btnLogout">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </span>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <!-- =========================================
         KHU VỰC NỘI DUNG CHÍNH
         ========================================= -->
    <div class="page-content">

        <!-- Header trên cùng -->
        <header class="C_Dashboard_user_header">
            <h1 class="C_Dashboard_user_logo">Dashboard</h1>
            <div class="C_Dashboard_user_userInfo">
                <span class="C_Dashboard_user_greeting" id="C_Dashboard_user_greeting">
                    Xin chào, <?php echo htmlspecialchars($user_name); ?>
                </span>
                <a href="C_Hosocanhan.php" class="C_Dashboard_user_avatarCircle" title="Xem hồ sơ">
                    NA
                </a>
            </div>
        </header>

        <main class="C_Dashboard_user_mainContent">
            
            <!-- Phần chào mừng & 4 thẻ thống kê -->
            <section class="C_Dashboard_user_welcomeSection">
                <h2 class="C_Dashboard_user_welcomeTitle">Chào mừng bạn trở lại! 👋</h2>
                <p class="C_Dashboard_user_welcomeSub">
                    Bạn có <strong class="highlight-text"><?php echo $tu_can_on_tap; ?> từ</strong> cần ôn tập hôm nay để duy trì chuỗi nhớ.
                </p>

                <div class="C_Dashboard_user_statsGrid">
                    <!-- Thẻ 1: Chuỗi ngày -->
                    <div class="C_Dashboard_user_statCard">
                        <span class="C_Dashboard_user_statLabel">Chuỗi ngày học</span>
                        <span class="C_Dashboard_user_statVal stat-streak">
                            <?php echo $chuoi_ngay; ?> ngày 🔥
                        </span>
                    </div>

                    <!-- Thẻ 2: Từ đã học -->
                    <div class="C_Dashboard_user_statCard">
                        <span class="C_Dashboard_user_statLabel">Tổng từ đã học</span>
                        <span class="C_Dashboard_user_statVal">
                            <?php echo $tong_tu_hoc; ?>
                        </span>
                    </div>

                    <!-- Thẻ 3: Cần ôn tập hôm nay -->
                    <div class="C_Dashboard_user_statCard statCard-alert">
                        <span class="C_Dashboard_user_statLabel">Cần ôn tập hôm nay</span>
                        <span class="C_Dashboard_user_statVal stat-orange">
                            <?php echo $tu_can_on_tap; ?> từ
                        </span>
                    </div>

                    <!-- Thẻ 4: Điểm TB Quiz -->
                    <div class="C_Dashboard_user_statCard">
                        <span class="C_Dashboard_user_statLabel">Điểm Quiz trung bình</span>
                        <span class="C_Dashboard_user_statVal">
                            <?php echo $diem_quiz_tb; ?>
                        </span>
                    </div>
                </div>
            </section>

            <!-- Khối hành động nhanh (Bạn muốn làm gì hôm nay?) -->
            <section class="C_Dashboard_user_actionsSection">
                <h3 class="C_Dashboard_user_sectionHeading">Bạn muốn làm gì hôm nay?</h3>
                
                <div class="C_Dashboard_user_actionsGrid">
                    <!-- 1. Ôn tập -->
                    <a href="C_Ontaphomnay.php" class="C_Dashboard_user_actionCard action-primary">
                        <span class="action-icon">🎯</span>
                        <span class="C_Dashboard_user_actionTitle">Ôn tập ngay</span>
                        <span class="C_Dashboard_user_actionNote"><?php echo $tu_can_on_tap; ?> từ đến hạn</span>
                    </a>

                    <!-- 2. Danh sách chủ đề -->
                    <a href="../main/B_DanhSachChuDe.php" class="C_Dashboard_user_actionCard">
                        <span class="action-icon">📚</span>
                        <span class="C_Dashboard_user_actionTitle">Danh sách chủ đề</span>
                        <span class="C_Dashboard_user_actionNote">Khám phá từ mới</span>
                    </a>

                    <!-- 3. Học FlashCard -->
                    <a href="C_HocFlashcard.php" class="C_Dashboard_user_actionCard">
                        <span class="action-icon">🗂️</span>
                        <span class="C_Dashboard_user_actionTitle">Học FlashCard</span>
                        <span class="C_Dashboard_user_actionNote">Lật thẻ ghi nhớ</span>
                    </a>

                    <!-- 4. Làm Quiz -->
                    <a href="C_Quiz.php" class="C_Dashboard_user_actionCard">
                        <span class="action-icon">📝</span>
                        <span class="C_Dashboard_user_actionTitle">Làm Quiz</span>
                        <span class="C_Dashboard_user_actionNote">Thử thách phản xạ</span>
                    </a>

                    <!-- 5. Quản lý từ vựng -->
                    <a href="C_Tuvungcuatoi.php" class="C_Dashboard_user_actionCard">
                        <span class="action-icon">📖</span>
                        <span class="C_Dashboard_user_actionTitle">Từ vựng của tôi</span>
                        <span class="C_Dashboard_user_actionNote">Thêm & sửa từ</span>
                    </a>

                    <!-- 6. Hồ sơ cá nhân -->
                    <a href="C_Hosocanhan.php" class="C_Dashboard_user_actionCard">
                        <span class="action-icon">👤</span>
                        <span class="C_Dashboard_user_actionTitle">Hồ sơ cá nhân</span>
                        <span class="C_Dashboard_user_actionNote">Xem thành tựu</span>
                    </a>
                </div>
            </section>

            <!-- Lịch sử hoạt động gần đây -->
            <section class="C_Dashboard_user_historySection">
                <div class="section-header-flex">
                    <h3 class="C_Dashboard_user_sectionHeading">Lịch sử học gần đây</h3>
                    <a href="C_Lichsuontap.php" class="link-view-all">Xem tất cả &rarr;</a>
                </div>

                <div class="C_Dashboard_user_historyTable">
                    <?php foreach ($lich_su_gan_day as $item): ?>
                        <div class="C_Dashboard_user_historyRow">
                            <div class="history-left">
                                <span class="history-dot dot-<?php echo $item['loai']; ?>"></span>
                                <span class="C_Dashboard_user_historyText"><?php echo htmlspecialchars($item['hanh_dong']); ?></span>
                            </div>
                            <span class="C_Dashboard_user_historyTime"><?php echo htmlspecialchars($item['thoi_gian']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

        </main>
    </div>

    <script src="../../JS/C_Dashboard_user.js"></script>
</body>
</html>