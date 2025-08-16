<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - Logo Store</title>
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

        .payment-header {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }

        /* Progress Steps */
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

        .payment-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .payment-main {
            flex: 2;
            min-width: 400px;
        }

        .payment-sidebar {
            flex: 1;
            min-width: 300px;
        }

        .payment-section {
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

        .order-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: 500;
            color: #333;
        }

        .info-value.highlight {
            color: #9b59b6;
            font-weight: bold;
            font-size: 16px;
        }

        .bank-info {
            background: #fff8e1;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .bank-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .bank-icon {
            width: 40px;
            height: 40px;
            background: #ffc107;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .bank-details h4 {
            margin-bottom: 5px;
            color: #333;
        }

        .bank-details p {
            color: #666;
            font-size: 14px;
            margin: 0;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            background: #f8f9fa;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
            position: relative;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .upload-section {
            margin-bottom: 30px;
        }

        .upload-label {
            display: block;
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            font-weight: 500;
        }

        .file-upload {
            width: 100%;
            padding: 20px;
            border: 2px dashed #9b59b6;
            border-radius: 8px;
            background: #f8f3ff;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .file-upload:hover {
            background: #f0e6ff;
            border-color: #8e44ad;
            transform: translateY(-2px);
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .upload-text {
            color: #9b59b6;
            font-size: 14px;
            pointer-events: none;
            line-height: 1.5;
        }

        .file-selected {
            color: #27ae60;
            font-weight: 500;
        }

        .submit-btn {
            width: 100%;
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .submit-btn:hover:not(:disabled) {
            background: #219a52;
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .order-summary {
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

        .note {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 12px;
            color: #1565c0;
            line-height: 1.5;
        }

        .note strong {
            display: block;
            margin-bottom: 5px;
        }

        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
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

        @media (max-width: 768px) {
            .payment-container {
                flex-direction: column;
            }

            .payment-main,
            .payment-sidebar {
                min-width: auto;
            }

            .qr-code {
                width: 180px;
                height: 180px;
            }

            .payment-header {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    
    <div class="container">

        <!-- Payment Header -->
        <h1 class="payment-header">ชำระเงิน</h1>

        <!-- Progress Steps -->
        <div class="checkout-steps">
            <div class="step completed">
                <div class="step-circle">1</div>
                <div class="step-label">ตะกร้าสินค้า</div>
                <div class="step-line"></div>
            </div>
            <div class="step completed">
                <div class="step-circle">2</div>
                <div class="step-label">ข้อมูลการจัดส่ง</div>
                <div class="step-line"></div>
            </div>
            <div class="step active">
                <div class="step-circle">3</div>
                <div class="step-label">ชำระเงิน</div>
                <div class="step-line"></div>
            </div>
        </div>

        <!-- Messages -->
        <div id="error-message" class="error" style="display: none;"></div>
        <div id="success-message" class="success" style="display: none;"></div>

        <div class="payment-container">
            <!-- Payment Main -->
            <div class="payment-main">
                <!-- Order Information -->
                <div class="payment-section">
                    <div class="section-title">
                        <div class="section-number">1</div>
                        ข้อมูลคำสั่งซื้อ
                    </div>

                    <div class="order-info">
                        <div class="info-row">
                            <span class="info-label">หมายเลขคำสั่งซื้อ:</span>
                            <span class="info-value highlight" id="order-number">#ORD202507260001</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">จำนวนสินค้า:</span>
                            <span class="info-value" id="item-count">4 รายการ</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ยอดรวม:</span>
                            <span class="info-value highlight" id="total-amount">฿836</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">สถานะ:</span>
                            <span class="info-value" style="color: #ff9800;">รอชำระเงิน</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="payment-section">
                    <div class="section-title">
                        <div class="section-number">2</div>
                        วิธีการชำระเงิน
                    </div>

                    <div class="bank-info">
                        <div class="bank-header">
                            <div class="bank-icon">
                                <img src="" alt="Bank" style="width: 30px; height: 30px;" onerror="this.style.display='none'">
                                <span style="font-weight: bold; color: white;">฿</span>
                            </div>
                            <div class="bank-details">
                                <h4>โอนเงินผ่านธนาคาร</h4>
                                <p>โอนเงินไปที่บัญชีธนาคารด้านล่าง</p>
                            </div>
                        </div>
                        
                        <div class="info-row">
                            <span class="info-label">ธนาคาร:</span>
                            <span class="info-value" id="bank-name">กรุงไทย</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ชื่อบัญชี:</span>
                            <span class="info-value" id="account-name">Narerat Jattayaworn</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">เลขที่บัญชี:</span>
                            <span class="info-value" id="account-number">xxx-x-xxxxx-x</span>
                        </div>
                    </div>

                    <div class="qr-section">
                        <div class="qr-code" id="qr-code">
                            <div style="text-align: center;">
                                <div style="font-size: 16px; margin-bottom: 10px;">QR Code</div>
                                <div style="font-size: 12px; color: #666;">สำหรับชำระเงิน</div>
                            </div>
                        </div>
                        <p style="color: #666; font-size: 14px;">สแกนคิวอาร์โค้ดเพื่อชำระเงิน</p>
                    </div>
                </div>

                <!-- Upload Payment Slip -->
                <div class="payment-section">
                    <div class="section-title">
                        <div class="section-number">3</div>
                        อัปโหลดหลักฐานการชำระเงิน
                    </div>

                    <form id="paymentForm">
                        <div class="upload-section">
                            <label class="upload-label">อัปโหลดหลักฐานการชำระเงิน</label>
                            <div class="file-upload">
                                <input type="file" id="paymentSlip" accept="image/*,.pdf" required>
                                <div class="upload-text" id="uploadText">
                                    คลิกเพื่อเลือกไฟล์หรือลากไฟล์มาวางที่นี่
                                    <br><small>(รองรับไฟล์ .jpg, .png, .pdf ขนาดไม่เกิน 5MB)</small>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="submit-btn" id="submitBtn">
                            <div class="btn-content">
                                <div class="loading" id="loading"></div>
                                <span id="btnText">ยืนยันการชำระเงิน</span>
                            </div>
                        </button>
                    </form>

                    <div class="note">
                        <strong>หมายเหตุ:</strong>
                        • กรุณาอัปโหลดหลักฐานการโอนเงินเพื่อยืนยันการชำระเงิน<br>
                        • ระบบจะตรวจสอบการชำระเงิน ภายใน 24 ชั่วโมง<br>
                        • หลังจากยืนยันแล้ว สินค้าจะถูกจัดส่งภายใน 3-5 วันทำการ
                    </div>
                </div>
            </div>

            <!-- Payment Sidebar -->
            <div class="payment-sidebar">
                <div class="order-summary">
                    <div class="summary-title">สรุปคำสั่งซื้อ</div>

                    <div id="order-items">
                        <!-- Sample items - จะถูกแทนที่ด้วย JavaScript -->
                        <div class="order-item">
                            <div class="item-image">
                                <img src="assets/images/no-image.png" alt="สินค้า" onerror="this.src='assets/images/no-image.png'">
                            </div>
                            <div class="item-info">
                                <div class="item-name">รองเท้าผ้าใบ Nike</div>
                                <div class="item-quantity">จำนวน: 2</div>
                            </div>
                            <div class="item-price">฿398</div>
                        </div>
                        
                        <div class="order-item">
                            <div class="item-image">
                                <img src="assets/images/no-image.png" alt="สินค้า" onerror="this.src='assets/images/no-image.png'">
                            </div>
                            <div class="item-info">
                                <div class="item-name">รองเท้าผ้าใบ Adidas</div>
                                <div class="item-quantity">จำนวน: 2</div>
                            </div>
                            <div class="item-price">฿398</div>
                        </div>
                    </div>

                    <div class="summary-row">
                        <span>ยอดรวม</span>
                        <span id="subtotal">฿796</span>
                    </div>

                    <div class="summary-row">
                        <span>ค่าจัดส่ง</span>
                        <span id="shipping-cost">฿40</span>
                    </div>

                    <div class="summary-row total">
                        <span>ยอดรวมทั้งสิ้น</span>
                        <span id="final-total">฿836</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/MainFooter.php"); ?>

    <script>
        const fileInput = document.getElementById('paymentSlip');
        const uploadText = document.getElementById('uploadText');
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const btnText = document.getElementById('btnText');

        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const orderNumber = urlParams.get('order') || '#ORD202507260001';

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadOrderData();
        });

        // Load order data
        async function loadOrderData() {
            try {
                // Here you would typically load order data from API
                // For now, we'll use mock data
                const mockOrderData = {
                    order_number: orderNumber,
                    total_amount: 836,
                    item_count: 4,
                    items: [
                        { name: 'รองเท้าผ้าใบ Nike', quantity: 2, price: 398, image: 'assets/images/no-image.png' },
                        { name: 'รองเท้าผ้าใบ Adidas', quantity: 2, price: 398, image: 'assets/images/no-image.png' }
                    ],
                    payment_method: {
                        bank: 'กรุงไทย',
                        account_name: 'Narerat Jattayaworn',
                        account_number: 'xxx-x-xxxxx-x'
                    }
                };

                updateOrderDisplay(mockOrderData);
            } catch (error) {
                console.error('Error loading order data:', error);
                showError('ไม่สามารถโหลดข้อมูลคำสั่งซื้อได้');
            }
        }

        // Update order display
        function updateOrderDisplay(orderData) {
            document.getElementById('order-number').textContent = orderData.order_number;
            document.getElementById('item-count').textContent = `${orderData.item_count} รายการ`;
            document.getElementById('total-amount').textContent = `฿${orderData.total_amount}`;
            document.getElementById('final-total').textContent = `฿${orderData.total_amount}`;
            
            // Update payment method info
            if (orderData.payment_method) {
                document.getElementById('bank-name').textContent = orderData.payment_method.bank;
                document.getElementById('account-name').textContent = orderData.payment_method.account_name;
                document.getElementById('account-number').textContent = orderData.payment_method.account_number;
            }

            // Update order items if provided
            if (orderData.items) {
                updateOrderItems(orderData.items);
            }
        }

        // Update order items display
        function updateOrderItems(items) {
            const container = document.getElementById('order-items');
            if (!container) return;

            container.innerHTML = '';
            
            items.forEach(item => {
                const itemEl = document.createElement('div');
                itemEl.className = 'order-item';
                itemEl.innerHTML = `
                    <div class="item-image">
                        <img src="${item.image}" alt="${item.name}" onerror="this.src='assets/images/no-image.png'">
                    </div>
                    <div class="item-info">
                        <div class="item-name">${item.name}</div>
                        <div class="item-quantity">จำนวน: ${item.quantity}</div>
                    </div>
                    <div class="item-price">฿${item.price}</div>
                `;
                container.appendChild(itemEl);
            });
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (validateFile(file)) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    uploadText.innerHTML = `
                        <div class="file-selected">
                            ✓ ไฟล์ที่เลือก: ${fileName}
                            <br><small>ขนาด: ${fileSize} MB</small>
                        </div>
                    `;
                }
            }
        });

        // Validate file
        function validateFile(file) {
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                showError('กรุณาเลือกไฟล์ประเภท JPG, PNG หรือ PDF เท่านั้น');
                clearFileInput();
                return false;
            }

            if (file.size > 5 * 1024 * 1024) {
                showError('ขนาดไฟล์ต้องไม่เกิน 5 MB');
                clearFileInput();
                return false;
            }

            return true;
        }

        // Clear file input
        function clearFileInput() {
            fileInput.value = '';
            uploadText.innerHTML = `
                คลิกเพื่อเลือกไฟล์หรือลากไฟล์มาวางที่นี่
                <br><small>(รองรับไฟล์ .jpg, .png, .pdf ขนาดไม่เกิน 5MB)</small>
            `;
        }

        // Handle drag and drop
        const fileUpload = document.querySelector('.file-upload');

        fileUpload.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = '#f0e6ff';
            this.style.borderColor = '#8e44ad';
        });

        fileUpload.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.background = '#f8f3ff';
            this.style.borderColor = '#9b59b6';
        });

        fileUpload.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '#f8f3ff';
            this.style.borderColor = '#9b59b6';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });

        // Handle form submission
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!fileInput.files[0]) {
                showError('กรุณาเลือกไฟล์หลักฐานการชำระเงิน');
                return;
            }

            // Show loading
            setLoadingState(true);

            try {
                // Create FormData for file upload
                const formData = new FormData();
                formData.append('payment_slip', fileInput.files[0]);
                formData.append('order_number', orderNumber);

                // Here you would typically upload to your API
                // const response = await fetch('controller/order_api.php?action=upload-payment-slip', {
                //     method: 'POST',
                //     body: formData
                // });

                // Simulate upload process
                await new Promise(resolve => setTimeout(resolve, 2000));

                showSuccess('ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว!\nระบบจะตรวจสอบและแจ้งผลภายใน 24 ชั่วโมง');
                
                // Redirect after success
                setTimeout(() => {
                    window.location.href = 'order-history.php';
                }, 3000);

            } catch (error) {
                console.error('Upload error:', error);
                showError('เกิดข้อผิดพลาดในการอัปโหลดไฟล์: ' + error.message);
            } finally {
                setLoadingState(false);
            }
        });

        // Set loading state
        function setLoadingState(isLoading) {
            if (isLoading) {
                loading.style.display = 'block';
                btnText.textContent = 'กำลังประมวลผล...';
                submitBtn.disabled = true;
            } else {
                loading.style.display = 'none';
                btnText.textContent = 'ยืนยันการชำระเงิน';
                submitBtn.disabled = false;
            }
        }

        // Show error message
        function showError(message) {
            const errorEl = document.getElementById('error-message');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
                errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(() => {
                    errorEl.style.display = 'none';
                }, 5000);
            }
        }

        // Show success message
        function showSuccess(message) {
            const successEl = document.getElementById('success-message');
            if (successEl) {
                successEl.textContent = message;
                successEl.style.display = 'block';
                successEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                setTimeout(() => {
                    successEl.style.display = 'none';
                }, 5000);
            }
        }
    </script>
</body>
</html>

        