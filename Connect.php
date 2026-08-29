<?php
$link = mysqli_connect("db", "webuser", "webpass123", "db_LexiLoop");

if (!$link) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8mb4");
?>