$(function () {
  // Tim kiem theo ten hoac email
  $("#D_Quanlynguoidung_OTimKiem").on("keyup", function () {
    var tuKhoa = $(this).val().toLowerCase();

    $("#D_Quanlynguoidung_ThanBang tr").each(function () {
      var noiDungDong = $(this).text().toLowerCase();
      $(this).toggle(noiDungDong.indexOf(tuKhoa) > -1);
    });
  });

  // Loc theo vai tro / trang thai
  function D_Quanlynguoidung_ApDungBoLoc() {
    var vaiTro = $("#D_Quanlynguoidung_LocVaiTro").val();
    var trangThai = $("#D_Quanlynguoidung_LocTrangThai").val();

    $("#D_Quanlynguoidung_ThanBang tr").each(function () {
      var khopVaiTro =
        vaiTro === "tat_ca" || $(this).attr("data-vaitro") === vaiTro;
      var khopTrangThai =
        trangThai === "tat_ca" || $(this).attr("data-trangthai") === trangThai;

      $(this).toggle(khopVaiTro && khopTrangThai);
    });
  }

  $("#D_Quanlynguoidung_LocVaiTro, #D_Quanlynguoidung_LocTrangThai").on(
    "change",
    D_Quanlynguoidung_ApDungBoLoc,
  );

  // Khoa / mo khoa tai khoan
  $(".D_Quanlynguoidung_NutKhoa").on("click", function () {
    var $dong = $(this).closest("tr");
    var $oTrangThai = $dong.find(".D_Quanlynguoidung_OTrangThai");

    var dangKhoa = $dong.attr("data-trangthai") === "da_khoa";

    if (dangKhoa) {
      $dong.attr("data-trangthai", "hoat_dong");
      $oTrangThai.text("Hoạt động");
      $(this).text("Khóa").removeClass("D_Quanlynguoidung_DangKhoa");
    } else {
      $dong.attr("data-trangthai", "da_khoa");
      $oTrangThai.text("Đã khóa");
      $(this).text("Mở khóa").addClass("D_Quanlynguoidung_DangKhoa");
    }
  });
});
