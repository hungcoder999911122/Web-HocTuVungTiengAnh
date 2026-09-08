<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");
// auth_guard.php đã xác thực session trước khi trang sử dụng user_id.
$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $isLoggedIn ? (int) $_SESSION['user_id'] : null;

$thong_bao = "";
$loai_thong_bao = "";
$danh_sach_tu = [];

try {
    if ($isLoggedIn && isset($link) && $link) {
        // Quét danh sách bảng thực tế tránh lỗi phân biệt hoa/thường trên Linux
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

        // =========================================================================
        // [XỬ LÝ THÊM TỪ VỰNG KHI SUBMIT FORM (POST)]
        // =========================================================================
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $tu_vung = trim($_POST["C_Tuvungcuatoi_txtTuVung"] ?? "");
            $nghia   = trim($_POST["C_Tuvungcuatoi_txtNghia"] ?? "");
            $chu_de  = trim($_POST["C_Tuvungcuatoi_txtChuDe"] ?? "");

            if (empty($tu_vung) || empty($nghia) || empty($chu_de)) {
                $thong_bao = "Vui lòng điền đầy đủ từ vựng, nghĩa và chủ đề!";
                $loai_thong_bao = "error";
            } else {
                $is_duplicate = false;
                $sql_check = "SELECT id FROM $tbl_vocab WHERE LOWER(word) = LOWER(?) LIMIT 1";
                if ($stmt_chk = @mysqli_prepare($link, $sql_check)) {
                    mysqli_stmt_bind_param($stmt_chk, "s", $tu_vung);
                    mysqli_stmt_execute($stmt_chk);
                    mysqli_stmt_store_result($stmt_chk);
                    if (mysqli_stmt_num_rows($stmt_chk) > 0) {
                        $is_duplicate = true;
                    }
                    mysqli_stmt_close($stmt_chk);
                }

                if ($is_duplicate) {
                    $thong_bao = "Từ vựng \"$tu_vung\" đã tồn tại trong danh sách từ vựng!";
                    $loai_thong_bao = "error";
                } else {
                    $topic_id = 1;
                    if (is_numeric($chu_de)) {
                        $topic_id = intval($chu_de);
                    } elseif (isset($db_tables['topics'])) {
                        $stmt_top = @mysqli_prepare($link, "SELECT topicID FROM $tbl_topics WHERE LOWER(topicName) = LOWER(?) LIMIT 1");
                        if ($stmt_top) {
                            mysqli_stmt_bind_param($stmt_top, "s", $chu_de);
                            mysqli_stmt_execute($stmt_top);
                            $res_top = mysqli_stmt_get_result($stmt_top);
                            if ($row_top = mysqli_fetch_assoc($res_top)) {
                                $topic_id = (int)$row_top['topicID'];
                            } else {
                                $stmt_ins_top = @mysqli_prepare($link, "INSERT INTO $tbl_topics (topicName, created_by) VALUES (?, ?)");
                                if ($stmt_ins_top) {
                                    mysqli_stmt_bind_param($stmt_ins_top, "si", $chu_de, $user_id);
                                    mysqli_stmt_execute($stmt_ins_top);
                                    $topic_id = mysqli_insert_id($link);
                                    mysqli_stmt_close($stmt_ins_top);
                                }
                            }
                            mysqli_stmt_close($stmt_top);
                        }
                    }

                    $sql_insert = "INSERT INTO $tbl_vocab (word, meaning, topic_id, created_by) VALUES (?, ?, ?, ?)";
                    if ($stmt_ins = @mysqli_prepare($link, $sql_insert)) {
                        mysqli_stmt_bind_param($stmt_ins, "ssii", $tu_vung, $nghia, $topic_id, $user_id);
                        if (mysqli_stmt_execute($stmt_ins)) {
                            $new_vocab_id = mysqli_insert_id($link);

                            if (isset($db_tables['user_vocab_progress']) && $new_vocab_id > 0) {
                                $stmt_prog = @mysqli_prepare($link, "INSERT INTO $tbl_progress (user_id, vocabulary_id, status, next_review_date) VALUES (?, ?, 'new', CURDATE())");
                                if ($stmt_prog) {
                                    mysqli_stmt_bind_param($stmt_prog, "ii", $user_id, $new_vocab_id);
                                    mysqli_stmt_execute($stmt_prog);
                                    mysqli_stmt_close($stmt_prog);
                                }
                            }

                            $thong_bao = "Thêm từ vựng \"$tu_vung\" thành công!";
                            $loai_thong_bao = "success";
                        } else {
                            $thong_bao = "Có lỗi xảy ra khi lưu từ vựng vào CSDL!";
                            $loai_thong_bao = "error";
                        }
                        mysqli_stmt_close($stmt_ins);
                    }
                }
            }
        }

        // =========================================================================
        // [TRUY VẤN DANH SÁCH TỪ VỰNG CỦA TÔI]
        // =========================================================================
        if (isset($db_tables['vocabulary'])) {
            $sql_list = "
                SELECT 
                    v.id,
                    v.word AS tu_vung,
                    v.meaning AS nghia,
                    COALESCE(t.topicName, 'Chung') AS chu_de,
                    COALESCE(p.status, 'new') AS db_status,
                    p.next_review_date
                FROM $tbl_vocab v
                LEFT JOIN $tbl_topics t ON v.topic_id = t.topicID
                LEFT JOIN $tbl_progress p ON v.id = p.vocabulary_id AND p.user_id = ?
                WHERE v.created_by = ? OR p.user_id = ?
                ORDER BY v.id DESC
            ";

            if ($stmt_l = @mysqli_prepare($link, $sql_list)) {
                mysqli_stmt_bind_param($stmt_l, "iii", $user_id, $user_id, $user_id);
                mysqli_stmt_execute($stmt_l);
                $res_l = mysqli_stmt_get_result($stmt_l);
                $today = date('Y-m-d');
                while ($row = mysqli_fetch_assoc($res_l)) {
                    $muc_do = "moi";
                    if ($row['db_status'] === 'mastered') {
                        $muc_do = "tot";
                    } elseif ($row['db_status'] === 'learning' || (!empty($row['next_review_date']) && $row['next_review_date'] <= $today)) {
                        $muc_do = "can_on_tap";
                    }

                    $danh_sach_tu[] = [
                        "id"         => (int)$row['id'],
                        "tu_vung"    => $row['tu_vung'],
                        "nghia"      => $row['nghia'],
                        "chu_de"     => $row['chu_de'],
                        "muc_do_nho" => $muc_do
                    ];
                }
                mysqli_stmt_close($stmt_l);
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi Từ vựng của tôi: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Từ vựng của tôi - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/C_Tuvungcuatoi.css">
    <link rel="stylesheet" href="../../CSS/topheader.css">
</head>

<body class="C_Tuvungcuatoi_body">

    <!-- =========================================
         SIDEBAR
         ========================================= -->
    <!-- Sidebar dùng chung cho mọi trang người dùng -->
    <?php
    if ($isLoggedIn) {
        include '../../includes/sidebar_user.php';
    } else {
        include '../../includes/sidebar_guest.php';
    }
    ?>

    <!-- =========================================
         KHU VỰC NỘI DUNG CHÍNH
         ========================================= -->
    <div class="page-content">

        <!-- Header -->
        <?php
        $headerTitle = 'Từ vựng của tôi';
        $topHeaderPageActions = '';

        /*
            ob_start() tạm giữ HTML nút thêm từ.
            Sau đó nội dung được truyền vào vị trí action của topheader.
        */
        if ($isLoggedIn) {
            ob_start();
        ?>
            <button
                type="button"
                id="C_Tuvungcuatoi_btnThemTu"
                class="top-header-page-action top-header-page-action--primary C_Tuvungcuatoi_btnAddTop">
                + Thêm từ mới
            </button>

        <?php

            $topHeaderPageActions = ob_get_clean();
        }
        include '../../includes/topheader.php';
        ?>

        <?php if (!$isLoggedIn): ?>
            <section class="guest-preview-card">
                <span class="guest-preview-card__icon" aria-hidden="true">📊</span>

                <div class="guest-preview-card__content">
                    <h2>Từ vựng cá nhân</h2>

                    <p>
                        Bạn đang chưa đăng nhập. Đăng nhập để theo dõi
                        số từ đã học, lịch sử Flashcard và kết quả Quiz của riêng bạn.
                    </p>

                    <ul class="guest-preview-card__benefits">
                        <li>Xem tiến độ học theo ngày</li>
                        <li>Theo dõi lịch sử Flashcard và Quiz</li>
                        <li>Đánh giá thói quen học tập cá nhân</li>
                    </ul>

                    <a
                        href="../auth/A_DangNhap.php"
                        class="guest-preview-card__login">
                        Đăng nhập
                    </a>
                </div>
            </section>
        <?php endif; ?>
        <!-- MAIN -->
        <main class="C_Tuvungcuatoi_main">

            <!-- Thông báo phản hồi PHP -->
            <?php if (!empty($thong_bao)): ?>
                <div class="C_Tuvungcuatoi_alert C_Tuvungcuatoi_alert_<?php echo $loai_thong_bao; ?>" id="C_Tuvungcuatoi_alert">
                    <?php echo htmlspecialchars($thong_bao); ?>
                </div>
            <?php endif; ?>

            <!-- ====================================================
                 BỐ CỤC 2 BÊN
                 ==================================================== -->
            <div class="C_Tuvungcuatoi_layout2Col">

                <!-- TÌM KIẾM & BẢNG TỪ VỰNG -->
                <div class="C_Tuvungcuatoi_colLeft">
                    <!-- Thanh lọc & tìm kiếm -->
                    <section class="C_Tuvungcuatoi_filterBar">
                        <div class="C_Tuvungcuatoi_searchWrapper">
                            <input
                                type="text"
                                id="C_Tuvungcuatoi_txtTimKiem"
                                class="C_Tuvungcuatoi_inputSearch"
                                placeholder="Tìm kiếm từ vựng, nghĩa...">
                        </div>

                        <select id="C_Tuvungcuatoi_selChuDe" class="C_Tuvungcuatoi_selectFilter">
                            <option value="">Chủ đề: Tất cả</option>
                            <option value="Du lịch">Du lịch</option>
                            <option value="Công nghệ">Công nghệ</option>
                            <option value="Ẩm thực">Ẩm thực</option>
                        </select>

                        <select id="C_Tuvungcuatoi_selTrangThai" class="C_Tuvungcuatoi_selectFilter">
                            <option value="">Mức độ: Tất cả</option>
                            <option value="tot">Tốt</option>
                            <option value="can_on_tap">Cần ôn tập</option>
                            <option value="moi">Mới</option>
                        </select>
                    </section>

                    <!-- Bảng danh sách từ vựng -->
                    <section class="C_Tuvungcuatoi_tableCard">
                        <div class="C_Tuvungcuatoi_tableResponsive">
                            <table class="C_Tuvungcuatoi_table" id="C_Tuvungcuatoi_table">
                                <thead>
                                    <tr>
                                        <th class="C_Tuvungcuatoi_th">Từ vựng</th>
                                        <th class="C_Tuvungcuatoi_th">Nghĩa</th>
                                        <th class="C_Tuvungcuatoi_th">Chủ đề</th>
                                        <th class="C_Tuvungcuatoi_th">Mức độ nhớ</th>
                                        <th class="C_Tuvungcuatoi_th" style="text-align: center;">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($danh_sach_tu as $item): ?>
                                        <tr data-id="<?php echo $item['id']; ?>" data-status="<?php echo $item['muc_do_nho']; ?>">
                                            <td class="C_Tuvungcuatoi_td font-bold"><?php echo htmlspecialchars($item['tu_vung']); ?></td>
                                            <td class="C_Tuvungcuatoi_td"><?php echo htmlspecialchars($item['nghia']); ?></td>
                                            <td class="C_Tuvungcuatoi_td">
                                                <span class="topic-tag"><?php echo htmlspecialchars($item['chu_de']); ?></span>
                                            </td>
                                            <td class="C_Tuvungcuatoi_td">
                                                <?php if ($item['muc_do_nho'] === 'tot'): ?>
                                                    <span class="badge-status badge-tot">Tốt</span>
                                                <?php elseif ($item['muc_do_nho'] === 'can_on_tap'): ?>
                                                    <span class="badge-status badge-on-tap">Cần ôn tập</span>
                                                <?php else: ?>
                                                    <span class="badge-status badge-moi">Mới</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="C_Tuvungcuatoi_td text-center">
                                                <button type="button" class="C_Tuvungcuatoi_btnTableAction btn-edit">Sửa</button>
                                                <button type="button" class="C_Tuvungcuatoi_btnTableAction btn-delete">Xóa</button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- FORM THÊM / SỬA TỪ VỰNG -->
                <div class="C_Tuvungcuatoi_colRight">
                    <section class="C_Tuvungcuatoi_formModalContainer" id="C_Tuvungcuatoi_formContainer">
                        <div class="C_Tuvungcuatoi_formCard">
                            <h2 class="C_Tuvungcuatoi_formTitle" id="C_Tuvungcuatoi_formTitle">Thêm từ vựng mới</h2>

                            <form id="C_Tuvungcuatoi_formThemTu" action="C_Tuvungcuatoi.php" method="POST">
                                <input type="hidden" id="C_Tuvungcuatoi_editId" name="C_Tuvungcuatoi_editId" value="">

                                <div class="C_Tuvungcuatoi_formRow">
                                    <div class="C_Tuvungcuatoi_formGroup">
                                        <label for="C_Tuvungcuatoi_txtTuVung" class="C_Tuvungcuatoi_label">Từ vựng</label>
                                        <input
                                            type="text"
                                            id="C_Tuvungcuatoi_txtTuVung"
                                            name="C_Tuvungcuatoi_txtTuVung"
                                            class="C_Tuvungcuatoi_input"
                                            maxlength="100"
                                            placeholder="VD: Airport"
                                            required>
                                    </div>
                                    <div class="C_Tuvungcuatoi_formGroup">
                                        <label for="C_Tuvungcuatoi_txtNghia" class="C_Tuvungcuatoi_label">Nghĩa tiếng Việt</label>
                                        <input
                                            type="text"
                                            id="C_Tuvungcuatoi_txtNghia"
                                            name="C_Tuvungcuatoi_txtNghia"
                                            class="C_Tuvungcuatoi_input"
                                            placeholder="VD: Sân bay"
                                            required>
                                    </div>
                                </div>

                                <div class="C_Tuvungcuatoi_formGroup">
                                    <label for="C_Tuvungcuatoi_txtChuDe" class="C_Tuvungcuatoi_label">Chủ đề</label>
                                    <input
                                        type="text"
                                        id="C_Tuvungcuatoi_txtChuDe"
                                        name="C_Tuvungcuatoi_txtChuDe"
                                        class="C_Tuvungcuatoi_input"
                                        maxlength="100"
                                        placeholder="VD: Du lịch, Công nghệ..."
                                        required>
                                </div>

                                <div class="C_Tuvungcuatoi_formButtons">
                                    <button type="button" id="C_Tuvungcuatoi_btnHuy" class="C_Tuvungcuatoi_btnCancel">
                                        Hủy bỏ
                                    </button>
                                    <button type="submit" id="C_Tuvungcuatoi_btnLuu" class="C_Tuvungcuatoi_btnSave">
                                        Lưu lại
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

            </div>

        </main>
    </div>

    <script src="../../JS/jquery-4.0.0.min.js"></script>
    <script src="../../JS/C_Tuvungcuatoi.js"></script>
    <script src="../../JS/auth.js"></script>
</body>

</html>