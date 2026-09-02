<?php

require_once '../../Connect.php';

// ==========================================
// 1. LẤY TOPIC ID TỪ URL
// ==========================================

$topic_id = isset($_GET['topicID']) ? (int) $_GET['topicID'] : 0;

if ($topic_id <= 0) {
    die("Topic không hợp lệ.");
}


// ==========================================
// 2. LẤY THÔNG TIN TOPIC
// ==========================================

$sql_topic = "
    SELECT
        topicID,
        topicName,
        topicDescription
    FROM Topics
    WHERE topicID = ?
";

$stmt_topic = mysqli_prepare($link, $sql_topic);

if (!$stmt_topic) {
    die("Lỗi chuẩn bị truy vấn Topic: " . mysqli_error($link));
}

mysqli_stmt_bind_param(
    $stmt_topic,
    "i",
    $topic_id
);

mysqli_stmt_execute($stmt_topic);

$result_topic = mysqli_stmt_get_result($stmt_topic);

$topic = mysqli_fetch_assoc($result_topic);

if (!$topic) {
    die("Không tìm thấy chủ đề.");
}


// ==========================================
// 3. ĐẾM SỐ LƯỢNG TỪ VỰNG
// ==========================================

$sql_count = "
    SELECT COUNT(*) AS word_count
    FROM vocabulary
    WHERE topic_id = ?
";

$stmt_count = mysqli_prepare($link, $sql_count);

if (!$stmt_count) {
    die("Lỗi chuẩn bị truy vấn số lượng: " . mysqli_error($link));
}

mysqli_stmt_bind_param(
    $stmt_count,
    "i",
    $topic_id
);

mysqli_stmt_execute($stmt_count);

$result_count = mysqli_stmt_get_result($stmt_count);

$count_data = mysqli_fetch_assoc($result_count);

$word_count = $count_data['word_count'];


// ==========================================
// 4. LẤY DANH SÁCH VOCABULARY
// ==========================================

$sql_vocab = "
    SELECT
        id,
        word,
        pronunciation,
        part_of_speech,
        meaning,
        example_sentence,
        audio_url
    FROM vocabulary
    WHERE topic_id = ?
    ORDER BY id ASC
";

$stmt_vocab = mysqli_prepare($link, $sql_vocab);

if (!$stmt_vocab) {
    die("Lỗi chuẩn bị truy vấn vocabulary: " . mysqli_error($link));
}

mysqli_stmt_bind_param(
    $stmt_vocab,
    "i",
    $topic_id
);

mysqli_stmt_execute($stmt_vocab);

$vocab_result = mysqli_stmt_get_result($stmt_vocab);

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý từ vựng</title>

    <!-- Link file CSS dùng chung -->
    <link rel="stylesheet" type="text/css" href="../../CSS/Style.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/B_DanhSachTuVung.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/responsive.css">
    <!-- <link rel="icon" type="image/x-icon" href="../../favicon.ico"> -->
</head>

