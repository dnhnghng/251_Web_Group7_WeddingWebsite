<?php
// --- LẤY DỮ LIỆU TRƯỚC KHI GỌI HEADER ---
require_once 'db_connect.php';

// 1. Lấy ID danh mục từ URL
$category_id = $_GET['id'] ?? null;
$category_info = null; // <-- ĐÃ ĐỔI TÊN TỪ $category
$products = [];

if (!$category_id || !is_numeric($category_id)) {
    die("Danh mục không hợp lệ.");
}

// 2. Lấy thông tin Danh mục để hiển thị tên
try {
    $sql_cat = "SELECT TenLoai FROM LOAISP WHERE MaLoai = :id";
    $stmt_cat = $pdo->prepare($sql_cat);
    $stmt_cat->execute(['id' => $category_id]);
    
    // Đổ vào biến MỚI
    $category_info = $stmt_cat->fetch(); // <-- ĐÃ ĐỔI TÊN
    
    if (!$category_info) { // <-- ĐÃ SỬA
        die("Không tìm thấy danh mục này.");
    }
    
    // 3. Lấy tất cả sản phẩm thuộc danh mục này
    $sql_prod = "SELECT * FROM SANPHAM WHERE MaLoai = :id AND TinhTrang = 'available' ORDER BY MaSanPham DESC";
    $stmt_prod = $pdo->prepare($sql_prod);
    $stmt_prod->execute(['id' => $category_id]);
    $products = $stmt_prod->fetchAll();

} catch (\PDOException $e) {
    die("Lỗi CSDL: " . $e->getMessage());
}

// 4. Định nghĩa biến cho header (SAU KHI ĐÃ CÓ $category_info)
$page_title = $category_info['TenLoai']; // <-- ĐÃ SỬA
// $category_id đã có ở trên (dùng để highlight menu)

// 5. Gọi Header
// (header.php vẫn dùng $category trong vòng lặp, nhưng giờ nó không ảnh hưởng nữa)
require_once 'header.php';
?>

<div class="container">
    <h2 class="section-title">Danh mục: <?php echo htmlspecialchars($category_info['TenLoai']); ?></h2>

    <section class="product-grid">
        <?php if (empty($products)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">Chưa có sản phẩm nào trong danh mục này.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" 
                         alt="<?php echo htmlspecialchars($product['TenSanPham']); ?>">
                    
                    <h3><?php echo htmlspecialchars($product['TenSanPham']); ?></h3>
                    
                    <p class="price">
                        Từ <?php echo number_format($product['GiaThue'], 0, ',', '.'); ?> VNĐ
                    </p>
                    
                    <a href="chitiet_sanpham.php?id=<?php echo $product['MaSanPham']; ?>" class="btn btn-secondary">
                        Xem chi tiết
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

<?php 
// 6. Gọi Footer
require_once 'footer.php'; 
?>