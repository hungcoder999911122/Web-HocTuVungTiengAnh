/**
 * Xử lý tương tác cho trang Lịch hẹn ôn tập - LexiLoop
 */
document.addEventListener("DOMContentLoaded", () => {
    console.log("LexiLoop - Trang Lịch Hẹn Ôn Tập đã sẵn sàng.");
});

// Hàm lọc danh sách theo Level
function filterByLevel(selectedLevel) {
    const currentUrl = new URL(window.location.href);
    if (selectedLevel > 0) {
        currentUrl.searchParams.set('level', selectedLevel);
    } else {
        currentUrl.searchParams.delete('level');
    }
    window.location.href = currentUrl.toString();
}