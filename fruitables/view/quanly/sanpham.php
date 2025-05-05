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

// Xử lý thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them'])) {
    $tensp = $_POST['tensp'];
    $gia = $_POST['gia'];
    $mota = $_POST['mota'];
    $idloai = $_POST['idloai'];
    $idnhacungcap = $_POST['idnhacungcap'];
    
    // Xử lý upload ảnh
    $target_dir = "../../img/sanpham/";
    $anhsp = basename($_FILES["anhsp"]["name"]);
    $target_file = $target_dir . $anhsp;
    move_uploaded_file($_FILES["anhsp"]["tmp_name"], $target_file);
    
    $sql = "INSERT INTO sanpham (tensp, gia, mota, anhsp, idloai, idnhacungcap) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdssii", $tensp, $gia, $mota, $anhsp, $idloai, $idnhacungcap);
    $stmt->execute();
}

// Xử lý duyệt sản phẩm
if (isset($_GET['duyet'])) {
    $id = $_GET['duyet'];
    $sql = "UPDATE sanpham SET trangthai = 1 WHERE idsp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Xử lý hủy duyệt sản phẩm
if (isset($_GET['huyduyet'])) {
    $id = $_GET['huyduyet'];
    $sql = "UPDATE sanpham SET trangthai = 0 WHERE idsp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Xử lý xóa sản phẩm
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $sql = "DELETE FROM sanpham WHERE idsp = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy danh sách sản phẩm
$sql = "SELECT s.*, l.tenloai, n.tennhacungcap 
        FROM sanpham s 
        JOIN loaisanpham l ON s.idloai = l.idloai 
        JOIN nhacungcap n ON s.idnhacungcap = n.idnhacungcap
        ORDER BY s.trangthai ASC, s.idsp DESC";
$result = $conn->query($sql);

// Lấy danh sách loại sản phẩm và nhà cung cấp
$loai_result = $conn->query("SELECT * FROM loaisanpham");
$ncc_result = $conn->query("SELECT * FROM nhacungcap");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Quản lý sản phẩm</h2>
        
        <!-- Form thêm sản phẩm -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thêm sản phẩm mới</h5>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="tensp" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá</label>
                            <input type="number" class="form-control" name="gia" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="mota" rows="3"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Loại sản phẩm</label>
                            <select class="form-select" name="idloai" required>
                                <?php while ($loai = $loai_result->fetch_assoc()): ?>
                                    <option value="<?php echo $loai['idloai']; ?>"><?php echo $loai['tenloai']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nhà cung cấp</label>
                            <select class="form-select" name="idnhacungcap" required>
                                <?php while ($ncc = $ncc_result->fetch_assoc()): ?>
                                    <option value="<?php echo $ncc['idnhacungcap']; ?>"><?php echo $ncc['tennhacungcap']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ảnh sản phẩm</label>
                        <input type="file" class="form-control" name="anhsp" required>
                    </div>
                    <button type="submit" name="them" class="btn btn-primary">Thêm sản phẩm</button>
                </form>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Loại</th>
                                <th>Nhà cung cấp</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idsp']; ?></td>
                                <td><img src="../../img/sanpham/<?php echo $row['anhsp']; ?>" width="50" height="50"></td>
                                <td><?php echo $row['tensp']; ?></td>
                                <td><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</td>
                                <td><?php echo $row['tenloai']; ?></td>
                                <td><?php echo $row['tennhacungcap']; ?></td>
                                <td>
                                    <?php if ($row['trangthai'] == 1): ?>
                                        <span class="badge bg-success">Đã duyệt</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Chờ duyệt</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['trangthai'] == 0): ?>
                                        <a href="?duyet=<?php echo $row['idsp']; ?>" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Duyệt
                                        </a>
                                    <?php else: ?>
                                        <a href="?huyduyet=<?php echo $row['idsp']; ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-x-lg"></i> Hủy duyệt
                                        </a>
                                    <?php endif; ?>
                                    <a href="sua_sanpham.php?id=<?php echo $row['idsp']; ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?xoa=<?php echo $row['idsp']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 