<?php 
$title = "Quản lý Đơn vị Vận chuyển";
require_once 'admin_header.php'; 
try {
    $sql = "SELECT * FROM donvivanchuyen ORDER BY MaDVVC DESC"; 
    $stmt = $pdo->query($sql);
    $dvvc_list = $stmt->fetchAll();
} catch (\PDOException $e) { die("Lỗi CSDL: " . $e->getMessage()); }
?>
<title><?php echo $title; ?></title>
<div class="content-box">
    <h1><?php echo $title; ?></h1>
    <a href="#" class="btn-add">Thêm ĐV Vận chuyển</a>
    <table>
        <thead>
            <tr>
                <th>Mã ĐV</th>
                <th>Tên ĐV Vận chuyển</th>
                <th>Phí Ship Mặc định</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dvvc_list)): ?>
                <tr><td colspan="5" style="text-align: center;">Chưa có đơn vị vận chuyển nào.</td></tr>
            <?php else: ?>
                <?php foreach ($dvvc_list as $dvvc): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($dvvc['MaDVVC']); ?></td>
                        <td><?php echo htmlspecialchars($dvvc['TenDVVC']); ?></td>
                        <td><?php echo number_format($dvvc['PhiShipMacDinh'], 0, ',', '.'); ?> VNĐ</td>
                        <td><?php echo htmlspecialchars($dvvc['MoTa']); ?></td>
                        <td><a href="#" class="btn-edit">Sửa</a></td>
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