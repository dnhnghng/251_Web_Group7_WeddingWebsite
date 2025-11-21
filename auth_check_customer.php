<?php
// File "gác cổng" cho khách hàng
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra xem "vé" (customer_id) có tồn tại không
if (!isset($_SESSION['customer_id'])) {
    // Nếu không có vé, lưu lại trang họ muốn vào
    $_SESSION['redirect_to'] = $_SERVER['REQUEST_URI'];
    
    // Đá về trang login
    header("Location: login.php");
    exit; // Dừng kịch bản ngay lập tức
}

// Nếu đã đăng nhập, code sẽ tiếp tục chạy
?>