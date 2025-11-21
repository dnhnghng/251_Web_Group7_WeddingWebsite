<?php 
// --- KHỐI XỬ LÝ PHP ---
$title = "Sửa Sản phẩm";
require_once 'admin_header.php'; // Gọi header chung

$id = $_GET['id'] ?? null;
$errors = [];

if (!$id || !is_numeric($id)) {
    die("ID sản phẩm không hợp lệ.");
}

// XỬ LÝ KHI FORM ĐƯỢC GỬI ĐI (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_sp = trim($_POST['ten_san_pham']);
    $ma_loai = trim($_POST['ma_loai']);
    $gia_thue = trim($_POST['gia_thue']);
    $tinh_trang = trim($_POST['tinh_trang']);
    
    // 1. XỬ LÝ UPLOAD ẢNH MỚI (NẾU CÓ)
    $hinh_anh_sql_clause = ""; // Mặc định là không cập nhật ảnh
    $params = [
        ':ma_loai' => $ma_loai,
        ':ten_sp' => $ten_sp,
        ':gia_thue' => $gia_thue,
        ':tinh_trang' => $tinh_trang,
        ':id' => $id
    ];

    // Kiểm tra xem có upload file mới không
    if (isset($_FILES["hinh_anh"]) && $_FILES["hinh_anh"]["error"] == 0 && $_FILES["hinh_anh"]["size"] > 0) {
        $target_dir = "../images/";
        $file_extension = strtolower(pathinfo($_FILES["hinh_anh"]["name"], PATHINFO_EXTENSION));
        $unique_file_name = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $unique_file_name;
        $db_path = "images/" . $unique_file_name;
        
        $check = getimagesize($_FILES["hinh_anh"]["tmp_name"]);
        if($check === false) {
            $errors[] = "File tải lên không phải là ảnh.";
        }
        
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["hinh_anh"]["tmp_name"], $target_file)) {
                // Upload thành công
                // Thêm vào câu lệnh SQL và mảng params
                $hinh_anh_sql_clause = ", HinhAnh = :hinh_anh";
                $params[':hinh_anh'] = $db_path;
                
                // (Nâng cao: nên xóa file ảnh cũ ở đây)
                
            } else {
                $errors[] = "Đã xảy ra lỗi khi upload ảnh mới.";
            }
        }
    }

    // 2. KIỂM TRA (Validate) DỮ LIỆU TEXT
    if (empty($ten_sp)) $errors[] = "Tên sản phẩm là bắt buộc.";
    if (empty($ma_loai)) $errors[] = "Loại sản phẩm là bắt buộc.";
    if (empty($gia_thue) || !is_numeric($gia_thue) || $gia_thue < 0) {
        $errors[] = "Giá thuê không hợp lệ.";
    }

    // 3. NẾU KHÔNG CÓ LỖI -> UPDATE CSDL
    if (empty($errors)) {
        try {
            // Câu lệnh SQL động
            $sql = "UPDATE SANPHAM 
                    SET MaLoai = :ma_loai, 
                        TenSanPham = :ten_sp, 
                        GiaThue = :gia_thue, 
                        TinhTrang = :tinh_trang 
                        {$hinh_anh_sql_clause} 
                    WHERE MaSanPham = :id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            header("Location: quanly_sanpham.php");
            exit;

        } catch (\PDOException $e) {
            $errors[] = "Lỗi khi cập nhật CSDL: " . $e->getMessage();
        }
    }
}

// 4. LẤY DỮ LIỆU HIỆN TẠI (GET)
try {
    // Lấy thông tin sản phẩm
    $sql_sanpham = "SELECT * FROM SANPHAM WHERE MaSanPham = :id";
    $stmt_sanpham = $pdo->prepare($sql_sanpham);
    $stmt_sanpham->execute(['id' => $id]);
    $sanpham = $stmt_sanpham->fetch();

    if (!$sanpham) {
        die("Không tìm thấy sản phẩm với ID này.");
    }
    
    // Lấy danh sách Loại Sản Phẩm
    $sql_loaisp = "SELECT * FROM LOAISP ORDER BY TenLoai ASC";
    $stmt_loaisp = $pdo->query($sql_loaisp);
    $loai_sp_list = $stmt_loaisp->fetchAll();
    
} catch (\PDOException $e) {
    die("Không thể lấy dữ liệu: " . $e->getMessage());
}

?>

<title><?php echo $title; ?>: <?php echo htmlspecialchars($sanpham['TenSanPham']); ?></title>

<div class="content-box">
    <h1><?php echo $title; ?>: <?php echo htmlspecialchars($sanpham['TenSanPham']); ?></h1>

    <?php if (!empty($errors)): ?>
        <div class="form-errors" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="sua_sanpham.php?id=<?php echo $id; ?>" method="POST" class="crud-form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="ten_san_pham">Tên Sản phẩm</label>
            <input type="text" id="ten_san_pham" name="ten_san_pham" 
                   value="<?php echo htmlspecialchars($sanpham['TenSanPham']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="ma_loai">Loại Sản phẩm</label>
            <select id="ma_loai" name="ma_loai" required>
                <option value="">-- Chọn loại --</option>
                <?php foreach ($loai_sp_list as $loai): ?>
                    <option value="<?php echo $loai['MaLoai']; ?>" 
                            <?php echo ($sanpham['MaLoai'] == $loai['MaLoai']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($loai['TenLoai']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="gia_thue">Giá Thuê (VNĐ)</label>
            <input type="number" id="gia_thue" name="gia_thue" 
                   value="<?php echo htmlspecialchars($sanpham['GiaThue']); ?>" required>
        </div>
        
        <div class="form-group">
            <label>Ảnh hiện tại</label>
            <div>
                <img src="../<?php echo htmlspecialchars($sanpham['HinhAnh']); ?>" alt="Ảnh sản phẩm" style="width: 100px; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                <span style="margin-left: 10px;"><?php echo htmlspecialchars($sanpham['HinhAnh']); ?></span>
            </div>
        </div>
        <div class="form-group">
            <label for="hinh_anh">Tải ảnh mới (Bỏ trống nếu không muốn thay đổi)</label>
            <input type="file" id="hinh_anh" name="hinh_anh">
        </div>
        
        <div class="form-group">
            <label for="tinh_trang">Tình Trạng</label>
            <select id="tinh_trang" name="tinh_trang" required>
                <option value="available" <?php echo ($sanpham['TinhTrang'] == 'available') ? 'selected' : ''; ?>>Có sẵn (available)</option>
                <option value="rented" <?php echo ($sanpham['TinhTrang'] == 'rented') ? 'selected' : ''; ?>>Đã thuê (rented)</option>
                <option value="maintenance" <?php echo ($sanpham['TinhTrang'] == 'maintenance') ? 'selected' : ''; ?>>Bảo trì (maintenance)</option>
            </select>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn-add">Cập nhật</button>
            <a href="quanly_sanpham.php" class="btn-cancel">Hủy</a>
        </div>
    </form>
</div>

<?php 
// 4. Đóng thẻ main, div container và body
?>
        </main> 
    </div> 
</body>
</html>