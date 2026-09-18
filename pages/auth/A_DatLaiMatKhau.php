<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

$loi = "";
$thanhcong = "";

// Lấy email từ tham số URL (Ví dụ: DatLaiMatKhau.php?email=user@gmail.com)
$email = $_GET['email'] ?? ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['A_DatLaiMatKhau_password_hash'] ?? '';
    $confirm_password = $_POST['A_DatLaiMatKhau_confirm_password'] ?? '';

    // 1. Kiểm tra dữ liệu
    if (empty($password) || empty($confirm_password)) {
        $loi = "Vui lòng nhập đầy đủ thông tin mật khẩu mới và xác nhận mật khẩu mới.";
    } elseif ($password != $confirm_password) {
        $loi = "Mật khẩu mới và xác nhận mật khẩu mới không khớp.";
    } elseif (strlen($password) < 6) {
        $loi = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    } else {
        // 2. Mã hóa mật khẩu an toàn
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // 3. Cập nhật vào Database (Thay 'users' bằng tên bảng người dùng của bạn)
$sql = "UPDATE Users SET password_hash = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            $thanhcong = "Đặt lại mật khẩu thành công! Đang chuyển đến trang đăng nhập...";
            header("refresh:2; url=/pages/auth/A_DangNhap.php"); // Chuyển hướng sau 2 giây
        } else {
            $loi = "Có lỗi xảy ra trong quá trình cập nhật, vui lòng thử lại.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - LexiLoop</title>
    <link rel="stylesheet" href="/CSS/A_DatLaiMatKhau.css">
    <link rel="stylesheet" type="text/css" href="/CSS/Style.css">
</head>
<body class="A_DatLaiMatKhau_body">

    <header class="A_DatLaiMatKhau_header">
        <h1 class="A_DatLaiMatKhau_logo">LexiLoop</h1>
    </header>

    <main class="A_DatLaiMatKhau_main">
        <div class="A_DatLaiMatKhau_boxContainer">
            <h2 class="A_DatLaiMatKhau_title">Đặt lại mật khẩu</h2>
            <p class="A_DatLaiMatKhau_subTitle">Tạo mật khẩu mới cho tài khoản của bạn</p>

            <!-- Hiển thị thông báo Lỗi / Thành công -->
            <?php if (!empty($loi)): ?>
                <p style="color: red; text-align: center; font-weight: bold;"><?php echo $loi; ?></p>
            <?php endif; ?>

            <?php if (!empty($thanhcong)): ?>
                <p style="color: green; text-align: center; font-weight: bold;"><?php echo $thanhcong; ?></p>
            <?php endif; ?>

            <form id="A_DatLaiMatKhau_formDatLaiMatKhau" action="" method="POST">
                
                <div class="A_DatLaiMatKhau_formGroup">
                    <label for="A_DatLaiMatKhau_password_hash" class="A_DatLaiMatKhau_label">Mật khẩu mới</label>
                    <input 
                        type="password" 
                        id="A_DatLaiMatKhau_password_hash" 
                        name="A_DatLaiMatKhau_password_hash" 
                        class="A_DatLaiMatKhau_input" 
                        maxlength="50" 
                        required>
                </div>

                <div class="A_DatLaiMatKhau_formGroup">
                    <label for="A_DatLaiMatKhau_confirm_password" class="A_DatLaiMatKhau_label">Xác nhận mật khẩu mới</label>
                    <input 
                        type="password" 
                        id="A_DatLaiMatKhau_confirm_password" 
                        name="A_DatLaiMatKhau_confirm_password" 
                        class="A_DatLaiMatKhau_input" 
                        maxlength="50" 
                        required>
                </div>

                <button type="submit" id="A_DatLaiMatKhau_btnDatLaiMatKhau" class="A_DatLaiMatKhau_btnSubmit">Đặt lại mật khẩu</button>
            </form>
        </div>
    </main>

</body>
</html>