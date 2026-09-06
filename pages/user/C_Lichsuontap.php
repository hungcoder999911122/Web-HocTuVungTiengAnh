<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// Lấy ID người dùng từ Session
$user_id = $_SESSION['user_id'] 
    ?? $_SESSION['userID'] 
    ?? $_SESSION['id'] 
    ?? $_SESSION['user']['userID'] 
    ?? $_SESSION['user']['id'] 
    ?? 2;

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
            return date('d/m/Y', $time);
        }
    }
}

// Khởi tạo khung biểu đồ 7 ngày mặc định
$du_lieu_bieu_do = [
    ["thu" => "T2", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "T3", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "T4", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "T5", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "T6", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "T7", "so_tu" => 0, "chieu_cao" => "0%"],
    ["thu" => "CN", "so_tu" => 0, "chieu_cao" => "0%"]
];

$danh_sach_lich_su = [];
$tong_tu_tuan = 0; // Biến lưu tổng số từ thực tế của 7 ngày

try {
    if (isset($link) && $link) {
        $tables_res = @mysqli_query($link, "SHOW TABLES");
        $db_tables = [];
        if ($tables_res) {
            while ($tbl_row = mysqli_fetch_array($tables_res)) {
                $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
            }
        }

        $tbl_sessions = isset($db_tables['learning_sessions']) ? "`" . $db_tables['learning_sessions'] . "`" : "`learning_sessions`";
        $tbl_quiz     = isset($db_tables['quiz_results']) ? "`" . $db_tables['quiz_results'] . "`" : "`quiz_results`";
        $tbl_topics   = isset($db_tables['topics']) ? "`" . $db_tables['topics'] . "`" : "`Topics`";

        // --- TÍNH TOÁN DỮ LIỆU BIỂU ĐỒ 7 NGÀY ---
        if (isset($db_tables['learning_sessions'])) {
            $ref_date = date('Y-m-d');
            
            // Tìm ngày học gần nhất
            $stmt_max = @mysqli_prepare($link, "SELECT MAX(session_date) AS max_d FROM $tbl_sessions WHERE user_id = ?");
            if ($stmt_max) {
                mysqli_stmt_bind_param($stmt_max, "i", $user_id);
                mysqli_stmt_execute($stmt_max);
                $res_m = mysqli_stmt_get_result($stmt_max);
                if ($row_m = mysqli_fetch_assoc($res_m)) {
                    if (!empty($row_m['max_d'])) {
                        $this_monday = date('Y-m-d', strtotime('monday this week'));
                        if ($this_monday > $row_m['max_d']) {
                            $ref_date = $row_m['max_d'];
                        }
                    }
                }
                mysqli_stmt_close($stmt_max);
            }

            $monday_ts = strtotime('monday this week', strtotime($ref_date));
            $chart_days = [
                0 => ["thu" => "T2", "date" => date('Y-m-d', $monday_ts), "so_tu" => 0],
                1 => ["thu" => "T3", "date" => date('Y-m-d', strtotime('+1 day', $monday_ts)), "so_tu" => 0],
                2 => ["thu" => "T4", "date" => date('Y-m-d', strtotime('+2 days', $monday_ts)), "so_tu" => 0],
                3 => ["thu" => "T5", "date" => date('Y-m-d', strtotime('+3 days', $monday_ts)), "so_tu" => 0],
                4 => ["thu" => "T6", "date" => date('Y-m-d', strtotime('+4 days', $monday_ts)), "so_tu" => 0],
                5 => ["thu" => "T7", "date" => date('Y-m-d', strtotime('+5 days', $monday_ts)), "so_tu" => 0],
                6 => ["thu" => "CN", "date" => date('Y-m-d', strtotime('+6 days', $monday_ts)), "so_tu" => 0],
            ];

            $start_date = $chart_days[0]['date'];
            $end_date   = $chart_days[6]['date'];

            $sql_chart = "
                SELECT session_date, SUM(words_studied) AS total_words 
                FROM $tbl_sessions 
                WHERE user_id = ? AND session_date BETWEEN ? AND ? 
                GROUP BY session_date
            ";
            if ($stmt = @mysqli_prepare($link, $sql_chart)) {
                mysqli_stmt_bind_param($stmt, "iss", $user_id, $start_date, $end_date);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                $map_words = [];
                while ($row = mysqli_fetch_assoc($res)) {
                    $map_words[$row['session_date']] = (int)$row['total_words'];
                }
                mysqli_stmt_close($stmt);

                $max_words = 0;
                foreach ($chart_days as &$d) {
                    if (isset($map_words[$d['date']])) {
                        $d['so_tu'] = $map_words[$d['date']];
                    }
                    if ($d['so_tu'] > $max_words) {
                        $max_words = $d['so_tu'];
                    }
                }
                unset($d);

                $calculated_chart = [];
                foreach ($chart_days as $d) {
                    $height = "0%";
                    if ($max_words > 0 && $d['so_tu'] > 0) {
                        $percent = round(($d['so_tu'] / $max_words) * 100);
                        $height = max(15, min(100, $percent)) . "%";
                    }
                    $calculated_chart[] = [
                        "thu"       => $d['thu'],
                        "so_tu"     => $d['so_tu'],
                        "chieu_cao" => $height
                    ];
                }
                $du_lieu_bieu_do = $calculated_chart;
            }
        }

        // Tính tổng số từ thực tế của cả tuần
        $tong_tu_tuan = array_sum(array_column($du_lieu_bieu_do, 'so_tu'));

        // --- TRUY VẤN LỊCH SỬ HOẠT ĐỘNG ---
        if (isset($db_tables['quiz_results']) && isset($db_tables['learning_sessions'])) {
            $sql_history = "
                (
                    SELECT 
                        q.id,
                        CONCAT('Làm Quiz \"', COALESCE(t.topicName, 'Tổng hợp'), '\"') AS hoat_dong,
                        'quiz' AS loai,
                        CONCAT(q.correct_answers, '/', q.total_questions) AS ket_qua,
                        COALESCE(q.finished_at, q.started_at) AS thoi_gian_raw
                    FROM $tbl_quiz q
                    LEFT JOIN $tbl_topics t ON q.topic_id = t.topicID
                    WHERE q.user_id = ?
                )
                UNION ALL
                (
                    SELECT 
                        s.id,
                        'Học FlashCard' AS hoat_dong,
                        'flashcard' AS loai,
                        CONCAT(s.words_studied, ' thẻ') AS ket_qua,
                        CAST(CONCAT(s.session_date, ' 12:00:00') AS DATETIME) AS thoi_gian_raw
                    FROM $tbl_sessions s
                    WHERE s.user_id = ? AND s.words_studied > 0
                )
                ORDER BY thoi_gian_raw DESC
                LIMIT 15
            ";

            if ($stmt = @mysqli_prepare($link, $sql_history)) {
                mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
                mysqli_stmt_execute($stmt);
                $res = mysqli_stmt_get_result($stmt);
                $idx = 1;
                while ($row = mysqli_fetch_assoc($res)) {
                    $danh_sach_lich_su[] = [
                        "id"        => $idx++,
                        "hoat_dong" => $row['hoat_dong'],
                        "loai"      => $row['loai'],
                        "ket_qua"   => $row['ket_qua'],
                        "thoi_gian" => dinhDangThoiGian($row['thoi_gian_raw'])
                    ];
                }
                mysqli_stmt_close($stmt);
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi Lịch sử ôn tập: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử ôn tập - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Lichsuontap.css">
</head>
<body class="C_Lichsuontap_body">

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

            <!-- 4. Lịch sử ôn tập (Đang Active) -->
            <a href="C_Lichsuontap.php" class="sidebar-link active">
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
        <header class="C_Lichsuontap_header">
            <h1 class="C_Lichsuontap_logo">Lịch sử ôn tập</h1>
            
            <div class="C_Lichsuontap_filterWrapper">
                <select id="C_Lichsuontap_filterSelect" class="C_Lichsuontap_filterSelect">
                    <option value="7">7 ngày qua</option>
                    <option value="30">30 ngày qua</option>
                    <option value="all">Tất cả thời gian</option>
                </select>
            </div>
        </header>

        <main class="C_Lichsuontap_main">

            <!-- Card Biểu đồ cột -->
            <section class="C_Lichsuontap_chartCard">
                <div class="C_Lichsuontap_chartHeader">
                    <h2 class="C_Lichsuontap_chartTitle">Số từ ôn tập trong 7 ngày qua</h2>
                    <span class="C_Lichsuontap_totalBadge">Tổng: <strong><?php echo $tong_tu_tuan; ?> từ</strong></span>
                </div>
                
                <div class="C_Lichsuontap_chartArea">
                    <div class="C_Lichsuontap_barsContainer">
                        <?php foreach ($du_lieu_bieu_do as $item): ?>
                            <div class="C_Lichsuontap_barGroup">
                                <div class="C_Lichsuontap_barWrapper">
                                    <div class="C_Lichsuontap_bar" 
                                         style="height: <?php echo $item['chieu_cao']; ?>;" 
                                         data-count="<?php echo $item['so_tu']; ?> từ">
                                    </div>
                                </div>
                                <span class="C_Lichsuontap_barLabel"><?php echo $item['thu']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

            <!-- Card Bảng hoạt động gần đây -->
            <section class="C_Lichsuontap_tableCard">
                <div class="C_Lichsuontap_tableHeader">
                    <h2 class="C_Lichsuontap_sectionTitle">Nhật ký hoạt động</h2>
                </div>

                <div class="C_Lichsuontap_tableResponsive">
                    <table class="C_Lichsuontap_table" id="C_Lichsuontap_table">
                        <thead>
                            <tr>
                                <th class="C_Lichsuontap_th">Hoạt động</th>
                                <th class="C_Lichsuontap_th">Kết quả</th>
                                <th class="C_Lichsuontap_th">Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($danh_sach_lich_su)): ?>
                                <tr>
                                    <td colspan="3" style="text-align: center; color: #888; padding: 25px 0;">
                                        Chưa có hoạt động ôn tập hoặc làm bài Quiz nào.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($danh_sach_lich_su as $row): ?>
                                    <tr>
                                        <td class="C_Lichsuontap_td">
                                            <span class="activity-badge activity-<?php echo $row['loai']; ?>">
                                                <?php echo ($row['loai'] === 'quiz') ? 'Quiz' : 'Flashcard'; ?>
                                            </span>
                                            <strong><?php echo htmlspecialchars($row['hoat_dong']); ?></strong>
                                        </td>
                                        <td class="C_Lichsuontap_td">
                                            <span class="result-tag"><?php echo htmlspecialchars($row['ket_qua']); ?></span>
                                        </td>
                                        <td class="C_Lichsuontap_td time-text">
                                            <?php echo htmlspecialchars($row['thoi_gian']); ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    <script src="../../JS/C_Lichsuontap.js"></script>
</body>
</html>