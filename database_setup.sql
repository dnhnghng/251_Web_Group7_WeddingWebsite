-- SQL Script for Wedding Rental Project (Version 2.1 - Fixed COMMENT Syntax)
-- Database: wedding_rental

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

--
-- 1. Cấu trúc bảng `LOAISP` (Loại Sản phẩm)
--
CREATE TABLE `LOAISP` (
  `MaLoai` int(11) NOT NULL AUTO_INCREMENT,
  `TenLoai` varchar(100) NOT NULL,
  PRIMARY KEY (`MaLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho `LOAISP`
INSERT INTO `LOAISP` (`MaLoai`, `TenLoai`) VALUES
(1, 'Váy cưới'),
(2, 'Vest chú rể'),
(3, 'Dụng cụ & Trang trí');

-- --------------------------------------------------------

--
-- 2. Cấu trúc bảng `SANPHAM` (Sản phẩm)
--
CREATE TABLE `SANPHAM` (
  `MaSanPham` int(11) NOT NULL AUTO_INCREMENT,
  `MaLoai` int(11) NOT NULL,
  `TenSanPham` varchar(255) NOT NULL,
  `GiaThue` decimal(10,2) NOT NULL,
  `TinhTrang` enum('available','rented','maintenance') NOT NULL DEFAULT 'available',
  `HinhAnh` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`MaSanPham`),
  KEY `FK_SANPHAM_LOAISP` (`MaLoai`),
  CONSTRAINT `FK_SANPHAM_LOAISP` FOREIGN KEY (`MaLoai`) REFERENCES `LOAISP` (`MaLoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho `SANPHAM`
INSERT INTO `SANPHAM` (`MaSanPham`, `MaLoai`, `TenSanPham`, `GiaThue`, `TinhTrang`, `HinhAnh`) VALUES
(1, 1, 'Váy cưới Công chúa A-line', 5000000.00, 'available', 'images/vay-cuoi-1.jpg'),
(2, 1, 'Váy cưới Đuôi cá Ren', 4500000.00, 'available', 'images/vay-cuoi-2.jpg'),
(3, 2, 'Vest đen Lịch lãm (Slim-fit)', 1500000.00, 'available', 'images/vest-1.jpg'),
(4, 3, 'Cổng hoa Lụa (Tông trắng hồng)', 3000000.00, 'maintenance', 'images/cong-hoa-1.jpg');

-- --------------------------------------------------------

--
-- 3. Cấu trúc bảng `KHACHHANG` (Khách hàng)
--
CREATE TABLE `KHACHHANG` (
  `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT,
  `HoVaTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `MatKhau` varchar(255) NOT NULL COMMENT 'Mật khẩu để khách đăng nhập',
  PRIMARY KEY (`MaKhachHang`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `SoDienThoai` (`SoDienThoai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho `KHACHHANG` (Mật khẩu: khach123)
INSERT INTO `KHACHHANG` (`MaKhachHang`, `HoVaTen`, `Email`, `SoDienThoai`, `DiaChi`, `MatKhau`) VALUES
(1, 'Nguyễn Văn A', 'khachhangA@gmail.com', '0901234567', '123 Đường ABC, Q1, TPHCM', '$2y$10$R/6.F.8.f.j/t.yU5.P4.o.e.U.a.z.K.u.P.e.Y.l.I.u.D.q.S.'),
(2, 'Trần Thị B', 'khachhangB@gmail.com', '0907654321', '456 Đường XYZ, Q.Tân Bình, TPHCM', '$2y$10$R/6.F.8.f.j/t.yU5.P4.o.e.U.a.z.K.u.P.e.Y.l.I.u.D.q.S.');

-- --------------------------------------------------------

--
-- 4. Cấu trúc bảng `NHANVIEN` (Nhân viên - Admin)
--
CREATE TABLE `NHANVIEN` (
  `MaNhanVien` int(11) NOT NULL AUTO_INCREMENT,
  `HoVaTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `TenDangNhap` varchar(50) NOT NULL,
  `MatKhau` varchar(255) NOT NULL COMMENT 'Mật khẩu để admin đăng nhập',
  `ChucVu` varchar(50) NOT NULL DEFAULT 'Quản trị viên',
  PRIMARY KEY (`MaNhanVien`),
  UNIQUE KEY `Email` (`Email`),
  UNIQUE KEY `TenDangNhap` (`TenDangNhap`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho `NHANVIEN` (Mật khẩu: admin123)
INSERT INTO `NHANVIEN` (`MaNhanVien`, `HoVaTen`, `Email`, `TenDangNhap`, `MatKhau`, `ChucVu`) VALUES
(1, 'Quản Trị Viên', 'admin@wedding.com', 'admin', '$2y$10$fWJ.g.3f6j/p.wK8.P9.peomv.f.R6.a.8iY3nE0Y2.cO4.2O6.S.', 'Quản trị viên');

-- --------------------------------------------------------

--
-- 5. Cấu trúc bảng `KHUYENMAI` (Khuyến mãi) -- *** LỖI ĐÃ SỬA ***
--
CREATE TABLE `KHUYENMAI` (
  `MaKhuyenMai` int(11) NOT NULL AUTO_INCREMENT,
  `MaCode` varchar(20) NOT NULL COMMENT 'Ví dụ: ''SALE10''',
  `GiaTri` decimal(5,2) NOT NULL COMMENT 'Nếu ''Loai'' là % thì 10.00, nếu là tiền mặt thì 100000',
  `Loai` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `HanSuDung` date DEFAULT NULL,
  PRIMARY KEY (`MaKhuyenMai`),
  UNIQUE KEY `MaCode` (`MaCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu cho `KHUYENMAI`
INSERT INTO `KHUYENMAI` (`MaKhuyenMai`, `MaCode`, `GiaTri`, `Loai`, `HanSuDung`) VALUES
(1, 'WELCOME10', 10.00, 'percentage', '2026-12-31');

-- --------------------------------------------------------

--
-- 6. Cấu trúc bảng `HOPDONG` (Hợp đồng thuê)
--
CREATE TABLE `HOPDONG` (
  `SoHD` int(11) NOT NULL AUTO_INCREMENT,
  `MaKhachHang` int(11) NOT NULL,
  `MaNhanVien` int(11) DEFAULT NULL COMMENT 'Nhân viên duyệt đơn',
  `MaKhuyenMai` int(11) DEFAULT NULL COMMENT 'Mã KM đã áp dụng',
  `NgayLap` datetime NOT NULL DEFAULT current_timestamp(),
  `NgayThue` date NOT NULL,
  `NgayTra` date NOT NULL,
  `TienCoc` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TongTien` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TrangThai` enum('pending','confirmed','renting','completed','cancelled') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`SoHD`),
  KEY `FK_HOPDONG_KHACHHANG` (`MaKhachHang`),
  KEY `FK_HOPDONG_NHANVIEN` (`MaNhanVien`),
  KEY `FK_HOPDONG_KHUYENMAI` (`MaKhuyenMai`),
  CONSTRAINT `FK_HOPDONG_KHACHHANG` FOREIGN KEY (`MaKhachHang`) REFERENCES `KHACHHANG` (`MaKhachHang`),
  CONSTRAINT `FK_HOPDONG_KHUYENMAI` FOREIGN KEY (`MaKhuyenMai`) REFERENCES `KHUYENMAI` (`MaKhuyenMai`),
  CONSTRAINT `FK_HOPDONG_NHANVIEN` FOREIGN KEY (`MaNhanVien`) REFERENCES `NHANVIEN` (`MaNhanVien`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 7. Cấu trúc bảng `HOPDONGCHITIET` (Chi tiết hợp đồng)
--
CREATE TABLE `HOPDONGCHITIET` (
  `MaHDChiTiet` int(11) NOT NULL AUTO_INCREMENT,
  `SoHD` int(11) NOT NULL,
  `MaSanPham` int(11) NOT NULL,
  `SoLuong` int(11) NOT NULL DEFAULT 1,
  `GiaLucThue` decimal(10,2) NOT NULL,
  PRIMARY KEY (`MaHDChiTiet`),
  KEY `FK_HDCHITIET_HOPDONG` (`SoHD`),
  KEY `FK_HDCHITIET_SANPHAM` (`MaSanPham`),
  CONSTRAINT `FK_HDCHITIET_HOPDONG` FOREIGN KEY (`SoHD`) REFERENCES `HOPDONG` (`SoHD`),
  CONSTRAINT `FK_HDCHITIET_SANPHAM` FOREIGN KEY (`MaSanPham`) REFERENCES `SANPHAM` (`MaSanPham`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


COMMIT;