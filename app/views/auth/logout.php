<?php
session_start();
session_unset();
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: /Quan_Ly_Su_Kien/app/views/TrangChu/trangchu.php");
exit();
?>