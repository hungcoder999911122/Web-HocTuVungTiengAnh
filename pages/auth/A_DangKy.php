<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="/CSS/Style.css"> 
	<link rel="stylesheet" type="text/css" href="/CSS/A_DangKy.css"> 
	<script src="/JS/jquery-4.0.0.min.js"></script>
	<title> Đăng ký </title>
</head>

<body>
<header>
</header>
    <div class="box">
        <h2> Tạo tài khoản </h2>
        <h4 style="text-align:center"> Bắt đầu hành trình học từ vựng của bạn </h4>

<form method="POST" action="">
<div>
	<label> Họ Tên </label> <br/>
	<input type="text" id="A_DangKy_fullname" name="A_DangKy_fullname" placeholder="Vui lòng nhập họ và tên" required>
</div>

<div>

	<label> Email </label> <br/>
	<input type="email" id="A_DangKy_email" name="A_DangKy_email" placeholder="Vui lòng nhập Email" required>
</div>

<div>
	<label> Mật khẩu </label> <br/>
	<input type="password" id="A_DangKy_password" name="A_DangKy_password" placeholder="Vui lòng tạo mật khẩu" required>
</div>

<div>
	<label> Xác nhận mật khẩu </label> <br/>
	<input type="password" id="A_DangKy_password_confirm" name="A_DangKy_password_confirm" placeholder="Vui lòng tạo mật khẩu" required>
</div>


<div>
	<input type="checkbox" id="A_DangKy_agree" 
name="A_DangKy_agree" placeholder="Vui lòng tạo mật khẩu" required>

	<label> Tôi đồng ý với điều khoản sử dụng  </label> <br/>
</div>
<div>
	<input type="submit" name="A_DangKybtn" id="A_DangKybtn" value="Đăng ký"  />
</div>
</form>

<div>
<a href="A_DangNhap.php"> Đã có tài khoản? Đăng nhập </a>
</div>

	</div>
    <script>
        $(document).ready(function () {
            $('#A_DangKybtn').click(function (event) {
                event.preventDefault(); // Ngăn chặn hành vi mặc định của nút submit
                var fullname = $('#A_DangKy_fullname').val();
                var email = $('#A_DangKy_email').val();
                var password = $('#A_DangKy_password').val();
                var passwordConfirm = $('#A_DangKy_password_confirm').val();
                var agree = $('#A_DangKy_agree').is(':checked');
                // Kiểm tra các trường dữ liệu
                if (!fullname || !email || !password || !passwordConfirm) {
                    alert('Vui lòng điền đầy đủ thông tin.');
                    return;
                }
                if (password !== passwordConfirm) {
                    alert('Mật khẩu xác nhận không khớp.');
                    return;
                }
                if (!agree) {
                    alert('Bạn phải đồng ý với điều khoản sử dụng.');
                    return;
                }
                // Nếu tất cả kiểm tra hợp lệ, chuyển hướng đến trang chính
                window.location.href = "/pages/main/B_homepage.php";
            });
        });
    </script>
<footer>


</footer>

<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $fullname   = $_POST['A_DangKy_fullname'];
    $email   = $_POST['A_DangKy_email'];
    $password   = $_POST['A_DangKy_password'];
    $passwordConfirm   = $_POST['A_DangKy_password_confirm'];
    $agree   = isset($_POST['A_DangKy_agree']); //để không lỗi khi không tick
    $Dangkybtn   = $_POST['A_DangKybtn'];
  
    //Kiểm tra dữ liệu
        $loi="";
    if(empty($fullname) || empty($email) || empty($password) || empty($passwordConfirm)) {
	    $loi="Vui lòng nhập đầy đủ thông tin.";

    }
           elseif($password != $passwordConfirm) 
    {
	$loi="Mật khẩu và xác nhận mật khẩu không khớp.";
    }
    elseif(strlen($password) < 6)
    {
	$loi="Mật khẩu phải có ít nhất 6 ký tự.";
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) 
    {
    $loi="Email không hợp lệ.";
    }
    elseif(!isset($agree)) 
    {
    $loi="Bạn phải đồng ý với điều khoản sử dụng.";
    }
    else
    {
    //Kết nối CSDL
    //Kiểm tra email đã tồn tại chưa
    $sql = "SELECT * FROM Users WHERE email='$email'";
    $result = mysqli_query($link, $sql);
    if(mysqli_num_rows($result) > 0) {
        $loi="Email đã tồn tại.";
    }
    else{
        //Mã hóa mật khẩu
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        //Thêm dữ liệu vào CSDL
        $sql = "INSERT INTO Users (full_name, email, password_hash) VALUES ('$fullname', '$email', '$password_hash')";
        if(mysqli_query($link, $sql)) {
            echo "<script>alert('Đăng ký thành công!'); window.location.href='/pages/main/B_homepage.php';</script>";
            exit();
        } else {
            $loi="Lỗi: " . mysqli_error($link);
        }
    }
    mysqli_close($link);
    }
}


?>
	
</body>
</html>
