<?php
session_start();

// Kiểm tra nếu giỏ hàng tồn tại trong session
if (!isset($_SESSION['giohang']) || empty($_SESSION['giohang'])) {
    echo "Giỏ hàng trống!";
    exit();
}

// Xử lý cập nhật số lượng sản phẩm trong giỏ hàng
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_POST['soluong'])) {
    $id = $_POST['id'];
    $soluong = $_POST['soluong'];

    // Kiểm tra sản phẩm có trong giỏ hàng không
    if (isset($_SESSION['giohang'][$id])) {
        // Cập nhật lại số lượng sản phẩm
        $_SESSION['giohang'][$id]['soluong'] = $soluong;
    }

    // Điều hướng lại trang
    header("Location: xemgiohang.php");
    exit();
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Kiểm tra sản phẩm có trong giỏ hàng không
    if (isset($_SESSION['giohang'][$id])) {
        // Xóa sản phẩm khỏi giỏ hàng
        unset($_SESSION['giohang'][$id]);
    }

    // Điều hướng lại trang
    header("Location: xemgiohang.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Fruitables - Vegetable Website Template</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style.css" rel="stylesheet">
    </head>

    <body>

        <!-- Spinner Start -->
        <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
            <div class="spinner-grow text-primary" role="status"></div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar start -->
        <div class="container-fluid fixed-top">
            <div class="container topbar bg-primary d-none d-lg-block">
                <div class="d-flex justify-content-between">
                    <div class="top-info ps-2">
                        <small class="me-3"><i class="fas fa-map-marker-alt me-2 text-secondary"></i> <a href="#" class="text-white">12 Nguyen Van Bao, TP.Ho Chi Minh</a></small>
                        <small class="me-3"><i class="fas fa-envelope me-2 text-secondary"></i><a href="#" class="text-white">ecofarm@gmail.com</a></small>
                    </div>
                    <div class="top-link pe-2">
                        <a href="#" class="text-white"><small class="text-white mx-2">Chính sách bảo mật</small>/</a>
                        <a href="#" class="text-white"><small class="text-white mx-2">Điều khoản sử dụng</small>/</a>
                        <a href="#" class="text-white"><small class="text-white ms-2">Bán hàng và hoàn tiền</small></a>
                    </div>
                </div>
            </div>
        <div class="container px-0">
                <nav class="navbar navbar-light bg-white navbar-expand-xl">
                    <a href="index.php" class="navbar-brand"><h1 class="text-primary display-6">EcoFarm</h1></a>
                    <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-white" id="navbarCollapse">
                        <div class="navbar-nav mx-auto">
                            <a href="index.php" class="nav-item nav-link">Trang Chủ</a>
                            <a href="sanpham.php" class="nav-item nav-link">Sản Phẩm</a>
                            <a href="contact.php" class="nav-item nav-link">Liên Hệ</a>
                        </div>
                        <div class="d-flex m-3 me-0">
                            <button class="btn-search btn border border-secondary btn-md-square rounded-circle bg-white me-4" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                            <a href="giohang.php" class="position-relative me-4 my-auto">
                                <i class="fa fa-shopping-bag fa-2x"></i>
                                <span class="position-absolute bg-secondary rounded-circle d-flex align-items-center justify-content-center text-dark px-1" style="top: -5px; left: 15px; height: 20px; min-width: 20px;">3</span>
                            </a>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle my-auto" data-bs-toggle="dropdown">
                                    <i class="fas fa-user fa-2x"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <span class="dropdown-item">Xin chào, <?php echo $_SESSION['hoten']; ?></span>
                                    <a href="view/dangxuat.php" class="dropdown-item">Đăng xuất</a>
                                </div>
                            </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->


        <!-- Modal Search Start -->
        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content rounded-0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Search by keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Single Page Header start -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Giỏ Hàng</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="#">Trang Chủ</a></li>
                <li class="breadcrumb-item active text-white">Giỏ Hàng</li>
            </ol>
        </div>
        <!-- Single Page Header End -->
        
        <!-- Cart Page Start -->
        <div class="container-fluid py-5">
    <div class="container py-5">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Sản Phẩm</th>
                        <th scope="col">Tên</th>
                        <th scope="col">Giá</th>
                        <th scope="col">Số Lượng</th>
                        <th scope="col">Tổng tiền</th>
                        <th scope="col">Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $tongtien = 0;
                    if (isset($_SESSION['giohang']) && !empty($_SESSION['giohang'])) {
                        foreach ($_SESSION['giohang'] as $id => $sp) {
                            $thanhtien = $sp['gia'] * $sp['soluong'];
                            $tongtien += $thanhtien;
                            echo "<tr>
                                <th scope=\"row\">
                                    <div class='d-flex align-items-center'>
                                        <img src='img/sanpham/{$sp['anhsp']}' class='img-fluid me-5 rounded-circle' style='width: 80px; height: 80px;' alt=''>
                                    </div>
                                </th>
                                <td><p class='mb-0 mt-4'>{$sp['tensp']}</p></td>
                                <td><p class='mb-0 mt-4'>" . number_format($sp['gia'], 0, ',', '.') . " VNĐ</p></td>
                                <td>
                                    <div class='input-group quantity mt-4' style='width: 100px;'>
                                        <form action='suagiohang.php' method='POST'>
                                            <input type='hidden' name='id' value='$id'>
                                            <input type='number' name='soluong' min='1' value='{$sp['soluong']}' class='form-control form-control-sm text-center border-0'>
                                            <button type='submit' class='btn btn-sm btn-success mt-2'>Cập nhật</button>
                                        </form>
                                    </div>
                                </td>
                                <td><p class='mb-0 mt-4'>" . number_format($thanhtien, 0, ',', '.') . " VNĐ</p></td>
                                <td>
                                    <a href='xoasp.php?id=$id' class='btn btn-md rounded-circle bg-light border mt-4'>
                                        <i class='fa fa-times text-danger'></i>
                                    </a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Giỏ hàng trống</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row g-4 justify-content-end">
            <div class="col-8"></div>
            <div class="col-sm-8 col-md-7 col-lg-6 col-xl-4">
                <div class="bg-light rounded">
                    <div class="p-4">
                        <h1 class="display-6 mb-4">Tổng <span class="fw-normal">giỏ hàng</span></h1>
                        <div class="d-flex justify-content-between mb-4">
                            <h5 class="mb-0 me-4">Tạm tính:</h5>
                            <p class="mb-0"><?php echo number_format($tongtien, 0, ',', '.'); ?> VNĐ</p>
                        </div>
                        <div class="py-4 mb-4 border-top border-bottom d-flex justify-content-between">
                            <h5 class="mb-0 ps-4 me-4">Thành tiền</h5>
                            <p class="mb-0 pe-4"><?php echo number_format($tongtien, 0, ',', '.'); ?> VNĐ</p>
                        </div>
                        <a href="checkout.php" class="btn border-secondary rounded-pill px-4 py-3 text-primary text-uppercase mb-4 ms-4">Thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


       <!-- Footer Start -->
       <div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
            <div class="container py-5">
                <div class="pb-4 mb-4" style="border-bottom: 1px solid rgba(226, 175, 24, 0.5) ;">
                    <div class="row g-4">
                        <div class="col-lg-3">
                            <a href="#">
                                <h1 class="text-primary mb-0">Ecofarm</h1>
                                <p class="text-secondary mb-0">Nông sản sạch</p>
                            </a>
                        </div>
                        <div class="col-lg-6">
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 w-100 py-3 px-4 rounded-pill" type="number" placeholder="Nhập Email">
                                <button type="submit" class="btn btn-primary border-0 border-secondary py-3 px-4 position-absolute rounded-pill text-white" style="top: 0; right: 0;">Đăng ký ngay</button>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="d-flex justify-content-end pt-3">
                                <a class="btn  btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-outline-secondary me-2 btn-md-square rounded-circle" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-outline-secondary btn-md-square rounded-circle" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Tại sao nên chọn EcoFarm!</h4>
                            <p class="mb-4">cung cấp nông sản sạch, an toàn và thân thiện với môi trường. Chúng tôi kết nối trực tiếp từ trang trại đến tay người tiêu dùng, cam kết sản phẩm không hóa chất độc hại</p>
                            <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Đọc thêm</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Thông tin cửa hàng</h4>
                            <a class="btn-link" href="">Về chúng tôi</a>
                            <a class="btn-link" href="">Liên hệ</a>
                            <a class="btn-link" href="">Chính sách bảo mật</a>
                            <a class="btn-link" href="">Điều khoản & Điều kiện</a>
                            <a class="btn-link" href="">Chính sách đổi trả</a>
                            <a class="btn-link" href="">Trợ giúp</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="d-flex flex-column text-start footer-item">
                            <h4 class="text-light mb-3">Tài khoản</h4>
                            <a class="btn-link" href="">Tài khoản của tôi</a>
                            <a class="btn-link" href="">Giỏ hàng</a>
                            <a class="btn-link" href="">Danh sách yêu thích</a>
                            <a class="btn-link" href="">Lịch sử đặt hàng</a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-item">
                            <h4 class="text-light mb-3">Liên hệ</h4>
                            <p>địa chỉ: 12 Nguyễn Văn Bảo, TP.Hồ Chí Minh</p>
                            <p>Email: ecofarm@gmail.com</p>
                            <p>Phone: +0123 4567 8910</p>
                            <p>Các hình thức thanh toán</p>
                            <img src="img/payment.png" class="img-fluid" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Copyright Start -->
        <div class="container-fluid copyright bg-dark py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        <span class="text-light"><a href="#"><i class="fas fa-copyright text-light me-2"></i>EcoFarm</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-6 my-auto text-center text-md-end text-white">
                        Designed By <a class="border-bottom" href="Kim Nhan">Kim Nhan</a> & <a class="border-bottom" href="https://themewagon.com">Thao Anh</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->



        <!-- Back to Top -->
        <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    </body>