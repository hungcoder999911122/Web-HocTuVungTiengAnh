<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 1. Nhúng trực tiếp các file core của PHPMailer theo đúng cấu trúc thư mục dự án
require_once($_SERVER['DOCUMENT_ROOT'] . '/pages/auth/PHPMailer/src/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/pages/auth/PHPMailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/pages/auth/PHPMailer/src/SMTP.php');

// 2. Kết nối CSDL
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trả về JSON để phục vụ cho AJAX
    header('Content-Type: application/json; charset=utf-8');

    $email = trim($_POST['email'] ?? '');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ!']);
        exit;
    }

    // Kiểm tra Email có tồn tại trong bảng Users không
    $checkUser = mysqli_query($link, "SELECT userID FROM Users WHERE email = '$email'");
    if (mysqli_num_rows($checkUser) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Email này chưa được đăng ký!']);
        exit;
    }

    // Sinh mã OTP 6 số ngẫu nhiên
    $otp = random_int(100000, 999999);

    // Lưu OTP và thời hạn vào SESSION (5 phút)
    $_SESSION['reset_otp'] = $otp;
    $_SESSION['reset_email'] = $email;
    $_SESSION['otp_expire'] = time() + 300; 

    // Gửi Email qua PHPMailer
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // >>> ĐIỀN THÔNG TIN GMAIL CỦA BẠN VÀO 2 DÒNG NÀY <<<
        $mail->Username   = 'nyhnk4900@ut.edu.vn'; // Email Gmail của bạn
        $mail->Password   = 'styi aebs pryw akrf';     // Mật khẩu ứng dụng (App Password)
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom($mail->Username, 'LexiLoop Support');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Mã OTP đặt lại mật khẩu - LexiLoop';
        $mail->Body    = "Mã OTP của bạn là: <b style='font-size: 20px; color: red;'>$otp</b>.<br>Mã này có hiệu lực trong 5 phút.";

        $mail->send();
        echo json_encode(['status' => 'success', 'message' => 'Đã gửi mã OTP thành công! Vui lòng kiểm tra hộp thư.']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi gửi mail: ' . $mail->ErrorInfo]);
    }
    exit;
}
?>