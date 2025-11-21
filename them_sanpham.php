<?php 
// --- KHỐI XỬ LÝ PHP ---
$title = "Thêm Sản phẩm mới";
require_once 'admin_header.php'; // Gọi header chung

$ten_sp = ''; $ma_loai = ''; $gia_thue = '';
$tinh_trang = 'available';
$errors = [];

// XỬ LÝ KHI FORM ĐƯỢC GỬI ĐI (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_sp = trim($_POST['ten_san_pham']);
    $ma_loai = trim($_POST['ma_loai']);
    $gia_thue = trim($_POST['gia_thue']);
    $tinh_trang = trim($_POST['tinh_trang']);
    
    // Khởi tạo đường dẫn ảnh mặc định
    $hinh_anh_path = 'images/placeholder.jpg'; // Ảnh mặc định

    // 1. KIỂM TRA VÀ XỬ LÝ UPLOAD ẢNH (KHỐI CODE MỚI)
    // Kiểm tra xem file đã được upload chưa và không có lỗi
    if (isset($_FILES["hinh_anh"]) && $_FILES["hinh_anh"]["error"] == 0) {
        $target_dir = "../images/"; // Thư mục lưu ảnh (đi ra 1 cấp)
        
        // Tạo tên file duy nhất (dựa trên thời gian) để tránh trùng lặp
        $file_extension = strtolower(pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION));
        $unique_file_name = time() . '_' . uniqid() . '.' . $file_extension;
        
        $target_file = $target_dir . $unique_file_name; // Đường dẫn đầy đủ trên server
        $db_path = "images/" . $unique_file_name; // Đường dẫn lưu vào CSDL
        
        // Kiểm tra 1 số điều kiện (ví dụ: là ảnh thật, kích thước,...)
        $check = getimagesize($_FILES["hinh_anh"]["tmp_name"]);
        if($check === false) {
            $errors[] = "File tải lên không phải là ảnh.";
        }
        
        // (Bạn có thể thêm check dung lượng file ở đây)

        // Nếu không lỗi, di chuyển file
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
                // Upload thành công, gán đường dẫn CSDL
                $hinh_anh_path = $db_path;
            } else {
                $errors[] = "Đã xảy ra lỗi khi upload ảnh.";
            }
        }
    }

    // 2. KIỂM TRA (Validate) DỮ LIỆU TEXT
    if (empty($ten_sp)) $errors[] = "Tên sản phẩm là bắt buộc.";
    if (empty($ma_loai)) $errors[] = "Loại sản phẩm là bắt buộc.";
    if (empty($gia_thue) || !is_numeric($gia_thue) || $gia_thue < 0) {
        $errors[] = "Giá thuê không hợp lệ.";
    }

    // 3. NẾU KHÔNG CÓ LỖI -> INSERT CSDL
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO SANPHAM (MaLoai, TenSanPham, GiaThue, TinhTrang, HinhAnh) 
                    VALUES (:ma_loai, :ten_sp, :gia_thue, :tinh_trang, :hinh_anh)";
            
            $stmt = $pdo->prepare($sql);
            
            $stmt->bindParam(':ma_loai', $ma_loai, PDO::PARAM_INT);
            $stmt->bindParam(':ten_sp', $ten_sp, PDO::PARAM_STR);
            $stmt->bindParam(':gia_thue', $gia_thue);
            $stmt->bindParam(':tinh_trang', $tinh_trang, PDO::PARAM_STR);
            $stmt->bindParam(':hinh_anh', $hinh_anh_path, PDO::PARAM_STR); // Dùng biến $hinh_anh_path
            
            $stmt->execute();
            
            // Chuyển hướng về trang danh sách
            header("Location: quanly_sanpham.php");
            exit; 

        } catch (\PDOException $e) {
            $errors[] = "Lỗi khi thêm CSDL: " . $e->getMessage();
        }
    }
}

// 4. LẤY DỮ LIỆU PHỤ (ĐỂ HIỂN THỊ FORM)
try {
    $sql_loaisp = "SELECT * FROM LOAISP ORDER BY TenLoai ASC";
    $stmt_loaisp = $pdo->query($sql_loaisp);
    $loai_sp_list = $stmt_loaisp->fetchAll();
} catch (\PDOException $e) {
    die("Không thể lấy danh sách loại sản phẩm: " . $e->getMessage());
}

?>

<title><?php echo $title; ?></title>

<div class="content-box">
    <h1><?php echo $title; ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="form-errors" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="them_sanpham.php" method="POST" class="crud-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="ten_san_pham">Tên Sản phẩm</label>
            <input type="text" id="ten_san_pham" name="ten_san_pham" value="<?php echo htmlspecialchars($ten_sp); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="ma_loai">Loại Sản phẩm</label>
            <select id="ma_loai" name="ma_loai" required>
                <option value="">-- Chọn loại --</option>
                <?php foreach ($loai_sp_list as $loai): ?>
                    <option value="<?php echo $loai['MaLoai']; ?>" <?php echo ($ma_loai == $loai['MaLoai']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($loai['TenLoai']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="gia_thue">Giá Thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" value="<?php echo htmlspecialchars($gia_thue); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="hinh_anh">Hình ảnh</label>
            <input type="file" id="hinh_anh" name="hinh_anh">
        </div>
        
        <div class="form-group">
            <label for="tinh_trang">Tình Trạng</label>
            <select id="tinh_trang" name="tinh_trang" required>
                <option value="available" selected>Có sẵn (available)</option>
                <option value="rented">Đã thuê (rented)</option>
                <option value="maintenance">Bảo trì (maintenance)</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-add">Lưu Sản phẩm</button>
            <a href="quanly_sanpham.php" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<?php 
// 4. Đóng thẻ main, div container và body (từ footer)
?>
        </main> 
    </div> 
</body>
</html>