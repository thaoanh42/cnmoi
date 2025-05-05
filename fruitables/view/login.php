<?php
session_start();
include("../model/connect.php");
$connection = (new connect())->connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["tentk"];
    $password = $_POST["matkhau"];
    $password_md5 = md5($password);

    $sql = "SELECT taikhoan.*, phanquyen.tenphanquyen 
            FROM taikhoan 
            JOIN phanquyen ON taikhoan.idphanquyen = phanquyen.idphanquyen 
            WHERE taikhoan.tentk = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if ($password_md5 === $user["matkhau"]) {
            $_SESSION["username"] = $user["tentk"];
            $_SESSION["role"] = $user["tenphanquyen"];
            $_SESSION["hoten"] = $user["hoten"];
            header("Location: ../index.php");
            exit;
        } else {
            $error = "Sai mật khẩu.";
        }
    } else {
        $error = "Tài khoản không tồn tại.";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
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
        .remember-me {
            font-size: 14px;
            color: #555;
        }
        .bottom-links a {
            font-size: 14px;
            text-decoration: none;
            color: #00796b;
        }
        .bottom-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card p-4">
                <h3 class="text-center form-title mb-4">Đăng nhập</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" name="tentk" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="matkhau" required>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label remember-me" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                    <div class="bottom-links text-center mt-3">
                        <a href="dangky.php">Chưa có tài khoản? Đăng ký ngay</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
