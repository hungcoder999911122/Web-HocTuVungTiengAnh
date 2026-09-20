<?php
require_once '../../includes/auth_guard.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// auth_guard.php đã xác thực session trước khi trang sử dụng user_id.
$user_id = (int) $_SESSION['user_id'];

// ---------------------------------------------------------
// ẢNH ĐẠI DIỆN
// Ảnh được lưu trong assets/images/avatars/, đường dẫn lưu ở Users.avatar_url.
// ---------------------------------------------------------
const AVATAR_URL_DIR   = '/assets/images/avatars/';
const AVATAR_MAX_BYTES = 2 * 1024 * 1024; // 2MB

/**
 * Kiểm tra và lưu ảnh người dùng vừa chọn.
 * Trả về ['url' => đường dẫn|null, 'error' => thông báo|null].
 * Không chọn ảnh mới thì url = null và error = null.
 */
function xu_ly_anh_dai_dien(int $user_id, array $file): array
{
    $code = $file['error'] ?? UPLOAD_ERR_NO_FILE;
    if ($code === UPLOAD_ERR_NO_FILE) {
        return ['url' => null, 'error' => null];
    }
    if ($code === UPLOAD_ERR_INI_SIZE || $code === UPLOAD_ERR_FORM_SIZE) {
        return ['url' => null, 'error' => 'Ảnh quá lớn, vui lòng chọn ảnh tối đa 2MB.'];
    }
    if ($code !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
        return ['url' => null, 'error' => 'Tải ảnh lên thất bại, vui lòng thử lại.'];
    }
    if ($file['size'] > AVATAR_MAX_BYTES) {
        return ['url' => null, 'error' => 'Ảnh quá lớn, vui lòng chọn ảnh tối đa 2MB.'];
    }

    // Kiểm tra nội dung thật của file, không tin vào đuôi file hay MIME do trình duyệt gửi.
    $allowed = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
    $info = @getimagesize($file['tmp_name']);
    if (!$info || !isset($allowed[$info[2]])) {
        return ['url' => null, 'error' => 'Chỉ chấp nhận ảnh định dạng JPG, PNG, GIF hoặc WebP.'];
    }

    $dir = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . AVATAR_URL_DIR;
    if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
        return ['url' => null, 'error' => 'Không tạo được thư mục lưu ảnh trên máy chủ.'];
    }

    $name = 'user_' . $user_id . '_' . bin2hex(random_bytes(8)) . '.' . $allowed[$info[2]];
    if (!move_uploaded_file($file['tmp_name'], $dir . $name)) {
        return ['url' => null, 'error' => 'Không lưu được ảnh. Hãy kiểm tra quyền ghi của thư mục assets/images/avatars.'];
    }

    return ['url' => AVATAR_URL_DIR . $name, 'error' => null];
}

/** Xóa ảnh cũ, chỉ với ảnh do chức năng này tạo ra (tên bắt đầu bằng user_). */
function xoa_anh_dai_dien_cu(string $url): void
{
    if (strpos($url, AVATAR_URL_DIR . 'user_') !== 0) {
        return;
    }
    $path = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') . AVATAR_URL_DIR . basename($url);
    if (is_file($path)) {
        @unlink($path);
    }
}

function lay_avatar_hien_tai(mysqli $link, string $tbl_users, string $col_id, int $user_id): string
{
    $avatar = '';
    if ($stmt = @mysqli_prepare($link, "SELECT avatar_url FROM $tbl_users WHERE `$col_id` = ? LIMIT 1")) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($res)) {
            $avatar = (string) ($row['avatar_url'] ?? '');
        }
        mysqli_stmt_close($stmt);
    }
    return $avatar;
}

/** Chữ cái đầu của tối đa 2 từ đầu trong họ tên (giống avatar chữ ở header). */
function lay_chu_cai_dau(string $name): string
{
    $words = preg_split('/\s+/u', trim($name), -1, PREG_SPLIT_NO_EMPTY);
    if (!$words) {
        return '?';
    }
    $initials = '';
    foreach (array_slice($words, 0, 2) as $word) {
        // Lấy 1 ký tự UTF-8 đầu tiên; không phụ thuộc extension mbstring.
        $char = preg_match('/^./us', $word, $m) ? $m[0] : '';
        $initials .= function_exists('mb_strtoupper') ? mb_strtoupper($char, 'UTF-8') : strtoupper($char);
    }
    return $initials;
}

