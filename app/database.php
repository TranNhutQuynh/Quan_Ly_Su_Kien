<?php
// database.php

// Thông tin kết nối cơ sở dữ liệu
$host = "localhost";
$dbname = "quan_ly_su_kien";
$username = "root";
$password = "123456";

// Tạo kết nối MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Đặt charset để hỗ trợ tiếng Việt
$conn->set_charset("utf8mb4");
?>