<?php
/*
 * File kết nối CSDL (Sử dụng PDO - an toàn hơn)
 */

$servername = "localhost"; // Mặc định của XAMPP
$username = "root";        // Mặc định của XAMPP
$password = "";            // Mặc định của XAMPP
$dbname = "wedding_rental"; // Tên database bạn vừa tạo
$charset = "utf8mb4";

// Tạo chuỗi DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

// Các tùy chọn cho PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Trả về dạng mảng key-value
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Tạo đối tượng PDO để kết nối
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (\PDOException $e) {
    // Nếu kết nối thất bại, hiển thị lỗi
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Nếu file này được "include" thành công, biến $pdo sẽ có sẵn
// để bạn sử dụng cho mọi truy vấn SQL.
?>