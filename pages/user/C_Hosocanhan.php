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
    ?? 2; // Dự phòng khi mở test link trực tiếp

$thong_bao = "";
$loai_thong_bao = "";

// Khởi tạo mảng thông tin hồ sơ (ưu tiên lấy từ Session của tài khoản đang đăng nhập)
$user_profile = [
    "C_Hosocanhan_full_name" => $_SESSION['full_name'] ?? $_SESSION['user_name'] ?? "",
    "C_Hosocanhan_email"     => $_SESSION['email'] ?? "",
    "C_Hosocanhan_ngay_sinh" => $_SESSION['user_profile_ngay_sinh'] ?? "2002-05-15",
    "C_Hosocanhan_trinh_do"  => $_SESSION['user_profile_trinh_do'] ?? "Trung cấp (B1)"
];

$thanh_tuu = [
    "tong_tu_hoc"     => 0,
    "chuoi_ngay"      => 0,
    "quiz_hoan_thanh" => 0,
    "diem_tb_quiz"    => "0%"
];

try {
    if (isset($link) && $link) {
        // Quét danh sách bảng thực tế tránh lỗi phân biệt hoa/thường trên Docker (Linux)
        $tables_res = @mysqli_query($link, "SHOW TABLES");
        $db_tables = [];
        if ($tables_res) {
            while ($tbl_row = mysqli_fetch_array($tables_res)) {
                $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
            }
        }

        $tbl_users    = isset($db_tables['users']) ? "`" . $db_tables['users'] . "`" : "`Users`";
        $tbl_sessions = isset($db_tables['learning_sessions']) ? "`" . $db_tables['learning_sessions'] . "`" : "`learning_sessions`";
        $tbl_progress = isset($db_tables['user_vocab_progress']) ? "`" . $db_tables['user_vocab_progress'] . "`" : "`user_vocab_progress`";
        $tbl_quiz     = isset($db_tables['quiz_results']) ? "`" . $db_tables['quiz_results'] . "`" : "`quiz_results`";

        // Xác định cột khóa chính của bảng users (userID hoặc id)
        $col_id = 'userID';
        $check_col = @mysqli_query($link, "SHOW COLUMNS FROM $tbl_users LIKE 'userID'");
        if (!$check_col || mysqli_num_rows($check_col) === 0) {
            $col_id = 'id';
        }

        // --- XỬ LÝ KHI NGƯỜI DÙNG BẤM LƯU THAY ĐỔI (POST) ---
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $full_name = trim($_POST["C_Hosocanhan_full_name"] ?? "");
            $email     = trim($_POST["C_Hosocanhan_email"] ?? "");
            $ngay_sinh = trim($_POST["C_Hosocanhan_ngay_sinh"] ?? "");
            $trinh_do  = trim($_POST["C_Hosocanhan_trinh_do"] ?? "");

            if (empty($full_name)) {
                $thong_bao = "Vui lòng nhập họ và tên!";
                $loai_thong_bao = "error";
            } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $thong_bao = "Địa chỉ email không hợp lệ!";
                $loai_thong_bao = "error";
            } else {
                // Kiểm tra xem email mới có bị trùng với tài khoản của người khác không
                $email_trung = false;
                $sql_check_email = "SELECT `$col_id` FROM $tbl_users WHERE email = ? AND `$col_id` != ? LIMIT 1";
                if ($stmt = @mysqli_prepare($link, $sql_check_email)) {
                    mysqli_stmt_bind_param($stmt, "si", $email, $user_id);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        $email_trung = true;
                    }
                    mysqli_stmt_close($stmt);
                }

                if ($email_trung) {
                    $thong_bao = "Email này đã được sử dụng bởi một tài khoản khác!";
                    $loai_thong_bao = "error";
                } else {
                    // Cập nhật thông tin mới vào CSDL
                    $sql_update = "UPDATE $tbl_users SET full_name = ?, email = ? WHERE `$col_id` = ?";
                    if ($stmt = @mysqli_prepare($link, $sql_update)) {
                        mysqli_stmt_bind_param($stmt, "ssi", $full_name, $email, $user_id);
                        if (mysqli_stmt_execute($stmt)) {
                            // Cập nhật đồng bộ sang SESSION để Dashboard đổi theo ngay lập tức
                            $_SESSION['full_name'] = $full_name;
                            $_SESSION['user_name'] = $full_name;
                            $_SESSION['email']     = $email;
                            $_SESSION['user_profile_ngay_sinh'] = $ngay_sinh;
                            $_SESSION['user_profile_trinh_do']  = $trinh_do;

                            $user_profile["C_Hosocanhan_full_name"] = $full_name;
                            $user_profile["C_Hosocanhan_email"]     = $email;
                            $user_profile["C_Hosocanhan_ngay_sinh"] = $ngay_sinh;
                            $user_profile["C_Hosocanhan_trinh_do"]  = $trinh_do;

                            $thong_bao = "Cập nhật thông tin hồ sơ thành công!";
                            $loai_thong_bao = "success";
                        } else {
                            $thong_bao = "Có lỗi xảy ra khi lưu thông tin vào cơ sở dữ liệu!";
                            $loai_thong_bao = "error";
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
            }
        }

        // --- LẤY THÔNG TIN HỒ SƠ THẬT TỪ CSDL CỦA USER ĐANG ĐĂNG NHẬP ---
        if (isset($db_tables['users']) && ($_SERVER["REQUEST_METHOD"] !== "POST" || $loai_thong_bao === "error")) {
            $sql_user = "SELECT full_name, email FROM $tbl_users WHERE `$col_id` = ? LIMIT 1";
            if ($stmt = @mysqli_prepare($link, $sql_user)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $user_profile["C_Hosocanhan_full_name"] = $row['full_name'] ?? $user_profile["C_Hosocanhan_full_name"];
                    $user_profile["C_Hosocanhan_email"]     = $row['email'] ?? $user_profile["C_Hosocanhan_email"];
                }
                mysqli_stmt_close($stmt);
            }
        }

        // --- TÍNH TOÁN CÁC CHỈ SỐ THÀNH TỰU THẬT CỦA TÀI KHOẢN ---
        // Tổng từ vựng đã học
        if (isset($db_tables['user_vocab_progress'])) {
            $sql_total = "SELECT COUNT(*) AS total FROM $tbl_progress WHERE user_id = ? AND status IN ('learning', 'mastered')";
            if ($stmt = @mysqli_prepare($link, $sql_total)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $thanh_tuu["tong_tu_hoc"] = (int)($row['total'] ?? 0);
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Chuỗi ngày học (Streak)
        if (isset($db_tables['learning_sessions'])) {
            $sql_streak = "SELECT streak_count FROM $tbl_sessions WHERE user_id = ? ORDER BY session_date DESC, id DESC LIMIT 1";
            if ($stmt = @mysqli_prepare($link, $sql_streak)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $thanh_tuu["chuoi_ngay"] = (int)($row['streak_count'] ?? 0);
                }
                mysqli_stmt_close($stmt);
            }
        }

        // Tổng số bài Quiz đã hoàn thành & Điểm Quiz trung bình
        if (isset($db_tables['quiz_results'])) {
            $sql_quiz = "SELECT COUNT(*) AS total_quiz, AVG(score) AS avg_score FROM $tbl_quiz WHERE user_id = ?";
            if ($stmt = @mysqli_prepare($link, $sql_quiz)) {
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($res)) {
                    $thanh_tuu["quiz_hoan_thanh"] = (int)($row['total_quiz'] ?? 0);
                    $thanh_tuu["diem_tb_quiz"]    = $row['avg_score'] !== null ? round($row['avg_score']) . "%" : "0%";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi Hồ sơ cá nhân: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Hosocanhan.css">
</head>
<body class="C_Hosocanhan_body">

    <!-- =========================================
         SIDEBAR
         ========================================= -->
    <aside class="sidebar">
        <!-- Logo -->
        <a href="C_Dashboard_user.php" class="sidebar-logo">
            <span class="logo-badge">🌿</span> LexiLoop
        </a>

        <!-- Danh sách menu -->
        <nav class="sidebar-nav">
            <!-- 1. Dashboard -->
            <a href="C_Dashboard_user.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
                </span>
                <span>Dashboard</span>
            </a>

            <!-- 2. Danh sách chủ đề -->
            <a href="B_DanhSachChuDe.php" class="sidebar-link">
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

            <!-- 5. Hồ sơ (Đang active) -->
            <a href="C_Hosocanhan.php" class="sidebar-link active">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </span>
                <span>Hồ sơ</span>
            </a>

            <!-- 6. Cài đặt -->
            <a href="CaiDat.php" class="sidebar-link">
                <span class="sidebar-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                </span>
                <span>Cài đặt</span>
            </a>
        </nav>
    </aside>

    <!-- =========================================
         KHU VỰC NỘI DUNG CHÍNH
         ========================================= -->
    <div class="page-content">

        <header class="C_Hosocanhan_header">
            <h1 class="C_Hosocanhan_logo">Hồ sơ cá nhân</h1>
        </header>

        <main class="C_Hosocanhan_main">

            <?php if (!empty($thong_bao)): ?>
                <div class="C_Hosocanhan_alert C_Hosocanhan_alert_<?php echo $loai_thong_bao; ?>" id="C_Hosocanhan_alert">
                    <?php echo htmlspecialchars($thong_bao); ?>
                </div>
            <?php endif; ?>

            <!-- Thông tin cá nhân -->
            <section class="C_Hosocanhan_profileSection">
                
                <div class="C_Hosocanhan_avatarWrapper">
                    <div class="C_Hosocanhan_avatarCircle" id="C_Hosocanhan_avatarCircle">
                        <span id="C_Hosocanhan_avatarInitials">NA</span>
                        <img id="C_Hosocanhan_avatarPreview" src="" alt="Avatar" style="display:none;">
                    </div>
                    <input type="file" id="C_Hosocanhan_fileInput" name="C_Hosocanhan_avatar_file" accept="image/*" style="display:none;">
                    <button type="button" id="C_Hosocanhan_btnDoiAnh" class="C_Hosocanhan_btnAvatar">
                        Đổi ảnh đại diện
                    </button>
                </div>

                <form id="C_Hosocanhan_formThongTin" class="C_Hosocanhan_form" action="C_Hosocanhan.php" method="POST" enctype="multipart/form-data">
                    <div class="C_Hosocanhan_formGroup">
                        <label for="C_Hosocanhan_full_name" class="C_Hosocanhan_label">Họ tên</label>
                        <input 
                            type="text" 
                            id="C_Hosocanhan_full_name" 
                            name="C_Hosocanhan_full_name" 
                            class="C_Hosocanhan_input" 
                            maxlength="100" 
                            value="<?php echo htmlspecialchars($user_profile['C_Hosocanhan_full_name']); ?>"
                            required>
                    </div>

                    <div class="C_Hosocanhan_formGroup">
                        <label for="C_Hosocanhan_email" class="C_Hosocanhan_label">Email</label>
                        <input 
                            type="email" 
                            id="C_Hosocanhan_email" 
                            name="C_Hosocanhan_email" 
                            class="C_Hosocanhan_input" 
                            maxlength="50" 
                            value="<?php echo htmlspecialchars($user_profile['C_Hosocanhan_email']); ?>"
                            required>
                    </div>

                    <div class="C_Hosocanhan_formRow">
                        <div class="C_Hosocanhan_formGroup">
                            <label for="C_Hosocanhan_ngay_sinh" class="C_Hosocanhan_label">Ngày sinh</label>
                            <input 
                                type="date" 
                                id="C_Hosocanhan_ngay_sinh" 
                                name="C_Hosocanhan_ngay_sinh" 
                                class="C_Hosocanhan_input"
                                value="<?php echo htmlspecialchars($user_profile['C_Hosocanhan_ngay_sinh']); ?>">
                        </div>

                        <div class="C_Hosocanhan_formGroup">
                            <label for="C_Hosocanhan_trinh_do" class="C_Hosocanhan_label">Trình độ</label>
                            <input 
                                type="text" 
                                id="C_Hosocanhan_trinh_do" 
                                name="C_Hosocanhan_trinh_do" 
                                class="C_Hosocanhan_input"
                                placeholder="VD: B1, B2..."
                                value="<?php echo htmlspecialchars($user_profile['C_Hosocanhan_trinh_do']); ?>">
                        </div>
                    </div>

                    <button type="submit" id="C_Hosocanhan_btnCapNhat" class="C_Hosocanhan_btnSubmit">
                        Cập nhật hồ sơ
                    </button>
                </form>

            </section>

            <div class="C_Hosocanhan_spacer"></div>

            <!-- Thành tựu học tập -->
            <section class="C_Hosocanhan_achievementSection">
                <h2 class="C_Hosocanhan_sectionTitle">Thành tựu học tập</h2>

                <div class="C_Hosocanhan_achievementGrid">
                    <div class="C_Hosocanhan_card">
                        <span class="C_Hosocanhan_cardLabel">Tổng từ đã học</span>
                        <span class="C_Hosocanhan_cardValue"><?php echo $thanh_tuu['tong_tu_hoc']; ?></span>
                    </div>

                    <div class="C_Hosocanhan_card">
                        <span class="C_Hosocanhan_cardLabel">Chuỗi ngày học</span>
                        <span class="C_Hosocanhan_cardValue"><?php echo $thanh_tuu['chuoi_ngay']; ?></span>
                    </div>

                    <div class="C_Hosocanhan_card">
                        <span class="C_Hosocanhan_cardLabel">Quiz đã hoàn thành</span>
                        <span class="C_Hosocanhan_cardValue"><?php echo $thanh_tuu['quiz_hoan_thanh']; ?></span>
                    </div>

                    <div class="C_Hosocanhan_card">
                        <span class="C_Hosocanhan_cardLabel">Điểm TB Quiz</span>
                        <span class="C_Hosocanhan_cardValue"><?php echo $thanh_tuu['diem_tb_quiz']; ?></span>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <script src="../../JS/C_Hosocanhan.js"></script>
</body>
</html>