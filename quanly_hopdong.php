<?php 
$title = "Quản lý Hợp đồng";
require_once 'admin_header.php'; // Gọi header chung
?>

<title><?php echo $title; ?></title>

<?php
// Logic Lấy danh sách Hợp đồng
try {
    $sql = "SELECT hd.SoHD, kh.HoVaTen, hd.NgayLap, hd.NgayThue, hd.NgayTra, hd.TongTien, hd.TrangThai
            FROM HOPDONG AS hd
            JOIN KHACHHANG AS kh ON hd.MaKhachHang = kh.MaKhachHang
            ORDER BY hd.NgayLap DESC"; // Sắp xếp theo ngày mới nhất
            
    $stmt = $pdo->query($sql);
    $hopdong_list = $stmt->fetchAll();

} catch (\PDOException $e) {
    die("Lỗi CSDL: " . $e->getMessage());
}
?>

<div class="content-box">
    <h1><?php echo $title; ?></h1>
    
    <table>
        <thead>
            <tr>
                <th>Số HĐ</th>
                <th>Tên Khách hàng</th>
                <th>Ngày Lập</th>
                <th>Ngày Thuê</th>
                <th>Ngày Trả</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($hopdong_list)): ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Chưa có hợp đồng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($hopdong_list as $hd): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($hd['SoHD']); ?></td>
                        <td><?php echo htmlspecialchars($hd['HoVaTen']); ?></td>
                        <td><?php echo htmlspecialchars($hd['NgayLap']); ?></td>
                        <td><?php echo htmlspecialchars($hd['NgayThue']); ?></td>
                        <td><?php echo htmlspecialchars($hd['NgayTra']); ?></td>
                        <td><?php echo number_format($hd['TongTien'], 0, ',', '.'); ?> VNĐ</td>
                        
                        <td style="font-weight: bold; color: 
                            <?php 
                                if ($hd['TrangThai'] == 'pending') echo '#ffc107'; // Vàng
                                else if ($hd['TrangThai'] == 'confirmed') echo '#007bff'; // Xanh
                                else if ($hd['TrangThai'] == 'completed') echo '#28a745'; // Xanh lá
                                else if ($hd['TrangThai'] == 'cancelled') echo '#dc3545'; // Đỏ
                                else echo '#6c757d'; // Xám
                            ?>
                        ">
                            <?php echo htmlspecialchars($hd['TrangThai']); ?>
                        </td>
                        
                        <td>
                            <a href="chitiet_hopdong.php?id=<?php echo $hd['SoHD']; ?>" class="btn-edit">Xem/Sửa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php 
// 4. Đóng thẻ main, div container và body
?>
        </main> 
    </div> 
</body>
</html>