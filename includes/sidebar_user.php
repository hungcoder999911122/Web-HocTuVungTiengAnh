<!-- Sidebar dành cho người dùng đã đăng nhập -->
<aside class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="B_homepage.php">
            <span class="logo">🌿</span>
            <span class="logo">LexiLoop</span>
        </a>
    </div>

    <?php
    // Lấy tên file PHP hiện tại
    $currentPage = basename($_SERVER['PHP_SELF']);
    ?>

    <!-- Menu chính -->
    <nav class="sidebar-nav">

        <!-- Dashboard -->
        <a href="../user/C_Dashboard_user.php"
            class="sidebar-link <?= ($currentPage == 'C_Dashboard_user.php') ? 'active' : '' ?>">
            <span class="icon">📊</span>
            <span>Dashboard</span>
        </a>

        <!-- Chủ đề -->
        <a href="../main/B_DanhSachChuDe.php"
            class="sidebar-link <?= ($currentPage == 'B_DanhSachChuDe.php') ? 'active' : '' ?>">
            <span class="icon">📚</span>
            <span>Chủ đề</span>
        </a>

        <!-- Thêm từ -->
        <a href="#"
            class="sidebar-link <?= ($currentPage == '#') ? 'active' : '' ?>">
            <span class="icon">➕</span>
            <span>Thêm Từ</span>
        </a>

        <!-- Từ vựng của tôi -->
        <a href="../user/C_Tuvungcuatoi.php"
            class="sidebar-link <?= ($currentPage == 'C_Tuvungcuatoi.php') ? 'active' : '' ?>">
            <span class="icon">📖</span>
            <span>Từ vựng của tôi</span>
        </a>


        <!-- Lịch sử ôn tập -->
        <a href="../user/C_Lichsuontap.php"
            class="sidebar-link <?= ($currentPage == 'C_Lichsuontap.php') ? 'active' : '' ?>">
            <span class="icon">🕒</span>
            <span>Lịch sử ôn tập</span>
        </a>

        <!-- Hồ sơ -->
        <a href="../user/C_Hosocanhan.php"
            class="sidebar-link <?= ($currentPage == 'C_Hosocanhan.php') ? 'active' : '' ?>">
            <span class="icon">👤</span>
            <span>Hồ sơ</span>
        </a>

        <!-- Cài đặt -->
        <a href="../auth/A_caidattaikhoan.php"
            class="sidebar-link <?= ($currentPage == 'A_caidattaikhoan.php') ? 'active' : '' ?>">
            <span class="icon">⚙️</span>
            <span>Cài đặt</span>
        </a>

    </nav>

    <!-- Menu phía dưới -->
    <div class="sidebar-bottom">

        <a href="../auth/A_DangXuat.php" class="sidebar-link">
            <span class="icon">🚪</span>
            <span>Đăng xuất</span>
        </a>

    </div>

</aside>

<!-- DỰ PHÒNG JS -->
<script src="/JS/jquery-4.0.0.min.js"></script>
<script src="/JS/auth.js"></script>