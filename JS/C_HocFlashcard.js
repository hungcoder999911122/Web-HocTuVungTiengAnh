document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy các phần tử DOM
    const cardBox = document.getElementById("C_HocFlashcard_cardBox");
    const wordText = document.getElementById("C_HocFlashcard_word");
    const hintText = document.getElementById("C_HocFlashcard_hint");
    const badgeR = document.getElementById("C_HocFlashcard_badgeR");
    const progressText = document.getElementById("C_HocFlashcard_progressText");
    const progressFill = document.getElementById("C_HocFlashcard_progressFill");
    const statsText = document.getElementById("C_HocFlashcard_stats");

    const btnPrev = document.getElementById("C_HocFlashcard_btnPrev");
    const btnNext = document.getElementById("C_HocFlashcard_btnNext");
    const btnChuaNho = document.getElementById("C_HocFlashcard_btnChuaNho");
    const btnDaNho = document.getElementById("C_HocFlashcard_btnDaNho");
    const btnKetThuc = document.getElementById("C_HocFlashcard_btnKetThuc");

    // Dữ liệu nhận từ PHP
    const cards = (typeof flashcardsData !== "undefined" && flashcardsData.length > 0)
        ? flashcardsData
        : [{ id: 1, tu_vung: "Software", nghia: "Phần mềm", is_review: true }];

    // Quản lý trạng thái học của người dùng
    let currentIndex = 0;
    let isFlipped = false;
    const cardStatus = {}; // Lưu trạng thái theo id hoặc index: 'da_nho' hoặc 'chua_nho'

    // Hàm cập nhật hiển thị thẻ hiện tại
    function renderCard() {
        const currentCard = cards[currentIndex];
        
        // Cập nhật text tiến độ (Thẻ X/Tổng)
        progressText.textContent = `Thẻ ${currentIndex + 1}/${cards.length}`;
        
        // Cập nhật thanh % tiến độ học
        const percentage = ((currentIndex + 1) / cards.length) * 100;
        progressFill.style.width = `${percentage}%`;

        // Reset trạng thái lật về mặt trước
        isFlipped = false;
        cardBox.classList.remove("is-flipped");
        wordText.textContent = currentCard.tu_vung;
        hintText.textContent = "Nhấn để xem nghĩa";

        // Huy hiệu Review 'R'
        if (currentCard.is_review) {
            badgeR.style.display = "flex";
        } else {
            badgeR.style.display = "none";
        }

        // Bật/tắt nút điều hướng Prev/Next
        btnPrev.disabled = (currentIndex === 0);
        btnNext.disabled = (currentIndex === cards.length - 1);

        // Cập nhật thống kê footer
        renderStats();
    }

    // Hàm cập nhật footer thống kê
    function renderStats() {
        let daNhoCount = 0;
        let chuaNhoCount = 0;

        Object.values(cardStatus).forEach(status => {
            if (status === "da_nho") daNhoCount++;
            if (status === "chua_nho") chuaNhoCount++;
        });

        const daHocCount = daNhoCount + chuaNhoCount;
        statsText.innerHTML = `Đã học: <strong>${daHocCount}</strong> &nbsp;&bull;&nbsp; Đã nhớ: <strong>${daNhoCount}</strong> &nbsp;&bull;&nbsp; Chưa nhớ: <strong>${chuaNhoCount}</strong>`;
    }

    // 2. Click lật thẻ (Từ vựng <-> Nghĩa)
    cardBox.addEventListener("click", () => {
        isFlipped = !isFlipped;
        const currentCard = cards[currentIndex];

        if (isFlipped) {
            cardBox.classList.add("is-flipped");
            wordText.textContent = currentCard.nghia;
            hintText.textContent = "Nhấn để xem từ tiếng Anh";
        } else {
            cardBox.classList.remove("is-flipped");
            wordText.textContent = currentCard.tu_vung;
            hintText.textContent = "Nhấn để xem nghĩa";
        }
    });

    // 3. Xử lý khi nhấn nút Đã nhớ / Chưa nhớ
    function handleAssessment(status) {
        /* =================================================================
           [GHI CHÚ ĐỒNG BỘ CSDL THEO LEADER]
           - Tại đây bạn có thể dùng fetch() gửi $_POST đến 1 file xử lý PHP
           - Ví dụ:
             fetch('LuuTienDo.php', {
                 method: 'POST',
                 headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                 body: `id_tuvung=${cards[currentIndex].id}&trang_thai=${status}`
             });
           ================================================================= */
        const cardId = cards[currentIndex].id || currentIndex;
        cardStatus[cardId] = status;

        if (currentIndex < cards.length - 1) {
            currentIndex++;
            renderCard();
        } else {
            renderStats();
            setTimeout(() => {
                alert("🎉 Bạn đã hoàn thành toàn bộ thẻ trong phiên học này!");
            }, 100);
        }
    }

    btnChuaNho.addEventListener("click", () => handleAssessment("chua_nho"));
    btnDaNho.addEventListener("click", () => handleAssessment("da_nho"));

    // 4. Nút chuyển từ trước / sau
    btnPrev.addEventListener("click", () => {
        if (currentIndex > 0) {
            currentIndex--;
            renderCard();
        }
    });

    btnNext.addEventListener("click", () => {
        if (currentIndex < cards.length - 1) {
            currentIndex++;
            renderCard();
        }
    });

    // 5. Nút kết thúc sớm
    btnKetThuc.addEventListener("click", () => {
        const confirmEnd = confirm("Bạn có chắc chắn muốn kết thúc phiên học này sớm không?");
        if (confirmEnd) {
            /* Leader rule: Đổi đuôi liên kết sang .php */
            window.location.href = "B_DanhSachChuDe.php";
        }
    });

    // Bắt đầu khởi tạo thẻ đầu tiên
    renderCard();
});