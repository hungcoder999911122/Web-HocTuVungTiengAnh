<?php
/*
|--------------------------------------------------------------------------
| Xác định trang hiện tại
|--------------------------------------------------------------------------
| basename($_SERVER['PHP_SELF']) trả về tên file đang chạy.
| Ví dụ: C_Lichsuontap.php
*/
$currentPage = basename($_SERVER['PHP_SELF']);

/*
|--------------------------------------------------------------------------
| Hàm kiểm tra menu có đang active hay không
|--------------------------------------------------------------------------
| Giúp tránh lặp lại điều kiện PHP dài trên mỗi thẻ <a>.
*/
function isSidebarActive(string $pageName, string $currentPage): string
{
    return $pageName === $currentPage ? 'active' : '';
}
?>

<!-- Sidebar dành cho người dùng đã đăng nhập -->
<aside class="sidebar">

    <!-- Logo: dùng đường dẫn tương đối phù hợp cho pages/main và pages/user -->
    <a href="../user/C_Dashboard_user.php" class="sidebar-logo">
        <span class="logo-badge" aria-hidden="true">🌿</span>
        <span>LexiLoop</span>
    </a>

    <!-- Điều hướng chính -->
    <nav class="sidebar-nav" aria-label="Điều hướng người dùng">

        <a
            href="../user/C_Dashboard_user.php"
            class="sidebar-link <?= isSidebarActive('C_Dashboard_user.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">


                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>

            </span>
            <span> Trang chủ </span>
        </a>

        <a
            href="../main/B_DanhSachChuDe.php"
            class="sidebar-link <?= isSidebarActive('B_DanhSachChuDe.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>

            </span>
            <span>Chủ đề</span>
        </a>

        

        <a
            href="../user/C_Gocrenluyen.php"
            class="sidebar-link <?= isSidebarActive('C_Gocrenluyen.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-play">
                    <path d="M5 5a2 2 0 0 1 3.008-1.728l11.997 6.998a2 2 0 0 1 .003 3.458l-12 7A2 2 0 0 1 5 19z" />
                </svg>

            </span>
            <span> Góc rèn luyện </span>
        </a>


        <a
            href="../user/C_Lichhen.php"
            class="sidebar-link <?= isSidebarActive('C_Lichhen.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>

            </span>
            <span>Lịch hẹn ôn tập</span>
        </a>

        <a
            href="../user/C_Lichsuontap.php"
            class="sidebar-link <?= isSidebarActive('C_Lichsuontap.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>

            </span>
            <span>Lịch sử ôn tập</span>
	        
            href="../user/C_Xephang.php"
            class="sidebar-link <?= isSidebarActive('C_Xephang.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M8 21h8"></path>
                    <path d="M12 17v4"></path>
                    <path d="M7 4h10v5a5 5 0 0 1-10 0z"></path>
                    <path d="M17 6h2a2 2 0 0 1 0 4h-1"></path>
                    <path d="M7 6H5a2 2 0 0 0 0 4h1"></path>
                </svg>
            </span>
            <span> Xếp hạng </span>
        </a>
        </a>

        <a
            href="../user/C_Hosocanhan.php"
            class="sidebar-link <?= isSidebarActive('C_Hosocanhan.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>

            </span>
            <span>Hồ sơ</span>
        </a>

        <a
            href="../auth/A_Caidattaikhoan.php"
            class="sidebar-link <?= isSidebarActive('A_Caidattaikhoan.php', $currentPage) ?>">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                </svg>

            </span>
            <span>Cài đặt</span>
        </a>
    </nav>

    <!-- Hành động phụ đặt cuối sidebar -->
    <div class="sidebar-bottom">
        <a href="../auth/A_DangXuat.php" class="sidebar-link sidebar-logout" data-action="logout">
            <span class="sidebar-icon" aria-hidden="true">

                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>

            </span>
            <span>Đăng xuất</span>
        </a>
    </div>
</aside>