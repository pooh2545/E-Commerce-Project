<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - Logo Store</title>
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

        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #9b59b6;
        }

        .cart-header {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }

        .cart-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .cart-items {
            flex: 2;
            min-width: 400px;
        }

        .cart-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: all 0.3s ease;
        }

        .cart-item.removing {
            opacity: 0.5;
            transform: scale(0.95);
        }

        .item-image {
            width: 80px;
            height: 80px;
            background-color: #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            overflow: hidden;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .item-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .item-price {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: #9b59b6;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .qty-btn:hover:not(:disabled) {
            background: #8e44ad;
            transform: scale(1.1);
        }

        .qty-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .qty-input {
            width: 50px;
            height: 35px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .qty-input:focus {
            border-color: #9b59b6;
            outline: none;
        }

        .item-total {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
            margin-left: 20px;
            min-width: 80px;
            text-align: right;
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 15px;
            transition: transform 0.3s;
        }

        .remove-btn:hover {
            transform: scale(1.2);
        }

        .remove-btn svg {
            width: 24px;
            height: 24px;
            fill: #e74c3c;
        }

        .cart-summary {
            flex: 1;
            min-width: 300px;
        }

        .summary-box {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            border-top: 2px solid #eee;
            padding-top: 15px;
            margin-top: 20px;
        }

        .discount {
            color: #27ae60;
        }

        .checkout-btn {
            width: 100%;
            background: #9b59b6;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .checkout-btn:hover:not(:disabled) {
            background: #8e44ad;
            transform: translateY(-2px);
        }

        .checkout-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .clear-cart-btn {
            width: 100%;
            background: #e74c3c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .clear-cart-btn:hover:not(:disabled) {
            background: #c0392b;
            transform: translateY(-2px);
        }

        .clear-cart-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .empty-cart h3 {
            margin-bottom: 15px;
            font-size: 24px;
        }

        .empty-cart p {
            margin-bottom: 20px;
        }

        .continue-shopping {
            background: #9b59b6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            transition: all 0.3s;
        }

        .continue-shopping:hover {
            background: #8e44ad;
            transform: translateY(-2px);
        }

        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #9b59b6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 2s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }

        .success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        .cart-actions {
            margin-top: 20px;
            text-align: right;
        }

        .cart-actions button {
            margin-left: 10px;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
            }

            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }

            .quantity-controls {
                justify-content: center;
            }

            .item-total {
                margin-left: 0;
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="index.php">หน้าแรก</a> > <span>ตะกร้าสินค้า</span>
        </div>

        <!-- Cart Header -->
        <h1 class="cart-header">ตะกร้าสินค้า</h1>

        <!-- Messages -->
        <div id="error-message" class="error" style="display: none;"></div>
        <div id="success-message" class="success" style="display: none;"></div>

        <!-- Loading -->
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>กำลังโหลดข้อมูล...</p>
        </div>

        <!-- Empty Cart -->
        <div id="empty-cart" class="empty-cart" style="display: none;">
            <h3>ตะกร้าสินค้าของคุณว่างเปล่า</h3>
            <p>เริ่มเลือกซื้อสินค้าเพื่อเพิ่มลงในตะกร้า</p>
            <a href="products.php" class="continue-shopping">เริ่มช้อปปิ้ง</a>
        </div>

        <div class="cart-container" id="cart-container" style="display: none;">
            <!-- Cart Items -->
            <div class="cart-items">
                <div id="cart-items">
                    <!-- Cart items will be loaded here -->
                </div>
            
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="summary-box">
                    <div class="summary-title">สรุปยอด</div>

                    <div class="summary-row">
                        <span id="items-count">0 รายการ</span>
                        <span id="subtotal">฿0</span>
                    </div>

                    <div class="summary-row">
                        <span>ค่าจัดส่ง</span>
                        <span id="shipping-cost">฿40</span>
                    </div>

                    <div class="summary-row discount">
                        <span>ยอดรวม</span>
                        <span id="grand-total">฿40</span>
                    </div>

                    <div class="summary-row total">
                        <span>ชำระเงิน</span>
                        <span id="total-amount">฿40</span>
                    </div>

                    <button class="checkout-btn" id="checkout-btn" disabled>
                        ดำเนินการชำระเงิน
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>

    <!-- Include cart.js -->
    <script src="assets/js/cart.js"></script>
    
    <script>
        // Constants
        const MEMBER_ID = getMemberId();
        const SHIPPING_COST = 40;
        let isLoading = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Cart page loaded, Member ID:', MEMBER_ID);
            
            if (!MEMBER_ID) {
                showError('กรุณาเข้าสู่ระบบก่อนดูตะกร้าสินค้า');
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
                return;
            }
            
            loadCart();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            const checkoutBtn = document.getElementById('checkout-btn');
            
            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', handleCheckout);
            }
            
        }

        // Load cart data
        async function loadCart() {
            if (isLoading) return;
            
            try {
                isLoading = true;
                showLoading();
                hideMessages();

                const response = await fetch(`controller/cart_api.php?action=get&member_id=${MEMBER_ID}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Cart data loaded:', data);

                if (data.success) {
                    if (data.data && data.data.length > 0) {
                        displayCartItems(data.data);
                        updateCartSummary();
                        showCartContainer();
                    } else {
                        showEmptyCart();
                    }
                } else {
                    throw new Error(data.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                showError('เกิดข้อผิดพลาดในการโหลดตะกร้า: ' + error.message);
            } finally {
                isLoading = false;
            }
        }

        // Display cart items
        function displayCartItems(items) {
            const cartItemsContainer = document.getElementById('cart-items');
            if (!cartItemsContainer) return;
            
            cartItemsContainer.innerHTML = '';

            items.forEach(item => {
                const cartItemElement = createCartItemElement(item);
                cartItemsContainer.appendChild(cartItemElement);
            });
        }

        // Create cart item element
        function createCartItemElement(item) {
            const div = document.createElement('div');
            div.className = 'cart-item';
            div.setAttribute('data-cart-id', item.cart_id);

            // Handle image
            let imageSrc = 'assets/images/no-image.png'; // default image
            if (item.shoe_image) {
                imageSrc = item.shoe_image;
            } else if (item.img_path) {
                imageSrc = `controller/uploads/products/${item.img_path}`;
            }

            const price = parseFloat(item.unit_price) || 0;
            const quantity = parseInt(item.quantity) || 1;
            const total = price * quantity;

            div.innerHTML = `
                <div class="item-image">
                    <img src="${imageSrc}" alt="${item.shoe_name || item.name || 'สินค้า'}" 
                         onerror="this.src='assets/images/no-image.png'">
                </div>
                <div class="item-details">
                    <div class="item-name">${item.shoe_name || item.name || 'สินค้า'}</div>
                    <div class="item-description">${item.shoe_description || item.description || 'รายละเอียด'}</div>
                    <div class="item-price" data-price="${price}">฿${formatNumber(price)}</div>
                </div>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick="decreaseQty('${item.cart_id}')" ${quantity <= 1 ? 'disabled' : ''}>
                        -
                    </button>
                    <input type="number" class="qty-input" value="${quantity}" min="1" 
                           onchange="updateQuantity('${item.cart_id}', this.value)"
                           onblur="validateQuantity(this, '${item.cart_id}')">
                    <button class="qty-btn" onclick="increaseQty('${item.cart_id}')">
                        +
                    </button>
                </div>
                <div class="item-total">฿${formatNumber(total)}</div>
                <button class="remove-btn" onclick="removeItem('${item.cart_id}')" title="ลบสินค้า">
                    <svg viewBox="0 0 24 24">
                        <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                    </svg>
                </button>
            `;

            return div;
        }

        // Quantity management functions
        async function increaseQty(cartId) {
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const input = cartItem.querySelector('.qty-input');
            const newQuantity = parseInt(input.value) + 1;
            await updateQuantity(cartId, newQuantity);
        }

        async function decreaseQty(cartId) {
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            const input = cartItem.querySelector('.qty-input');
            const currentQuantity = parseInt(input.value);

            if (currentQuantity > 1) {
                const newQuantity = currentQuantity - 1;
                await updateQuantity(cartId, newQuantity);
            }
        }

        function validateQuantity(input, cartId) {
            let quantity = parseInt(input.value);
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
                input.value = quantity;
            }
            updateQuantity(cartId, quantity);
        }

        async function updateQuantity(cartId, quantity) {
            if (quantity < 1) quantity = 1;

            try {
                disableControls(cartId, true);

                const response = await fetch(`controller/cart_api.php?action=update&cart_id=${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `quantity=${quantity}`
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                    if (cartItem) {
                        const input = cartItem.querySelector('.qty-input');
                        const priceElement = cartItem.querySelector('.item-price');
                        const totalElement = cartItem.querySelector('.item-total');
                        const decreaseBtn = cartItem.querySelector('.qty-btn');

                        if (input) input.value = quantity;
                        if (priceElement && totalElement) {
                            const price = parseFloat(priceElement.dataset.price);
                            totalElement.textContent = '฿' + formatNumber(price * quantity);
                        }
                        if (decreaseBtn) {
                            decreaseBtn.disabled = quantity <= 1;
                        }
                    }

                    updateCartSummary();
                    showSuccess('อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
                } else {
                    throw new Error(data.message || 'เกิดข้อผิดพลาดในการอัปเดตจำนวนสินค้า');
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
                showError('เกิดข้อผิดพลาดในการอัปเดต: ' + error.message);
                // Reload to ensure data consistency
                setTimeout(loadCart, 1000);
            } finally {
                disableControls(cartId, false);
            }
        }

        async function removeItem(cartId) {
            if (!confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าหรือไม่?')) {
                return;
            }

            try {
                const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (cartItem) {
                    cartItem.classList.add('removing');
                }

                disableControls(cartId, true);

                const response = await fetch(`controller/cart_api.php?action=remove&cart_id=${cartId}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    if (cartItem) {
                        cartItem.style.transition = 'all 0.5s ease';
                        cartItem.style.opacity = '0';
                        cartItem.style.transform = 'translateX(-100%)';
                        
                        setTimeout(() => {
                            cartItem.remove();
                            const remainingItems = document.querySelectorAll('.cart-item');
                            if (remainingItems.length === 0) {
                                showEmptyCart();
                            } else {
                                updateCartSummary();
                            }
                        }, 500);
                    }

                    showSuccess('ลบสินค้าเรียบร้อยแล้ว');
                    
                    // Update header cart count
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                } else {
                    throw new Error(data.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                showError('เกิดข้อผิดพลาดในการลบสินค้า: ' + error.message);
                
                // Remove removing class if error
                const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (cartItem) {
                    cartItem.classList.remove('removing');
                }
            } finally {
                disableControls(cartId, false);
            }
        }

        

        // Update cart summary
        function updateCartSummary() {
            try {
                const cartItems = document.querySelectorAll('.cart-item:not(.removing)');
                let totalItems = 0;
                let subtotal = 0;

                cartItems.forEach(item => {
                    const quantityInput = item.querySelector('.qty-input');
                    const priceElement = item.querySelector('.item-price');

                    if (quantityInput && priceElement) {
                        const quantity = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(priceElement.dataset.price) || 0;

                        totalItems += quantity;
                        subtotal += price * quantity;
                    }
                });

                const grandTotal = subtotal + SHIPPING_COST;

                // Update summary elements
                updateElement('items-count', `${totalItems} รายการ`);
                updateElement('subtotal', '฿' + formatNumber(subtotal));
                updateElement('grand-total', '฿' + formatNumber(grandTotal));
                updateElement('total-amount', '฿' + formatNumber(grandTotal));

                // Update buttons
                const checkoutBtn = document.getElementById('checkout-btn');
                
                if (checkoutBtn) {
                    checkoutBtn.disabled = totalItems === 0;
                }
            } catch (error) {
                console.error('Error updating cart summary:', error);
            }
        }

        // Checkout handler
        function handleCheckout() {
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                showError('ไม่มีสินค้าในตะกร้า');
                return;
            }

            // Redirect to checkout page
            window.location.href = 'checkout.php?from=cart';
        }

        // Utility functions
        function updateElement(id, content) {
            const element = document.getElementById(id);
            if (element) {
                element.textContent = content;
            }
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('th-TH').format(num);
        }

        function disableControls(cartId, disabled) {
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (!cartItem) return;

            const buttons = cartItem.querySelectorAll('button');
            const input = cartItem.querySelector('.qty-input');

            buttons.forEach(btn => btn.disabled = disabled);
            if (input) input.disabled = disabled;

            cartItem.style.opacity = disabled ? '0.6' : '1';
            cartItem.style.pointerEvents = disabled ? 'none' : 'auto';
        }

        // Display state functions
        function showLoading() {
            showElement('loading');
            hideElement('cart-container');
            hideElement('empty-cart');
        }

        function showCartContainer() {
            hideElement('loading');
            showElement('cart-container');
            hideElement('empty-cart');
        }

        function showEmptyCart() {
            hideElement('loading');
            hideElement('cart-container');
            showElement('empty-cart');
        }

        function showElement(id) {
            const element = document.getElementById(id);
            if (element) {
                element.style.display = element.id === 'cart-container' ? 'flex' : 'block';
            }
        }

        function hideElement(id) {
            const element = document.getElementById(id);
            if (element) {
                element.style.display = 'none';
            }
        }

        // Message functions
        function showError(message) {
            const errorEl = document.getElementById('error-message');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
                errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            hideElement('loading');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideElement('error-message');
            }, 5000);
        }

        function showSuccess(message) {
            const successEl = document.getElementById('success-message');
            if (successEl) {
                successEl.textContent = message;
                successEl.style.display = 'block';
                successEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
            
            // Auto hide after 3 seconds
            setTimeout(() => {
                hideElement('success-message');
            }, 3000);
        }

        function hideMessages() {
            hideElement('error-message');
            hideElement('success-message');
        }

        // Auto-refresh cart every 30 seconds to sync with other tabs
        let autoRefreshInterval;
        
        function startAutoRefresh() {
            autoRefreshInterval = setInterval(() => {
                if (!isLoading && MEMBER_ID) {
                    console.log('Auto-refreshing cart...');
                    loadCart();
                }
            }, 30000); // 30 seconds
        }

        function stopAutoRefresh() {
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }
        }

        // Start auto refresh when page is visible
        /*document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                stopAutoRefresh();
            } else {
                startAutoRefresh();
            }
        });

        // Start auto refresh on load
        if (MEMBER_ID) {
            startAutoRefresh();
        }*/

        // Handle page unload
        /*window.addEventListener('beforeunload', function() {
            stopAutoRefresh();
        });*/

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + R or F5 to refresh cart
            if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
                e.preventDefault();
                loadCart();
            }
        });

        // Handle network errors gracefully
        window.addEventListener('online', function() {
            console.log('Connection restored, refreshing cart...');
            loadCart();
        });

        window.addEventListener('offline', function() {
            showError('การเชื่อมต่ออินเทอร์เน็ตขาดหาย กรุณาตรวจสอบการเชื่อมต่อ');
        });
    </script>
</body>

</html>