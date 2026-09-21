<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../Connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id']) && !isset($_SESSION['userID'])) {
    header("Location: ../auth/A_DangNhap.php");
    exit;
}

$user_id = $_SESSION['user_id'] ?? $_SESSION['userID'] ?? 3;

// ====================================================================
// 1. THỐNG KÊ LỊCH HẸN ÔN TẬP
// ====================================================================
// Hôm nay / Quá hạn cần ôn
$sql_due_today = "SELECT COUNT(*) AS total FROM user_vocab_progress WHERE user_id = ? AND next_review_date <= CURDATE() AND level < 5";
$stmt = mysqli_prepare($link, $sql_due_today);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$count_today = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;
mysqli_stmt_close($stmt);

// Hẹn ngày mai
$sql_tomorrow = "SELECT COUNT(*) AS total FROM user_vocab_progress WHERE user_id = ? AND next_review_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY) AND level < 5";
$stmt = mysqli_prepare($link, $sql_tomorrow);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$count_tomorrow = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;
mysqli_stmt_close($stmt);

// Trong 7 ngày tới (không tính hôm nay và ngày mai)
$sql_upcoming = "SELECT COUNT(*) AS total FROM user_vocab_progress WHERE user_id = ? AND next_review_date BETWEEN DATE_ADD(CURDATE(), INTERVAL 2 DAY) AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND level < 5";
$stmt = mysqli_prepare($link, $sql_upcoming);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$count_upcoming = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;
mysqli_stmt_close($stmt);

// Đã Mastered (Level 5)
$sql_mastered = "SELECT COUNT(*) AS total FROM user_vocab_progress WHERE user_id = ? AND (level >= 5 OR status = 'mastered')";
$stmt = mysqli_prepare($link, $sql_mastered);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$count_mastered = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt))['total'] ?? 0;
mysqli_stmt_close($stmt);

// ====================================================================
// 2. LẤY DANH SÁCH TỪ VỰNG KÈM LỊCH HẸN
// ====================================================================
$filter_level = isset($_GET['level']) ? intval($_GET['level']) : 0;
$where_level = ($filter_level > 0) ? "AND p.level = $filter_level" : "";

$sql_list = "
    SELECT 
        v.id,
        v.word,
        v.meaning,
        v.pronunciation,
        v.part_of_speech,
        t.topicName,
        p.level,
        p.next_review_date,
        p.last_reviewed_at,
        DATEDIFF(p.next_review_date, CURDATE()) AS days_diff
    FROM user_vocab_progress p
    JOIN vocabulary v ON p.vocabulary_id = v.id
    LEFT JOIN Topics t ON v.topic_id = t.topicID
    WHERE p.user_id = ? $where_level
    ORDER BY p.next_review_date ASC, p.level ASC
";
$stmt = mysqli_prepare($link, $sql_list);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result_list = mysqli_stmt_get_result($stmt);

