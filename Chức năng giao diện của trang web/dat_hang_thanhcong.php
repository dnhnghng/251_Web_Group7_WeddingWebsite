<?php
// --- KHỐI LẤY DỮ LIỆU / XỬ LÝ ---
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

// --- KHỐI ĐỊNH NGHĨA BIẾN CHO HEADER ---
$page_title = "Đặt hàng thành công";

// CSS riêng
$page_specific_css = '
    <style>
        .success-container {
            max-width: 600px; margin: 50px auto; background-color: #fff;
            padding: 40px; border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-container h1 {
            color: #28a745; /* Xanh lá */
            font-size: 2.5rem; margin-bottom: 15px;
        }
        .success-container p {
            font-size: 1.1rem; line-height: 1.6; margin-bottom: 30px;
        }
    </style>
';

// --- GỌI HEADER ---
require_once 'header.php';
?>

<div class="success-container">
    <h1>Đặt hàng thành công!</h1>
    <p>Cảm ơn bạn đã tin tưởng dịch vụ của WeddingBliss. Chúng tôi đã nhận được yêu cầu thuê của bạn.<br>Nhân viên của chúng tôi sẽ liên hệ với bạn qua SĐT để xác nhận hợp đồng.</p>
    <a href="index.php" class="btn btn-primary">Quay về Trang chủ</a>
</div>

<?php 
// --- GỌI FOOTER ---
require_once 'footer.php'; 
?>