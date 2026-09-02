<?php

require_once '../../Connect.php';

$sql = "SELECT 
        t.topicID,
        t.topicName,
        t.topicDescription,
        t.category,
        COUNT(v.id) AS word_count
    FROM Topics t
    LEFT JOIN vocabulary v
        ON t.topicID = v.topic_id
    GROUP BY
        t.topicID,
        t.topicName,
        t.topicDescription,
        t.category
    ORDER BY t.topicID ASC
";

$result = mysqli_query($link, $sql);

if (!$result) {
    die("Lỗi truy vấn: " . mysqli_error($link));
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Danh sách các topic & học từ vựng</title>

    <link rel="stylesheet" type="text/css" href="../../CSS/Style.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/B_DanhSachChuDe.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/responsive.css">
    <!-- <link rel="icon" type="image/x-icon" href="../../favicon.ico"> -->
</head>

<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <!-- Logo -->
        <div class="sidebar-logo">
            <a href="B_DanhSachChuDe.html" class="logo">
                🌿LexiLoop
            </a>
        </div>

        <nav class="sidebar-nav">
            <!-- Nội dung thanh sidebar -->
            <a href="./B_DanhSachChuDe.php" class="sidebar-link active">
                <span class="icon">|||\</span> Chủ đề
            </a>

            <!-- Thêm từ -->
            <a href="#" class="sidebar-link">
                <span class="icon">+</span> Thêm Từ
            </a>

            <!-- Quay lại -->
            <div class="sidebar-bottom">
                <hr class="">
                <a href="./B_homepage.html" class="sidebar-link">
                    <span class="icon">→]</span> Trang chủ
                </a>
            </div>

        </nav>
    </aside>

    <main class="page-content">
        <!-- TOP BAR -->
        <header class="top-header">
            <!-- Các chức năng bên phải -->
            <div class="top-header-act">
                <!-- Chế độ sáng / tối -->
                <button type="button" class="top-header-btn" aria-label="Chế độ sáng tối">🌙</button>

                <!-- Thông báo -->
                <button type="button" class="top-header-btn" aria-label="Thông báo">🔔</button>

                <!-- Đăng nhập -->
                <a href="../auth/A_DangNhap.php" class="login-btn">
                    Đăng nhập
                </a>
            </div>
        </header>

        <section class="topics-header">
            <div class="topics-header-content">

                <span class="topics-eyebrow">
                    — KHÁM PHÁ
                </span>
                <h1>Khám phá các chủ đề </h1>

                <p>
                    Hãy cùng khám phá các chủ đề từ vựng tiếng Anh thú vị và đầy mới mẻ
                </p>
            </div>

            <!-- THANH TÌM KIẾM VÀ LỌC -->
            <div class="topics-toolbar">
                <!-- Tìm kiếm -->
                <div class="topics-search">
                    <span class="search-icon" aria-hidden="true">🔍</span>
                    <input type="search" id="topic-search" name="topic-search" class="search-input"
                        placeholder="Tìm kiếm chủ đề...">
                </div>

                <!-- Lọc -->
                <div class="topic-filter">

                    <select id="topic-filter" name="topic-filter">
                        <option value="all">Tất cả</option>
                        <option value="Ielts">IELTS</option>
                        <option value="Toiec">TOEIC</option>
                        <option value="common">Common</option>
                    </select>

                </div>

            </div>
        </section>

        <section class="topic-section">
            <div class="topic-list">

                <!-- CÁC TOPIC  -->
                <!-- SỬ DỤNG PHP ĐỔ DỮ LIỆU DB VÀO -->
                <?php while ($topic = mysqli_fetch_assoc($result)): ?>

                    <article
                        class="topic-card"
                        data-category="<?= htmlspecialchars($topic['category']) ?>">

                        <div class="topic-icon">
                            <img
                                src="../../assets/icons/book-open-svgrepo-com.svg"
                                alt="book">
                        </div>

                        <div class="topic-content">

                            <h3 class="topic-title">
                                <?= htmlspecialchars($topic['topicName']) ?>
                            </h3>

                            <p class="topic-count">
                                <?= $topic['word_count'] ?> từ vựng
                            </p>

                            <a href="./B_DanhSachTuVung.php?topicID=<?= $topic['topicID'] ?>">
                                Xem bộ từ vựng
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            </div>

            <!-- PHÂN TRANG -->
            <nav class="pagination" aria-label="Phân trang danh sách chủ đề">

                <a href="#" class="pagination-button" aria-label="Trang trước">
                    &laquo;
                </a>

                <a href="#" class="pagination-button active" aria-current="page">
                    1
                </a>

                <a href="#" class="pagination-button">
                    2
                </a>

                <a href="#" class="pagination-button">
                    3
                </a>

                <a href="#" class="pagination-button" aria-label="Trang sau">
                    &raquo;
                </a>

            </nav>
        </section>
    </main>

    <script src="../../JS/jquery-4.0.0.min.js"></script>
    <script src="../../JS/B_DanhSachChuDe.js"></script>

</body>

</html>