$schedule_words = [];
while ($row = mysqli_fetch_assoc($result_list)) {
    $schedule_words[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch hẹn ôn tập - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/topheader.css">
    <link rel="stylesheet" href="../../CSS/responsive.css">
    <link rel="stylesheet" href="../../CSS/C_LichHen.css">
</head>
<body class="C_LichHen_body">

    <?php
    $headerTitle = 'Lịch hẹn ôn tập';
    include '../../includes/topheader.php';
    ?>

    <?php include '../../includes/sidebar_user.php'; ?>

    <div class="C_LichHen_layoutWrapper">
        <main class="C_LichHen_mainContent">

            <!-- Banner Header -->
            <section class="C_LichHen_headerCard">
                <div class="C_LichHen_headerInfo">
                    <span class="C_LichHen_headerBadge">📅 Lộ trình ngắt quãng (SRS)</span>
                    <h2 class="C_LichHen_headerTitle">Lịch hẹn ôn tập cá nhân</h2>
                    <p class="C_LichHen_headerDesc">
                        Hệ thống tự động phân loại và tính toán thời điểm vàng dựa trên thuật toán 5 giai đoạn để giúp bạn ghi nhớ từ vựng lâu nhất.
                    </p>
                </div>
                <?php if ($count_today > 0): ?>
                    <a href="C_HocFlashcard.php?mode=review" class="C_LichHen_btnStartNow">
                        ⚡ Ôn tập ngay (<?= $count_today ?> từ)
                    </a>
                <?php else: ?>
                    <button class="C_LichHen_btnStartNow is-disabled" disabled>
                        ✔ Hôm nay đã xong
                    </button>
                <?php endif; ?>
            </section>

            <!-- 4 Thẻ thống kê mốc thời gian -->
            <section class="C_LichHen_statsGrid">
                <div class="C_LichHen_statCard card-today">
                    <div class="C_LichHen_statIcon">⏰</div>
                    <div class="C_LichHen_statDetail">
                        <span class="C_LichHen_statVal"><?= $count_today ?></span>
                        <span class="C_LichHen_statLabel">Cần ôn hôm nay</span>
                    </div>
                </div>

                <div class="C_LichHen_statCard card-tomorrow">
                    <div class="C_LichHen_statIcon">🌅</div>
                    <div class="C_LichHen_statDetail">
                        <span class="C_LichHen_statVal"><?= $count_tomorrow ?></span>
                        <span class="C_LichHen_statLabel">Hẹn ngày mai</span>
                    </div>
                </div>

                <div class="C_LichHen_statCard card-week">
                    <div class="C_LichHen_statIcon">📆</div>
                    <div class="C_LichHen_statDetail">
                        <span class="C_LichHen_statVal"><?= $count_upcoming ?></span>
                        <span class="C_LichHen_statLabel">Trong 7 ngày tới</span>
                    </div>
                </div>

                <div class="C_LichHen_statCard card-mastered">
                    <div class="C_LichHen_statIcon">🏆</div>
                    <div class="C_LichHen_statDetail">
                        <span class="C_LichHen_statVal"><?= $count_mastered ?></span>
                        <span class="C_LichHen_statLabel">Đã thuộc hẳn (Lv 5)</span>
                    </div>
                </div>
            </section>

            <!-- Bảng danh sách từ và bộ lọc Level -->
            <section class="C_LichHen_tableSection">
                <div class="C_LichHen_tableHeader">
                    <h3 class="C_LichHen_tableTitle">Chi tiết lịch hẹn từng từ vựng</h3>

                    <div class="C_LichHen_filters">
                        <label for="filterLevel">Lọc theo Level:</label>
                        <select id="filterLevel" class="C_LichHen_select" onchange="filterByLevel(this.value)">
                            <option value="0" <?= $filter_level === 0 ? 'selected' : '' ?>>Tất cả Level</option>
                            <option value="1" <?= $filter_level === 1 ? 'selected' : '' ?>>Level 1 (Ôn sau 1 ngày)</option>
                            <option value="2" <?= $filter_level === 2 ? 'selected' : '' ?>>Level 2 (Ôn sau 3 ngày)</option>
                            <option value="3" <?= $filter_level === 3 ? 'selected' : '' ?>>Level 3 (Ôn sau 7 ngày)</option>
                            <option value="4" <?= $filter_level === 4 ? 'selected' : '' ?>>Level 4 (Ôn sau 14 ngày)</option>
                            <option value="5" <?= $filter_level === 5 ? 'selected' : '' ?>>Level 5 (Đã thuộc - 30 ngày)</option>
                        </select>
                    </div>
                </div>

                <div class="C_LichHen_tableWrapper">
                    <?php if (empty($schedule_words)): ?>
                        <div class="C_LichHen_emptyState">
                            <img src="../../assets/images/empty-box.svg" alt="Empty" onerror="this.style.display='none'">
                            <p>Không có từ vựng nào trong danh sách lịch hẹn hiện tại.</p>
                            <a href="../main/B_DanhSachTuVung.php" class="C_LichHen_btnStudyMore">Học thêm từ mới</a>
                        </div>
                    <?php else: ?>
                        <table class="C_LichHen_table">
                            <thead>
                                <tr>
                                    <th>Từ vựng</th>
                                    <th>Nghĩa & Loại từ</th>
                                    <th>Chủ đề</th>
                                    <th>Cấp độ</th>
                                    <th>Ngày hẹn ôn</th>
                                    <th>Trạng thái hẹn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($schedule_words as $w): 
                                    $diff = (int)$w['days_diff'];
                                    $level = (int)$w['level'];

                                    // Nhãn trạng thái
                                    if ($level >= 5) {
                                        $badgeClass = 'badge-mastered';
                                        $badgeText = 'Đã thuộc hẳn';
                                    } elseif ($diff <= 0) {
                                        $badgeClass = 'badge-due';
                                        $badgeText = ($diff === 0) ? 'Đến hạn hôm nay' : 'Quá hạn (' . abs($diff) . ' ngày)';
                                    } elseif ($diff === 1) {
                                        $badgeClass = 'badge-tomorrow';
                                        $badgeText = 'Hẹn ngày mai';
                                    } else {
                                        $badgeClass = 'badge-future';
                                        $badgeText = 'Còn ' . $diff . ' ngày nữa';
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <span class="C_LichHen_word"><?= htmlspecialchars($w['word']) ?></span>
                                            <span class="C_LichHen_pronun"><?= htmlspecialchars($w['pronunciation'] ?? '') ?></span>
                                        </td>
                                        <td>
                                            <span class="C_LichHen_meaning"><?= htmlspecialchars($w['meaning']) ?></span>
                                            <span class="C_LichHen_pos"><?= htmlspecialchars($w['part_of_speech'] ?? '') ?></span>
                                        </td>
                                        <td>
                                            <span class="C_LichHen_topicTag"><?= htmlspecialchars($w['topicName'] ?? 'Chung') ?></span>
                                        </td>
                                        <td>
                                            <span class="C_LichHen_levelPill level-<?= $level ?>">
                                                Level <?= $level ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?= date('d/m/Y', strtotime($w['next_review_date'])) ?></strong>
                                        </td>
                                        <td>
                                            <span class="C_LichHen_statusBadge <?= $badgeClass ?>">
                                                <?= $badgeText ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </section>

        </main>
    </div>

    <script src="../../JS/C_LichHen.js"></script>
</body>
</html>