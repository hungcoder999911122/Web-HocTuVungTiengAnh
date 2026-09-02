$(function () {
  var D_Quanlytuvung_DangSuaDong = null;

  // Loc theo chu de
  $("#D_Quanlytuvung_LocChuDe").on("change", function () {
    var chuDe = $(this).val();

    $("#D_Quanlytuvung_ThanBang tr").each(function () {
      var khop = chuDe === "tat_ca" || $(this).attr("data-chude") === chuDe;
      $(this).toggle(khop);
    });
  });

  function D_Quanlytuvung_MoModalThem() {
    D_Quanlytuvung_DangSuaDong = null;
    $("#D_Quanlytuvung_TieuDeModal").text("Thêm từ vựng");
    $("#D_Quanlytuvung_ONhapTu").val("");
    $("#D_Quanlytuvung_ONhapNghia").val("");
    $("#D_Quanlytuvung_ONhapChuDe").val("Du lịch");
    $("#D_Quanlytuvung_LopPhu").css("display", "flex");
  }

  function D_Quanlytuvung_MoModalSua($dong) {
    D_Quanlytuvung_DangSuaDong = $dong;
    $("#D_Quanlytuvung_TieuDeModal").text("Sửa từ vựng");
    $("#D_Quanlytuvung_ONhapTu").val($dong.attr("data-tuvung"));
    $("#D_Quanlytuvung_ONhapNghia").val($dong.attr("data-nghia"));
    $("#D_Quanlytuvung_ONhapChuDe").val($dong.attr("data-chude"));
    $("#D_Quanlytuvung_LopPhu").css("display", "flex");
  }

  function D_Quanlytuvung_DongModal() {
    $("#D_Quanlytuvung_LopPhu").hide();
  }

  $("#D_Quanlytuvung_BtnThem").on("click", D_Quanlytuvung_MoModalThem);

  $("#D_Quanlytuvung_ThanBang").on(
    "click",
    ".D_Quanlytuvung_NutSua",
    function () {
      D_Quanlytuvung_MoModalSua($(this).closest("tr"));
    },
  );

  $("#D_Quanlytuvung_ThanBang").on(
    "click",
    ".D_Quanlytuvung_NutXoa",
    function () {
      if (confirm("Xóa từ vựng này?")) {
        $(this).closest("tr").remove();
      }
    },
  );

  $("#D_Quanlytuvung_BtnHuy").on("click", D_Quanlytuvung_DongModal);

  $("#D_Quanlytuvung_BtnLuu").on("click", function () {
    var tu = $("#D_Quanlytuvung_ONhapTu").val().trim();
    var nghia = $("#D_Quanlytuvung_ONhapNghia").val().trim();
    var chuDe = $("#D_Quanlytuvung_ONhapChuDe").val();

    if (tu === "" || nghia === "") {
      alert("Vui lòng nhập đầy đủ từ vựng và nghĩa.");
      return;
    }

    if (D_Quanlytuvung_DangSuaDong) {
      D_Quanlytuvung_DangSuaDong.attr("data-tuvung", tu)
        .attr("data-nghia", nghia)
        .attr("data-chude", chuDe)
        .find(".D_Quanlytuvung_OTu")
        .text(tu)
        .end()
        .find(".D_Quanlytuvung_ONghia")
        .text(nghia)
        .end()
        .find(".D_Quanlytuvung_OChuDe")
        .text(chuDe);
    } else {
      var $dongMoi = $(
        '<tr data-tuvung="' +
          tu +
          '" data-nghia="' +
          nghia +
          '" data-chude="' +
          chuDe +
          '">' +
          '<td class="D_Quanlytuvung_OTu">' +
          tu +
          "</td>" +
          '<td class="D_Quanlytuvung_ONghia">' +
          nghia +
          "</td>" +
          '<td class="D_Quanlytuvung_OChuDe">' +
          chuDe +
          "</td>" +
          "<td>" +
          '<button class="D_Quanlytuvung_NutSua" type="button">Sửa</button>' +
          '<button class="D_Quanlytuvung_NutXoa" type="button">Xóa</button>' +
          "</td>" +
          "</tr>",
      );

      $("#D_Quanlytuvung_ThanBang").append($dongMoi);
    }

    D_Quanlytuvung_DongModal();
  });
});