$avatar_url = '';

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

                // Chỉ xử lý ảnh khi email hợp lệ để không lưu file thừa.
                $avatar_result = $email_trung
                    ? ['url' => null, 'error' => null]
                    : xu_ly_anh_dai_dien($user_id, $_FILES['C_Hosocanhan_avatar_file'] ?? []);

                if ($email_trung) {
                    $thong_bao = "Email này đã được sử dụng bởi một tài khoản khác!";
                    $loai_thong_bao = "error";
                } elseif ($avatar_result['error'] !== null) {
                    $thong_bao = $avatar_result['error'];
                    $loai_thong_bao = "error";
                } else {
                    $avatar_moi = $avatar_result['url'];
                    $avatar_cu  = $avatar_moi !== null
                        ? lay_avatar_hien_tai($link, $tbl_users, $col_id, $user_id)
                        : '';

                    // Cập nhật thông tin mới vào CSDL (kèm ảnh đại diện nếu có ảnh mới)
                    $sql_update = $avatar_moi !== null
                        ? "UPDATE $tbl_users SET full_name = ?, email = ?, avatar_url = ? WHERE `$col_id` = ?"
                        : "UPDATE $tbl_users SET full_name = ?, email = ? WHERE `$col_id` = ?";
                    if ($stmt = @mysqli_prepare($link, $sql_update)) {
                        if ($avatar_moi !== null) {
                            mysqli_stmt_bind_param($stmt, "sssi", $full_name, $email, $avatar_moi, $user_id);
                        } else {
                            mysqli_stmt_bind_param($stmt, "ssi", $full_name, $email, $user_id);
                        }
                        if (mysqli_stmt_execute($stmt)) {
                            if ($avatar_moi !== null) {
                                xoa_anh_dai_dien_cu($avatar_cu);
                                $_SESSION['avatar_url'] = $avatar_moi;
                            }
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
                            if ($avatar_moi !== null) {
                                xoa_anh_dai_dien_cu($avatar_moi);
                            }
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

        // --- ẢNH ĐẠI DIỆN HIỆN TẠI ---
        if (isset($db_tables['users'])) {
            $avatar_url = lay_avatar_hien_tai($link, $tbl_users, $col_id, $user_id);
            $_SESSION['avatar_url'] = $avatar_url;
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
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/C_Hosocanhan.css">
    <link rel="stylesheet" href="../../CSS/topheader.css">
</head>

<body class="C_Hosocanhan_body">

    <!-- =========================================
         SIDEBAR
         ========================================= -->
    <!-- Sidebar dùng chung cho mọi trang người dùng -->
    <?php include '../../includes/sidebar_user.php'; ?>

    <!-- =========================================
         KHU VỰC NỘI DUNG CHÍNH
         ========================================= -->
    <div class="page-content">
        
        <!-- HEADER -->
        <?php
        $headerTitle = 'Hồ sơ cá nhân';
        $topHeaderPageActions = '';

        include '../../includes/topheader.php';
        ?>

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
                        <span id="C_Hosocanhan_avatarInitials"<?php echo $avatar_url !== '' ? ' style="display:none;"' : ''; ?>><?php echo htmlspecialchars(lay_chu_cai_dau($user_profile['C_Hosocanhan_full_name'])); ?></span>
                        <img id="C_Hosocanhan_avatarPreview" src="<?php echo htmlspecialchars($avatar_url); ?>" alt="Avatar" style="display:<?php echo $avatar_url !== '' ? 'block' : 'none'; ?>;">
                    </div>
                    <!-- form="..." gắn ô chọn ảnh vào form bên dưới (ô này nằm ngoài thẻ <form>), nếu không file sẽ không được gửi lên -->
                    <input type="file" id="C_Hosocanhan_fileInput" name="C_Hosocanhan_avatar_file" form="C_Hosocanhan_formThongTin" accept="image/jpeg,image/png,image/gif,image/webp" style="display:none;">
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
    <script src="../../JS/jquery-4.0.0.min.js"></script>
    <script src="../../JS/C_Hosocanhan.js"></script>
    <script src="../../JS/auth.js"></script>
</body>

</html>