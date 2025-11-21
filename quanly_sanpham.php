<?php 
// 1. Gọi header (đã bao gồm auth và db)
// Đặt biến $title cho header
$title = "Quản lý Sản phẩm";
require_once 'admin_header.php'; 
?>

<title><?php echo $title; ?></title>

<?php
// 2. Logic PHP (giữ nguyên)
$sql = "SELECT sp.MaSanPham, sp.TenSanPham, sp.GiaThue, sp.TinhTrang, lp.TenLoai
        FROM SANPHAM AS sp
        JOIN LOAISP AS lp ON sp.MaLoai = lp.MaLoai
        ORDER BY sp.MaSanPham ASC";
$stmt = $pdo->query($sql);
$sanpham_list = $stmt->fetchAll();
?>

<div class="content-box">
    <h1><?php echo $title; ?></h1>
    
    <a href="them_sanpham.php" class="btn-add">Thêm Sản phẩm mới</a>

    <table>
        <thead>
            <tr>
                <th>Mã SP</th>
                <th>Tên Sản phẩm</th>
                <th>Loại Sản phẩm</th>
                <th>Giá Thuê</th>
                <th>Tình Trạng</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sanpham_list as $sanpham): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sanpham['MaSanPham']); ?></td>
                    <td><?php echo htmlspecialchars($sanpham['TenSanPham']); ?></td>
                    <td><?php echo htmlspecialchars($sanpham['TenLoai']); ?></td>
                    <td><?php echo number_format($sanpham['GiaThue'], 0, ',', '.'); ?> VNĐ</td>
                    <td><?php echo htmlspecialchars($sanpham['TinhTrang']); ?></td>
                    <td>
                        <a href="sua_sanpham.php?id=<?php echo $sanpham['MaSanPham']; ?>" class="btn-edit">Sửa</a>
                        <a href="xoa_sanpham.php?id=<?php echo $sanpham['MaSanPham']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
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