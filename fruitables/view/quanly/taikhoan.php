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

// Xử lý thêm tài khoản
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['them'])) {
    $tentk = $_POST['tentk'];
    $matkhau = md5($_POST['matkhau']);
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $idphanquyen = $_POST['idphanquyen'];
    
    $sql = "INSERT INTO taikhoan (tentk, matkhau, hoten, email, sdt, idphanquyen) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $tentk, $matkhau, $hoten, $email, $sdt, $idphanquyen);
    $stmt->execute();
}

// Xử lý xóa tài khoản
if (isset($_GET['xoa'])) {
    $id = $_GET['xoa'];
    $sql = "DELETE FROM taikhoan WHERE idtaikhoan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Lấy danh sách tài khoản
$sql = "SELECT t.*, p.tenphanquyen 
        FROM taikhoan t 
        JOIN phanquyen p ON t.idphanquyen = p.idphanquyen";
$result = $conn->query($sql);

// Lấy danh sách phân quyền
$pq_result = $conn->query("SELECT * FROM phanquyen");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Quản lý tài khoản</h2>
        
        <!-- Form thêm tài khoản -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thêm tài khoản mới</h5>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên tài khoản</label>
                            <input type="text" class="form-control" name="tentk" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="matkhau" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ tên</label>
                            <input type="text" class="form-control" name="hoten" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="sdt" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phân quyền</label>
                            <select class="form-select" name="idphanquyen" required>
                                <?php while ($pq = $pq_result->fetch_assoc()): ?>
                                    <option value="<?php echo $pq['idphanquyen']; ?>"><?php echo $pq['tenphanquyen']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="them" class="btn btn-primary">Thêm tài khoản</button>
                </form>
            </div>
        </div>

        <!-- Danh sách tài khoản -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách tài khoản</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên tài khoản</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Số điện thoại</th>
                                <th>Phân quyền</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['idtk']; ?></td>
                                <td><?php echo $row['tentk']; ?></td>
                                <td><?php echo $row['hoten']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['sdt']; ?></td>
                                <td><?php echo $row['tenphanquyen']; ?></td>
                                <td>
                                    <a href="sua_taikhoan.php?id=<?php echo $row['idtk']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="?xoa=<?php echo $row['idtk']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?')">
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