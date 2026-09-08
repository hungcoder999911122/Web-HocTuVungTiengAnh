<?php

// =====================================================
// 1. KẾT NỐI DATABASE
// =====================================================
// =====================================================
// 2. KHỞI ĐỘNG SESSION
// =====================================================
// =====================================================
// 3. KIỂM TRA ĐĂNG NHẬP
// =====================================================

require_once '../../includes/auth_guard.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// auth_guard.php đã chắc chắn user_id tồn tại.
// Ép kiểu int giúp dữ liệu truyền vào truy vấn nhất quán.
$userId = (int) $_SESSION['user_id'];

// =====================================================
// LẤY THÔNG TIN USER
// =====================================================
$sqlUser = "
    SELECT
        userID,
        full_name,
        avatar_url,
        daily_target_words
    FROM Users
    WHERE userID = ?
";

$stmtUser = mysqli_prepare($link, $sqlUser);

mysqli_stmt_bind_param($stmtUser, "i", $userId);

mysqli_stmt_execute($stmtUser);

$resultUser = mysqli_stmt_get_result($stmtUser);

$user = mysqli_fetch_assoc($resultUser);

if (!$user) {
    die("Không tìm thấy thông tin người dùng.");
}


// =====================================================
// 6. THÔNG TIN HIỂN THỊ
// =====================================================
$fullName = $user['full_name'];

$dailyTarget = (int)$user['daily_target_words'];

// =====================================================
// 7. LẤY CHUỖI HỌC GẦN NHẤT
// =====================================================

$sqlStreak = "
    SELECT streak_count
    FROM learning_sessions
    WHERE user_id = ?
    ORDER BY session_date DESC, id DESC
    LIMIT 1
";

$stmtStreak = mysqli_prepare($link, $sqlStreak);

mysqli_stmt_bind_param($stmtStreak, "i", $userId);

mysqli_stmt_execute($stmtStreak);

$resultStreak = mysqli_stmt_get_result($stmtStreak);

$rowStreak = mysqli_fetch_assoc($resultStreak);

$streak = $rowStreak ? (int)$rowStreak['streak_count'] : 0;

// =====================================================
// 8. SỐ TỪ ĐANG HỌC
// =====================================================

$sqlLearning = "
    SELECT COUNT(*) AS total
    FROM user_vocab_progress
    WHERE user_id = ?
      AND status = 'learning'
";

$stmtLearning = mysqli_prepare($link, $sqlLearning);

mysqli_stmt_bind_param($stmtLearning, "i", $userId);

mysqli_stmt_execute($stmtLearning);

$resultLearning = mysqli_stmt_get_result($stmtLearning);

$rowLearning = mysqli_fetch_assoc($resultLearning);

$learningWords = (int)$rowLearning['total'];

// =====================================================
// 9. SỐ TỪ ĐÃ THUỘC
// =====================================================

$sqlMastered = "
    SELECT COUNT(*) AS total
    FROM user_vocab_progress
    WHERE user_id = ?
      AND status = 'mastered'
";

$stmtMastered = mysqli_prepare($link, $sqlMastered);

mysqli_stmt_bind_param($stmtMastered, "i", $userId);

mysqli_stmt_execute($stmtMastered);

$resultMastered = mysqli_stmt_get_result($stmtMastered);

$rowMastered = mysqli_fetch_assoc($resultMastered);

$masteredWords = (int)$rowMastered['total'];

// =====================================================
// 10. ĐIỂM QUIZ TRUNG BÌNH
// =====================================================

$sqlQuiz = "
    SELECT AVG(score) AS average_score
    FROM quiz_results
    WHERE user_id = ?
";

$stmtQuiz = mysqli_prepare($link, $sqlQuiz);

mysqli_stmt_bind_param($stmtQuiz, "i", $userId);

mysqli_stmt_execute($stmtQuiz);

$resultQuiz = mysqli_stmt_get_result($stmtQuiz);

$rowQuiz = mysqli_fetch_assoc($resultQuiz);

$averageQuiz = $rowQuiz['average_score'] !== null
    ? round((float)$rowQuiz['average_score'])
    : 0;

// =====================================================
// 11. SỐ TỪ CẦN ÔN HÔM NAY
// =====================================================

$sqlReviewToday = "
    SELECT COUNT(*) AS total
    FROM user_vocab_progress
    WHERE user_id = ?
      AND next_review_date <= CURDATE()
