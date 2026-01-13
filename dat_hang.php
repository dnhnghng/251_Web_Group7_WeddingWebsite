<?php
// --- KHỐI LẤY DỮ LIỆU / XỬ LÝ ---
require_once 'auth_check_customer.php'; 
require_once 'db_connect.php';

$product_id = $_GET['id'] ?? null;
$product = null;
$errors = [];
$ngay_thue = ''; $ngay_tra = '';

if (!$product_id || !is_numeric($product_id)) {
    die("ID sản phẩm không hợp lệ.");
}

// ... (Toàn bộ logic POST và GET giữ nguyên) ...
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ngay_thue = $_POST['ngay_thue'];
    $ngay_tra = $_POST['ngay_tra'];
    $product_id_post = $_POST['product_id'];
    $customer_id = $_SESSION['customer_id'];
    $gia_thue = $_POST['gia_thue'];
    
    if (empty($ngay_thue) || empty($ngay_tra)) $errors[] = "Vui lòng chọn cả ngày thuê và ngày trả.";
    else if ($ngay_tra < $ngay_thue) $errors[] = "Ngày trả không thể trước ngày thuê.";

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            $sql_hopdong = "INSERT INTO HOPDONG (MaKhachHang, NgayThue, NgayTra, TongTien, TrangThai)
                            VALUES (:ma_kh, :ngay_thue, :ngay_tra, :tong_tien, 'pending')";
            $stmt_hopdong = $pdo->prepare($sql_hopdong);
            $stmt_hopdong->execute([
                'ma_kh' => $customer_id, 'ngay_thue' => $ngay_thue,
                'ngay_tra' => $ngay_tra, 'tong_tien' => $gia_thue
            ]);
            
            $new_hopdong_id = $pdo->lastInsertId();
            
            $sql_chitiet = "INSERT INTO HOPDONGCHITIET (SoHD, MaSanPham, SoLuong, GiaLucThue)
                            VALUES (:so_hd, :ma_sp, 1, :gia_thue)";
            $stmt_chitiet = $pdo->prepare($sql_chitiet);
            $stmt_chitiet->execute([
                'so_hd' => $new_hopdong_id, 'ma_sp' => $product_id_post, 'gia_thue' => $gia_thue
            ]);
            
            $pdo->commit();
            header("Location: dat_hang_thanhcong.php");
            exit;
        } catch (\PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Lỗi khi đặt hàng: " . $e->getMessage();
        }
    }
}

try {
    $sql_get = "SELECT * FROM SANPHAM WHERE MaSanPham = :id AND TinhTrang = 'available'";
    $stmt_get = $pdo->prepare($sql_get);
    $stmt_get->execute(['id' => $product_id]);
    $product = $stmt_get->fetch();
    
    if (!$product) die("Sản phẩm này không tồn tại hoặc đã hết hàng.");
} catch (\PDOException $e) { die("Lỗi CSDL: " . $e->getMessage()); }

// --- KHỐI ĐỊNH NGHĨA BIẾN CHO HEADER ---
$page_title = "Xác nhận Thuê";

// CSS riêng
$page_specific_css = '
    <style>
        .form-container { max-width: 800px; margin: 50px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-container h1 { text-align: center; border-bottom: none; margin-bottom: 20px; }
        .crud-form { margin: 0; padding: 0; background: none; border: none; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="date"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        .form-actions { margin-top: 20px; }
        .btn-add { width: 100%; padding: 12px; font-size: 1.1rem; }
        .form-errors {
            background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
            padding: 10px 20px; border-radius: 4px; margin-bottom: 20px;
        }
        .form-errors p { margin: 5px 0; }
        .product-summary { display: flex; gap: 20px; border-bottom: 1px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .product-summary img { width: 100px; height: 120px; object-fit: cover; border-radius: 4px; }
        .product-summary-info h3 { margin: 0; }
        .product-summary-info .price { font-size: 1.2rem; color: var(--primary-color); font-weight: 700; }
    </style>
';

// --- GỌI HEADER ---
require_once 'header.php';
?>

<div class="form-container">
    <h1>Xác nhận Thuê</h1>

    <div class="product-summary">
        <img src="<?php echo htmlspecialchars($product['HinhAnh']); ?>" alt="">
        <div class="product-summary-info">
            <h3><?php echo htmlspecialchars($product['TenSanPham']); ?></h3>
            <p class="price"><?php echo number_format($product['GiaThue'], 0, ',', '.'); ?> VNĐ</p>
        </div>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="dat_hang.php?id=<?php echo $product_id; ?>" method="POST" class="crud-form">
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
        <input type="hidden" name="gia_thue" value="<?php echo $product['GiaThue']; ?>">
        
        <div class="form-group">
            <label for="ngay_thue">Chọn Ngày Thuê</label>
            <input type="date" id="ngay_thue" name="ngay_thue" min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo htmlspecialchars($ngay_thue); ?>" required>
        </div>
        <div class="form-group">
            <label for="ngay_tra">Chọn Ngày Trả</label>
            <input type="date" id="ngay_tra" name="ngay_tra" min="<?php echo date('Y-m-d'); ?>"
                   value="<?php echo htmlspecialchars($ngay_tra); ?>" required>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-add">Xác nhận Thuê</button>
        </div>
    </form>
</div>

<?php 
// --- GỌI FOOTER ---
require_once 'footer.php'; 
?>