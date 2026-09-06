// THÔNG BÁO THÀNH CÔNG/ THẤT BẠI CỦA MỖI CHỨC NĂNG : ĐĂNG NHẬP, ĐĂNG KÝ, ĐĂNG XUẤT
/*
 * ============================================================
 * auth.js
 * Xử lý các chức năng JavaScript liên quan đến tài khoản
 *
 * Bao gồm:
 * 1. Kiểm tra form Đăng nhập
 * 2. Kiểm tra form Đăng ký
 * 3. Chuyển sang trang Đăng ký
 * 4. Xác nhận trước khi Đăng xuất
 *
 * Lưu ý:
 * - JavaScript chỉ kiểm tra dữ liệu phía trình duyệt.
 * - PHP vẫn phải kiểm tra dữ liệu một lần nữa.
 * - JavaScript KHÔNG xử lý Database.
 * - JavaScript KHÔNG xóa Session.
 * ============================================================
 */

$(document).ready(function () {

    // ========================================================
    // 1. XỬ LÝ FORM ĐĂNG NHẬP
    // ========================================================

    /*
     * Kiểm tra khi người dùng nhấn nút "Đăng nhập".
     *
     * Nếu Email hoặc Mật khẩu bị bỏ trống:
     * -> Hiển thị thông báo
     * -> Chặn form gửi lên PHP
     *
     * Nếu dữ liệu đầy đủ:
     * -> Không gọi preventDefault()
     * -> Cho phép form gửi lên PHP xử lý.
     */
    $('#DangNhap_btn').click(function (event) {

        var email = $('#A_DangNhap_Email').val().trim();
        var password = $('#A_DangNhap_password').val();

        // Kiểm tra Email
        if (email === '') {
            alert('Vui lòng nhập Email.');
            event.preventDefault();
            return;
        }

        // Kiểm tra Mật khẩu
        if (password === '') {
            alert('Vui lòng nhập Mật khẩu.');
            event.preventDefault();
            return;
        }

        /*
         * Nếu chạy đến đây nghĩa là dữ liệu phía Client hợp lệ.
         *
         * KHÔNG dùng:
         * event.preventDefault();
         *
         * để trình duyệt tiếp tục submit form cho PHP.
         */
    });


    // ========================================================
    // 2. XỬ LÝ FORM ĐĂNG KÝ
    // ========================================================

    /*
     * Kiểm tra dữ liệu trước khi gửi form Đăng ký.
     *
     * Đây là phần JavaScript trước đây đang nằm trực tiếp
     * bên trong A_DangKy.php.
     *
     * Bây giờ chuyển sang auth.js để dùng chung và dễ quản lý.
     */
    $('#A_DangKybtn').click(function (event) {

        var fullname = $('#A_DangKy_fullname').val().trim();
        var email = $('#A_DangKy_email').val().trim();
        var password = $('#A_DangKy_password').val();
        var passwordConfirm = $('#A_DangKy_password_confirm').val();
        var agree = $('#A_DangKy_agree').is(':checked');


        // ----------------------------------------------------
        // Kiểm tra Họ tên
        // ----------------------------------------------------

        if (fullname === '') {
            alert('Vui lòng nhập họ và tên.');
            event.preventDefault();
            return;
        }


        // ----------------------------------------------------
        // Kiểm tra Email
        // ----------------------------------------------------

        if (email === '') {
            alert('Vui lòng nhập Email.');
            event.preventDefault();
            return;
        }


        // ----------------------------------------------------
        // Kiểm tra Mật khẩu
        // ----------------------------------------------------

        if (password === '') {
            alert('Vui lòng nhập mật khẩu.');
            event.preventDefault();
            return;
        }


        // ----------------------------------------------------
        // Kiểm tra Xác nhận mật khẩu
        // ----------------------------------------------------

        if (passwordConfirm === '') {
            alert('Vui lòng nhập lại mật khẩu.');
            event.preventDefault();
            return;
        }


        // ----------------------------------------------------
        // Kiểm tra hai mật khẩu có giống nhau không
        // ----------------------------------------------------

        if (password !== passwordConfirm) {
            alert('Mật khẩu xác nhận không khớp.');
            event.preventDefault();
            return;
        }


        // ----------------------------------------------------
        // Kiểm tra điều khoản sử dụng
        // ----------------------------------------------------

        if (!agree) {
            alert('Bạn phải đồng ý với điều khoản sử dụng.');
            event.preventDefault();
            return;
        }


        /*
         * Nếu chạy đến đây:
         * - Dữ liệu phía Client hợp lệ.
         *
         * Không gọi event.preventDefault().
         *
         * Form sẽ được gửi lên PHP để PHP tiếp tục:
         * - Kiểm tra Email đã tồn tại chưa
         * - Hash mật khẩu
         * - INSERT vào Database
         */
    });


    // ========================================================
    // 3. NÚT "TẠO TÀI KHOẢN" Ở TRANG ĐĂNG NHẬP
    // ========================================================

    /*
     * Khi người dùng đang ở A_DangNhap.php và nhấn
     * "Tạo tài khoản":
     *
     * -> Chuyển sang A_DangKy.php
     */
    $('#A_DangNhap_TaoTaiKhoan').click(function () {

        window.location.href = "A_DangKy.php";

    });


    // ========================================================
    // 4. XÁC NHẬN TRƯỚC KHI ĐĂNG XUẤT
    // ========================================================

    /*
     * Bắt sự kiện click vào nút Đăng xuất.
     *
     * HTML hiện tại:
     *
     * <a href="../auth/A_DangXuat.php" class="sidebar-link">
     *
     * Nếu không xử lý JavaScript:
     * -> Người dùng click
     * -> Trình duyệt đi thẳng đến A_DangXuat.php
     * -> Session bị xóa ngay.
     *
     * Vì vậy ở đây ta dùng preventDefault()
     * để tạm thời chặn hành động chuyển trang.
     */
    $('.sidebar-link[href="../auth/A_DangXuat.php"]').click(function (event) {

        // Chặn chuyển sang A_DangXuat.php ngay lập tức
        event.preventDefault();


        /*
         * Hiển thị hộp thoại xác nhận.
         *
         * OK     -> confirm() trả về true
         * Hủy    -> confirm() trả về false
         */
        var xacNhan = confirm('Bạn có chắc chắn muốn đăng xuất không?');


        // ----------------------------------------------------
        // Người dùng chọn OK
        // ----------------------------------------------------

        if (xacNhan) {

            /*
             * Cho phép trình duyệt chuyển đến đúng URL
             * của thẻ <a>.
             *
             * Không tự xóa Session bằng JavaScript.
             *
             * A_DangXuat.php sẽ chịu trách nhiệm:
             * - session_start()
             * - $_SESSION = []
             * - session_destroy()
             */
            window.location.href = $(this).attr('href');

        }


        // ----------------------------------------------------
        // Người dùng chọn Hủy
        // ----------------------------------------------------

        /*
         * Nếu chọn Hủy:
         *
         * xacNhan = false
         *
         * Không làm gì thêm.
         *
         * Người dùng vẫn ở trang hiện tại
         * và Session vẫn còn.
         */
    });

});