<?php 
// Định nghĩa các biến cho header
$page_title = "Trang chủ";
$current_page = "home"; // Dùng để highlight menu "Trang chủ"

// 1. GỌI HEADER (đã bao gồm session_start và db_connect)
require_once 'header.php'; 

// 2. KHỐI LOGIC MỚI: CHẠY 4 TRUY VẤN
$featured_products = [];
$wedding_dresses = [];
$vests = [];
$services = [];

try {
    // === Query 1: Sản Phẩm Nổi Bật (2 Vest đắt nhất + 2 Váy cưới đắt nhất) ===
    // Dùng UNION để gộp kết quả của 2 truy vấn con
    $sql_featured = "
        (SELECT sp.* FROM SANPHAM AS sp
         JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
         WHERE lp.TenLoai = 'Váy cưới' AND sp.TinhTrang = 'available'
         ORDER BY sp.GiaThue DESC
         LIMIT 2)
        
        UNION ALL
        
        (SELECT sp.* FROM SANPHAM AS sp
         JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
         WHERE lp.TenLoai = 'Vest chú rể' AND sp.TinhTrang = 'available'
         ORDER BY sp.GiaThue DESC
         LIMIT 2)
    ";
    $stmt_featured = $pdo->query($sql_featured);
    $featured_products = $stmt_featured->fetchAll();

    // === Query 2: Toàn bộ Váy cưới (Lấy 8 sp mới nhất) ===
    $sql_dresses = "SELECT sp.* FROM SANPHAM AS sp
                    JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
                    WHERE lp.TenLoai = 'Váy cưới' AND sp.TinhTrang = 'available'
                    ORDER BY sp.MaSanPham DESC
                    LIMIT 8";
    $stmt_dresses = $pdo->query($sql_dresses);
    $wedding_dresses = $stmt_dresses->fetchAll();
    // Ghi nhớ MaLoai để dùng cho link "Xem tất cả"
    $dress_category_id = $pdo->query("SELECT MaLoai FROM LOAISP WHERE TenLoai = 'Váy cưới'")->fetchColumn();

    // === Query 3: Toàn bộ Vest nam (Lấy 8 sp mới nhất) ===
    $sql_vests = "SELECT sp.* FROM SANPHAM AS sp
                  JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
                  WHERE lp.TenLoai = 'Vest chú rể' AND sp.TinhTrang = 'available'
                  ORDER BY sp.MaSanPham DESC
                  LIMIT 8";
    $stmt_vests = $pdo->query($sql_vests);
    $vests = $stmt_vests->fetchAll();
    $vest_category_id = $pdo->query("SELECT MaLoai FROM LOAISP WHERE TenLoai = 'Vest chú rể'")->fetchColumn();

    // === Query 4: Toàn bộ Dịch vụ (Lấy 8 sp mới nhất) ===
    $sql_services = "SELECT sp.* FROM SANPHAM AS sp
                     JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
                     WHERE lp.TenLoai = 'Dụng cụ & Trang trí' AND sp.TinhTrang = 'available'
                     ORDER BY sp.MaSanPham DESC
                     LIMIT 8";
    $stmt_services = $pdo->query($sql_services);
    $services = $stmt_services->fetchAll();
    $service_category_id = $pdo->query("SELECT MaLoai FROM LOAISP WHERE TenLoai = 'Dụng cụ & Trang trí'")->fetchColumn();

} catch (\PDOException $e) {
    echo "Lỗi truy vấn CSDL: " . $e->getMessage();
}

// Hàm trợ giúp để render 1 card sản phẩm
// Giúp chúng ta không phải lặp lại code HTML 4 lần
function render_product_card($product) {
    echo '<div class="product-card">';
    echo '    <img src="' . htmlspecialchars($product['HinhAnh']) . '" alt="' . htmlspecialchars($product['TenSanPham']) . '">';
    echo '    <h3>' . htmlspecialchars($product['TenSanPham']) . '</h3>';
    echo '    <p class="price">Từ ' . number_format($product['GiaThue'], 0, ',', '.') . ' VNĐ</p>';
    echo '    <a href="chitiet_sanpham.php?id=' . $product['MaSanPham'] . '" class="btn btn-secondary">Xem chi tiết</a>';
    echo '</div>';
}

?>

<section class="hero">
    <div class="hero-content">
        <h1>Ngày hoàn hảo của bạn,</h1>
        <h2>Bắt đầu từ đây.</h2>
        <p>Dịch vụ cho thuê váy cưới và dụng cụ tiệc cưới hàng đầu.</p>
        <a href="#featured-products" class="btn btn-primary">Xem bộ sưu tập</a>
    </div>
</section>

<section id="featured-products" class="container">
    <h2 class="section-title">- Sản phẩm nổi bật- </h2>
    <div class="product-grid"> 
        <?php if (empty($featured_products)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">Chưa có sản phẩm nổi bật.</p>
        <?php else: ?>
            <?php foreach ($featured_products as $product) {
                render_product_card($product); // Gọi hàm render
            } ?>
        <?php endif; ?>
    </div>
</section>

<section id="wedding-dresses" class="container section-bg-light">
    <h2 class="section-title">Váy cưới nữ</h2>
    <div class="product-grid"> 
        <?php if (empty($wedding_dresses)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">Chưa có sản phẩm nào trong danh mục này.</p>
        <?php else: ?>
            <?php foreach ($wedding_dresses as $product) {
                render_product_card($product); // Gọi hàm render
            } ?>
        <?php endif; ?>
    </div>
    <div class="section-more-link">
        <a href="danhmuc.php?id=<?php echo $dress_category_id; ?>" class="btn btn-secondary">Xem tất cả Váy cưới</a>
    </div>
</section>

<section id="vests" class="container">
    <h2 class="section-title">Vest nam</h2>
    <div class="product-grid"> 
        <?php if (empty($vests)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">Chưa có sản phẩm nào trong danh mục này.</p>
        <?php else: ?>
            <?php foreach ($vests as $product) {
                render_product_card($product); // Gọi hàm render
            } ?>
        <?php endif; ?>
    </div>
    <div class="section-more-link">
        <a href="danhmuc.php?id=<?php echo $vest_category_id; ?>" class="btn btn-secondary">Xem tất cả Vest nam</a>
    </div>
</section>

<section id="services" class="container section-bg-light">
    <h2 class="section-title">Dịch vụ & Dụng cụ</h2>
    <div class="product-grid"> 
        <?php if (empty($services)): ?>
            <p style="text-align: center; grid-column: 1 / -1;">Chưa có sản phẩm nào trong danh mục này.</p>
        <?php else: ?>
            <?php foreach ($services as $product) {
                render_product_card($product); // Gọi hàm render
            } ?>
        <?php endif; ?>
    </div>
    <div class="section-more-link">
        <a href="danhmuc.php?id=<?php echo $service_category_id; ?>" class="btn btn-secondary">Xem tất cả Dịch vụ</a>
    </div>
</section>

<?php 
// 3. GỌI FOOTER
require_once 'footer.php'; 
?>