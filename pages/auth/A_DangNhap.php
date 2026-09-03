<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");
session_start();

$loi = "";

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $email    = $_POST['A_DangNhap_Email'];
    $password = $_POST['A_DangNhap_password'];

    if(empty($email) || empty($password)) 
    {
        $loi = "Vui lòng nhập đầy đủ thông tin đăng nhập.";
    }
    else
    {
        $sql = "SELECT userID, password_hash, full_name FROM Users WHERE email='$email'";
        $result = mysqli_query($link, $sql);

        if(mysqli_num_rows($result) == 0) {
            $loi = "Email chưa được đăng ký.";
        } else {
            $row = mysqli_fetch_assoc($result);

            if(!password_verify($password, $row['password_hash'])) {
                $loi = "Sai mật khẩu.";
            } else {
                $_SESSION['user_id']   = $row['userID'];
                $_SESSION['full_name'] = $row['full_name'];

                header("Location: A_Caidattaikhoan.php");
                exit();
            }
        }
        mysqli_close($link);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="/CSS/Style.css">
	<link rel="stylesheet" type="text/css" href="/CSS/A_DangNhap.css">
	<script src="/JS/jquery-4.0.0.min.js"></script>
	<title>Đăng nhập</title>
</head>

<body>
	<fieldset>
		<div class="box">
			<h2>Đăng nhập</h2>

			<?php if (!empty($loi)) { ?>
				<p style="color:red; text-align:center;"><?php echo $loi; ?></p>
			<?php } ?>

			<form method="POST" action="">
				<label>Email</label> <br />
				<input type="email" id="A_DangNhap_Email" name="A_DangNhap_Email" placeholder="Vui lòng nhập Email">

				<label>Mật khẩu</label> <br />
				<input type="password" id="A_DangNhap_password" name="A_DangNhap_password" placeholder="Vui lòng nhập Mật khẩu">

				<div>
					<a href="A_QuenMatKhau.php">Quên mật khẩu?</a>
				</div>

				<input type="submit" name="DangNhap_btn" id="DangNhap_btn" value="Đăng nhập" />
			</form>

			<div>
				<label>hoặc</label> <br />
				<input type="button" name="A_DangNhap_TaoTaiKhoan" id="A_DangNhap_TaoTaiKhoan" value="Tạo tài khoản" />
			</div>

			<script>
				$(document).ready(function () {
					$('#A_DangNhap_TaoTaiKhoan').click(function () {
						window.location.href = "A_DangKy.php";
					});
				});
			</script>
		</div>
	</fieldset>
</body>
</html>