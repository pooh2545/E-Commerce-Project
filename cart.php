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

    html{
        height: 100%;
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
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
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

    /* Stock warning styles */
    .stock-warning {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-top: 5px;
        display: inline-block;
    }

    .stock-warning.out-of-stock {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .stock-warning.low-stock {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fbbf24;
    }

    .stock-warning.limited-stock {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #7dd3fc;
    }

    /* Cart item states */
    .cart-item.out-of-stock-item {
        opacity: 0.6;
        background-color: #f9f9f9;
    }

    .cart-item.out-of-stock-item .item-total.out-of-stock {
        text-decoration: line-through;
        color: #dc2626;
    }

    .cart-item.low-stock-item {
        border-left: 3px solid #d97706;
        background-color: #fffbeb;
    }

    /* Disabled button styles for stock control */
    .qty-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        background-color: #f3f4f6;
        color: #9ca3af;
    }

    .qty-input:disabled {
        background-color: #f9fafb;
        color: #6b7280;
        cursor: not-allowed;
    }

    /* Checkout button disabled state */
    #checkout-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        background-color: #d1d5db;
        color: #6b7280;
    }

    /* Animation for removing items */
    .cart-item.removing {
        transition: all 0.5s ease;
        opacity: 0;
        transform: translateX(-100%);
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

        .stock-warning {
            font-size: 11px;
            padding: 3px 6px;
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

    // Create cart item element with stock check
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

        // เช็ค stock จากข้อมูลที่ได้จาก API
        const stock = item.stock || 0;
        const isOutOfStock = stock === 0;
        const isLowStock = stock > 0 && stock < quantity;
        const isMaxQuantity = quantity >= stock;

        // สร้าง stock warning message
        let stockWarning = '';
        if (isOutOfStock) { 
            stockWarning = '<div class="stock-warning out-of-stock">สินค้าหมด</div>';
        } else if (isLowStock) {
            stockWarning = `<div class="stock-warning low-stock">มีสินค้าเหลือเพียง ${stock} ชิ้น</div>`;
        } else if (stock <= 5) {
            stockWarning = `<div class="stock-warning limited-stock">เหลือ ${stock} ชิ้น</div>`;
        }

        div.innerHTML = `
        <div class="item-image">
            <img src="${imageSrc}" alt="${item.shoe_name || item.name || 'สินค้า'}" 
                 onerror="this.src='assets/images/no-image.png'">
        </div>
        <div class="item-details">
            <div class="item-name">${item.shoe_name || item.name || 'สินค้า'}</div>
            <div class="item-description">${item.shoe_description || item.description || 'รายละเอียด'}</div>
            <div class="item-price" data-price="${price}">฿${formatNumber(price)}</div>
            ${stockWarning}
        </div>
        <div class="quantity-controls">
            <button class="qty-btn decrease" onclick="decreaseQty('${item.cart_id}')" 
                    ${quantity <= 1 || isOutOfStock ? 'disabled' : ''}>
                -
            </button>
            <input type="number" class="qty-input" value="${quantity}" min="1" max="${stock}"
                   onchange="updateQuantity('${item.cart_id}', this.value)"
                   onblur="validateQuantity(this, '${item.cart_id}', ${stock})"
                   ${isOutOfStock ? 'disabled' : ''}>
            <button class="qty-btn increase" onclick="increaseQty('${item.cart_id}')" 
                    ${isMaxQuantity || isOutOfStock ? 'disabled' : ''}>
                +
            </button>
        </div>
        <div class="item-total ${isOutOfStock ? 'out-of-stock' : ''}">฿${formatNumber(total)}</div>
        <button class="remove-btn" onclick="removeItem('${item.cart_id}')" title="ลบสินค้า">
            <svg viewBox="0 0 24 24">
                <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
            </svg>
        </button>
    `;

        // เพิ่ม class สำหรับสินค้าที่หมดสต็อก
        if (isOutOfStock) {
            div.classList.add('out-of-stock-item');
        } else if (isLowStock) {
            div.classList.add('low-stock-item');
        }

        return div;
    }

    // Enhanced quantity management functions with stock check
    async function increaseQty(cartId) {
        const input = document.querySelector(`[data-cart-id="${cartId}"] .qty-input`);
        const increaseBtn = input?.querySelector('.qty-btn.increase');

        if (!input ) {
            console.error(`Elements not found for cart id ${cartId}`);
            return;
        }

        const currentQuantity = parseInt(input.value) || 1;
        const maxStock = parseInt(input.getAttribute('max')) || 0;

        if (currentQuantity >= maxStock) {
            showError(`ไม่สามารถเพิ่มได้เนื่องจากมีสินค้าเหลือเพียง ${maxStock} ชิ้น`);
            return;
        }

        const newQuantity = currentQuantity + 1;
        input.value = newQuantity;
        await updateQuantity(cartId, newQuantity);
    }

    async function decreaseQty(cartId) {
        const input = document.querySelector(`[data-cart-id="${cartId}"] .qty-input`);

        if (!input) {
            console.error(`Input not found for cart id ${cartId}`);
            return;
        }

        const currentQuantity = parseInt(input.value) || 1;

        if (currentQuantity > 1) {
            const newQuantity = currentQuantity - 1;
            input.value = newQuantity;
            await updateQuantity(cartId, newQuantity);
        }
    }

    function validateQuantity(input, cartId, maxStock = null) {
        let quantity = parseInt(input.value);

        if (isNaN(quantity) || quantity < 1) {
            quantity = 1;
        }

        // เช็ค stock limit ถ้ามี
        if (maxStock !== null && quantity > maxStock) {
            quantity = maxStock;
            showError(`จำนวนสูงสุดที่สามารถสั่งซื้อได้คือ ${maxStock} ชิ้น`);
        }

        input.value = quantity;
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
                // อัพเดทข้อมูลใหม่จาก API response ที่มี stock ล่าสุด
                if (data.item_data) {
                    updateCartItemDisplay(cartId, data.item_data, quantity);
                } else {
                    // ถ้าไม่มี item_data ให้ reload cart เพื่อให้ได้ข้อมูล stock ล่าสุด
                    loadCart();
                }

                updateCartCount();
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

    // Helper function to update cart item display with stock check
    function updateCartItemDisplay(cartId, itemData, quantity) {
        const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
        if (!cartItem) return;

        const input = cartItem.querySelector('.qty-input');
        const priceElement = cartItem.querySelector('.item-price');
        const totalElement = cartItem.querySelector('.item-total');
        const decreaseBtn = cartItem.querySelector('.qty-btn.decrease');
        const increaseBtn = cartItem.querySelector('.qty-btn.increase');

        const stock = parseInt(itemData.stock) || 0;
        const isOutOfStock = stock === 0;
        const isMaxQuantity = quantity >= stock;

        if (input) {
            input.value = quantity;
            input.setAttribute('max', stock);
            input.disabled = isOutOfStock;
        }

        if (priceElement && totalElement) {
            const price = parseFloat(priceElement.dataset.price);
            totalElement.textContent = '฿' + formatNumber(price * quantity);

            // เพิ่ม/ลบ class สำหรับสินค้าหมดสต็อก
            if (isOutOfStock) {
                totalElement.classList.add('out-of-stock');
            } else {
                totalElement.classList.remove('out-of-stock');
            }
        }

        // อัพเดทสถานะปุ่ม
        if (decreaseBtn) {
            decreaseBtn.disabled = quantity <= 1 || isOutOfStock;
        }

        if (increaseBtn) {
            increaseBtn.disabled = isMaxQuantity || isOutOfStock;
        }

        // อัพเดท stock warning
        updateStockWarning(cartItem, stock, quantity, isOutOfStock);

        // อัพเดท CSS class ของ cart item
        cartItem.classList.toggle('out-of-stock-item', isOutOfStock);
        cartItem.classList.toggle('low-stock-item', stock > 0 && stock < quantity);
    }

    // Helper function to update stock warning
    function updateStockWarning(cartItem, stock, quantity, isOutOfStock) {
        let existingWarning = cartItem.querySelector('.stock-warning');
        if (existingWarning) {
            existingWarning.remove();
        }

        let warningHTML = '';
        if (isOutOfStock) {
            warningHTML = '<div class="stock-warning out-of-stock">สินค้าหมด</div>';
        } else if (stock > 0 && stock < quantity) {
            warningHTML = `<div class="stock-warning low-stock">มีสินค้าเหลือเพียง ${stock} ชิ้น</div>`;
        } else if (stock <= 5) {
            warningHTML = `<div class="stock-warning limited-stock">เหลือ ${stock} ชิ้น</div>`;
        }

        if (warningHTML) {
            const itemDetails = cartItem.querySelector('.item-details');
            if (itemDetails) {
                itemDetails.insertAdjacentHTML('beforeend', warningHTML);
            }
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
                        location.reload();
                    }, 1000);
                }

                showSuccess('ลบสินค้าเรียบร้อยแล้ว');

                if (typeof updateCartCount === 'function') {
                    updateCartCount();
                }
            } else {
                throw new Error(data.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            showError('เกิดข้อผิดพลาดในการลบสินค้า: ' + error.message);

            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (cartItem) {
                cartItem.classList.remove('removing');
            }
        } finally {
            disableControls(cartId, false);
        }
    }

    // Update cart summary with stock validation
    function updateCartSummary() {
        try {
            const cartItems = document.querySelectorAll('.cart-item:not(.removing)');
            let totalItems = 0;
            let subtotal = 0;
            let hasOutOfStockItems = false;

            cartItems.forEach(item => {
                const quantityInput = item.querySelector('.qty-input');
                const priceElement = item.querySelector('.item-price');
                const isOutOfStock = item.classList.contains('out-of-stock-item');

                if (quantityInput && priceElement && !isOutOfStock) {
                    const quantity = parseInt(quantityInput.value) || 0;
                    const price = parseFloat(priceElement.dataset.price) || 0;

                    totalItems += quantity;
                    subtotal += price * quantity;
                }

                if (isOutOfStock) {
                    hasOutOfStockItems = true;
                }
            });

            const grandTotal = subtotal + SHIPPING_COST;

            // Update summary elements
            updateElement('items-count', `${totalItems} รายการ`);
            updateElement('subtotal', '฿' + formatNumber(subtotal));
            updateElement('grand-total', '฿' + formatNumber(grandTotal));
            updateElement('total-amount', '฿' + formatNumber(grandTotal));

            // Update buttons - disable if no items or has out of stock items
            const checkoutBtn = document.getElementById('checkout-btn');
            if (checkoutBtn) {
                const shouldDisable = totalItems === 0 || hasOutOfStockItems;
                checkoutBtn.disabled = shouldDisable;

                // แสดงข้อความแจ้งเตือนถ้ามีสินค้าหมดสต็อก
                if (hasOutOfStockItems && totalItems > 0) {
                    showError('ไม่สามารถสั่งซื้อได้เนื่องจากมีสินค้าหมดสต็อก กรุณาลบสินค้าที่หมดสต็อกออกก่อน');
                }
            }
        } catch (error) {
            console.error('Error updating cart summary:', error);
        }
    }

    // Enhanced checkout handler with stock validation
    function handleCheckout() {
        const cartItems = document.querySelectorAll('.cart-item');
        const outOfStockItems = document.querySelectorAll('.cart-item.out-of-stock-item');

        if (cartItems.length === 0) {
            showError('ไม่มีสินค้าในตะกร้า');
            return;
        }

        if (outOfStockItems.length > 0) {
            showError('ไม่สามารถสั่งซื้อได้เนื่องจากมีสินค้าหมดสต็อก กรุณาลบสินค้าที่หมดสต็อกออกก่อน');
            return;
        }

        // Additional validation: check for low stock warnings
        const lowStockItems = document.querySelectorAll('.cart-item.low-stock-item');
        if (lowStockItems.length > 0) {
            if (!confirm('มีสินค้าบางรายการที่คุณสั่งมากกว่าที่มีในสต็อก ต้องการดำเนินการต่อหรือไม่?')) {
                return;
            }
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
            errorEl.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
        hideElement('loading');

        setTimeout(() => {
            hideElement('error-message');
        }, 5000);
    }

    function showSuccess(message) {
        const successEl = document.getElementById('success-message');
        if (successEl) {
            successEl.textContent = message;
            successEl.style.display = 'block';
            successEl.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }

        setTimeout(() => {
            hideElement('success-message');
        }, 3000);
    }

    function hideMessages() {
        hideElement('error-message');
        hideElement('success-message');
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
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