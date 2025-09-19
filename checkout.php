<?php
require_once 'controller/config.php';
require_once 'controller/auth_check.php';

redirectIfNotLoggedIn(); // จะ redirect ไป login.php ถ้ายังไม่ login

$auth = new auth_check($pdo);
$cartItems = $auth->redirectIfNoItemCart('cart.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลการจัดส่ง - Logo Store</title>
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
        padding: 20px;
        margin-top: 50px;
        margin-bottom: 50px;
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

    .checkout-header {
        font-size: 28px;
        margin-bottom: 30px;
        color: #333;
        font-weight: bold;
    }

    .checkout-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .checkout-main {
        flex: 2;
        min-width: 400px;
    }

    .checkout-section {
        background: white;
        border-radius: 10px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        display: flex;
        align-items: center;
    }

    .section-number {
        background: #9b59b6;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: bold;
        margin-right: 15px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .form-col {
        flex: 1;
        min-width: 200px;
    }

    .form-col.full {
        flex: 100%;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .required {
        color: #e74c3c;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    textarea,
    select {
        width: 100%;
        padding: 12px;
        border: 2px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s;
    }

    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="tel"]:focus,
    textarea:focus,
    select:focus {
        border-color: #9b59b6;
        outline: none;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    .address-card {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .address-card:hover {
        border-color: #9b59b6;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .address-card.selected {
        border-color: #9b59b6;
        background-color: #f8f4fd;
    }

    .address-card input[type="radio"] {
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .address-name {
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }

    .address-recipient {
        margin-bottom: 5px;
        color: #666;
    }

    .address-details {
        color: #666;
        font-size: 14px;
        line-height: 1.4;
    }

    .payment-method {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .payment-method:hover {
        border-color: #9b59b6;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .payment-method.selected {
        border-color: #9b59b6;
        background-color: #f8f4fd;
    }

    .payment-icon {
        width: 50px;
        height: 50px;
        background-color: #f0f0f0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .payment-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .payment-details h4 {
        margin-bottom: 5px;
        color: #333;
    }

    .payment-details p {
        color: #666;
        font-size: 14px;
        margin: 0;
    }

    .payment-method input[type="radio"] {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .order-summary {
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

    .order-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
        border-bottom: none;
        margin-bottom: 20px;
        padding-bottom: 0;
    }

    .item-image {
        width: 60px;
        height: 60px;
        background-color: #e0e0e0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        font-size: 12px;
        overflow: hidden;
    }

    .item-image::before {
        content: '📷';
        font-size: 1rem;
        opacity: 0.3;
    }

    .item-image.has-image::before {
        display: none;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
        font-size: 14px;
    }

    .item-quantity {
        color: #666;
        font-size: 14px;
    }

    .item-price {
        font-weight: bold;
        color: #27ae60;
        font-size: 14px;
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

    .place-order-btn {
        width: 100%;
        background: #27ae60;
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

    .place-order-btn:hover:not(:disabled) {
        background: #219a52;
        transform: translateY(-2px);
    }

    .place-order-btn:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
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

    .new-address-btn {
        width: 100%;
        background: #9b59b6;
        color: white;
        border: none;
        padding: 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .new-address-btn:hover {
        background: #8e44ad;
        transform: translateY(-2px);
    }

    .new-address-form {
        display: none;
        padding: 20px;
        border: 2px dashed #9b59b6;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-save {
        background: #27ae60;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-save:hover {
        background: #219a52;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-cancel:hover {
        background: #5a6268;
    }

    .note-section {
        margin-top: 20px;
    }

    .note-section label {
        margin-bottom: 10px;
    }

    @media (max-width: 768px) {
        .checkout-container {
            flex-direction: column;
        }

        .form-row {
            flex-direction: column;
        }

        .form-col {
            min-width: auto;
        }

        .payment-method,
        .address-card {
            flex-direction: column;
            text-align: center;
        }

        .payment-method input[type="radio"],
        .address-card input[type="radio"] {
            position: static;
            margin-bottom: 10px;
        }
    }

    .section-completed {
        background-color: #d4edda;
        border-left: 4px solid #27ae60;
    }

    .section-completed .section-number {
        background-color: #27ae60;
    }

    .order-note {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        font-size: 14px;
        color: #666;
        line-height: 1.5;
    }

    .checkout-steps {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
        position: relative;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #ddd;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 10px;
        z-index: 2;
        position: relative;
    }

    .step.active .step-circle {
        background: #9b59b6;
        color: white;
    }

    .step.completed .step-circle {
        background: #27ae60;
        color: white;
    }

    .step-label {
        font-size: 12px;
        color: #666;
        text-align: center;
    }

    .step.active .step-label {
        color: #9b59b6;
        font-weight: bold;
    }

    .step.completed .step-label {
        color: #27ae60;
    }

    .step-line {
        position: absolute;
        top: 20px;
        left: 50%;
        right: -50%;
        height: 2px;
        background: #ddd;
        z-index: 1;
    }

    .step:last-child .step-line {
        display: none;
    }

    .step.completed .step-line {
        background: #27ae60;
    }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>

    <div class="container">
        <!-- Checkout Header -->
        <h1 class="checkout-header">ข้อมูลการจัดส่ง</h1>

        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step completed">
                <div class="step-circle">1</div>
                <div class="step-label">ตะกร้าสินค้า</div>
                <div class="step-line"></div>
            </div>
            <div class="step active">
                <div class="step-circle">2</div>
                <div class="step-label">ข้อมูลการจัดส่ง</div>
                <div class="step-line"></div>
            </div>
            <div class="step">
                <div class="step-circle">3</div>
                <div class="step-label">ชำระเงิน</div>
                <div class="step-line"></div>
            </div>
        </div>

        <!-- Loading -->
        <div id="loading" class="loading">
            <div class="spinner"></div>
            <p>กำลังโหลดข้อมูล...</p>
        </div>

        <div class="checkout-container" id="checkout-container" style="display: none;">
            <!-- Checkout Form -->
            <div class="checkout-main">
                <!-- Shipping Address Section -->
                <div class="checkout-section" id="shipping-section">
                    <div class="section-title">
                        <div class="section-number">1</div>
                        ที่อยู่จัดส่ง
                    </div>

                    <button class="new-address-btn" onclick="toggleNewAddressForm()">
                        เพิ่มที่อยู่ใหม่
                    </button>

                    <!-- New Address Form -->
                    <div class="new-address-form" id="new-address-form">
                        <div class="form-row">
                            <div class="form-col">
                                <label>ชื่อผู้รับ <span class="required">*</span></label>
                                <input type="text" id="recipient-name" placeholder="ชื่อผู้รับสินค้า">
                            </div>
                            <div class="form-col">
                                <label>เบอร์โทรศัพท์ <span class="required">*</span></label>
                                <input type="tel" id="recipient-phone" placeholder="เบอร์โทรศัพท์" maxlength="10"
                                    oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>ชื่อที่อยู่ <span class="required">*</span></label>
                            <input type="text" id="address-name" placeholder="เช่น บ้าน, ที่ทำงาน">
                        </div>

                        <div class="form-group">
                            <label>ที่อยู่ <span class="required">*</span></label>
                            <textarea id="address-line" placeholder="บ้านเลขที่, ถนน, ตำบล/แขวง"></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label>อำเภอ/เขต <span class="required">*</span></label>
                                <input type="text" id="district" placeholder="อำเภอ/เขต">
                            </div>
                            <div class="form-col">
                                <label>จังหวัด <span class="required">*</span></label>
                                <input type="text" id="province" placeholder="จังหวัด">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <label>รหัสไปรษณีย์ <span class="required">*</span></label>
                                <input type="text" id="postal-code" placeholder="รหัสไปรษณีย์">
                            </div>
                            <div class="form-col">
                                <label>
                                    <input type="checkbox" id="set-default"> ตั้งเป็นที่อยู่เริ่มต้น
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button class="btn-save" onclick="saveNewAddress()">บันทึก</button>
                            <button class="btn-cancel" onclick="cancelNewAddress()">ยกเลิก</button>
                        </div>
                    </div>

                    <!-- Address List -->
                    <div id="address-list">
                        <!-- Addresses will be loaded here -->
                    </div>
                </div>

                <!-- Payment Method Section -->
                <div class="checkout-section" id="payment-section">
                    <div class="section-title">
                        <div class="section-number">2</div>
                        วิธีการชำระเงิน
                    </div>

                    <div id="payment-methods">
                        <!-- Payment methods will be loaded here -->
                    </div>
                </div>

                <!-- Order Notes -->
                <div class="checkout-section">
                    <div class="section-title">
                        <div class="section-number">3</div>
                        หมายเหตุ (ถ้ามี)
                    </div>

                    <div class="note-section">
                        <label>หมายเหตุการสั่งซื้อ</label>
                        <textarea id="order-notes"
                            placeholder="หมายเหตุเพิ่มเติม เช่น คำขอพิเศษสำหรับการจัดส่ง"></textarea>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <div class="summary-box">
                    <div class="summary-title">สรุปคำสั่งซื้อ</div>

                    <div id="order-items">
                        <!-- Order items will be loaded here -->
                    </div>

                    <div class="summary-row">
                        <span>ยอดรวม</span>
                        <span id="subtotal">฿0</span>
                    </div>

                    <div class="summary-row">
                        <span>ค่าจัดส่ง</span>
                        <span id="shipping-cost">฿40</span>
                    </div>

                    <div class="summary-row total">
                        <span>ยอดรวมทั้งสิ้น</span>
                        <span id="total-amount">฿40</span>
                    </div>

                    <button class="place-order-btn" id="place-order-btn">
                        สั่งซื้อสินค้า
                    </button>

                    <div class="order-note">
                        <strong>หมายเหตุ:</strong><br>
                        • กรุณาตรวจสอบข้อมูลให้ถูกต้องก่อนสั่งซื้อ<br>
                        • หลังจากสั่งซื้อแล้ว เราจะติดต่อกลับภายใน 24 ชั่วโมง<br>
                        • ระยะเวลาจัดส่ง 3-5 วันทำการ
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/MainFooter.php"); ?>

    <!-- Include notification.js -->
    <script src="assets/js/notification.js"></script>

    <!-- Include cart.js for shared functions -->
    <script src="assets/js/cart.js"></script>

    <script>
    // Constants
    const MEMBER_ID = getMemberId();
    const SHIPPING_COST = 40;
    let selectedAddressId = null;
    let selectedPaymentMethod = null;
    let isLoading = false;
    let cartItems = [];
    let addresses = [];
    let paymentMethods = [];
    let loadingInstance = null;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Checkout page loaded, Member ID:', MEMBER_ID);

        if (!MEMBER_ID) {
            showError('กรุณาเข้าสู่ระบบก่อนดำเนินการชำระเงิน');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2000);
            return;
        }

        const placeOrderBtn = document.getElementById('place-order-btn');
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function() {
                // Show confirmation dialog using new notification system
                showOrderConfirmation();
            });
        }

        loadCheckoutData();
        setupEventListeners();
    });

    // Load all checkout data
    async function loadCheckoutData() {
        if (isLoading) return;

        try {
            isLoading = true;
            showLoadingSpinner();

            // Load cart items
            await loadCartItems();

            // Load addresses
            await loadAddresses();

            // Load payment methods
            await loadPaymentMethods();

            showCheckoutContainer();

        } catch (error) {
            console.error('Error loading checkout data:', error);
            showError('เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message);
        } finally {
            isLoading = false;
            hideLoadingSpinner();
        }
    }

    // Load cart items
    async function loadCartItems() {
        try {
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
            console.log('Cart items loaded:', data);

            if (data.success && data.data && data.data.length > 0) {
                cartItems = data.data;
                displayOrderItems();
                updateOrderSummary();
            } else {
                throw new Error('ไม่มีสินค้าในตะกร้า กรุณาเพิ่มสินค้าก่อน');
                window.location.href = 'cart.php';
            }
        } catch (error) {
            console.error('Error loading cart items:', error);
            throw error;
        }
    }

    // Load addresses
    async function loadAddresses() {
        try {
            const response = await fetch(`controller/member_api.php?action=addresses&member_id=${MEMBER_ID}`, {
                method: 'GET'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Addresses loaded:', data);

            if (data.success) {
                addresses = data.data || [];
                displayAddresses();
            } else {
                console.warn('No addresses found for member');
                addresses = [];
                displayAddresses();
            }
        } catch (error) {
            console.error('Error loading addresses:', error);
            addresses = [];
            displayAddresses();
        }
    }

    // Load payment methods
    async function loadPaymentMethods() {
        try {
            const response = await fetch('controller/payment_method_api.php?action=all', {
                method: 'GET'
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Payment methods loaded:', data);

            if (data && Array.isArray(data)) {
                paymentMethods = data;
                displayPaymentMethods();
            } else {
                console.warn('No payment methods available');
                paymentMethods = [];
                displayPaymentMethods();
            }
        } catch (error) {
            console.error('Error loading payment methods:', error);
            paymentMethods = [];
            displayPaymentMethods();
        }
    }

    // Display order items
    function displayOrderItems() {
        const container = document.getElementById('order-items');
        if (!container) return;

        container.innerHTML = '';

        cartItems.forEach(item => {
            const orderItem = createOrderItemElement(item);
            container.appendChild(orderItem);
        });
    }

    // Create order item element
    function createOrderItemElement(item) {
        const div = document.createElement('div');
        div.className = 'order-item';

        // Handle image
        let imageSrc = 'assets/images/no-image.png';
        if (item.shoe_image) {
            imageSrc = item.shoe_image;
        } else if (item.img_path) {
            imageSrc = `controller/uploads/products/${item.img_path}`;
        }

        const price = parseFloat(item.unit_price) || 0;
        const quantity = parseInt(item.quantity) || 1;
        const total = price * quantity;

        div.innerHTML = `
                <div class="item-image ${item.img_path ? 'has-image' : ''}">
                    <img src="${imageSrc}" alt="${item.shoe_name || item.name || 'สินค้า'}" 
                         onerror="this.style.display='none'">
                </div>
                <div class="item-info">
                    <div class="item-name">${item.shoe_name || item.name || 'สินค้า'}</div>
                    <div class="item-quantity">จำนวน: ${quantity}</div>
                </div>
                <div class="item-price">฿${formatNumber(total)}</div>
            `;

        return div;
    }

    // Display addresses
    function displayAddresses() {
        const container = document.getElementById('address-list');
        if (!container) return;

        container.innerHTML = '';

        if (addresses.length === 0) {
            container.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <p>ยังไม่มีที่อยู่ในระบบ กรุณาเพิ่มที่อยู่ใหม่</p>
                    </div>
                `;
            return;
        }

        addresses.forEach((address, index) => {
            const addressCard = createAddressElement(address, index === 0);
            container.appendChild(addressCard);
        });

        // Auto select first address if available and no address is selected
        if (addresses.length > 0 && !selectedAddressId) {
            selectedAddressId = addresses[0].address_id || addresses[0].id;
            updateSectionStatus('shipping-section', true);
            validateForm();
        }
    }

    // Create address element
    function createAddressElement(address, isFirst = false) {
        const div = document.createElement('div');
        div.className = 'address-card';
        if (isFirst) {
            div.classList.add('selected');
        }

        // Use the correct field name from your database
        const addressId = address.address_id || address.id;

        div.innerHTML = `
                <input type="radio" name="shipping-address" value="${addressId}" ${isFirst ? 'checked' : ''}>
                <div class="address-name">${address.address_name || 'ที่อยู่'}</div>
                <div class="address-recipient">${address.recipient_name} | ${address.recipient_phone}</div>
                <div class="address-details">
                    ${address.address_line}<br>
                    ${address.district}, ${address.province} ${address.postal_code}
                </div>
            `;

        div.addEventListener('click', function() {
            selectAddress(addressId, div);
        });

        const radio = div.querySelector('input[type="radio"]');
        radio.addEventListener('change', function() {
            if (this.checked) {
                selectAddress(addressId, div);
            }
        });

        return div;
    }

    // Display payment methods
    function displayPaymentMethods() {
        const container = document.getElementById('payment-methods');
        if (!container) return;

        container.innerHTML = '';

        if (paymentMethods.length === 0) {
            container.innerHTML = `
                    <div style="text-align: center; padding: 20px; color: #666;">
                        <p>ไม่มีวิธีการชำระเงินในขณะนี้</p>
                    </div>
                `;
            return;
        }

        paymentMethods.forEach((method, index) => {
            const paymentCard = createPaymentMethodElement(method, index === 0);
            container.appendChild(paymentCard);
        });

        // Auto select first payment method if available
        if (paymentMethods.length > 0 && !selectedPaymentMethod) {
            selectedPaymentMethod = paymentMethods[0].id;
            updateSectionStatus('payment-section', true);
            validateForm();
        }
    }

    // Create payment method element
    function createPaymentMethodElement(method, isFirst = false) {
        const div = document.createElement('div');
        div.className = 'payment-method';

        // Handle payment method image
        let imageSrc = '';
        if (method.bank == 'กรุงเทพ') {
            imageSrc = 'assets/images/bank/krungtep.png';
        } else if (method.bank == 'กสิกรไทย') {
            imageSrc = 'assets/images/bank/kasikorn.png';
        } else if (method.bank == 'กรุงไทย') {
            imageSrc = 'assets/images/bank/krungthai.png';
        } else if (method.bank == 'ไทยพาณิชย์') {
            imageSrc = 'assets/images/bank/scb.png';
        } else if (method.bank == 'ทหารไทยธนชาต') {
            imageSrc = 'assets/images/bank/ttb.png';
        }

        div.innerHTML = `
                <input type="radio" name="payment-method" value="${method.payment_method_id}" }>
                <div class="payment-icon">
                    <img src="${imageSrc}" alt="${method.bank || 'ธนาคาร'}" 
                         onerror="this.src=''">
                </div>
                <div class="payment-details">
                    <h4>${method.bank || 'ธนาคาร'}</h4>
                    <p>เลขที่บัญชี: ${method.account_number || 'N/A'}</p>
                    <p>ชื่อบัญชี: ${method.name || 'N/A'}</p>
                </div>
            `;

        div.addEventListener('click', function() {
            selectPaymentMethod(method.payment_method_id, div);
        });

        const radio = div.querySelector('input[type="radio"]');
        radio.addEventListener('change', function() {
            if (this.checked) {
                selectPaymentMethod(method.payment_method_id, div);
            }
        });

        return div;
    }

    // Select address
    function selectAddress(addressId, element) {
        selectedAddressId = addressId;

        // Update UI
        document.querySelectorAll('.address-card').forEach(card => {
            card.classList.remove('selected');
        });
        element.classList.add('selected');

        // Update radio button
        const radio = element.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }

        updateSectionStatus('shipping-section', true);
        validateForm();
    }

    // Select payment method
    function selectPaymentMethod(methodId, element) {
        selectedPaymentMethod = methodId;

        // Update UI
        document.querySelectorAll('.payment-method').forEach(method => {
            method.classList.remove('selected');
        });
        element.classList.add('selected');

        // Update radio button
        const radio = element.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }

        updateSectionStatus('payment-section', true);
        validateForm();
    }

    // Update section status
    function updateSectionStatus(sectionId, completed) {
        const section = document.getElementById(sectionId);
        if (!section) return;

        if (completed) {
            section.classList.add('section-completed');
        } else {
            section.classList.remove('section-completed');
        }
    }

    // Update order summary
    function updateOrderSummary() {
        try {
            let subtotal = 0;

            cartItems.forEach(item => {
                const price = parseFloat(item.unit_price) || 0;
                const quantity = parseInt(item.quantity) || 1;
                subtotal += price * quantity;
            });

            const total = subtotal + SHIPPING_COST;

            // Update summary elements
            updateElement('subtotal', '฿' + formatNumber(subtotal));
            updateElement('total-amount', '฿' + formatNumber(total));

        } catch (error) {
            console.error('Error updating order summary:', error);
        }
    }

    // Validate form
    function validateForm() {
        const placeOrderBtn = document.getElementById('place-order-btn');
        if (!placeOrderBtn) return;

        const isValid = selectedAddressId && selectedPaymentMethod && cartItems.length > 0;
        //placeOrderBtn.disabled = !isValid;
    }

    // Toggle new address form
    function toggleNewAddressForm() {
        const form = document.getElementById('new-address-form');
        const btn = document.querySelector('.new-address-btn');

        if (form.style.display === 'block') {
            form.style.display = 'none';
            btn.textContent = 'เพิ่มที่อยู่ใหม่';
        } else {
            form.style.display = 'block';
            btn.textContent = 'ยกเลิกการเพิ่มที่อยู่';
            clearNewAddressForm();
        }
    }

    // Save new address
    async function saveNewAddress() {
        try {
            const addressData = {
                member_id: MEMBER_ID,
                recipient_name: document.getElementById('recipient-name').value.trim(),
                recipient_phone: document.getElementById('recipient-phone').value.trim(),
                address_name: document.getElementById('address-name').value.trim(),
                address_line: document.getElementById('address-line').value.trim(),
                sub_district: document.getElementById('district').value
                    .trim(), // Using district as sub_district
                district: document.getElementById('district').value.trim(),
                province: document.getElementById('province').value.trim(),
                postal_code: document.getElementById('postal-code').value.trim(),
                is_default: document.getElementById('set-default').checked ? 1 : 0
            };

            // Validate required fields
            const required = ['recipient_name', 'recipient_phone', 'address_name', 'address_line', 'district',
                'province', 'postal_code'
            ];
            for (let field of required) {
                if (!addressData[field]) {
                    showError(`กรุณากรอก${getFieldLabel(field)}`);
                    return;
                }
            }

            // Validate phone number
            if (!/^\d{10}$/.test(addressData.recipient_phone)) {
                showError('กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)');
                return;
            }

            // Validate postal code
            if (!/^[0-9]{5}$/.test(addressData.postal_code)) {
                showError('กรุณากรอกรหัสไปรษณีย์ให้ถูกต้อง (5 หลัก)');
                return;
            }

            // Show loading notification
            const hideLoading = showLoading('กำลังบันทึกที่อยู่...');

            const response = await fetch('controller/member_api.php?action=create-address', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(addressData)
            });

            hideLoading();

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                showSuccess('เพิ่มที่อยู่เรียบร้อยแล้ว');
                toggleNewAddressForm();
                await loadAddresses(); // Reload addresses
            } else {
                throw new Error(result.message || 'ไม่สามารถเพิ่มที่อยู่ได้');
            }

        } catch (error) {
            console.error('Error saving address:', error);
            showError('เกิดข้อผิดพลาดในการบันทึกที่อยู่: ' + error.message);
        }
    }

    // Cancel new address
    function cancelNewAddress() {
        toggleNewAddressForm();
    }

    // Clear new address form
    function clearNewAddressForm() {
        document.getElementById('recipient-name').value = '';
        document.getElementById('recipient-phone').value = '';
        document.getElementById('address-name').value = '';
        document.getElementById('address-line').value = '';
        document.getElementById('district').value = '';
        document.getElementById('province').value = '';
        document.getElementById('postal-code').value = '';
        document.getElementById('set-default').checked = false;
    }

    // Get field label for validation
    function getFieldLabel(field) {
        const labels = {
            recipient_name: 'ชื่อผู้รับ',
            recipient_phone: 'เบอร์โทรศัพท์',
            address_name: 'ชื่อที่อยู่',
            address_line: 'ที่อยู่',
            district: 'อำเภอ/เขต',
            province: 'จังหวัด',
            postal_code: 'รหัสไปรษณีย์'
        };
        return labels[field] || field;
    }

    // Handle place order
    async function handlePlaceOrder() {
        if (!validateOrderData()) {
            return;
        }

        try {
            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.disabled = true;
            placeOrderBtn.textContent = 'กำลังดำเนินการ...';

            // Show loading notification
            const hideLoading = showLoading('กำลังสั่งซื้อสินค้า...');

            // Get selected address with correct field mapping
            const selectedAddress = getSelectedAddress();
            if (!selectedAddress) {
                throw new Error('ไม่พบที่อยู่ที่เลือก');
            }

            console.log('Selected address:', selectedAddress);

            // Calculate total amount
            let subtotal = 0;
            cartItems.forEach(item => {
                const price = parseFloat(item.unit_price) || 0;
                const quantity = parseInt(item.quantity) || 1;
                subtotal += price * quantity;
            });
            const totalAmount = subtotal + SHIPPING_COST;

            // Format shipping address string
            const shippingAddress = [
                selectedAddress.address_line,
                `${selectedAddress.district}, ${selectedAddress.province} ${selectedAddress.postal_code}`
            ].filter(line => line).join('\n');

            // Prepare order data according to OrderController requirements
            const orderData = {
                member_id: MEMBER_ID,
                address_id: selectedAddress.address_id || selectedAddress.id, // Use correct field name
                payment_method_id: selectedPaymentMethod,
                recipient_name: selectedAddress.recipient_name,
                total_amount: totalAmount,
                shipping_address: shippingAddress,
                shipping_phone: selectedAddress.recipient_phone,
                notes: document.getElementById('order-notes').value.trim() || null,
                items: cartItems.map(item => ({
                    shoe_id: item.shoe_id,
                    quantity: parseInt(item.quantity),
                    unit_price: parseFloat(item.unit_price)
                })),
                payment_timeout_hours: 24
            };

            console.log('Sending order data:', orderData);

            // Validate order data before sending
            if (!orderData.address_id) {
                throw new Error('ไม่พบ ID ที่อยู่');
            }

            if (!orderData.payment_method_id) {
                throw new Error('ไม่พบ ID วิธีชำระเงิน');
            }

            if (!orderData.items || orderData.items.length === 0) {
                throw new Error('ไม่มีรายการสินค้า');
            }

            // Send to API
            const response = await fetch('controller/order_api.php?action=create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(orderData)
            });

            hideLoading();

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Response error:', response.status, errorText);
                throw new Error(`เซิร์ฟเวอร์ตอบกลับด้วยสถานะ ${response.status}`);
            }

            const result = await response.json();
            console.log('Order result:', result);

            if (result.success) {
                // Clear saved form data
                clearSavedFormData();

                // Show success message
                showSuccess(result.message || 'สั่งซื้อสินค้าเรียบร้อยแล้ว!', 3000);

                // Store order info for payment page
                sessionStorage.setItem('newOrder', JSON.stringify({
                    order_id: result.order_id,
                    order_number: result.order_number,
                    payment_expire_at: result.payment_expire_at,
                    total_amount: totalAmount
                }));

                // Redirect to payment page
                setTimeout(() => {
                    if (result.order_number) {
                        window.location.href = `order-payment.php?order=${result.order_number}`;
                    } else {
                        window.location.href = `order-payment.php?order_id=${result.order_id}`;
                    }
                }, 3000);

            } else {
                throw new Error(result.message || 'ไม่สามารถสั่งซื้อสินค้าได้');
            }

        } catch (error) {
            console.error('Error placing order:', error);

            let errorMessage = 'เกิดข้อผิดพลาดในการสั่งซื้อ: ' + error.message;

            // Handle specific errors
            if (error.message.includes('fetch')) {
                errorMessage = 'ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้ กรุณาตรวจสอบการเชื่อมต่ออินเทอร์เน็ต';
            } else if (error.message.includes('JSON')) {
                errorMessage = 'เกิดข้อผิดพลาดในการประมวลผลข้อมูล กรุณาลองใหม่อีกครั้ง';
            }

            showError(errorMessage);

            // Reset button
            const placeOrderBtn = document.getElementById('place-order-btn');
            placeOrderBtn.disabled = false;
            placeOrderBtn.textContent = 'สั่งซื้อสินค้า';
        }
    }

    // Validate order data
    function validateOrderData() {
        if (!MEMBER_ID) {
            showError('กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ');
            return false;
        }

        if (!selectedAddressId) {
            showError('กรุณาเลือกที่อยู่จัดส่ง');
            // Scroll to address section
            const addressSection = document.getElementById('shipping-section');
            if (addressSection) {
                addressSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
            return false;
        }

        if (!selectedPaymentMethod) {
            showError('กรุณาเลือกวิธีการชำระเงิน');
            // Scroll to payment section
            const paymentSection = document.getElementById('payment-section');
            if (paymentSection) {
                paymentSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
            return false;
        }

        if (!cartItems || cartItems.length === 0) {
            showError('ไม่มีสินค้าในตะกร้า กรุณาเพิ่มสินค้าก่อนสั่งซื้อ');
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 2000);
            return false;
        }

        // Validate each cart item
        for (let item of cartItems) {
            if (!item.shoe_id || !item.quantity || !item.unit_price) {
                showError('พบข้อมูลสินค้าไม่ถูกต้อง กรุณาตรวจสอบตะกร้าสินค้า');
                return false;
            }

            if (item.quantity <= 0) {
                showError('จำนวนสินค้าต้องมากกว่า 0');
                return false;
            }

            if (item.unit_price <= 0) {
                showError('ราคาสินค้าต้องมากกว่า 0');
                return false;
            }
        }

        return true;
    }

    // Show order confirmation using new notification system
    function showOrderConfirmation() {
        const totals = calculateOrderTotals();
        const selectedAddr = getSelectedAddress();
        const selectedPay = getSelectedPaymentMethod();

        if (!selectedAddr || !selectedPay) {
            showError('กรุณาเลือกที่อยู่และวิธีชำระเงิน');
            return;
        }

        const confirmMessage = `
                ยืนยันการสั่งซื้อ:<br>
                <br>
                ที่อยู่จัดส่ง: ${selectedAddr.recipient_name}
                ${selectedAddr.address_line}
                ${selectedAddr.district}, ${selectedAddr.province} ${selectedAddr.postal_code}
                <br><br>
                วิธีชำระเงิน: ${selectedPay.bank}
                เลขบัญชี: ${selectedPay.account_number}
                <br><br>
                จำนวนสินค้า: ${cartItems.length} รายการ
                ยอดรวม: ฿${formatNumber(totals.total)}
                <br><br>
                ต้องการดำเนินการสั่งซื้อหรือไม่?
            `;

        showConfirm(confirmMessage, () => {
            handlePlaceOrder();
        });
    }

    // Get selected address object
    function getSelectedAddress() {
        return addresses.find(addr => (addr.address_id || addr.id) === selectedAddressId) || null;
    }

    // Get selected payment method object
    function getSelectedPaymentMethod() {
        return paymentMethods.find(method => method.payment_method_id === selectedPaymentMethod) || null;
    }

    // Calculate order totals
    function calculateOrderTotals() {
        let subtotal = 0;

        cartItems.forEach(item => {
            const price = parseFloat(item.unit_price) || 0;
            const quantity = parseInt(item.quantity) || 1;
            subtotal += price * quantity;
        });

        return {
            subtotal: subtotal,
            shipping: SHIPPING_COST,
            total: subtotal + SHIPPING_COST
        };
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

    // Display state functions
    function showLoadingSpinner() {
        showElement('loading');
        hideElement('checkout-container');
    }

    function hideLoadingSpinner() {
        hideElement('loading');
    }

    function showCheckoutContainer() {
        hideElement('loading');
        showElement('checkout-container');
    }

    function showElement(id) {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = element.id === 'checkout-container' ? 'flex' : 'block';
        }
    }

    function hideElement(id) {
        const element = document.getElementById(id);
        if (element) {
            element.style.display = 'none';
        }
    }

    // Auto-save form data to prevent data loss
    function autoSaveFormData() {
        const formData = {
            notes: document.getElementById('order-notes').value,
            selectedAddressId: selectedAddressId,
            selectedPaymentMethod: selectedPaymentMethod
        };
        sessionStorage.setItem('checkoutFormData', JSON.stringify(formData));
    }

    // Restore form data
    function restoreFormData() {
        try {
            const saved = sessionStorage.getItem('checkoutFormData');
            if (saved) {
                const data = JSON.parse(saved);

                if (data.notes && document.getElementById('order-notes')) {
                    document.getElementById('order-notes').value = data.notes;
                }

                // These will be restored when addresses/payments are loaded
                if (data.selectedAddressId) {
                    selectedAddressId = data.selectedAddressId;
                }
                if (data.selectedPaymentMethod) {
                    selectedPaymentMethod = data.selectedPaymentMethod;
                }
            }
        } catch (error) {
            console.error('Error restoring form data:', error);
        }
    }

    // Auto-save on form changes
    document.addEventListener('input', function(e) {
        if (e.target.id === 'order-notes') {
            autoSaveFormData();
        }
    });

    // Clear saved data on successful order
    function clearSavedFormData() {
        sessionStorage.removeItem('checkoutFormData');
    }

    function debugOrderData() {
        console.log('Debug Order Data:');
        console.log('Member ID:', MEMBER_ID);
        console.log('Selected Address ID:', selectedAddressId);
        console.log('Selected Payment Method:', selectedPaymentMethod);
        console.log('Cart Items:', cartItems);
        console.log('Addresses:', addresses);
        console.log('Payment Methods:', paymentMethods);
    }

    // Restore form data on page load
    window.addEventListener('load', restoreFormData);
    </script>
</body>

</html>