<?php
// Luôn bắt đầu session ở đầu
session_start();
// Kết nối CSDL
require_once 'db_connect.php';

// KHỐI LỆNH MỚI: Tự động lấy danh mục từ CSDL
$categories_list = [];
try {
    // Lấy tất cả danh mục từ bảng LOAISP
    $sql_cats = "SELECT * FROM LOAISP ORDER BY TenLoai ASC";
    $stmt_cats = $pdo->query($sql_cats);
    $categories_list = $stmt_cats->fetchAll();
} catch (\PDOException $e) {
    // Bỏ qua lỗi nếu có
    echo "Lỗi không thể tải danh mục: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - WeddingBliss' : 'WeddingBliss - Dịch vụ Cưới'; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    
    <?php if (isset($page_specific_css)) echo $page_specific_css; ?>
</head>
<body>

    <header>
        <div class="container">
            <a href="index.php" class="logo">WeddingBliss</a>
            <nav>
                <ul>
                    <li><a href="index.php" class="<?php echo ($current_page == 'home') ? 'active' : ''; ?>">Trang chủ</a></li>
                    
                    <?php foreach ($categories_list as $category): ?>
                        <li>
                            <a href="danhmuc.php?id=<?php echo $category['MaLoai']; ?>"
                               class="<?php echo (isset($category_id) && $category_id == $category['MaLoai']) ? 'active' : ''; ?>">
                                <?php echo htmlspecialchars($category['TenLoai']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    
                    <li><a href="gioi_thieu.php" class="<?php echo ($current_page == 'about') ? 'active' : ''; ?>">Giới thiệu</a></li>
                    <li><a href="lien_he.php" class="<?php echo ($current_page == 'contact') ? 'active' : ''; ?>">Liên hệ</a></li>
                </ul>
            </nav>
            <div class="auth-buttons">
                <?php if (isset($_SESSION['customer_id'])): ?>
                    <span style="color: #333; margin-right: 15px;">
                        Chào, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>!
                    </span>
                    <a href="logout.php" class="btn btn-secondary">Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-secondary">Đăng nhập</a>
                    <a href="register.php" class="btn btn-primary">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main>