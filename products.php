<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สินค้าทั้งหมด</title>
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
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            position: relative;
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

        .product-image::before {
            content: '📦';
            font-size: 3rem;
            opacity: 0.3;
        }

        .product-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
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
        }

        .product-price {
            font-size: 1.2rem;
            font-weight: 700;
            color: #8e44ad;
            margin-bottom: 15px;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #8e44ad, #e74c3c);
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

        .add-to-cart-btn:active {
            transform: translateY(0);
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
    <div class="container" style="margin-top: 10px; margin-bottom:10px">
        <div class="main-content">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">หน้าแรก</a>
                <span>›</span>
                <span>สินค้า</span>
            </div>
            <h1 class="page-title">สินค้าทั้งหมด</h1>
            <!--
            <div class="filters">
                <button class="filter-btn active" data-category="all">ทั้งหมด</button>
                <button class="filter-btn" data-category="men">ผู้ชาย</button>
                <button class="filter-btn" data-category="women">ผู้หญิง</button>
                <button class="filter-btn" data-category="extra-size">Extra Size</button>
                <button class="filter-btn" data-category="divided">Divided</button>
                <button class="filter-btn" data-category="sport">Sport</button>
                <button class="filter-btn" data-category="bag">Bag</button>
                <button class="filter-btn" data-category="shoes">Shoes</button>
            </div>
    -->
            <div class="products-grid" id="productsGrid">
                <!-- Products will be generated by JavaScript -->
            </div>

            <div class="pagination">
                <button class="page-btn" data-page="prev">‹</button>
                <button class="page-btn active" data-page="1">1</button>
                <button class="page-btn" data-page="2">2</button>
                <button class="page-btn" data-page="3">3</button>
                <button class="page-btn" data-page="4">4</button>
                <button class="page-btn" data-page="next">›</button>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script>
        // Sample product data with header categories
        const products = [
            // ผู้ชาย
            { id: 1, name: 'เสื้อเชิ้ตผู้ชาย', price: '890', category: 'men', sale: true },
            { id: 2, name: 'กางเกงยีนส์ผู้ชาย', price: '1,290', category: 'men' },
            { id: 3, name: 'เสื้อยืดผู้ชาย', price: '590', category: 'men', new: true },
            
            // ผู้หญิง
            { id: 4, name: 'เดรสผู้หญิง', price: '1,590', category: 'women', new: true },
            { id: 5, name: 'เสื้อเบลาส์', price: '790', category: 'women', sale: true },
            { id: 6, name: 'กระโปรงยีนส์', price: '690', category: 'women' },
            
            // Extra Size
            { id: 7, name: 'เสื้อยืด Plus Size', price: '690', category: 'extra-size', new: true },
            { id: 8, name: 'กางเกง Plus Size', price: '1,490', category: 'extra-size' },
            
            // Divided
            { id: 9, name: 'เสื้อแฟชั่นหนุ่มสาว', price: '390', category: 'divided', sale: true },
            { id: 10, name: 'กางเกงขาสั้น Teen', price: '490', category: 'divided' },
            
            // Sport
            { id: 11, name: 'รองเท้าวิ่ง', price: '3,590', category: 'sport', new: true },
            { id: 12, name: 'เสื้อกีฬา', price: '890', category: 'sport' },
            { id: 13, name: 'กางเกงกีฬา', price: '690', category: 'sport', sale: true },
            
            // Bag
            { id: 14, name: 'กระเป๋าสะพาย', price: '1,990', category: 'bag' },
            { id: 15, name: 'กระเป๋าเป้', price: '2,490', category: 'bag', new: true },
            { id: 16, name: 'กระเป๋าถือ', price: '1,590', category: 'bag' },
            
            // Shoes
            { id: 17, name: 'รองเท้าหนัง', price: '2,990', category: 'shoes', sale: true },
            { id: 18, name: 'รองเท้าผ้าใบ', price: '1,890', category: 'shoes' },
            { id: 19, name: 'รองเท้าส้นสูง', price: '2,290', category: 'shoes', new: true }
        ];

        let currentCategory = 'all';
        let currentPage = 1;
        const itemsPerPage = 12;

        // Get category from URL parameters
        function getCategoryFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('category') || 'all';
        }

        // Set page title based on category
        function updatePageTitle(category) {
            const categoryNames = {
                'all': 'สินค้าทั้งหมด',
                'men': 'สินค้าผู้ชาย',
                'women': 'สินค้าผู้หญิง',
                'extra-size': 'Extra Size',
                'divided': 'Divided',
                'sport': 'Sport',
                'bag': 'Bag',
                'shoes': 'Shoes'
            };
            
            document.querySelector('.page-title').textContent = categoryNames[category] || 'สินค้าทั้งหมด';
        }

        function createProductCard(product) {
            const badges = [];
            if (product.sale) badges.push('<div class="sale-badge">SALE</div>');
            if (product.new) badges.push('<div class="new-badge">NEW</div>');

            return `
                <div class="product-card" data-category="${product.category}" onclick="goToProductDetail(${product.id})" style="cursor: pointer;">
                    ${badges.join('')}
                    <div class="product-image"></div>
                    <div class="product-info">
                        <div class="product-name">${product.name}</div>
                        <div class="product-price">฿${product.price}</div>
                        <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(${product.id})">
                            เพิ่มลงตะกร้า
                        </button>
                    </div>
                </div>
            `;
        }

        function renderProducts() {
            const grid = document.getElementById('productsGrid');
            const filteredProducts = currentCategory === 'all' 
                ? products 
                : products.filter(p => p.category === currentCategory);

            grid.innerHTML = filteredProducts.map(createProductCard).join('');
        }

        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            alert(`เพิ่ม "${product.name}" ลงตะกร้าแล้ว!`);
        }

        // Function to navigate to product detail page
        function goToProductDetail(productId) {
            // วิธีที่ 1: ใช้ไฟล์แยก (แนะนำ)
            window.location.href = `products-detail.php?id=${productId}`;
            
            // วิธีที่ 2: ใช้ localStorage (สำหรับการทดสอบ)
            // const product = products.find(p => p.id === productId);
            // localStorage.setItem('selectedProduct', JSON.stringify(product));
            // window.location.href = 'product-detail.html';
        }

        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Get category from URL and set initial state
            const urlCategory = getCategoryFromURL();
            currentCategory = urlCategory;
            updatePageTitle(currentCategory);

            const filterBtns = document.querySelectorAll('.filter-btn');
            const pageBtns = document.querySelectorAll('.page-btn');

            // Set active filter button based on URL category
            filterBtns.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.category === currentCategory);
            });

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentCategory = this.dataset.category;
                    updatePageTitle(currentCategory);
                    
                    // Update URL without refreshing page
                    const newUrl = currentCategory === 'all' 
                        ? window.location.pathname 
                        : `${window.location.pathname}?category=${currentCategory}`;
                    window.history.pushState({}, '', newUrl);
                    
                    renderProducts();
                });
            });

            pageBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    if (this.dataset.page !== 'prev' && this.dataset.page !== 'next') {
                        pageBtns.forEach(b => b.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });

            renderProducts();
        });
    </script>
</body>
</html>