<?php
require_once 'controller/config.php';
require_once 'controller/auth_check.php';
redirectIfNotLoggedIn(); // จะ redirect ไป login.php ถ้ายังไม่ login

$auth = new auth_check($pdo);
$check = $auth->redirectIfNotMemberOrder($_GET['order']);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ - Logo Store</title>
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
        max-width: 1000px;
        margin: 0 auto;
        margin-top: 50px;
        margin-bottom: 50px;
    }

    .page-title {
        font-size: 32px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 40px;
        color: #333;
    }

    .search-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .search-form {
        display: flex;
        gap: 15px;
        align-items: end;
    }

    .form-group {
        flex: 1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
    }

    .form-group input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input:focus {
        border-color: #3498db;
        outline: none;
    }

    .search-btn {
        background: #3498db;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        height: fit-content;
        transition: background-color 0.3s;
    }

    .search-btn:hover {
        background: #2980b9;
    }

    .status-container {
        background: white;
        border-radius: 15px;
        padding: 40px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .status-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .status-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 30px;
    }

    .progress-container {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 50px;
        position: relative;
    }

    .progress-line {
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 4px;
        background: #e0e0e0;
        transform: translateY(-50%);
        z-index: 1;
    }

    .progress-line-active {
        position: absolute;
        top: 50%;
        left: 0;
        height: 4px;
        background: #27ae60;
        transform: translateY(-50%);
        z-index: 2;
        width: 0%;
        transition: width 0.5s ease;
    }

    .progress-steps {
        display: flex;
        justify-content: space-between;
        width: 100%;
        position: relative;
        z-index: 3;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
    }

    .step-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-size: 24px;
        border: 3px solid #e0e0e0;
        background: white;
        transition: all 0.3s ease;
    }

    .step-icon.completed {
        background: #27ae60;
        border-color: #27ae60;
        color: white;
    }

    .step-icon.active {
        background: #3498db;
        border-color: #3498db;
        color: white;
        animation: pulse 2s infinite;
    }

    .step-icon.pending {
        background: #f8f9fa;
        border-color: #e0e0e0;
        color: #bdc3c7;
    }

    .step-icon.cancelled {
        background: #e74c3c;
        border-color: #e74c3c;
        color: white;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.1);
        }

        100% {
            transform: scale(1);
        }
    }

    .step-label {
        font-size: 14px;
        text-align: center;
        color: #666;
        font-weight: 500;
    }

    .step-label.active {
        color: #3498db;
        font-weight: bold;
    }

    .step-label.completed {
        color: #27ae60;
        font-weight: bold;
    }

    .step-label.cancelled {
        color: #e74c3c;
        font-weight: bold;
    }

    .order-details {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 25px;
        margin-top: 30px;
    }

    .details-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .detail-row:last-child {
        margin-bottom: 0;
    }

    .detail-label {
        color: #666;
        font-weight: 500;
    }

    .detail-value {
        color: #333;
        font-weight: bold;
    }

    .tracking-number {
        color: #9b59b6;
    }

    .status-message {
        text-align: center;
        background: #e8f5e8;
        border: 2px solid #27ae60;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
    }

    .status-message.processing {
        background: #e3f2fd;
        border-color: #3498db;
    }

    .status-message.pending {
        background: #fff3cd;
        border-color: #ffc107;
    }

    .status-message.cancelled {
        background: #f8d7da;
        border-color: #e74c3c;
    }

    .status-message.expired {
        background: #f8d7da;
        border-color: #dc3545;
    }

    .status-message-text {
        font-size: 16px;
        font-weight: 500;
        color: #27ae60;
    }

    .status-message-text.processing {
        color: #3498db;
    }

    .status-message-text.pending {
        color: #856404;
    }

    .status-message-text.cancelled {
        color: #721c24;
    }

    .status-message-text.expired {
        color: #721c24;
    }

    .order-items {
        background: white;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 25px;
        margin-top: 20px;
    }

    .items-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 0;
        border-bottom: 1px solid #eee;
    }

    .item-row:last-child {
        border-bottom: none;
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

    .item-image::before {
        content: '📷';
        font-size: 2rem;
        opacity: 0.3;
    }

    .item-image.has-image::before {
        display: none;
    }

    .item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #ddd;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .item-info {
        flex: 1;
        margin-left: 15px;
    }

    .item-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }

    .item-details {
        color: #666;
        font-size: 13px;
    }

    .item-quantity {
        color: #666;
        margin: 0 20px;
        font-size: 14px;
    }

    .item-price {
        color: #333;
        font-weight: 600;
        font-size: 14px;
    }

    .payment-info {
        background: #f0f8ff;
        border: 2px solid #3498db;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
    }

    .payment-info.expired {
        background: #fff5f5;
        border-color: #e74c3c;
    }

    .payment-time {
        text-align: center;
        color: #2c3e50;
    }

    .error-message {
        background: #f8d7da;
        border: 2px solid #e74c3c;
        border-radius: 10px;
        padding: 20px;
        text-align: center;
        color: #721c24;
        font-weight: 500;
    }

    .loading {
        text-align: center;
        padding: 40px;
        color: #666;
    }

    .refresh-btn {
        display: block;
        margin: 20px auto 0;
        background: #9b59b6;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .refresh-btn:hover {
        background: #8e44ad;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .action-btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .action-btn.payment {
        background: #27ae60;
        color: white;
    }

    .action-btn.payment:hover {
        background: #229954;
    }

    .action-btn.cancel {
        background: #e74c3c;
        color: white;
    }

    .action-btn.cancel:hover {
        background: #c0392b;
    }

    .action-btn.refresh {
        background: #9b59b6;
        color: white;
    }

    .action-btn.refresh:hover {
        background: #8e44ad;
    }

    .action-btn.delivery {
        background: #27ae60;
        color: white;
    }

    .action-btn.delivery:hover {
        background: #229954;
    }

    .estimated-delivery {
        text-align: center;
        margin-top: 20px;
        padding: 15px;
        background: #f0f8ff;
        border-radius: 8px;
        border-left: 4px solid #3498db;
    }

    .estimated-delivery-text {
        color: #2c3e50;
        font-size: 14px;
    }

    .estimated-date {
        font-weight: bold;
        color: #3498db;
        font-size: 16px;
    }

    .status-history {
        background: white;
        border-radius: 15px;
        padding: 30px;
        margin-top: 20px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .history-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .history-timeline {
        position: relative;
        padding-left: 30px;
    }

    .history-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }

    .history-item {
        position: relative;
        margin-bottom: 25px;
        padding-left: 35px;
    }

    .history-item:last-child {
        margin-bottom: 0;
    }

    .history-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #3498db;
        border: 3px solid white;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .history-item.completed::before {
        background: #27ae60;
    }

    .history-item.cancelled::before {
        background: #e74c3c;
    }

    .history-item.processing::before {
        background: #f39c12;
    }

    .history-item.pending::before {
        background: #95a5a6;
    }

    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .history-status {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .history-date {
        font-size: 13px;
        color: #666;
        white-space: nowrap;
        margin-left: 10px;
    }

    .history-details {
        font-size: 13px;
        color: #777;
        line-height: 1.4;
    }

    .history-changed-by {
        font-style: italic;
        color: #9b59b6;
        font-size: 12px;
        margin-top: 3px;
    }

    .history-notes {
        background: #f8f9fa;
        border-left: 3px solid #dee2e6;
        padding: 8px 12px;
        margin-top: 8px;
        font-size: 13px;
        color: #555;
        border-radius: 0 4px 4px 0;
    }

    .no-history {
        text-align: center;
        color: #999;
        font-style: italic;
        padding: 20px;
    }


    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .status-container {
            padding: 20px;
        }

        .page-title {
            font-size: 24px;
        }

        .status-title {
            font-size: 20px;
        }

        .progress-steps {
            flex-direction: column;
            gap: 30px;
        }

        .progress-line,
        .progress-line-active {
            display: none;
        }

        .step {
            flex-direction: row;
            justify-content: flex-start;
            text-align: left;
            width: 100%;
        }

        .step-icon {
            margin-right: 15px;
            margin-bottom: 0;
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .detail-row {
            flex-direction: column;
            gap: 5px;
        }

        .item-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .item-image {
            width: 60px;
            height: 60px;
            margin-right: 0;
            margin-bottom: 10px;
        }

        .action-buttons {
            flex-direction: column;
            align-items: center;
        }

        .action-btn {
            width: 100%;
            max-width: 300px;
            text-align: center;
        }

        .status-history {
            padding: 20px;
        }

        .history-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .history-date {
            margin-left: 0;
            margin-top: 2px;
        }

        .history-timeline {
            padding-left: 20px;
        }

        .history-item {
            padding-left: 25px;
        }

        .history-item::before {
            left: -18px;
        }
    }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container">
        <!--<h1 class="page-title">สถานะคำสั่งซื้อ</h1>-->

        <!-- Order Status Container -->
        <div class="status-container" id="statusContainer">
            <div class="status-header">
                <h2 class="status-title" id="orderTitle">สถานะคำสั่งซื้อ</h2>

                <div class="progress-container">
                    <div class="progress-line"></div>
                    <div class="progress-line-active" id="progressLine"></div>

                    <div class="progress-steps">
                        <div class="step">
                            <div class="step-icon pending" id="step1">💳</div>
                            <div class="step-label">รอการยืนยัน</div>
                        </div>

                        <div class="step">
                            <div class="step-icon pending" id="step2">📦</div>
                            <div class="step-label">ชำระเงินแล้ว</div>
                        </div>

                        <div class="step">
                            <div class="step-icon pending" id="step3">🚚</div>
                            <div class="step-label">กำลังจัดส่ง</div>
                        </div>

                        <div class="step">
                            <div class="step-icon pending" id="step4">✓</div>
                            <div class="step-label">จัดส่งสำเร็จ</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="status-message" id="statusMessage">
                <div class="status-message-text" id="statusText"></div>
            </div>

            <!-- Order Status History -->
            <div class="status-history" id="statusHistoryContainer" style="display: none;">
                <div class="history-title">ประวัติการเปลี่ยนสถานะ</div>
                <div class="history-timeline" id="historyTimeline">
                    <!-- History items will be loaded here -->
                </div>
            </div>

            <!-- Payment Info -->
            <div class="payment-info" id="paymentInfo" style="display: none;">
                <div class="payment-time" id="paymentTime"></div>
            </div>

            <!-- Estimated Delivery (only show when shipped) -->
            <div class="estimated-delivery" id="estimatedDelivery" style="display: none;">
                <div class="estimated-delivery-text">วันที่คาดว่าจะได้รับสินค้า</div>
                <div class="estimated-date" id="estimatedDate"></div>
            </div>

            <!-- Order Details -->
            <div class="order-details" id="orderDetailsContainer"></div>

            <!-- Order Items -->
            <div class="order-items" id="orderItemsContainer" style="display: none;"></div>

            <div id="actionButtons" class="action-buttons"></div>
        </div>

        <!-- Error/Loading Messages -->
        <div id="messageContainer"></div>
    </div>

    <?php include("includes/MainFooter.php"); ?>

    <!-- โหลด notification.js -->
    <script src="assets/js/notification.js"></script>

    <script>
    let currentOrderData = null;

    // Order status mapping
    const statusConfig = {
        1: { // รอการชำระ
            step: 1,
            status: 'pending',
            message: 'รอการชำระเงิน กรุณาชำระเงินภายในเวลาที่กำหนด',
            messageClass: 'pending',
            progressWidth: '0%',
            stepLabel: 'รอการยืนยัน'
        },
        2: { // รอการอนุมัติ
            step: 2,
            status: 'pending',
            message: 'ชำระเงินแล้ว รอการอนุมัติจากเจ้าหน้าที่',
            messageClass: 'processing',
            progressWidth: '40%',
            stepLabel: 'ตรวจสอบการชำระ'
        },
        3: { // กำลังดำเนินการ
            step: 3,
            status: 'processing',
            message: 'คำสั่งซื้อของคุณกำลังถูกจัดเตรียม กรุณารออีกสักครู่',
            messageClass: 'processing',
            progressWidth: '64%',
            stepLabel: 'จัดเตรียมสินค้า'
        },
        4: { // จัดส่งสำเร็จ
            step: 4,
            status: 'delivered',
            message: 'สินค้าถูกจัดส่งเรียบร้อยแล้ว ขอบคุณที่ใช้บริการ',
            messageClass: 'completed',
            progressWidth: '100%',
            stepLabel: 'จัดส่งสำเร็จ'
        },
        5: { // ยกเลิก/คืนเงิน
            step: 0,
            status: 'cancelled',
            message: 'คำสั่งซื้อถูกยกเลิกแล้ว',
            messageClass: 'cancelled',
            progressWidth: '0%',
            stepLabel: 'ยกเลิก'
        }
    };

    function displayOrderStatus(orderData) {
        const config = statusConfig[orderData.order_status] || statusConfig[1];

        // Update order title
        document.getElementById('orderTitle').textContent = `คำสั่งซื้อ #${orderData.order_number}`;

        // Update status message
        updateStatusMessage(config);

        // Update progress
        updateProgress(config, orderData.order_status);

        // Update order details
        updateOrderDetails(orderData);

        // Update order items if available
        if (orderData.items && orderData.items.length > 0) {
            updateOrderItems(orderData.items);
        } else {
            // Load order items separately if not included
            loadOrderItems(orderData.order_id);
        }

        // Show payment info if pending payment
        updatePaymentInfo(orderData);

        // Show estimated delivery if shipped
        updateEstimatedDelivery(orderData);

        // Update action buttons based on order status
        updateActionButtons(orderData);

        // โหลดประวัติการเปลี่ยนสถานะ
        loadStatusHistory(orderData.order_id);
    }

    function updateStatusMessage(config) {
        const statusMessage = document.getElementById('statusMessage');
        const statusText = document.getElementById('statusText');

        statusMessage.className = `status-message ${config.messageClass}`;
        statusText.className = `status-message-text ${config.messageClass}`;
        statusText.textContent = config.message;
    }

    function updateProgress(config, statusId) {
        // Update progress line
        document.getElementById('progressLine').style.width = config.progressWidth;

        // Update step icons and labels
        const stepIcons = ['💳', '📦', '🚚', '✓'];
        const stepLabels = ['รอการยืนยัน', 'ชำระเงินแล้ว', 'กำลังจัดส่ง', 'จัดส่งสำเร็จ'];

        for (let i = 1; i <= 4; i++) {
            const stepIcon = document.getElementById(`step${i}`);
            const stepLabel = stepIcon.parentElement.querySelector('.step-label');

            // Reset classes
            stepIcon.className = 'step-icon';
            stepLabel.className = 'step-label';

            if (statusId === 5) { // Cancelled
                if (i === 1) {
                    stepIcon.className = 'step-icon cancelled';
                    stepLabel.className = 'step-label cancelled';
                    stepIcon.textContent = '✗';
                    stepLabel.textContent = 'ยกเลิก';
                } else {
                    stepIcon.className = 'step-icon pending';
                    stepLabel.className = 'step-label';
                    stepIcon.textContent = stepIcons[i - 1];
                    stepLabel.textContent = stepLabels[i - 1];
                }
            } else if (i < config.step) {
                // Completed steps
                stepIcon.className = 'step-icon completed';
                stepLabel.className = 'step-label completed';
                stepIcon.textContent = '✓';
                stepLabel.textContent = stepLabels[i - 1];
            } else if (i === config.step) {
                // Current active step
                stepIcon.className = 'step-icon active';
                stepLabel.className = 'step-label active';
                stepIcon.textContent = stepIcons[i - 1];
                stepLabel.textContent = stepLabels[i - 1];
            } else {
                // Pending steps
                stepIcon.className = 'step-icon pending';
                stepLabel.className = 'step-label';
                stepIcon.textContent = stepIcons[i - 1];
                stepLabel.textContent = stepLabels[i - 1];
            }
        }
    }

    function updateOrderDetails(orderData) {
        const container = document.getElementById('orderDetailsContainer');

        const formatDate = (dateStr) => {
            const date = new Date(dateStr);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        container.innerHTML = `
                <div class="details-title">รายละเอียดคำสั่งซื้อ</div>
                <div class="detail-row">
                    <span class="detail-label">หมายเลขคำสั่งซื้อ:</span>
                    <span class="detail-value">#${orderData.order_number}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">วันที่สั่งซื้อ:</span>
                    <span class="detail-value">${formatDate(orderData.create_at)}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ชื่อผู้รับ:</span>
                    <span class="detail-value">${orderData.recipient_name}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ที่อยู่จัดส่ง:</span>
                    <span class="detail-value">${orderData.shipping_address}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">เบอร์โทรศัพท์:</span>
                    <span class="detail-value">${orderData.shipping_phone}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ยอดรวมทั้งหมด:</span>
                    <span class="detail-value">฿${parseFloat(orderData.total_amount).toLocaleString()}</span>
                </div>
                ${orderData.tracking_number ? `
                <div class="detail-row">
                    <span class="detail-label">หมายเลขติดตาม:</span>
                    <span class="detail-value tracking-number">${orderData.tracking_number}</span>
                </div>
                ` : `
                <div class="detail-row">
                    <span class="detail-label">หมายเลขติดตาม:</span>
                    <span class="detail-value tracking-number">-</span>
                </div>
                `}
                ${orderData.note ? `
                <div class="detail-row">
                    <span class="detail-label">หมายเหตุ:</span>
                    <span class="detail-value">${orderData.note}</span>
                </div>
                ` : `
                <div class="detail-row">
                    <span class="detail-label">หมายเหตุ:</span>
                    <span class="detail-value">-</span>
                </div>
                `}
            `;
    }

    async function loadOrderItems(orderId) {
        try {
            const response = await fetch(`order_api.php?action=get&order_id=${orderId}`);
            const data = await response.json();

            if (data.success && data.data && data.data.items) {
                updateOrderItems(data.data.items);
            }
        } catch (error) {
            console.error('Error loading order items:', error);
        }
    }

    function updateOrderItems(items) {
        const container = document.getElementById('orderItemsContainer');
        container.style.display = 'block';

        let itemsHtml = '<div class="items-title">รายการสินค้า</div>';

        items.forEach(item => {
            const imagePath = `controller/uploads/products/${item.img_path}`;
            itemsHtml += `
                    <div class="item-row">
                        <div class="item-image ${item.img_path ? 'has-image' : ''}">
                            <img src="${imagePath}" alt="${item.shoename}" onerror="this.style.display='none'">
                        </div>
                        <div class="item-info">
                            <div class="item-name">${item.shoename}</div>
                            <div class="item-details">
                                ไซส์: ${item.size}
                            </div>
                        </div>
                        <div class="item-quantity">จำนวน: ${item.quantity}</div>
                        <div class="item-price">฿${parseFloat(item.unit_price || item.price).toLocaleString()}</div>
                    </div>
                `;
        });

        container.innerHTML = itemsHtml;
    }

    function updatePaymentInfo(orderData) {
        const paymentInfo = document.getElementById('paymentInfo');
        const paymentTime = document.getElementById('paymentTime');

        if (orderData.order_status === 1 && orderData.payment_due_date) {
            const dueDate = new Date(orderData.payment_due_date);
            const now = new Date();
            const timeLeft = dueDate - now;

            if (timeLeft > 0) {
                paymentInfo.style.display = 'block';
                paymentInfo.className = 'payment-info';

                const hours = Math.floor(timeLeft / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));

                paymentTime.innerHTML = `
                        <strong>เวลาที่เหลือในการชำระเงิน</strong><br>
                        ${hours} ชั่วโมง ${minutes} นาที<br>
                        <small>ครบกำหนด: ${dueDate.toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}</small>
                    `;
            } else {
                paymentInfo.style.display = 'block';
                paymentInfo.className = 'payment-info expired';
                paymentTime.innerHTML = `
                        <strong style="color: #e74c3c;">หมดเวลาชำระเงินแล้ว</strong><br>
                        <small>คำสั่งซื้ออาจถูกยกเลิกโดยอัตโนมัติ</small>
                    `;
            }
        } else {
            paymentInfo.style.display = 'none';
        }
    }

    function updateActionButtons(orderData) {
        const actionButtons = document.getElementById('actionButtons');

        if (orderData.order_status == 1) {
            // Show payment and cancel buttons for pending payment orders
            actionButtons.innerHTML = `
            <button class="action-btn payment" onclick="processPayment()">ชำระเงิน</button>
            <button class="action-btn cancel" onclick="cancelOrder()">ยกเลิก</button>
        `;
        } else if (orderData.order_status == 3 && orderData.tracking_number) {
            // Show confirm delivery button for shipped orders with tracking number
            actionButtons.innerHTML = `
            <button class="action-btn delivery" onclick="confirmDelivery()">ได้รับสินค้าแล้ว</button>
        `;
        } else if (orderData.order_status == 4 || orderData.order_status == 5) {
            // Hide buttons for completed or cancelled orders
            actionButtons.innerHTML = '';
        } else {
            // Show refresh button for other statuses
            actionButtons.innerHTML = ``;
        }
    }

    async function loadStatusHistory(orderId) {
        try {
            const response = await fetch(`controller/order_api.php?action=status-history&order_id=${orderId}`);
            const data = await response.json();

            if (data.success && data.data) {
                displayStatusHistory(data.data);
                document.getElementById('statusHistoryContainer').style.display = 'block';
            } else {
                console.log('No history data available');
                // ซ่อน history container ถ้าไม่มีข้อมูล
                document.getElementById('statusHistoryContainer').style.display = 'none';
            }
        } catch (error) {
            console.error('Error loading status history:', error);
            document.getElementById('statusHistoryContainer').style.display = 'none';
        }
    }

    function displayStatusHistory(historyData) {
        const timeline = document.getElementById('historyTimeline');

        if (!historyData || historyData.length === 0) {
            timeline.innerHTML = '<div class="no-history">ไม่มีประวัติการเปลี่ยนสถานะ</div>';
            return;
        }

        // เรียงลำดับตามเวลาจากใหม่ไปเก่า
        const sortedHistory = historyData.sort((a, b) => new Date(b.create_at) - new Date(a.create_at));

        let historyHTML = '';

        sortedHistory.forEach(item => {
            const formatDate = (dateStr) => {
                const date = new Date(dateStr);
                return date.toLocaleDateString('th-TH', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            };

            // กำหนดสถานะและข้อความ
            const statusInfo = getStatusInfo(item.old_status, item.new_status);

            // สร้างชื่อผู้เปลี่ยนสถานะ
            let changedByName = '';
            if (item.changed_by_name) {
                changedByName = item.changed_by_name;
            } else if (item.first_name && item.last_name) {
                changedByName = `${item.first_name} ${item.last_name}`;
            } else if (item.changed_by) {
                changedByName = item.changed_by;
            }

            historyHTML += `
            <div class="history-item ${statusInfo.class}">
                <div class="history-header">
                    <div class="history-status">${statusInfo.message}</div>
                    <div class="history-date">${formatDate(item.create_at)}</div>
                </div>
                <div class="history-details">
                    ${item.old_status ? `จาก: ${getStatusText(item.old_status)}` : 'สถานะเริ่มต้น'} 
                    → ${item.status_name || getStatusText(item.new_status)}
                </div>
                
                ${item.notes ? `<div class="history-notes">${item.notes}</div>` : ''}
            </div>
        `;
        });

        timeline.innerHTML = historyHTML;
    }

    function getStatusInfo(oldStatus, newStatus) {
        const statusMap = {
            1: {
                text: 'รอการชำระเงิน',
                class: 'pending'
            },
            2: {
                text: 'รอการอนุมัติ',
                class: 'processing'
            },
            3: {
                text: 'กำลังจัดส่ง',
                class: 'processing'
            },
            4: {
                text: 'จัดส่งสำเร็จ',
                class: 'completed'
            },
            5: {
                text: 'ยกเลิก',
                class: 'cancelled'
            }
        };

        const newStatusInfo = statusMap[newStatus] || {
            text: 'ไม่ทราบสถานะ',
            class: 'pending'
        };

        let message = '';
        switch (newStatus) {
            case 1:
                message = 'สร้างคำสั่งซื้อ - รอการชำระเงิน';
                break;
            case 2:
                message = 'ได้รับการชำระเงิน - รอการอนุมัติ';
                break;
            case 3:
                message = 'อนุมัติการชำระแล้ว - กำลังจัดเตรียมสินค้า';
                break;
            case 4:
                message = 'จัดส่งสินค้าเรียบร้อย';
                break;
            case 5:
                message = 'ยกเลิกคำสั่งซื้อ';
                break;
            default:
                message = `เปลี่ยนสถานะเป็น ${newStatusInfo.text}`;
        }

        return {
            message: message,
            class: newStatusInfo.class
        };
    }

    function getStatusText(statusId) {
        const statusTexts = {
            1: 'รอการชำระเงิน',
            2: 'รอการอนุมัติ',
            3: 'กำลังจัดส่ง',
            4: 'จัดส่งสำเร็จ',
            5: 'ยกเลิก'
        };

        return statusTexts[statusId] || 'ไม่ทราบสถานะ';
    }

    function updateEstimatedDelivery(orderData) {
        const estimatedDelivery = document.getElementById('estimatedDelivery');
        const estimatedDate = document.getElementById('estimatedDate');

        // Show estimated delivery for shipped orders (status 3 or 4)
        if (orderData.order_status === 3 || orderData.order_status === 4) {
            estimatedDelivery.style.display = 'block';

            // Calculate estimated delivery date (3-5 days from now for status 3, or actual delivery date for status 4)
            let deliveryDate = new Date();
            if (orderData.order_status === 3) {
                deliveryDate.setDate(deliveryDate.getDate() + 3); // 3 days from now
                estimatedDate.textContent = `${deliveryDate.toLocaleDateString('th-TH', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    })} - ${new Date(deliveryDate.getTime() + 2*24*60*60*1000).toLocaleDateString('th-TH', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    })}`;
            } else {
                // For delivered orders, show completion date
                estimatedDate.textContent = new Date(orderData.update_at).toLocaleDateString('th-TH', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
                document.querySelector('.estimated-delivery-text').textContent = 'วันที่จัดส่งสำเร็จ';
            }
        } else {
            estimatedDelivery.style.display = 'none';
        }
    }

    async function processPayment() {
        if (!currentOrderData) {
            showError('ไม่พบข้อมูลคำสั่งซื้อ');
            return;
        }

        // Redirect to payment page or show payment modal
        const paymentUrl = `order-payment.php?order=${currentOrderData.order_number}`;
        window.location.href = paymentUrl;
    }

    async function cancelOrder() {
        if (!currentOrderData) {
            showError('ไม่พบข้อมูลคำสั่งซื้อ');
            return;
        }

        showConfirm('คุณแน่ใจหรือไม่ที่จะยกเลิกคำสั่งซื้อนี้?', async function() {
            const hideLoading = showLoading('กำลังยกเลิกคำสั่งซื้อ...');

            try {
                const response = await fetch(
                    `controller/order_api.php?action=cancel&order_id=${currentOrderData.order_id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: currentOrderData.order_id,
                            changed_by: 'customer',
                            reason: 'ยกเลิกโดยลูกค้า'
                        })
                    });

                const data = await response.json();

                if (data.success) {
                    showSuccess('ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว');
                    // Refresh order data
                    await loadOrderById(currentOrderData.order_id);
                } else {
                    showError(data.message || 'ไม่สามารถยกเลิกคำสั่งซื้อได้');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการยกเลิกคำสั่งซื้อ');
            } finally {
                hideLoading();
            }
        });
    }

    async function confirmDelivery() {
        if (!currentOrderData) {
            showError('ไม่พบข้อมูลคำสั่งซื้อ');
            return;
        }

        showConfirm('คุณแน่ใจหรือไม่ที่ได้รับสินค้าแล้ว?', async function() {
            const hideLoading = showLoading('กำลังยืนยันการรับสินค้า...');

            try {
                const response = await fetch(
                    `controller/order_api.php?action=complete-order&order_id=${currentOrderData.order_id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            changed_by: 'customer',
                            delivery_confirmed: true
                        })
                    });

                const data = await response.json();

                if (data.success) {
                    showSuccess('ยืนยันการรับสินค้าเรียบร้อยแล้ว');
                    // Refresh order data to show updated status
                    await loadOrderById(currentOrderData.order_id);
                } else {
                    showError(data.message || 'ไม่สามารถยืนยันการรับสินค้าได้');
                }
            } catch (error) {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการยืนยันการรับสินค้า');
            } finally {
                hideLoading();
            }
        });
    }

    async function refreshStatus() {
        if (!currentOrderData) {
            showError('ไม่พบข้อมูลคำสั่งซื้อ');
            return;
        }

        const hideLoading = showLoading('กำลังอัปเดตสถานะ...');

        try {
            const response = await fetch(`order_api.php?action=get&order_id=${currentOrderData.order_id}`);
            const data = await response.json();

            if (data.success && data.data) {
                currentOrderData = data.data;
                displayOrderStatus(data.data);
                showSuccess('อัปเดตสถานะเรียบร้อยแล้ว');
            } else {
                showError('ไม่สามารถอัปเดตสถานะได้');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('เกิดข้อผิดพลาดในการอัปเดต');
        } finally {
            hideLoading();
        }
    }

    function showMessage(message, type) {
        const container = document.getElementById('messageContainer');
        let className = 'error-message';

        if (type === 'loading') {
            className = 'loading';
        }

        container.innerHTML = `<div class="${className}">${message}</div>`;
    }

    function hideStatusContainer() {
        document.getElementById('statusContainer').style.display = 'none';
    }

    // Auto-refresh every 30 seconds if order is found and not completed/cancelled
    setInterval(() => {
        if (currentOrderData &&
            currentOrderData.order_status_id !== 4 &&
            currentOrderData.order_status_id !== 5) {
            // Silent refresh without showing loading message
            fetch(`order_api.php?action=get&order_id=${currentOrderData.order_id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data) {
                        const oldStatus = currentOrderData.order_status;
                        currentOrderData = data.data;

                        // Show notification if status changed
                        if (oldStatus !== data.data.order_status) {
                            displayOrderStatus(data.data);
                            showInfo('สถานะคำสั่งซื้อมีการอัปเดต');
                        } else {
                            // Update payment countdown if still pending
                            if (data.data.order_status === 1) {
                                updatePaymentInfo(data.data);
                            }
                        }
                    }
                })
                .catch(error => {
                    console.error('Auto-refresh error:', error);
                });
        }
    }, 30000);

    // Check URL parameters and load order directly
    window.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const orderNumber = urlParams.get('order') || urlParams.get('order_number');
        const orderId = urlParams.get('order_id') || urlParams.get('id');

        if (orderId) {
            // Load by order ID (preferred method)
            loadOrderById(orderId);
        } else if (orderNumber) {
            // Load by order number (fallback)
            loadOrderByNumber(orderNumber);
        } else {
            // No parameters provided
            showMessage('ไม่พบข้อมูลคำสั่งซื้อที่ระบุ', 'error');
        }
    });

    async function loadOrderById(orderId) {
        const hideLoading = showLoading('กำลังโหลดข้อมูลคำสั่งซื้อ...');

        try {
            const response = await fetch(
                `controller/order_api.php?action=get&order_id=${encodeURIComponent(orderId)}`);
            const data = await response.json();

            if (data.success && data.data) {
                currentOrderData = data.data;
                displayOrderStatus(data.data);
                document.getElementById('messageContainer').innerHTML = '';
            } else {
                showMessage(data.message || 'ไม่พบคำสั่งซื้อที่ระบุ', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
        } finally {
            hideLoading();
        }
    }

    async function loadOrderByNumber(orderNumber) {
        const hideLoading = showLoading('กำลังโหลดข้อมูลคำสั่งซื้อ...');

        try {
            const response = await fetch(
                `controller/order_api.php?action=get-by-number&order_number=${encodeURIComponent(orderNumber)}`);
            const data = await response.json();

            if (data.success && data.data) {
                currentOrderData = data.data;
                displayOrderStatus(data.data);
                document.getElementById('messageContainer').innerHTML = '';
            } else {
                showMessage(data.message || 'ไม่พบคำสั่งซื้อที่ระบุ', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
        } finally {
            hideLoading();
        }
    }
    </script>
</body>

</html>