";

$stmtReviewToday = mysqli_prepare($link, $sqlReviewToday);

mysqli_stmt_bind_param($stmtReviewToday, "i", $userId);

mysqli_stmt_execute($stmtReviewToday);

$resultReviewToday = mysqli_stmt_get_result($stmtReviewToday);

$rowReviewToday = mysqli_fetch_assoc($resultReviewToday);

$reviewToday = (int)$rowReviewToday['total'];

// =====================================================
// 12. SỐ TỪ ĐÃ HỌC HÔM NAY
// =====================================================

$sqlTodayWords = "
    SELECT COALESCE(SUM(words_studied), 0) AS total
    FROM learning_sessions
    WHERE user_id = ?
      AND session_date = CURDATE()
";

$stmtTodayWords = mysqli_prepare($link, $sqlTodayWords);

mysqli_stmt_bind_param($stmtTodayWords, "i", $userId);

mysqli_stmt_execute($stmtTodayWords);

$resultTodayWords = mysqli_stmt_get_result($stmtTodayWords);

$rowTodayWords = mysqli_fetch_assoc($resultTodayWords);

$todayWords = (int)$rowTodayWords['total'];

// =====================================================
// 13. TÍNH TIẾN ĐỘ MỤC TIÊU
// =====================================================

if ($dailyTarget > 0) {
    $targetPercent = ($todayWords / $dailyTarget) * 100;
    $targetPercent = min($targetPercent, 100);
} else {
    $targetPercent = 0;
}

$remainingWords = max($dailyTarget - $todayWords, 0);

// =====================================================
// 14. DỮ LIỆU BIỂU ĐỒ 7 NGÀY
// =====================================================

$sqlChart = "
    SELECT
        session_date,
        SUM(words_studied) AS total_words
    FROM learning_sessions
    WHERE user_id = ?
      AND session_date >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY session_date
    ORDER BY session_date ASC
";

$stmtChart = mysqli_prepare($link, $sqlChart);

mysqli_stmt_bind_param($stmtChart, "i", $userId);

mysqli_stmt_execute($stmtChart);

$resultChart = mysqli_stmt_get_result($stmtChart);

$chartLabels = [];
$chartData = [];

while ($row = mysqli_fetch_assoc($resultChart)) {

    $chartLabels[] = $row['session_date'];

    $chartData[] = (int)$row['total_words'];
}

// =====================================================
// 15. QUIZ GẦN ĐÂY
// =====================================================

$sqlRecentQuiz = "
    SELECT
        qr.score,
        qr.finished_at,
        t.topicName
    FROM quiz_results qr
    LEFT JOIN Topics t
        ON qr.topic_id = t.topicID
    WHERE qr.user_id = ?
    ORDER BY qr.finished_at DESC
    LIMIT 5
";

$stmtRecentQuiz = mysqli_prepare($link, $sqlRecentQuiz);

mysqli_stmt_bind_param($stmtRecentQuiz, "i", $userId);

mysqli_stmt_execute($stmtRecentQuiz);

$resultRecentQuiz = mysqli_stmt_get_result($stmtRecentQuiz);

$recentQuiz = [];

