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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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

        /* เพิ่ม CSS สำหรับปุ่มยกเลิก */
        .cancel-section {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #ffd6d6;
            background: #fef8f8;
        }

        .cancel-btn {
            width: 100%;
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .cancel-btn:hover:not(:disabled) {
            background: #c82333;
            transform: translateY(-1px);
        }

        .cancel-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .cancel-warning {
            color: #856404;
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            font-size: 14px;
            line-height: 1.4;
        }

        .cancel-confirm-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        }

        .cancel-confirm-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .cancel-confirm-title {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 15px;
        }

        .cancel-confirm-text {
            color: #666;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .cancel-confirm-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .confirm-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        .cancel-modal-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        .confirm-btn:hover {
            background: #c82333;
        }

        .cancel-modal-btn:hover {
            background: #5a6268;
        }

        /* Animation สำหรับ modal */
        .cancel-confirm-modal.show {
            display: flex;
            animation: fadeIn 0.3s ease;
        }

        .cancel-confirm-modal.show .cancel-confirm-content {
            animation: slideIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                transform: scale(0.8) translateY(-20px);
                opacity: 0;
            }

            to {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
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
                            <span class="info-value" id="order-status">รอชำระเงิน</span>
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
                <!-- เพิ่ม HTML section สำหรับปุ่มยกเลิก (เพิ่มใน payment-sidebar หรือ payment-main) -->
                <div class="cancel-section">
                    <div class="cancel-warning">
                        <strong>⚠️ คำเตือน:</strong><br>
                        หากคุณต้องการยกเลิกการสั่งซื้อ สามารถกดปุ่มด้านล่างได้<br>
                        <small>• สินค้าที่จองไว้จะถูกปล่อยกลับไปยังสต็อก<br>
                            • ไม่สามารถกู้คืนการสั่งซื้อหลังจากยกเลิกแล้ว</small>
                    </div>

                    <button type="button" class="cancel-btn" id="cancelOrderBtn">
                        <div class="btn-content">
                            <div class="loading" id="cancelLoading" style="display: none;"></div>
                            <span id="cancelBtnText">ยกเลิกการสั่งซื้อ</span>
                        </div>
                    </button>
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
                                <img src="" alt="สินค้า" onerror="this.src=''">
                            </div>
                            <div class="item-info">
                                <div class="item-name">รองเท้าผ้าใบ Nike</div>
                                <div class="item-quantity">จำนวน: 2</div>
                            </div>
                            <div class="item-price">฿398</div>
                        </div>

                        <div class="order-item">
                            <div class="item-image">
                                <img src="" alt="สินค้า" onerror="this.src=''">
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


        <!-- Modal สำหรับยืนยันการยกเลิก -->
        <div class="cancel-confirm-modal" id="cancelConfirmModal">
            <div class="cancel-confirm-content">
                <div class="cancel-confirm-title">ยืนยันการยกเลิกออร์เดอร์</div>
                <div class="cancel-confirm-text">
                    คุณแน่ใจหรือไม่ที่จะยกเลิกการสั่งซื้อนี้?<br>
                    <strong>การดำเนินการนี้ไม่สามารถย้อนกลับได้</strong>
                </div>
                <div class="cancel-confirm-buttons">
                    <button class="confirm-btn" id="confirmCancelBtn">ยืนยันยกเลิก</button>
                    <button class="cancel-modal-btn" id="cancelModalBtn">ไม่ยกเลิก</button>
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

        // ตัวแปรสำหรับ modal
        const cancelConfirmModal = document.getElementById('cancelConfirmModal');
        const cancelOrderBtn = document.getElementById('cancelOrderBtn');
        const confirmCancelBtn = document.getElementById('confirmCancelBtn');
        const cancelModalBtn = document.getElementById('cancelModalBtn');
        const cancelLoading = document.getElementById('cancelLoading');
        const cancelBtnText = document.getElementById('cancelBtnText');

        // Get URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const orderNumber = urlParams.get('order') || '#ORD202507260001';

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadOrderData();
        });

        // Load order data from API
        async function loadOrderData() {
            try {
                showLoading('กำลังโหลดข้อมูลออเดอร์...');

                // เรียก API เพื่อดึงข้อมูลออเดอร์
                const response = await fetch(`controller/order_api.php?action=get-by-number&order_number=${encodeURIComponent(orderNumber)}`);
                const result = await response.json();

                if (result.success && result.data) {
                    updateOrderDisplayWithCancelCheck(result.data);

                    // ตรวจสอบสถานะออเดอร์
                    if (result.data.order_status === 5) { // ยกเลิกแล้ว
                        showError('ออเดอร์นี้ถูกยกเลิกแล้ว');
                        disableForm();
                    } else if (result.data.payment_status === 3) { // ชำระเงินแล้ว
                        showSuccess('ออเดอร์นี้ชำระเงินเรียบร้อยแล้ว');
                        disableForm();
                    } else if (new Date(result.data.payment_expire_at) < new Date()) { // หมดเวลา
                        showError('ออเดอร์นี้หมดเวลาชำระเงินแล้ว');
                        disableForm();
                    }
                } else {
                    throw new Error(result.message || 'ไม่พบข้อมูลออเดอร์');
                }

            } catch (error) {
                console.error('Error loading order data:', error);
                showError('ไม่สามารถโหลดข้อมูลออเดอร์ได้: ' + error.message);
                updateOrderDisplay(mockOrderData);

            } finally {
                hideLoading();
            }
        }

        // Update order display with API data
        function updateOrderDisplay(orderData) {
            // อัปเดตข้อมูลพื้นฐาน
            document.getElementById('order-number').textContent = orderData.order_number;
            document.getElementById('item-count').textContent = `${orderData.item_count || orderData.items?.length || 0} รายการ`;
            document.getElementById('total-amount').textContent = `฿${parseFloat(orderData.total_amount).toLocaleString()}`;
            document.getElementById('final-total').textContent = `฿${parseFloat(orderData.total_amount).toLocaleString()}`;
            document.getElementById('order-status').textContent = orderData.order_status_name;
            document.getElementById('bank-name').textContent = orderData.bank;
            document.getElementById('account-name').textContent = orderData.bank_account_name;
            document.getElementById('account-number').textContent = orderData.account_number;

            // อัปเดตรายการสินค้า
            if (orderData.items) {
                updateOrderItems(orderData.items);

                // คำนวณราคารวม
                const subtotal = orderData.items.reduce((sum, item) => sum + (item.unit_price * item.quantity), 0);
                const shipping = parseFloat(orderData.shipping_cost) || 40;

                document.getElementById('subtotal').textContent = `฿${subtotal.toLocaleString()}`;
                document.getElementById('shipping-cost').textContent = `฿${shipping.toLocaleString()}`;
            }

            // แสดงเวลาที่เหลือ
            if (orderData.payment_expire_at) {
                showTimeRemaining(orderData.payment_expire_at);
            }

            // เก็บ order_id สำหรับใช้ในการ submit
            window.currentOrderId = orderData.order_id;
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
                <img src="controller/uploads/products/${item.img_path || ''}" alt="${item.shoename}" onerror="this.src=''; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div style="display:none; align-items:center; justify-content:center; width:100%; height:100%; background:#f0f0f0; font-size:12px; color:#999;">ไม่มีรูป</div>
            </div>
            <div class="item-info">
                <div class="item-name">${item.shoename}</div>
                <div class="item-quantity">จำนวน: ${item.quantity}</div>
            </div>
            <div class="item-price">฿${(item.total_price).toLocaleString()}</div>
        `;
                container.appendChild(itemEl);
            });
        }

        // Show time remaining
        function showTimeRemaining(expireAt) {
            const expireTime = new Date(expireAt);
            const now = new Date();
            const timeLeft = expireTime - now;

            if (timeLeft <= 0) {
                showError('ออเดอร์นี้หมดเวลาชำระเงินแล้ว');
                disableForm();
                return;
            }

            // แสดง countdown (optional)
            const hours = Math.floor(timeLeft / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));

            // สร้าง element แสดงเวลาที่เหลือ
            const timeRemainingEl = document.createElement('div');
            timeRemainingEl.style.cssText = `
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: bold;
        color: #856404;
    `;
            timeRemainingEl.innerHTML = `
        ⏰ เวลาที่เหลือในการชำระเงิน: ${hours} ชั่วโมง ${minutes} นาที
    `;

            // เพิ่ม element ด้านบนของ form
            const paymentSection = document.querySelector('.payment-section');
            if (paymentSection) {
                paymentSection.insertBefore(timeRemainingEl, paymentSection.firstChild);
            }
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
                const event = new Event('change', {
                    bubbles: true
                });
                fileInput.dispatchEvent(event);
            }
        });

        // Handle form submission with API call
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!fileInput.files[0]) {
                showError('กรุณาเลือกไฟล์หลักฐานการชำระเงิน');
                return;
            }

            if (!window.currentOrderId) {
                showError('ไม่พบข้อมูลออเดอร์ กรุณาโหลดหน้าใหม่');
                return;
            }

            // Show loading
            setLoadingState(true);
            hideMessages();

            try {
                // Create FormData for file upload
                const formData = new FormData();
                formData.append('payment_slip', fileInput.files[0]);
                formData.append('order_id', window.currentOrderId);

                // Add timestamp and user info if available
                formData.append('upload_timestamp', new Date().toISOString());

                // เรียก API สำหรับอัปโหลดหลักฐานการชำระเงิน
                const response = await fetch('controller/order_api.php?action=upload-payment-slip', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    showSuccess('ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว!\nระบบจะตรวจสอบและแจ้งผลภายใน 24 ชั่วโมง');

                    // ปิดใช้งานฟอร์ม
                    disableForm();

                    // อัปเดตสถานะออเดอร์เป็น "รอตรวจสอบการชำระเงิน"
                    updateOrderStatus('รอตรวจสอบการชำระเงิน');

                    // Redirect หลังจาก 3 วินาที
                    setTimeout(() => {
                        window.location.href = 'order-history.php';
                    }, 3000);

                } else {
                    throw new Error(result.message || 'ไม่สามารถอัปโหลดไฟล์ได้');
                }

            } catch (error) {
                console.error('Upload error:', error);
                showError('เกิดข้อผิดพลาดในการอัปโหลดไฟล์: ' + error.message);
            } finally {
                setLoadingState(false);
            }
        });

        // Update order status display
        function updateOrderStatus(newStatus) {
            const statusElements = document.querySelectorAll('.info-value');
            statusElements.forEach(el => {
                if (el.textContent.includes('รอชำระเงิน')) {
                    el.textContent = newStatus;
                    el.style.color = '#ff9800';
                }
            });
        }



        // Disable form after successful submission
        function disableForm() {
            submitBtn.disabled = true;
            fileInput.disabled = true;
            fileUpload.style.pointerEvents = 'none';
            fileUpload.style.opacity = '0.6';

            // Change button text
            btnText.textContent = 'ส่งหลักฐานแล้ว';
            submitBtn.style.background = '#ccc';
        }

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

        // Show loading message
        function showLoading(message = 'กำลังโหลด...') {
            // สร้าง loading overlay (optional)
            let loadingOverlay = document.getElementById('loadingOverlay');
            if (!loadingOverlay) {
                loadingOverlay = document.createElement('div');
                loadingOverlay.id = 'loadingOverlay';
                loadingOverlay.innerHTML = `
            <div style="
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
                font-size: 16px;
                color: #333;
            ">
                <div style="text-align: center;">
                    <div style="
                        width: 40px;
                        height: 40px;
                        border: 4px solid #f3f3f3;
                        border-top: 4px solid #9b59b6;
                        border-radius: 50%;
                        animation: spin 1s linear infinite;
                        margin: 0 auto 15px;
                    "></div>
                    ${message}
                </div>
            </div>
        `;
                document.body.appendChild(loadingOverlay);
            }
            loadingOverlay.style.display = 'flex';
        }

        // Hide loading message
        function hideLoading() {
            const loadingOverlay = document.getElementById('loadingOverlay');
            if (loadingOverlay) {
                loadingOverlay.style.display = 'none';
            }
        }

        // Hide all messages
        function hideMessages() {
            const errorEl = document.getElementById('error-message');
            const successEl = document.getElementById('success-message');
            if (errorEl) errorEl.style.display = 'none';
            if (successEl) successEl.style.display = 'none';
        }

        // Show error message
        function showError(message) {
            hideMessages();
            const errorEl = document.getElementById('error-message');
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.style.display = 'block';
                errorEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });

                // Auto hide after 8 seconds
                setTimeout(() => {
                    errorEl.style.display = 'none';
                }, 8000);
            } else {
                alert('Error: ' + message);
            }
        }

        // Show success message
        function showSuccess(message) {
            hideMessages();
            const successEl = document.getElementById('success-message');
            if (successEl) {
                successEl.innerHTML = message.replace(/\n/g, '<br>');
                successEl.style.display = 'block';
                successEl.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });

                // Auto hide after 10 seconds
                setTimeout(() => {
                    successEl.style.display = 'none';
                }, 10000);
            } else {
                alert('Success: ' + message);
            }
        }

        // Auto refresh order status (optional)
        function startAutoRefresh() {
            // Refresh ทุก 30 วินาที เพื่อเช็คสถานะ
            setInterval(async () => {
                try {
                    const response = await fetch(`controller/order_api.php?action=get-by-number&order_number=${encodeURIComponent(orderNumber)}`);
                    const result = await response.json();

                    if (result.success && result.data) {
                        const currentStatus = result.data.payment_status_id;

                        // ถ้าสถานะเปลี่ยน reload หน้า
                        if (currentStatus === 3) { // ชำระเงินแล้ว
                            location.reload();
                        } else if (result.data.order_status_id === 5) { // ยกเลิก
                            location.reload();
                        }
                    }
                } catch (error) {
                    console.log('Auto refresh error:', error.message);
                }
            }, 30000);
        }

        // เริ่ม auto refresh หลังจากโหลดหน้าเสร็จ
        setTimeout(startAutoRefresh, 5000);

        // Handle page unload warning (ถ้ากำลังอัปโหลด)
        window.addEventListener('beforeunload', function(e) {
            if (submitBtn.disabled && btnText.textContent === 'กำลังประมวลผล...') {
                e.preventDefault();
                e.returnValue = 'กำลังอัปโหลดไฟล์อยู่ คุณแน่ใจหรือไม่ที่จะออกจากหน้านี้?';
                return e.returnValue;
            }
        });

        // Utility function: Check if order number format is valid
        function isValidOrderNumber(orderNum) {
            // Format: #ORDyyyymmddxxxx
            const pattern = /^#ORD\d{12}$/;
            return pattern.test(orderNum);
        }

        // Utility function: Format currency
        function formatCurrency(amount) {
            return '฿' + parseFloat(amount).toLocaleString('th-TH', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        // Add CSS for loading animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);

        // เปิด modal ยืนยันการยกเลิก
        cancelOrderBtn.addEventListener('click', function() {
            showCancelModal();
        });

        // ปิด modal
        cancelModalBtn.addEventListener('click', function() {
            hideCancelModal();
        });

        // คลิกนอก modal เพื่อปิด
        cancelConfirmModal.addEventListener('click', function(e) {
            if (e.target === cancelConfirmModal) {
                hideCancelModal();
            }
        });

        // ยืนยันการยกเลิกออร์เดอร์
        confirmCancelBtn.addEventListener('click', async function() {
            if (!window.currentOrderId) {
                showError('ไม่พบข้อมูลออร์เดอร์ กรุณาโหลดหน้าใหม่');
                hideCancelModal();
                return;
            }

            // แสดง loading
            setCancelLoadingState(true);
            hideMessages();

            try {
                // เรียก API สำหรับยกเลิกออร์เดอร์
                const response = await fetch(`controller/order_api.php?action=cancel&order_id=${window.currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        reason: 'ยกเลิกโดยลูกค้า',
                        changed_by: 'test', // หรือใส่ member_id ถ้ามี session
                        force_cancel: false
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    showSuccess('ยกเลิกการสั่งซื้อเรียบร้อยแล้ว!\nสินค้าที่จองไว้จะถูกปล่อยกลับไปยังสต็อก');

                    // อัปเดตสถานะในหน้า
                    updateOrderStatus('ยกเลิกแล้ว');
                    document.getElementById('order-status').style.color = '#dc3545';

                    // ปิดใช้งานฟอร์มทั้งหมด
                    disableAllForms();

                    // ปิด modal
                    hideCancelModal();

                    // Redirect หลังจาก 3 วินาที
                    setTimeout(() => {
                        window.location.href = 'order-history.php';
                    }, 3000);

                } else {
                    throw new Error(result.message || 'ไม่สามารถยกเลิกการสั่งซื้อได้');
                }

            } catch (error) {
                console.error('Cancel order error:', error);
                showError('เกิดข้อผิดพลาดในการยกเลิกออร์เดอร์: ' + error.message);
                hideCancelModal();
            } finally {
                setCancelLoadingState(false);
            }
        });

        // แสดง modal ยืนยันการยกเลิก
        function showCancelModal() {
            cancelConfirmModal.classList.add('show');
            cancelConfirmModal.style.display = 'flex';

            // ป้องกันการ scroll ของ body
            document.body.style.overflow = 'hidden';
        }

        // ซ่อน modal ยืนยันการยกเลิก
        function hideCancelModal() {
            cancelConfirmModal.classList.remove('show');
            setTimeout(() => {
                cancelConfirmModal.style.display = 'none';
            }, 300);

            // คืนค่าการ scroll ของ body
            document.body.style.overflow = 'auto';
        }

        // ตั้งค่าสถานะ loading ของปุ่มยกเลิก
        function setCancelLoadingState(isLoading) {
            if (isLoading) {
                cancelLoading.style.display = 'block';
                cancelBtnText.textContent = 'กำลังยกเลิก...';
                confirmCancelBtn.disabled = true;
                cancelModalBtn.disabled = true;
            } else {
                cancelLoading.style.display = 'none';
                cancelBtnText.textContent = 'ยกเลิกการสั่งซื้อ';
                confirmCancelBtn.disabled = false;
                cancelModalBtn.disabled = false;
            }
        }

        // ปิดใช้งานฟอร์มทั้งหมดหลังยกเลิก
        function disableAllForms() {
            // ปิดใช้งานฟอร์มการชำระเงิน
            disableForm();

            // ปิดใช้งานปุ่มยกเลิก
            cancelOrderBtn.disabled = true;
            cancelOrderBtn.style.background = '#ccc';
            cancelOrderBtn.style.cursor = 'not-allowed';
            cancelBtnText.textContent = 'ยกเลิกแล้ว';

            // ซ่อนส่วนคำเตือน
            const cancelSection = document.querySelector('.cancel-section');
            if (cancelSection) {
                cancelSection.style.display = 'none';
            }
        }

        // ปิดการใช้งานปุ่มยกเลิกเมื่อไม่สามารถยกเลิกได้
        function disableCancelButton(reason = 'ไม่สามารถยกเลิกได้') {
            cancelOrderBtn.disabled = true;
            cancelOrderBtn.style.background = '#ccc';
            cancelOrderBtn.style.cursor = 'not-allowed';
            cancelBtnText.textContent = reason;
        }

        // เพิ่มการตรวจสอบสถานะออร์เดอร์สำหรับปุ่มยกเลิก
        function checkCancelButtonStatus(orderData) {
            // ตรวจสอบเงื่อนไขที่ไม่สามารถยกเลิกได้
            if (!orderData) return;

            // หากออร์เดอร์ยกเลิกแล้ว
            if (orderData.order_status_id === 5) {
                disableCancelButton('ยกเลิกแล้ว');
                return;
            }

            // หากชำระเงินแล้ว
            if (orderData.payment_status_id === 3) {
                disableCancelButton('ไม่สามารถยกเลิก (ชำระเงินแล้ว)');
                return;
            }

            // หากส่งสินค้าแล้ว
            if (orderData.order_status_id >= 3) {
                disableCancelButton('ไม่สามารถยกเลิก (ส่งสินค้าแล้ว)');
                return;
            }

            // หากหมดเวลาชำระเงินแล้ว (อาจจะให้ยกเลิกได้)
            if (orderData.payment_expire_at && new Date(orderData.payment_expire_at) < new Date()) {
                // สำหรับออร์เดอร์หมดเวลา ให้ยกเลิกได้
                return;
            }
        }

        // อัปเดตฟังก์ชัน updateOrderDisplay เพื่อตรวจสอบสถานะปุ่มยกเลิก
        // เพิ่มโค้ดนี้ในฟังก์ชัน updateOrderDisplay ที่มีอยู่
        function updateOrderDisplayWithCancelCheck(orderData) {
            // เรียกฟังก์ชัน updateOrderDisplay เดิม
            updateOrderDisplay(orderData);

            // ตรวจสอบสถานะปุ่มยกเลิก
            checkCancelButtonStatus(orderData);
        }

        // Handle ESC key เพื่อปิด modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && cancelConfirmModal.classList.contains('show')) {
                hideCancelModal();
            }
        });

        // เพิ่มการตรวจสอบสิทธิ์การยกเลิก (ถ้าต้องการ)
        function canCancelOrder(orderData) {
            if (!orderData) return false;

            // เงื่อนไขที่ไม่สามารถยกเลิกได้
            const cannotCancel = [
                orderData.order_status_id === 5, // ยกเลิกแล้ว
                orderData.payment_status_id === 3, // ชำระเงินแล้ว
                orderData.order_status_id >= 3 // ส่งสินค้าแล้ว
            ];

            return !cannotCancel.some(condition => condition);
        }

        // ฟังก์ชันสำหรับตรวจสอบสถานะใน real-time (เพิ่มเติม)
        function startCancelButtonCheck() {
            setInterval(async () => {
                if (!window.currentOrderId) return;

                try {
                    const response = await fetch(`controller/order_api.php?action=get&order_id=${window.currentOrderId}`);
                    const result = await response.json();

                    if (result.success && result.data) {
                        checkCancelButtonStatus(result.data);
                    }
                } catch (error) {
                    console.log('Cancel button check error:', error.message);
                }
            }, 60000); // ตรวจสอบทุก 1 นาที
        }

        setTimeout(startCancelButtonCheck, 10000);
    </script>
</body>

</html>