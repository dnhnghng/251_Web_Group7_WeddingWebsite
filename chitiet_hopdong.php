<?php 
$title = "Chi tiết Hợp đồng";
require_once 'admin_header.php'; // Gọi header chung

$hopdong_id = $_GET['id'] ?? null;
if (!$hopdong_id || !is_numeric($hopdong_id)) {
    die("ID Hợp đồng không hợp lệ.");
}

// XỬ LÝ KHI ADMIN CẬP NHẬT (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_status = $_POST['trang_thai'];
    $ma_nhan_vien_duyet = $_SESSION['admin_id']; // Lấy ID admin đang đăng nhập
    
    try {
        $sql_update = "UPDATE HOPDONG 
                       SET TrangThai = :status, MaNhanVien = :ma_nv 
                       WHERE SoHD = :id";
        $stmt_update = $pdo->prepare($sql_update);
        $stmt_update->execute([
            'status' => $new_status,
            'ma_nv' => $ma_nhan_vien_duyet,
            'id' => $hopdong_id
        ]);
        
        // Chuyển về trang danh sách
        header("Location: quanly_hopdong.php");
        exit;
        
    } catch (\PDOException $e) {
        // --- ĐÂY LÀ DÒNG ĐÃ SỬA (XÓA CHỮ 's') ---
        die("Lỗi khi cập nhật: " . $e->getMessage());
    }
}


// LẤY DỮ LIỆU ĐỂ HIỂN THỊ (GET)
try {
    // Query 1: Lấy thông tin Hợp đồng và Khách hàng
    $sql_hd = "SELECT hd.*, kh.HoVaTen, kh.Email, kh.SoDienThoai
               FROM HOPDONG AS hd
               JOIN KHACHHANG AS kh ON hd.MaKhachHang = kh.MaKhachHang
               WHERE hd.SoHD = :id";
    $stmt_hd = $pdo->prepare($sql_hd);
    $stmt_hd->execute(['id' => $hopdong_id]);
    $hopdong = $stmt_hd->fetch();
    
    if (!$hopdong) {
        die("Không tìm thấy hợp đồng.");
    }
    
    // Query 2: Lấy danh sách sản phẩm trong hợp đồng
    $sql_sp = "SELECT sp.TenSanPham, hdc.SoLuong, hdc.GiaLucThue
               FROM HOPDONGCHITIET AS hdc
               JOIN SANPHAM AS sp ON hdc.MaSanPham = sp.MaSanPham
               WHERE hdc.SoHD = :id";
    $stmt_sp = $pdo->prepare($sql_sp);
    $stmt_sp->execute(['id' => $hopdong_id]);
    $sanpham_list = $stmt_sp->fetchAll();

} catch (\PDOException $e) {
    die("Lỗi CSDL: " . $e->getMessage());
}
?>

<title><?php echo $title; ?> #<?php echo $hopdong['SoHD']; ?></title>

<div class="content-box">
    
    <h2>Thông tin Khách hàng</h2>
    <p><strong>Số HĐ:</strong> #<?php echo htmlspecialchars($hopdong['SoHD']); ?></p>
    <p><strong>Tên Khách hàng:</strong> <?php echo htmlspecialchars($hopdong['HoVaTen']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($hopdong['Email']); ?></p>
    <p><strong>SĐT:</strong> <?php echo htmlspecialchars($hopdong['SoDienThoai']); ?></p>
    
    <hr>
    
    <h2>Thông tin Hợp đồng</h2>
    <p><strong>Ngày Thuê:</strong> <?php echo htmlspecialchars($hopdong['NgayThue']); ?></p>
    <p><strong>Ngày Trả:</strong> <?php echo htmlspecialchars($hopdong['NgayTra']); ?></p>
    <p><strong>Tổng Tiền:</strong> <?php echo number_format($hopdong['TongTien'], 0, ',', '.'); ?> VNĐ</p>
    
    <h3>Sản phẩm đã thuê:</h3>
    <table>
        <thead>
            <tr>
                <th>Tên Sản phẩm</th>
                <th>Số Lượng</th>
                <th>Giá lúc thuê</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sanpham_list as $sp): ?>
            <tr>
                <td><?php echo htmlspecialchars($sp['TenSanPham']); ?></td>
                <td><?Sửa" và Cập nhật trạng thái hợp đồng thành công, hãy báo cho tôi biết để chúng ta đi đến bước cuối cùng (Yêu cầu 4: Deploy web) nhé! echo htmlspecialchars($sp['SoLuong']); ?></td>
                <td><?php echo number_format($sp['GiaLucThue'], 0, ',', '.'); ?> VNĐ</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <hr>
    
    <h2>Cập nhật Trạng thái</h2>
    <form action="chitiet_hopdong.php?id=<?php echo $hopdong_id; ?>" method="POST" class="crud-form" style="max-width: 400px;">
        <div class="form-group">
            <label for="trang_thai">Trạng thái Hợp đồng</label>
            <select id="trang_thai" name="trang_thai">
                <option value="pending" <?php echo ($hopdong['TrangThai'] == 'pending') ? 'selected' : ''; ?>>Chờ xử lý (pending)</option>
                <option value="confirmed" <?php echo ($hopdong['TrangThai'] == 'confirmed') ? 'selected' : ''; ?>>Đã xác nhận (confirmed)</option>
                <option value="renting" <?php echo ($hopdong['TrangThai'] == 'renting') ? 'selected' : ''; ?>>Đang thuê (renting)</option>
                <option value="completed" <?php echo ($hopdong['TrangThai'] == 'completed') ? 'selected' : ''; ?>>Hoàn thành (completed)</option>
                <option value="cancelled" <?php echo ($hopdong['TrangThai'] == 'cancelled') ? 'selected' : ''; ?>>Đã hủy (cancelled)</option>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn-add">Cập nhật</button>
        </div>
    </form>

</div>

<?php 
// Đóng thẻ main, div container và body
?>
        </main> 
    </div> 
</body>
</html>