while ($row = mysqli_fetch_assoc($resultRecentQuiz)) {
    $recentQuiz[] = $row;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/C_Dashboard_user.css">

    <link rel="stylesheet" href="../../CSS/topheader.css">
    <link rel="stylesheet" href="../../CSS/responsive.css">
</head>

<body class="C_Dashboard_user_body">

    <!-- Header dùng chung; tên người dùng lấy từ session, không query lại database. -->
    <?php
    $headerTitle = 'Dashboard';
    include '../../includes/topheader.php';
    ?>

    <!-- SIDEBAR -->
    <?php include '../../includes/sidebar_user.php'; ?>

    <div class="C_Dashboard_user_layoutWrapper">


        <main class="C_Dashboard_user_mainContent">

            <!-- Phần chào mừng & 4 thẻ thống kê -->
            <section class="C_Dashboard_user_welcomeSection">
                <h2 class="C_Dashboard_user_welcomeTitle">Chào mừng trở lại!</h2>
                <p class="C_Dashboard_user_welcomeSub"> Bạn có <?= $reviewToday ?> từ cần ôn tập hôm nay</p>

                <div class="C_Dashboard_user_statsGrid">
                    <!-- Thẻ 1: Chuỗi ngày -->
                    <div class="C_Dashboard_user_statCard">
                        <span class="C_Dashboard_user_statLabel">
                            Chuỗi ngày học
                        </span>

                        <span class="C_Dashboard_user_statVal">
                            <?= $streak ?> ngày
                        </span>
                    </div>

                    <div class="C_Dashboard_user_statCard">

                        <span class="C_Dashboard_user_statLabel">
                            Đang học
                        </span>

                        <span class="C_Dashboard_user_statVal">
                            <?= $learningWords ?>
                        </span>

                    </div>

                    <div class="C_Dashboard_user_statCard">

                        <span class="C_Dashboard_user_statLabel">
                            Đã thuộc
                        </span>

                        <span class="C_Dashboard_user_statVal">
                            <?= $masteredWords ?>
                        </span>

                    </div>

                    <div class="C_Dashboard_user_statCard">
                        <span class="C_Dashboard_user_statLabel">
                            Điểm quiz TB
                        </span>

                        <span class="C_Dashboard_user_statVal">
                            <?= $averageQuiz ?>%
                        </span>
                    </div>

                </div>
            </section>

            <section class="C_Dashboard_user_reviewSection">

                <h3 class="C_Dashboard_user_sectionHeading">
                    Ôn tập hôm nay
                </h3>

                <div class="C_Dashboard_user_reviewCard">

                    <div class="C_Dashboard_user_reviewInfo">

                        <span class="C_Dashboard_user_reviewText">
                            Bạn có <?= $reviewToday ?> từ cần ôn tập
                        </span>

                    </div>

                    <a
                        href="../user/C_Ontaphomnay.php"
                        class="C_Dashboard_user_reviewButton">
                        ÔN TẬP NGAY
                    </a>

                </div>
            </section>

            <section class="C_Dashboard_user_progressSection">

                <h3 class="C_Dashboard_user_sectionHeading">
                    Tiến độ học tập 7 ngày gần đây
                </h3>

                <div class="C_Dashboard_user_chartContainer">

                    <canvas id="learningProgressChart"></canvas>

                </div>
            </section>

            <section class="C_Dashboard_user_targetSection">
                <h3 class="C_Dashboard_user_sectionHeading">
                    Mục tiêu hôm nay
                </h3>

                <div class="C_Dashboard_user_targetCard">

                    <div class="C_Dashboard_user_targetValue">
                        <?= $todayWords ?> / <?= $dailyTarget ?> từ
                    </div>

                    <div class="C_Dashboard_user_targetProgress">

                        <div
                            class="C_Dashboard_user_targetProgressBar"
                            style="width: <?= $targetPercent ?>%;"></div>

                    </div>

                    <p class="C_Dashboard_user_targetNote">

                        <?php if ($remainingWords > 0): ?>

                            Còn <?= $remainingWords ?> từ nữa để hoàn thành mục tiêu.

                        <?php else: ?>

                            Bạn đã hoàn thành mục tiêu hôm nay.

                        <?php endif; ?>

                    </p>

                </div>

            </section>

            <section class="C_Dashboard_user_historySection">

                <h3 class="C_Dashboard_user_sectionHeading">
                    Hoạt động gần đây
                </h3>

                <div class="C_Dashboard_user_historyTable">

                    <?php if (empty($recentQuiz)): ?>

                        <div class="C_Dashboard_user_historyRow">

                            <span class="C_Dashboard_user_historyText">
                                Chưa có hoạt động gần đây.
                            </span>

                        </div>

                    <?php else: ?>

                        <?php foreach ($recentQuiz as $activity): ?>

                            <div class="C_Dashboard_user_historyRow">

                                <span class="C_Dashboard_user_historyText">

                                    Hoàn thành Quiz
                                    "<?=
                                        htmlspecialchars(
                                            $activity['topicName'] ?? 'Không xác định'
                                        )
                                        ?>"

                                </span>

                                <span class="C_Dashboard_user_historyTime">

                                    <?= htmlspecialchars($activity['finished_at']) ?>

                                </span>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </section>

        </main>
    </div>
    <script src="../../JS/jquery-4.0.0.min.js"></script>
    <script src="../../JS/auth.js"></script>
</body>

</html>