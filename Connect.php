<?php

define('ROOT_PATH', __DIR__);

// Kết nối MySQL
$link = mysqli_connect(
    "db",
    "webuser",
    "webpass123",
    "hoc_ngoai_ngu"
);

// Kiểm tra kết nối
if (!$link) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Thiết lập bộ mã hóa tiếng Việt
mysqli_set_charset($link, "utf8mb4");

?>