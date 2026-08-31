<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả Quiz - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_KetquaQuiz.css">
</head>
<body class="C_KetquaQuiz_body">

    <header class="C_KetquaQuiz_header">
        <h1 class="C_KetquaQuiz_logo">Kết quả Quiz</h1>
    </header>

    <main class="C_KetquaQuiz_main">
        <div class="C_KetquaQuiz_scoreCircle">
            <span class="C_KetquaQuiz_scoreText">8/10</span>
        </div>

        <p class="C_KetquaQuiz_feedback">Bạn làm rất tốt!</p>

        <section class="C_KetquaQuiz_statsContainer">
            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Thời gian</span>
                <span class="C_KetquaQuiz_statValue">3:45</span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Độ chính xác</span>
                <span class="C_KetquaQuiz_statValue">80%</span>
            </div>

            <div class="C_KetquaQuiz_statCard">
                <span class="C_KetquaQuiz_statLabel">Xếp hạng</span>
                <span class="C_KetquaQuiz_statValue">Khá</span>
            </div>
        </section>

        <div class="C_KetquaQuiz_btnGroup">
            <button type="button" id="C_KetquaQuiz_btnXemLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Xem lại bài làm
            </button>
            <button type="button" id="C_KetquaQuiz_btnLamLai" class="C_KetquaQuiz_btn C_KetquaQuiz_btnGray">
                Làm lại Quiz
            </button>
            <button type="button" id="C_KetquaQuiz_btnDashboard" class="C_KetquaQuiz_btn C_KetquaQuiz_btnWhite">
                Về Dashboard
            </button>
        </div>

        <footer class="C_KetquaQuiz_wrongBoxContainer">
            <div class="C_KetquaQuiz_wrongBox" id="C_KetquaQuiz_wrongBox">
                Câu sai: Câu 3 (Software), Câu 7 (Meeting) - nhấn để xem lại đáp án
            </div>
        </footer>

    </main>

</body>
</html>