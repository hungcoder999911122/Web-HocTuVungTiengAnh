<?php
/*
|--------------------------------------------------------------------------
| TOPHEADER DÙNG CHUNG
|--------------------------------------------------------------------------
| Trang cha có thể truyền:
|
| $headerTitle
|     Tiêu đề hiển thị trên topheader.
|
| $topHeaderPageActions
|     HTML hành động riêng của trang.
|     Ví dụ: nút Thêm từ hoặc dropdown lọc lịch sử.
*/

$headerTitle = $headerTitle ?? '';
$topHeaderPageActions = $topHeaderPageActions ?? '';

$isLoggedIn = isset($_SESSION['user_id']);
$headerUserName = $_SESSION['full_name'] ?? 'Người dùng';

/*
| Ảnh đại diện.
| Lấy từ session; nếu session chưa có thì tra database đúng một lần rồi lưu lại,
| nên đăng nhập xong là header có ảnh ngay, không cần vào trang Hồ sơ trước.
| $_SESSION['avatar_url'] = '' nghĩa là người dùng chưa có ảnh (đã tra rồi).
*/
$headerAvatarUrl = '';

if ($isLoggedIn) {
    if (!isset($_SESSION['avatar_url'])) {
        try {
            // Trang cha thường đã nạp Connect.php; nếu chưa thì nạp ở đây.
            if (!isset($link) || !($link instanceof mysqli)) {
                require_once($_SERVER['DOCUMENT_ROOT'] . '/Connect.php');
            }

            $headerUserId = (int) $_SESSION['user_id'];
            $headerStmt = mysqli_prepare($link, 'SELECT avatar_url FROM Users WHERE userID = ? LIMIT 1');
            mysqli_stmt_bind_param($headerStmt, 'i', $headerUserId);
            mysqli_stmt_execute($headerStmt);
            $headerRow = mysqli_fetch_assoc(mysqli_stmt_get_result($headerStmt));
            mysqli_stmt_close($headerStmt);

            $_SESSION['avatar_url'] = (string) ($headerRow['avatar_url'] ?? '');
        } catch (Throwable $e) {
            // Không ghi vào session để lần tải sau thử lại; header vẫn hiện chữ cái đầu.
            error_log('topheader avatar: ' . $e->getMessage());
        }
    }

    $headerAvatarUrl = (string) ($_SESSION['avatar_url'] ?? '');

    // Chỉ hiển thị ảnh do chức năng tải ảnh của hệ thống tạo ra.
    if (strpos($headerAvatarUrl, '/assets/images/avatars/') !== 0) {
        $headerAvatarUrl = '';
    }
}

/* Tạo tối đa hai chữ cái đầu cho avatar (an toàn với tên tiếng Việt có dấu). */
$headerInitials = '';
$headerNameParts = preg_split('/\s+/u', trim($headerUserName), -1, PREG_SPLIT_NO_EMPTY) ?: [];

foreach (array_slice($headerNameParts, 0, 2) as $namePart) {
    // Lấy nguyên 1 ký tự UTF-8 (không cắt theo byte để tránh làm hỏng chữ như Đ, Ư, Á).
    $firstChar = preg_match('/^./us', $namePart, $headerMatch) ? $headerMatch[0] : '';
    $headerInitials .= function_exists('mb_strtoupper')
        ? mb_strtoupper($firstChar, 'UTF-8')
        : strtoupper($firstChar);
}
?>

<header class="top-header">

    <!-- Bên trái: tên trang và hành động riêng -->
    <div class="top-header-start">
        <div class="top-header-context">
            <?php if ($headerTitle !== ''): ?>
                <h1 class="top-header-title">
                    <?= htmlspecialchars($headerTitle) ?>
                </h1>
            <?php endif; ?>
        </div>

        <?php if ($topHeaderPageActions !== ''): ?>
            <div class="top-header-page-actions">
                <?= $topHeaderPageActions ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bên phải: chức năng toàn hệ thống và tài khoản -->
    <div class="top-header-act">

        <!-- Tạm thời chỉ là UI; có thể ẩn sau nếu chưa phát triển. -->
        <button
            class="top-header-btn"
            type="button"
            aria-label="Thông báo đang phát triển"
            title="Đang phát triển"
            disabled>
            🔔
        </button>

        <button
            class="top-header-btn"
            type="button"
            aria-label="Chế độ sáng tối đang phát triển"
            title="Đang phát triển"
            disabled>
            🌙
        </button>

        <?php if ($isLoggedIn && ($_SESSION['role'] ?? '') === 'admin'): ?>
            <!-- Chỉ tài khoản admin mới thấy nút này (trang admin vẫn tự kiểm tra quyền bằng admin_guard.php). -->
            <a href="../admin/D_Dashboard_admin.php" class="login-btn top-header-admin-btn">
                Vào trang admin
            </a>
        <?php endif; ?>

        <?php if ($isLoggedIn): ?>
            <details class="top-header-account">
                <summary class="top-header-account-summary">
                    <span class="top-header-avatar" aria-hidden="true"<?= $headerAvatarUrl !== '' ? ' style="overflow:hidden;"' : '' ?>>
                        <?php if ($headerAvatarUrl !== ''): ?>
                            <img
                                src="<?= htmlspecialchars($headerAvatarUrl) ?>"
                                alt=""
                                style="width:100%;height:100%;object-fit:cover;border-radius:50%;display:block;">
                        <?php else: ?>
                            <?= htmlspecialchars($headerInitials) ?>
                        <?php endif; ?>
                    </span>

                    <span class="top-header-user-name">
                        <?= htmlspecialchars($headerUserName) ?>
                    </span>
                </summary>

                <nav class="top-header-account-menu" aria-label="Tài khoản">
                    <a href="../user/C_Hosocanhan.php">Hồ sơ</a>
                    <a href="../auth/A_Caidattaikhoan.php">Cài đặt</a>

                    <a
                        href="../auth/A_DangXuat.php"
                        data-action="logout">
                        Đăng xuất
                    </a>
                </nav>
            </details>
        <?php else: ?>
            <a href="../auth/A_DangNhap.php" class="login-btn">
                Đăng nhập
            </a>
        <?php endif; ?>
    </div>
</header>