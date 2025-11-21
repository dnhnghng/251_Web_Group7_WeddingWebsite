-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 21, 2025 lúc 02:15 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `wedding_rental`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `khachhang`
--

CREATE TABLE `khachhang` (
  `MaKhachHang` int(11) NOT NULL,
  `HoVaTen` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `SoDienThoai` varchar(15) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL,
  `MatKhau` varchar(255) NOT NULL COMMENT 'Mật khẩu để khách đăng nhập'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `khachhang`
--

INSERT INTO `khachhang` (`MaKhachHang`, `HoVaTen`, `Email`, `SoDienThoai`, `DiaChi`, `MatKhau`) VALUES
(1, 'Nguyễn Văn A', 'khachhangA@gmail.com', '0901234567', '123 Đường ABC, Q1, TPHCM', '$2y$10$R/6.F.8.f.j/t.yU5.P4.o.e.U.a.z.K.u.P.e.Y.l.I.u.D.q.S.'),
(2, 'Trần Thị B', 'khachhangB@gmail.com', '0907654321', '456 Đường XYZ, Q.Tân Bình, TPHCM', '$2y$10$R/6.F.8.f.j/t.yU5.P4.o.e.U.a.z.K.u.P.e.Y.l.I.u.D.q.S.'),
(3, 'Nguyễn Đình Nghi', 'dinhnghingo2k6@gmail.com', '0787197657', '', '$2y$10$y0Rvdqn/W.YGuue.AtG5HO7A76572l1G/BEYxzYG0OHruDEu5ttZm'),
(4, 'Nguyen Nghi', 'nghi22@gmail.com', '07871976576', '123', '$2y$10$yrWkoXD3alM.f/9vvDKDy.X28Zopj42YGjBof/F5ooJRXO/mbx8L2');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  ADD PRIMARY KEY (`MaKhachHang`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `SoDienThoai` (`SoDienThoai`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `khachhang`
--
ALTER TABLE `khachhang`
  MODIFY `MaKhachHang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
