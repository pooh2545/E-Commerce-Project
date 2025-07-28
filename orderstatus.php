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
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .status-container {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
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
            width: 33.33%;
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

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
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
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container" style="margin-top: 30px;">
        <h1 class="page-title">สถานะคำสั่งซื้อ</h1>
        
        <div class="status-container">
            <div class="status-header">
                <h2 class="status-title">สถานะคำสั่งซื้อ</h2>
                
                <div class="progress-container">
                    <div class="progress-line"></div>
                    <div class="progress-line-active" id="progressLine"></div>
                    
                    <div class="progress-steps">
                        <div class="step">
                            <div class="step-icon completed" id="step1">
                                ✓
                            </div>
                            <div class="step-label completed">รอการชำระ</div>
                        </div>
                        
                        <div class="step">
                            <div class="step-icon active" id="step2">
                                📦
                            </div>
                            <div class="step-label active">ชำระเงินแล้ว</div>
                        </div>
                        
                        <div class="step">
                            <div class="step-icon pending" id="step3">
                                🚚
                            </div>
                            <div class="step-label">กำลังจัดส่ง</div>
                        </div>
                        
                        <div class="step">
                            <div class="step-icon pending" id="step4">
                                ✓
                            </div>
                            <div class="step-label">จัดส่งสำเร็จ</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="status-message processing">
                <div class="status-message-text processing">
                    คำสั่งซื้อของคุณกำลังถูกจัดเตรียม กรุณารออีกสักครู่
                </div>
            </div>

            <div class="estimated-delivery">
                <div class="estimated-delivery-text">
                    วันที่คาดว่าจะได้รับสินค้า
                </div>
                <div class="estimated-date">
                    29-31 กรกฎาคม 2025
                </div>
            </div>

            <div class="order-details">
                <div class="details-title">รายละเอียดคำสั่งซื้อ</div>
                
                <div class="detail-row">
                    <span class="detail-label">หมายเลขคำสั่งซื้อ:</span>
                    <span class="detail-value">#ORD2568</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">วันที่สั่งซื้อ:</span>
                    <span class="detail-value">26 กรกฎาคม 2025, 14:30</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">วิธีการจัดส่ง:</span>
                    <span class="detail-value">Kerry Express (ส่งด่วน)</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">ที่อยู่จัดส่ง:</span>
                    <span class="detail-value">123 ถ.สุขุมวิท เขตวัฒนา กรุงเทพฯ 10110</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">ยอดรวมทั้งหมด:</span>
                    <span class="detail-value">฿836</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">หมายเลขติดตาม:</span>
                    <span class="detail-value tracking-number">TH1234567890</span>
                </div>
            </div>
            <!--
            <button class="refresh-btn" onclick="refreshStatus()">
                อัปเดตสถานะ
            </button>
        -->
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script>
        // Simulate different order statuses
        const orderStatuses = [
            {
                step: 1,
                status: 'pending',
                message: 'รอการยืนยันการชำระเงิน',
                messageClass: 'pending',
                progressWidth: '0%'
            },
            {
                step: 2,
                status: 'processing',
                message: 'คำสั่งซื้อของคุณกำลังถูกจัดเตรียม กรุณารออีกสักครู่',
                messageClass: 'processing',
                progressWidth: '33.33%'
            },
            {
                step: 3,
                status: 'shipping',
                message: 'สินค้าของคุณกำลังอยู่ระหว่างการขนส่ง',
                messageClass: 'processing',
                progressWidth: '66.66%'
            },
            {
                step: 4,
                status: 'delivered',
                message: 'สินค้าถูกจัดส่งเรียบร้อยแล้ว ขอบคุณที่ใช้บริการ',
                messageClass: 'completed',
                progressWidth: '100%'
            }
        ];

        let currentStatusIndex = 1; // Start at processing

        function updateOrderStatus(statusIndex) {
            const status = orderStatuses[statusIndex];
            
            // Update progress line
            document.getElementById('progressLine').style.width = status.progressWidth;
            
            // Update status message
            const statusMessage = document.querySelector('.status-message');
            const statusMessageText = document.querySelector('.status-message-text');
            
            statusMessage.className = `status-message ${status.messageClass}`;
            statusMessageText.className = `status-message-text ${status.messageClass}`;
            statusMessageText.textContent = status.message;
            
            // Update step icons and labels
            for (let i = 1; i <= 4; i++) {
                const stepIcon = document.getElementById(`step${i}`);
                const stepLabel = stepIcon.parentElement.querySelector('.step-label');
                
                if (i < status.step) {
                    // Completed steps
                    stepIcon.className = 'step-icon completed';
                    stepIcon.textContent = '✓';
                    stepLabel.className = 'step-label completed';
                } else if (i === status.step) {
                    // Current active step
                    stepIcon.className = 'step-icon active';
                    stepLabel.className = 'step-label active';
                    
                    // Set appropriate icon for current step
                    switch(i) {
                        case 1:
                            stepIcon.textContent = '💳';
                            break;
                        case 2:
                            stepIcon.textContent = '📦';
                            break;
                        case 3:
                            stepIcon.textContent = '🚚';
                            break;
                        case 4:
                            stepIcon.textContent = '✓';
                            break;
                    }
                } else {
                    // Pending steps
                    stepIcon.className = 'step-icon pending';
                    stepLabel.className = 'step-label';
                    
                    // Set appropriate icon for pending steps
                    switch(i) {
                        case 1:
                            stepIcon.textContent = '💳';
                            break;
                        case 2:
                            stepIcon.textContent = '📦';
                            break;
                        case 3:
                            stepIcon.textContent = '🚚';
                            break;
                        case 4:
                            stepIcon.textContent = '✓';
                            break;
                    }
                }
            }
        }

        function refreshStatus() {
            // Simulate status update
            const btn = document.querySelector('.refresh-btn');
            btn.textContent = 'กำลังอัปเดต...';
            btn.disabled = true;
            
            setTimeout(() => {
                // Randomly advance status or keep current
                if (currentStatusIndex < orderStatuses.length - 1 && Math.random() > 0.3) {
                    currentStatusIndex++;
                }
                
                updateOrderStatus(currentStatusIndex);
                
                btn.textContent = 'อัปเดตสถานะ';
                btn.disabled = false;
                
                // Show notification
                showNotification('อัปเดตสถานะเรียบร้อยแล้ว');
            }, 1500);
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
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Initialize the page with current status
        updateOrderStatus(currentStatusIndex);

        // Auto-refresh every 30 seconds (optional)
        setInterval(() => {
            if (currentStatusIndex < orderStatuses.length - 1) {
                // Small chance to auto-advance status
                if (Math.random() > 0.8) {
                    currentStatusIndex++;
                    updateOrderStatus(currentStatusIndex);
                    showNotification('สถานะคำสั่งซื้อมีการอัปเดต');
                }
            }
        }, 30000);
    </script>
</body>
</html>