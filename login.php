<?php
// BẮT BUỘC phải gọi session_start() ở đầu file
session_start();

// 1. Kết nối CSDL
require_once '../db_connect.php';

$error_message = '';

// 2. Kiểm tra xem người dùng đã đăng nhập chưa?
// Nếu đã đăng nhập, chuyển thẳng về trang quản lý
if (isset($_SESSION['admin_id'])) {
    header("Location: quanly_sanpham.php");
    exit;
}

// 3. Xử lý khi form được gửi đi (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ten_dang_nhap = trim($_POST['ten_dang_nhap']);
    $mat_khau = trim($_POST['mat_khau']);
    
    // 3.1. Kiểm tra dữ liệu
    if (empty($ten_dang_nhap) || empty($mat_khau)) {
        $error_message = "Vui lòng nhập cả Tên đăng nhập và Mật khẩu.";
    } else {
        try {
            // 3.2. Truy vấn CSDL
            $sql = "SELECT * FROM NHANVIEN WHERE TenDangNhap = :ten_dang_nhap";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['ten_dang_nhap' => $ten_dang_nhap]);
            $admin = $stmt->fetch();
            
            // 3.3. Xác thực
            // password_verify() sẽ so sánh $mat_khau với chuỗi hash trong CSDL
            if ($admin && password_verify($mat_khau, $admin['MatKhau'])) {
                // Đăng nhập thành công!
                
                // 3.4. Lưu "vé vào cửa" (Session)
                $_SESSION['admin_id'] = $admin['MaNhanVien'];
                $_SESSION['admin_name'] = $admin['HoVaTen'];
                
                // 3.5. Chuyển hướng đến trang quản lý
                header("Location: quanly_sanpham.php");
                exit;
                
            } else {
                // Đăng nhập thất bại
                $error_message = "Tên đăng nhập hoặc mật khẩu không chính xác.";
            }

        } catch (\PDOException $e) {
            $error_message = "Lỗi CSDL: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Đăng nhập Quản trị</title>
    <link rel="stylesheet" href="style.css"> </head>
<body>
    <div class="login-container">
        <h1>Đăng nhập Trang Quản Trị</h1>

        <?php if (!empty($error_message)): ?>
            <div class="form-errors">
                <p><?php echo htmlspecialchars($error_message); ?></p>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="crud-form">
            <div class="form-group">
                <label for="ten_dang_nhap">Tên đăng nhập</label>
                <input type="text" id="ten_dang_nhap" name="ten_dang_nhap" required>
            </div>
            <div class="form-group">
                <label for="mat_khau">Mật khẩu</label>
                <input type="password" id="mat_khau" name="mat_khau" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-add">Đăng nhập</button>
            </div>
        </form>
    </div>
</body>
</html>