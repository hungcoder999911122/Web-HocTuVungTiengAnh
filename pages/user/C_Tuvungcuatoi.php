<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Từ vựng của tôi - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Tuvungcuatoi.css">
</head>
<body class="C_Tuvungcuatoi_body">

    <header class="C_Tuvungcuatoi_header">
        <h1 class="C_Tuvungcuatoi_logo">Từ vựng của tôi</h1>
        <button type="button" id="C_Tuvungcuatoi_btnThemTu" class="C_Tuvungcuatoi_btnAddTop">
            + Thêm từ
        </button>
    </header>

    <main class="C_Tuvungcuatoi_main">
        <section class="C_Tuvungcuatoi_filterBar">
            <input 
                type="text" 
                id="C_Tuvungcuatoi_txtTimKiem" 
                class="C_Tuvungcuatoi_inputSearch" 
                placeholder="Tìm kiếm từ vựng...">

            <select id="C_Tuvungcuatoi_selChuDe" class="C_Tuvungcuatoi_selectFilter">
                <option value="">Chủ đề: Tất cả</option>
                <option value="du_lich">Du lịch</option>
                <option value="cong_nghe">Công nghệ</option>
                <option value="am_thuc">Ẩm thực</option>
            </select>

            <select id="C_Tuvungcuatoi_selTrangThai" class="C_Tuvungcuatoi_selectFilter">
                <option value="">Trạng thái: Tất cả</option>
                <option value="tot">Tốt</option>
                <option value="can_on_tap">Cần ôn tập</option>
                <option value="moi">Mới</option>
            </select>
        </section>

        <section class="C_Tuvungcuatoi_tableSection">
            <table class="C_Tuvungcuatoi_table">
                <thead>
                    <tr>
                        <th class="C_Tuvungcuatoi_th">Từ vựng</th>
                        <th class="C_Tuvungcuatoi_th">Nghĩa</th>
                        <th class="C_Tuvungcuatoi_th">Chủ đề</th>
                        <th class="C_Tuvungcuatoi_th">Mức độ nhớ</th>
                        <th class="C_Tuvungcuatoi_th">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="C_Tuvungcuatoi_td">Airport</td>
                        <td class="C_Tuvungcuatoi_td">Sân bay</td>
                        <td class="C_Tuvungcuatoi_td">Du lịch</td>
                        <td class="C_Tuvungcuatoi_td">Tốt</td>
                        <td class="C_Tuvungcuatoi_td">
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Sửa</button>
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Xóa</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="C_Tuvungcuatoi_td">Software</td>
                        <td class="C_Tuvungcuatoi_td">Phần mềm</td>
                        <td class="C_Tuvungcuatoi_td">Công nghệ</td>
                        <td class="C_Tuvungcuatoi_td">Cần ôn tập</td>
                        <td class="C_Tuvungcuatoi_td">
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Sửa</button>
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Xóa</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="C_Tuvungcuatoi_td">Noodle</td>
                        <td class="C_Tuvungcuatoi_td">Mì</td>
                        <td class="C_Tuvungcuatoi_td">Ẩm thực</td>
                        <td class="C_Tuvungcuatoi_td">Mới</td>
                        <td class="C_Tuvungcuatoi_td">
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Sửa</button>
                            <button type="button" class="C_Tuvungcuatoi_btnTableAction">Xóa</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section class="C_Tuvungcuatoi_formModalContainer">
            <div class="C_Tuvungcuatoi_formCard">
                <h2 class="C_Tuvungcuatoi_formTitle">Thêm từ vựng mới</h2>

                <form id="C_Tuvungcuatoi_formThemTu" action="#" method="POST">
                    
                    <div class="C_Tuvungcuatoi_formRow">
                        <div class="C_Tuvungcuatoi_formGroup">
                            <label for="C_Tuvungcuatoi_txtTuVung" class="C_Tuvungcuatoi_label">Từ vựng</label>
                            <input 
                                type="text" 
                                id="C_Tuvungcuatoi_txtTuVung" 
                                name="C_Tuvungcuatoi_txtTuVung" 
                                class="C_Tuvungcuatoi_input" 
                                maxlength="100" 
                                required>
                        </div>
                        <div class="C_Tuvungcuatoi_formGroup">
                            <label for="C_Tuvungcuatoi_txtNghia" class="C_Tuvungcuatoi_label">Nghĩa</label>
                            <input 
                                type="text" 
                                id="C_Tuvungcuatoi_txtNghia" 
                                name="C_Tuvungcuatoi_txtNghia" 
                                class="C_Tuvungcuatoi_input" 
                                required>
                        </div>
                    </div>

                    <div class="C_Tuvungcuatoi_formGroup">
                        <label for="C_Tuvungcuatoi_txtChuDe" class="C_Tuvungcuatoi_label">Chủ đề</label>
                        <input 
                            type="text" 
                            id="C_Tuvungcuatoi_txtChuDe" 
                            name="C_Tuvungcuatoi_txtChuDe" 
                            class="C_Tuvungcuatoi_input" 
                            maxlength="100" 
                            required>
                    </div>

                    <div class="C_Tuvungcuatoi_formButtons">
                        <button type="button" id="C_Tuvungcuatoi_btnHuy" class="C_Tuvungcuatoi_btnCancel">
                            Hủy
                        </button>
                        <button type="submit" id="C_Tuvungcuatoi_btnLuu" class="C_Tuvungcuatoi_btnSave">
                            Lưu lại
                        </button>
                    </div>
                </form>
            </div>
        </section>

    </main>

</body>
</html>