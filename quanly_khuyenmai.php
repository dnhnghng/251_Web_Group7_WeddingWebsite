<?php 
$title = "Quản lý Khuyến mãi";
require_once 'admin_header.php'; // Gọi header chung
?>
<title><?php echo $title; ?></title>
<?php
try {
    // Lấy tất cả mã khuyến mãi (SỬ DỤNG TÊN BẢNG CHỮ THƯỜNG)
    $sql = "SELECT * FROM khuyenmai ORDER BY MaKhuyenMai DESC"; 
    $stmt = $pdo->query($sql);
    $khuyenmai_list = $stmt->fetchAll();

} catch (\PDOException $e) {
    die("Lỗi CSDL: " . $e->getMessage());
}
?>

<div class="content-box">
    <h1><?php echo $title; ?></h1>
    
    <a href="them_khuyenmai.php" class="btn-add">Thêm Mã khuyến mãi</a>
    <p style="margin-top: 10px; font-size: 0.9rem; color: #555;">Tổng số mã: <?php echo count($khuyenmai_list); ?></p>
    
    <table>
        <thead>
            <tr>
                <th>Mã KM</th>
                <th>Mã Code</th>
                <th>Giá Trị</th>
                <th>Loại</th>
                <th>Hạn Sử Dụng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($khuyenmai_list)): ?>
                <tr><td colspan="7" style="text-align: center;">Chưa có mã khuyến mãi nào.</td></tr>
            <?php else: ?>
                <?php foreach ($khuyenmai_list as $km): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($km['MaKhuyenMai']); ?></td>
                        <td><strong><?php echo htmlspecialchars($km['MaCode']); ?></strong></td>
                        <td><?php echo ($km['Loai'] == 'percentage') ? htmlspecialchars($km['GiaTri']) . ' %' : number_format($km['GiaTri'], 0, ',', '.') . ' VNĐ'; ?></td>
                        <td><?php echo ($km['Loai'] == 'percentage') ? 'Phần trăm' : 'Tiền mặt'; ?></td>
                        <td><?php echo htmlspecialchars($km['HanSuDung']); ?></td>
                        
                        <td><?php echo (strtotime($km['HanSuDung']) >= time()) ? '<span style="color:green; font-weight: bold;">Còn hạn</span>' : '<span style="color:red; font-weight: bold;">Hết hạn</span>'; ?></td>
                        
                        <td>
                            <a href="sua_khuyenmai.php?id=<?php echo $km['MaKhuyenMai']; ?>" class="btn-edit">Sửa</a>
                            <a href="xoa_khuyenmai.php?id=<?php echo $km['MaKhuyenMai']; ?>" class="btn-delete" onclick="return confirm('Xóa mã khuyến mãi?');">Xóa</a>
                        </td>
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