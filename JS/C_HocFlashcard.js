document.addEventListener("DOMContentLoaded", () => {
    const byId = (id) => document.getElementById(id);

    const cardBox = byId("C_HocFlashcard_cardBox");
    const wordText = byId("C_HocFlashcard_word");
    const hintText = byId("C_HocFlashcard_hint");
    const badgeR = byId("C_HocFlashcard_badgeR");

    const pronunciationArea = byId("C_HocFlashcard_pronunciationArea");
    const pronunciationText = byId("C_HocFlashcard_pronunciation");
    const audioButton = byId("C_HocFlashcard_audioButton");
    const audioPlayer = byId("C_HocFlashcard_audioPlayer");

    const progressText = byId("C_HocFlashcard_progressText");
    const progressFill = byId("C_HocFlashcard_progressFill");
    const statsText = byId("C_HocFlashcard_stats");

    const btnUndo = byId("C_HocFlashcard_btnPrev"); // nút ← nay dùng để hoàn tác
    const btnNext = byId("C_HocFlashcard_btnNext"); // không còn dùng
    const btnChuaNho = byId("C_HocFlashcard_btnChuaNho");
    const btnDaNho = byId("C_HocFlashcard_btnDaNho");
    const btnKetThuc = byId("C_HocFlashcard_btnKetThuc");

    /*
     * ============================================================
     * LUỒNG HỌC
     * ------------------------------------------------------------
     * - Danh sách từ được chia thành các nhóm GROUP_SIZE từ.
     * - Trong một nhóm: học từng thẻ một lượt (một "vòng").
     *     + "Đã nhớ"  -> từ được tính là đã thuộc, thanh tiến trình tăng,
     *                    từ không xuất hiện lại trong phiên này.
     *     + "Chưa nhớ" -> thanh tiến trình giữ nguyên, từ vào danh sách
     *                    cần học lại.
     * - Hết vòng: nếu còn từ chưa nhớ thì mở vòng mới CHỈ gồm các từ đó
     *   (xáo trộn thứ tự). Lặp đến khi cả nhóm đã nhớ hết.
     * - Xong nhóm thì sang nhóm kế tiếp. Nhớ hết mọi từ = hoàn thành.
     * - Mỗi lần vào học là bắt đầu lại từ đầu (không resume phiên cũ).
     * ============================================================
     */
    const GROUP_SIZE = 10;
    const MAX_UNDO = 30;

    const cards = Array.isArray(flashcardsData) ? flashcardsData : [];
    const cardsById = new Map(cards.map((card) => [Number(card.id), card]));

    const sessionConfig = flashcardSessionConfig || {
        topicId: 0,
        mode: "new_learning"
    };

    // Chia nhóm theo thứ tự PHP trả về.
    const groups = [];
    for (let i = 0; i < cards.length; i += GROUP_SIZE) {
        groups.push(cards.slice(i, i + GROUP_SIZE).map((card) => Number(card.id)));
    }

    // Trạng thái phiên học.
    let cardStatuses = {};              // { id: "da_nho" | "chua_nho" }
    let groupIndex = 0;                 // nhóm hiện tại
    let round = 1;                      // vòng hiện tại trong nhóm
    let queue = groups.length ? groups[0].slice() : []; // id các thẻ của vòng này
    let queuePos = 0;                   // vị trí thẻ hiện tại trong queue
    let missedIds = [];                 // các thẻ bấm "Chưa nhớ" trong vòng này
    const undoStack = [];

    // Trạng thái giao diện / lưu trữ.
    let isFlipped = false;
    const sessionStartedAt = Date.now();
    let isSaving = false;
    let isCompleting = false;
    let hasCompletedSession = false;
    let checkpointQueue = Promise.resolve();

    /* ---------------------------------------------------------------
     * Giao tiếp với API (giữ nguyên định dạng request cũ)
     * ------------------------------------------------------------- */
    async function attemptRequest(action, state = null) {
        const response = await fetch("../api/learning_attempt.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                action,
                activity: "flashcard",
                csrf: sessionConfig.csrf,
                source: sessionConfig.source,
                sourceId: sessionConfig.sourceId,
                limit: sessionConfig.limit || "10",
                state
            })
        });
        const result = await response.json();
        if (!response.ok || !result.success) {
            throw new Error(result.message || "Không thể lưu phiên Flashcard.");
        }
        return result;
    }

    function getElapsedSeconds() {
        return Math.round((Date.now() - sessionStartedAt) / 1000);
    }

    function getCurrentCard() {
        return cardsById.get(queue[queuePos]) || null;
    }

    // Dùng cả style.display vì CSS của khu vực phiên âm có thể ghi đè thuộc tính hidden.
    function setPronunciationVisible(visible) {
        pronunciationArea.hidden = !visible;
        pronunciationArea.style.display = visible ? "" : "none";
    }

    function getAttemptState() {
        const current = getCurrentCard();
        const currentIndex = current
            ? cards.findIndex((card) => Number(card.id) === Number(current.id))
            : Math.max(0, cards.length - 1);

        // Giữ đúng cấu trúc cũ để C_Gocrenluyen.php tính % tiến độ như trước.
        return {
            currentIndex,
            cardStatuses,
            cardIds: cards.map((card) => Number(card.id)),
            durationSeconds: getElapsedSeconds()
        };
    }

    function saveCheckpoint() {
        if (cards.length === 0) return Promise.resolve(true);
        const snapshot = JSON.parse(JSON.stringify(getAttemptState()));
        checkpointQueue = checkpointQueue
            .catch(() => undefined)
            .then(() => attemptRequest("save", snapshot));
        return checkpointQueue.then(() => true).catch((error) => {
            console.error(error);
            return false;
        });
    }

    function saveCheckpointOnExit() {
        if (cards.length === 0 || isCompleting) return;
        const payload = JSON.stringify({
            action: "save",
            activity: "flashcard",
            csrf: sessionConfig.csrf,
            source: sessionConfig.source,
            sourceId: sessionConfig.sourceId,
            limit: sessionConfig.limit || "10",
            state: getAttemptState()
        });
        navigator.sendBeacon(
            "../api/learning_attempt.php",
            new Blob([payload], { type: "application/json" })
        );
    }

    async function saveProgress(isFinal = true) {
        if (Object.keys(cardStatuses).length === 0) return true;
        if (isSaving) return false;
        isSaving = true;
        try {
            const response = await fetch("../api/save_flashcard_progress.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    csrf: sessionConfig.csrf,
                    source: sessionConfig.source,
                    sourceId: sessionConfig.sourceId,
                    durationSeconds: getElapsedSeconds(),
                    isFinal,
                    statuses: cardStatuses
                })
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || "Không thể lưu tiến trình.");
            }
            return true;
        } catch (error) {
            alert(error.message);
            return false;
        } finally {
            isSaving = false;
        }
    }

    /* ---------------------------------------------------------------
     * Logic vòng học
     * ------------------------------------------------------------- */
    function getStatistics() {
        let rememberedCount = 0;
        let notRememberedCount = 0;

        Object.values(cardStatuses).forEach((status) => {
            if (status === "da_nho") rememberedCount++;
            if (status === "chua_nho") notRememberedCount++;
        });

        return { rememberedCount, notRememberedCount };
    }

    function isFinished() {
        return cards.length > 0 && getStatistics().rememberedCount === cards.length;
    }

    // Xáo trộn Fisher–Yates; tránh để thẻ vừa học xong lại lên đầu vòng mới.
    function shuffleAvoidingFirst(ids, avoidId) {
        const result = ids.slice();
        for (let i = result.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [result[i], result[j]] = [result[j], result[i]];
        }
        if (result.length > 1 && result[0] === avoidId) {
            const j = 1 + Math.floor(Math.random() * (result.length - 1));
            [result[0], result[j]] = [result[j], result[0]];
        }
        return result;
    }

    function advanceQueue(lastId) {
        queuePos++;
        if (queuePos < queue.length) return;

        // Hết vòng: còn từ chưa nhớ thì mở vòng mới chỉ gồm các từ đó.
        if (missedIds.length > 0) {
            round++;
            queue = shuffleAvoidingFirst(missedIds, lastId);
            missedIds = [];
            queuePos = 0;
            return;
        }

        // Cả nhóm đã nhớ: sang nhóm kế tiếp (hoặc kết thúc nếu hết nhóm).
        groupIndex++;
        round = 1;
        queuePos = 0;
        queue = groupIndex < groups.length ? groups[groupIndex].slice() : [];
    }

    function pushUndo() {
        undoStack.push(JSON.stringify({
            queue, queuePos, missedIds, round, groupIndex, cardStatuses
        }));
        if (undoStack.length > MAX_UNDO) undoStack.shift();
    }

    function popUndo() {
        const snapshot = JSON.parse(undoStack.pop());
        queue = snapshot.queue;
        queuePos = snapshot.queuePos;
        missedIds = snapshot.missedIds;
        round = snapshot.round;
        groupIndex = snapshot.groupIndex;
        cardStatuses = snapshot.cardStatuses;
    }

    function answerCurrentCard(status) {
        const card = getCurrentCard();
        if (!card || hasCompletedSession || isCompleting) return false;

        pushUndo();
        const id = Number(card.id);
        cardStatuses[id] = status;
        if (status === "chua_nho") missedIds.push(id);
        advanceQueue(id);
        return true;
    }

    /* ---------------------------------------------------------------
     * Hiển thị
     * ------------------------------------------------------------- */
    function clearAssessmentButtons() {
        [btnDaNho, btnChuaNho].forEach((button) => {
            button.classList.remove("C_HocFlashcard_btnSelected");
            button.setAttribute("aria-pressed", "false");
        });
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

    function renderStats() {
        const { rememberedCount, notRememberedCount } = getStatistics();
        const total = cards.length;

        statsText.innerHTML = `
            Đã nhớ: <strong>${rememberedCount}</strong>/${total}
            &nbsp;&bull;&nbsp;
            Chưa nhớ: <strong>${notRememberedCount}</strong>
            &nbsp;&bull;&nbsp;
            Vòng: <strong>${round}</strong>
        `;

        // Thanh tiến trình chỉ tăng khi có từ "Đã nhớ".
        progressFill.style.width = total > 0
            ? `${(rememberedCount / total) * 100}%`
            : "0%";

        if (isFinished()) {
            statsText.insertAdjacentText("beforeend", " • Hoàn thành 100%");
        }
    }

    function renderHeader() {
        const groupLabel = groups.length > 1
            ? `Nhóm ${groupIndex + 1}/${groups.length} • `
            : "";
        progressText.textContent =
            `${groupLabel}Vòng ${round} • Thẻ ${queuePos + 1}/${queue.length}`;
    }

    function renderEmptyState() {
        wordText.textContent = "Chủ đề này chưa có từ vựng";
        hintText.textContent = "Hãy quay lại và chọn chủ đề khác.";
        pronunciationText.textContent = "";
        audioButton.hidden = true;
        badgeR.style.display = "none";

        progressText.textContent = "Thẻ 0/0";
        progressFill.style.width = "0%";

        btnUndo.disabled = true;
        btnChuaNho.disabled = true;
        btnDaNho.disabled = true;
    }

    function renderFinishedState() {
        isFlipped = false;
        cardBox.classList.remove("is-flipped");

        wordText.textContent = "🎉 Hoàn thành!";
        hintText.textContent = `Bạn đã nhớ tất cả ${cards.length} từ.`;
        setPronunciationVisible(false);
        audioButton.hidden = true;
        badgeR.style.display = "none";

        progressText.textContent = `Hoàn thành • ${cards.length}/${cards.length} từ`;

        clearAssessmentButtons();
        btnUndo.disabled = true;
        btnChuaNho.disabled = true;
        btnDaNho.disabled = true;
        renderStats();
    }

    function renderCard() {
        if (cards.length === 0) {
            renderEmptyState();
            return;
        }

        const currentCard = getCurrentCard();
        if (!currentCard) {
            renderFinishedState();
            return;
        }

        isFlipped = false;
        cardBox.classList.remove("is-flipped");
        audioPlayer.pause();

        wordText.textContent = currentCard.tu_vung;
        hintText.textContent = "Nhấn vào thẻ để xem nghĩa";
        setPronunciationVisible(true);
        badgeR.style.display = currentCard.is_review ? "flex" : "none";

        renderPronunciation(currentCard);
        renderHeader();
        renderStats();
        clearAssessmentButtons();

        btnUndo.disabled = undoStack.length === 0;
        btnChuaNho.disabled = false;
        btnDaNho.disabled = false;
    }

    /* ---------------------------------------------------------------
     * Hoàn thành phiên
     * ------------------------------------------------------------- */
    async function completeFlashcardIfFinished() {
        if (hasCompletedSession || !isFinished()) return;

        hasCompletedSession = true;
        isCompleting = true;
        await checkpointQueue.catch(console.error);

        const saved = await saveProgress(true);
        if (!saved) {
            // Cho phép thử lại khi bấm nút kết thúc.
            hasCompletedSession = false;
            isCompleting = false;
            return;
        }

        await attemptRequest("complete").catch(console.error);
        btnKetThuc.textContent = "Hoàn tất phiên học";
    }

    async function handleAnswer(status) {
        if (!answerCurrentCard(status)) return;
        renderCard();
        saveCheckpoint(); // tự xếp hàng tuần tự trong checkpointQueue
        await completeFlashcardIfFinished();
    }

    function leaveSession() {
        if (sessionConfig.source === "review") {
            window.location.href = "C_Ontaphomnay.php";
            return;
        }
        const sourceQuery = new URLSearchParams({
            source: sessionConfig.source || "topic",
            id: sessionConfig.sourceId || sessionConfig.topicId,
            limit: sessionConfig.limit || "10"
        });
        window.location.href = `C_Gocrenluyen.php?${sourceQuery.toString()}`;
    }

    /* ---------------------------------------------------------------
     * Sự kiện
     * ------------------------------------------------------------- */
    cardBox.addEventListener("click", () => {
        const currentCard = getCurrentCard();
        if (!currentCard) return;

        isFlipped = !isFlipped;
        cardBox.classList.toggle("is-flipped", isFlipped);

        if (isFlipped) {
            wordText.textContent = currentCard.nghia;
            hintText.textContent = "Nhấn vào thẻ để xem từ tiếng Anh";
            setPronunciationVisible(false);
        } else {
            wordText.textContent = currentCard.tu_vung;
            hintText.textContent = "Nhấn vào thẻ để xem nghĩa";
            setPronunciationVisible(true);
        }
    });

    audioButton.addEventListener("click", () => {
        audioPlayer.currentTime = 0;
        audioPlayer.play().catch(() => {
            alert("Không thể phát audio của từ này.");
        });
    });

    btnDaNho.addEventListener("click", () => handleAnswer("da_nho"));
    btnChuaNho.addEventListener("click", () => handleAnswer("chua_nho"));

    // Hoàn tác lựa chọn vừa rồi (phòng khi bấm nhầm).
    btnUndo.addEventListener("click", () => {
        if (hasCompletedSession || isCompleting || undoStack.length === 0) return;
        popUndo();
        renderCard();
        saveCheckpoint();
    });

    btnKetThuc.addEventListener("click", async () => {
        if (isFinished()) {
            // Đã nhớ hết: đảm bảo kết quả đã được lưu rồi mới thoát.
            await completeFlashcardIfFinished();
            if (!hasCompletedSession) return;
            leaveSession();
            return;
        }

        const shouldEnd = confirm(
            "Kết thúc phiên học?\nCác từ đã đánh giá vẫn được lưu, " +
            "nhưng lần sau bạn sẽ học lại từ đầu."
        );
        if (!shouldEnd) return;

        await saveCheckpoint();
        const saved = await saveProgress(false);
        if (!saved) return;
        leaveSession();
    });

    window.addEventListener("pagehide", saveCheckpointOnExit);

    /* ---------------------------------------------------------------
     * Khởi động: luôn bắt đầu phiên mới từ đầu
     * ------------------------------------------------------------- */
    btnNext.style.visibility = "hidden"; // nút → không còn ý nghĩa trong luồng mới
    btnNext.disabled = true;
    renderCard();
    // Tạo/ghi đè phiên hiện tại bằng trạng thái trống, không đọc phiên cũ.
    saveCheckpoint();
});