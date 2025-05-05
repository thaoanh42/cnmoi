<?php
session_start();

// Kiểm tra nếu có ID sản phẩm và số lượng
if (isset($_POST['id']) && isset($_POST['soluong'])) {
    $id = $_POST['id'];
    $soluong = $_POST['soluong'];

    // Kiểm tra nếu sản phẩm tồn tại trong giỏ hàng
    if (isset($_SESSION['giohang'][$id])) {
        // Cập nhật số lượng sản phẩm trong giỏ hàng
        $_SESSION['giohang'][$id]['soluong'] = $soluong;

        // Trả về thành công
        echo 'success';
        exit();
    } else {
        // Nếu không tìm thấy sản phẩm trong giỏ hàng
        echo 'Sản phẩm không có trong giỏ hàng!';
        exit();
    }
} else {
    echo 'Dữ liệu không hợp lệ!';
    exit();
}
?>
