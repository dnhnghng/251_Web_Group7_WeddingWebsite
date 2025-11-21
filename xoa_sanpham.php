<?php require_once 'auth_check.php'; ?>
<?php
// 1. Kết nối CSDL
require_once '../db_connect.php';

// 2. Lấy ID từ URL
$id = $_GET['id'] ?? null;

// 3. Kiểm tra xem ID có hợp lệ không
if (!$id || !is_numeric($id)) {
    die("ID sản phẩm không hợp lệ.");
}

try {
    // 4. Chuẩn bị và thực thi lệnh DELETE
    // Chúng ta dùng prepared statement để bảo vệ
    $sql = "DELETE FROM SANPHAM WHERE MaSanPham = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    $stmt->execute();
    
    // 5. Chuyển hướng về trang danh sách
    // Sau khi xóa thành công, quay về trang quanly_sanpham.php
    header("Location: quanly_sanpham.php");
    exit;

} catch (\PDOException $e) {
    // Xử lý nếu có lỗi
    // Ví dụ: Lỗi khóa ngoại (nếu sản phẩm đã có trong 1 hợp đồng)
    // Bạn có thể muốn hiển thị một thông báo lỗi thân thiện hơn
    die("Lỗi khi xóa sản phẩm: " . $e->getMessage());
}
?>