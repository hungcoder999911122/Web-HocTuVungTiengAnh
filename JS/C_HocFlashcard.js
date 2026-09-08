document.addEventListener("DOMContentLoaded", () => {
    const cardBox = document.getElementById("C_HocFlashcard_cardBox");
    const wordText = document.getElementById("C_HocFlashcard_word");
    const hintText = document.getElementById("C_HocFlashcard_hint");
    const badgeR = document.getElementById("C_HocFlashcard_badgeR");

    const pronunciationArea = document.getElementById(
    "C_HocFlashcard_pronunciationArea"
    );
    const pronunciationText = document.getElementById(
        "C_HocFlashcard_pronunciation"
    );
    const audioButton = document.getElementById(
        "C_HocFlashcard_audioButton"
    );
    const audioPlayer = document.getElementById(
        "C_HocFlashcard_audioPlayer"
    );

    const progressText = document.getElementById(
        "C_HocFlashcard_progressText"
    );
    const progressFill = document.getElementById(
        "C_HocFlashcard_progressFill"
    );
    const statsText = document.getElementById(
        "C_HocFlashcard_stats"
    );

    const btnPrev = document.getElementById(
        "C_HocFlashcard_btnPrev"
    );
    const btnNext = document.getElementById(
        "C_HocFlashcard_btnNext"
    );
    const btnChuaNho = document.getElementById(
        "C_HocFlashcard_btnChuaNho"
    );
    const btnDaNho = document.getElementById(
        "C_HocFlashcard_btnDaNho"
    );
    const btnVaoQuiz = document.getElementById(
        "C_HocFlashcard_btnVaoQuiz"
    );
    const btnKetThuc = document.getElementById(
        "C_HocFlashcard_btnKetThuc"
    );

    /*
     * Không tạo dữ liệu giả khi database không trả về từ nào.
     * Dữ liệu giả có thể khiến người dùng tưởng rằng đang học
     * một từ thật thuộc chủ đề.
     */
    const cards = Array.isArray(flashcardsData)
        ? flashcardsData
        : [];

    const sessionConfig = flashcardSessionConfig || {
        topicId: 0,
        mode: "new_learning"
    };

    let currentIndex = 0;
    let isFlipped = false;

    /*
     * Lưu một trạng thái duy nhất cho mỗi từ:
     *
     * {
     *   15: "da_nho",
     *   16: "chua_nho"
     * }
     *
     * Nếu người dùng đổi ý, giá trị cũ bị thay thế chứ không
     * bị cộng thêm vào thống kê.
     */
    const cardStatuses = {};

    function getCurrentCard() {
        return cards[currentIndex];
    }

    function renderEmptyState() {
        wordText.textContent = "Chủ đề này chưa có từ vựng";
        hintText.textContent = "Hãy quay lại và chọn chủ đề khác.";

        pronunciationText.textContent = "";
        audioButton.hidden = true;

        progressText.textContent = "Thẻ 0/0";
        progressFill.style.width = "0%";

        btnPrev.disabled = true;
        btnNext.disabled = true;
        btnChuaNho.disabled = true;
        btnDaNho.disabled = true;
        btnVaoQuiz.disabled = true;
    }

    function renderPronunciation(card) {
        const pronunciation = card.phien_am?.trim();

        pronunciationText.textContent = pronunciation || "Chưa có phiên âm";

        const audioUrl = card.audio_url?.trim();

        if (audioUrl) {
            audioButton.hidden = false;
            audioPlayer.src = audioUrl;
        } else {
            audioButton.hidden = true;
            audioPlayer.removeAttribute("src");
        }
    }

    function renderAssessmentButtons(cardId) {
        const currentStatus = cardStatuses[cardId] || null;

        const isRemembered = currentStatus === "da_nho";
        const isNotRemembered = currentStatus === "chua_nho";

        btnDaNho.classList.toggle(
            "C_HocFlashcard_btnSelected",
            isRemembered
        );

        btnChuaNho.classList.toggle(
            "C_HocFlashcard_btnSelected",
            isNotRemembered
        );

        btnDaNho.setAttribute("aria-pressed", String(isRemembered));
        btnChuaNho.setAttribute("aria-pressed", String(isNotRemembered));
    }

    function renderCard() {
        if (cards.length === 0) {
            renderEmptyState();
            return;
        }

        const currentCard = getCurrentCard();

        isFlipped = false;
        cardBox.classList.remove("is-flipped");

        wordText.textContent = currentCard.tu_vung;
        hintText.textContent = "Nhấn vào thẻ để xem nghĩa";

        progressText.textContent =
            `Thẻ ${currentIndex + 1}/${cards.length}`;

        const progressPercent =
            ((currentIndex + 1) / cards.length) * 100;

        progressFill.style.width = `${progressPercent}%`;

        badgeR.style.display = currentCard.is_review
            ? "flex"
            : "none";

        renderPronunciation(currentCard);
        renderAssessmentButtons(currentCard.id);

        btnPrev.disabled = currentIndex === 0;
        btnNext.disabled = currentIndex === cards.length - 1;

        renderStats();
    }

    function getStatistics() {
        let rememberedCount = 0;
        let notRememberedCount = 0;

        Object.values(cardStatuses).forEach((status) => {
            if (status === "da_nho") {
                rememberedCount++;
            }

            if (status === "chua_nho") {
                notRememberedCount++;
            }
        });

        return {
            rememberedCount,
            notRememberedCount,
            assessedCount: rememberedCount + notRememberedCount
        };
    }

    function renderStats() {
        const statistics = getStatistics();

        statsText.innerHTML = `
            Đã đánh giá: <strong>${statistics.assessedCount}</strong>
            &nbsp;&bull;&nbsp;
            Đã nhớ: <strong>${statistics.rememberedCount}</strong>
            &nbsp;&bull;&nbsp;
            Chưa nhớ: <strong>${statistics.notRememberedCount}</strong>
        `;

        const isAllCardsAssessed =
            cards.length > 0 &&
            statistics.assessedCount === cards.length;

        btnVaoQuiz.disabled = !isAllCardsAssessed;

        btnVaoQuiz.textContent = isAllCardsAssessed
            ? "Bắt đầu Quiz"
            : "Hoàn thành đánh giá để làm Quiz";
    }

    function setCardStatus(status) {
        const currentCard = getCurrentCard();

        if (!currentCard) {
            return;
        }

        /*
         * Gán đè trạng thái cũ. Đây là điểm giúp tránh việc
         * bấm nhiều lần dẫn đến thống kê sai.
         */
        cardStatuses[currentCard.id] = status;

        renderAssessmentButtons(currentCard.id);
        renderStats();
    }

    function moveToCard(nextIndex) {
        if (nextIndex < 0 || nextIndex >= cards.length) {
            return;
        }

        currentIndex = nextIndex;
        renderCard();
    }

cardBox.addEventListener("click", () => {
    const currentCard = getCurrentCard();

    if (!currentCard) {
        return;
    }

    isFlipped = !isFlipped;

    cardBox.classList.toggle("is-flipped", isFlipped);

    if (isFlipped) {
        wordText.textContent = currentCard.nghia;
        hintText.textContent = "Nhấn vào thẻ để xem từ tiếng Anh";

        pronunciationArea.hidden = true;
    } else {
        wordText.textContent = currentCard.tu_vung;
        hintText.textContent = "Nhấn vào thẻ để xem nghĩa";

        pronunciationArea.hidden = false;
    }
});

    audioButton.addEventListener("click", () => {
        audioPlayer.currentTime = 0;
        audioPlayer.play().catch(() => {
            alert("Không thể phát audio của từ này.");
        });
    });

    btnDaNho.addEventListener("click", () => {
        setCardStatus("da_nho");
    });

    btnChuaNho.addEventListener("click", () => {
        setCardStatus("chua_nho");
    });

    btnPrev.addEventListener("click", () => {
        moveToCard(currentIndex - 1);
    });

    btnNext.addEventListener("click", () => {
        moveToCard(currentIndex + 1);
    });

    btnKetThuc.addEventListener("click", () => {
        const shouldEnd = confirm(
            "Bạn có chắc chắn muốn kết thúc phiên học này?"
        );

        if (shouldEnd) {
            /*
             * File B_DanhSachChuDe.php nằm trong pages/main,
             * không nằm trong pages/user.
             */
            window.location.href = "../main/B_DanhSachChuDe.php";
        }
    });

    btnVaoQuiz.addEventListener("click", () => {
        /*
         * Chưa chuyển sang Quiz ngay ở bước này.
         * Bước tiếp theo sẽ gọi save_flashcard_session.php,
         * lưu phiên học và nhận learning_session_id từ PHP.
         */
        console.log({
            topicId: sessionConfig.topicId,
            mode: sessionConfig.mode,
            cardStatuses
        });

        alert(
            "Flashcard đã sẵn sàng. Bước tiếp theo sẽ lưu phiên học " +
            "và chuyển đúng danh sách từ sang Quiz."
        );
    });

    renderCard();
});