<?php 
$title = "Quản lý Lịch thuê";
require_once 'admin_header.php'; 
try {
    // JOIN 3 bảng: LICH_THUE, SANPHAM, HOPDONG
    $sql = "SELECT lt.*, sp.TenSanPham, hd.NgayThue, hd.NgayTra
            FROM lich_thue AS lt
            JOIN sanpham AS sp ON lt.MaSanPham = sp.MaSanPham
            JOIN hopdong AS hd ON lt.SoHD = hd.SoHD
            ORDER BY lt.NgayBatDau DESC"; 
            
    $stmt = $pdo->query($sql);
    $lichtue_list = $stmt->fetchAll();
} catch (\PDOException $e) { 
    // Gợi ý cho người dùng nếu chưa tạo bảng
    die("Lỗi CSDL: Bạn đã chạy lệnh CREATE TABLE cho bảng LICH_THUE chưa? Lỗi: " . $e->getMessage()); 
}
?>
<title><?php echo $title; ?></title>
<div class="content-box">
    <h1><?php echo $title; ?></h1>
    <p>Tổng quan về lịch đã khóa theo Hợp đồng.</p>
    <table>
        <thead>
            <tr>
                <th>Mã Lịch</th>
                <th>Mã SP</th>
                <th>Tên Sản phẩm</th>
                <th>Ngày Bắt đầu</th>
                <th>Ngày Kết thúc</th>
                <th>Số HĐ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lichtue_list)): ?>
                <tr><td colspan="6" style="text-align: center;">Chưa có lịch thuê nào được tạo.</td></tr>
            <?php else: ?>
                <?php foreach ($lichtue_list as $lt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lt['MaLich']); ?></td>
                        <td><?php echo htmlspecialchars($lt['MaSanPham']); ?></td>
                        <td><?php echo htmlspecialchars($lt['TenSanPham']); ?></td>
                        <td><?php echo htmlspecialchars($lt['NgayBatDau']); ?></td>
                        <td><?php echo htmlspecialchars($lt['NgayKetThuc']); ?></td>
                        <td><a href="chitiet_hopdong.php?id=<?php echo $lt['SoHD']; ?>">#<?php echo htmlspecialchars($lt['SoHD']); ?></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
        </main> 
    </div> 
</body>
</html>