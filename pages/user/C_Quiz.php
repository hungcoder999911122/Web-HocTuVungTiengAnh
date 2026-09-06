<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . "/Connect.php");

// Lấy trực tiếp ID tài khoản từ Session
$user_id = $_SESSION['user_id'] 
    ?? $_SESSION['userID'] 
    ?? $_SESSION['id'] 
    ?? $_SESSION['user']['userID'] 
    ?? $_SESSION['user']['id'] 
    ?? 2; // Dự phòng user 2 khi mở link test trực tiếp

// Lấy ID chủ đề từ URL
$id_chu_de = isset($_GET['id']) ? intval($_GET['id']) : (isset($_GET['topic_id']) ? intval($_GET['topic_id']) : 4);
if ($id_chu_de <= 0) {
    $id_chu_de = 4;
}

$ten_chu_de = "Du lịch";
$danh_sach_cau_hoi = [];

// Dữ liệu dự phòng nếu database chưa có dữ liệu
$du_lieu_du_phong = [
    [
        "id"          => 1,
        "tu_vung"     => "Airport",
        "dap_an"      => ["A. Sân bay", "B. Bến xe", "C. Nhà ga", "D. Bến tàu"],
        "dap_an_dung" => 0
    ],
    [
        "id"          => 2,
        "tu_vung"     => "Passport",
        "dap_an"      => ["A. Vé máy bay", "B. Hộ chiếu", "C. Giấy phép lái xe", "D. Thẻ căn cước"],
        "dap_an_dung" => 1
    ],
    [
        "id"          => 3,
        "tu_vung"     => "Luggage",
        "dap_an"      => ["A. Khách sạn", "B. Bản đồ du lịch", "C. Hành lý", "D. Chuyến bay"],
        "dap_an_dung" => 2
    ],
    [
        "id"          => 4,
        "tu_vung"     => "Ticket",
        "dap_an"      => ["A. Vé đi lại", "B. Hộ chiếu", "C. Hướng dẫn viên", "D. Hành lý"],
        "dap_an_dung" => 0
    ],
    [
        "id"          => 5,
        "tu_vung"     => "Flight",
        "dap_an"      => ["A. Đường cao tốc", "B. Nhà chờ", "C. Tàu hoả", "D. Chuyến bay"],
        "dap_an_dung" => 3
    ]
];

try {
    if (isset($link) && $link) {
        // Quét danh sách bảng thực tế tránh lỗi phân biệt hoa/thường trên Linux
        $tables_res = @mysqli_query($link, "SHOW TABLES");
        $db_tables = [];
        if ($tables_res) {
            while ($tbl_row = mysqli_fetch_array($tables_res)) {
                $db_tables[strtolower($tbl_row[0])] = $tbl_row[0];
            }
        }

        $tbl_vocab  = isset($db_tables['vocabulary']) ? "`" . $db_tables['vocabulary'] . "`" : "`vocabulary`";
        $tbl_topics = isset($db_tables['topics']) ? "`" . $db_tables['topics'] . "`" : "`Topics`";

        // Lấy tên chủ đề
        if (isset($db_tables['topics'])) {
            $stmt_topic = @mysqli_prepare($link, "SELECT topicName FROM $tbl_topics WHERE topicID = ? LIMIT 1");
            if ($stmt_topic) {
                mysqli_stmt_bind_param($stmt_topic, "i", $id_chu_de);
                mysqli_stmt_execute($stmt_topic);
                $res_t = mysqli_stmt_get_result($stmt_topic);
                if ($row_t = mysqli_fetch_assoc($res_t)) {
                    $ten_chu_de = $row_t['topicName'];
                }
                mysqli_stmt_close($stmt_topic);
            }
        }

        // Tự động sinh câu hỏi Quiz từ bảng vocabulary
        if (isset($db_tables['vocabulary'])) {
            // Lấy danh sách từ vựng thuộc chủ đề này (tối đa 10 câu)
            $topic_words = [];
            $stmt_w = @mysqli_prepare($link, "SELECT id, word, meaning FROM $tbl_vocab WHERE topic_id = ? ORDER BY RAND() LIMIT 10");
            if ($stmt_w) {
                mysqli_stmt_bind_param($stmt_w, "i", $id_chu_de);
                mysqli_stmt_execute($stmt_w);
                $res_w = mysqli_stmt_get_result($stmt_w);
                while ($rw = mysqli_fetch_assoc($res_w)) {
                    $topic_words[] = $rw;
                }
                mysqli_stmt_close($stmt_w);
            }

            // Lấy kho đáp án sai (các nghĩa của từ ở chủ đề khác)
            $distractor_pool = [];
            $res_pool = @mysqli_query($link, "SELECT DISTINCT meaning FROM $tbl_vocab WHERE topic_id != $id_chu_de ORDER BY RAND() LIMIT 30");
            if ($res_pool) {
                while ($rp = mysqli_fetch_assoc($res_pool)) {
                    $distractor_pool[] = trim($rp['meaning']);
                }
            }

            // Gom thêm cả các nghĩa trong cùng chủ đề để làm phong phú đáp án gây nhiễu
            $all_topic_meanings = array_column($topic_words, 'meaning');

            // Tiến hành tạo 4 đáp án A, B, C, D cho từng câu hỏi
            $prefixes = ["A. ", "B. ", "C. ", "D. "];

            foreach ($topic_words as $item) {
                $correct_meaning = trim($item['meaning']);
                
                // Lấy các đáp án sai tiềm năng (khác với đáp án đúng)
                $wrong_candidates = array_filter(
                    array_merge($all_topic_meanings, $distractor_pool), 
                    function($m) use ($correct_meaning) {
                        return trim($m) !== $correct_meaning && !empty($m);
                    }
                );
                $wrong_candidates = array_unique($wrong_candidates);
                shuffle($wrong_candidates);

                // Lấy 3 đáp án sai
                $wrong_answers = array_slice($wrong_candidates, 0, 3);

                // Nếu không đủ 3 đáp án sai, bổ sung thêm đáp án mẫu
                $fallbacks = ["Không xác định", "Phương án khác", "Đáp án khác"];
                while (count($wrong_answers) < 3) {
                    $wrong_answers[] = array_shift($fallbacks);
                }

                // Gộp đáp án đúng và 3 đáp án sai rồi xáo trộn vị trí
                $options = array_merge([$correct_meaning], $wrong_answers);
                shuffle($options);

                // Tìm vị trí đúng (0, 1, 2 hoặc 3)
                $correct_index = array_search($correct_meaning, $options);

                // Gán nhãn tiền tố A., B., C., D.
                $final_options = [];
                foreach ($options as $idx => $opt) {
                    $final_options[] = $prefixes[$idx] . $opt;
                }

                $danh_sach_cau_hoi[] = [
                    "id"          => (int)$item['id'],
                    "tu_vung"     => ucfirst($item['word']),
                    "dap_an"      => $final_options,
                    "dap_an_dung" => (int)$correct_index
                ];
            }
        }
    }
} catch (\Throwable $e) {
    error_log("Lỗi tạo Quiz: " . $e->getMessage());
}

