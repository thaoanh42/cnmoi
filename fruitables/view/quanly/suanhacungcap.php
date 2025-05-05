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

// Lấy ID của đơn vị cung cấp cần sửa
if (isset($_GET['id'])) {
    $iddvcc = $_GET['id'];
    
    // Lấy thông tin của đơn vị cung cấp
    $sql = "SELECT * FROM donvicungcap WHERE iddvcc = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $iddvcc);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Nếu không tìm thấy đơn vị cung cấp
    if (!$row) {
        echo "Không tìm thấy đơn vị cung cấp!";
        exit;
    }

    // Xử lý cập nhật thông tin
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['sua'])) {
        $tendvcc = isset($_POST['tendvcc']) ? $_POST['tendvcc'] : '';
        $lienhe = isset($_POST['lienhe']) ? $_POST['lienhe'] : '';
        $diachi = isset($_POST['diachi']) ? $_POST['diachi'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';

        // Kiểm tra nếu có giá trị trống thì không thực hiện cập nhật
        if ($tendvcc && $diachi && $lienhe && $email) {
            $sql_update = "UPDATE donvicungcap SET tendvcc = ?, lienhe = ?, diachi = ?, email = ? WHERE iddvcc = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssi", $tendvcc, $lienhe, $diachi, $email, $iddvcc);
            $stmt_update->execute();
            
            // Thêm thành công, thông báo cho người dùng
            $success_message = 'Cập nhật đơn vị cung cấp thành công!';
        } else {
            echo "<script>alert('Vui lòng điền đầy đủ thông tin!');</script>";
        }
    }
} else {
    echo "ID không hợp lệ!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa thông tin đơn vị cung cấp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Sửa thông tin đơn vị cung cấp</h2>
        
        <!-- Hiển thị thông báo thành công nếu có -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>
        
        <!-- Form sửa nhà cung cấp -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Sửa thông tin đơn vị cung cấp</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đơn vị cung cấp</label>
                            <input type="text" class="form-control" name="tendvcc" value="<?php echo $row['tendvcc']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <input type="text" class="form-control" name="diachi" value="<?php echo $row['diachi']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="lienhe" value="<?php echo $row['lienhe']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $row['email']; ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="sua" class="btn btn-primary">Cập nhật thông tin</button>
                </form>
            </div>
        </div>

        <!-- Nút quay lại với icon mũi tên -->
        <a href="nhacungcap.php" class="btn btn-secondary mt-3">
            <i class="bi bi-arrow-left-circle"></i> Quay lại
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
