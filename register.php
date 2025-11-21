<?php
// --- KHỐI LẤY DỮ LIỆU / XỬ LÝ ---
session_start();
require_once 'db_connect.php';

$errors = [];
$full_name = ''; $email = ''; $phone = ''; $address = '';

if (isset($_SESSION['customer_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (Toàn bộ logic xử lý POST giữ nguyên) ...
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (empty($full_name)) $errors[] = "Họ và tên là bắt buộc.";
    if (empty($email)) $errors[] = "Email là bắt buộc.";
    if (empty($phone)) $errors[] = "Số điện thoại là bắt buộc.";
    if (empty($password)) $errors[] = "Mật khẩu là bắt buộc.";
    if ($password != $password_confirm) $errors[] = "Mật khẩu xác nhận không khớp.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";

    if (empty($errors)) {
        try {
            $sql_check = "SELECT * FROM KHACHHANG WHERE Email = :email OR SoDienThoai = :phone LIMIT 1";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['email' => $email, 'phone' => $phone]);
            $existing_user = $stmt_check->fetch();

            if ($existing_user) {
                if ($existing_user['Email'] == $email) $errors[] = "Email này đã được sử dụng.";
                if ($existing_user['SoDienThoai'] == $phone) $errors[] = "Số điện thoại này đã được sử dụng.";
            }
        } catch (\PDOException $e) { $errors[] = "Lỗi CSDL: " . $e->getMessage(); }
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $sql_insert = "INSERT INTO KHACHHANG (HoVaTen, Email, SoDienThoai, DiaChi, MatKhau) 
                           VALUES (:full_name, :email, :phone, :address, :password)";
            $stmt_insert = $pdo->prepare($sql_insert);
            $stmt_insert->execute([
                'full_name' => $full_name, 'email' => $email, 'phone' => $phone,
                'address' => $address, 'password' => $hashed_password
            ]);
            
            $new_customer_id = $pdo->lastInsertId();
            
            $_SESSION['customer_id'] = $new_customer_id;
            $_SESSION['customer_name'] = $full_name;
            
            header("Location: index.php");
            exit;
        } catch (\PDOException $e) { $errors[] = "Lỗi khi đăng ký: " . $e->getMessage(); }
    }
}

// --- KHỐI ĐỊNH NGHĨA BIẾN CHO HEADER ---
$page_title = "Đăng ký tài khoản";

// CSS riêng cho trang này
$page_specific_css = '
    <style>
        .form-container {
            max-width: 600px; margin: 50px auto; background-color: #fff;
            padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-container h1 { text-align: center; border-bottom: none; margin-bottom: 20px; }
        .crud-form { margin: 0; padding: 0; background: none; border: none; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: bold; margin-bottom: 5px; }
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="tel"],
        .form-group input[type="password"] {
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
    <h1>Tạo tài khoản</h1>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
    <form action="register.php" method="POST" class="crud-form">
        <div class="form-group">
            <label for="full_name">Họ và Tên</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Số Điện Thoại</label>
            <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ (Tùy chọn)</label>
            <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
        </div>
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="password_confirm">Xác nhận mật khẩu</label>
            <input type="password" id="password_confirm" name="password_confirm" required>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-add">Đăng ký</button>
        </div>
        <div class="login-link">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
        </div>
    </form>
</div>

<?php 
// --- GỌI FOOTER ---
require_once 'footer.php'; 
?>