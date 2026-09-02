<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ôn tập hôm nay - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Ontaphomnay.css">
</head>
<body class="C_Ontaphomnay_body">

    <header class="C_Ontaphomnay_header">
        <h1 class="C_Ontaphomnay_logo">Ôn tập hôm nay</h1>
    </header>

    <main class="C_Ontaphomnay_main">
        <p class="C_Ontaphomnay_subtitle">
            Bạn có 24 từ cần ôn tập, được chia thành 2 bước
        </p>

        <!-- HỌC FLASHCARD  -->
        <section class="C_Ontaphomnay_stepCard">
            <div class="C_Ontaphomnay_badge">1</div>

            <div class="C_Ontaphomnay_stepContent">
                <h2 class="C_Ontaphomnay_stepTitle">Học FlashCard</h2>
                <p class="C_Ontaphomnay_stepDesc">
                    Ôn lại 24 từ bằng thẻ ghi nhớ, đánh dấu đã nhớ / chưa nhớ
                </p>

                <div class="C_Ontaphomnay_progressRow">
                    <div class="C_Ontaphomnay_progressBar">
                        <div class="C_Ontaphomnay_progressFill" style="width: 62.5%;"></div>
                    </div>
                    <span class="C_Ontaphomnay_progressText">15/24</span>
                </div>

                <button type="button" id="C_Ontaphomnay_btnTiepTucHoc" class="C_Ontaphomnay_btnAction">
                    Tiếp tục học
                </button>
            </div>
        </section>

        <!-- LÀM QUIZ ÔN TẬP  -->
        <section class="C_Ontaphomnay_stepCard">
            <div class="C_Ontaphomnay_badge">2</div>

            <div class="C_Ontaphomnay_stepContent">
                <h2 class="C_Ontaphomnay_stepTitle">Làm Quiz ôn tập</h2>
                <p class="C_Ontaphomnay_stepDesc">
                    Kiểm tra lại kiến thức sau khi học FlashCard
                </p>

                <div class="C_Ontaphomnay_progressRow">
                    <div class="C_Ontaphomnay_progressBar"></div>
                </div>

                <p class="C_Ontaphomnay_lockNote">
                    Hoàn thành bước 1 để mở khóa
                </p>
            </div>
        </section>

        <footer class="C_Ontaphomnay_bannerContainer">
            <div class="C_Ontaphomnay_bannerBox">
                Sau khi hoàn thành, dữ liệu Spaced Repetition của bạn sẽ được cập nhật tự động
            </div>
        </footer>

    </main>

</body>
</html>