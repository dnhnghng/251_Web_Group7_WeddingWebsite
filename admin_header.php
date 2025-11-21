<?php
// File này sẽ là file "gác cổng" MỚI
// 1. Kiểm tra đăng nhập
require_once 'auth_check.php'; // (Tự động start_session)

// 2. Kết nối CSDL
require_once '../db_connect.php'; // $pdo sẽ có sẵn
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="admin-top-bar">
        <div class="logo">
            <a href="quanly_sanpham.php">Admin WeddingBliss</a>
        </div>
        <div class="user-info">
            <span>Chào, <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</span>
            <a href="logout.php" class="btn-delete">Đăng xuất</a>
        </div>
    </header>
    
    <div class="admin-container">
        <nav class="admin-nav">
            <ul>
                <li><a href="quanly_sanpham.php">Quản lý Sản phẩm</a></li>
                <li><a href="quanly_hopdong.php">Quản lý Hợp đồng</a></li>
                </ul>
        </nav>
        
        <main class="admin-content">
            ```

