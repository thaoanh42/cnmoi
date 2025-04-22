-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 22, 2025 lúc 04:43 AM
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
-- Cơ sở dữ liệu: `nongsan`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdonhang`
--

CREATE TABLE `chitietdonhang` (
  `idctdh` int(11) NOT NULL,
  `soluong` varchar(100) NOT NULL,
  `dongia` decimal(10,0) NOT NULL,
  `iddh` int(11) NOT NULL,
  `idsp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdonhang`
--

INSERT INTO `chitietdonhang` (`idctdh`, `soluong`, `dongia`, `iddh`, `idsp`) VALUES
(1, '2', 30000, 1, 1),
(2, '1', 18000, 1, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietgiohang`
--

CREATE TABLE `chitietgiohang` (
  `idctgh` int(11) NOT NULL,
  `idsp` int(11) NOT NULL,
  `soluong` float NOT NULL,
  `idgh` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietgiohang`
--

INSERT INTO `chitietgiohang` (`idctgh`, `idsp`, `soluong`, `idgh`) VALUES
(1, 1, 2, 1),
(2, 2, 1, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietsanpham`
--

CREATE TABLE `chitietsanpham` (
  `idctsp` int(11) NOT NULL,
  `donvitinh` varchar(100) NOT NULL,
  `mota` varchar(100) NOT NULL,
  `soluongton` float NOT NULL,
  `idsp` int(11) NOT NULL,
  `iddvcc` int(11) NOT NULL,
  `idloai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietsanpham`
--

INSERT INTO `chitietsanpham` (`idctsp`, `donvitinh`, `mota`, `soluongton`, `idsp`, `iddvcc`, `idloai`) VALUES
(1, 'kg', 'Rau cải xanh trồng theo phương pháp thủy canh', 100, 1, 1, 1),
(2, 'kg', 'Cà rốt không thuốc trừ sâu', 80, 2, 1, 2),
(3, 'kg', 'Táo hữu cơ Lâm Đồng', 50, 3, 2, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhgia`
--

CREATE TABLE `danhgia` (
  `iddg` int(11) NOT NULL,
  `idtk` int(11) NOT NULL,
  `iddh` int(11) NOT NULL,
  `noidung` varchar(255) NOT NULL,
  `ngaygui` date NOT NULL,
  `idsp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhgia`
--

INSERT INTO `danhgia` (`iddg`, `idtk`, `iddh`, `noidung`, `ngaygui`, `idsp`) VALUES
(1, 2, 1, 'Sản phẩm tươi ngon, sẽ ủng hộ tiếp!', '2025-04-04', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donhang`
--

CREATE TABLE `donhang` (
  `iddh` int(11) NOT NULL,
  `ngaydat` date NOT NULL,
  `tongtien` decimal(10,0) NOT NULL,
  `trangthaidh` enum('Đã đặt','Đã giao','Đã nhận') NOT NULL,
  `idtk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donhang`
--

INSERT INTO `donhang` (`iddh`, `ngaydat`, `tongtien`, `trangthaidh`, `idtk`) VALUES
(1, '2025-04-01', 48000, 'Đã đặt', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `donvicungcap`
--

CREATE TABLE `donvicungcap` (
  `iddvcc` int(11) NOT NULL,
  `tendvcc` varchar(100) NOT NULL,
  `lienhe` varchar(100) NOT NULL,
  `diachi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `donvicungcap`
--

INSERT INTO `donvicungcap` (`iddvcc`, `tendvcc`, `lienhe`, `diachi`) VALUES
(1, 'HTX Nông Nghiệp Xanh', '0901234567', 'Đà Lạt'),
(2, 'Trang trại An Bình', '0911222333', 'Củ Chi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giohang`
--

CREATE TABLE `giohang` (
  `idgh` int(11) NOT NULL,
  `trangthai` enum('Đã thanh toán','Chưa thanh toán') NOT NULL,
  `idtk` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giohang`
--

INSERT INTO `giohang` (`idgh`, `trangthai`, `idtk`) VALUES
(1, 'Chưa thanh toán', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaisanpham`
--

CREATE TABLE `loaisanpham` (
  `idloai` int(11) NOT NULL,
  `tenloai` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `loaisanpham`
--

INSERT INTO `loaisanpham` (`idloai`, `tenloai`) VALUES
(1, 'Rau xanh'),
(2, 'Củ quả'),
(3, 'Trái cây');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phanquyen`
--

CREATE TABLE `phanquyen` (
  `idphanquyen` int(11) NOT NULL,
  `tenphanquyen` varchar(100) NOT NULL,
  `ghichu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phanquyen`
--

INSERT INTO `phanquyen` (`idphanquyen`, `tenphanquyen`, `ghichu`) VALUES
(1, 'Quản trị viên', 'Toàn quyền quản trị'),
(2, 'Khách hàng', 'Khách mua hàng'),
(3, 'Đơn vị cung cấp', 'Đơn vị cung cấp sản phẩm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `idsp` int(11) NOT NULL,
  `tensp` varchar(100) NOT NULL,
  `gia` decimal(10,0) NOT NULL,
  `anhsp` varchar(255) NOT NULL,
  `idloai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`idsp`, `tensp`, `gia`, `anhsp`, `idloai`) VALUES
(1, 'Rau cải xanh', 15000, 'caixanh.png', 1),
(2, 'Cà rốt ', 18000, 'carot.png', 2),
(3, 'Táo đỏ', 30000, 'tao.jpg', 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `idtk` int(11) NOT NULL,
  `tentk` varchar(100) NOT NULL,
  `matkhau` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `hoten` varchar(50) NOT NULL,
  `diachi` varchar(100) NOT NULL,
  `sdt` varchar(50) NOT NULL,
  `idphanquyen` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`idtk`, `tentk`, `matkhau`, `email`, `hoten`, `diachi`, `sdt`, `idphanquyen`) VALUES
(1, 'thaoanh', '123456', 'anh@gmail.com', 'Vũ Thảo Ánh', 'Hà Nội', '0909123456', 1),
(2, 'kimnhan', '123456', 'nhan@gmail.com', 'Đặng Thị Kim Nhàn', 'Thành phố Hồ Chí Minh', '0909000111', 2),
(3, 'lan', '123456', 'lan@gmail.com', 'Nguyễn Thị Lan', 'Long An', '0909888777', 3);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD PRIMARY KEY (`idctdh`),
  ADD KEY `FK_CTDH_DH` (`iddh`),
  ADD KEY `FK_CTDH_SP` (`idsp`);

--
-- Chỉ mục cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD PRIMARY KEY (`idctgh`),
  ADD KEY `FK_CTGH_GH` (`idgh`),
  ADD KEY `FK_CTGH_SP` (`idsp`);

--
-- Chỉ mục cho bảng `chitietsanpham`
--
ALTER TABLE `chitietsanpham`
  ADD PRIMARY KEY (`idctsp`),
  ADD KEY `FK_CTSP_DVCC` (`iddvcc`),
  ADD KEY `FK_CTSP_L` (`idloai`),
  ADD KEY `FK_CTSP_SP` (`idsp`);

--
-- Chỉ mục cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD PRIMARY KEY (`iddg`),
  ADD KEY `FK_DG_DH` (`iddh`),
  ADD KEY `FK_DG_TK` (`idtk`),
  ADD KEY `FK_DG_SP` (`idsp`);

--
-- Chỉ mục cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD PRIMARY KEY (`iddh`),
  ADD KEY `FK_DH_TK` (`idtk`);

--
-- Chỉ mục cho bảng `donvicungcap`
--
ALTER TABLE `donvicungcap`
  ADD PRIMARY KEY (`iddvcc`);

--
-- Chỉ mục cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD PRIMARY KEY (`idgh`),
  ADD KEY `FK_GH_TK` (`idtk`);

--
-- Chỉ mục cho bảng `loaisanpham`
--
ALTER TABLE `loaisanpham`
  ADD PRIMARY KEY (`idloai`);

--
-- Chỉ mục cho bảng `phanquyen`
--
ALTER TABLE `phanquyen`
  ADD PRIMARY KEY (`idphanquyen`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`idsp`),
  ADD KEY `FK_SP_L` (`idloai`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`idtk`),
  ADD KEY `FK_TK_PQ` (`idphanquyen`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  MODIFY `idctdh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  MODIFY `idctgh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `chitietsanpham`
--
ALTER TABLE `chitietsanpham`
  MODIFY `idctsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  MODIFY `iddg` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `donhang`
--
ALTER TABLE `donhang`
  MODIFY `iddh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `donvicungcap`
--
ALTER TABLE `donvicungcap`
  MODIFY `iddvcc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `giohang`
--
ALTER TABLE `giohang`
  MODIFY `idgh` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `loaisanpham`
--
ALTER TABLE `loaisanpham`
  MODIFY `idloai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phanquyen`
--
ALTER TABLE `phanquyen`
  MODIFY `idphanquyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `idsp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  MODIFY `idtk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `chitietdonhang`
--
ALTER TABLE `chitietdonhang`
  ADD CONSTRAINT `FK_CTDH_DH` FOREIGN KEY (`iddh`) REFERENCES `donhang` (`iddh`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CTDH_SP` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chitietgiohang`
--
ALTER TABLE `chitietgiohang`
  ADD CONSTRAINT `FK_CTGH_GH` FOREIGN KEY (`idgh`) REFERENCES `giohang` (`idgh`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CTGH_SP` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chitietsanpham`
--
ALTER TABLE `chitietsanpham`
  ADD CONSTRAINT `FK_CTSP_DVCC` FOREIGN KEY (`iddvcc`) REFERENCES `donvicungcap` (`iddvcc`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CTSP_L` FOREIGN KEY (`idloai`) REFERENCES `loaisanpham` (`idloai`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_CTSP_SP` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `danhgia`
--
ALTER TABLE `danhgia`
  ADD CONSTRAINT `FK_DG_DH` FOREIGN KEY (`iddh`) REFERENCES `donhang` (`iddh`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DG_SP` FOREIGN KEY (`idsp`) REFERENCES `sanpham` (`idsp`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_DG_TK` FOREIGN KEY (`idtk`) REFERENCES `taikhoan` (`idtk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `donhang`
--
ALTER TABLE `donhang`
  ADD CONSTRAINT `FK_DH_TK` FOREIGN KEY (`idtk`) REFERENCES `taikhoan` (`idtk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `giohang`
--
ALTER TABLE `giohang`
  ADD CONSTRAINT `FK_GH_TK` FOREIGN KEY (`idtk`) REFERENCES `taikhoan` (`idtk`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `FK_SP_L` FOREIGN KEY (`idloai`) REFERENCES `loaisanpham` (`idloai`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD CONSTRAINT `FK_TK_PQ` FOREIGN KEY (`idphanquyen`) REFERENCES `phanquyen` (`idphanquyen`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
