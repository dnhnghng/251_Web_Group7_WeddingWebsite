<?php 
$title = "Quản lý Nhà cung cấp";
require_once 'admin_header.php'; 
try {
    $sql = "SELECT * FROM nhacungcap ORDER BY MaNCC DESC"; 
    $stmt = $pdo->query($sql);
    $ncc_list = $stmt->fetchAll();
} catch (\PDOException $e) { die("Lỗi CSDL: " . $e->getMessage()); }
?>
<title><?php echo $title; ?></title>
<div class="content-box">
    <h1><?php echo $title; ?></h1>
    <a href="#" class="btn-add">Thêm NCC</a>
    <table>
        <thead>
            <tr>
                <th>Mã NCC</th>
                <th>Tên NCC</th>
                <th>Địa chỉ</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($ncc_list)): ?>
                <tr><td colspan="6" style="text-align: center;">Chưa có nhà cung cấp nào.</td></tr>
            <?php else: ?>
                <?php foreach ($ncc_list as $ncc): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ncc['MaNCC']); ?></td>
                        <td><?php echo htmlspecialchars($ncc['TenNCC']); ?></td>
                        <td><?php echo htmlspecialchars($ncc['DiaChi']); ?></td>
                        <td><?php echo htmlspecialchars($ncc['Email']); ?></td>
                        <td><?php echo htmlspecialchars($ncc['SoDienThoai']); ?></td>
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