document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy dữ liệu từ sessionStorage nếu vừa làm bài Quiz xong
    const storedScore = sessionStorage.getItem("quiz_score");
    const storedTotal = sessionStorage.getItem("quiz_total");

    const scoreText = document.getElementById("C_KetquaQuiz_scoreText");
    const statAccuracy = document.getElementById("C_KetquaQuiz_statAccuracy");
    const statRank = document.getElementById("C_KetquaQuiz_statRank");
    const feedback = document.getElementById("C_KetquaQuiz_feedback");

    if (storedScore !== null && storedTotal !== null) {
        const score = parseInt(storedScore, 10);
        const total = parseInt(storedTotal, 10);
        const percent = Math.round((score / total) * 100);

        scoreText.textContent = `${score}/${total}`;
        statAccuracy.textContent = `${percent}%`;

        // Đánh giá xếp loại theo điểm
        if (percent >= 90) {
            statRank.textContent = "Xuất sắc";
            feedback.textContent = "Tuyệt vời! Bạn nắm từ vựng rất xuất sắc! 🌟";
        } else if (percent >= 70) {
            statRank.textContent = "Khá";
            feedback.textContent = "Bạn làm rất tốt! Cố gắng phát huy nhé! 👍";
        } else {
            statRank.textContent = "Cần cố gắng";
            feedback.textContent = "Đừng nản lòng, hãy xem lại câu sai và ôn thêm nhé! 💪";
        }
    }

    // 2. Nút "Làm lại Quiz" -> Quay lại trang làm Quiz
    const btnLamLai = document.getElementById("C_KetquaQuiz_btnLamLai");
    if (btnLamLai) {
        btnLamLai.addEventListener("click", () => {
            window.location.href = "C_Quiz.php";
        });
    }

    // 3. Nút "Về Dashboard" hoặc "Thoát"
    const btnDashboard = document.getElementById("C_KetquaQuiz_btnDashboard");
    if (btnDashboard) {
        btnDashboard.addEventListener("click", () => {
            window.location.href = "Dashboard.php";
        });
    }

    const btnDong = document.getElementById("C_KetquaQuiz_btnDong");
    if (btnDong) {
        btnDong.addEventListener("click", () => {
            window.location.href = "B_DanhSachChuDe.php";
        });
    }

    // 4. Bật/Tắt xem chi tiết các câu sai
    const wrongBox = document.getElementById("C_KetquaQuiz_wrongBox");
    const wrongDetail = document.getElementById("C_KetquaQuiz_wrongDetail");
    const btnXemLai = document.getElementById("C_KetquaQuiz_btnXemLai");

    function toggleWrongDetail() {
        if (wrongDetail.style.display === "none" || wrongDetail.style.display === "") {
            wrongDetail.style.display = "block";
        } else {
            wrongDetail.style.display = "none";
        }
    }

    if (wrongBox) {
        wrongBox.addEventListener("click", toggleWrongDetail);
    }
    if (btnXemLai) {
        btnXemLai.addEventListener("click", () => {
            wrongDetail.style.display = "block";
            wrongDetail.scrollIntoView({ behavior: "smooth" });
        });
    }
});