<?php
/*
|--------------------------------------------------------------------------
| ADMIN GUARD
|--------------------------------------------------------------------------
| Đặt ở ĐẦU mọi trang trong pages/admin/:
|
|     require_once '../../includes/admin_guard.php';
|
| - Chưa đăng nhập        -> chuyển về trang đăng nhập.
| - Đã đăng nhập, không phải admin (hoặc tài khoản bị khóa)
|                         -> chuyển về Dashboard người dùng.
|
| Role được kiểm tra lại trong database mỗi lần vào, không chỉ tin vào session,
| nên khi đổi role hoặc khóa tài khoản thì có hiệu lực ngay, không cần chờ đăng nhập lại.
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/A_DangNhap.php');
    exit;
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/Connect.php');

$adminGuardUserId = (int) $_SESSION['user_id'];
$adminGuardStmt = mysqli_prepare($link, 'SELECT role, status FROM Users WHERE userID = ? LIMIT 1');
mysqli_stmt_bind_param($adminGuardStmt, 'i', $adminGuardUserId);
mysqli_stmt_execute($adminGuardStmt);
$adminGuardRow = mysqli_fetch_assoc(mysqli_stmt_get_result($adminGuardStmt));
mysqli_stmt_close($adminGuardStmt);

if ($adminGuardRow) {
    // Đồng bộ session để nút "Vào trang admin" ở header luôn khớp với database.
    $_SESSION['role'] = $adminGuardRow['role'];
}

if (!$adminGuardRow || $adminGuardRow['role'] !== 'admin' || $adminGuardRow['status'] !== 'active') {
    header('Location: ../user/C_Dashboard_user.php');
    exit;
}