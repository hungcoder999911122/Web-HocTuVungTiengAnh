<?php
// Connect.php (đặt ở gốc webhoctienganh/)
define('ROOT_PATH', __DIR__);

$link = mysqli_connect("db", "webuser", "webpass123", "db_LexiLoop");    die("Kết nối thất bại: " . mysqli_connect_error());
}
mysqli_set_charset($link, "utf8mb4");
?>