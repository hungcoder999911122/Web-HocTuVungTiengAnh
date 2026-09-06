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
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="9"></rect>
                    <rect x="14" y="3" width="7" height="5"></rect>
                    <rect x="14" y="12" width="7" height="9"></rect>
                    <rect x="3" y="16" width="7" height="5"></rect>
                </svg>
            </span>
            <span>Dashboard</span>
        </a>

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

        <!-- Từ vựng của tôi -->
        <a href="../user/C_Tuvungcuatoi.php"
            class="sidebar-link <?= ($currentPage == 'C_Tuvungcuatoi.php') ? 'active' : '' ?>">
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"></line>
                    <line x1="8" y1="12" x2="21" y2="12"></line>
                    <line x1="8" y1="18" x2="21" y2="18"></line>
                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                </svg>
            </span>
            <span>Từ vựng của tôi</span>
        </a>


        <!-- Lịch sử ôn tập -->
        <a href="../user/C_Lichsuontap.php"
            class="sidebar-link <?= ($currentPage == 'C_Lichsuontap.php') ? 'active' : '' ?>">
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </span>
            <span>Lịch sử ôn tập</span>
        </a>

        <!-- Hồ sơ -->
        <a href="../user/C_Hosocanhan.php"
            class="sidebar-link <?= ($currentPage == 'C_Hosocanhan.php') ? 'active' : '' ?>">
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </span>
            <span>Hồ sơ</span>
        </a>

        <!-- Cài đặt -->
        <a href="../auth/A_caidattaikhoan.php"
            class="sidebar-link <?= ($currentPage == 'A_caidattaikhoan.php') ? 'active' : '' ?>">
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>
            </span>
            <span>Cài đặt</span>
        </a>

    </nav>

    <!-- Menu phía dưới -->
    <div class="sidebar-bottom">

        <a href="../auth/A_DangXuat.php" class="sidebar-link">
            <span class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
            </span>
            <span>Đăng xuất</span>
        </a>

    </div>

</aside>

<!-- DỰ PHÒNG JS -->
<script src="/JS/jquery-4.0.0.min.js"></script>
<script src="/JS/auth.js"></script>