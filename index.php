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

        html {
            scrollbar-gutter: stable;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .add-to-cart-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
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

        /* Category Section Styles */
        .category-scroll-container {
            position: relative;
            overflow: hidden;
        }

        .category-grid {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .category-grid::-webkit-scrollbar {
            display: none;
        }

        .category-card {
            flex: 0 0 200px;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .category-image {
            width: 100%;
            height: 150px;
            background: #e0e0e0;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 12px;
            overflow: hidden;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .category-name {
            padding: 12px 8px;
            text-align: center;
            font-size: 14px;
            font-weight: 500;
            color: #333;
        }

        /* Product Section Styles */
        .product-scroll-container {
            position: relative;
            overflow: hidden;
        }

        .product-grid {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 10px 0;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .product-grid::-webkit-scrollbar {
            display: none;
        }

        .product-card {
            flex: 0 0 250px;
            background: white;
            border-radius: 12px;
            text-align: center;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #e0e0e0 0%, #c0c0c0 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-image::before {
            content: '📦';
            font-size: 2rem;
            opacity: 0.3;
            position: absolute;
            z-index: 1;
        }

        .product-image.has-image::before {
            display: none;
        }

        .product-content {
            padding: 12px;
        }

        .product-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 20px;
        }

        .product-detail {
            font-size: 12px;
            color: #666;
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #28A745;
            margin-bottom: 10px;
        }

        .add-to-cart-btn {
            background: linear-gradient(135deg, #8e44ad 0%, #9b59b6 100%);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            font-weight: 500;
        }

        .add-to-cart-btn:hover {
            background: linear-gradient(135deg, #7d3c98 0%, #8e44ad 100%);
            transform: translateY(-1px);
        }

        .add-to-cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        /* Navigation Arrows */
        .scroll-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            z-index: 10;
            transition: all 0.3s ease;
            opacity: 0.8;
        }

        .scroll-arrow:hover {
            background: rgba(0, 0, 0, 0.9);
            opacity: 1;
            transform: translateY(-50%) scale(1.1);
        }

        .scroll-left {
            left: 0px;
        }

        .scroll-right {
            right: 0px;
        }

        .loading-section {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .error-section {
            text-align: center;
            padding: 40px 20px;
            color: #e74c3c;
            background-color: #ffeaea;
            border-radius: 8px;
            margin: 20px 0;
        }

        /* Banner Section */
        .banner-section {
            position: relative;
            height: 400px;
            margin-bottom: 50px;
            overflow: hidden;
            border-radius: 0px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
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
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            animation: slideInDown 1s ease;
        }

        .banner-subtitle {
            font-size: 20px;
            opacity: 0.95;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
            animation: slideInUp 1s ease 0.3s both;
        }

        .banner-button {
            background: rgba(255, 255, 255, 0.2);
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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
            background: rgba(255, 255, 255, 0.5);
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
            background: rgba(255, 255, 255, 0.2);
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
            background: rgba(255, 255, 255, 0.3);
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

            .category-card {
                flex: 0 0 100px;
            }

            .category-image {
                height: 70px;
            }

            .product-card {
                flex: 0 0 180px;
            }

            .product-image {
                height: 130px;
            }

            .scroll-arrow {
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
            .category-card {
                flex: 0 0 90px;
            }

            .product-card {
                flex: 0 0 160px;
            }

            .banner-section {
                height: 250px;
            }

            .banner-title {
                font-size: 28px;
            }

            .banner-subtitle {
                font-size: 14px;
            }
        }

        /* Animation keyframes */
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

        .fade-in {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        @keyframes fadeInUp {
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
                    <button class="banner-button" onclick="window.location.href='products.php'">เริ่มช้อปปิ้ง</button>
                </div>
            </div>
            <div class="banner-slide">
                <div class="banner-content">
                    <div class="banner-title">สินค้าใหม่เพิ่งมาถึง</div>
                    <div class="banner-subtitle">อัพเดทเทรนด์ล่าสุด ก่อนใครในราคาพิเศษ</div>
                    <button class="banner-button" onclick="window.location.href='products.php'">ดูสินค้าใหม่</button>
                </div>
            </div>
        </div>

        <button class="banner-arrow prev" onclick="changeBanner(-1)">‹</button>
        <button class="banner-arrow next" onclick="changeBanner(1)">›</button>

        <div class="banner-nav">
            <span class="banner-dot active" onclick="goToBanner(0)"></span>
            <span class="banner-dot" onclick="goToBanner(1)"></span>
        </div>
    </div>

    <div class="container">
        <!-- หมวดหมู่ Section -->
        <div class="section">
            <h2 class="section-title">หมวดหมู่</h2>
            <div id="categoriesLoading" class="loading-section">กำลังโหลดหมวดหมู่...</div>
            <div id="categoriesError" class="error-section" style="display: none;"></div>
            <div class="category-scroll-container" id="categoryContainer" style="display: none;">
                <div class="category-grid" id="categoriesGrid">
                    <!-- Categories will be populated by JavaScript -->
                </div>
                <button class="scroll-arrow scroll-left" onclick="scrollContainer('categoriesGrid', -200)">‹</button>
                <button class="scroll-arrow scroll-right" onclick="scrollContainer('categoriesGrid', 200)">›</button>
            </div>
        </div>

        <!-- สินค้าแนะนำ Section -->
        <div class="section">
            <h2 class="section-title">สินค้าแนะนำ</h2>
            <div id="recommendedLoading" class="loading-section">กำลังโหลดสินค้าแนะนำ...</div>
            <div id="recommendedError" class="error-section" style="display: none;"></div>
            <div class="product-scroll-container" id="recommendedContainer" style="display: none;">
                <div class="product-grid" id="recommendedGrid">
                    <!-- Recommended products will be populated by JavaScript -->
                </div>
                <button class="scroll-arrow scroll-left" onclick="scrollContainer('recommendedGrid', -220)">‹</button>
                <button class="scroll-arrow scroll-right" onclick="scrollContainer('recommendedGrid', 220)">›</button>
            </div>
        </div>

        <!-- สินค้าขายดี Section -->
        <div class="section">
            <h2 class="section-title">สินค้าขายดี</h2>
            <div id="popularProductsLoading" class="loading-section">กำลังโหลดสินค้าขายดี...</div>
            <div id="popularProductsError" class="error-section" style="display: none;"></div>
            <div class="product-scroll-container" id="popularProductsContainer" style="display: none;">
                <div class="product-grid" id="popularProductsGrid">
                    <!-- Popular products will be populated by JavaScript -->
                </div>
                <button class="scroll-arrow scroll-left" onclick="scrollContainer('popularProductsGrid', -220)">‹</button>
                <button class="scroll-arrow scroll-right" onclick="scrollContainer('popularProductsGrid', 220)">›</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

    <script src="assets/js/notification.js"></script>   
    <script src="assets/js/cart.js"></script>
    
    <script>
        let products = [];
        let categories = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
            loadProducts();
            startBannerAutoSlide();
        });

        // Load categories from API
        async function loadCategories() {
            const loadingEl = document.getElementById('categoriesLoading');
            const errorEl = document.getElementById('categoriesError');
            const containerEl = document.getElementById('categoryContainer');

            try {
                const response = await fetch('controller/shoetype_api.php?action=all');
                if (!response.ok) throw new Error('Failed to fetch categories');

                const data = await response.json();
                if (Array.isArray(data)) {
                    categories = data;
                    renderCategories();
                    containerEl.style.display = 'block';
                    loadingEl.style.display = 'none';
                } else {
                    throw new Error('Invalid categories data');
                }
            } catch (error) {
                console.error('Error loading categories:', error);
                errorEl.textContent = 'เกิดข้อผิดพลาดในการโหลดหมวดหมู่';
                errorEl.style.display = 'block';
                loadingEl.style.display = 'none';
            }
        }

        // Load products from API
        async function loadProducts() {
            try {
                const response = await fetch('controller/product_api.php?action=all');
                if (!response.ok) throw new Error('Failed to fetch products');

                const data = await response.json();
                if (Array.isArray(data)) {
                    products = data;
                    renderRecommendedProducts();
                    renderPopularProducts();
                } else {
                    throw new Error('Invalid products data');
                }
            } catch (error) {
                console.error('Error loading products:', error);
                showProductError('recommendedError', 'เกิดข้อผิดพลาดในการโหลดสินค้าแนะนำ');
                showProductError('newProductsError', 'เกิดข้อผิดพลาดในการโหลดสินค้าใหม่');
                showProductError('popularProductsError', 'เกิดข้อผิดพลาดในการโหลดสินค้ายอดนิยม');
                hideProductLoading();
            }
        }

        // Show product error
        function showProductError(errorId, message) {
            const errorEl = document.getElementById(errorId);
            errorEl.textContent = message;
            errorEl.style.display = 'block';
        }

        // Hide product loading
        function hideProductLoading() {
            document.getElementById('recommendedLoading').style.display = 'none';
            document.getElementById('newProductsLoading').style.display = 'none';
            document.getElementById('popularProductsLoading').style.display = 'none';
        }

        // Render categories
        function renderCategories() {
            const gridEl = document.getElementById('categoriesGrid');

            if (categories.length === 0) {
                gridEl.innerHTML = '<div style="text-align: center; padding: 40px; color: #666;">ไม่พบหมวดหมู่</div>';
                return;
            }

            const categoriesHTML = categories.map((category, index) => {
                const imageSrc = category.images ? `controller/uploads/${category.images}` : '';
                const imageHTML = imageSrc ?
                    `<img src="${imageSrc}" alt="${category.name}" onerror="this.style.display='none'">` : '';

                return `
                    <div class="category-card " style="animation-delay: ${index * 0.1}s" onclick="goToCategory('${category.shoetype_id}')">
                        <div class="category-image ${imageSrc ? 'has-image' : ''}">
                            ${imageHTML}
                        </div>
                        <div class="category-name">${category.name}</div>
                    </div>
                `;
            }).join('');

            gridEl.innerHTML = categoriesHTML;
        }

        // Render recommended products (first 8 products)
        function renderRecommendedProducts() {
            const loadingEl = document.getElementById('recommendedLoading');
            const errorEl = document.getElementById('recommendedError');
            const containerEl = document.getElementById('recommendedContainer');
            const gridEl = document.getElementById('recommendedGrid');

            const recommendedProducts = products.slice(0, 8);

            if (recommendedProducts.length === 0) {
                errorEl.textContent = 'ไม่พบสินค้าแนะนำ';
                errorEl.style.display = 'block';
                loadingEl.style.display = 'none';
                return;
            }

            gridEl.innerHTML = renderProductCards(recommendedProducts);
            containerEl.style.display = 'block';
            loadingEl.style.display = 'none';
        }

        // Render popular products (last 8 products)
        function renderPopularProducts() {
            const loadingEl = document.getElementById('popularProductsLoading');
            const errorEl = document.getElementById('popularProductsError');
            const containerEl = document.getElementById('popularProductsContainer');
            const gridEl = document.getElementById('popularProductsGrid');

            const popularProducts = products.slice(-8);

            if (popularProducts.length === 0) {
                errorEl.textContent = 'ไม่พบสินค้ายอดนิยม';
                errorEl.style.display = 'block';
                loadingEl.style.display = 'none';
                return;
            }

            gridEl.innerHTML = renderProductCards(popularProducts);
            containerEl.style.display = 'block';
            loadingEl.style.display = 'none';
        }

        // Create product cards HTML
        function renderProductCards(productList) {
            return productList.map((product, index) => {
                const imageSrc = product.img_path ? `controller/uploads/products/${product.img_path}` : '';
                const imageHTML = imageSrc ?
                    `<img src="${imageSrc}" alt="${product.name}" onerror="this.style.display='none'">` : '';

                const stock = parseInt(product.stock) || 0;
                const isOutOfStock = stock <= 0;
                const price = parseFloat(product.price) || 0;

                return `
            <div class="product-card" style="animation-delay: ${index * 0.1}s" onclick="goToProductDetail('${product.shoe_id}')">
                <div class="product-image ${imageSrc ? 'has-image' : ''}">
                    ${imageHTML}
                </div>
                <div class="product-content">
                    <div class="product-name">${product.name}</div>
                    <div class="product-detail">รายละเอียด</div>
                    <div class="product-price">฿${price.toLocaleString()}</div>
                    <button id="cart-btn-${product.shoe_id}" 
                            class="add-to-cart-btn ${isOutOfStock ? 'disabled' : ''}" 
                            ${isOutOfStock ? 'disabled' : ''} 
                            onclick="event.stopPropagation(); ${isOutOfStock ? '' : 'handleAddToCart(\'' + product.shoe_id + '\')'}">
                        ${isOutOfStock ? 'สินค้าหมด' : 'เพิ่มลงตะกร้า'}
                    </button>
                </div>
            </div>
        `;
            }).join('');
        }

        // Scroll container function
        function scrollContainer(containerId, scrollAmount) {
            const container = document.getElementById(containerId);
            container.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }

        // Navigate to category
        function goToCategory(categoryId) {
            window.location.href = `products.php?category=${categoryId}`;
        }

        // Navigate to product detail
        function goToProductDetail(productId) {
            window.location.href = `products-detail.php?id=${productId}`;
        }

        // Add to cart function
        async function handleAddToCart(productId) {
            const button = document.getElementById(`cart-btn-${productId}`);

            if (!button) {
                console.error('Button not found for product:', productId);
                return;
            }

            const originalText = button.textContent;

            try {
                // Set loading state
                button.disabled = true;
                button.textContent = 'กำลังเพิ่ม...';
                button.style.opacity = '0.7';

                console.log('Attempting to add product to cart:', productId);

                // เรียกใช้ฟังก์ชัน addToCart จาก cart.js
                const success = await addToCart(productId, 1);

                if (success) {
                    // Success - temporarily change button text
                    button.textContent = '✓ เพิ่มแล้ว';
                    button.style.background = '#27ae60';
                    button.style.opacity = '1';

                    // Reset button after 2 seconds
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.background = '';
                        button.disabled = false;
                        button.style.opacity = '1';
                    }, 2000);
                } else {
                    // Failed - reset button immediately
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    button.style.opacity = '1';
                }
            } catch (error) {
                console.error('Error in handleAddToCart:', error);

                // Reset button on error
                button.textContent = originalText;
                button.disabled = false;
                button.style.background = '';
                button.style.opacity = '1';

                // Show error notification
                if (typeof showNotification === 'function') {
                    showNotification('เกิดข้อผิดพลาดในการเพิ่มสินค้า', 'error');
                }
            }
        }

        // Show add to cart success message
        function showAddToCartSuccess(productName) {
            // Create a simple notification
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #2ecc71;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 1000;
                font-weight: 500;
                animation: slideInRight 0.3s ease;
            `;
            notification.textContent = `เพิ่ม "${productName}" ลงตะกร้าแล้ว!`;

            // Add CSS animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideInRight {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
            `;
            document.head.appendChild(style);

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideInRight 0.3s ease reverse';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Banner functionality
        let currentBanner = 0;
        const bannerSlides = document.querySelectorAll('.banner-slide');
        const bannerDots = document.querySelectorAll('.banner-dot');
        const totalBanners = bannerSlides.length;

        function showBanner(index) {
            bannerSlides.forEach(slide => slide.classList.remove('active'));
            bannerDots.forEach(dot => dot.classList.remove('active'));

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

        // Handle scroll arrow visibility
        function updateScrollArrows() {
            const containers = [
                'categoriesGrid',
                'recommendedGrid',
                'newProductsGrid',
                'popularProductsGrid'
            ];

            containers.forEach(containerId => {
                const container = document.getElementById(containerId);
                if (!container) return;

                const parent = container.parentElement;
                const leftArrow = parent.querySelector('.scroll-left');
                const rightArrow = parent.querySelector('.scroll-right');

                if (leftArrow && rightArrow) {
                    // Show/hide arrows based on scroll position
                    leftArrow.style.display = container.scrollLeft > 0 ? 'flex' : 'none';
                    rightArrow.style.display =
                        container.scrollLeft < (container.scrollWidth - container.clientWidth) ? 'flex' : 'none';
                }
            });
        }

        // Add scroll event listeners to update arrow visibility
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing page...');

            // ตรวจสอบว่า cart.js โหลดแล้วหรือยัง
            if (typeof addToCart === 'function') {
                console.log('Cart.js functions are available');
            } else {
                console.warn('Cart.js functions not found, retrying...');
                // รอ 1 วินาทีแล้วลองใหม่
                setTimeout(() => {
                    if (typeof addToCart === 'function') {
                        console.log('Cart.js functions are now available');
                    } else {
                        console.error('Cart.js functions still not available');
                    }
                }, 1000);
            }

            loadCategories();
            loadProducts();
            startBannerAutoSlide();
        });

        function cleanupInvalidOnclickEvents() {
            // หาปุ่มทั้งหมดที่มี onclick
            const buttonsWithOnclick = document.querySelectorAll('button[onclick]');

            buttonsWithOnclick.forEach(button => {
                const onclickValue = button.getAttribute('onclick');

                // ตรวจสอบว่ามี PD แต่ไม่มีเครื่องหมาย quote
                if (onclickValue && onclickValue.includes('PD') && !onclickValue.includes('\'PD') && !onclickValue.includes('"PD')) {
                    console.warn('Found invalid onclick:', onclickValue);

                    // แยก product ID ออกมา
                    const match = onclickValue.match(/PD(\d+)/);
                    if (match) {
                        const productId = 'PD' + match[1];
                        console.log('Fixing product ID:', productId);

                        // แก้ไข onclick attribute
                        const newOnclick = onclickValue.replace(/PD\d+/g, `'${productId}'`);
                        button.setAttribute('onclick', newOnclick);
                        console.log('Fixed onclick:', newOnclick);
                    }
                }
            });
        }

        // Touch/swipe support for mobile
        let startX = 0;
        let isScrolling = false;

        function addTouchSupport() {
            const scrollContainers = document.querySelectorAll('.category-grid, .product-grid');

            scrollContainers.forEach(container => {
                container.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isScrolling = false;
                }, {
                    passive: true
                });

                container.addEventListener('touchmove', (e) => {
                    if (!startX) return;

                    const currentX = e.touches[0].clientX;
                    const diffX = startX - currentX;

                    if (Math.abs(diffX) > 10) {
                        isScrolling = true;
                    }
                }, {
                    passive: true
                });

                container.addEventListener('touchend', () => {
                    startX = 0;
                    isScrolling = false;
                }, {
                    passive: true
                });
            });
        }

        // Initialize touch support
        setTimeout(addTouchSupport, 1000);

        // Intersection Observer for animation
        function setupAnimations() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, {
                threshold: 0.1
            });

            // Observe elements after they are rendered
            setTimeout(() => {
                document.querySelectorAll('.fade-in').forEach(element => {
                    observer.observe(element);
                });
            }, 500);
        }

        // Setup animations after content is loaded
        setTimeout(setupAnimations, 1500);

        // Add keyboard navigation support
        document.addEventListener('keydown', (e) => {
            const focusedElement = document.activeElement;
            const isScrollContainer = focusedElement.classList.contains('category-grid') ||
                focusedElement.classList.contains('product-grid');

            if (isScrollContainer) {
                switch (e.key) {
                    case 'ArrowLeft':
                        e.preventDefault();
                        focusedElement.scrollBy({
                            left: -200,
                            behavior: 'smooth'
                        });
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        focusedElement.scrollBy({
                            left: 200,
                            behavior: 'smooth'
                        });
                        break;
                }
            }
        });

        setTimeout(cleanupInvalidOnclickEvents, 2000);

        // Make scroll containers focusable for keyboard navigation
        setTimeout(() => {
            document.querySelectorAll('.category-grid, .product-grid').forEach(container => {
                container.setAttribute('tabindex', '0');
                container.style.outline = 'none';
            });
        }, 1000);
    </script>
</body>

</html>