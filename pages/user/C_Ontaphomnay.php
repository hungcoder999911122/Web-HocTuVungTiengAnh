<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

/* =========================================================================
   [KHU VỰC XỬ LÝ DỮ LIỆU TỪ DATABASE THEO YÊU CẦU LEADER]
   - Khi kết nối DB thật, dùng đúng tên cột trong CSDL của bạn.
   - Dưới đây là dữ liệu mẫu để chạy thử giao diện:
   ========================================================================= */

$tong_tu_on_tap = 24;
$tu_da_hoc      = 15; // Số từ đã học ở bước 1 (FlashCard)
$quiz_da_lam    = 0;

// Tính % tiến độ bước 1
$phan_tram_b1 = ($tong_tu_on_tap > 0) ? round(($tu_da_hoc / $tong_tu_on_tap) * 100) : 0;

// Mở khóa bước 2 khi đã học xong 100% từ vựng bước 1
$is_bước2_unlocked = ($tu_da_hoc >= $tong_tu_on_tap);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ôn tập hôm nay - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Ontaphomnay.css">
</head>
<body class="C_Ontaphomnay_body">

    <!-- Header -->
    <header class="C_Ontaphomnay_header">
        <h1 class="C_Ontaphomnay_logo">Ôn tập hôm nay</h1>
        <button type="button" id="C_Ontaphomnay_btnThoat" class="C_Ontaphomnay_btnBack" title="Quay lại">
            &times; Thoát
        </button>
    </header>

    <!-- Khối nội dung chính -->
    <main class="C_Ontaphomnay_main">
        
        <div class="C_Ontaphomnay_infoBox">
            <p class="C_Ontaphomnay_subtitle">
                Bạn có <strong><?php echo $tong_tu_on_tap; ?> từ</strong> cần ôn tập hôm nay, được chia thành 2 bước:
            </p>
        </div>

        <!-- BƯỚC 1: HỌC FLASHCARD -->
        <section class="C_Ontaphomnay_stepCard">
            <div class="C_Ontaphomnay_badge C_Ontaphomnay_badgeActive">1</div>

            <div class="C_Ontaphomnay_stepContent">
                <h2 class="C_Ontaphomnay_stepTitle">Học FlashCard</h2>
                <p class="C_Ontaphomnay_stepDesc">
                    Ôn lại <?php echo $tong_tu_on_tap; ?> từ bằng thẻ ghi nhớ, đánh dấu đã nhớ / chưa nhớ.
                </p>

                <div class="C_Ontaphomnay_progressRow">
                    <div class="C_Ontaphomnay_progressBar">
                        <div class="C_Ontaphomnay_progressFill" style="width: <?php echo $phan_tram_b1; ?>%;"></div>
                    </div>
                    <span class="C_Ontaphomnay_progressText"><?php echo $tu_da_hoc; ?>/<?php echo $tong_tu_on_tap; ?></span>
                </div>

                <button type="button" id="C_Ontaphomnay_btnTiepTucHoc" class="C_Ontaphomnay_btnAction">
                    Tiếp tục học
                </button>
            </div>
        </section>

        <!-- BƯỚC 2: LÀM QUIZ ÔN TẬP -->
        <section class="C_Ontaphomnay_stepCard <?php echo !$is_bước2_unlocked ? 'is-locked' : ''; ?>">
            <div class="C_Ontaphomnay_badge <?php echo $is_bước2_unlocked ? 'C_Ontaphomnay_badgeActive' : ''; ?>">2</div>

            <div class="C_Ontaphomnay_stepContent">
                <h2 class="C_Ontaphomnay_stepTitle">Làm Quiz ôn tập</h2>
                <p class="C_Ontaphomnay_stepDesc">
                    Kiểm tra phản xạ và ghi nhớ sâu các từ vựng sau khi hoàn thành FlashCard.
                </p>

                <div class="C_Ontaphomnay_progressRow">
                    <div class="C_Ontaphomnay_progressBar">
                        <div class="C_Ontaphomnay_progressFill" style="width: 0%;"></div>
                    </div>
                    <span class="C_Ontaphomnay_progressText">0/<?php echo $tong_tu_on_tap; ?></span>
                </div>

                <?php if (!$is_bước2_unlocked): ?>
                    <p class="C_Ontaphomnay_lockNote">
                        🔒 Hoàn thành bước 1 để mở khóa
                    </p>
                <?php else: ?>
                    <button type="button" id="C_Ontaphomnay_btnVaoQuiz" class="C_Ontaphomnay_btnAction">
                        Bắt đầu làm Quiz
                    </button>
                <?php endif; ?>
            </div>
        </section>

        <!-- Ghi chú thuật toán Spaced Repetition -->
        <footer class="C_Ontaphomnay_bannerContainer">
            <div class="C_Ontaphomnay_bannerBox">
                💡 <strong>Ghi chú:</strong> Sau khi hoàn thành, hệ thống sẽ tự động tính toán thời gian ngắt quãng (Spaced Repetition) để lên lịch ôn lại tối ưu cho bạn.
            </div>
        </footer>

    </main>

    <script src="../../JS/C_Ontaphomnay.js"></script>
</body>
</html>