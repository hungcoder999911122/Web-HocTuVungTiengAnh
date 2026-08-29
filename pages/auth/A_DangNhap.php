<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");
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
			<div>
				<form method="POST" action="">
					<label>Email</label> <br />
					<input type="email" id="A_DangNhap_Email" name="A_DangNhap_Email" placeholder="Vui lòng nhập Email">
			</div>

			<div>
					<label>Mật khẩu</label> <br />
					<input type="password" id="A_DangNhap_password" name="A_DangNhap_password" placeholder="Vui lòng nhập Mật khẩu">
			</div>

			<div>
				<a href="A_QuenMatKhau.php">Quên mật khẩu?</a>
			</div>

			<div>
					<input type="submit" name="DangNhap_btn" id="DangNhap_btn" value="Đăng nhập" />
			</div>

			<div>
				<label>hoặc</label> <br />
					<input type="button" name="A_DangNhap_TaoTaiKhoan" id="A_DangNhap_TaoTaiKhoan" value="Tạo tài khoản" />
				</form>
			</div>

			<script>
				$(document).ready(function () {
					$("#DangNhap_btn").click(function () {
						var email = $("#A_DangNhap_Email").val();
						var password = $("#A_DangNhap_password").val();
						if (email === "" || password === "") {
							alert("Vui lòng nhập đầy đủ thông tin đăng nhập.");
						} else {
							window.location.href = "/pages/main/B_homepage.php";
						}
					});

					$('#A_DangNhap_TaoTaiKhoan').click(function () {
						window.location.href = "A_DangKy.php";
					});
				});
			</script>
		</div>
	</fieldset>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $email   = $_POST['A_DangNhap_Email'];
    $password   = $_POST['A_DangNhap_password'];
  
	//Kiểm tra dữ liệu
	$loi="";
	if(empty($email) || empty($password)) 
	{
	$loi="Vui lòng nhập đầy đủ thông tin đăng nhập.";
	}
}


?>
</body>
</html>