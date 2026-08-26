$(function () {
  // Hien ten file logo vua chon
  $("#D_Caidathethong_ChonFileLogo").on("change", function () {
    var tenFile = this.files.length > 0 ? this.files[0].name : "Tải ảnh lên";
    $("#D_Caidathethong_OLogo").text(tenFile);
  });
});
