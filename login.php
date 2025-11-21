<?php
// --- KHỐI LẤY DỮ LIỆU / XỬ LÝ ---
session_start();
require_once 'db_connect.php';

$errors = [];
$email = '';

if (isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $errors[] = "Vui lòng nhập Email và Mật khẩu.";
    } else {
        try {
            $sql = "SELECT * FROM KHACHHANG WHERE Email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['email' => $email]);
            $customer = $stmt->fetch();
            
            if ($customer && password_verify($password, $customer['MatKhau'])) {
                $_SESSION['customer_id'] = $customer['MaKhachHang'];
                $_SESSION['customer_name'] = $customer['HoVaTen'];
                
                // (Nâng cấp) Chuyển hướng thông minh
                $redirect_to = $_SESSION['redirect_to'] ?? 'index.php';
                unset($_SESSION['redirect_to']); // Xóa link đã lưu
                header("Location: $redirect_to");
                exit;
            } else {
                $errors[] = "Email hoặc mật khẩu không chính xác.";
            }
        } catch (\PDOException $e) { $errors[] = "Lỗi CSDL: " . $e->getMessage(); }
    }
}

// --- KHỐI ĐỊNH NGHĨA BIẾN CHO HEADER ---
$page_title = "Đăng nhập";

// CSS riêng (Dùng chung với register.php)
$page_specific_css = '
    <style>
        .form-container { max-width: 600px; margin: 50px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-container h1 { text-align: center; border-bottom: none; margin-bottom: 20px; }
        .crud-form { margin: 0; padding: 0; background: none; border: none; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"], .form-group input[type="email"], .form-group input[type="password"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;
        }
        .form-actions { margin-top: 20px; }
        .btn-add { width: 100%; padding: 12px; }
        .form-errors {
            background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
            padding: 10px 20px; border-radius: 4px; margin-bottom: 20px;
        }
        .form-errors p { margin: 5px 0; }
        .login-link { text-align: center; margin-top: 20px; }
    </style>
';

// --- GỌI HEADER ---
require_once 'header.php';
?>

<div class="form-container">
    <h1>Đăng nhập tài khoản</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="login.php" method="POST" class="crud-form">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-add">Đăng nhập</button>
        </div>
        <div class="login-link">
            <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </div>
    </form>
</div>

<?php 
// --- GỌI FOOTER ---
require_once 'footer.php'; 
?>