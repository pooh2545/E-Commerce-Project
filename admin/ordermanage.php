<?php
require_once '../controller/admin_auth_check.php';

$auth = requireLogin();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการคำสั่งซื้อ</title>
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
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
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

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin: 15px 0;
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
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <!-- หน้ารายการคำสั่งซื้อ -->
        <div id="orderList">
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
                            <option value="1">รออการยืนยันคำสั่งซื้อ</option>
                            <option value="2">ชำระเงินแล้ว / รอการตรวจสอบ</option>
                            <option value="3">กำลังจัดเตรียม</option>
                            <option value="4">จัดส่งสำเร็จ</option>
                            <option value="5">ยกเลิกคำสั่งซื้อ</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>สถานะการชำระเงิน</label>
                        <select id="paymentFilter" onchange="filterOrders()">
                            <option value="all">ทั้งหมด</option>
                            <option value="0">ยังไม่ชำระ</option>
                            <option value="1">ชำระแล้ว</option>
                            <option value="pending">รอการอนุมัติ</option>
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
        <div id="orderDetail" class="hidden">
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

                <div class="order-actions">
                    <button class="btn btn-info" onclick="showOrderList()">กลับไปหน้ารายการคำสั่งซื้อ</button>
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

    <script>
        // API Base URL - ปรับตามที่ตั้งไฟล์ API
        const API_BASE_URL = '../controller/order_api.php';

        let currentOrderId = null;
        let orders = [];
        let filteredOrders = [];
        let currentOrderStatus = null;

        // โหลดรายการคำสั่งซื้อเมื่อเริ่มต้น
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
        });

        // ฟังก์ชันโหลดรายการคำสั่งซื้อ
        async function loadOrders() {
            try {
                // เรียกใช้ API เพื่อดึงข้อมูลคำสั่งซื้อทั้งหมด
                const response = await fetch(`${API_BASE_URL}?action=all`);
                const result = await response.json();

                if (result.success && result.data) {
                    orders = result.data;
                    filteredOrders = [...orders];
                    renderOrderTable();
                } else {
                    showMessage('ไม่สามารถโหลดข้อมูลได้', 'error');
                }

                // ลบข้อความ loading
                const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                loadingMessages.forEach(msg => msg.remove());

            } catch (error) {
                console.error('Error loading orders:', error);
                showMessage('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
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
                            <button class="btn btn-info" onclick="viewOrderDetail(${orderId})">ดูรายละเอียด</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // ฟังก์ชันดูรายละเอียดคำสั่งซื้อ
        async function viewOrderDetail(orderId) {
            try {
                currentOrderId = orderId;
                    
                const response = await fetch(`${API_BASE_URL}?action=get&order_id=${orderId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    displayOrderDetail(result.data);
                } else {
                    showMessage('ไม่สามารถโหลดรายละเอียดได้', 'error');
                    return;
                }

                // ลบข้อความ loading
                const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                loadingMessages.forEach(msg => msg.remove());

                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');

            } catch (error) {
                console.error('Error loading order detail:', error);
                showMessage('เกิดข้อผิดพลาดในการโหลดรายละเอียด', 'error');
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

            if (!confirm('คุณต้องการยืนยันการชำระเงินสำหรับคำสั่งซื้อนี้หรือไม่?')) {
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status_id: 3, // เปลี่ยนเป็นสถานะ "กำลังจัดเตรียม"
                        notes: 'ยืนยันการชำระเงินโดย Admin',
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('ยืนยันการชำระเงินสำเร็จ', 'success');
                    viewOrderDetail(currentOrderId); // รีโหลดข้อมูล
                    loadOrders(); // รีโหลดรายการ
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error approving payment:', error);
                showMessage('เกิดข้อผิดพลาดในการยืนยันการชำระเงิน', 'error');
            }
        }

        // ฟังก์ชันปฏิเสธการชำระเงิน
        async function rejectPayment() {
            if (!currentOrderId) return;

            const reason = document.getElementById('rejectReason').value.trim();
            if (!reason) {
                showMessage('กรุณาระบุเหตุผลในการปฏิเสธ', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status_id: 1, // กลับไปสถานะ "รออการยืนยันคำสั่งซื้อ"
                        notes: `ปฏิเสธการชำระเงิน: ${reason}`,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('ปฏิเสธการชำระเงินสำเร็จ', 'success');
                    closeModal('rejectModal');
                    viewOrderDetail(currentOrderId); // รีโหลดข้อมูล
                    loadOrders(); // รีโหลดรายการ
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error rejecting payment:', error);
                showMessage('เกิดข้อผิดพลาดในการปฏิเสธการชำระเงิน', 'error');
            }
        }

        // ฟังก์ชันเพิ่มหมายเหตุการชำระเงิน
        async function addPaymentNote() {
            if (!currentOrderId) return;

            const note = document.getElementById('paymentNote').value.trim();
            if (!note) {
                showMessage('กรุณาระบุหมายเหตุ', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status_id: currentOrderStatus, // คงสถานะเดิม
                        notes: `หมายเหตุการชำระเงิน: ${note}`,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('เพิ่มหมายเหตุสำเร็จ', 'success');
                    closeModal('paymentNoteModal');
                    viewOrderDetail(currentOrderId); // รีโหลดข้อมูล
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error adding payment note:', error);
                showMessage('เกิดข้อผิดพลาดในการเพิ่มหมายเหตุ', 'error');
            }
        }

        // ฟังก์ชันกรองข้อมูล
        function filterOrders() {
            const statusFilter = document.getElementById('statusFilter').value;
            const paymentFilter = document.getElementById('paymentFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredOrders = orders.filter(order => {
                const matchStatus = statusFilter === 'all' ||
                    (order.order_status || order.OrderStatusID) == statusFilter;

                const matchPayment = paymentFilter === 'all' ||
                    (paymentFilter === 'pending' && (order.order_status || order.OrderStatusID) == 2) ||
                    (order.payment_status || order.PaymentStatus) == paymentFilter;

                const matchSearch = searchTerm === '' ||
                    (order.order_number || order.OrderNumber || '').toLowerCase().includes(searchTerm) ||
                    (order.first_name || order.last_name || '').toLowerCase().includes(searchTerm);

                return matchStatus && matchPayment && matchSearch;
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
            document.getElementById('paymentFilter').value = 'all';
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

        // ฟังก์ชันแสดงข้อความ
        function showMessage(message, type) {
            // ลบข้อความเดิม
            const existingMessages = document.querySelectorAll('.success-message, .error-message');
            existingMessages.forEach(msg => msg.remove());

            const messageDiv = document.createElement('div');
            messageDiv.className = type === 'error' ? 'error-message' :
                type === 'success' ? 'success-message' : 'loading';
            messageDiv.textContent = message;

            const container = document.querySelector('.container');
            container.insertBefore(messageDiv, container.firstChild);

            // ลบข้อความหลังจาก 3 วินาที
            if (type !== 'loading') {
                setTimeout(() => {
                    messageDiv.remove();
                }, 3000);
            }
        }

        // ฟังก์ชันกลับไปหน้ารายการ
        function showOrderList() {
            document.getElementById('orderList').classList.remove('hidden');
            document.getElementById('orderDetail').classList.add('hidden');
            currentOrderId = null;
            currentOrderStatus = null;
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

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
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

            // กด Ctrl+F เพื่อโฟกัสที่ search box
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                document.getElementById('searchInput').focus();
            }

            // กด F5 หรือ Ctrl+R เพื่อรีเฟรช (ถ้าอยู่ในหน้ารายการ)
            if ((e.key === 'F5' || (e.ctrlKey && e.key === 'r')) && !document.getElementById('orderList').classList.contains('hidden')) {
                e.preventDefault();
                refreshOrders();
            }
        });

        // เพิ่ม auto-refresh ทุก 30 วินาที (สำหรับการอัปเดตแบบ real-time)
        setInterval(function() {
            // รีเฟรชเฉพาะเมื่ออยู่ในหน้ารายการและไม่มี modal เปิดอยู่
            const isOrderListVisible = !document.getElementById('orderList').classList.contains('hidden');
            const hasOpenModal = ['rejectModal', 'paymentNoteModal']
                .some(modalId => document.getElementById(modalId).style.display === 'block');

            if (isOrderListVisible && !hasOpenModal) {
                loadOrders();
            }
        }, 30000); // 30 seconds

        // เพิ่มฟังก์ชันสำหรับการจัดการข้อผิดพลาดแบบ global
        window.addEventListener('error', function(e) {
            console.error('Global error:', e.error);
            showMessage('เกิดข้อผิดพลาดในระบบ กรุณาลองใหม่อีกครั้ง', 'error');
        });

        // เพิ่มฟังก์ชันสำหรับการตรวจสอบการเชื่อมต่ออินเทอร์เน็ต
        window.addEventListener('online', function() {
            showMessage('เชื่อมต่ออินเทอร์เน็ตแล้ว', 'success');
        });

        window.addEventListener('offline', function() {
            showMessage('ขาดการเชื่อมต่ออินเทอร์เน็ต', 'error');
        });

        // Initialize tooltips and help text
        function initializeTooltips() {
            // เพิ่ม tooltip สำหรับปุ่มต่างๆ (ในอนาคต)
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                // Add accessibility attributes
                if (!button.getAttribute('aria-label')) {
                    button.setAttribute('aria-label', button.textContent.trim());
                }
            });
        }

        // เรียกใช้เมื่อโหลดหน้าเว็บ
        document.addEventListener('DOMContentLoaded', function() {
            initializeTooltips();
        });
    </script>
</body>

</html>