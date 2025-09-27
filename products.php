<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าทั้งหมด</title>
    <link rel="icon" type="image/x-icon" href="assets/images/Logo.png">
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

    .main-content {
        padding: 40px;
    }

    .page-title {
        text-align: center;
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 40px;
        font-weight: 300;
        position: relative;
    }

    .page-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #8e44ad, #e74c3c);
        border-radius: 2px;
    }

    .filters {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 12px 24px;
        background: rgba(142, 68, 173, 0.1);
        border: 2px solid #8e44ad;
        border-radius: 25px;
        color: #8e44ad;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        font-size: 14px;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background: #8e44ad;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
    }

    .loading {
        text-align: center;
        padding: 40px;
        font-size: 18px;
        color: #666;
    }

    .error-message {
        text-align: center;
        padding: 40px;
        font-size: 18px;
        color: #e74c3c;
        background: #ffeaea;
        border-radius: 10px;
        margin: 20px 0;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .product-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        text-align: center;
        transition: all 0.3s ease;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        position: relative;
        cursor: pointer;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .product-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        color: #ccc;
        position: relative;
        overflow: hidden;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-image::before {
        content: '📦';
        font-size: 3rem;
        opacity: 0.3;
    }

    .product-image.has-image::before {
        display: none;
    }

    .product-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: left 0.5s;
    }

    .product-card:hover .product-image::after {
        left: 100%;
    }

    .product-info {
        padding: 20px;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-price {
        font-size: 1.2rem;
        font-weight: 700;
        color: #28A745;
        margin-bottom: 8px;
    }

    .product-stock {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 15px;
    }

    .product-stock.in-stock {
        color: #27ae60;
    }

    .product-stock.out-of-stock {
        color: #e74c3c;
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        background: #752092;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(142, 68, 173, 0.4);
    }

    .add-to-cart-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .sale-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #e74c3c;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        z-index: 1;
    }

    .new-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #27ae60;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
        z-index: 1;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 40px;
    }

    .page-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #8e44ad;
        background: white;
        color: #8e44ad;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
    }

    .page-btn:hover,
    .page-btn.active {
        background: #8e44ad;
        color: white;
        transform: scale(1.1);
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

    .product-card {
        animation: fadeInUp 0.6s ease-out;
    }

    @media (max-width: 768px) {
        .main-content {
            padding: 20px;
        }

        .page-title {
            font-size: 2rem;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .filters {
            gap: 10px;
        }

        .filter-btn {
            padding: 10px 20px;
            font-size: 13px;
        }
    }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container" style="margin-top: 50px; margin-bottom: 50px;">
        <div class="main-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">หน้าแรก</a>
                <span>›</span>
                <span>สินค้า</span>
            </div>
            <h1 class="page-title"></h1>

            <!-- Loading indicator -->
            <div id="loadingIndicator" class="loading">
                กำลังโหลดสินค้า...
            </div>

            <!-- Error message -->
            <div id="errorMessage" class="error-message" style="display: none;">
                เกิดข้อผิดพลาดในการโหลดข้อมูล กรุณาลองใหม่อีกครั้ง
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                <!-- Products will be generated by JavaScript -->
            </div>

            <div class="pagination" id="pagination" style="display: none;">
                <!--
                <button class="page-btn" data-page="prev">‹</button>
                <button class="page-btn active" data-page="1">1</button>
                <button class="page-btn" data-page="2">2</button>
                <button class="page-btn" data-page="3">3</button>
                <button class="page-btn" data-page="4">4</button>
                <button class="page-btn" data-page="next">›</button>
    -->
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>

    <script src="assets/js/notification.js"></script>
    <script src="assets/js/cart.js"></script>
    <script>
    // เพิ่มฟังก์ชันสำหรับดึง URL parameters
    function getURLParameter(name) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    // เพิ่มฟังก์ชันสำหรับอัพเดต URL โดยไม่ reload หน้า
    function updateURL(category) {
        const url = new URL(window.location);
        if (category && category !== 'all') {
            url.searchParams.set('category', category);
        } else {
            url.searchParams.delete('category');
        }
        window.history.pushState({}, '', url);
    }

    let products = [];
    let categories = [];
    let currentCategory = 'all';
    let currentPage = 1;
    const itemsPerPage = 12;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        const urlCategory = getURLParameter('category');
        if (urlCategory) {
            currentCategory = urlCategory;
        }

        // ตรวจสอบว่า cart.js functions พร้อมใช้งานหรือยัง
        if (typeof addToCart === 'function') {
            console.log('Cart.js functions are available');
        } else {
            console.warn('Cart.js functions not found, retrying...');
            setTimeout(() => {
                if (typeof addToCart === 'function') {
                    console.log('Cart.js functions are now available');
                } else {
                    console.error('Cart.js functions still not available');
                }
            }, 1000);
        }

        loadCategories().then(() => {
            loadProducts();
            updatePageTitle();
        });

        setupEventListeners();
    });

    // Load categories from API
    async function loadCategories() {
        try {
            const response = await fetch('controller/product_api.php?action=categories');
            if (!response.ok) throw new Error('Failed to fetch categories');

            const data = await response.json();
            if (Array.isArray(data)) {
                categories = data;
                return Promise.resolve();
            }
        } catch (error) {
            console.error('Error loading categories:', error);
            return Promise.reject(error);
        }
    }

    // ฟังก์ชันสำหรับอัพเดต page title
    function updatePageTitle() {
        const pageTitle = document.querySelector('.page-title');
        const documentTitle = document.querySelector('title');

        if (currentCategory === 'all') {
            const titleText = 'สินค้าทั้งหมด';
            pageTitle.textContent = titleText;
            documentTitle.textContent = titleText;
        } else {
            // หาชื่อหมวดหมู่จาก categories array
            const category = categories.find(cat => cat.shoetype_id == currentCategory);
            if (category) {
                const titleText = `${category.name}`;
                pageTitle.textContent = titleText;
                documentTitle.textContent = titleText;
            } else {
                // กรณีไม่เจอหมวดหมู่ (อาจจะเป็น category ที่ไม่มีอยู่)
                const titleText = 'สินค้าทั้งหมด';
                pageTitle.textContent = titleText;
                documentTitle.textContent = titleText;
            }
        }
    }
    // ปรับปรุงฟังก์ชัน loadProducts
    async function loadProducts() {
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');

        try {
            loadingIndicator.style.display = 'block';
            errorMessage.style.display = 'none';

            const response = await fetch('controller/product_api.php?action=all');
            if (!response.ok) throw new Error('Failed to fetch products');

            const data = await response.json();

            if (data.error) {
                throw new Error(data.error);
            }

            if (Array.isArray(data)) {
                products = data;
                renderProducts();
                setupPagination();
            } else {
                throw new Error('Invalid data format');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            errorMessage.style.display = 'block';
            errorMessage.textContent = `เกิดข้อผิดพลาด: ${error.message}`;
        } finally {
            loadingIndicator.style.display = 'none';
        }
    }

    async function handleAddToCart(productId) {
        const button = document.querySelector(`button[onclick*="${productId}"]`);

        if (!button) {
            console.error('Button not found for product:', productId);
            return;
        }

        const originalText = button.textContent;

        try {
            // Set loading state
            button.disabled = true;
            button.textContent = 'กำลังเพิ่ม...';
            button.classList.add('loading');

            console.log('Attempting to add product to cart:', productId);

            // เรียกใช้ฟังก์ชัน addToCart จาก cart.js
            const success = await addToCart(productId, 1);

            if (success) {
                // Success - temporarily change button text
                button.textContent = '✓ เพิ่มแล้ว';
                button.style.background = '#27ae60';

                // Reset button after 2 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.style.background = '';
                    button.disabled = false;
                    button.classList.remove('loading');
                }, 2000);
            } else {
                // Failed - reset button immediately
                button.textContent = originalText;
                button.disabled = false;
                button.classList.remove('loading');
                button.style.background = '';
            }
        } catch (error) {
            console.error('Error in handleAddToCart:', error);

            // Reset button on error
            button.textContent = originalText;
            button.disabled = false;
            button.classList.remove('loading');
            button.style.background = '';

            // Show error notification
            if (typeof showNotification === 'function') {
                showNotification('เกิดข้อผิดพลาดในการเพิ่มสินค้า', 'error');
            }
        }
    }

    function createProductCard(product) {
        const imageSrc = product.img_path ? `controller/uploads/products/${product.img_path}` : '';
        const imageHTML = imageSrc ?
            `<img src="${imageSrc}" alt="${product.name}" onerror="this.style.display='none'">` :
            '';

        const stock = parseInt(product.stock);
        const stockClass = stock > 0 ? 'in-stock' : 'out-of-stock';
        const stockText = stock > 0 ? `คงเหลือ ${stock} ชิ้น` : 'สินค้าหมด';

        const isDisabled = stock <= 0 ? 'disabled' : '';
        const buttonText = stock <= 0 ? 'สินค้าหมด' : 'เพิ่มลงตะกร้า';

        return `
            <div class="product-card" data-category="${product.shoetype_id}" onclick="goToProductDetail('${product.shoe_id}')">
                <div class="product-image ${imageSrc ? 'has-image' : ''}">
                    ${imageHTML}
                </div>
                <div class="product-info">
                    <div class="product-name">${product.name}</div>
                    <div class="product-price">฿${parseFloat(product.price).toLocaleString()}</div>
                    <div class="product-stock ${stockClass}">${stockText}</div>
                    <button class="add-to-cart-btn" ${isDisabled} 
                            onclick="event.stopPropagation(); ${stock > 0 ? `handleAddToCart('${product.shoe_id}')` : ''}">
                        ${buttonText}
                    </button>
                </div>
            </div>
        `;
    }

    // Render products
    function renderProducts() {
        const grid = document.getElementById('productsGrid');

        let filteredProducts = products;
        if (currentCategory !== 'all') {
            filteredProducts = products.filter(p => p.shoetype_id == currentCategory);
        }

        if (filteredProducts.length === 0) {
            grid.innerHTML =
                '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">ไม่พบสินค้าในหมวดหมู่นี้</div>';
            return;
        }

        grid.innerHTML = filteredProducts.map(createProductCard).join('');
    }

    // Setup pagination
    function setupPagination() {
        const pagination = document.getElementById('pagination');
        if (products.length > 0) {
            pagination.style.display = 'flex';
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        // Pagination buttons
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.dataset.page !== 'prev' && this.dataset.page !== 'next') {
                    document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                }
            });
        });
    }

    // Navigate to product detail page
    function goToProductDetail(productId) {
        console.log('Navigating to product detail:', productId);
        window.location.href = `products-detail.php?id=${productId}`;
    }
    </script>
</body>

</html>