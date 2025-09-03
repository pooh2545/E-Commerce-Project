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

        .main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
        }

        .main-image::before {
            content: '📷';
            font-size: 4rem;
            opacity: 0.3;
        }

        .main-image.has-image::before {
            display: none;
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
            z-index: 2;
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

        .product-details {
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .detail-label {
            font-weight: 600;
            width: 120px;
            color: #555;
        }

        .detail-value {
            color: #333;
        }

        .stock-info {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .stock-available {
            color: #27ae60;
            font-weight: 600;
        }

        .stock-out {
            color: #e74c3c;
            font-weight: 600;
        }

        .product-description {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .description-title {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }

        .description-text {
            line-height: 1.6;
            color: #666;
        }

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

        .qty-btn:disabled {
            background: #e9ecef;
            color: #adb5bd;
            cursor: not-allowed;
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
            background: #752092;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
        }

        .add-to-cart:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(142, 68, 173, 0.4);
        }

        .add-to-cart:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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
            position: relative;
        }

        .related-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .related-image::before {
            content: '📦';
            font-size: 2.5rem;
            opacity: 0.3;
        }

        .related-image.has-image::before {
            display: none;
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
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
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
            background: #752092;
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
        }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container" style="margin-top: 10px; margin-bottom:10px">
        <div class="main-content">
            <!-- Loading indicator -->
            <div id="loadingIndicator" class="loading">
                กำลังโหลดข้อมูลสินค้า...
            </div>

            <!-- Error message -->
            <div id="errorMessage" class="error-message" style="display: none;">
                เกิดข้อผิดพลาดในการโหลดข้อมูล กรุณาลองใหม่อีกครั้ง
            </div>

            <!-- Main content (will be populated by JavaScript) -->
            <div id="productContent" style="display: none;">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="index.php">หน้าแรก</a>
                    <span>›</span>
                    <a href="products.php">สินค้า</a>
                    <span>›</span>
                    <span>รายละเอียด</span>
                </div>

                <!-- Product Detail Section -->
                <div class="product-detail">
                    <div class="product-image-section">
                        <div class="main-image" id="productImage">
                            <div class="zoom-overlay">🔍 ซูม</div>
                        </div>
                    </div>

                    <div class="product-info">
                        <h1 class="product-title" id="productTitle">ชื่อสินค้า</h1>

                        <div class="product-price" id="productPrice">
                            ฿0 <span class="price-currency">บาท</span>
                        </div>

                        <div class="product-details" id="productDetails">
                            <!-- Product details will be populated here -->
                        </div>

                        <div class="stock-info" id="stockInfo">
                            <!-- Stock information will be populated here -->
                        </div>

                        <div class="product-description" id="productDescription" style="display: none;">
                            <div class="description-title">รายละเอียด</div>
                            <div class="description-text" id="descriptionText"></div>
                        </div>

                        <div class="quantity-selector">
                            <span class="quantity-label">จำนวน:</span>
                            <div class="quantity-controls">
                                <button class="qty-btn" onclick="decreaseQty()" id="decreaseBtn">-</button>
                                <input type="number" class="qty-input" value="1" min="1" id="quantity">
                                <button class="qty-btn" onclick="increaseQty()" id="increaseBtn">+</button>
                            </div>
                        </div>

                        <button class="add-to-cart" onclick="HandleAddToCart()" id="addToCartBtn">เพิ่มลงตะกร้า</button>
                    </div>
                </div>

                <!-- Related Products Section -->
                <div class="related-products">
                    <h2 class="section-title">รายการที่คล้ายกัน</h2>
                    <div class="related-grid" id="relatedGrid">
                        <!-- Related products will be populated here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script src="assets/js/cart.js"></script>
    <script>
        let currentProduct = null;
        let allProducts = [];
        let maxStock = 0;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            const productId = getProductIdFromURL();
            if (productId) {
                loadProductData(productId);
                loadAllProducts();
            } else {
                showError('ไม่พบรหัสสินค้า');
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
        });

        function getProductIdFromURL() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('id');
        }

        async function loadProductData(productId) {
            const loadingIndicator = document.getElementById('loadingIndicator');
            const errorMessage = document.getElementById('errorMessage');
            const productContent = document.getElementById('productContent');

            try {
                loadingIndicator.style.display = 'block';
                errorMessage.style.display = 'none';
                productContent.style.display = 'none';

                const response = await fetch(`controller/product_api.php?action=get&id=${productId}`);
                if (!response.ok) throw new Error('Failed to fetch product data');

                const data = await response.json();

                if (data.error) {
                    throw new Error(data.error);
                }

                currentProduct = data;
                maxStock = parseInt(currentProduct.stock);
                renderProductData();
                productContent.style.display = 'block';

            } catch (error) {
                console.error('Error loading product:', error);
                showError(`เกิดข้อผิดพลาด: ${error.message}`);
            } finally {
                loadingIndicator.style.display = 'none';
            }
        }

        async function loadAllProducts() {
            try {
                const response = await fetch('controller/product_api.php?action=all');
                if (!response.ok) throw new Error('Failed to fetch products');

                const data = await response.json();
                if (Array.isArray(data)) {
                    allProducts = data;
                    renderRelatedProducts();
                }
            } catch (error) {
                console.error('Error loading related products:', error);
            }
        }

        function renderProductData() {
            if (!currentProduct) return;

            document.getElementById('productTitle').textContent = currentProduct.name;
            document.title = `${currentProduct.name} - รายละเอียดสินค้า`;

            const priceElement = document.getElementById('productPrice');
            priceElement.innerHTML = `฿${parseFloat(currentProduct.price).toLocaleString()} <span class="price-currency">บาท</span>`;

            const imageElement = document.getElementById('productImage');
            if (currentProduct.img_path) {
                const imgSrc = `controller/uploads/products/${currentProduct.img_path}`;
                imageElement.innerHTML = `
                <img src="${imgSrc}" alt="${currentProduct.name}" onerror="this.parentElement.classList.remove('has-image')">
                <div class="zoom-overlay">🔍 ซูม</div>
            `;
                imageElement.classList.add('has-image');
            }

            const detailsElement = document.getElementById('productDetails');
            detailsElement.innerHTML = `
            <div class="detail-item">
                <span class="detail-label">ขนาด:</span>
                <span class="detail-value">${currentProduct.size || 'ไม่ระบุ'}</span>
            </div>
        `;

            const stockElement = document.getElementById('stockInfo');
            const stock = parseInt(currentProduct.stock);
            if (stock > 0) {
                stockElement.innerHTML = `<div class="stock-available">✅ มีสินค้าในสต็อก (${stock} ชิ้น)</div>`;
            } else {
                stockElement.innerHTML = `<div class="stock-out">❌ สินค้าหมด</div>`;
            }

            if (currentProduct.detail && currentProduct.detail.trim()) {
                const descriptionElement = document.getElementById('productDescription');
                document.getElementById('descriptionText').textContent = currentProduct.detail;
                descriptionElement.style.display = 'block';
            }

            updateButtonsState();
        }

        function updateButtonsState() {
            const stock = parseInt(currentProduct.stock);
            const addToCartBtn = document.getElementById('addToCartBtn');
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            const quantityInput = document.getElementById('quantity');

            if (stock <= 0) {
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'สินค้าหมด';
                decreaseBtn.disabled = true;
                increaseBtn.disabled = true;
                quantityInput.disabled = true;
                quantityInput.value = 0;
            } else {
                addToCartBtn.disabled = false;
                addToCartBtn.textContent = 'เพิ่มลงตะกร้า';
                decreaseBtn.disabled = false;
                increaseBtn.disabled = false;
                quantityInput.disabled = false;
                quantityInput.max = stock;
            }
        }

        function renderRelatedProducts() {
            const relatedGrid = document.getElementById('relatedGrid');

            if (!currentProduct) {
                console.error('No current product for related products');
                relatedGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">ไม่สามารถแสดงสินค้าที่เกี่ยวข้องได้</div>';
                return;
            }

            if (!allProducts || allProducts.length === 0) {
                console.error('No products data for related products');
                relatedGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">กำลังโหลดสินค้าที่เกี่ยวข้อง...</div>';
                return;
            }

            try {
                let relatedProducts = allProducts.filter(product => {
                    if (!product || !product.shoe_id) return false;

                    const currentId = currentProduct.shoe_id || currentProduct.id;
                    if (product.shoe_id === currentId) return false;

                    if (currentProduct.shoetype_id && product.shoetype_id) {
                        return product.shoetype_id === currentProduct.shoetype_id;
                    }

                    return true;
                });

                if (relatedProducts.length < 4) {
                    const otherProducts = allProducts.filter(product => {
                        if (!product || !product.shoe_id) return false;

                        const currentId = currentProduct.shoe_id || currentProduct.id;
                        if (product.shoe_id === currentId) return false;

                        return !relatedProducts.some(rp => rp.shoe_id === product.shoe_id);
                    });

                    relatedProducts = [...relatedProducts, ...otherProducts];
                }

                relatedProducts = relatedProducts
                    .sort(() => Math.random() - 0.5)
                    .slice(0, 4);

                if (relatedProducts.length === 0) {
                    relatedGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">ไม่พบสินค้าที่เกี่ยวข้อง</div>';
                    return;
                }

                relatedGrid.innerHTML = relatedProducts.map((product, index) => {
                    const imageSrc = product.img_path && product.img_path.trim() ?
                        `controller/uploads/products/${product.img_path}` : '';

                    const imageHTML = imageSrc ?
                        `<img src="${imageSrc}" alt="${product.name || 'สินค้า'}" 
                  onerror="this.style.display='none'; console.error('Related product image failed:', '${imageSrc}');"
                  onload="console.log('Related product image loaded:', '${imageSrc}');">` : '';

                    const price = parseFloat(product.price) || 0;
                    const productId = product.shoe_id;

                    return `
                    <div class="related-card" onclick="viewProduct('${productId}')" 
                         style="animation-delay: ${index * 0.1}s">
                        <div class="related-image ${imageSrc ? 'has-image' : ''}">
                            ${imageHTML}
                        </div>
                        <div class="related-info">
                            <div class="related-name">${product.name || 'สินค้า'}</div>
                            <div class="related-price">฿${price.toLocaleString()}</div>
                            <button class="related-btn" onclick="event.stopPropagation(); viewProduct('${productId}')">
                                ดูรายละเอียด  
                            </button>
                        </div>
                    </div>
                `;
                }).join('');

                console.log(`Related products rendered successfully: ${relatedProducts.length} products`);

            } catch (error) {
                console.error('Error rendering related products:', error);
                relatedGrid.innerHTML = '<div style="text-align: center; padding: 40px; color: #666; grid-column: 1/-1;">เกิดข้อผิดพลาดในการแสดงสินค้าที่เกี่ยวข้อง</div>';
            }
        }

        function showError(message) {
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
        }

        function increaseQty() {
            const qtyInput = document.getElementById('quantity');
            const currentValue = parseInt(qtyInput.value);
            if (currentValue < maxStock) {
                qtyInput.value = currentValue + 1;
            }
        }

        function decreaseQty() {
            const qtyInput = document.getElementById('quantity');
            const currentValue = parseInt(qtyInput.value);
            if (currentValue > 1) {
                qtyInput.value = currentValue - 1;
            }
        }

        // แก้ไขฟังก์ชัน addToCart ให้เชื่อมต่อกับ cart.js
        async function HandleAddToCart() {
            if (!currentProduct) return;

            const quantity = parseInt(document.getElementById('quantity').value);
            if (quantity <= 0 || quantity > maxStock) {
                if (typeof showNotification === 'function') {
                    showNotification('จำนวนสินค้าไม่ถูกต้อง', 'warning');
                } else {
                    alert('จำนวนสินค้าไม่ถูกต้อง');
                }
                return;
            }

            const button = document.getElementById('addToCartBtn');
            const originalText = button.textContent;

            try {
                // Set loading state
                button.disabled = true;
                button.textContent = 'กำลังเพิ่ม...';
                button.style.opacity = '0.7';

                console.log('Attempting to add product to cart:', currentProduct.shoe_id, 'quantity:', quantity);

                // เรียกใช้ฟังก์ชัน addToCart จาก cart.js
                const success = await window.addToCart(currentProduct.shoe_id, quantity);

                if (success) {
                    // Success - temporarily change button text
                    button.textContent = '✓ เพิ่มแล้ว';
                    button.style.background = '#27ae60';
                    button.style.opacity = '1';

                    // Reset button after 3 seconds
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.style.background = '';
                        button.disabled = false;
                        button.style.opacity = '1';
                    }, 3000);
                } else {
                    // Failed - reset button immediately
                    button.textContent = originalText;
                    button.disabled = false;
                    button.style.background = '';
                    button.style.opacity = '1';
                }
            } catch (error) {
                console.error('Error in addToCart:', error);

                // Reset button on error
                button.textContent = originalText;
                button.disabled = false;
                button.style.background = '';
                button.style.opacity = '1';

                // Show error notification
                if (typeof showNotification === 'function') {
                    showNotification('เกิดข้อผิดพลาดในการเพิ่มสินค้า: ' + error.message, 'error');
                } else {
                    alert('เกิดข้อผิดพลาดในการเพิ่มสินค้า: ' + error.message);
                }
            }
        }

        function viewProduct(productId) {
            console.log('Navigating to product detail:', productId);
            window.location.href = `products-detail.php?id=${productId}`;
        }

        // Zoom functionality
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('zoom-overlay')) {
                alert('เปิดโหมดซูมรูปภาพ (สามารถพัฒนาเพิ่มเติมได้)');
            }
        });

        // Validate quantity input
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const quantityInput = document.getElementById('quantity');
                if (quantityInput) {
                    quantityInput.addEventListener('change', function() {
                        let value = parseInt(this.value);
                        if (isNaN(value) || value < 1) {
                            this.value = 1;
                        } else if (value > maxStock) {
                            this.value = maxStock;
                            if (typeof showNotification === 'function') {
                                showNotification(`จำนวนสูงสุดที่สามารถซื้อได้คือ ${maxStock} ชิ้น`, 'warning');
                            } else {
                                alert(`จำนวนสูงสุดที่สามารถซื้อได้คือ ${maxStock} ชิ้น`);
                            }
                        }
                    });
                }
            }, 1000);
        });
    </script>
</body>

</html>