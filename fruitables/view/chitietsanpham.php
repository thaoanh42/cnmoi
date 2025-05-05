<?php
include("../model/connect.php");
$database = new Connect();
$conn = $database->connectDB();

if (isset($_GET['id'])) {
    $idsp = intval($_GET['id']);

    $sql = "SELECT 
                sp.tensp, sp.gia, sp.anhsp,
                ctsp.donvitinh, ctsp.mota, ctsp.soluongton,
                lo.tenloai,
                dvcc.tendvcc
            FROM chitietsanpham ctsp
            JOIN sanpham sp ON ctsp.idsp = sp.idsp
            JOIN loaisanpham lo ON ctsp.idloai = lo.idloai
            JOIN donvicungcap dvcc ON ctsp.iddvcc = dvcc.iddvcc
            WHERE sp.idsp = $idsp";

    $result = $conn->query($sql);

    if (!$result) {
        die("<p class='text-danger text-center mt-5'>Lỗi truy vấn SQL: " . $conn->error . "</p>");
    }

    if ($result->num_rows > 0) {
        $sp = $result->fetch_assoc();
    } else {
        echo "<p class='text-center mt-5'>Sản phẩm không tồn tại.</p>";
        exit();
    }
} else {
    echo "<p class='text-center mt-5'>Không có sản phẩm được chọn.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($sp['tensp']) ?> - Chi tiết sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .product-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
            padding: 30px;
        }
        .product-card h2 {
            font-weight: bold;
        }
        .product-card p {
            font-size: 1.05rem;
            margin-bottom: 10px;
        }
        .product-card .btn {
            padding: 10px 20px;
            font-size: 1.1rem;
            border-radius: 12px;
        }
        .product-image {
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10 product-card">
                <div class="row g-5">
                    <div class="col-md-5">
                        <img src="../img/sanpham/<?= htmlspecialchars($sp['anhsp']) ?>" alt="<?= htmlspecialchars($sp['tensp']) ?>" class="img-fluid product-image">
                    </div>
                    <div class="col-md-7">
                        <h2 class="mb-3"><?= htmlspecialchars($sp['tensp']) ?></h2>
                        <p><strong>Giá:</strong> <span class="text-danger"><?= number_format($sp['gia'], 0, ',', '.') ?> VNĐ</span></p>
                        <p><strong>Loại sản phẩm:</strong> <?= htmlspecialchars($sp['tenloai']) ?></p>
                        <p><strong>Nhà cung cấp:</strong> <?= htmlspecialchars($sp['tendvcc']) ?></p>
                        <p><strong>Đơn vị tính:</strong> <?= htmlspecialchars($sp['donvitinh']) ?></p>
                        <p><strong>Số lượng tồn:</strong> <?= htmlspecialchars($sp['soluongton']) ?></p>
                        <p><strong>Mô tả:</strong><br><?= nl2br(htmlspecialchars($sp['mota'])) ?></p>
                        <div class="d-grid gap-2 d-md-block">
                        <a href="../giohang.php?add=<?= $idsp ?>" class="btn btn-primary me-2 px-4">
                            <i class="fa fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                        </a>
                        <a href="../index.php" class="btn btn-secondary px-4">
                            <i class="fa fa-arrow-left me-2"></i>Quay lại trang chủ
                        </a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
