<?php
session_start();

$conn = new mysqli("localhost", "root", "", "nongsan");
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$thongbao = "";

$ds_phanquyen = $conn->query("SELECT idphanquyen, tenphanquyen FROM phanquyen");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tentk = trim($_POST['tentk']);
    $matkhau = md5($_POST['matkhau']);  // ✅ Mã hóa MD5 ở đây
    $email = trim($_POST['email']);
    $hoten = trim($_POST['hoten']);
    $diachi = trim($_POST['diachi']);
    $sdt = trim($_POST['sdt']);
    $idphanquyen = intval($_POST['idphanquyen']);

    $stmt = $conn->prepare("SELECT idtk FROM taikhoan WHERE tentk = ?");
    $stmt->bind_param("s", $tentk);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $thongbao = "Tên tài khoản đã tồn tại!";
    } else {
        $stmt = $conn->prepare("INSERT INTO taikhoan (tentk, matkhau, email, hoten, diachi, sdt, idphanquyen) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssi", $tentk, $matkhau, $email, $hoten, $diachi, $sdt, $idphanquyen);

        if ($stmt->execute()) {
            $thongbao = "Đăng ký thành công! <a href='dangnhap.php'>Đăng nhập</a>";
        } else {
            $thongbao = "Lỗi đăng ký: " . $stmt->error;
        }
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            font-family: 'Segoe UI', sans-serif;
        }
        .card {
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .form-title {
            color: #00796b;
        }
        .btn-primary {
            background-color: #00796b;
            border: none;
        }
        .btn-primary:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h3 class="text-center form-title mb-4">Đăng ký tài khoản</h3>

                <?php if ($thongbao): ?>
                    <div class="alert alert-info"><?php echo $thongbao; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" class="form-control" name="hoten" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" name="tentk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="matkhau" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" name="diachi" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" name="sdt" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phân quyền</label>
                        <select class="form-control" name="idphanquyen" required>
                            <option value="">-- Chọn phân quyền --</option>
                            <?php while ($row = $ds_phanquyen->fetch_assoc()): ?>
                                <option value="<?php echo $row['idphanquyen']; ?>">
                                    <?php echo htmlspecialchars($row['tenphanquyen']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                    <div class="text-center mt-3">
                        <a href="login.php">Đã có tài khoản? Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
