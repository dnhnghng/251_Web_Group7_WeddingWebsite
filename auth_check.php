<?php
// Luôn bắt đầu session ở đầu
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem "vé" (admin_id) có tồn tại không
if (!isset($_SESSION['admin_id'])) {
    // Nếu không có vé, đá về trang login
    header("Location: login.php");
    exit; // Dừng kịch bản ngay lập tức
}

// Nếu có vé, không làm gì cả, 
// file gọi (ví dụ: quanly_sanpham.php) sẽ tiếp tục chạy
?>