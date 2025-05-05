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

// Biến để lưu thông báo
$success_message = '';

// Xử lý thêm nhà cung cấp
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them'])) {
    // Kiểm tra sự tồn tại của các chỉ mục trước khi sử dụng
    $tendvcc = isset($_POST['tendvcc']) ? $_POST['tendvcc'] : '';
    $lienhe = isset($_POST['lienhe']) ? $_POST['lienhe'] : '';
    $diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    
    // Kiểm tra nếu có giá trị trống thì không thực hiện thêm
    if ($tendvcc && $diachi && $lienhe && $email) {
        $sql = "INSERT INTO donvicungcap (tendvcc, lienhe, diachi, email) 
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $tendvcc, $lienhe, $diachi, $email);
        $stmt->execute();
        
        // Thêm thành công, thông báo cho người dùng
        $success_message = 'Thêm đơn vị cung cấp thành công!';
    } else {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
    }
}

// Xử lý xóa nhà cung cấp
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $sql = "DELETE FROM donvicungcap WHERE iddvcc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy danh sách nhà cung cấp
$sql = "SELECT * FROM donvicungcap";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý đơn vị cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Quản lý đơn vị cung cấp</h2>

        <!-- Thêm nút quay lại -->
        <a href="../../index.php" class="btn btn-secondary mb-3">
    <i class="bi bi-arrow-left-circle"></i> Quay lại trang chủ
</a>


        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Form thêm nhà cung cấp -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thêm đơn vị cung cấp mới</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đơn vị cung cấp</label>
                            <input type="text" class="form-control" name="tendvcc" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="diachi" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="lienhe" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <button type="submit" name="them" class="btn btn-primary">Thêm đơn vị cung cấp</button>
                </form>
            </div>
        </div>

        <!-- Danh sách nhà cung cấp -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách đơn vị cung cấp</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên đơn vị cung cấp</th>
                                <th>Địa chỉ</th>
                                <th>Số điện thoại</th>
                                <th>Email</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['iddvcc']; ?></td>
                                <td><?php echo $row['tendvcc']; ?></td>
                                <td><?php echo $row['diachi']; ?></td>
                                <td><?php echo $row['lienhe']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <a href="suanhacungcap.php?id=<?php echo $row['iddvcc']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?xoa=<?php echo $row['iddvcc']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa đơn vị cung cấp này?')">
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
