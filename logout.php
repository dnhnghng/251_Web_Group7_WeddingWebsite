<?php
session_start(); // Khởi động session

// Hủy tất cả các biến session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: login.php");
exit;
?>