<!-- Sidebar dành cho khách chưa đăng nhập -->
<aside class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="../main/B_DanhSachChuDe.php">
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

    </nav>

    <!-- Menu phía dưới -->
    <div class="sidebar-bottom">

        <a href="../main/B_homepage.html" class="sidebar-link">
            <span class="icon">🚪</span>
            <span>Trang chủ</span>
        </a>

    </div>

</aside>

<!-- DỰ PHÒNG JS -->
<script src="/JS/jquery-4.0.0.min.js"></script>
<script src="/JS/auth.js"></script>