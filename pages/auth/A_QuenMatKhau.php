<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

$loi = "";
$thanhcong = "";

// Xử lý khi người dùng ấn nút Xác Nhận OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_xacnhan'])) {
    $otp_input = trim($_POST['otp'] ?? '');

    if (empty($otp_input)) {
        $loi = "Vui lòng nhập mã OTP!";
    } elseif (!isset($_SESSION['reset_otp']) || time() > $_SESSION['otp_expire']) {
        $loi = "Mã OTP đã hết hạn hoặc không tồn tại! Vui lòng yêu cầu gửi lại mã.";
    } elseif ($otp_input != $_SESSION['reset_otp']) {
        $loi = "Mã OTP không chính xác!";
    } else {
        // Đúng OTP -> Chuyển sang trang Đặt lại mật khẩu
        $email = $_SESSION['reset_email'];
        header("Location: /pages/auth/A_DatLaiMatKhau.php?email=" . urlencode($email));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu - LexiLoop</title>
    <link rel="stylesheet" href="/CSS/A_QuenMatKhau.css">
    <link rel="stylesheet" href="/CSS/Style.css">
    <script src="/JS/jquery-4.0.0.min.js"></script>
</head>
<body>
    <div class="box" style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; text-align: center;">
        <h2>Quên mật khẩu</h2>
        
        <p id="msg" style="font-weight: bold; color: red;"><?php echo $loi; ?></p>

        <!-- BƯỚC 1: Nhập Email để nhận mã -->
        <div id="step-email">
            <label>Nhập Email tài khoản của bạn:</label><br><br>
            <input type="email" id="email_input" placeholder="nhap-email@gmail.com" style="width: 80%; padding: 8px;"><br><br>
            <button type="button" id="btn_gui_otp" style="padding: 8px 15px; cursor: pointer;">Gửi mã OTP</button>
        </div>

        <!-- BƯỚC 2: Nhập OTP (Mặc định ẩn, gửi OTP thành công mới hiện) -->
        <form id="step-otp" method="POST" style="display: none; margin-top: 20px;">
            <label>Nhập mã OTP (6 chữ số):</label><br><br>
            <input type="text" name="otp" maxlength="6" style="width: 80%; padding: 8px; text-align: center; letter-spacing: 5px; font-weight: bold;"><br><br>
            <button type="submit" name="btn_xacnhan" style="padding: 8px 15px; cursor: pointer;">Xác nhận OTP</button>
        </form>
    </div>

    <!-- Script xử lý Ajax gửi mã OTP mà không cần reload trang -->
    <script>
    $(document).ready(function() {
        $('#btn_gui_otp').click(function() {
            var email = $('#email_input').val();
            if(!email) {
                $('#msg').css('color', 'red').text('Vui lòng nhập Email!');
                return;
            }

            $('#msg').css('color', 'blue').text('Đang gửi mã OTP, vui lòng chờ...');
            $(this).prop('disabled', true);

            $.ajax({
                url: '/pages/auth/A_Gui_otp.php',
                type: 'POST',
                data: { email: email },
                dataType: 'json',
                success: function(response) {
                    if(response.status === 'success') {
                        $('#msg').css('color', 'green').text(response.message);
                        $('#step-email').hide();
                        $('#step-otp').show();
                    } else {
                        $('#msg').css('color', 'red').text(response.message);
                        $('#btn_gui_otp').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#msg').css('color', 'red').text('Có lỗi xảy ra, vui lòng thử lại!');
                    $('#btn_gui_otp').prop('disabled', false);
                }
            });
        });
    });
    </script>
</body>
</html>