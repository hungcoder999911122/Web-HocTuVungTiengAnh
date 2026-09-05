<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC XỬ LÝ DATABASE THEO QUY TẮC CỦA LEADER]
   - Leader lưu ý: Khi kết nối DB phải lấy đúng y chang tên cột trong CSDL.
   - Dưới đây là dữ liệu mẫu để hiển thị giao diện.
   - Khi kết nối DB thật, bạn nhận dữ liệu từ Session hoặc câu lệnh INSERT kết quả:
     $id_user = $_SESSION['user_id'];
     $sql = "INSERT INTO ket_qua_quiz (id_nguoidung, diem_so, tong_cau, thoi_gian_lam, ngay_thi) 
             VALUES (?, ?, ?, ?, NOW())";
   ========================================================================= */

// Dữ liệu mẫu ban đầu (sẽ được JS cập nhật nếu có dữ liệu từ bài Quiz vừa làm)
$diem_so     = 8;
$tong_cau    = 10;
$thoi_gian   = "3:45";
$do_chinh_xac = round(($diem_so / $tong_cau) * 100) . "%";

// Đánh giá xếp loại
if ($diem_so >= 9) {
    $xep_hang = "Xuất sắc";
    $feedback = "Tuyệt vời! Bạn nắm từ vựng rất vững!";
} elseif ($diem_so >= 7) {
    $xep_hang = "Khá";
    $feedback = "Bạn làm rất tốt! Cố gắng phát huy nhé!";
} else {
    $xep_hang = "Cần cố gắng";
    $feedback = "Hãy ôn lại các từ chưa nhớ và thử lại nhé!";
}

$cau_sai = [
    ["cau" => 3, "tu" => "Software", "nghia_dung" => "Phần mềm"],
    ["cau" => 7, "tu" => "Meeting", "nghia_dung" => "Cuộc họp"]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả Quiz - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_KetquaQuiz.css">
</head>
<body class="C_KetquaQuiz_body">

    <!-- Header -->
    <header class="C_KetquaQuiz_header">
        <h1 class="C_KetquaQuiz_logo">Kết quả Quiz</h1>
        <button type="button" id="C_KetquaQuiz_btnDong" class="C_KetquaQuiz_btnClose" title="Đóng">
            &times; Thoát
        </button>
    </header>

    <main class="C_KetquaQuiz_main">
        
        <!-- Vòng tròn hiển thị điểm -->
        <div class="C_KetquaQuiz_scoreCircle" id="C_KetquaQuiz_scoreCircle">
            <span class="C_KetquaQuiz_scoreText" id="C_KetquaQuiz_scoreText">
                <?php echo "{$diem_so}/{$tong_cau}"; ?>
            </span>
        </div>

        <!-- Lời khen / Nhận xét -->
        <p class="C_KetquaQuiz_feedback" id="C_KetquaQuiz_feedback">
            <?php echo $feedback; ?>
        </p>

        <!-- 3 Thẻ thống kê chi tiết -->
        <section class="C_KetquaQuiz_statsContainer">
            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Thời gian</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statTime"><?php echo $thoi_gian; ?></span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Độ chính xác</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statAccuracy"><?php echo $do_chinh_xac; ?></span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Xếp hạng</span>
                <span class="C_KetquaQuiz_statValue" id="C_KetquaQuiz_statRank"><?php echo $xep_hang; ?></span>
            </div>
        </section>

        <!-- Cụm 3 nút hành động -->
        <div class="C_KetquaQuiz_btnGroup">
            <button type="button" id="C_KetquaQuiz_btnXemLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Xem lại câu sai
            </button>
            <button type="button" id="C_KetquaQuiz_btnLamLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnPrimary">
                Làm lại Quiz
            </button>
            <button type="button" id="C_KetquaQuiz_btnDashboard" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Về Dashboard
            </button>
        </div>

        <!-- Khối hiển thị chi tiết câu sai (có thể bấm mở rộng) -->
        <footer class="C_KetquaQuiz_wrongBoxContainer">
            <div class="C_KetquaQuiz_wrongBox" id="C_KetquaQuiz_wrongBox">
                <span class="wrong-icon">⚠️</span>
                <span><strong>Câu cần xem lại:</strong> Câu 3 (Software), Câu 7 (Meeting) - <em>Nhấn để xem chi tiết</em></span>
            </div>
            
            <div class="C_KetquaQuiz_wrongDetail" id="C_KetquaQuiz_wrongDetail" style="display: none;">
                <ul>
                    <?php foreach ($cau_sai as $item): ?>
                        <li><strong>Câu <?php echo $item['cau']; ?>:</strong> <?php echo $item['tu']; ?> &rarr; Nghĩa đúng: <span class="correct-text"><?php echo $item['nghia_dung']; ?></span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </footer>

    </main>

    <script src="../../JS/C_KetquaQuiz.js"></script>
</body>
</html>