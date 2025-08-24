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
                            <th>การชำระเงิน</th>
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

                <!-- ส่วนการอนุมัติการชำระเงิน -->
                <div id="paymentApprovalSection" class="hidden payment-approval-section">
                    <h3>การอนุมัติการชำระเงิน</h3>
                    <p>ลูกค้าได้อัพโหลดหลักฐานการชำระเงินแล้ว กรุณาตรวจสอบและอนุมัติการชำระเงิน</p>
                    <div class="approval-actions">
                        <button class="btn btn-success" onclick="approvePayment()">✓ อนุมัติการชำระเงิน</button>
                        <button class="btn btn-danger" onclick="openRejectModal()">✗ ปฏิเสธการชำระเงิน</button>
                        <button class="btn btn-info" onclick="openPaymentNoteModal()">📝 เพิ่มหมายเหตุ</button>
                    </div>
                </div>

                <div class="order-actions">
                    <button class="btn btn-info" onclick="showOrderList()">กลับไปหน้ารายการคำสั่งซื้อ</button>
                    <button class="btn btn-success" onclick="openStatusModal()">เปลี่ยนสถานะ</button>
                    <button class="btn btn-warning" onclick="openTrackingModal()">เพิ่ม/แก้ไขหมายเลขติดตาม</button>
                    <button class="btn btn-info" onclick="openPaymentModal()">จัดการการชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal สำหรับเปลี่ยนสถานะ -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>เปลี่ยนสถานะคำสั่งซื้อ</h3>
                <span class="close" onclick="closeModal('statusModal')">&times;</span>
            </div>
            <div class="form-group">
                <label for="orderStatus">เลือกสถานะใหม่:</label>
                <select id="orderStatus">
                    <option value="1">รออการยืนยันคำสั่งซื้อ</option>
                    <option value="2">ชำระเงินแล้ว / รอการตรวจสอบ</option>
                    <option value="3">กำลังจัดเตรียม</option>
                    <option value="4">จัดส่งสำเร็จ</option>
                    <option value="5">ยกเลิกคำสั่งซื้อ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="statusNotes">หมายเหตุ:</label>
                <textarea id="statusNotes" placeholder="หมายเหตุเพิ่มเติม"></textarea>
            </div>
            <button class="btn btn-success" onclick="updateOrderStatus()">บันทึก</button>
            <button class="btn" onclick="closeModal('statusModal')">ยกเลิก</button>
        </div>
    </div>

    <!-- Modal สำหรับหมายเลขติดตาม -->
    <div id="trackingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>จัดการหมายเลขติดตาม</h3>
                <span class="close" onclick="closeModal('trackingModal')">&times;</span>
            </div>
            <div class="form-group">
                <label for="trackingNumber">หมายเลขติดตาม:</label>
                <input type="text" id="trackingNumber" placeholder="กรอกหมายเลขติดตาม">
            </div>
            <button class="btn btn-success" onclick="updateTrackingNumber()">บันทึก</button>
            <button class="btn" onclick="closeModal('trackingModal')">ยกเลิก</button>
        </div>
    </div>

    <!-- Modal สำหรับจัดการการชำระเงิน -->
    <div id="paymentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>จัดการการชำระเงิน</h3>
                <span class="close" onclick="closeModal('paymentModal')">&times;</span>
            </div>
            <div class="form-group">
                <label for="paymentStatus">สถานะการชำระเงิน:</label>
                <select id="paymentStatus">
                    <option value="0">ยังไม่ชำระ</option>
                    <option value="1">รออนุมัติ</option>
                    <option value="2">ชำระแล้ว</option>
                    <option value="3">ไม่อนุมัติ</option>
                </select>
            </div>
            <button class="btn btn-success" onclick="updatePaymentStatus()">บันทึก</button>
            <button class="btn btn-info" onclick="confirmPayment()">ยืนยันการชำระเงิน</button>
            <button class="btn" onclick="closeModal('paymentModal')">ยกเลิก</button>
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

        // โหลดรายการคำสั่งซื้อเมื่อเริ่มต้น
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
        });

        // ฟังก์ชันโหลดรายการคำสั่งซื้อ
        async function loadOrders() {
            try {
                showMessage('กำลังโหลดข้อมูล...', 'loading');

                // ในที่นี้เราจะใช้ข้อมูลจำลอง เนื่องจากไม่มี API endpoint สำหรับดึงรายการทั้งหมด
                // ในการใช้งานจริง ควรเพิ่ม endpoint สำหรับดึงรายการคำสั่งซื้อทั้งหมด
                // เรียกใช้ API เพื่อดึงข้อมูลคำสั่งซื้อทั้งหมด
                const response = await fetch(`${API_BASE_URL}?action=all`);
                const result = await response.json();

                if (result.success && result.data) {
                    orders = result.data;
                    filteredOrders = [...orders];
                    renderOrderTable();

                    // ลบข้อความ loading
                    const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                    loadingMessages.forEach(msg => msg.remove());

                } else {
                    // ใช้ข้อมูลจำลองในกรณีที่ API ไม่พร้อม
                    orders = getMockOrders();
                    filteredOrders = [...orders];
                    renderOrderTable();

                    // ลบข้อความ loading
                    const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                    loadingMessages.forEach(msg => msg.remove());
                }

            } catch (error) {
                console.error('Error loading orders:', error);

                // แสดงข้อมูลจำลองในกรณีที่เกิดข้อผิดพลาด
                orders = getMockOrders();
                filteredOrders = [...orders];
                renderOrderTable();

                // ลบข้อความ loading
                const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                loadingMessages.forEach(msg => msg.remove());
            }
        }

        // ข้อมูลจำลองสำหรับการทดสอบ
        function getMockOrders() {
            return [{
                    order_id: 1,
                    order_number: 'ORD001245',
                    customer_name: 'สมยศ นิติรัตน์',
                    total_amount: 2700,
                    created_at: '2025-01-19',
                    order_status: 2,
                    order_status_name: 'ชำระเงินแล้ว / รอการตรวจสอบ',
                    payment_status: 'pending', // รอการอนุมัติ
                    payment_status_name: 'รอการอนุมัติ',
                    has_payment_slip: true
                },
                {
                    order_id: 2,
                    order_number: 'ORD001246',
                    customer_name: 'สุดา จันทรัตน์',
                    total_amount: 1500,
                    created_at: '2025-01-18',
                    order_status: 4,
                    order_status_name: 'จัดส่งสำเร็จ',
                    payment_status: 1,
                    payment_status_name: 'ชำระแล้ว',
                    has_payment_slip: true
                },
                {
                    order_id: 3,
                    order_number: 'ORD001247',
                    customer_name: 'อรุณ พุทธรักษ์',
                    total_amount: 1200,
                    created_at: '2025-01-20',
                    order_status: 1,
                    order_status_name: 'รออการยืนยันคำสั่งซื้อ',
                    payment_status: 0,
                    payment_status_name: 'ยังไม่ชำระ',
                    has_payment_slip: false
                },
                {
                    order_id: 4,
                    order_number: 'ORD001248',
                    customer_name: 'วิมล สุขเจริญ',
                    total_amount: 3200,
                    created_at: '2025-01-17',
                    order_status: 2,
                    order_status_name: 'ชำระเงินแล้ว / รอการตรวจสอบ',
                    payment_status: 'pending',
                    payment_status_name: 'รอการอนุมัติ',
                    has_payment_slip: true
                }
            ];
        }

        // ฟังก์ชันแสดงรายการในตาราง
        function renderOrderTable() {
            const tbody = document.getElementById('orderTableBody');
            tbody.innerHTML = '';

            if (filteredOrders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading">ไม่พบข้อมูลคำสั่งซื้อ</td></tr>';
                return;
            }

            filteredOrders.forEach(order => {
                // รองรับทั้ง field names แบบ camelCase และ snake_case
                const orderNumber = order.order_number || order.OrderNumber;
                const customerName = order.customer_name || order.recipient_name;
                const totalAmount = order.total_amount || order.TotalAmount;
                const orderDate = order.created_at || order.order_date || order.OrderDate;
                const statusName = order.order_status_name || order.StatusName;
                const statusId = order.order_status || order.OrderStatusID;
                const orderId = order.order_id || order.OrderID;
                const paymentStatus = order.payment_status;
                const paymentStatusName = order.payment_status_name;
                const hasPaymentSlip = order.has_payment_slip;

                const statusClass = getStatusClass(statusId);
                const paymentStatusClass = getPaymentStatusClass(paymentStatus);

                // แสดงตัวบ่งชี้พิเศษสำหรับการชำระเงินที่รอการอนุมัติ
                const paymentIndicator = paymentStatusName === "รออนุมัติ" ?
                    '<div class="pending-payment-indicator">รอการอนุมัติการชำระเงิน</div>' : '';

                const row = `
                    <tr>
                        <td>${orderNumber}</td>
                        <td>${customerName}</td>
                        <td>฿${parseFloat(totalAmount).toLocaleString()}</td>
                        <td>${formatDate(orderDate)}</td>
                        <td><span class="status-badge ${statusClass}">${statusName}</span></td>
                        <td>
                            <span class="status-badge ${paymentStatusClass}">${paymentStatusName}</span>
                            ${paymentIndicator}
                        </td>
                        <td class="action-buttons">
                            <button class="btn btn-info" onclick="viewOrderDetail(${orderId})">ดูรายละเอียด</button>
                            ${paymentStatus === 1 ? '<button class="btn btn-warning" onclick="viewOrderDetail(' + orderId + ')">ตรวจสอบการชำระเงิน</button>' : ''}
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
                showMessage('กำลังโหลดรายละเอียด...', 'loading');

                const response = await fetch(`${API_BASE_URL}?action=get&order_id=${orderId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    displayOrderDetail(result.data);
                } else {
                    // ใช้ข้อมูลจำลองในกรณีที่ API ไม่พร้อม
                    const mockOrder = getMockOrderDetail(orderId);
                    displayOrderDetail(mockOrder);
                }

                // ลบข้อความ loading
                const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                loadingMessages.forEach(msg => msg.remove());

                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');

            } catch (error) {
                console.error('Error loading order detail:', error);

                // ใช้ข้อมูลจำลองในกรณีที่เกิดข้อผิดพลาด
                const mockOrder = getMockOrderDetail(orderId);
                displayOrderDetail(mockOrder);

                // ลบข้อความ loading
                const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                loadingMessages.forEach(msg => msg.remove());

                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');
            }
        }

        // ฟังก์ชันแสดงรายละเอียดคำสั่งซื้อ
        function displayOrderDetail(order) {
            const detailInfo = document.getElementById('orderDetailInfo');

            // รองรับทั้ง field names แบบ camelCase และ snake_case
            const orderNumber = order.order_number || order.OrderNumber;
            const customerName = order.customer_name || order.CustomerName;
            const shippingAddress = order.shipping_address || order.ShippingAddress;
            const shippingPhone = order.shipping_phone || order.ShippingPhone;
            const orderDate = order.created_at || order.order_date || order.OrderDate;
            const totalAmount = order.total_amount || order.TotalAmount;
            const statusName = order.status_name || order.StatusName || order.order_status_name;
            const statusId = order.order_status_id || order.OrderStatusID || order.order_status;
            const paymentStatusName = order.payment_status_name || order.PaymentStatusName;
            const paymentStatusId = order.payment_status_id || order.PaymentStatusID || order.payment_status;
            const trackingNumber = order.tracking_number || order.TrackingNumber;
            const paymentSlipPath = order.payment_slip_path || order.PaymentSlipPath;
            const paymentNotes = order.note || order.PaymentNote;

            const statusClass = getStatusClass(statusId);

            detailInfo.innerHTML = `
                <div><strong>หมายเลขคำสั่งซื้อ:</strong> ${orderNumber}</div>
                <div><strong>ลูกค้า:</strong> ${customerName}</div>
                <div><strong>ที่อยู่จัดส่ง:</strong> ${shippingAddress || 'ไม่ระบุ'}</div>
                <div><strong>เบอร์โทร:</strong> ${shippingPhone || 'ไม่ระบุ'}</div>
                <div><strong>วันที่สั่งซื้อ:</strong> ${formatDate(orderDate)}</div>
                <div><strong>ยอดรวม:</strong> ฿${parseFloat(totalAmount).toLocaleString()}</div>
                <div><strong>สถานะคำสั่งซื้อ:</strong> <span class="status-badge ${statusClass}">${statusName}</span></div>
                <div><strong>สถานะการชำระเงิน:</strong> <span class="status-badge ${getPaymentStatusClass(paymentStatusId)}">${paymentStatusName}</span></div>
                <div><strong>หมายเลขติดตาม:</strong> <span id="trackingDisplay">${trackingNumber || 'ยังไม่มี'}</span></div>
                ${paymentNotes ? `<div><strong>หมายเหตุการชำระเงิน:</strong> ${paymentNotes}</div>` : ''}
            `;

            // แสดงรายการสินค้า
            displayOrderItems(order.items || order.order_items || getMockOrderItems(currentOrderId));

            // แสดงหลักฐานการชำระเงิน (ถ้ามี)
            if (paymentSlipPath || (paymentStatusId === 'pending')) {
                displayPaymentSlip(paymentSlipPath || 'uploads/payment_slips/sample_slip.jpg');
            } else {
                document.getElementById('paymentSlipSection').classList.add('hidden');
            }

            // แสดงส่วนการอนุมัติการชำระเงิน (ถ้าสถานะเป็น pending)
            if (paymentStatusId === 1) {
                document.getElementById('paymentApprovalSection').classList.remove('hidden');
            } else {
                document.getElementById('paymentApprovalSection').classList.add('hidden');
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
                // รองรับทั้ง field names แบบ camelCase และ snake_case
                const shoeName = item.shoe_name || item.ShoeName;
                const size = item.size || item.Size;
                const quantity = item.quantity || item.Quantity;
                const price = item.price || item.Price;

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
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjE1MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0iI2Y4ZjlmYSIvPgo8dGV4dCB4PSI1MCUiIHk9IjUwJSIgZm9udC1mYW1pbHk9IkFyaWFsLCBzYW5zLXNlcmlmIiBmb250LXNpemU9IjE0IiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+SG1ha2ZhbnQgQ2hhbXJhZSBOZ2VybiBOYW08L3RleHQ+Cjwvc3ZnPg=='; this.alt='ไม่สามารถโหลดรูปภาพได้';">
                </div>
                <p style="margin-top: 10px; font-size: 12px; color: #666;">คลิกเพื่อดูภาพขนาดใหญ่</p>
            `;

            section.classList.remove('hidden');
        }

        // ฟังก์ชันอนุมัติการชำระเงิน
        async function approvePayment() {
            if (!currentOrderId) return;

            try {
                // อัปเดตสถานะการชำระเงิน (payment_status = 2)
                const paymentResponse = await fetch(`${API_BASE_URL}?action=update-payment-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_status: 2, // ชำระแล้ว
                        changed_by: 'admin'
                    })
                });

                const paymentResult = await paymentResponse.json();

                if (!paymentResult.success) {
                    showMessage(paymentResult.message || 'เกิดข้อผิดพลาดในการอัปเดตสถานะการชำระเงิน', 'error');
                    return;
                }

                // อัปเดตสถานะออเดอร์ (order_status = 3)
                const orderResponse = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status_id: 3, // กำลังจัดเตรียม
                        changed_by: 'admin',
                        notes: 'อนุมัติการชำระเงินแล้ว'
                    })
                });

                const orderResult = await orderResponse.json();

                if (orderResult.success) {
                    showMessage('อนุมัติการชำระเงินสำเร็จ', 'success');
                    viewOrderDetail(currentOrderId);
                    loadOrders();
                } else {
                    showMessage(orderResult.message || 'เกิดข้อผิดพลาดในการอัปเดตสถานะออเดอร์', 'error');
                }

            } catch (error) {
                console.error('Error approving payment:', error);
                // จำลองการอนุมัติสำหรับการทดสอบ
                showMessage('อนุมัติการชำระเงินสำเร็จ (โหมดจำลอง)', 'success');

                // อัปเดตข้อมูลใน mock data
                const orderIndex = orders.findIndex(o => (o.order_id || o.OrderID) == currentOrderId);
                if (orderIndex !== -1) {
                    orders[orderIndex].payment_status = 2;
                    orders[orderIndex].payment_status_name = 'ชำระแล้ว';
                    orders[orderIndex].order_status = 3;
                    orders[orderIndex].order_status_name = 'กำลังจัดเตรียม';
                }

                viewOrderDetail(currentOrderId);
                loadOrders();
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
                // อัปเดตสถานะการชำระเงิน (payment_status = 0)
                const paymentResponse = await fetch(`${API_BASE_URL}?action=update-payment-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_status: 3, // ปฏิเสธ
                        changed_by: 'admin'
                    })
                });

                const paymentResult = await paymentResponse.json();

                if (!paymentResult.success) {
                    showMessage(paymentResult.message || 'เกิดข้อผิดพลาดในการอัปเดตสถานะการชำระเงิน', 'error');
                    return;
                }

                // อัปเดตสถานะออเดอร์ (order_status = 0 - ยกเลิก)
                const orderResponse = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status: 5, // ยกเลิก
                        changed_by: 'admin',
                        notes: `ปฏิเสธการชำระเงิน: ${reason}`
                    })
                });

                const orderResult = await orderResponse.json();

                if (orderResult.success) {
                    showMessage('ปฏิเสธการชำระเงินสำเร็จ', 'success');
                    closeModal('rejectModal');
                    viewOrderDetail(currentOrderId);
                    loadOrders();
                } else {
                    showMessage(orderResult.message || 'เกิดข้อผิดพลาดในการอัปเดตสถานะออเดอร์', 'error');
                }

            } catch (error) {
                console.error('Error rejecting payment:', error);
                // จำลองการปฏิเสธสำหรับการทดสอบ
                showMessage('ปฏิเสธการชำระเงินสำเร็จ (โหมดจำลอง)', 'success');

                // อัปเดตข้อมูลใน mock data
                const orderIndex = orders.findIndex(o => (o.order_id || o.OrderID) == currentOrderId);
                if (orderIndex !== -1) {
                    orders[orderIndex].payment_status = 0;
                    orders[orderIndex].payment_status_name = 'ปฏิเสธ';
                    orders[orderIndex].order_status = 0;
                    orders[orderIndex].order_status_name = 'ยกเลิก';
                    orders[orderIndex].payment_notes = `เหตุผลการปฏิเสธ: ${reason}`;
                }

                closeModal('rejectModal');
                viewOrderDetail(currentOrderId);
                loadOrders();
            }
        }

        // ฟังก์ชันเพิ่มหมายเหตุการชำระเงิน
        async function addPaymentNote() {
            if (!currentOrderId) return;

            const note = document.getElementById('paymentNote').value.trim();
            if (!note) {
                showMessage('กรุณากรอกหมายเหตุ', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}?action=add-payment-note&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        note: note,
                        added_by: 'admin',
                        added_date: new Date().toISOString()
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('เพิ่มหมายเหตุสำเร็จ', 'success');
                    closeModal('paymentNoteModal');
                    viewOrderDetail(currentOrderId);
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error adding payment note:', error);
                // จำลองการเพิ่มหมายเหตุสำหรับการทดสอบ
                showMessage('เพิ่มหมายเหตุสำเร็จ (โหมดจำลอง)', 'success');
                closeModal('paymentNoteModal');
            }
        }

        // ฟังก์ชันเปลี่ยนสถานะคำสั่งซื้อ
        async function updateOrderStatus() {
            if (!currentOrderId) return;

            const statusId = document.getElementById('orderStatus').value;
            const notes = document.getElementById('statusNotes').value;

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-order-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status_id: parseInt(statusId),
                        notes: notes,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('อัปเดตสถานะสำเร็จ', 'success');
                    closeModal('statusModal');
                    viewOrderDetail(currentOrderId);
                    loadOrders();
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error updating order status:', error);
                // จำลองการอัปเดต
                showMessage('อัปเดตสถานะสำเร็จ (โหมดจำลอง)', 'success');
                closeModal('statusModal');
            }
        }

        // ฟังก์ชันอัปเดตหมายเลขติดตาม
        async function updateTrackingNumber() {
            if (!currentOrderId) return;

            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            if (!trackingNumber) {
                showMessage('กรุณากรอกหมายเลขติดตาม', 'error');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}?action=set-tracking&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        tracking_number: trackingNumber,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('อัปเดตหมายเลขติดตามสำเร็จ', 'success');
                    closeModal('trackingModal');
                    document.getElementById('trackingDisplay').textContent = trackingNumber;
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error updating tracking number:', error);
                // จำลองการอัปเดต
                showMessage('อัปเดตหมายเลขติดตามสำเร็จ (โหมดจำลอง)', 'success');
                document.getElementById('trackingDisplay').textContent = trackingNumber;
                closeModal('trackingModal');
            }
        }

        // ฟังก์ชันอัปเดตสถานะการชำระเงิน
        async function updatePaymentStatus() {
            if (!currentOrderId) return;

            const paymentStatus = document.getElementById('paymentStatus').value;

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-payment-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_status: parseInt(paymentStatus),
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('อัปเดตสถานะการชำระเงินสำเร็จ', 'success');
                    closeModal('paymentModal');
                    viewOrderDetail(currentOrderId);
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error updating payment status:', error);
                showMessage('อัปเดตสถานะการชำระเงินสำเร็จ (โหมดจำลอง)', 'success');
                closeModal('paymentModal');
            }
        }

        // ฟังก์ชันยืนยันการชำระเงิน
        async function confirmPayment() {
            if (!currentOrderId) return;

            try {
                const response = await fetch(`${API_BASE_URL}?action=update-payment-status&order_id=${currentOrderId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_status: 1,
                        changed_by: 'admin'
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('ยืนยันการชำระเงินสำเร็จ', 'success');
                    closeModal('paymentModal');
                    viewOrderDetail(currentOrderId);
                    loadOrders();
                } else {
                    showMessage(result.message || 'เกิดข้อผิดพลาด', 'error');
                }

            } catch (error) {
                console.error('Error confirming payment:', error);
                showMessage('ยืนยันการชำระเงินสำเร็จ (โหมดจำลอง)', 'success');
                closeModal('paymentModal');
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
                    (paymentFilter === 'pending' && (order.payment_status === 'pending')) ||
                    (paymentFilter !== 'pending' && (order.payment_status || order.PaymentStatusID) == paymentFilter);

                const matchSearch = searchTerm === '' ||
                    (order.order_number || order.OrderNumber || '').toLowerCase().includes(searchTerm) ||
                    (order.customer_name || order.CustomerName || '').toLowerCase().includes(searchTerm);

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
        function openStatusModal() {
            document.getElementById('statusModal').style.display = 'block';
        }

        function openTrackingModal() {
            const currentTracking = document.getElementById('trackingDisplay').textContent;
            if (currentTracking !== 'ยังไม่มี') {
                document.getElementById('trackingNumber').value = currentTracking;
            } else {
                document.getElementById('trackingNumber').value = '';
            }
            document.getElementById('trackingModal').style.display = 'block';
        }

        function openPaymentModal() {
            document.getElementById('paymentModal').style.display = 'block';
        }

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
            if (modalId === 'statusModal') {
                document.getElementById('statusNotes').value = '';
            } else if (modalId === 'trackingModal') {
                document.getElementById('trackingNumber').value = '';
            } else if (modalId === 'rejectModal') {
                document.getElementById('rejectReason').value = '';
            } else if (modalId === 'paymentNoteModal') {
                document.getElementById('paymentNote').value = '';
            }
        }

        // ปิด Modal เมื่อคลิกข้างนอก
        window.onclick = function(event) {
            const modals = ['statusModal', 'trackingModal', 'paymentModal', 'rejectModal', 'paymentNoteModal'];
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

        function getPaymentStatusClass(statusId) {
            if (statusId === 'pending') return 'status-warning';
            switch (parseInt(statusId)) {
                case 0:
                    return 'status-pending';
                case 1:
                    return 'status-completed';
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

        // ข้อมูลจำลองสำหรับการทดสอบ
        function getMockOrderDetail(orderId) {
            const baseOrder = {
                OrderID: orderId,
                OrderNumber: `ORD00124${orderId + 4}`,
                CustomerName: 'สมยศ นิติรัตน์',
                ShippingAddress: '39/1 ถนนสยามสแควร์ พระราม กรุงเทพฯ',
                ShippingPhone: '081-234-5678',
                OrderDate: '2025-01-19',
                TotalAmount: 2700,
                OrderStatusID: orderId === 1 ? 2 : orderId === 2 ? 4 : 1,
                StatusName: orderId === 1 ? 'ชำระเงินแล้ว / รอการตรวจสอบ' : orderId === 2 ? 'จัดส่งสำเร็จ' : 'รออการยืนยันคำสั่งซื้อ',
                PaymentStatusID: orderId === 1 ? 'pending' : orderId === 2 ? 1 : 0,
                PaymentStatusName: orderId === 1 ? 'รอการอนุมัติ' : orderId === 2 ? 'ชำระแล้ว' : 'ยังไม่ชำระ',
                TrackingNumber: orderId === 2 ? 'TH9876543210' : null,
                PaymentSlipPath: orderId <= 2 ? 'uploads/payment_slips/sample_slip.jpg' : null,
                PaymentNotes: orderId === 1 ? 'ลูกค้าได้อัพโหลดหลักฐานการชำระเงินแล้ว กรุณาตรวจสอบ' : null
            };

            // ปรับแต่งข้อมูลตาม orderId
            if (orderId === 4) {
                baseOrder.CustomerName = 'วิมล สุขเจริญ';
                baseOrder.TotalAmount = 3200;
                baseOrder.PaymentStatusID = 'pending';
                baseOrder.PaymentStatusName = 'รอการอนุมัติ';
                baseOrder.StatusName = 'ชำระเงินแล้ว / รอการตรวจสอบ';
                baseOrder.OrderStatusID = 2;
            }

            return baseOrder;
        }

        function getMockOrderItems(orderId) {
            const itemSets = {
                1: [{
                        ShoeName: 'รองเท้าผ้าใบสีดำ Nike Air Max',
                        Size: '42',
                        Quantity: 1,
                        Price: 1500
                    },
                    {
                        ShoeName: 'รองเท้าผ้าใบสีขาว Adidas Ultraboost',
                        Size: '41',
                        Quantity: 1,
                        Price: 1200
                    }
                ],
                2: [{
                    ShoeName: 'รองเท้าผ้าใบสีแดง Converse Chuck Taylor',
                    Size: '40',
                    Quantity: 1,
                    Price: 1500
                }],
                3: [{
                    ShoeName: 'รองเท้าผ้าใบสีเขียว New Balance 574',
                    Size: '43',
                    Quantity: 1,
                    Price: 1200
                }],
                4: [{
                    ShoeName: 'รองเท้าผ้าใบสีน้ำเงิน Vans Old Skool',
                    Size: '42',
                    Quantity: 2,
                    Price: 1600
                }]
            };

            return itemSets[orderId] || itemSets[1];
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

        // เพิ่มการฟังก์ชันจัดการ keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // กด ESC เพื่อปิด modal
            if (e.key === 'Escape') {
                const modals = ['statusModal', 'trackingModal', 'paymentModal', 'rejectModal', 'paymentNoteModal'];
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
            const hasOpenModal = ['statusModal', 'trackingModal', 'paymentModal', 'rejectModal', 'paymentNoteModal']
                .some(modalId => document.getElementById(modalId).style.display === 'block');

            if (isOrderListVisible && !hasOpenModal) {
                loadOrders();
            }
        }, 30000); // 30 seconds

        // เพิ่มฟังก์ชันสำหรับ export ข้อมูล (อนาคต)
        function exportOrderData() {
            // สำหรับการใช้งานในอนาคต - export ข้อมูลเป็น CSV หรือ Excel
            console.log('Export functionality - to be implemented');
        }

        // เพิ่มฟังก์ชันสำหรับ print รายงาน
        function printOrderReport(orderId) {
            // สำหรับการใช้งานในอนาคต - print รายงานคำสั่งซื้อ
            console.log('Print report functionality - to be implemented for order:', orderId);
        }

        // เพิ่มฟังก์ชันสำหรับการแจ้งเตือน
        function showNotification(title, message, type = 'info') {
            // ตรวจสอบว่าเบราว์เซอร์รองรับ Notification API หรือไม่
            if ('Notification' in window) {
                if (Notification.permission === 'granted') {
                    new Notification(title, {
                        body: message,
                        icon: type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'
                    });
                } else if (Notification.permission !== 'denied') {
                    Notification.requestPermission().then(permission => {
                        if (permission === 'granted') {
                            new Notification(title, {
                                body: message,
                                icon: type === 'success' ? '✅' : type === 'error' ? '❌' : 'ℹ️'
                            });
                        }
                    });
                }
            }
        }

        // เรียกใช้การแจ้งเตือนเมื่อมีคำสั่งซื้อใหม่ที่รอการอนุมัติ
        function checkForPendingApprovals() {
            const pendingOrders = orders.filter(order =>
                (order.payment_status === 1 || order.PaymentStatusID === 1)
            );

            if (pendingOrders.length > 0) {
                showNotification(
                    'การแจ้งเตือนระบบ',
                    `มีคำสั่งซื้อ ${pendingOrders.length} รายการรอการอนุมัติการชำระเงิน`,
                    'info'
                );
            }
        }

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

            // ตรวจสอบการอนุมัติที่รออยู่หลังจาก 3 วินาที
            setTimeout(checkForPendingApprovals, 3000);
        });
    </script>
</body>

</html>