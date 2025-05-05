<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: view/login.php");
    exit;
}

// Kết nối CSDL
$conn = new mysqli("localhost", "root", "", "nongsan");
$conn->set_charset("utf8");
// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn danh sách sản phẩm
$sql = "SELECT * FROM sanpham";
$result = $conn->query($sql);

$loai_result = $conn->query("SELECT * FROM loaisanpham");
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>EcoFarm - Nông sản sạch, giao tận tay</title>
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
<style>
/* Điều chỉnh kích thước hình ảnh sản phẩm */
/* Điều chỉnh kích thước hình ảnh sản phẩm */
.fruite-img img {
    width: 100% !important;           /* Đảm bảo hình ảnh chiếm toàn bộ chiều rộng container */
    max-height: 180px !important;     /* Đặt chiều cao tối đa cho hình ảnh */
    height: auto !important;          /* Giữ tỷ lệ khung hình của ảnh */
    object-fit: cover !important;     /* Đảm bảo ảnh không bị kéo dãn và giữ chất lượng */
    margin: 0 auto !important;        /* Căn giữa hình ảnh trong container */
}

/* Điều chỉnh kích thước của khung sản phẩm */
.fruite-item {
    height: auto;                     /* Cho phép chiều cao tự động điều chỉnh theo nội dung */
    padding: 15px;                    /* Thêm khoảng cách xung quanh khung sản phẩm */
}

/* Điều chỉnh kích thước tiêu đề sản phẩm */
.fruite-item h4 {
    font-size: 1rem;                  /* Điều chỉnh kích thước font cho tên sản phẩm */
    margin-bottom: 10px;              /* Thêm khoảng cách giữa tiêu đề và mô tả */
}

/* Điều chỉnh mô tả sản phẩm */
.fruite-item .text-muted {
    font-size: 0.85rem;               /* Kích thước font nhỏ hơn cho mô tả sản phẩm */
}

/* Điều chỉnh kích thước font cho giá sản phẩm */
.fruite-item .fs-5 {
    font-size: 1rem;                  /* Giảm kích thước font một chút */
}

/* Đảm bảo các nút nằm ngang */
.fruite-item .d-flex {
    display: flex;              /* Sử dụng flexbox để căn chỉnh các phần tử */
    justify-content: space-between; /* Căn chỉnh các nút với khoảng cách đều */
    gap: 10px;                  /* Khoảng cách giữa các nút */
}

/* Điều chỉnh các nút có chiều rộng bằng nhau */
.fruite-item .btn {
    font-size: 0.9rem;           /* Giảm kích thước chữ một chút */
    padding: 10px 16px;          /* Điều chỉnh khoảng cách cho nút */
    width: 48%;                  /* Đảm bảo mỗi nút chiếm 48% chiều rộng */
    display: inline-flex;        /* Đảm bảo nút nằm ngang */
    justify-content: center;     /* Căn giữa văn bản trong nút */
}

/* Điều chỉnh căn giữa các nút khi màn hình lớn */
@media (min-width: 768px) {
    .fruite-item .d-flex {
        flex-direction: row;      /* Căn chỉnh các nút theo hàng ngang */
    }

    .fruite-item .btn {
        width: 48%;               /* Mỗi nút chiếm 48% chiều rộng */
    }
}

/* Trên màn hình nhỏ, các nút sẽ xếp thành cột */
@media (max-width: 767px) {
    .fruite-item .d-flex {
        flex-direction: column;   /* Chuyển các nút thành dạng cột */
        align-items: center;       /* Căn giữa các nút */
    }

    .fruite-item .btn {
        width: 100%;              /* Đảm bảo nút chiếm 100% chiều rộng */
        margin-bottom: 10px;      /* Thêm khoảng cách giữa các nút */
    }
}



/* Cấu trúc của khung sản phẩm */
.fruite-item {
    position: relative;
    overflow: hidden;              /* Đảm bảo không bị tràn ra ngoài */
    transition: transform 0.3s ease-in-out;  /* Thêm hiệu ứng chuyển động */
}

/* Khi di chuột vào, sản phẩm sẽ nhảy lên */
.fruite-item:hover {
    transform: translateY(-10px);  /* Di chuyển sản phẩm lên một chút */
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1); /* Thêm bóng mờ để tạo hiệu ứng nổi */
}

