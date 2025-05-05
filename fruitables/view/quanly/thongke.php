<?php
session_start();

// Kiểm tra đăng nhập và phân quyền
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../../index.php");
    exit;
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "nongsan");
$conn->set_charset("utf8");

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thống kê số lượng sản phẩm theo loại
$sql_loai = "SELECT l.tenloai, COUNT(s.idsp) as soluong 
             FROM loaisanpham l 
             LEFT JOIN sanpham s ON l.idloai = s.idloai 
             GROUP BY l.idloai";
$result_loai = $conn->query($sql_loai);

// Thống kê số lượng sản phẩm theo nhà cung cấp
$sql_ncc = "SELECT n.tennhacungcap, COUNT(s.idsp) as soluong 
            FROM nhacungcap n 
            LEFT JOIN sanpham s ON n.idnhacungcap = s.idnhacungcap 
            GROUP BY n.idnhacungcap";
$result_ncc = $conn->query($sql_ncc);

// Thống kê sản phẩm bán chạy
$sql_banchay = "SELECT s.tensp, s.gia, COUNT(ct.idchitiet) as soluongban 
                FROM sanpham s 
                LEFT JOIN chitietdonhang ct ON s.idsp = ct.idsp 
                GROUP BY s.idsp 
                ORDER BY soluongban DESC 
                LIMIT 5";
$result_banchay = $conn->query($sql_banchay);

// Thống kê doanh thu theo tháng
$sql_doanhthu = "SELECT MONTH(dh.ngaydat) as thang, SUM(ct.soluong * ct.gia) as doanhthu 
                 FROM donhang dh 
                 JOIN chitietdonhang ct ON dh.iddonhang = ct.iddonhang 
                 GROUP BY MONTH(dh.ngaydat) 
                 ORDER BY thang";
$result_doanhthu = $conn->query($sql_doanhthu);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2>Thống kê sản phẩm</h2>
        
        <!-- Thống kê theo loại sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thống kê sản phẩm theo loại</h5>
            </div>
            <div class="card-body">
                <canvas id="chartLoai"></canvas>
            </div>
        </div>

        <!-- Thống kê theo nhà cung cấp -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thống kê sản phẩm theo nhà cung cấp</h5>
            </div>
            <div class="card-body">
                <canvas id="chartNCC"></canvas>
            </div>
        </div>

        <!-- Sản phẩm bán chạy -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Top 5 sản phẩm bán chạy</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng bán</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_banchay->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['tensp']; ?></td>
                                <td><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $row['soluongban']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Doanh thu theo tháng -->
        <div class="card">
            <div class="card-header">
                <h5>Doanh thu theo tháng</h5>
            </div>
            <div class="card-body">
                <canvas id="chartDoanhThu"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Biểu đồ thống kê theo loại
        const ctxLoai = document.getElementById('chartLoai').getContext('2d');
        new Chart(ctxLoai, {
            type: 'pie',
            data: {
                labels: [
                    <?php 
                    $result_loai->data_seek(0);
                    while ($row = $result_loai->fetch_assoc()) {
                        echo "'" . $row['tenloai'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    data: [
                        <?php 
                        $result_loai->data_seek(0);
                        while ($row = $result_loai->fetch_assoc()) {
                            echo $row['soluong'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ]
                }]
            }
        });

        // Biểu đồ thống kê theo nhà cung cấp
        const ctxNCC = document.getElementById('chartNCC').getContext('2d');
        new Chart(ctxNCC, {
            type: 'bar',
            data: {
                labels: [
                    <?php 
                    $result_ncc->data_seek(0);
                    while ($row = $result_ncc->fetch_assoc()) {
                        echo "'" . $row['tennhacungcap'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Số lượng sản phẩm',
                    data: [
                        <?php 
                        $result_ncc->data_seek(0);
                        while ($row = $result_ncc->fetch_assoc()) {
                            echo $row['soluong'] . ",";
                        }
                        ?>
                    ],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)'
                }]
            }
        });

        // Biểu đồ doanh thu theo tháng
        const ctxDoanhThu = document.getElementById('chartDoanhThu').getContext('2d');
        new Chart(ctxDoanhThu, {
            type: 'line',
            data: {
                labels: [
                    <?php 
                    $result_doanhthu->data_seek(0);
                    while ($row = $result_doanhthu->fetch_assoc()) {
                        echo "'Tháng " . $row['thang'] . "',";
                    }
                    ?>
                ],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [
                        <?php 
                        $result_doanhthu->data_seek(0);
                        while ($row = $result_doanhthu->fetch_assoc()) {
                            echo $row['doanhthu'] . ",";
                        }
                        ?>
                    ],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 