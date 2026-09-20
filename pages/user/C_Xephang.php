<?php
require_once '../../includes/auth_guard.php';
require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

$user_id = (int) $_SESSION['user_id'];

/* =========================================================
   Chọn tháng để xem xếp hạng (mặc định: tháng hiện tại)
   Định dạng: YYYY-MM
   ========================================================= */
$thangChon = $_GET['thang'] ?? date('Y-m');
if (!preg_match('/^\d{4}-\d{2}$/', $thangChon)) {
    $thangChon = date('Y-m');
}
$laThangHienTai = $thangChon === date('Y-m');

/* =========================================================
   Kiểm tra bảng user_points đã tồn tại chưa (đã chạy migration)
   ========================================================= */
$bangDiemTonTai = mysqli_num_rows(mysqli_query($link, "SHOW TABLES LIKE 'user_points'")) > 0;

$bangXepHang = [];
$viTriCuaToi = null;
$diemCuaToi = 0;

if ($bangDiemTonTai) {
    $sql = "
        SELECT u.userID, u.full_name, u.avatar_url, COALESCE(SUM(up.points), 0) AS tong_diem
        FROM Users u
        LEFT JOIN user_points up
            ON up.user_id = u.userID AND DATE_FORMAT(up.earned_at, '%Y-%m') = ?
        WHERE u.status = 'active'
        GROUP BY u.userID, u.full_name, u.avatar_url
        HAVING tong_diem > 0
        ORDER BY tong_diem DESC, u.full_name ASC
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
        if ((int) $hang['userID'] === $user_id) {
            $viTriCuaToi = $thuHang;
            $diemCuaToi = (int) $hang['tong_diem'];
        }
    }
    mysqli_stmt_close($stmt);

    // Nếu người dùng hiện tại chưa có điểm tháng này, vẫn lấy điểm thật (0) để hiển thị.
    if ($viTriCuaToi === null) {
        $sqlDiemToi = "
            SELECT COALESCE(SUM(points), 0) AS tong_diem
            FROM user_points
            WHERE user_id = ? AND DATE_FORMAT(earned_at, '%Y-%m') = ?
        ";
        $stmtToi = mysqli_prepare($link, $sqlDiemToi);
        mysqli_stmt_bind_param($stmtToi, "is", $user_id, $thangChon);
        mysqli_stmt_execute($stmtToi);
        $diemCuaToi = (int) mysqli_fetch_assoc(mysqli_stmt_get_result($stmtToi))['tong_diem'];
        mysqli_stmt_close($stmtToi);
    }
}

/* Danh sách các tháng gần đây để chọn (12 tháng gần nhất). */
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
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bảng xếp hạng - LexiLoop</title>

    <link rel="stylesheet" href="../../CSS/Style.css">
    <link rel="stylesheet" href="../../CSS/topheader.css">
    <link rel="stylesheet" href="../../CSS/C_Xephang.css">
</head>

<body class="C_Xephang_body">

    <?php include '../../includes/sidebar_user.php'; ?>

    <div class="page-content">
        <?php
        $headerTitle = 'Bảng xếp hạng';
        ob_start();
        ?>
        <form method="get" action="C_Xephang.php" class="C_Xephang_chonThang">
            <select name="thang" class="top-header-page-action top-header-page-action--control" onchange="this.form.submit()">
                <?php foreach ($danhSachThang as $ym): ?>
                    <option value="<?= htmlspecialchars($ym) ?>" <?= $ym === $thangChon ? 'selected' : '' ?>>
                        <?= htmlspecialchars(tenThangHienThi($ym)) ?><?= $ym === date('Y-m') ? ' (hiện tại)' : '' ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
        <?php
        $topHeaderPageActions = ob_get_clean();
        include '../../includes/topheader.php';
        ?>

        <main class="C_Xephang_NoiDung">

            <?php if (!$bangDiemTonTai): ?>
                <div class="C_Xephang_ThongBao C_Xephang_ThongBao--canh-bao">
                    Tính năng xếp hạng chưa được kích hoạt trên hệ thống (thiếu bảng dữ liệu
                    <code>user_points</code>). Vui lòng liên hệ quản trị viên.
                </div>
            <?php else: ?>

                <section class="C_Xephang_TheGioiThieu">
                    <h2>Cách tính điểm</h2>
                    <p>
                        Mỗi khi bạn học <strong>thuộc toàn bộ từ vựng</strong> của một chủ đề
                        (tất cả từ đạt trạng thái "Đã thuộc"), bạn nhận
                        <strong>+10 điểm</strong>. Cuối mỗi tháng, <strong>Top 5</strong>
                        người có điểm cao nhất sẽ nhận phần thưởng từ LexiLoop.
                    </p>
                </section>

                <section class="C_Xephang_TheDiemToi">
                    <div>
                        <span class="C_Xephang_NhanDiemToi">Điểm của bạn — <?= htmlspecialchars(tenThangHienThi($thangChon)) ?></span>
                        <span class="C_Xephang_SoDiemToi"><?= $diemCuaToi ?> điểm</span>
                    </div>
                    <div class="C_Xephang_ViTriToi">
                        <?= $viTriCuaToi ? 'Hạng #' . $viTriCuaToi : 'Chưa có thứ hạng' ?>
                    </div>
                </section>

                <?php if (empty($bangXepHang)): ?>
                    <div class="C_Xephang_ThongBao">
                        Chưa có ai ghi điểm trong <?= htmlspecialchars(mb_strtolower(tenThangHienThi($thangChon))) ?>.
                        Hãy là người đầu tiên hoàn thành một chủ đề!
                    </div>
                <?php else: ?>
                    <table class="C_Xephang_Bang">
                        <thead>
                            <tr>
                                <th>Hạng</th>
                                <th>Người dùng</th>
                                <th>Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bangXepHang as $hang):
                                $laToi = (int) $hang['userID'] === $user_id;
                                $laTop5 = $hang['thu_hang'] <= 5 && $laThangHienTai;
                            ?>
                                <tr class="<?= $laToi ? 'C_Xephang_HangCuaToi' : '' ?> <?= $laTop5 ? 'C_Xephang_HangTop5' : '' ?>">
                                    <td class="C_Xephang_OHang">
                                        <span class="C_Xephang_HuyHieu"><?= huyHieuThuHang($hang['thu_hang']) ?></span>
                                    </td>
                                    <td>
                                        <?= htmlspecialchars($hang['full_name'] ?? 'Người dùng') ?>
                                        <?= $laToi ? '<span class="C_Xephang_NhanBan">Bạn</span>' : '' ?>
                                    </td>
                                    <td class="C_Xephang_ODiem"><?= (int) $hang['tong_diem'] ?> điểm</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?php if ($laThangHienTai): ?>
                        <p class="C_Xephang_GhiChu">
                            🏆 Top 5 người dẫn đầu (được tô sáng) sẽ nhận phần thưởng khi tháng này kết thúc.
                        </p>
                    <?php endif; ?>
                <?php endif; ?>

            <?php endif; ?>
        </main>
    </div>

</body>

</html>
