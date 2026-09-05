document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy các phần tử DOM
    const progressText = document.getElementById("C_Quiz_progressText");
    const progressFill = document.getElementById("C_Quiz_progressFill");
    const timerCircle = document.getElementById("C_Quiz_timerCircle");
    const questionWord = document.getElementById("C_Quiz_questionWord");
    const optionsGrid = document.getElementById("C_Quiz_optionsGrid");
    const btnCauTruoc = document.getElementById("C_Quiz_btnCauTruoc");
    const btnCauTiep = document.getElementById("C_Quiz_btnCauTiep");
    const bubblesRow = document.getElementById("C_Quiz_bubblesRow");

    // Dữ liệu từ PHP truyền sang
    const questions = (typeof quizQuestions !== "undefined" && quizQuestions.length > 0)
        ? quizQuestions
        : [{ id: 1, tu_vung: "Airport", dap_an: ["A. Sân bay", "B. Bến xe", "C. Nhà ga", "D. Bến tàu"], dap_an_dung: 0 }];

    let currentIndex = 0;
    const userAnswers = {}; // Lưu đáp án người dùng chọn: { 0: 1, 1: 0,... }
    
    // Đồng hồ đếm ngược
    const TIME_PER_QUESTION = 15;
    let timeLeft = TIME_PER_QUESTION;
    let timerInterval = null;

    // 2. Tạo danh sách bóng tròn (Bubbles)
    function initBubbles() {
        bubblesRow.innerHTML = "";
        questions.forEach((q, index) => {
            const bubble = document.createElement("span");
            bubble.classList.add("C_Quiz_bubble");
            bubble.textContent = index + 1;
            bubble.addEventListener("click", () => {
                currentIndex = index;
                renderQuestion();
            });
            bubblesRow.appendChild(bubble);
        });
    }

    // 3. Đếm ngược thời gian
    function startTimer() {
        clearInterval(timerInterval);
        timeLeft = TIME_PER_QUESTION;
        timerCircle.textContent = `${timeLeft}s`;
        timerCircle.classList.remove("warning");

        timerInterval = setInterval(() => {
            timeLeft--;
            timerCircle.textContent = `${timeLeft}s`;

            if (timeLeft <= 5) {
                timerCircle.classList.add("warning");
            }

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                // Hết giờ -> tự động sang câu tiếp theo hoặc nộp bài
                handleNext();
            }
        }, 1000);
    }

    // 4. Render câu hỏi hiện tại
    function renderQuestion() {
        const q = questions[currentIndex];

        // Cập nhật text và thanh tiến độ
        progressText.textContent = `Câu ${currentIndex + 1}/${questions.length}`;
        const percentage = ((currentIndex + 1) / questions.length) * 100;
        progressFill.style.width = `${percentage}%`;

        // Cập nhật từ vựng
        questionWord.textContent = q.tu_vung;

        // Cập nhật các nút đáp án
        optionsGrid.innerHTML = "";
        q.dap_an.forEach((optText, optIndex) => {
            const btn = document.createElement("button");
            btn.type = "button";
            btn.classList.add("C_Quiz_optionBtn");
            btn.textContent = optText;

            // Đánh dấu nếu câu này người dùng đã chọn trước đó
            if (userAnswers[currentIndex] === optIndex) {
                btn.classList.add("C_Quiz_optionSelected");
            }

            // Xử lý chọn đáp án
            btn.addEventListener("click", () => {
                userAnswers[currentIndex] = optIndex;
                
                // Xóa chọn ở các nút khác và active nút vừa bấm
                const allBtns = optionsGrid.querySelectorAll(".C_Quiz_optionBtn");
                allBtns.forEach(b => b.classList.remove("C_Quiz_optionSelected"));
                btn.classList.add("C_Quiz_optionSelected");

                updateBubbles();
            });

            optionsGrid.appendChild(btn);
        });

        // Bật/tắt nút "Câu trước"
        btnCauTruoc.disabled = (currentIndex === 0);

        // Nếu ở câu cuối cùng -> Đổi chữ nút thành "Nộp bài"
        if (currentIndex === questions.length - 1) {
            btnCauTiep.textContent = "Nộp bài 🎉";
        } else {
            btnCauTiep.textContent = "Câu tiếp \u2192";
        }

        updateBubbles();
        startTimer();
    }

    // Cập nhật trạng thái bóng câu hỏi
    function updateBubbles() {
        const bubbles = bubblesRow.querySelectorAll(".C_Quiz_bubble");
        bubbles.forEach((b, index) => {
            b.classList.remove("active");
            if (index === currentIndex) {
                b.classList.add("active");
            }
            if (userAnswers[index] !== undefined) {
                b.classList.add("C_Quiz_bubbleFilled");
            } else {
                b.classList.remove("C_Quiz_bubbleFilled");
            }
        });
    }

    // 5. Xử lý nút "Câu tiếp" hoặc "Nộp bài"
    function handleNext() {
        if (currentIndex < questions.length - 1) {
            currentIndex++;
            renderQuestion();
        } else {
            // Đã làm hết tất cả các câu hỏi -> Hoàn thành Quiz
            submitQuiz();
        }
    }

    // 6. Nộp bài và chuyển sang trang C_KetquaQuiz.php
    function submitQuiz() {
        clearInterval(timerInterval);

        // Tính điểm
        let diemSo = 0;
        questions.forEach((q, index) => {
            if (userAnswers[index] === q.dap_an_dung) {
                diemSo++;
            }
        });

        console.log(`Kết quả: ${diemSo}/${questions.length}`);

        /* =================================================================
           [GHI CHÚ KẾT NỐI THEO QUY TRÌNH LEADER]
           - Tại đây bạn có thể lưu điểm vào Session hoặc CSDL bằng fetch() POST.
           - Sau khi hoàn thành, tự động chuyển hướng sang C_KetquaQuiz.php:
           ================================================================= */
        sessionStorage.setItem("quiz_score", diemSo);
        sessionStorage.setItem("quiz_total", questions.length);

        // Chuyển trang theo đúng yêu cầu
        window.location.href = "C_KetquaQuiz.php";
    }

    // Gắn sự kiện nút
    btnCauTiep.addEventListener("click", handleNext);

    btnCauTruoc.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            renderQuestion();
        }
    });

    // Khởi tạo Quiz
    initBubbles();
    renderQuestion();
});