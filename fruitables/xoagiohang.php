<?php
session_start();

// Kiểm tra xem có tồn tại giỏ hàng trong session hay không
if (isset($_SESSION['giohang']) && !empty($_SESSION['giohang'])) {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Kiểm tra xem sản phẩm có tồn tại trong giỏ hàng không
        if (isset($_SESSION['giohang'][$id])) {
            // Xóa sản phẩm khỏi giỏ hàng
            unset($_SESSION['giohang'][$id]);

            // Điều hướng lại về trang giỏ hàng
            header("Location: giohang.php");
            exit();
        }
    }
} else {
    echo "Giỏ hàng trống!";
}
?>
