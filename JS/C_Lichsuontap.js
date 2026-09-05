document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy phần tử select bộ lọc thời gian
    const filterSelect = document.getElementById("C_Lichsuontap_filterSelect");
    const tableBody = document.querySelector("#C_Lichsuontap_table tbody");

    if (filterSelect) {
        // Sự kiện khi người dùng chọn mốc thời gian lọc
        filterSelect.addEventListener("change", (e) => {
            const selectedRange = e.target.value;
            console.log(`Đang lọc dữ liệu theo mốc: ${selectedRange} ngày`);

            /* =================================================================
               [GHI CHÚ KẾT NỐI THEO QUY TRÌNH LEADER]
               Tại đây bạn có thể dùng fetch/AJAX để tải lại dữ liệu từ PHP:
               
               fetch(`C_Lichsuontap.php?range=${selectedRange}`)
                   .then(res => res.json())
                   .then(data => {
                       // Cập nhật lại biểu đồ và các hàng trong bảng
                   });
               ================================================================= */
            
            // Hiệu ứng mờ nhẹ để báo hiệu đang lọc
            if (tableBody) {
                tableBody.style.opacity = "0.5";
                setTimeout(() => {
                    tableBody.style.opacity = "1";
                }, 250);
            }
        });
    }

    // 2. Tương tác với các cột biểu đồ
    const bars = document.querySelectorAll(".C_Lichsuontap_bar");
    bars.forEach((bar) => {
        bar.addEventListener("click", () => {
            const count = bar.getAttribute("data-count");
            alert(`Số lượng từ ôn tập trong ngày này là: ${count}`);
        });
    });
});