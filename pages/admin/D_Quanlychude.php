<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>LexiLoop Admin - Quản lý chủ đề</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/D_Quanlychude.css"/>
    <script src="../jquery-4.0.0.min.js"></script>
  </head>

  <body>
    <div class="D_Quanlychude_Wrapper">
      <header class="D_Quanlychude_Topbar">
        <div class="D_Quanlychude_Logo">LexiLoop Admin</div>
        <div class="D_Quanlychude_TopbarPhai">
          <input
            type="text"
            class="D_Quanlychude_TimKiem"
            placeholder="Tìm kiếm"
          />
          <div class="D_Quanlychude_Avatar">AD</div>
        </div>
      </header>

      <div class="D_Quanlychude_Body">
        <nav class="D_Quanlychude_Sidebar">
          <a href="D_Dashboard_admin.php" class="D_Quanlychude_MucMenu"
            >Dashboard</a
          >
          <a href="D_Quanlynguoidung.php" class="D_Quanlychude_MucMenu"
            >Người dùng</a
          >
          <a
            href="D_Quanlychude.php"
            class="D_Quanlychude_MucMenu D_Quanlychude_DangChon"
            >Chủ đề</a
          >
          <a href="D_Quanlytuvung.php" class="D_Quanlychude_MucMenu"
            >Từ vựng</a
          >
          <a href="D_Thongkehethong.php" class="D_Quanlychude_MucMenu"
            >Thống kê</a
          >
          <a href="D_Caidathethong.php" class="D_Quanlychude_MucMenu"
            >Cài đặt</a
          >
          <hr class="D_Quanlychude_GachNgang" />
          <a href="../main/B_homepage.php" class="D_Quanlychude_MucMenu"
            >Đăng xuất</a
          >
        </nav>

        <main class="D_Quanlychude_NoiDung">
          <div class="D_Quanlychude_HangTieuDe">
            <h1 class="D_Quanlychude_TieuDe">Quản lý chủ đề</h1>
            <button
              id="D_Quanlychude_BtnThem"
              class="D_Quanlychude_NutXam"
              type="button"
            >
              + Thêm chủ đề
            </button>
          </div>

          <table class="D_Quanlychude_Bang">
            <thead>
              <tr>
                <th>Tên chủ đề</th>
                <th>Số từ vựng</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="D_Quanlychude_ThanBang">
              <tr data-tenchude="Du lịch" data-mota="Từ vựng chủ đề du lịch">
                <td class="D_Quanlychude_OTen">Du lịch</td>
                <td>42</td>
                <td>12/01/2026</td>
                <td>
                  <button class="D_Quanlychude_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlychude_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr
                data-tenchude="Công nghệ"
                data-mota="Từ vựng chủ đề công nghệ"
              >
                <td class="D_Quanlychude_OTen">Công nghệ</td>
                <td>67</td>
                <td>05/02/2026</td>
                <td>
                  <button class="D_Quanlychude_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlychude_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr data-tenchude="Ẩm thực" data-mota="Từ vựng chủ đề ẩm thực">
                <td class="D_Quanlychude_OTen">Ẩm thực</td>
                <td>28</td>
                <td>20/03/2026</td>
                <td>
                  <button class="D_Quanlychude_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlychude_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr data-tenchude="Công sở" data-mota="Từ vựng chủ đề công sở">
                <td class="D_Quanlychude_OTen">Công sở</td>
                <td>51</td>
                <td>02/04/2026</td>
                <td>
                  <button class="D_Quanlychude_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlychude_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </main>
      </div>

      <!-- Modal them / sua chu de -->
      <div id="D_Quanlychude_LopPhu" class="D_Quanlychude_LopPhu">
        <div class="D_Quanlychude_HopModal">
          <h2 id="D_Quanlychude_TieuDeModal" class="D_Quanlychude_TieuDeModal">
            Thêm chủ đề
          </h2>

          <label class="D_Quanlychude_Nhan">Tên chủ đề</label>
          <input
            type="text"
            id="D_Quanlychude_ONhapTen"
            class="D_Quanlychude_ONhap"
          />

          <label class="D_Quanlychude_Nhan">Mô tả</label>
          <input
            type="text"
            id="D_Quanlychude_ONhapMoTa"
            class="D_Quanlychude_ONhap"
          />

          <div class="D_Quanlychude_HangNutModal">
            <button
              id="D_Quanlychude_BtnHuy"
              class="D_Quanlychude_NutTrang"
              type="button"
            >
              Hủy
            </button>
            <button
              id="D_Quanlychude_BtnLuu"
              class="D_Quanlychude_NutXam"
              type="button"
            >
              Lưu lại
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="../JS/D_Quanlychude.js"></script>
  </body>
</html>