/* Điều chỉnh hình ảnh khi di chuột vào */
.fruite-img img {
    transition: transform 0.3s ease-in-out;  /* Thêm hiệu ứng chuyển động cho hình ảnh */
}

.fruite-item:hover .fruite-img img {
    transform: scale(1.05);  /* Phóng to hình ảnh một chút khi di chuột vào */
}
</style>
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
                            <a href="index.php" class="nav-item nav-link active">Trang chủ</a>
                            <a href="sanpham.php" class="nav-item nav-link">Sản phẩm</a>
                            <a href="contact.php" class="nav-item nav-link">Liên hệ</a>
                            
                            <?php if ($_SESSION['role'] == 'Quản trị viên') { ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Quản lý</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="view/quanly/sanpham.php" class="dropdown-item">Quản lý sản phẩm</a>
                                        <a href="view/quanly/taikhoan.php" class="dropdown-item">Quản lý tài khoản</a>
                                        <a href="view/quanly/thongke.php" class="dropdown-item">Thống kê sản phẩm</a>
                                        <a href="view/quanly/nhacungcap.php" class="dropdown-item">Quản lý đơn vị cung cấp</a>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <?php if ($_SESSION['role'] == 'Đơn vị cung cấp') { ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Đơn vị cung cấp</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="view/nhacungcap/sanpham.php" class="dropdown-item">Quản lý sản phẩm</a>
                                        <a href="view/nhacungcap/donhang.php" class="dropdown-item">Quản lý đơn hàng</a>
                                    </div>
                                </div>
                            <?php } ?>
                            
                            <?php if ($_SESSION['role'] == 'Khách hàng') { ?>
                                <div class="nav-item dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Khách hàng</a>
                                    <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                        <a href="view/khachhang/donhang.php" class="dropdown-item">Đơn hàng của tôi</a>
                                        <a href="view/khachhang/thongtin.php" class="dropdown-item">Thông tin tài khoản</a>
                                    </div>
                                </div>
                            <?php } ?>
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
                        <h5 class="modal-title" id="exampleModalLabel">Tìm kiếm bằng từ khóa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body d-flex align-items-center">
                        <div class="input-group w-75 mx-auto d-flex">
                            <input type="search" class="form-control p-3" placeholder="Nhập từ khóa tìm kiếm" aria-describedby="search-icon-1">
                            <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Search End -->


        <!-- Hero Start -->
        <div class="container-fluid py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row g-5 align-items-center">
                    <div class="col-md-12 col-lg-7">
                        <h4 class="mb-3 text-secondary">Nông sản sạch, giao tận tay </h4>
                        <h1 class="mb-5 display-3 text-primary">Rau củ quả hữu cơ & Thực phẩm</h1>
                        <!-- <div class="position-relative mx-auto">
                            <input class="form-control border-2 border-secondary w-75 py-3 px-4 rounded-pill" type="number" placeholder="Search">
                            <button type="submit" class="btn btn-primary border-2 border-secondary py-3 px-4 position-absolute rounded-pill text-white h-100" style="top: 0; right: 25%;">Submit Now</button>
                        </div> -->
                    </div>
                    <div class="col-md-12 col-lg-5">
                        <div id="carouselId" class="carousel slide position-relative" data-bs-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <div class="carousel-item active rounded">
                                    <img src="img/hero-img-1.png" class="img-fluid w-100 h-100 bg-secondary rounded" alt="First slide">
                                    <a href="#" class="btn px-4 py-2 text-white rounded">Trái cây</a>
                                </div>
                                <div class="carousel-item rounded">
                                    <img src="img/hero-img-2.jpg" class="img-fluid w-100 h-100 rounded" alt="Second slide">
                                    <a href="#" class="btn px-4 py-2 text-white rounded">Rau củ quả</a>
                                </div>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Hero End -->


        <!-- Featurs Section Start -->
        <div class="container-fluid featurs py-5">
            <div class="container py-5">
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-car-side fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Miễn phí vận chuyển</h5>
                                <p class="mb-0">Miễn phí đơn hàng trên 1 triệu </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-user-shield fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Thanh toán bảo mật</h5>
                                <p class="mb-0">100% thanh toán bảo mật</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fas fa-exchange-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Chính sách hoàn tiền</h5>
                                <p class="mb-0">Đảm bảo trong vòng 3 ngày</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="featurs-item text-center rounded bg-light p-4">
                            <div class="featurs-icon btn-square rounded-circle bg-secondary mb-5 mx-auto">
                                <i class="fa fa-phone-alt fa-3x text-white"></i>
                            </div>
                            <div class="featurs-content text-center">
                                <h5>Hỗ trợ 24/7</h5>
                                <p class="mb-0">Hỗ trợ nhanh chóng mọi lúc</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Featurs Section End -->


        <!-- Fruits Shop Start-->
        <div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-4 text-start">
                    <h1>Rau củ quả hữu cơ</h1>
                </div>
                <div class="col-lg-8 text-end">
                    <ul class="nav nav-pills d-inline-flex text-center mb-5">
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                                <span class="text-dark" style="width: 130px;">Tất cả</span>
                            </a>
                        </li>
                        <?php
                        // Reset lại con trỏ dữ liệu
                        $loai_result->data_seek(0);
                        while ($loai = $loai_result->fetch_assoc()):
                        ?>
                        <li class="nav-item">
                            <a class="d-flex m-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-<?php echo $loai['idloai']; ?>">
                                <span class="text-dark" style="width: 130px;"><?php echo $loai['tenloai']; ?></span>
                            </a>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane fade show p-0 active">
                            <div class="row g-4">
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                        <?php
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                     ?>
                                    <div class="col-md-6 col-lg-4 col-xl-3">
                                    <div class="rounded position-relative fruite-item shadow-sm">
                                        <div class="fruite-img overflow-hidden rounded-top">
                                        <div class="fruite-img">
                                    <img src="img/sanpham/<?php echo $row['anhsp']; ?>" class="img-fluid w-100" alt="">
                                        </div>
                                        </div>
                                        <div class="text-white bg-secondary px-3 py-1 rounded position-absolute" style="top: 10px; left: 10px; font-size: 0.85rem;">Nông sản</div>
                                        <div class="p-4 border border-secondary border-top-0 rounded-bottom">
                                            <h4 class="text-dark mb-3"><?php echo $row['tensp']; ?></h4> 
                                            <p class="text-dark fs-5 fw-bold mb-3 mb-lg-0"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VNĐ</p>
                                            <div class="d-flex justify-content-between align-items-center flex-column flex-lg-row">
                                            <div class="d-flex flex-column align-items-center">
    <a href="giohang.php?add=<?php echo $row['idsp']; ?>" 
       class="btn btn-outline-primary rounded-pill px-4 py-2 mb-2 w-100 text-center">
        <i class="fa fa-shopping-bag me-2"></i> Thêm vào giỏ
    </a>
    <a href="view/chitietsanpham.php?id=<?php echo $row['idsp']; ?>" 
       class="btn btn-outline-primary rounded-pill px-4 py-2 mb-2 w-100 text-center">
        Chi tiết
    </a>
</div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <?php
                                            }
                                        } else {
                                            echo "<p class='text-center'>Không có sản phẩm nào.</p>";
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $conn->close(); ?>
        <!-- Fruits Shop End-->


     

        <!-- Bestsaler Product Start -->
        <div class="container-fluid py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 700px;">
                    <h1 class="display-4">Bestseller Products</h1>
                    <p>Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable.</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-1.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-2.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-3.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-4.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-5.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-4">
                        <div class="p-4 rounded bg-light">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <img src="img/best-product-6.jpg" class="img-fluid rounded-circle w-100" alt="">
                                </div>
                                <div class="col-6">
                                    <a href="#" class="h5">Organic Tomato</a>
                                    <div class="d-flex my-3">
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star text-primary"></i>
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <h4 class="mb-3">3.12 $</h4>
                                    <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-1.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-2.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-3.jpg" class="img-fluid rounded" alt="">
                            <div class="py-4">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="text-center">
                            <img src="img/fruite-item-4.jpg" class="img-fluid rounded" alt="">
                            <div class="py-2">
                                <a href="#" class="h5">Organic Tomato</a>
                                <div class="d-flex my-3 justify-content-center">
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star text-primary"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <h4 class="mb-3">3.12 $</h4>
                                <a href="#" class="btn border border-secondary rounded-pill px-3 text-primary"><i class="fa fa-shopping-bag me-2 text-primary"></i> Add to cart</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bestsaler Product End -->

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

</html>