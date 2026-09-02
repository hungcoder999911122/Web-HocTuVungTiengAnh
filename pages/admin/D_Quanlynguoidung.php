<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>LexiLoop Admin - Quản lý người dùng</title>
    <link
      rel="stylesheet"
      type="text/css"
      href="../../CSS/D_Quanlynguoidung.css"
    />
    <script src="../jquery-4.0.0.min.js"></script>
  </head>

  <body>
    <div class="D_Quanlynguoidung_Wrapper">
      <header class="D_Quanlynguoidung_Topbar">
        <div class="D_Quanlynguoidung_Logo">LexiLoop Admin</div>
        <div class="D_Quanlynguoidung_TopbarPhai">
          <input
            type="text"
            class="D_Quanlynguoidung_TimKiem"
            placeholder="Tìm kiếm"
          />
          <div class="D_Quanlynguoidung_Avatar">AD</div>
        </div>
      </header>

      <div class="D_Quanlynguoidung_Body">
        <nav class="D_Quanlynguoidung_Sidebar">
          <a href="D_Dashboard_admin.php" class="D_Quanlynguoidung_MucMenu"
            >Dashboard</a
          >
          <a
            href="D_Quanlynguoidung.php"
            class="D_Quanlynguoidung_MucMenu D_Quanlynguoidung_DangChon"
            >Người dùng</a
          >
          <a href="D_Quanlychude.php" class="D_Quanlynguoidung_MucMenu"
            >Chủ đề</a
          >
          <a href="D_Quanlytuvung.php" class="D_Quanlynguoidung_MucMenu"
            >Từ vựng</a
          >
          <a href="D_Thongkehethong.php" class="D_Quanlynguoidung_MucMenu"
            >Thống kê</a
          >
          <a href="D_Caidathethong.php" class="D_Quanlynguoidung_MucMenu"
            >Cài đặt</a
          >
          <hr class="D_Quanlynguoidung_GachNgang" />
          <a href="../main/B_homepage.php" class="D_Quanlynguoidung_MucMenu"
            >Đăng xuất</a
          >
        </nav>

        <main class="D_Quanlynguoidung_NoiDung">
          <div class="D_Quanlynguoidung_HangTieuDe">
            <h1 class="D_Quanlynguoidung_TieuDe">Quản lý người dùng</h1>
            <input
              type="text"
              id="D_Quanlynguoidung_OTimKiem"
              class="D_Quanlynguoidung_OTimKiem"
              placeholder="Tìm kiếm theo tên hoặc email..."
            />
          </div>

          <div class="D_Quanlynguoidung_HangLoc">
            <select
              id="D_Quanlynguoidung_LocVaiTro"
              class="D_Quanlynguoidung_Loc"
            >
              <option value="tat_ca">Vai trò: Tất cả</option>
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
            <select
              id="D_Quanlynguoidung_LocTrangThai"
              class="D_Quanlynguoidung_Loc"
            >
              <option value="tat_ca">Trạng thái: Tất cả</option>
              <option value="hoat_dong">Hoạt động</option>
              <option value="da_khoa">Đã khóa</option>
            </select>
          </div>

          <table class="D_Quanlynguoidung_Bang">
            <thead>
              <tr>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="D_Quanlynguoidung_ThanBang">
              <tr data-vaitro="user" data-trangthai="hoat_dong">
                <td>Nguyễn Văn A</td>
                <td>a@mail.com</td>
                <td>User</td>
                <td class="D_Quanlynguoidung_OTrangThai">Hoạt động</td>
                <td>
                  <button class="D_Quanlynguoidung_NutKhoa" type="button">
                    Khóa
                  </button>
                </td>
              </tr>
              <tr data-vaitro="user" data-trangthai="da_khoa">
                <td>Trần Thị B</td>
                <td>b@mail.com</td>
                <td>User</td>
                <td class="D_Quanlynguoidung_OTrangThai">Đã khóa</td>
                <td>
                  <button class="D_Quanlynguoidung_NutKhoa" type="button">
                    Mở khóa
                  </button>
                </td>
              </tr>
              <tr data-vaitro="user" data-trangthai="hoat_dong">
                <td>Lê Văn C</td>
                <td>c@mail.com</td>
                <td>User</td>
                <td class="D_Quanlynguoidung_OTrangThai">Hoạt động</td>
                <td>
                  <button class="D_Quanlynguoidung_NutKhoa" type="button">
                    Khóa
                  </button>
                </td>
              </tr>
              <tr data-vaitro="admin" data-trangthai="hoat_dong">
                <td>Phạm Thị D</td>
                <td>d@mail.com</td>
                <td>Admin</td>
                <td class="D_Quanlynguoidung_OTrangThai">Hoạt động</td>
                <td>
                  <button class="D_Quanlynguoidung_NutKhoa" type="button">
                    Khóa
                  </button>
                </td>
              </tr>
              <tr data-vaitro="user" data-trangthai="hoat_dong">
                <td>Võ Văn E</td>
                <td>e@mail.com</td>
                <td>User</td>
                <td class="D_Quanlynguoidung_OTrangThai">Hoạt động</td>
                <td>
                  <button class="D_Quanlynguoidung_NutKhoa" type="button">
                    Khóa
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="D_Quanlynguoidung_PhanTrang">
            <span>&lt;</span> <span>1</span> <span>2</span> <span>3</span>
            <span>&gt;</span>
          </div>
        </main>
      </div>
    </div>

    <script src="../JS/D_Quanlynguoidung.js"></script>
  </body>
</html>
