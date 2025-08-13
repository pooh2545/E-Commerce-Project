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
            transition: background-color 0.3s;
        }

        .qty-btn:hover {
            background: #8e44ad;
        }

        .qty-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .qty-input {
            width: 50px;
            height: 35px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .item-total {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
            margin-left: 20px;
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 15px;
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
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background: #8e44ad;
        }

        .checkout-btn:disabled {
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
        }

        .loading {
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="#">หน้าแรก</a> > <span>ตะกร้าสินค้า</span>
        </div>

        <!-- Cart Header -->
        <h1 class="cart-header">ตะกร้าสินค้า</h1>

        <!-- Error Message -->
        <div id="error-message" class="error" style="display: none;"></div>

        <!-- Loading -->
        <div id="loading" class="loading">
            <p>กำลังโหลดข้อมูล...</p>
        </div>

        <!-- Empty Cart -->
        <div id="empty-cart" class="empty-cart" style="display: none;">
            <h3>ตะกร้าสินค้าของคุณว่างเปล่า</h3>
            <p>เริ่มเลือกซื้อสินค้าเพื่อเพิ่มลงในตะกร้า</p>
            <a href="#" class="continue-shopping">เริ่มช้อปปิ้ง</a>
        </div>

        <div class="cart-container" id="cart-container" style="display: none;">
            <!-- Cart Items -->
            <div class="cart-items" id="cart-items">
                <!-- Cart items will be loaded here -->
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

                    <button class="checkout-btn" id="checkout-btn">ชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>

    <script>
        function getMemberId() {
            try {
                const rawCookie = document.cookie;
                const memberIdStart = rawCookie.indexOf('member_id=');
                if (memberIdStart !== -1) {
                    const valueStart = memberIdStart + 'member_id='.length;
                    let valueEnd = rawCookie.indexOf(';', valueStart);
                    if (valueEnd === -1) {
                        valueEnd = rawCookie.length;
                    }
                    const memberId = rawCookie.substring(valueStart, valueEnd).trim();
                    return memberId;
                }
                return null;
            } catch (error) {
                return null;
            }
        }

        const MEMBER_ID = getMemberId();
        const SHIPPING_COST = 40;

        document.addEventListener('DOMContentLoaded', function() {
            if (!MEMBER_ID) {
                showError('กรุณาเข้าสู่ระบบก่อนดูตะกร้าสินค้า');
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 2000);
                return;
            }
            loadCart();
        });

        async function loadCart() {
            try {
                showLoading();
                const response = await fetch(`controller/cart_api.php?action=get&member_id=${MEMBER_ID}`);
                const data = await response.json();

                if (data.success) {
                    if (data.data && data.data.length > 0) {
                        displayCartItems(data.data);
                        // เรียกใช้ updateCartSummary หลังจาก DOM ถูกสร้างเสร็จแล้ว
                        setTimeout(() => {
                            updateCartSummary();
                        }, 100);
                        showCartContainer();
                    } else {
                        showEmptyCart();
                    }
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาดในการโหลดข้อมูล');
                }
            } catch (error) {
                console.error('Error loading cart:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
            }
        }

        function displayCartItems(items) {
            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = '';

            items.forEach(item => {
                // จัดการ image path
                let imageSrc = '';
                if (item.shoe_image) {
                    imageSrc = item.shoe_image;
                } else if (item.img_path) {
                    imageSrc = `controller/uploads/products/${item.img_path}`;
                }

                const cartItemHtml = `
            <div class="cart-item" data-cart-id="${item.cart_id}">
                <div class="item-image">
                    <img src="${imageSrc}" alt="${item.shoe_name || item.name || 'สินค้า'}" 
                         onerror="">
                </div>
                <div class="item-details">
                    <div class="item-name">${item.shoe_name || item.name || 'สินค้า'}</div>
                    <div class="item-description">${item.shoe_description || item.description || 'รายละเอียด'}</div>
                    <div class="item-price" data-price="${item.unit_price}">฿${formatNumber(item.unit_price)}</div>
                </div>
                <div class="quantity-controls">
                    <button class="qty-btn" onclick="decreaseQty(${item.cart_id})" ${item.quantity <= 1 ? 'disabled' : ''}>-</button>
                    <input type="number" class="qty-input" value="${item.quantity}" min="1" 
                           onchange="updateQuantity(${item.cart_id}, this.value)">
                    <button class="qty-btn" onclick="increaseQty(${item.cart_id})">+</button>
                </div>
                <div class="item-total">฿${formatNumber(item.unit_price * item.quantity)}</div>
                <button class="remove-btn" onclick="removeItem(${item.cart_id})" title="ลบสินค้า">
                    <svg viewBox="0 0 24 24">
                        <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                    </svg>
                </button>
            </div>
        `;
                cartItemsContainer.insertAdjacentHTML('beforeend', cartItemHtml);
            });
        }

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

                const data = await response.json();

                if (data.success) {
                    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                    const input = cartItem.querySelector('.qty-input');
                    const price = parseFloat(cartItem.querySelector('.item-price').dataset.price);

                    input.value = quantity;
                    cartItem.querySelector('.item-total').textContent = '฿' + formatNumber(price * quantity);

                    const decreaseBtn = cartItem.querySelector('.qty-btn');
                    decreaseBtn.disabled = quantity <= 1;

                    updateCartSummary();
                    showSuccess('อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาดในการอัปเดตจำนวนสินค้า');
                    loadCart();
                }
            } catch (error) {
                console.error('Error updating quantity:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                loadCart();
            } finally {
                disableControls(cartId, false);
            }
        }

        async function removeItem(cartId) {
            if (!confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าหรือไม่?')) {
                return;
            }

            try {
                disableControls(cartId, true);

                const response = await fetch(`controller/cart_api.php?action=remove&cart_id=${cartId}`, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (data.success) {
                    const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
                    cartItem.remove();

                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        showEmptyCart();
                    } else {
                        updateCartSummary();
                    }

                    showSuccess('ลบสินค้าเรียบร้อยแล้ว');
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
                }
            } catch (error) {
                console.error('Error removing item:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            } finally {
                disableControls(cartId, false);
            }
        }

        function updateCartSummary() {
            try {
                const cartItems = document.querySelectorAll('.cart-item');
                let totalItems = 0;
                let subtotal = 0;

                cartItems.forEach(item => {
                    const quantityInput = item.querySelector('.qty-input');
                    const priceElement = item.querySelector('.item-price');

                    // ตรวจสอบว่า elements มีอยู่จริงก่อนเข้าถึง
                    if (quantityInput && priceElement) {
                        const quantity = parseInt(quantityInput.value) || 0;
                        const price = parseFloat(priceElement.dataset.price) || 0;

                        totalItems += quantity;
                        subtotal += price * quantity;
                    }
                });

                const grandTotal = subtotal + SHIPPING_COST;

                // ตรวจสอบว่า elements มีอยู่ก่อนอัปเดต
                const itemsCountEl = document.getElementById('items-count');
                const subtotalEl = document.getElementById('subtotal');
                const grandTotalEl = document.getElementById('grand-total');
                const totalAmountEl = document.getElementById('total-amount');
                const checkoutBtn = document.getElementById('checkout-btn');

                if (itemsCountEl) {
                    itemsCountEl.textContent = `${totalItems} รายการ`;
                }
                if (subtotalEl) {
                    subtotalEl.textContent = '฿' + formatNumber(subtotal);
                }
                if (grandTotalEl) {
                    grandTotalEl.textContent = '฿' + formatNumber(grandTotal);
                }
                if (totalAmountEl) {
                    totalAmountEl.textContent = '฿' + formatNumber(grandTotal);
                }
                if (checkoutBtn) {
                    checkoutBtn.disabled = totalItems === 0;
                }
            } catch (error) {
                console.error('Error updating cart summary:', error);
            }
        }

        function formatNumber(num) {
            return new Intl.NumberFormat('th-TH').format(num);
        }

        function showLoading() {
            const loadingEl = document.getElementById('loading');
            const cartContainerEl = document.getElementById('cart-container');
            const emptyCartEl = document.getElementById('empty-cart');
            const errorEl = document.getElementById('error-message');

            if (loadingEl) loadingEl.style.display = 'block';
            if (cartContainerEl) cartContainerEl.style.display = 'none';
            if (emptyCartEl) emptyCartEl.style.display = 'none';
            if (errorEl) errorEl.style.display = 'none';
        }

        function showCartContainer() {
            const loadingEl = document.getElementById('loading');
            const cartContainerEl = document.getElementById('cart-container');
            const emptyCartEl = document.getElementById('empty-cart');
            const errorEl = document.getElementById('error-message');

            if (loadingEl) loadingEl.style.display = 'none';
            if (cartContainerEl) cartContainerEl.style.display = 'flex';
            if (emptyCartEl) emptyCartEl.style.display = 'none';
            if (errorEl) errorEl.style.display = 'none';
        }

        function showEmptyCart() {
            const loadingEl = document.getElementById('loading');
            const cartContainerEl = document.getElementById('cart-container');
            const emptyCartEl = document.getElementById('empty-cart');
            const errorEl = document.getElementById('error-message');

            if (loadingEl) loadingEl.style.display = 'none';
            if (cartContainerEl) cartContainerEl.style.display = 'none';
            if (emptyCartEl) emptyCartEl.style.display = 'block';
            if (errorEl) errorEl.style.display = 'none';
        }

        function showError(message) {
            const errorEl = document.getElementById('error-message');
            const loadingEl = document.getElementById('loading');

            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
            }
            if (loadingEl) {
                loadingEl.style.display = 'none';
            }

            setTimeout(() => {
                if (errorEl) {
                    errorEl.style.display = 'none';
                }
            }, 5000);
        }

        function showSuccess(message) {
            let successEl = document.getElementById('success-message');
            if (!successEl) {
                successEl = document.createElement('div');
                successEl.id = 'success-message';
                successEl.className = 'success';
                successEl.style.cssText = `
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: none;
        `;
                const container = document.querySelector('.container');
                const breadcrumb = document.querySelector('.breadcrumb');
                if (container && breadcrumb) {
                    container.insertBefore(successEl, breadcrumb);
                }
            }

            if (successEl) {
                successEl.textContent = message;
                successEl.style.display = 'block';

                setTimeout(() => {
                    successEl.style.display = 'none';
                }, 3000);
            }
        }

        function disableControls(cartId, disabled) {
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (cartItem) {
                const buttons = cartItem.querySelectorAll('button');
                const input = cartItem.querySelector('.qty-input');

                buttons.forEach(btn => btn.disabled = disabled);
                if (input) input.disabled = disabled;

                if (disabled) {
                    cartItem.style.opacity = '0.6';
                    cartItem.style.pointerEvents = 'none';
                } else {
                    cartItem.style.opacity = '1';
                    cartItem.style.pointerEvents = 'auto';
                }
            }
        }

        // Event listener สำหรับปุ่มชำระเงิน
        document.getElementById('checkout-btn')?.addEventListener('click', function() {
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                showError('ไม่มีสินค้าในตะกร้า');
                return;
            }

            // ไปหน้าชำระเงิน
            window.location.href = 'checkout.php';
        });
    </script>
</body>

</html>