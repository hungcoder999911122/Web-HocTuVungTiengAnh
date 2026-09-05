<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC TRUY VẤN CSDL THEO QUY TẮC CỦA LEADER]
   - Leader lưu ý: Khi kết nối DB phải lấy đúng y chang tên cột trong CSDL.
   - Dưới đây là mảng câu hỏi mẫu (Quiz Du lịch) để chạy thử nghiệm giao diện.
   - Khi nối DB thật, bạn thay bằng SELECT ngẫu nhiên câu hỏi theo chủ đề:
     $sql = "SELECT id, tu_vung, dapan_dung, dapan_a, dapan_b, dapan_c, dapan_d 
             FROM tbl_quiz WHERE id_chude = ? LIMIT 10";
   ========================================================================= */

$ten_chu_de = "Du lịch";

$danh_sach_cau_hoi = [
    [
        "id"       => 1,
        "tu_vung"  => "Airport",
        "dap_an"   => ["A. Sân bay", "B. Bến xe", "C. Nhà ga", "D. Bến tàu"],
        "dap_an_dung" => 0 // Vị trí A
    ],
    [
        "id"       => 2,
        "tu_vung"  => "Passport",
        "dap_an"   => ["A. Vé máy bay", "B. Hộ chiếu", "C. Giấy phép lái xe", "D. Thẻ căn cước"],
        "dap_an_dung" => 1 // Vị trí B
    ],
    [
        "id"       => 3,
        "tu_vung"  => "Luggage",
        "dap_an"   => ["A. Khách sạn", "B. Bản đồ du lịch", "C. Hành lý", "D. Chuyến bay"],
        "dap_an_dung" => 2 // Vị trí C
    ],
    [
        "id"       => 4,
        "tu_vung"  => "Ticket",
        "dap_an"   => ["A. Vé đi lại", "B. Hộ chiếu", "C. Hướng dẫn viên", "D. Hành lý"],
        "dap_an_dung" => 0 // Vị trí A
    ],
    [
        "id"       => 5,
        "tu_vung"  => "Flight",
        "dap_an"   => ["A. Đường cao tốc", "B. Nhà chờ", "C. Tàu hoả", "D. Chuyến bay"],
        "dap_an_dung" => 3 // Vị trí D
    ]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: <?php echo htmlspecialchars($ten_chu_de); ?> - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Quiz.css">
</head>
<body class="C_Quiz_body">

    <!-- Header -->
    <header class="C_Quiz_header">
        <h1 class="C_Quiz_logo">Quiz: <?php echo htmlspecialchars($ten_chu_de); ?></h1>
        <span class="C_Quiz_progressText" id="C_Quiz_progressText">Câu 1/<?php echo count($danh_sach_cau_hoi); ?></span>
    </header>

    <!-- Thanh tiến độ Quiz -->
    <div class="C_Quiz_progressBarWrapper">
        <div class="C_Quiz_progressBar">
            <div class="C_Quiz_progressFill" id="C_Quiz_progressFill" style="width: 20%;"></div>
        </div>
    </div>

    <!-- Nội dung chính câu hỏi -->
    <main class="C_Quiz_main">
        
        <!-- Đồng hồ đếm ngược -->
        <div class="C_Quiz_timerWrapper">
            <div class="C_Quiz_timerCircle" id="C_Quiz_timerCircle">15s</div>
        </div>

        <!-- Từ vựng câu hỏi -->
        <div class="C_Quiz_questionSection">
            <p class="C_Quiz_instruction">Chọn nghĩa đúng của từ:</p>
            <h2 class="C_Quiz_questionWord" id="C_Quiz_questionWord">Airport</h2>
        </div>

        <!-- Lưới 4 đáp án -->
        <div class="C_Quiz_optionsGrid" id="C_Quiz_optionsGrid">
            <button type="button" class="C_Quiz_optionBtn" data-index="0">A. Sân bay</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="1">B. Bến xe</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="2">C. Nhà ga</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="3">D. Bến tàu</button>
        </div>

        <!-- Các nút điều hướng -->
        <div class="C_Quiz_navButtons">
            <button type="button" id="C_Quiz_btnCauTruoc" class="C_Quiz_btnNav C_Quiz_btnWhite">
                &larr; Câu trước
            </button>
            <button type="button" id="C_Quiz_btnCauTiep" class="C_Quiz_btnNav C_Quiz_btnGray">
                Câu tiếp &rarr;
            </button>
        </div>

        <!-- Danh sách bóng câu hỏi -->
        <footer class="C_Quiz_questionListSection">
            <p class="C_Quiz_listLabel">Danh sách câu hỏi</p>
            <div class="C_Quiz_bubblesRow" id="C_Quiz_bubblesRow"> </div>
        </footer>

    </main>

    <script>
        const quizQuestions = <?php echo json_encode($danh_sach_cau_hoi, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="../../JS/C_Quiz.js"></script>
</body>
</html>