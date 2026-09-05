<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC TRUY VẤN CSDL THEO QUY TẮC CỦA LEADER]
   - Leader lưu ý: Khi kết nối DB phải lấy đúng y chang tên cột trong CSDL.
   - Dưới đây là các biến mẫu lưu thông tin tổng quan của người dùng.
   - Khi kết nối DB thật, bạn thay bằng các truy vấn SELECT tương ứng:
     $user_id = $_SESSION['user_id'] ?? 1;
     $sql_user = "SELECT ho_ten, chuoi_ngay FROM nguoi_dung WHERE id = ?";
   ========================================================================= */

$user_name       = "Nguyễn An";
$chuoi_ngay      = 12; // Streak
$tong_tu_hoc     = 356;
$tu_can_on_tap   = 24;
$diem_quiz_tb    = "82%";

// Danh sách lịch sử học tập gần đây
$lich_su_gan_day = [
    [
        "hanh_dong" => "Hoàn thành Quiz \"Du lịch\"",
        "loai"      => "quiz",
        "thoi_gian" => "Hôm nay, 09:15"
    ],
    [
        "hanh_dong" => "Học 20 thẻ FlashCard \"Công nghệ\"",
        "loai"      => "flashcard",
        "thoi_gian" => "Hôm qua, 20:40"
    ],
    [
        "hanh_dong" => "Thêm 5 từ vựng mới vào bộ sưu tập",
        "loai"      => "vocab",
        "thoi_gian" => "2 ngày trước"
    ],
    [
        "hanh_dong" => "Hoàn thành Quiz \"Ẩm thực\"",
        "loai"      => "quiz",
        "thoi_gian" => "3 ngày trước"
    ]
];
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
            <span class="logo-badge">🌱</span> LexiLoop
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