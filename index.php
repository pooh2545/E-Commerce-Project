<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="assets/images/ico.jpg">
    <title>ร้านค้าออนไลน์</title>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap/bootstrap.min.css" rel="stylesheet"> 
    <!--<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-card {
            transition: transform 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.12);
        }
        .carousel-item img {
            height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/api/placeholder/1200/400" class="d-block w-100" alt="โปรโมชั่น">
                <div class="carousel-caption d-none d-md-block">
                    <h2>โปรโมชั่นพิเศษประจำเดือน</h2>
                    <p>ลดสูงสุด 50% เฉพาะสินค้าที่ร่วมรายการ</p>
                    <a href="#" class="btn btn-primary">ดูเพิ่มเติม</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/api/placeholder/1200/400" class="d-block w-100" alt="สินค้าใหม่">
                <div class="carousel-caption d-none d-md-block">
                    <h2>สินค้าใหม่มาแรง</h2>
                    <p>อัพเดทสินค้าใหม่ประจำสัปดาห์</p>
                    <a href="#" class="btn btn-primary">ช้อปเลย</a>
                </div>
            </div>
            <div class="carousel-item">
                <img src="/api/placeholder/1200/400" class="d-block w-100" alt="ส่งฟรี">
                <div class="carousel-caption d-none d-md-block">
                    <h2>ส่งฟรีทั่วประเทศ</h2>
                    <p>เมื่อสั่งซื้อสินค้าครบ 1,000 บาท</p>
                    <a href="#" class="btn btn-primary">เงื่อนไข</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Categories -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">หมวดหมู่สินค้า</h2>
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-tshirt fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">เสื้อผ้า</h5>
                            <p class="card-text text-muted">แฟชั่นล่าสุด</p>
                            <a href="#" class="btn btn-outline-primary">ดูสินค้า</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-mobile-alt fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">อิเล็กทรอนิกส์</h5>
                            <p class="card-text text-muted">สินค้าไฮเทค</p>
                            <a href="#" class="btn btn-outline-primary">ดูสินค้า</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-home fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">เครื่องใช้ในบ้าน</h5>
                            <p class="card-text text-muted">สไตล์ทันสมัย</p>
                            <a href="#" class="btn btn-outline-primary">ดูสินค้า</a>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="fas fa-gift fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">ของขวัญ</h5>
                            <p class="card-text text-muted">โอกาสพิเศษ</p>
                            <a href="#" class="btn btn-outline-primary">ดูสินค้า</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>สินค้าแนะนำ</h2>
                <a href="#" class="btn btn-link text-decoration-none">ดูทั้งหมด <i class="fas fa-arrow-right ms-1"></i></a>
            </div>
            <div class="row g-4">
                <?php
                // ในไฟล์จริงคุณจะดึงข้อมูลจากฐานข้อมูล แต่นี่เป็นตัวอย่างแบบ static
                $featuredProducts = [
                    [
                        'id' => 1,
                        'name' => 'เสื้อยืดคอกลม',
                        'price' => 350,
                        'image' => '/api/placeholder/300/300',
                        'discount' => 0
                    ],
                    [
                        'id' => 2,
                        'name' => 'กางเกงยีนส์ทรงสลิม',
                        'price' => 1290,
                        'image' => '/api/placeholder/300/300',
                        'discount' => 20
                    ],
                    [
                        'id' => 3,
                        'name' => 'รองเท้าผ้าใบสีขาว',
                        'price' => 1590,
                        'image' => '/api/placeholder/300/300',
                        'discount' => 15
                    ],
                    [
                        'id' => 4,
                        'name' => 'กระเป๋าสะพายข้าง',
                        'price' => 890,
                        'image' => '/api/placeholder/300/300',
                        'discount' => 0
                    ],
                ];

                foreach ($featuredProducts as $product) {
                    $discountPrice = $product['price'] - ($product['price'] * $product['discount'] / 100);
                    ?>
                    <div class="col-6 col-md-3">
                        <div class="card product-card h-100">
                            <?php if ($product['discount'] > 0): ?>
                                <div class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded-pill">
                                    -<?php echo $product['discount']; ?>%
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo $product['image']; ?>" class="card-img-top" alt="<?php echo $product['name']; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $product['name']; ?></h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <?php if ($product['discount'] > 0): ?>
                                        <div>
                                            <span class="text-danger fw-bold"><?php echo number_format($discountPrice); ?> บาท</span>
                                            <small class="text-muted text-decoration-line-through d-block"><?php echo number_format($product['price']); ?> บาท</small>
                                        </div>
                                    <?php else: ?>
                                        <span class="fw-bold"><?php echo number_format($product['price']); ?> บาท</span>
                                    <?php endif; ?>
                                    <div class="d-flex">
                                        <button class="btn btn-sm btn-outline-secondary me-1">
                                            <i class="far fa-heart"></i>
                                        </button>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="fas fa-shopping-cart me-1"></i>ซื้อ
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Promotions -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">โปรโมชั่นพิเศษ</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="/api/placeholder/300/400" class="img-fluid rounded-start h-100 object-fit-cover" alt="โปรโมชั่น">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <span class="badge bg-danger mb-2">โปรฮอต</span>
                                    <h4 class="card-title">ซื้อ 1 แถม 1</h4>
                                    <p class="card-text">โปรโมชั่นสุดคุ้ม ซื้อสินค้าที่ร่วมรายการ 1 ชิ้น รับฟรีทันที 1 ชิ้น เฉพาะวันนี้ - 31 พ.ค. เท่านั้น</p>
                                    <a href="#" class="btn btn-outline-primary">ดูรายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <img src="/api/placeholder/300/400" class="img-fluid rounded-start h-100 object-fit-cover" alt="คูปอง">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <span class="badge bg-primary mb-2">สมาชิกใหม่</span>
                                    <h4 class="card-title">รับส่วนลด 200 บาท</h4>
                                    <p class="card-text">สมัครสมาชิกใหม่วันนี้ รับคูปองส่วนลด 200 บาท เมื่อซื้อสินค้าครบ 1,000 บาทขึ้นไป</p>
                                    <a href="#" class="btn btn-outline-primary">สมัครสมาชิก</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <h3>สมัครรับข่าวสาร</h3>
                    <p>รับข่าวสารโปรโมชั่นและสินค้าใหม่ก่อนใคร</p>
                    <form class="row g-2 justify-content-center">
                        <div class="col-sm-8">
                            <input type="email" class="form-control form-control-lg" placeholder="อีเมลของคุณ" required>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-warning btn-lg w-100">สมัครรับข่าวสาร</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

    <!-- Bootstrap & jQuery JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <script>
        // ในไฟล์จริงคุณจะมี JavaScript สำหรับฟังก์ชันต่างๆ เช่น การเพิ่มสินค้าลงตะกร้า
        $(document).ready(function() {
            // ตัวอย่างฟังก์ชันการเพิ่มสินค้าลงตะกร้า
            $('.btn-primary').click(function() {
                alert('เพิ่มสินค้าลงตะกร้าเรียบร้อยแล้ว');
                // ในระบบจริงคุณจะใช้ AJAX เพื่อเพิ่มสินค้าลงตะกร้าโดยไม่ต้อง refresh หน้า
            });
        });
    </script>
</body>
</html>