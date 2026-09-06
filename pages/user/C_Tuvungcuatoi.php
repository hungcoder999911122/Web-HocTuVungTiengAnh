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

$thong_bao = "";
$loai_thong_bao = "";
$danh_sach_tu = [];

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
    <link rel="stylesheet" href="../../CSS/C_Tuvungcuatoi.css">
</head>
<body class="C_Tuvungcuatoi_body">

    <!-- =========================================
         SIDEBAR
         ========================================= -->
    <aside class="sidebar">
        <!-- Logo -->
        <a href="C_Dashboard_user.php" class="sidebar-logo">
            <span class="logo-badge">🌱</span> LexiLoop
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

            <!-- 3. Từ vựng của tôi (Đang Active) -->
            <a href="C_Tuvungcuatoi.php" class="sidebar-link active">
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

        <!-- Header -->
        <header class="C_Tuvungcuatoi_header">
            <h1 class="C_Tuvungcuatoi_logo">Từ vựng của tôi</h1>
            <button type="button" id="C_Tuvungcuatoi_btnThemTu" class="C_Tuvungcuatoi_btnAddTop">
                + Thêm từ mới
            </button>
        </header>

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

    <script src="../../JS/C_Tuvungcuatoi.js"></script>
</body>
</html>