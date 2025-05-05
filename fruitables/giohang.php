<?php
session_start();

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "nongsan");
$conn->set_charset("utf8");

// Kiểm tra có id sản phẩm cần thêm không
if (isset($_GET['add'])) {
    $idsp = intval($_GET['add']);

    // Lấy thông tin sản phẩm từ CSDL
    $query = "SELECT * FROM sanpham WHERE idsp = $idsp";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $sanpham = $result->fetch_assoc();

        // Nếu sản phẩm đã có trong giỏ, tăng số lượng
        if (isset($_SESSION['giohang'][$idsp])) {
            $_SESSION['giohang'][$idsp]['soluong'] += 1;
        } else {
            // Thêm mới vào giỏ
            $_SESSION['giohang'][$idsp] = [
                'tensp' => $sanpham['tensp'],
                'gia' => $sanpham['gia'],
                'anhsp' => $sanpham['anhsp'],
                'soluong' => 1
            ];
        }
    }
}

// Chuyển người dùng đến trang hiển thị giỏ hàng
header("Location: xemgiohang.php");
exit();
?>
