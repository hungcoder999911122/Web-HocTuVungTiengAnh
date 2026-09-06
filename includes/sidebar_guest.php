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
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
            </span>
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
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </span>
            <span>Trang chủ</span>
        </a>

    </div>

</aside>

<!-- DỰ PHÒNG JS -->
<script src="/JS/jquery-4.0.0.min.js"></script>
<script src="/JS/auth.js"></script>