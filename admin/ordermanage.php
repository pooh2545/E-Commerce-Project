<?php
require_once '../controller/admin_auth_check.php';

$auth = requireLogin();
$currentUser = $auth->getCurrentUser();

// รับ order ID จาก URL parameter
$selectedOrderId = isset($_GET['order']) ? (int)$_GET['order'] : null;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำสั่งซื้อ</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo.png">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            margin-left: 220px;
            padding: 30px;
        }

        .page-header {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .page-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .page-subtitle {
            color: #666;
            font-size: 16px;
        }

        .order-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #C957BC;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            transition: all 0.3s;
            margin: 2px;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-info:hover {
            background-color: #0056b3;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .order-detail-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 20px;
        }

        .order-detail-title {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .order-info {
            line-height: 1.8;
            color: #555;
        }

        .order-info div {
            margin-bottom: 8px;
        }

        .back-link {
            color: #7B3F98;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
            font-size: 14px;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .hidden {
            display: none;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .order-actions {
            margin-top: 20px;
        }

        .items-table {
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }

        .items-table table {
            border: 1px solid #ddd;
        }

        .items-table th {
            background-color: #f8f9fa;
            color: #333;
            font-size: 12px;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        /* Order History Styles */
        .order-history-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            overflow: hidden;
        }

        .order-history-header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-history-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .order-history-title::before {
            content: "📋";
            margin-right: 8px;
        }

        .history-toggle {
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .history-toggle:hover {
            background-color: #0056b3;
        }

        .order-history-content {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .order-history-content.expanded {
            max-height: 500px;
            overflow-y: auto;
        }

        .history-timeline {
            position: relative;
            padding: 20px;
        }

        .history-timeline::before {
            content: '';
            position: absolute;
            left: 30px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #007bff, #6c757d);
        }

        .history-item {
            position: relative;
            padding-left: 60px;
            margin-bottom: 20px;
            opacity: 0;
            animation: fadeInUp 0.3s ease-out forwards;
        }

        .history-item:nth-child(1) { animation-delay: 0.1s; }
        .history-item:nth-child(2) { animation-delay: 0.2s; }
        .history-item:nth-child(3) { animation-delay: 0.3s; }
        .history-item:nth-child(4) { animation-delay: 0.4s; }
        .history-item:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .history-icon {
            position: absolute;
            left: -45px;
            top: 2px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .history-icon.status-1 { background-color: #ffc107; }
        .history-icon.status-2 { background-color: #17a2b8; }
        .history-icon.status-3 { background-color: #fd7e14; }
        .history-icon.status-4 { background-color: #28a745; }
        .history-icon.status-5 { background-color: #dc3545; }

        .history-content {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 15px;
            border-left: 4px solid #007bff;
        }

        .history-status {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .history-date {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .history-user {
            font-size: 12px;
            color: #495057;
            margin-bottom: 8px;
        }

        .history-notes {
            font-size: 13px;
            color: #495057;
            font-style: italic;
            background: white;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #e9ecef;
        }

        .history-empty {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }

        .history-empty::before {
            content: "📝";
            display: block;
            font-size: 48px;
            margin-bottom: 10px;
            opacity: 0.5;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 8px;
            width: 50%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ddd;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-group textarea {
            height: 80px;
            resize: vertical;
        }

        .payment-slip-preview {
            max-width: 200px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .payment-slip-preview img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .payment-approval-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #ffc107;
        }

        .payment-approval-section h3 {
            color: #856404;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .payment-approval-section h3::before {
            content: "⚠️";
            margin-right: 10px;
        }

        .approval-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .payment-slip-section {
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .payment-slip-info {
            flex: 1;
        }

        .large-payment-preview {
            max-width: 300px;
            border: 2px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .large-payment-preview img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }

        /* Filter and Search */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filter-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .filter-group label {
            font-size: 12px;
            color: #666;
            font-weight: bold;
        }

        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .pending-payment-indicator {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 4px;
            padding: 8px 12px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            font-size: 13px;
            color: #856404;
        }

        .pending-payment-indicator::before {
            content: "💳";
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
            }

            .order-history-content.expanded {
                max-height: 300px;
            }

            .history-timeline::before {
                left: 20px;
            }

            .history-item {
                padding-left: 50px;
            }

            .history-icon {
                left: -35px;
            }
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <!-- หน้ารายการคำสั่งซื้อ -->
        <div id="orderList" <?php echo $selectedOrderId ? 'class="hidden"' : ''; ?>>
            <div class="page-header">
                <h1 class="page-title">หน้าจัดการคำสั่งซื้อ : รายการคำสั่งซื้อ</h1>
                <p class="page-subtitle">จัดการคำสั่งซื้อทั้งหมดในระบบและตรวจสอบการชำระเงิน</p>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <div class="filter-row">
                    <div class="filter-group">
                        <label>สถานะคำสั่งซื้อ</label>
                        <select id="statusFilter" onchange="filterOrders()">
                            <option value="all">ทั้งหมด</option>
                            <option value="1">รอการยืนยันคำสั่งซื้อ</option>
                            <option value="2">ชำระเงินแล้ว / รอการตรวจสอบ</option>
                            <option value="3">กำลังจัดเตรียม</option>
                            <option value="4">จัดส่งสำเร็จ</option>
                            <option value="5">ยกเลิกคำสั่งซื้อ</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>ค้นหา</label>
                        <input type="text" id="searchInput" placeholder="ค้นหาหมายเลขคำสั่งซื้อหรือชื่อลูกค้า" onkeyup="searchOrders()">
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button class="btn btn-info" onclick="refreshOrders()">รีเฟรชข้อมูล</button>
                    </div>
                </div>
            </div>

            <div class="order-table">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสคำสั่งซื้อ</th>
                            <th>ข้อมูลผู้ซื้อ</th>
                            <th>ราคา</th>
                            <th>วันที่สั่ง</th>
                            <th>สถานะ</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <tr>
                            <td colspan="7" class="loading">กำลังโหลดข้อมูล...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้ารายละเอียดคำสั่งซื้อ -->
        <div id="orderDetail" <?php echo !$selectedOrderId ? 'class="hidden"' : ''; ?>>
            <div class="page-header">
                <h1 class="page-title">หน้าจัดการคำสั่งซื้อ : รายละเอียดคำสั่งซื้อ</h1>
                <p class="page-subtitle"></p>
            </div>

            <div class="order-detail-card">
                <h2 class="order-detail-title">รายละเอียดคำสั่งซื้อ</h2>

                <div class="order-info" id="orderDetailInfo">
                    <!-- ข้อมูลจะถูกแทรกที่นี่ -->
                </div>

                <!-- รายการสินค้า -->
                <div class="items-table">
                    <h3 style="padding: 15px; background-color: #f8f9fa; margin: 0;">รายการสินค้า</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ชื่อสินค้า</th>
                                <th>ไซส์</th>
                                <th>จำนวน</th>
                                <th>ราคาต่อชิ้น</th>
                                <th>รวม</th>
                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- รายการสินค้าจะถูกแทรกที่นี่ -->
                        </tbody>
                    </table>
                </div>

                <!-- หลักฐานการชำระเงิน -->
                <div id="paymentSlipSection" class="hidden" style="margin-top: 20px;">
                    <div class="payment-slip-section">
                        <div class="payment-slip-info">
                            <h3>หลักฐานการชำระเงิน</h3>
                            <div id="paymentSlipPreview"></div>
                        </div>
                    </div>
                </div>

                <!-- Payment Approval Section for Status 2 -->
                <div id="paymentApprovalSection" class="payment-approval-section hidden">
                    <h3>รอการยืนยันการชำระเงิน</h3>
                    <p>คำสั่งซื้อนี้ได้ทำการชำระเงินแล้วและรอการตรวจสอบจากแอดมิน</p>
                    <div class="approval-actions">
                        <button class="btn btn-success" onclick="approvePayment()">ยืนยันการชำระเงิน</button>
                        <button class="btn btn-danger" onclick="openRejectModal()">ปฏิเสธการชำระ</button>
                        <button class="btn btn-warning" onclick="openPaymentNoteModal()">เพิ่มหมายเหตุ</button>
                    </div>
                </div>

                <!-- Order History Section -->
                <div class="order-history-section">
                    <div class="order-history-header">
                        <h3 class="order-history-title">ประวัติการเปลี่ยนแปลงคำสั่งซื้อ</h3>
                        <button class="history-toggle" onclick="toggleOrderHistory()">แสดงประวัติ</button>
                    </div>
                    <div id="orderHistoryContent" class="order-history-content">
                        <div class="history-timeline" id="historyTimeline">
                            <div class="loading">กำลังโหลดประวัติ...</div>
                        </div>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="ordermanage.php" class="btn btn-info">กลับไปหน้ารายการคำสั่งซื้อ</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal สำหรับปฏิเสธการชำระเงิน -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>ปฏิเสธการชำระเงิน</h3>
                <span class="close" onclick="closeModal('rejectModal')">&times;</span>
            </div>
            <div class="form-group">
                <label for="rejectReason">เหตุผลในการปฏิเสธ:</label>
                <textarea id="rejectReason" placeholder="กรุณาระบุเหตุผลในการปฏิเสธการชำระเงิน" required></textarea>
            </div>
            <button class="btn btn-danger" onclick="rejectPayment()">ยืนยันการปฏิเสธ</button>
            <button class="btn" onclick="closeModal('rejectModal')">ยกเลิก</button>
        </div>
    </div>

    <!-- Modal สำหรับเพิ่มหมายเหตุการชำระเงิน -->
    <div id="paymentNoteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>เพิ่มหมายเหตุการชำระเงิน</h3>
                <span class="close" onclick="closeModal('paymentNoteModal')">&times;</span>
            </div>
            <div class="form-group">
                <label for="paymentNote">หมายเหตุ:</label>
                <textarea id="paymentNote" placeholder="เพิ่มหมายเหตุเกี่ยวกับการชำระเงิน"></textarea>
            </div>
            <button class="btn btn-success" onclick="addPaymentNote()">บันทึกหมายเหตุ</button>
            <button class="btn" onclick="closeModal('paymentNoteModal')">ยกเลิก</button>
        </div>
    </div>

    <!-- Include Notification.js -->
    <script src="../assets/js/notification.js"></script>

    <script>
        // API Base URL - ปรับตามที่ตั้งไฟล์ API
        const API_BASE_URL = '../controller/order_api.php';

        let currentOrderId = <?php echo $selectedOrderId ? $selectedOrderId : 'null'; ?>;
        let orders = [];
        let filteredOrders = [];
        let currentOrderStatus = null;
        let orderHistory = [];
        let historyExpanded = false;

        // โหลดรายการคำสั่งซื้อเมื่อเริ่มต้น
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
            
            // ถ้ามี order ID ใน URL ให้โหลดรายละเอียด
            if (currentOrderId) {
                viewOrderDetail(currentOrderId);
            }
        });

        // ฟังก์ชันโหลดรายการคำสั่งซื้อ
        async function loadOrders() {
            const hideLoading = showLoading('กำลังโหลดรายการคำสั่งซื้อ...');
            
            try {
                // เรียกใช้ API เพื่อดึงข้อมูลคำสั่งซื้อทั้งหมด
                const response = await fetch(`${API_BASE_URL}?action=all`);
                const result = await response.json();

                if (result.success && result.data) {
                    orders = result.data;
                    filteredOrders = [...orders];
                    renderOrderTable();
                    showSuccess('โหลดข้อมูลคำสั่งซื้อสำเร็จ');
                } else {
                    showError('ไม่สามารถโหลดข้อมูลได้');
                }

            } catch (error) {
                console.error('Error loading orders:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            } finally {
                hideLoading();
            }
        }

        // ฟังก์ชันแสดงรายการในตาราง
        function renderOrderTable() {
            const tbody = document.getElementById('orderTableBody');
            tbody.innerHTML = '';

            if (filteredOrders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="loading">ไม่พบข้อมูลคำสั่งซื้อ</td></tr>';
                return;
            }

            filteredOrders.forEach(order => {
                const orderNumber = order.order_number || order.OrderNumber;
                const customerName = order.first_name + ' ' + order.last_name ;
                const totalAmount = order.total_amount || order.TotalAmount;
                const orderDate = order.create_at || order.order_date || order.OrderDate;
                const statusName = order.order_status_name || order.StatusName;
                const statusId = order.order_status || order.OrderStatusID;
                const orderId = order.order_id || order.OrderID;

                const statusClass = getStatusClass(statusId);

                const row = `
                    <tr>
                        <td>${orderNumber}</td>
                        <td>${customerName}</td>
                        <td>฿${parseFloat(totalAmount).toLocaleString()}</td>
                        <td>${formatDate(orderDate)}</td>
                        <td><span class="status-badge ${statusClass}">${statusName}</span></td>
                        <td class="action-buttons">
                            <a href="ordermanage.php?order=${orderId}" class="btn btn-info">ดูรายละเอียด</a>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // ฟังก์ชันดูรายละเอียดคำสั่งซื้อ
        async function viewOrderDetail(orderId) {
            const hideLoading = showLoading('กำลังโหลดรายละเอียดคำสั่งซื้อ...');
            
            try {
                currentOrderId = orderId;
                    
                const response = await fetch(`${API_BASE_URL}?action=get&order_id=${orderId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    displayOrderDetail(result.data);
                    // โหลดประวัติคำสั่งซื้อ
                    await loadOrderHistory(orderId);
                    showSuccess('โหลดรายละเอียดคำสั่งซื้อสำเร็จ');
                } else {
                    showError('ไม่สามารถโหลดรายละเอียดได้');
                    return;
                }

                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');

            } catch (error) {
                console.error('Error loading order detail:', error);
                showError('เกิดข้อผิดพลาดในการโหลดรายละเอียด');
            } finally {
                hideLoading();
            }
        }

        // ฟังก์ชันโหลดประวัติคำสั่งซื้อ
        async function loadOrderHistory(orderId) {
            try {
                const response = await fetch(`${API_BASE_URL}?action=status-history&order_id=${orderId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    orderHistory = result.data;
                    renderOrderHistory();
                } else {
                    console.warn('ไม่พบประวัติคำสั่งซื้อ');
                    orderHistory = [];
                    renderOrderHistory();
                }
            } catch (error) {
                console.error('Error loading order history:', error);
                orderHistory = [];
                renderOrderHistory();
            }
        }

        // ฟังก์ชันแสดงประวัติคำสั่งซื้อ
        function renderOrderHistory() {
            const timeline = document.getElementById('historyTimeline');
            
            if (!orderHistory || orderHistory.length === 0) {
                timeline.innerHTML = `
                    <div class="history-empty">
                        <div>ไม่พบประวัติการเปลี่ยนแปลง</div>
                        <small>คำสั่งซื้อนี้ยังไม่มีการเปลี่ยนแปลงสถานะ</small>
                    </div>
                `;
                return;
            }

            let historyHtml = '';
            
            orderHistory.forEach((history, index) => {
                const statusIcon = getStatusIcon(history.new_status);
                const statusClass = `status-${history.new_status}`;
                const statusName = getStatusName(history.new_status);
                
                historyHtml += `
                    <div class="history-item" style="animation-delay: ${(index + 1) * 0.1}s">
                        <div class="history-icon ${statusClass}">${statusIcon}</div>
                        <div class="history-content">
                            <div class="history-status">${statusName}</div>
                            <div class="history-date">${formatDateTime(history.create_at)}</div>
                            ${history.changed_by ? `<div class="history-user">เปลี่ยนแปลงโดย: ${history.changed_by}</div>` : ''}
                            ${history.notes ? `<div class="history-notes">${history.notes}</div>` : ''}
                        </div>
                    </div>
                `;
            });

            timeline.innerHTML = historyHtml;
        }

        // ฟังก์ชันสลับการแสดงประวัติ
        function toggleOrderHistory() {
            const content = document.getElementById('orderHistoryContent');
            const button = document.querySelector('.history-toggle');
            
            historyExpanded = !historyExpanded;
            
            if (historyExpanded) {
                content.classList.add('expanded');
                button.textContent = 'ซ่อนประวัติ';
            } else {
                content.classList.remove('expanded');
                button.textContent = 'แสดงประวัติ';
            }
        }

        // ฟังก์ชันแสดงรายละเอียดคำสั่งซื้อ
        function displayOrderDetail(order) {
            const detailInfo = document.getElementById('orderDetailInfo');

            const orderNumber = order.order_number || order.OrderNumber;
            const customerName = order.first_name + ' ' + order.last_name;
            const recipientName = order.recipient_name;
            const shippingAddress = order.shipping_address || order.ShippingAddress;
            const shippingPhone = order.shipping_phone || order.ShippingPhone;
            const orderDate = order.create_at || order.order_date || order.OrderDate;
            const totalAmount = order.total_amount || order.TotalAmount;
            const statusName = order.status_name || order.StatusName || order.order_status_name;
            const statusId = order.order_status_id || order.OrderStatusID || order.order_status;
            const trackingNumber = order.tracking_number || order.TrackingNumber;
            const notes = order.notes || order.Note;

            currentOrderStatus = statusId;
            const statusClass = getStatusClass(statusId);

            detailInfo.innerHTML = `
                <div><strong>หมายเลขคำสั่งซื้อ:</strong> ${orderNumber}</div>
                <div><strong>ลูกค้า:</strong> ${customerName}</div>
                <div><strong>ชื่อผู้รับ:</strong> ${recipientName}</div>
                <div><strong>ที่อยู่จัดส่ง:</strong> ${shippingAddress || 'ไม่ระบุ'}</div>
                <div><strong>เบอร์โทร:</strong> ${shippingPhone || 'ไม่ระบุ'}</div>
                <div><strong>วันที่สั่งซื้อ:</strong> ${formatDate(orderDate)}</div>
                <div><strong>ยอดรวม:</strong> ฿${parseFloat(totalAmount).toLocaleString()}</div>
                <div><strong>สถานะคำสั่งซื้อ:</strong> <span class="status-badge ${statusClass}">${statusName}</span></div>
                <div><strong>หมายเลขติดตาม:</strong> <span id="trackingDisplay">${trackingNumber || 'ยังไม่มี'}</span></div>
                ${notes ? `<div><strong>หมายเหตุ:</strong> ${notes}</div>` : ''}
            `;

            // แสดงรายการสินค้า
            displayOrderItems(order.items || order.order_items || []);

            // แสดงหลักฐานการชำระเงิน (ถ้ามี)
            const paymentSlipPath = order.payment_slip_path || order.PaymentSlipPath;
            if (paymentSlipPath) {
                displayPaymentSlip(paymentSlipPath);
            } else {
                document.getElementById('paymentSlipSection').classList.add('hidden');
            }

            // แสดง Payment Approval Section ถ้าสถานะเป็น 2 (ชำระเงินแล้ว / รอการตรวจสอบ)
            const approvalSection = document.getElementById('paymentApprovalSection');
            if (parseInt(statusId) === 2) {
                approvalSection.classList.remove('hidden');
            } else {
                approvalSection.classList.add('hidden');
            }
        }

        // ฟังก์ชันแสดงรายการสินค้า
        function displayOrderItems(items) {
            const tbody = document.getElementById('itemsTableBody');
            tbody.innerHTML = '';

            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="loading">ไม่พบรายการสินค้า</td></tr>';
                return;
            }

            items.forEach(item => {
                const shoeName = item.shoename;
                const size = item.size || item.Size;
                const quantity = item.quantity || item.Quantity;
                const price = item.unit_price;

                const row = `
                    <tr>
                        <td>${shoeName}</td>
                        <td>${size}</td>
                        <td>${quantity}</td>
                        <td>฿${parseFloat(price).toLocaleString()}</td>
                        <td>฿${(quantity * price).toLocaleString()}</td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // ฟังก์ชันแสดงหลักฐานการชำระเงิน
        function displayPaymentSlip(imagePath) {
            const section = document.getElementById('paymentSlipSection');
            const preview = document.getElementById('paymentSlipPreview');

            preview.innerHTML = `
                <div class="large-payment-preview">
                    <img src="${imagePath.startsWith('uploads/') ? '../controller/' + imagePath : imagePath}" 
                         alt="หลักฐานการชำระเงิน" 
                         onclick="openImageModal('${imagePath}')"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2Y4ZjlmYSIvPgo8dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+ไม่สามารถโหลดรูปภาพได้</dGV4dD4KPC9zdmc+'; this.alt='ไม่สามารถโหลดรูปภาพได้';">
                </div>
                <p style="margin-top: 10px; font-size: 12px; color: #666;">คลิกเพื่อดูภาพขนาดใหญ่</p>
            `;

            section.classList.remove('hidden');
        }

        // ฟังก์ชันเปิดรูปภาพในหน้าต่างใหม่
        function openImageModal(imagePath) {
            const fullPath = imagePath.startsWith('uploads/') ? '../controller/' + imagePath : imagePath;
            const newWindow = window.open('', '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
            newWindow.document.write(`
                <html>
                    <head>
                        <title>หลักฐานการชำระเงิน</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 20px;
                                background-color: #f5f5f5;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                min-height: 100vh;
                                font-family: Arial, sans-serif;
                            }
                            .container {
                                background: white;
                                padding: 20px;
                                border-radius: 8px;
                                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                                text-align: center;
                            }
                            img {
                                max-width: 100%;
                                max-height: 80vh;
                                border: 1px solid #ddd;
                                border-radius: 4px;
                            }
                            h3 {
                                margin-top: 0;
                                color: #333;
                            }
                            .close-btn {
                                margin-top: 15px;
                                padding: 10px 20px;
                                background-color: #007bff;
                                color: white;
                                border: none;
                                border-radius: 4px;
                                cursor: pointer;
                            }
                            .close-btn:hover {
                                background-color: #0056b3;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h3>หลักฐานการชำระเงิน</h3>
                            <img src="${fullPath}" alt="หลักฐานการชำระเงิน" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjMwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2Y4ZjlmYSIvPgo8dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE2IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+ไม่สามารถโหลดรูปภาพได้</dGV4dD4KPC9zdmc+'; this.alt='ไม่สามารถโหลดรูปภาพได้';">
                            <br>
                            <button class="close-btn" onclick="window.close()">ปิดหน้าต่าง</button>
                        </div>
                    </body>
                </html>
            `);
        }

        // ฟังก์ชันยืนยันการชำระเงิน
        async function approvePayment() {
            if (!currentOrderId) return;

            showConfirm(
                'คุณต้องการยืนยันการชำระเงินสำหรับคำสั่งซื้อนี้หรือไม่?',
                async function() {
                    const hideLoading = showLoading('กำลังอัปเดตสถานะ...');
                    
                    try {
                        const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                order_status: 3, // เปลี่ยนเป็นสถานะ "กำลังจัดเตรียม"
                                notes: 'ยืนยันการชำระเงินโดย Admin',
                                changed_by: 'admin'
                            })
                        });

                        const result = await response.json();

                        if (result.success) {
                            showSuccess('ยืนยันการชำระเงินสำเร็จ');
                            // รีโหลดหน้าด้วย URL parameter เดิม
                            window.location.href = `ordermanage.php?order=${currentOrderId}`;
                        } else {
                            showError(result.message || 'เกิดข้อผิดพลาด');
                        }

                    } catch (error) {
                        console.error('Error approving payment:', error);
                        showError('เกิดข้อผิดพลาดในการยืนยันการชำระเงิน');
                    } finally {
                        hideLoading();
                    }
                }
            );
        }

        // ฟังก์ชันปฏิเสธการชำระเงิน
        async function rejectPayment() {
            if (!currentOrderId) return;

            const reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                showError('กรุณาระบุเหตุผลในการปฏิเสธ');
                return;
            }

            const hideLoading = showLoading('กำลังอัปเดตสถานะ...');

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status: 5, // กลับไปสถานะ "ยกเลิก"
                        notes: `ปฏิเสธการชำระเงิน: ${reason}`,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('ปฏิเสธการชำระเงินสำเร็จ');
                    closeModal('rejectModal');
                    // รีโหลดหน้าด้วย URL parameter เดิม
                    window.location.href = `ordermanage.php?order=${currentOrderId}`;
                } else {
                    showError(result.message || 'เกิดข้อผิดพลาด');
                }

            } catch (error) {
                console.error('Error rejecting payment:', error);
                showError('เกิดข้อผิดพลาดในการปฏิเสธการชำระเงิน');
            } finally {
                hideLoading();
            }
        }

        // ฟังก์ชันเพิ่มหมายเหตุการชำระเงิน
        async function addPaymentNote() {
            if (!currentOrderId) return;

            const note = document.getElementById('paymentNote').value.trim();
            if (!note) {
                showError('กรุณาระบุหมายเหตุ');
                return;
            }

            const hideLoading = showLoading('กำลังบันทึกหมายเหตุ...');

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status: currentOrderStatus, // คงสถานะเดิม
                        notes: `หมายเหตุการชำระเงิน: ${note}`,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showSuccess('เพิ่มหมายเหตุสำเร็จ');
                    closeModal('paymentNoteModal');
                    // รีโหลดหน้าด้วย URL parameter เดิม
                    window.location.href = `ordermanage.php?order=${currentOrderId}`;
                } else {
                    showError(result.message || 'เกิดข้อผิดพลาด');
                }

            } catch (error) {
                console.error('Error adding payment note:', error);
                showError('เกิดข้อผิดพลาดในการเพิ่มหมายเหตุ');
            } finally {
                hideLoading();
            }
        }

        // ฟังก์ชันกรองข้อมูล
        function filterOrders() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredOrders = orders.filter(order => {
                const matchStatus = statusFilter === 'all' ||
                    (order.order_status || order.OrderStatusID) == statusFilter;

                const matchSearch = searchTerm === '' ||
                    (order.order_number || '').toLowerCase().includes(searchTerm) ||
                    (order.order_id || '').toString().toLowerCase().includes(searchTerm) ||
                    (order.first_name || '').toLowerCase().includes(searchTerm) ||
                    (order.last_name || '').toLowerCase().includes(searchTerm) ||
                    ((order.first_name || '') + ' ' + (order.last_name || '')).toLowerCase().includes(searchTerm);

                return matchStatus && matchSearch;
            });

            renderOrderTable();
        }

        // ฟังก์ชันค้นหา
        function searchOrders() {
            filterOrders();
        }

        // ฟังก์ชันรีเฟรช
        function refreshOrders() {
            loadOrders();
            // รีเซ็ตฟิลเตอร์
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('searchInput').value = '';
        }

        // ฟังก์ชันเปิด Modal
        function openRejectModal() {
            document.getElementById('rejectModal').style.display = 'block';
        }

        function openPaymentNoteModal() {
            document.getElementById('paymentNoteModal').style.display = 'block';
        }

        // ฟังก์ชันปิด Modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';

            // เคลียร์ฟอร์ม
            if (modalId === 'rejectModal') {
                document.getElementById('rejectReason').value = '';
            } else if (modalId === 'paymentNoteModal') {
                document.getElementById('paymentNote').value = '';
            }
        }

        // ฟังก์ชันช่วยเหลือ
        function getStatusClass(statusId) {
            switch (parseInt(statusId)) {
                case 1:
                    return 'status-pending';
                case 2:
                    return 'status-processing';
                case 3:
                    return 'status-processing';
                case 4:
                    return 'status-completed';
                case 5:
                    return 'status-cancelled';
                default:
                    return 'status-pending';
            }
        }

        function getStatusName(statusId) {
            const statusNames = {
                1: 'รอการยืนยันคำสั่งซื้อ',
                2: 'ชำระเงินแล้ว / รอการตรวจสอบ',
                3: 'กำลังจัดเตรียม',
                4: 'จัดส่งสำเร็จ',
                5: 'ยกเลิกคำสั่งซื้อ'
            };
            return statusNames[statusId] || 'ไม่ระบุสถานะ';
        }

        function getStatusIcon(statusId) {
            const icons = {
                1: '⏳',
                2: '💳',
                3: '📦',
                4: '✅',
                5: '❌'
            };
            return icons[statusId] || '❓';
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        }

        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Event Listeners
        // ปิด Modal เมื่อคลิกข้างนอก
        window.onclick = function(event) {
            const modals = ['rejectModal', 'paymentNoteModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target == modal) {
                    closeModal(modalId);
                }
            });
        }

        // เพิ่มการฟังก์ชันจัดการ keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // กด ESC เพื่อปิด modal
            if (e.key === 'Escape') {
                const modals = ['rejectModal', 'paymentNoteModal'];
                modals.forEach(modalId => {
                    const modal = document.getElementById(modalId);
                    if (modal.style.display === 'block') {
                        closeModal(modalId);
                    }
                });
            }

            // กด Ctrl+F เพื่อโฟกัสที่ search box (เฉพาะในหน้ารายการ)
            if (e.ctrlKey && e.key === 'f' && !document.getElementById('orderList').classList.contains('hidden')) {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }
        });

        // เพิ่มฟังก์ชันสำหรับการจัดการข้อผิดพลาดแบบ global
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
            showError('เกิดข้อผิดพลาดในระบบ กรุณาลองใหม่อีกครั้ง');
        });

        // เพิ่มฟังก์ชันสำหรับการตรวจสอบการเชื่อมต่ออินเทอร์เน็ต
        window.addEventListener('online', function() {
            showSuccess('เชื่อมต่ออินเทอร์เน็ตแล้ว');
        });

        window.addEventListener('offline', function() {
            showWarning('ขาดการเชื่อมต่ออินเทอร์เน็ต');
        });
    </script>
</body>

</html>