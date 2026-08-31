<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>LexiLoop Admin - Quản lý từ vựng</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/D_Quanlytuvung.css" />
    <script src="../jquery-4.0.0.min.js"></script>
  </head>

  <body>
    <div class="D_Quanlytuvung_Wrapper">
      <header class="D_Quanlytuvung_Topbar">
        <div class="D_Quanlytuvung_Logo">LexiLoop Admin</div>
        <div class="D_Quanlytuvung_TopbarPhai">
          <input
            type="text"
            class="D_Quanlytuvung_TimKiem"
            placeholder="Tìm kiếm"
          />
          <div class="D_Quanlytuvung_Avatar">AD</div>
        </div>
      </header>

      <div class="D_Quanlytuvung_Body">
        <nav class="D_Quanlytuvung_Sidebar">
          <a href="D_Dashboard_admin.php" class="D_Quanlytuvung_MucMenu"
            >Dashboard</a
          >
          <a href="D_Quanlynguoidung.php" class="D_Quanlytuvung_MucMenu"
            >Người dùng</a
          >
          <a href="D_Quanlychude.php" class="D_Quanlytuvung_MucMenu">Chủ đề</a>
          <a
            href="D_Quanlytuvung.php"
            class="D_Quanlytuvung_MucMenu D_Quanlytuvung_DangChon"
            >Từ vựng</a
          >
          <a href="D_Thongkehethong.php" class="D_Quanlytuvung_MucMenu"
            >Thống kê</a
          >
          <a href="D_Caidathethong.php" class="D_Quanlytuvung_MucMenu"
            >Cài đặt</a
          >
          <hr class="D_Quanlytuvung_GachNgang" />
          <a href="../main/B_homepage.php" class="D_Quanlytuvung_MucMenu"
            >Đăng xuất</a
          >
        </nav>

        <main class="D_Quanlytuvung_NoiDung">
          <div class="D_Quanlytuvung_HangTieuDe">
            <h1 class="D_Quanlytuvung_TieuDe">Quản lý từ vựng</h1>
            <div class="D_Quanlytuvung_HangNutPhai">
              <select id="D_Quanlytuvung_LocChuDe" class="D_Quanlytuvung_Loc">
                <option value="tat_ca">Lọc theo chủ đề</option>
                <option value="Du lịch">Du lịch</option>
                <option value="Công nghệ">Công nghệ</option>
                <option value="Ẩm thực">Ẩm thực</option>
                <option value="Công sở">Công sở</option>
              </select>
              <button
                id="D_Quanlytuvung_BtnThem"
                class="D_Quanlytuvung_NutXam"
                type="button"
              >
                + Thêm từ
              </button>
            </div>
          </div>

          <table class="D_Quanlytuvung_Bang">
            <thead>
              <tr>
                <th>Từ vựng</th>
                <th>Nghĩa</th>
                <th>Chủ đề</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody id="D_Quanlytuvung_ThanBang">
              <tr
                data-tuvung="Airport"
                data-nghia="Sân bay"
                data-chude="Du lịch"
              >
                <td class="D_Quanlytuvung_OTu">Airport</td>
                <td class="D_Quanlytuvung_ONghia">Sân bay</td>
                <td class="D_Quanlytuvung_OChuDe">Du lịch</td>
                <td>
                  <button class="D_Quanlytuvung_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlytuvung_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr
                data-tuvung="Software"
                data-nghia="Phần mềm"
                data-chude="Công nghệ"
              >
                <td class="D_Quanlytuvung_OTu">Software</td>
                <td class="D_Quanlytuvung_ONghia">Phần mềm</td>
                <td class="D_Quanlytuvung_OChuDe">Công nghệ</td>
                <td>
                  <button class="D_Quanlytuvung_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlytuvung_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr data-tuvung="Noodle" data-nghia="Mì" data-chude="Ẩm thực">
                <td class="D_Quanlytuvung_OTu">Noodle</td>
                <td class="D_Quanlytuvung_ONghia">Mì</td>
                <td class="D_Quanlytuvung_OChuDe">Ẩm thực</td>
                <td>
                  <button class="D_Quanlytuvung_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlytuvung_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
              <tr
                data-tuvung="Meeting"
                data-nghia="Cuộc họp"
                data-chude="Công sở"
              >
                <td class="D_Quanlytuvung_OTu">Meeting</td>
                <td class="D_Quanlytuvung_ONghia">Cuộc họp</td>
                <td class="D_Quanlytuvung_OChuDe">Công sở</td>
                <td>
                  <button class="D_Quanlytuvung_NutSua" type="button">
                    Sửa
                  </button>
                  <button class="D_Quanlytuvung_NutXoa" type="button">
                    Xóa
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </main>
      </div>

      <!-- Modal them / sua tu vung -->
      <div id="D_Quanlytuvung_LopPhu" class="D_Quanlytuvung_LopPhu">
        <div class="D_Quanlytuvung_HopModal">
          <h2
            id="D_Quanlytuvung_TieuDeModal"
            class="D_Quanlytuvung_TieuDeModal"
          >
            Thêm từ vựng
          </h2>

          <label class="D_Quanlytuvung_Nhan">Từ vựng</label>
          <input
            type="text"
            id="D_Quanlytuvung_ONhapTu"
            class="D_Quanlytuvung_ONhap"
          />

          <label class="D_Quanlytuvung_Nhan">Nghĩa</label>
          <input
            type="text"
            id="D_Quanlytuvung_ONhapNghia"
            class="D_Quanlytuvung_ONhap"
          />

          <label class="D_Quanlytuvung_Nhan">Chủ đề</label>
          <select id="D_Quanlytuvung_ONhapChuDe" class="D_Quanlytuvung_ONhap">
            <option value="Du lịch">Du lịch</option>
            <option value="Công nghệ">Công nghệ</option>
            <option value="Ẩm thực">Ẩm thực</option>
            <option value="Công sở">Công sở</option>
          </select>

          <div class="D_Quanlytuvung_HangNutModal">
            <button
              id="D_Quanlytuvung_BtnHuy"
              class="D_Quanlytuvung_NutTrang"
              type="button"
            >
              Hủy
            </button>
            <button
              id="D_Quanlytuvung_BtnLuu"
              class="D_Quanlytuvung_NutXam"
              type="button"
            >
              Lưu lại
            </button>
          </div>
        </div>
      </div>
    </div>

    <script src="../JS/D_Quanlytuvung.js"></script>
  </body>
</html>
