<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");
/** @var mysqli $link Ket noi CSDL duoc tao trong Connect.php */

$thongBao = "";
$loaiThongBao = "";

$bangDiemTonTai = mysqli_num_rows(mysqli_query($link, "SHOW TABLES LIKE 'user_points'")) > 0;
$bangThuongTonTai = mysqli_num_rows(mysqli_query($link, "SHOW TABLES LIKE 'monthly_rewards'")) > 0;

// ---- Xử lý lưu phần thưởng cho 1 người dùng trong 1 tháng ----
if ($_SERVER["REQUEST_METHOD"] === "POST" && $bangThuongTonTai) {
    $hanhDong = $_POST["hanhdong"] ?? "";
    $userID   = isset($_POST["userID"]) ? (int) $_POST["userID"] : 0;
    $thangGui = $_POST["thang"] ?? "";
    $hangGui  = isset($_POST["hang"]) ? (int) $_POST["hang"] : 0;
    $diemGui  = isset($_POST["diem"]) ? (int) $_POST["diem"] : 0;
    $tenQua   = trim($_POST["ten_qua"] ?? "");

    if ($hanhDong === "luuphanthuong" && $userID > 0 && preg_match('/^\d{4}-\d{2}$/', $thangGui) && $hangGui > 0) {
        $sql = "
            INSERT INTO monthly_rewards (user_id, year_month, rank_position, points, reward_name)
            VALUES (?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
                rank_position = VALUES(rank_position),
                points = VALUES(points),
                reward_name = VALUES(reward_name)
        ";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "isiis", $userID, $thangGui, $hangGui, $diemGui, $tenQua);

        if (mysqli_stmt_execute($stmt)) {
            $thongBao = "Đã lưu phần thưởng.";
            $loaiThongBao = "thanhcong";
        } else {
            $thongBao = "Có lỗi xảy ra: " . mysqli_error($link);
            $loaiThongBao = "loi";
        }
        mysqli_stmt_close($stmt);
    }
}

// ---- Tháng đang xem (mặc định: tháng trước, vì tháng hiện tại chưa kết thúc) ----
$thangChon = $_GET['thang'] ?? date('Y-m', strtotime('-1 month'));
if (!preg_match('/^\d{4}-\d{2}$/', $thangChon)) {
    $thangChon = date('Y-m', strtotime('-1 month'));
}

$bangXepHang = [];
$phanThuongDaLuu = [];

if ($bangDiemTonTai) {
    $sql = "
        SELECT u.userID, u.full_name, u.email, COALESCE(SUM(up.points), 0) AS tong_diem
        FROM Users u
        INNER JOIN user_points up
            ON up.user_id = u.userID AND DATE_FORMAT(up.earned_at, '%Y-%m') = ?
        GROUP BY u.userID, u.full_name, u.email
        ORDER BY tong_diem DESC, u.full_name ASC
        LIMIT 10
    ";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "s", $thangChon);
    mysqli_stmt_execute($stmt);
    $ketQua = mysqli_stmt_get_result($stmt);
    $thuHang = 0;
    while ($hang = mysqli_fetch_assoc($ketQua)) {
        $thuHang++;
        $hang['thu_hang'] = $thuHang;
        $bangXepHang[] = $hang;
    }
    mysqli_stmt_close($stmt);
}

if ($bangThuongTonTai) {
    $sqlThuong = "SELECT user_id, reward_name FROM monthly_rewards WHERE `year_month` = ?";
    $stmtThuong = mysqli_prepare($link, $sqlThuong);
    mysqli_stmt_bind_param($stmtThuong, "s", $thangChon);
    mysqli_stmt_execute($stmtThuong);
    $ketQuaThuong = mysqli_stmt_get_result($stmtThuong);
    while ($row = mysqli_fetch_assoc($ketQuaThuong)) {
        $phanThuongDaLuu[(int) $row['user_id']] = $row['reward_name'];
    }
    mysqli_stmt_close($stmtThuong);
}

/* Danh sách 12 tháng gần đây để chọn. */
$danhSachThang = [];
for ($i = 0; $i < 12; $i++) {
    $danhSachThang[] = date('Y-m', strtotime("-$i month"));
}

function tenThangHienThi(string $ym): string
{
    [$nam, $thang] = explode('-', $ym);
    return "Tháng " . (int) $thang . "/" . $nam;
}

function huyHieuThuHang(int $thuHang): string
{
    return match ($thuHang) {
        1 => '🥇',
        2 => '🥈',
        3 => '🥉',
        default => (string) $thuHang,
    };
}
?>
<!doctype html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <title>LexiLoop Admin - Xếp hạng & Phần thưởng</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/D_Quanlynguoidung.css" />
    <link rel="stylesheet" type="text/css" href="../../CSS/D_Xephang.css" />
    <script src="../jquery-4.0.0.min.js"></script>
  </head>

  <body>
    <div class="D_Quanlynguoidung_Wrapper">
      <header class="D_Quanlynguoidung_Topbar">
        <div class="D_Quanlynguoidung_Logo">LexiLoop Admin</div>
        <div class="D_Quanlynguoidung_TopbarPhai">
          <input type="text" class="D_Quanlynguoidung_TimKiem" placeholder="Tìm kiếm" />
          <div class="D_Quanlynguoidung_Avatar">AD</div>
        </div>
      </header>

      <div class="D_Quanlynguoidung_Body">
        <nav class="D_Quanlynguoidung_Sidebar">
          <a href="D_Dashboard_admin.php" class="D_Quanlynguoidung_MucMenu">Dashboard</a>
          <a href="D_Quanlynguoidung.php" class="D_Quanlynguoidung_MucMenu">Người dùng</a>
          <a href="D_Quanlychude.php" class="D_Quanlynguoidung_MucMenu">Chủ đề</a>
          <a href="D_Quanlytuvung.php" class="D_Quanlynguoidung_MucMenu">Từ vựng</a>
          <a href="D_Xephang.php" class="D_Quanlynguoidung_MucMenu D_Quanlynguoidung_DangChon">Xếp hạng</a>
          <a href="D_Thongkehethong.php" class="D_Quanlynguoidung_MucMenu">Thống kê</a>
          <a href="D_Caidathethong.php" class="D_Quanlynguoidung_MucMenu">Cài đặt</a>
          <hr class="D_Quanlynguoidung_GachNgang" />
          <a href="../auth/A_DangXuat.php" class="D_Quanlynguoidung_MucMenu">Đăng xuất</a>
        </nav>

        <main class="D_Quanlynguoidung_NoiDung">
          <div class="D_Quanlynguoidung_HangTieuDe">
            <h1 class="D_Quanlynguoidung_TieuDe">Xếp hạng & Phần thưởng</h1>
            <form method="get" action="D_Xephang.php">
              <select name="thang" onchange="this.form.submit()">
                <?php foreach ($danhSachThang as $ym): ?>
                  <option value="<?= htmlspecialchars($ym) ?>" <?= $ym === $thangChon ? 'selected' : '' ?>>
                    <?= htmlspecialchars(tenThangHienThi($ym)) ?><?= $ym === date('Y-m') ? ' (đang diễn ra)' : '' ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </form>
          </div>

          <?php if ($thongBao !== ""): ?>
            <p class="D_Quanlynguoidung_ThongBao D_Quanlynguoidung_ThongBao_<?php echo $loaiThongBao; ?>">
              <?php echo htmlspecialchars($thongBao); ?>
            </p>
          <?php endif; ?>

          <?php if (!$bangDiemTonTai || !$bangThuongTonTai): ?>
            <p class="D_Xephang_CanhBao">
              Chưa chạy migration hệ thống điểm thưởng. Vào phpMyAdmin, chạy file
              <code>data/migrations/2026_09_20_add_ranking_and_rewards.sql</code> trên database ứng dụng đang dùng.
            </p>
          <?php else: ?>

            <?php if ($thangChon === date('Y-m')): ?>
              <p class="D_Xephang_GhiChu">
                Tháng này vẫn đang diễn ra, điểm số có thể còn thay đổi. Nên xét thưởng sau khi tháng đã kết thúc.
              </p>
            <?php endif; ?>

            <?php if (empty($bangXepHang)): ?>
              <p class="D_Xephang_ThongBaoRong">Chưa có ai ghi điểm trong <?= htmlspecialchars(mb_strtolower(tenThangHienThi($thangChon))) ?>.</p>
            <?php else: ?>
              <table class="D_Quanlynguoidung_Bang">
                <thead>
                  <tr>
                    <th>Hạng</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Điểm</th>
                    <th>Phần thưởng</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($bangXepHang as $hang):
                      $laTop5 = $hang['thu_hang'] <= 5;
                      $quaHienTai = $phanThuongDaLuu[(int) $hang['userID']] ?? '';
                  ?>
                    <tr class="<?= $laTop5 ? 'D_Xephang_HangTop5' : '' ?>">
                      <td><?= huyHieuThuHang($hang['thu_hang']) ?></td>
                      <td><?php echo htmlspecialchars($hang["full_name"] ?? ""); ?></td>
                      <td><?php echo htmlspecialchars($hang["email"]); ?></td>
                      <td><?= (int) $hang['tong_diem'] ?> điểm</td>
                      <td>
                        <?php if ($laTop5): ?>
                          <form method="post" action="D_Xephang.php?thang=<?= htmlspecialchars($thangChon) ?>" class="D_Xephang_FormQua">
                            <input type="hidden" name="hanhdong" value="luuphanthuong" />
                            <input type="hidden" name="userID" value="<?= (int) $hang['userID'] ?>" />
                            <input type="hidden" name="thang" value="<?= htmlspecialchars($thangChon) ?>" />
                            <input type="hidden" name="hang" value="<?= (int) $hang['thu_hang'] ?>" />
                            <input type="hidden" name="diem" value="<?= (int) $hang['tong_diem'] ?>" />
                            <input
                              type="text"
                              name="ten_qua"
                              class="D_Xephang_OQua"
                              placeholder="VD: Voucher 100k"
                              value="<?php echo htmlspecialchars($quaHienTai); ?>" />
                            <button type="submit" class="D_Xephang_NutLuu">Lưu</button>
                          </form>
                        <?php else: ?>
                          <span class="D_Xephang_KhongApDung">—</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          <?php endif; ?>
        </main>
      </div>
    </div>
  </body>
</html>
