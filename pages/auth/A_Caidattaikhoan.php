<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// auth_guard.php đã xác thực session trước khi trang sử dụng user_id.
$isLoggedIn = isset($_SESSION['user_id']);
$user_id = $isLoggedIn ? (int) $_SESSION['user_id'] : null;

$loi = "";

//Xử lý form, CHỈ chạy sau khi đã chắc chắn đăng nhập rồi
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $A_Caidattaikhoan_password         = $_POST['A_Caidattaikhoan_password'] ?? '';
    $A_Caidattaikhoan_password_new     = $_POST['A_Caidattaikhoan_password_new'] ?? '';
    $A_Caidattaikhoan_password_new_acp = $_POST['A_Caidattaikhoan_password_new_acp'] ?? '';

    if(empty($A_Caidattaikhoan_password_new) || empty($A_Caidattaikhoan_password_new_acp)) {
        $loi = "Vui lòng nhập đầy đủ thông tin mật khẩu mới và xác nhận mật khẩu mới.";
    }
    elseif($A_Caidattaikhoan_password_new != $A_Caidattaikhoan_password_new_acp) {
        $loi = "Mật khẩu mới và xác nhận mật khẩu mới không khớp.";
    }
    elseif(strlen($A_Caidattaikhoan_password_new) < 6) {
        $loi = "Mật khẩu mới phải có ít nhất 6 ký tự.";
    }
    else {
        // Kiểm tra mật khẩu hiện tại đúng không
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT password_hash FROM Users WHERE userID='$user_id'";
        $result = mysqli_query($link, $sql);
        $row = mysqli_fetch_assoc($result);

        if(!password_verify($A_Caidattaikhoan_password, $row['password_hash'])) {
            $loi = "Mật khẩu hiện tại không đúng.";
        } else {
            $new_hash = password_hash($A_Caidattaikhoan_password_new, PASSWORD_DEFAULT);
            $sql = "UPDATE Users SET password_hash='$new_hash' WHERE userID='$user_id'";

            if(mysqli_query($link, $sql)) {
                header("Location: ../user/C_Dashboard_user.php?settings=success");
                exit();
            } else {
                $loi = "Lỗi: " . mysqli_error($link);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="/CSS/A_Caidattaikhoan.css">
	<link rel="stylesheet" type="text/css" href="/CSS/Style.css">

	<script src="/JS/jquery-4.0.0.min.js"></script>
	<title>Cài đặt tài khoản</title>
</head>
<body>

	<div class="wrapper">
		<a href="../user/C_Dashboard_user.php" style="display:inline-block; margin-bottom:12px; text-decoration:none; color:#0e7748; font-weight:600;">
			&larr; Quay lại Dashboard
		</a>
		<h2>Cài đặt tài khoản</h2>

		<?php if (!empty($loi)): ?>
			<p style="color:red; text-align:center;"><?php echo htmlspecialchars($loi); ?></p>
		<?php endif; ?>

		<form method="POST" action="">

			<div class="box">
				<h3>Đổi mật khẩu</h3>
				<label>Mật khẩu hiện tại</label> <br />
				<input id="A_Caidattaikhoan_password" name="A_Caidattaikhoan_password" type="password"> <br /><br />

				<label>Mật khẩu mới</label> <br />
				<input id="A_Caidattaikhoan_password_new" name="A_Caidattaikhoan_password_new" type="password"> <br /><br />

				<label>Xác nhận mật khẩu mới</label> <br />
				<input id="A_Caidattaikhoan_password_new_acp" name="A_Caidattaikhoan_password_new_acp" type="password"> <br /><br />
			</div>

			<div style="text-align:center; margin-top:20px;">
				<input type="submit" name="saved" value="Lưu thay đổi" />
			</div>

		</form>
	</div>

</body>
</html>