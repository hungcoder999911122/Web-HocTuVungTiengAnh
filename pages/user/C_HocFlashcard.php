<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC TRUY VẤN DỮ LIỆU TỪ DATABASE]
   - Quy tắc của Leader: Lấy đúng y chang tên cột trong CSDL của bạn.
   - Hiện tại để sẵn mảng mẫu $danh_sach_tu để bạn test giao diện ngay.
   - Khi nối DB thật, bạn thay bằng truy vấn SQL (Ví dụ):
     $id_chu_de = isset($_GET['id']) ? intval($_GET['id']) : 1;
     $sql = "SELECT id, tu_vung, nghia, phien_am FROM tbl_tuvung WHERE id_chude = $id_chu_de";
     $result = $conn->query($sql);
     $danh_sach_tu = $result->fetch_all(MYSQLI_ASSOC);
   ========================================================================= */

$danh_sach_tu = [
    [
        "id"       => 1,
        "tu_vung"  => "Software",
        "nghia"    => "Phần mềm",
        "phien_am" => "/ˈsɔːftwer/",
        "is_review"=> true // Thẻ cần ôn tập lại (hiện huy hiệu R)
    ],
    [
        "id"       => 2,
        "tu_vung"  => "Hardware",
        "nghia"    => "Phần cứng",
        "phien_am" => "/ˈhɑːrdwer/",
        "is_review"=> false
    ],
    [
        "id"       => 3,
        "tu_vung"  => "Database",
        "nghia"    => "Cơ sở dữ liệu",
        "phien_am" => "/ˈdeɪtəbeɪs/",
        "is_review"=> true
    ],
    [
        "id"       => 4,
        "tu_vung"  => "Network",
        "nghia"    => "Mạng máy tính",
        "phien_am" => "/ˈnetwɜːrk/",
        "is_review"=> false
    ],
    [
        "id"       => 5,
        "tu_vung"  => "Algorithm",
        "nghia"    => "Thuật toán",
        "phien_am" => "/ˈælɡərɪðəm/",
        "is_review"=> false
    ]
];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Học FlashCard - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_HocFlashcard.css">
</head>
<body class="C_HocFlashcard_body">

    <!-- Header -->
    <header class="C_HocFlashcard_header">
        <h1 class="C_HocFlashcard_logo">Học FlashCard</h1>
        <span class="C_HocFlashcard_progressText" id="C_HocFlashcard_progressText">Thẻ 1/5</span>
    </header>

    <!-- Thanh tiến độ học -->
    <div class="C_HocFlashcard_progressBarWrapper">
        <div class="C_HocFlashcard_progressBar">
            <div class="C_HocFlashcard_progressFill" id="C_HocFlashcard_progressFill"></div>
        </div>
    </div>

    <!-- Khu vực thẻ học và điều hướng -->
    <main class="C_HocFlashcard_main">
        <div class="C_HocFlashcard_cardArea">
            <!-- Nút từ trước -->
            <button type="button" id="C_HocFlashcard_btnPrev" class="C_HocFlashcard_navBtn" title="Thẻ trước">&larr;</button>

            <!-- Hộp thẻ Flashcard -->
            <div class="C_HocFlashcard_cardBox" id="C_HocFlashcard_cardBox">
                <!-- Badge R (Ôn tập - Review) -->
                <div class="C_HocFlashcard_badgeR" id="C_HocFlashcard_badgeR" title="Thẻ cần ôn tập">R</div>
                
                <h2 class="C_HocFlashcard_word" id="C_HocFlashcard_word">Software</h2>
                <p class="C_HocFlashcard_hint" id="C_HocFlashcard_hint">Nhấn để xem nghĩa</p>
            </div>

            <!-- Nút từ tiếp theo -->
            <button type="button" id="C_HocFlashcard_btnNext" class="C_HocFlashcard_navBtn" title="Thẻ tiếp theo">&rarr;</button>
        </div>

        <!-- 2 Nút Đánh Giá -->
        <div class="C_HocFlashcard_btnGroup">
            <button type="button" id="C_HocFlashcard_btnChuaNho" class="C_HocFlashcard_btn C_HocFlashcard_btnWhite">
                Chưa nhớ
            </button>
            <button type="button" id="C_HocFlashcard_btnDaNho" class="C_HocFlashcard_btn C_HocFlashcard_btnGray">
                Đã nhớ
            </button>
        </div>
    </main>

    <!-- Footer thống kê và Kết thúc sớm -->
    <footer class="C_HocFlashcard_footerWrapper">
        <div class="C_HocFlashcard_footerBox">
            <div class="C_HocFlashcard_stats" id="C_HocFlashcard_stats">
                Đã học: 0 &nbsp;&bull;&nbsp; Đã nhớ: 0 &nbsp;&bull;&nbsp; Chưa nhớ: 0
            </div>
            <button type="button" id="C_HocFlashcard_btnKetThuc" class="C_HocFlashcard_btnKetThuc">
                Kết thúc sớm
            </button>
        </div>
    </footer>

    <script>
        const flashcardsData = <?php echo json_encode($danh_sach_tu, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="../../JS/C_HocFlashcard.js"></script>
</body>
</html>