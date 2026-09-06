<?php
/*
 * Top header dùng chung cho các trang.
 *
 * Trang cha có thể truyền $headerTitle để hiển thị tên màn hình.
 * Không truy vấn database ở đây: thông tin cơ bản đã có trong session
 * sau khi đăng nhập, giúp component này tái sử dụng và tránh query lặp.
 */
$headerTitle = $headerTitle ?? '';
$isLoggedIn = isset($_SESSION['user_id']);
$headerUserName = $_SESSION['full_name'] ?? 'Người dùng';

// Tạo tối đa 2 chữ cái đầu để dùng khi tài khoản chưa có ảnh đại diện.
$headerInitials = '';
foreach (explode(' ', trim($headerUserName)) as $namePart) {
    if ($namePart !== '') {
        $headerInitials .= strtoupper(substr($namePart, 0, 1));
    }
}
$headerInitials = substr($headerInitials, 0, 2);
?>

<header class="top-header">
    <div class="top-header-context">
        <?php if ($headerTitle !== ''): ?>
            <h1 class="top-header-title"><?= htmlspecialchars($headerTitle) ?></h1>
        <?php endif; ?>
    </div>

    <div class="top-header-act">
        <!-- Hai chức năng này mới là giao diện; sẽ chỉ mở khóa khi đã có JavaScript xử lý. -->
        <button class="top-header-btn" type="button" aria-label="Thông báo đang phát triển" title="Đang phát triển" disabled>🔔</button>
        <button class="top-header-btn" type="button" aria-label="Chế độ sáng tối đang phát triển" title="Đang phát triển" disabled>🌙</button>

        <?php if ($isLoggedIn): ?>
            <details class="top-header-account">
                <summary class="top-header-account-summary">
                    <span class="top-header-avatar" aria-hidden="true"><?= htmlspecialchars($headerInitials) ?></span>
                    <span class="top-header-user-name"><?= htmlspecialchars($headerUserName) ?></span>
                </summary>

                <nav class="top-header-account-menu" aria-label="Tài khoản">
                    <a href="../user/C_Hosocanhan.php">Hồ sơ</a>
                    <a href="../auth/A_Caidattaikhoan.php">Cài đặt</a>
                    <a href="../auth/A_DangXuat.php">Đăng xuất</a>
                </nav>
            </details>
        <?php else: ?>
            <a href="../auth/A_DangNhap.php" class="login-btn">Đăng nhập</a>
        <?php endif; ?>
    </div>
</header>
