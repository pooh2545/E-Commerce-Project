<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า</title>
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
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            padding: 40px;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #8e44ad;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: #732d91;
        }

        .breadcrumb span {
            color: #999;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 50px;
        }

        .product-image-section {
            position: relative;
        }

        .main-image {
            width: 100%;
            height: 400px;
            background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 64px;
            color: #ccc;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .main-image::before {
            content: '📷';
            font-size: 4rem;
            opacity: 0.3;
        }

        .zoom-overlay {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .zoom-overlay:hover {
            background: rgba(0, 0, 0, 0.9);
            transform: scale(1.05);
        }

        .product-info {
            padding: 20px 0;
        }

        .product-title {
            font-size: 2rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .star {
            color: #ffc107;
            font-size: 18px;
        }

        .star.empty {
            color: #ddd;
        }

        .rating-text {
            color: #666;
            font-size: 14px;
        }

        .product-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #27ae60;
            margin-bottom: 20px;
        }

        .price-currency {
            font-size: 1.5rem;
            color: #666;
        }

        .product-options {
            margin-bottom: 30px;
        }

        .option-group {
            margin-bottom: 20px;
        }

        .option-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }

        .color-options {
            display: flex;
            gap: 10px;
        }

        .color-option {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .color-option.selected {
            border-color: #8e44ad;
            transform: scale(1.1);
        }

        .color-option.purple { background: #8e44ad; }
        .color-option.blue { background: #3498db; }
        .color-option.green { background: #27ae60; }
        .color-option.red { background: #e74c3c; }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
        }

        .quantity-label {
            font-weight: 600;
            color: #333;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .qty-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f8f9fa;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            color: #666;
            transition: all 0.3s ease;
        }

        .qty-btn:hover {
            background: #8e44ad;
            color: white;
        }

        .qty-input {
            width: 60px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
        }

        .add-to-cart {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #8e44ad, #e74c3c);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(142, 68, 173, 0.4);
        }

        .buy-now {
            width: 100%;
            padding: 15px;
            background: transparent;
            color: #8e44ad;
            border: 2px solid #8e44ad;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .buy-now:hover {
            background: #8e44ad;
            color: white;
            transform: translateY(-2px);
        }

        .related-products {
            margin-top: 50px;
        }

        .section-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 30px;
            text-align: left;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #8e44ad, #e74c3c);
            border-radius: 2px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            position: relative;
        }

        .related-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .related-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .related-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: #ccc;
        }

        .related-image::before {
            content: '📦';
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .related-info {
            padding: 20px;
            text-align: center;
        }

        .related-name {
            font-size: 1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .related-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: #8e44ad;
            margin-bottom: 15px;
        }

        .related-btn {
            width: 100%;
            padding: 10px;
            background: linear-gradient(45deg, #8e44ad, #e74c3c);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .related-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.4);
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            cursor: pointer;
            font-size: 20px;
            color: #8e44ad;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .carousel-nav:hover {
            background: #8e44ad;
            color: white;
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-nav.prev {
            left: -20px;
        }

        .carousel-nav.next {
            right: -20px;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .related-card {
            animation: fadeInUp 0.6s ease-out;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .product-detail {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .product-price {
                font-size: 2rem;
            }

            .related-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
            }

            .carousel-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container" style="margin-top: 10px; margin-bottom:10px">
        <div class="main-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">หน้าแรก</a>
                <span>›</span>
                <a href="#">สินค้า</a>
                <span>›</span>
                <span>รายละเอียด</span>
            </div>

            <!-- Product Detail Section -->
            <div class="product-detail">
                <div class="product-image-section">
                    <div class="main-image">
                        <div class="zoom-overlay">🔍 ซูม</div>
                    </div>
                </div>

                <div class="product-info">
                    <h1 class="product-title">ชื่อสินค้า</h1>
                    <!--
                    <div class="product-rating">
                        <div class="stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star empty">★</span>
                        </div>
                        <span class="rating-text">(4.0 จาก 128 รีวิว)</span>
                    </div>
    -->
                    <div class="product-price">
                        ฿199 <span class="price-currency">บาท</span>
                    </div>
                    <!--
                    <div class="product-options">
                        <div class="option-group">
                            <label class="option-label">เลือกสี:</label>
                            <div class="color-options">
                                <div class="color-option purple selected" data-color="purple"></div>
                                <div class="color-option blue" data-color="blue"></div>
                                <div class="color-option green" data-color="green"></div>
                                <div class="color-option red" data-color="red"></div>
                            </div>
                        </div>
                    </div>
    -->
                    <div class="quantity-selector">
                        <span class="quantity-label">จำนวน:</span>
                        <div class="quantity-controls">
                            <button class="qty-btn" onclick="decreaseQty()">-</button>
                            <input type="number" class="qty-input" value="1" min="1" id="quantity">
                            <button class="qty-btn" onclick="increaseQty()">+</button>
                        </div>
                    </div>

                    <button class="add-to-cart" onclick="addToCart()">เพิ่มลงตะกร้า</button>
                    <button class="buy-now" onclick="buyNow()">ซื้อทันที</button>
                </div>
            </div>

            <!-- Related Products Section -->
            <div class="related-products">
                <h2 class="section-title">รายการที่คล้ายกัน</h2>
                <div class="related-grid" id="relatedGrid">
                    <button class="carousel-nav prev" onclick="slideCarousel('prev')">‹</button>
                    <button class="carousel-nav next" onclick="slideCarousel('next')">›</button>
                    
                    <div class="related-card">
                        <div class="related-image"></div>
                        <div class="related-info">
                            <div class="related-name">สินค้าที่ 1</div>
                            <div class="related-price">฿299</div>
                            <button class="related-btn" onclick="viewProduct(1)">ดูรายละเอียด</button>
                        </div>
                    </div>

                    <div class="related-card">
                        <div class="related-image"></div>
                        <div class="related-info">
                            <div class="related-name">สินค้าที่ 2</div>
                            <div class="related-price">฿459</div>
                            <button class="related-btn" onclick="viewProduct(2)">ดูรายละเอียด</button>
                        </div>
                    </div>

                    <div class="related-card">
                        <div class="related-image"></div>
                        <div class="related-info">
                            <div class="related-name">สินค้าที่ 3</div>
                            <div class="related-price">฿599</div>
                            <button class="related-btn" onclick="viewProduct(3)">ดูรายละเอียด</button>
                        </div>
                    </div>

                    <div class="related-card">
                        <div class="related-image"></div>
                        <div class="related-info">
                            <div class="related-name">สินค้าที่ 4</div>
                            <div class="related-price">฿799</div>
                            <button class="related-btn" onclick="viewProduct(4)">ดูรายละเอียด</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script>
        // Sample product data (ในการใช้งานจริงควรดึงจาก API)
        const products = [
            { id: 1, name: 'สมาร์ทโฟน X1', price: '15,990', category: 'electronics', sale: true, description: 'สมาร์ทโฟนรุ่นล่าสุด พร้อมฟีเจอร์ครบครัน', rating: 4.5, reviews: 128 },
            { id: 2, name: 'เสื้อยืดแฟชั่น', price: '590', category: 'fashion', new: true, description: 'เสื้อยืดคุณภาพดี ใส่สบาย', rating: 4.2, reviews: 85 },
            { id: 3, name: 'หูฟังไร้สาย', price: '2,490', category: 'electronics', description: 'หูฟังไร้สายคุณภาพเสียงดี', rating: 4.7, reviews: 203 },
            { id: 4, name: 'หนังสือพัฒนาตนเอง', price: '320', category: 'books', description: 'หนังสือเพื่อพัฒนาตนเองและเติบโตในชีวิต', rating: 4.3, reviews: 67 },
            { id: 5, name: 'กางเกงยีนส์', price: '1,290', category: 'fashion', sale: true, description: 'กางเกงยีนส์สไตล์เท่ ใส่ได้ทุกโอกาส', rating: 4.0, reviews: 152 },
            { id: 6, name: 'โคมไฟตั้งโต๊ะ', price: '890', category: 'home', description: 'โคมไฟตกแต่งบ้าน สวยงามใช้งานได้จริง', rating: 4.4, reviews: 94 }
        ];

        // Get product ID from URL parameters
        function getProductIdFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            return parseInt(urlParams.get('id')) || 1; // Default to product ID 1
        }

        // Load product data
        function loadProductData() {
            const productId = getProductIdFromURL();
            const product = products.find(p => p.id === productId) || products[0];
            
            // Update page content with product data
            document.querySelector('.product-title').textContent = product.name;
            document.querySelector('.product-price').innerHTML = `฿${product.price} <span class="price-currency">บาท</span>`;
            
            // Update rating
            const stars = document.querySelectorAll('.star');
            const rating = Math.floor(product.rating);
            stars.forEach((star, index) => {
                star.classList.toggle('empty', index >= rating);
            });
            document.querySelector('.rating-text').textContent = `(${product.rating} จาก ${product.reviews} รีวิว)`;
        }

        // Color selection functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Load product data when page loads
            loadProductData();
            
            const colorOptions = document.querySelectorAll('.color-option');
            
            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });
        });

        // Quantity control functions
        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            const currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }

        // Add to cart function
        function addToCart() {
            const quantity = document.getElementById('quantity').value;
            const selectedColor = document.querySelector('.color-option.selected').dataset.color;
            alert(`เพิ่มสินค้าลงตะกร้าแล้ว!\nจำนวน: ${quantity}\nสี: ${selectedColor}`);
        }

        // Buy now function
        function buyNow() {
            const quantity = document.getElementById('quantity').value;
            const selectedColor = document.querySelector('.color-option.selected').dataset.color;
            alert(`ดำเนินการซื้อทันที!\nจำนวน: ${quantity}\nสี: ${selectedColor}`);
        }

        // View related product
        function viewProduct(id) {
            alert(`ไปยังหน้ารายละเอียดสินค้า ID: ${id}`);
        }

        // Carousel functionality
        let currentSlide = 0;
        const cardsPerView = window.innerWidth <= 768 ? 1 : 4;

        function slideCarousel(direction) {
            const grid = document.getElementById('relatedGrid');
            const cards = grid.querySelectorAll('.related-card');
            const totalCards = cards.length;
            
            if (direction === 'next') {
                currentSlide = (currentSlide + 1) % (totalCards - cardsPerView + 1);
            } else {
                currentSlide = currentSlide > 0 ? currentSlide - 1 : totalCards - cardsPerView;
            }
            
            // Add smooth scroll effect (simplified version)
            console.log(`Sliding to card ${currentSlide}`);
        }

        // Zoom functionality
        document.querySelector('.zoom-overlay').addEventListener('click', function() {
            alert('เปิดโหมดซูมรูปภาพ');
        });
    </script>
</body>
</html>