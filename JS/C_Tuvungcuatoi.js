document.addEventListener("DOMContentLoaded", () => {
    // 1. Lấy các phần tử DOM
    const btnThemTu = document.getElementById("C_Tuvungcuatoi_btnThemTu");
    const formCard = document.getElementById("C_Tuvungcuatoi_formContainer");
    const formTitle = document.getElementById("C_Tuvungcuatoi_formTitle");
    const formThemTu = document.getElementById("C_Tuvungcuatoi_formThemTu");
    const btnHuy = document.getElementById("C_Tuvungcuatoi_btnHuy");

    const inputTuVung = document.getElementById("C_Tuvungcuatoi_txtTuVung");
    const inputNghia = document.getElementById("C_Tuvungcuatoi_txtNghia");
    const inputChuDe = document.getElementById("C_Tuvungcuatoi_txtChuDe");
    const editIdInput = document.getElementById("C_Tuvungcuatoi_editId");

    const searchInput = document.getElementById("C_Tuvungcuatoi_txtTimKiem");
    const selectChuDe = document.getElementById("C_Tuvungcuatoi_selChuDe");
    const selectTrangThai = document.getElementById("C_Tuvungcuatoi_selTrangThai");
    const tableRows = document.querySelectorAll("#C_Tuvungcuatoi_table tbody tr");

    // 2. Click "+ Thêm từ mới" -> Cuộn mượt xuống Form và focus
    if (btnThemTu && formCard) {
        btnThemTu.addEventListener("click", () => {
            resetForm();
            formTitle.textContent = "Thêm từ vựng mới";
            formCard.scrollIntoView({ behavior: "smooth" });
            inputTuVung.focus();
        });
    }

    // 3. Nút "Hủy bỏ"
    if (btnHuy) {
        btnHuy.addEventListener("click", () => {
            resetForm();
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }

    function resetForm() {
        formThemTu.reset();
        editIdInput.value = "";
        formTitle.textContent = "Thêm từ vựng mới";
    }

    // 4. Xử lý nút "Sửa" và "Xóa" trên từng hàng của bảng
    tableRows.forEach((row) => {
        const btnEdit = row.querySelector(".btn-edit");
        const btnDelete = row.querySelector(".btn-delete");

        // Khi bấm Sửa
        if (btnEdit) {
            btnEdit.addEventListener("click", () => {
                const tuVung = row.cells[0].textContent.trim();
                const nghia = row.cells[1].textContent.trim();
                const chuDe = row.cells[2].textContent.trim();
                const id = row.getAttribute("data-id");

                inputTuVung.value = tuVung;
                inputNghia.value = nghia;
                inputChuDe.value = chuDe;
                editIdInput.value = id;

                formTitle.textContent = `Chỉnh sửa từ: "${tuVung}"`;
                formCard.scrollIntoView({ behavior: "smooth" });
                inputTuVung.focus();
            });
        }

        // Khi bấm Xóa
        if (btnDelete) {
            btnDelete.addEventListener("click", () => {
                const tuVung = row.cells[0].textContent.trim();
                const confirmDelete = confirm(`Bạn có chắc chắn muốn xóa từ vựng "${tuVung}" không?`);
                if (confirmDelete) {
                    /* =================================================================
                       [GHI CHÚ KẾT NỐI DATABASE THEO LEADER]
                       Tại đây bạn gửi request POST hoặc gọi fetch() để xóa từ trong CSDL:
                       fetch('XoaTuVung.php', {
                           method: 'POST',
                           headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                           body: `id=${row.getAttribute('data-id')}`
                       });
                       ================================================================= */
                    row.remove();
                }
            });
        }
    });

    // 5. Tìm kiếm trực tiếp (Real-time Filter)
    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedChuDe = selectChuDe.value.toLowerCase();
        const selectedTrangThai = selectTrangThai.value.toLowerCase();

        tableRows.forEach((row) => {
            const tuVung = row.cells[0].textContent.toLowerCase();
            const nghia = row.cells[1].textContent.toLowerCase();
            const chuDe = row.cells[2].textContent.toLowerCase();
            const trangThai = row.getAttribute("data-status") || "";

            const matchSearch = tuVung.includes(searchTerm) || nghia.includes(searchTerm);
            const matchChuDe = !selectedChuDe || chuDe.includes(selectedChuDe);
            const matchTrangThai = !selectedTrangThai || trangThai === selectedTrangThai;

            if (matchSearch && matchChuDe && matchTrangThai) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    if (searchInput) searchInput.addEventListener("input", filterTable);
    if (selectChuDe) selectChuDe.addEventListener("change", filterTable);
    if (selectTrangThai) selectTrangThai.addEventListener("change", filterTable);

    // 6. Tự động ẩn thông báo thành công sau 4s
    const alertBox = document.getElementById("C_Tuvungcuatoi_alert");
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = "opacity 0.4s ease";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 400);
        }, 4000);
    }
});