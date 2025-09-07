
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

    /* เพิ่ม CSS สำหรับ disabled state */
    .payment-section.disabled {
        opacity: 0.6;
        pointer-events: none;
        background: #f8f8f8;
    }

    .file-upload.disabled {
        background: #f8f8f8 !important;
        border-color: #ddd !important;
        cursor: not-allowed !important;
        pointer-events: none;
    }

    .file-upload.disabled:hover {
        transform: none !important;
    }

    .file-upload.disabled .upload-text {
        color: #999 !important;
    }

    .status-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-left: 4px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        color: #856404;
    }

    .status-error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-left: 4px solid #dc3545;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        color: #721c24;
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
                <div class="payment-section" id="payment-method-section">
                    <div class="section-title">
                        <div class="section-number">2</div>
                        วิธีการชำระเงิน
                    </div>

                    <div class="bank-info">
                        <div class="bank-header">
                            <div class="bank-icon">
                                <img src="" alt="Bank" style="width: 30px; height: 30px;"
                                    onerror="this.style.display='none'">
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
                <div class="payment-section" id="upload-section">
                    <div class="section-title">
                        <div class="section-number">3</div>
                        อัปโหลดหลักฐานการชำระเงิน
                    </div>

                    <!-- Status Warning/Error Messages -->
                    <div id="status-message" style="display: none;"></div>

                    <form id="paymentForm">
                        <div class="upload-section">
                            <label class="upload-label">อัปโหลดหลักฐานการชำระเงิน</label>
                            <div class="file-upload" id="file-upload-area">
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

                <!-- Cancel Order Section -->
                <div class="cancel-section" id="cancel-section">
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
                                <img src="" alt="สินค้า" >
                            </div>
                            <div class="item-info">
                                <div class="item-name">รองเท้าผ้าใบ Nike</div>
                                <div class="item-quantity">จำนวน: 2</div>
                            </div>
                            <div class="item-price">฿398</div>
                        </div>

                        <div class="order-item">
                            <div class="item-image">
                                <img src="" alt="สินค้า" >
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



    
</body>

</html>