$(function () {
  var D_Quanlychude_DangSuaDong = null;

  function D_Quanlychude_MoModalThem() {
    D_Quanlychude_DangSuaDong = null;
    $("#D_Quanlychude_TieuDeModal").text("Thêm chủ đề");
    $("#D_Quanlychude_ONhapTen").val("");
    $("#D_Quanlychude_ONhapMoTa").val("");
    $("#D_Quanlychude_LopPhu").css("display", "flex");
  }

  function D_Quanlychude_MoModalSua($dong) {
    D_Quanlychude_DangSuaDong = $dong;
    $("#D_Quanlychude_TieuDeModal").text("Sửa chủ đề");
    $("#D_Quanlychude_ONhapTen").val($dong.attr("data-tenchude"));
    $("#D_Quanlychude_ONhapMoTa").val($dong.attr("data-mota"));
    $("#D_Quanlychude_LopPhu").css("display", "flex");
  }

  function D_Quanlychude_DongModal() {
    $("#D_Quanlychude_LopPhu").hide();
  }

  // Mo modal them chu de moi
  $("#D_Quanlychude_BtnThem").on("click", D_Quanlychude_MoModalThem);

  // Mo modal sua chu de (nhan tu dong tuong ung)
  $("#D_Quanlychude_ThanBang").on(
    "click",
    ".D_Quanlychude_NutSua",
    function () {
      D_Quanlychude_MoModalSua($(this).closest("tr"));
    },
  );

  // Xoa chu de
  $("#D_Quanlychude_ThanBang").on(
    "click",
    ".D_Quanlychude_NutXoa",
    function () {
      if (confirm("Xóa chủ đề này?")) {
        $(this).closest("tr").remove();
      }
    },
  );

  // Huy modal
  $("#D_Quanlychude_BtnHuy").on("click", D_Quanlychude_DongModal);

  // Luu chu de (them moi hoac cap nhat dong dang sua)
  $("#D_Quanlychude_BtnLuu").on("click", function () {
    var ten = $("#D_Quanlychude_ONhapTen").val().trim();
    var moTa = $("#D_Quanlychude_ONhapMoTa").val().trim();

    if (ten === "") {
      alert("Vui lòng nhập tên chủ đề.");
      return;
    }

    if (D_Quanlychude_DangSuaDong) {
      // Cap nhat dong da co
      D_Quanlychude_DangSuaDong.attr("data-tenchude", ten)
        .attr("data-mota", moTa)
        .find(".D_Quanlychude_OTen")
        .text(ten);
    } else {
      // Them dong moi vao cuoi bang
      var homNay = new Date();
      var ngayTao =
        ("0" + homNay.getDate()).slice(-2) +
        "/" +
        ("0" + (homNay.getMonth() + 1)).slice(-2) +
        "/" +
        homNay.getFullYear();

      var $dongMoi = $(
        '<tr data-tenchude="' +
          ten +
          '" data-mota="' +
          moTa +
          '">' +
          '<td class="D_Quanlychude_OTen">' +
          ten +
          "</td>" +
          "<td>0</td>" +
          "<td>" +
          ngayTao +
          "</td>" +
          "<td>" +
          '<button class="D_Quanlychude_NutSua" type="button">Sửa</button>' +
          '<button class="D_Quanlychude_NutXoa" type="button">Xóa</button>' +
          "</td>" +
          "</tr>",
      );

      $("#D_Quanlychude_ThanBang").append($dongMoi);
    }

    D_Quanlychude_DongModal();
  });
});
