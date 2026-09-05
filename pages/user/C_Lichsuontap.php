<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC TRUY VẤN CSDL THEO QUY TẮC CỦA LEADER]
   - Leader lưu ý: Khi kết nối với DB thì phải lấy đúng y chang tên cột trong CSDL.
   - Dưới đây là mảng dữ liệu mẫu để bạn chạy thử nghiệm giao diện.
   - Khi kết nối DB thật, bạn thay bằng câu truy vấn SELECT (Ví dụ):
     $user_id = $_SESSION['user_id'] ?? 1;
     $sql = "SELECT * FROM lich_su_on_tap WHERE id_nguoidung = ? ORDER BY thoi_gian DESC";
   ========================================================================= */

// Dữ liệu mẫu số từ ôn tập 7 ngày qua (dùng vẽ biểu đồ cột)
$du_lieu_bieu_do = [
    ["thu" => "T2", "so_tu" => 15, "chieu_cao" => "40%"],
    ["thu" => "T3", "so_tu" => 35, "chieu_cao" => "65%"],
    ["thu" => "T4", "so_tu" => 25, "chieu_cao" => "50%"],
    ["thu" => "T5", "so_tu" => 50, "chieu_cao" => "95%"],
    ["thu" => "T6", "so_tu" => 28, "chieu_cao" => "55%"],
    ["thu" => "T7", "so_tu" => 42, "chieu_cao" => "80%"],
    ["thu" => "CN", "so_tu" => 60, "chieu_cao" => "100%"]
];

// Dữ liệu mẫu bảng lịch sử hoạt động
$danh_sach_lich_su = [
    [
        "id"        => 1,
        "hoat_dong" => "Làm Quiz \"Du lịch\"",
        "loai"      => "quiz",
        "ket_qua"   => "8/10",
        "thoi_gian" => "Hôm nay, 09:15"
    ],
    [
        "id"        => 2,
        "hoat_dong" => "Học FlashCard \"Công nghệ\"",
        "loai"      => "flashcard",
        "ket_qua"   => "20 thẻ",
        "thoi_gian" => "Hôm qua, 20:40"
    ],
    [
        "id"        => 3,
        "hoat_dong" => "Làm Quiz \"Ẩm thực\"",
        "loai"      => "quiz",
        "ket_qua"   => "6/10",
        "thoi_gian" => "2 ngày trước"
    ],
    [
        "id"        => 4,
        "hoat_dong" => "Học FlashCard \"Du lịch\"",
        "loai"      => "flashcard",
        "ket_qua"   => "15 thẻ",
        "thoi_gian" => "3 ngày trước"
    ]
];
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
            
            <!-- Bộ lọc thời gian -->
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
                    <span class="C_Lichsuontap_totalBadge">Tổng: <strong>250 từ</strong></span>
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
                        </tbody>
                    </table>
                </div>
            </section>

        </main>
    </div>

    <script src="../../JS/C_Lichsuontap.js"></script>
</body>
</html>