// Nếu CSDL chưa có từ vựng thì dùng mảng câu hỏi dự phòng
if (empty($danh_sach_cau_hoi)) {
    $danh_sach_cau_hoi = $du_lieu_du_phong;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz: <?php echo htmlspecialchars($ten_chu_de); ?> - LexiLoop</title>
    <link rel="stylesheet" href="../../CSS/C_Quiz.css">
</head>
<body class="C_Quiz_body">

    <!-- Header -->
    <header class="C_Quiz_header">
        <h1 class="C_Quiz_logo">Quiz: <?php echo htmlspecialchars($ten_chu_de); ?></h1>
        <span class="C_Quiz_progressText" id="C_Quiz_progressText">Câu 1/<?php echo count($danh_sach_cau_hoi); ?></span>
    </header>

    <!-- Thanh tiến độ Quiz -->
    <div class="C_Quiz_progressBarWrapper">
        <div class="C_Quiz_progressBar">
            <div class="C_Quiz_progressFill" id="C_Quiz_progressFill" style="width: 20%;"></div>
        </div>
    </div>

    <!-- Nội dung chính câu hỏi -->
    <main class="C_Quiz_main">
        
        <!-- Đồng hồ đếm ngược -->
        <div class="C_Quiz_timerWrapper">
            <div class="C_Quiz_timerCircle" id="C_Quiz_timerCircle">15s</div>
        </div>

        <!-- Từ vựng câu hỏi -->
        <div class="C_Quiz_questionSection">
            <p class="C_Quiz_instruction">Chọn nghĩa đúng của từ:</p>
            <h2 class="C_Quiz_questionWord" id="C_Quiz_questionWord">Airport</h2>
        </div>

        <!-- Lưới 4 đáp án -->
        <div class="C_Quiz_optionsGrid" id="C_Quiz_optionsGrid">
            <button type="button" class="C_Quiz_optionBtn" data-index="0">A. Sân bay</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="1">B. Bến xe</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="2">C. Nhà ga</button>
            <button type="button" class="C_Quiz_optionBtn" data-index="3">D. Bến tàu</button>
        </div>

        <!-- Các nút điều hướng -->
        <div class="C_Quiz_navButtons">
            <button type="button" id="C_Quiz_btnCauTruoc" class="C_Quiz_btnNav C_Quiz_btnWhite">
                &larr; Câu trước
            </button>
            <button type="button" id="C_Quiz_btnCauTiep" class="C_Quiz_btnNav C_Quiz_btnGray">
                Câu tiếp &rarr;
            </button>
        </div>

        <!-- Danh sách bóng câu hỏi -->
        <footer class="C_Quiz_questionListSection">
            <p class="C_Quiz_listLabel">Danh sách câu hỏi</p>
            <div class="C_Quiz_bubblesRow" id="C_Quiz_bubblesRow"> </div>
        </footer>

    </main>

    <script>
        const quizQuestions = <?php echo json_encode($danh_sach_cau_hoi, JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <script src="../../JS/C_Quiz.js"></script>
</body>
</html>