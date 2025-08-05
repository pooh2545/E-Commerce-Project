<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Design</title>
    <link rel="icon" type="image/x-icon" href="assets/images/ico.jpg">
    <link href="assets/css/header.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            position: relative;
        }

        .card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .card-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #e0e0e0 0%, #c0c0c0 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
        }

        .card-content {
            padding: 15px;
        }

        .card-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .card-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .card-button {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        .card-button:hover {
            background: linear-gradient(135deg, #7d3c98 0%, #8e44ad 100%);
        }

        .navigation-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: #333;
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            z-index: 10;
            transition: background 0.3s ease;
        }

        .navigation-arrow:hover {
            background: #555;
        }

        .nav-right {
            right: -20px;
        }

        .nav-left {
            left: -20px;
        }

        /* Banner Section */
        .banner-section {
            position: relative;
            height: 400px;
            margin-bottom: 50px;
            overflow: hidden;
            border-radius: 0px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        }

        .banner-slider {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .banner-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            background-size: cover;
            background-position: center;
        }

        .banner-slide.active {
            opacity: 1;
        }

        .banner-slide:nth-child(1) {
            background: linear-gradient(135deg, rgba(142, 68, 173, 0.8), rgba(155, 89, 182, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="1200" height="400" fill="%238e44ad"/><rect width="1200" height="400" fill="url(%23dots)"/></svg>');
        }

        .banner-slide:nth-child(2) {
            background: linear-gradient(135deg, rgba(46, 204, 113, 0.8), rgba(39, 174, 96, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="lines" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse"><path d="M0,25 L50,25" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="1200" height="400" fill="%232ecc71"/><rect width="1200" height="400" fill="url(%23lines)"/></svg>');
        }

        .banner-slide:nth-child(3) {
            background: linear-gradient(135deg, rgba(230, 126, 34, 0.8), rgba(211, 84, 0, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 400"><defs><pattern id="waves" x="0" y="0" width="100" height="50" patternUnits="userSpaceOnUse"><path d="M0,25 Q25,10 50,25 T100,25" stroke="rgba(255,255,255,0.1)" stroke-width="2" fill="none"/></pattern></defs><rect width="1200" height="400" fill="%23e67e22"/><rect width="1200" height="400" fill="url(%23waves)"/></svg>');
        }

        .banner-content {
            z-index: 2;
            max-width: 800px;
            padding: 0 20px;
        }

        .banner-title {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            animation: slideInDown 1s ease;
        }

        .banner-subtitle {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            animation: slideInUp 1s ease 0.3s both;
        }

        .banner-button {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid white;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            animation: slideInUp 1s ease 0.6s both;
        }

        .banner-button:hover {
            background: white;
            color: #8e44ad;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .banner-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 3;
        }

        .banner-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255,255,255,0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .banner-dot.active {
            background: white;
            transform: scale(1.2);
        }

        .banner-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 3;
        }

        .banner-arrow:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-50%) scale(1.1);
        }

        .banner-arrow.prev {
            left: 20px;
        }

        .banner-arrow.next {
            right: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .section-title {
                font-size: 20px;
                margin-bottom: 15px;
            }

            .card-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }

            .card-image {
                height: 150px;
            }

            .navigation-arrow {
                display: none;
            }

            .banner-section {
                height: 300px;
            }

            .banner-title {
                font-size: 32px;
            }

            .banner-subtitle {
                font-size: 16px;
            }

            .banner-arrow {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .banner-arrow.prev {
                left: 10px;
            }

            .banner-arrow.next {
                right: 10px;
            }
        }

        @media (max-width: 480px) {
            .card-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .banner-section {
                padding: 40px 15px;
                height: 250px;
            }

            .banner-title {
                font-size: 28px;
            }

            .banner-subtitle {
                font-size: 14px;
            }
        }

        /* Animation for cards */
        .card {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }

        /* Animation keyframes */
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>
    <!-- Banner Section -->
    <div class="banner-section">
        <div class="banner-slider">
            <div class="banner-slide active">
                <div class="banner-content">
                    <div class="banner-title">ยินดีต้อนรับสู่ร้านค้าออนไลน์</div>
                    <div class="banner-subtitle">สินค้าคุณภาพดี ราคาถูก ส่งฟรีทั่วประเทศ</div>
                    <button class="banner-button">เริ่มช้อปปิ้ง</button>
                </div>
            </div>
            <div class="banner-slide">
                <div class="banner-content">
                    <div class="banner-title">สินค้าใหม่เพิ่งมาถึง</div>
                    <div class="banner-subtitle">อัพเดทเทรนด์ล่าสุด ก่อนใครในราคาพิเศษ</div>
                    <button class="banner-button">ดูสินค้าใหม่</button>
                </div>
            </div>
            <div class="banner-slide">
                <div class="banner-content">
                    <div class="banner-title">โปรโมชั่นสุดพิเศษ</div>
                    <div class="banner-subtitle">ลด 50% สำหรับสมาชิกใหม่ วันนี้เท่านั้น!</div>
                    <button class="banner-button">รับส่วนลด</button>
                </div>
            </div>
        </div>
        
        <button class="banner-arrow prev" onclick="changeBanner(-1)">‹</button>
        <button class="banner-arrow next" onclick="changeBanner(1)">›</button>
        
        <div class="banner-nav">
            <span class="banner-dot active" onclick="goToBanner(0)"></span>
            <span class="banner-dot" onclick="goToBanner(1)"></span>
            <span class="banner-dot" onclick="goToBanner(2)"></span>
        </div>
    </div>

    <div class="container">
        <!-- หมวดหมู่ Section -->
        <div class="section">
            <h2 class="section-title">หมวดหมู่</h2>
            <div class="card-grid">
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">เสื้อผ้า</div>
                        <div class="card-subtitle">แฟชั่นทันสมัย</div>
                        <button class="card-button">ดูเพิ่มเติม</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">กระเป๋า</div>
                        <div class="card-subtitle">คุณภาพดี</div>
                        <button class="card-button">ดูเพิ่มเติม</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">รองเท้า</div>
                        <div class="card-subtitle">สไตล์หลากหลาย</div>
                        <button class="card-button">ดูเพิ่มเติม</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">เครื่องประดับ</div>
                        <div class="card-subtitle">สวยงาม</div>
                        <button class="card-button">ดูเพิ่มเติม</button>
                    </div>
                </div>
                <button class="navigation-arrow nav-right" onclick="scrollCards(1)">›</button>
            </div>
        </div>

        <!-- สินค้าแนะนำ Section -->
        <div class="section">
            <h2 class="section-title">สินค้าแนะนำ</h2>
            <div class="card-grid">
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้าพิเศษ</div>
                        <div class="card-subtitle">ราคา 999 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้าใหม่</div>
                        <div class="card-subtitle">ราคา 1,299 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้าฮิต</div>
                        <div class="card-subtitle">ราคา 799 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้าโปรโมชั่น</div>
                        <div class="card-subtitle">ราคา 599 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <button class="navigation-arrow nav-right" onclick="scrollCards(2)">›</button>
            </div>
        </div>

        <!-- สินค้ายอดนิยม Section -->
        <div class="section">
            <h2 class="section-title">สินค้ายอดนิยม</h2>
            <div class="card-grid">
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้ายอดนิยม 1</div>
                        <div class="card-subtitle">ราคา 1,199 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้ายอดนิยม 2</div>
                        <div class="card-subtitle">ราคา 899 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้ายอดนิยม 3</div>
                        <div class="card-subtitle">ราคา 1,499 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card-image">ภาพสินค้า</div>
                    <div class="card-content">
                        <div class="card-title">สินค้ายอดนิยม 4</div>
                        <div class="card-subtitle">ราคา 1,099 บาท</div>
                        <button class="card-button">สั่งซื้อเลย</button>
                    </div>
                </div>
                <button class="navigation-arrow nav-right" onclick="scrollCards(3)">›</button>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>
    <script>
        // Banner slider functionality
        let currentBanner = 0;
        const bannerSlides = document.querySelectorAll('.banner-slide');
        const bannerDots = document.querySelectorAll('.banner-dot');
        const totalBanners = bannerSlides.length;

        function showBanner(index) {
            // Remove active class from all slides and dots
            bannerSlides.forEach(slide => slide.classList.remove('active'));
            bannerDots.forEach(dot => dot.classList.remove('active'));
            
            // Add active class to current slide and dot
            bannerSlides[index].classList.add('active');
            bannerDots[index].classList.add('active');
            
            currentBanner = index;
        }

        function changeBanner(direction) {
            let newIndex = currentBanner + direction;
            
            if (newIndex >= totalBanners) {
                newIndex = 0;
            } else if (newIndex < 0) {
                newIndex = totalBanners - 1;
            }
            
            showBanner(newIndex);
        }

        function goToBanner(index) {
            showBanner(index);
        }

        // Auto-slide banner every 5 seconds
        function startBannerAutoSlide() {
            setInterval(() => {
                changeBanner(1);
            }, 5000);
        }

        function scrollCards(section) {
            // Smooth scroll functionality for cards
            console.log('Scrolling section:', section);
            // This would implement horizontal scrolling for card grids
        }

        // Add smooth scroll behavior and intersection observer for animations
        document.addEventListener('DOMContentLoaded', function() {
            // Start banner auto-slide
            startBannerAutoSlide();
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            });

            document.querySelectorAll('.card').forEach(card => {
                observer.observe(card);
            });
        });

        // Add click handlers for buttons
        document.querySelectorAll('.card-button').forEach(button => {
            button.addEventListener('click', function() {
                const cardTitle = this.closest('.card').querySelector('.card-title').textContent;
                alert(`คลิกที่: ${cardTitle}`);
            });
        });

        // Add click handlers for banner buttons
        document.querySelectorAll('.banner-button').forEach(button => {
            button.addEventListener('click', function() {
                const buttonText = this.textContent;
                alert(`คลิกที่ปุ่ม: ${buttonText}`);
            });
        });
    </script>
</body>
</html>