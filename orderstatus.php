<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะคำสั่งซื้อ - Logo Store</title>
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
            margin-right: 15px;
            flex-shrink: 0;
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
        }
    </style>
</head>

<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container" >
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
                            <div class="step-label">รอการชำระ</div>
                        </div>

                        <div class="step">
                            <div class="step-icon pending" id="step2">📦</div>
                            <div class="step-label">จัดเตรียมสินค้า</div>
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
                stepLabel: 'รอการชำระ'
            },
            2: { // รอการอนุมัติ
                step: 1,
                status: 'pending',
                message: 'กำลังตรวจสอบการชำระเงิน รอการอนุมัติจากเจ้าหน้าที่',
                messageClass: 'processing',
                progressWidth: '0%',
                stepLabel: 'ตรวจสอบการชำระ'
            },
            3: { // กำลังดำเนินการ
                step: 2,
                status: 'processing',
                message: 'คำสั่งซื้อของคุณกำลังถูกจัดเตรียม กรุณารออีกสักครู่',
                messageClass: 'processing',
                progressWidth: '33.33%',
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
            const stepLabels = ['รอการชำระ', 'จัดเตรียมสินค้า', 'กำลังจัดส่ง', 'จัดส่งสำเร็จ'];

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
                ` : ''}
                ${orderData.notes ? `
                <div class="detail-row">
                    <span class="detail-label">หมายเหตุ:</span>
                    <span class="detail-value">${orderData.notes}</span>
                </div>
                ` : ''}
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
                const imagePath = item.img_path ? `controller/uploads/products/${item.img_path}` : 'assets/images/ico.jpg';
                itemsHtml += `
                    <div class="item-row">
                        <div class="item-image">
                            <img src="${imagePath}" alt="${item.shoename}" onerror="this.src='assets/images/ico.jpg'">
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

            if (orderData.order_status_id === 1 && orderData.payment_due_date) {
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
                showMessage('ไม่พบข้อมูลคำสั่งซื้อ', 'error');
                return;
            }

            // Redirect to payment page or show payment modal
            const paymentUrl = `order-payment.php?order=${currentOrderData.order_number}`;
            window.location.href = paymentUrl;
        }

        async function cancelOrder() {
            if (!currentOrderData) {
                showMessage('ไม่พบข้อมูลคำสั่งซื้อ', 'error');
                return;
            }

            if (!confirm('คุณแน่ใจหรือไม่ที่จะยกเลิกคำสั่งซื้อนี้?')) {
                return;
            }

            try {
                const response = await fetch(`controller/order_api.php?action=cancel&order_id=${currentOrderData.order_id}`, {
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
                    showNotification('ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว');
                    // Refresh order data
                    await loadOrderById(currentOrderData.order_id);
                } else {
                    showMessage(data.message || 'ไม่สามารถยกเลิกคำสั่งซื้อได้', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('เกิดข้อผิดพลาดในการยกเลิกคำสั่งซื้อ', 'error');
            }
        }

        async function confirmDelivery() {
            if (!currentOrderData) {
                showMessage('ไม่พบข้อมูลคำสั่งซื้อ', 'error');
                return;
            }

            if (!confirm('คุณแน่ใจหรือไม่ที่ได้รับสินค้าแล้ว?')) {
                return;
            }

            const btn = document.querySelector('.action-btn.delivery');
            const originalText = btn.textContent;
            btn.textContent = 'กำลังยืนยัน...';
            btn.disabled = true;

            try {
                const response = await fetch(`controller/order_api.php?action=complete-order&order_id=${currentOrderData.order_id}`, {
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
                    showNotification('ยืนยันการรับสินค้าเรียบร้อยแล้ว');
                    // Refresh order data to show updated status
                    await loadOrderById(currentOrderData.order_id);
                } else {
                    showMessage(data.message || 'ไม่สามารถยืนยันการรับสินค้าได้', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('เกิดข้อผิดพลาดในการยืนยันการรับสินค้า', 'error');
            } finally {
                btn.textContent = originalText;
                btn.disabled = false;
            }
        }

        async function refreshStatus() {
            if (!currentOrderData) {
                showMessage('ไม่พบข้อมูลคำสั่งซื้อ', 'error');
                return;
            }

            const btn = document.querySelector('.refresh-btn');
            const originalText = btn.textContent;
            btn.textContent = 'กำลังอัปเดต...';
            btn.disabled = true;

            try {
                const response = await fetch(`order_api.php?action=get&order_id=${currentOrderData.order_id}`);
                const data = await response.json();

                if (data.success && data.data) {
                    currentOrderData = data.data;
                    displayOrderStatus(data.data);
                    showNotification('อัปเดตสถานะเรียบร้อยแล้ว');
                } else {
                    showMessage('ไม่สามารถอัปเดตสถานะได้', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('เกิดข้อผิดพลาดในการอัปเดต', 'error');
            } finally {
                btn.textContent = originalText;
                btn.disabled = false;
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

        function showNotification(message) {
            // Create notification element
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: #27ae60;
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 1000;
                font-size: 14px;
                font-weight: 500;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
                max-width: 300px;
            `;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);

            // Animate out and remove
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
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
                                showNotification('สถานะคำสั่งซื้อมีการอัปเดต');
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
            showMessage('กำลังโหลดข้อมูลคำสั่งซื้อ...', 'loading');

            try {
                const response = await fetch(`controller/order_api.php?action=get&order_id=${encodeURIComponent(orderId)}`);
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
                showMessage('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
            }
        }

        async function loadOrderByNumber(orderNumber) {
            showMessage('กำลังโหลดข้อมูลคำสั่งซื้อ...', 'loading');

            try {
                const response = await fetch(`controller/order_api.php?action=get-by-number&order_number=${encodeURIComponent(orderNumber)}`);
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
                showMessage('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
            }
        }
    </script>
</body>

</html>