<body>
    <div class="layout-wrapper">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <!-- LOGO -->
            <div class="sidebar-logo">
                <a href="B_DanhSachTuVung.html">🌿LexiLoop</a>
            </div>

            <nav class="sidebar-nav">
                <!-- Thêm class 'active' cho trang đang được chọn -->
                <a href="./B_DanhSachChuDe.php" class="sidebar-link active">
                    <span class="icon">|||\</span> Chủ đề
                </a>

                <a href="#" class="sidebar-link">
                    <span class="icon">+</span> Thêm Từ
                </a>

            </nav>

            <!-- BOTTOM LINK -->
            <div class="sidebar-bottom">
                <hr class="">
                <a href="./B_DanhSachChuDe.php" class="sidebar-link">
                    <span class="icon">→]</span> Quay về
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <header class="top-header">
                <div class="top-header-act">
                    <button class="top-header-btn" type="button" aria-label="Thông báo">🔔</button>
                    <button class="top-header-btn" type="button" aria-label="Chế độ sáng tối">🌙</button>

                    <a href="../auth/A_DangNhap.php " class="login-btn">Đăng nhập</a>
                </div>
            </header>

            <div class="content-area">

                <section class="topic-header">

                    <!-- PHP đổ dữ liệu từ DB -->
                    <div class="topic-info">

                        <h2 class="topic-name">
                            Chủ đề:
                            <span class="dynamic-text">
                                <?= htmlspecialchars($topic['topicName']) ?>
                            </span>
                        </h2>

                        <p class="topic-count">
                            Số lượng:
                            <span class="dynamic-badge">
                                <?= $word_count ?>
                            </span>
                            từ vựng
                        </p>

                    </div>

                </section>

                <!-- THANH TÌM KIẾM VÀ LỌC  -->
                <div class="vocabs-toolbar">
                    <div class="vocabs-search">
                        <span class="search-icon" aria-hidden="true">🔍</span>
                        <input type="search" id="vocab-search" name="vocab-search" class="search-input"
                            placeholder="Tìm kiếm từ vựng hoặc nghĩa...">
                    </div>

                    <!-- LỌC -->
                    <div class="vocab-filter">
                        <select name="vocab-filter" id="vocab-filter-select">
                            <option value="all">Tất cả từ vựng</option>
                            <option value="vocab-new">Từ mới(Chưa học)</option>
                            <option value="vocab-review">Từ đang ôn tập</option>
                        </select>
                    </div>
                </div>

                <section class="vocab-card">
                    <!-- Bảng danh sách từ vựng -->
                    <table class="vocab-table">
                        <thead>
                            <tr>
                                <th class="col-word">TỪ VỰNG & LOẠI</th>
                                <th class="col-meaning">NGHĨA</th>
                                <th class="col-ipa">PHIÊN ÂM</th>
                                <th class="col-example">CÂU VÍ DỤ</th>
                            </tr>
                        </thead>

                        <tbody id="vocab-data-body" class="vocab-data">

                            <!-- SỬ DỤNG PHP ĐỔ DỮ LIỆU TỪ DATABASE VÀO -->
                            <?php if (mysqli_num_rows($vocab_result) > 0): ?>

                                <?php while ($vocab = mysqli_fetch_assoc($vocab_result)): ?>

                                    <tr>

                                        <td class="col-word">

                                            <div class="word-info">

                                                <div class="word-img-placeholder">
                                                    IMG
                                                </div>

                                                <div class="word-details">

                                                    <!-- Tên từ vựng -> php -->
                                                    <span class="word-name">
                                                        <?= htmlspecialchars($vocab['word']) ?>
                                                    </span>

                                                    <!-- Loại từ vựng -> php -->
                                                    <span class="word-type">
                                                        <?= htmlspecialchars($vocab['part_of_speech'] ?? '') ?>
                                                    </span>

                                                </div>

                                            </div>

                                        </td>

                                        <!-- Nghĩa từ vựng -> php -->
                                        <td class="col-meaning">
                                            <?= htmlspecialchars($vocab['meaning']) ?>
                                        </td>

                                        <!-- Phát âm từ vựng -> php -->
                                        <td class="col-ipa">
                                            <?= htmlspecialchars($vocab['pronunciation'] ?? '') ?>
                                        </td>

                                        <!-- Câu ví dụ về từ vựng -> php -->
                                        <td class="col-example">

                                            <div class="ex-en">
                                                <?= htmlspecialchars($vocab['example_sentence'] ?? '') ?>
                                            </div>

                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4">
                                        Chủ đề này chưa có từ vựng.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>
                    </table>
                </section>
            </div>
        </main>
    </div>

    <script src="../../JS/jquery-4.0.0.min.js"></script>
    <script src="../../JS/B_DanhSachTuVung.js"></script>

</body>

</html>