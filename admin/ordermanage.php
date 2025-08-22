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
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
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
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- หน้ารายการคำสั่งซื้อ -->
        <div id="orderList">
            <div class="page-header">
                <h1 class="page-title">หน้าจัดการคำสั่งซื้อ : รายการคำสั่งซื้อ</h1>
                <p class="page-subtitle">จัดการคำสั่งซื้อทั้งหมดในระบบ</p>
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
                            <td colspan="6" class="loading">กำลังโหลดข้อมูล...</td>
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
                    <h3>หลักฐานการชำระเงิน</h3>
                    <div id="paymentSlipPreview"></div>
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
                <input type="text" id="statusNotes" placeholder="หมายเหตุเพิ่มเติม">
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
                    <option value="1">ชำระแล้ว</option>
                </select>
            </div>
            <button class="btn btn-success" onclick="updatePaymentStatus()">บันทึก</button>
            <button class="btn btn-info" onclick="confirmPayment()">ยืนยันการชำระเงิน</button>
            <button class="btn" onclick="closeModal('paymentModal')">ยกเลิก</button>
        </div>
    </div>

    <script>
        // API Base URL - ปรับตามที่ตั้งไฟล์ API
        const API_BASE_URL = '../controller/order_api.php';

        let currentOrderId = null;
        let orders = [];

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
                    renderOrderTable();

                    // ลบข้อความ loading
                    const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                    loadingMessages.forEach(msg => msg.remove());

                } else {
                    throw new Error(result.message || 'ไม่สามารถดึงข้อมูลได้');
                }

            } catch (error) {
                console.error('Error loading orders:', error);
                showMessage('เกิดข้อผิดพลาดในการโหลดข้อมูล: ' + error.message, 'error');

                // แสดงข้อมูลว่างในกรณีที่เกิดข้อผิดพลาด
                orders = [];
                renderOrderTable();
            }
        }

        // ฟังก์ชันแสดงรายการในตาราง
        function renderOrderTable() {
            const tbody = document.getElementById('orderTableBody');
            tbody.innerHTML = '';

            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading">ไม่พบข้อมูลคำสั่งซื้อ</td></tr>';
                return;
            }

            orders.forEach(order => {
                // รองรับทั้ง field names แบบ camelCase และ snake_case
                const orderNumber = order.order_number || order.OrderNumber;
                const customerName = order.customer_name || order.recipient_name;
                const totalAmount = order.total_amount || order.TotalAmount;
                const orderDate = order.created_at || order.order_date || order.OrderDate;
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
                showMessage('กำลังโหลดรายละเอียด...', 'loading');

                const response = await fetch(`${API_BASE_URL}?action=get&order_id=${orderId}`);
                const result = await response.json();

                if (result.success && result.data) {
                    displayOrderDetail(result.data);

                    // ลบข้อความ loading
                    const loadingMessages = document.querySelectorAll('.success-message, .error-message');
                    loadingMessages.forEach(msg => msg.remove());

                } else {
                    throw new Error(result.message || 'ไม่พบข้อมูลคำสั่งซื้อ');
                }

                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');

            } catch (error) {
                console.error('Error loading order detail:', error);
                showMessage('เกิดข้อผิดพลาดในการโหลดรายละเอียด: ' + error.message, 'error');
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
            const statusName = order.status_name || order.StatusName;
            const statusId = order.order_status_id || order.OrderStatusID;
            const paymentStatusName = order.payment_status_name || order.PaymentStatusName;
            const paymentStatusId = order.payment_status_id || order.PaymentStatusID;
            const trackingNumber = order.tracking_number || order.TrackingNumber;
            const paymentSlipPath = order.payment_slip_path || order.PaymentSlipPath;

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
            `;

            // แสดงรายการสินค้า
            displayOrderItems(order.items || order.order_items || []);

            // แสดงหลักฐานการชำระเงิน (ถ้ามี)
            if (paymentSlipPath) {
                displayPaymentSlip(paymentSlipPath);
            } else {
                document.getElementById('paymentSlipSection').classList.add('hidden');
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
                <div class="payment-slip-preview">
                    <img src="../controller/${imagePath}" alt="หลักฐานการชำระเงิน" onclick="openImageModal('${imagePath}')">
                </div>
            `;

            section.classList.remove('hidden');
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
                        changed_by: 'admin' // ในการใช้งานจริงควรใช้ ID ของผู้ใช้ที่ล็อกอิน
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showMessage('อัปเดตสถานะสำเร็จ', 'success');
                    closeModal('statusModal');
                    // รีโหลดข้อมูล
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
                    // อัปเดตการแสดงผล
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
                        payment_status: 1, // ชำระแล้ว
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

        // ลบฟังก์ชัน rejectPayment ออกเนื่องจากไม่มี payment status สำหรับ "ปฏิเสธ"
        // ฟังก์ชัน rejectPayment ถูกลบออกแล้ว

        // ฟังก์ชันเปิด Modal
        function openStatusModal() {
            document.getElementById('statusModal').style.display = 'block';
        }

        function openTrackingModal() {
            // ใส่ค่าเดิมถ้ามี
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

        // ฟังก์ชันปิด Modal
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';

            // เคลียร์ฟอร์ม
            if (modalId === 'statusModal') {
                document.getElementById('statusNotes').value = '';
            } else if (modalId === 'trackingModal') {
                document.getElementById('trackingNumber').value = '';
            }
        }

        // ปิด Modal เมื่อคลิกข้างนอก
        window.onclick = function(event) {
            const modals = ['statusModal', 'trackingModal', 'paymentModal'];
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
                    return 'status-pending'; // รออการยืนยันคำสั่งซื้อ
                case 2:
                    return 'status-processing'; // ชำระเงินแล้ว / รอการตรวจสอบ
                case 3:
                    return 'status-processing'; // กำลังจัดเตรียม
                case 4:
                    return 'status-completed'; // จัดส่งสำเร็จ
                case 5:
                    return 'status-cancelled'; // ยกเลิกคำสั่งซื้อ
                default:
                    return 'status-pending';
            }
        }

        function getPaymentStatusClass(statusId) {
            switch (parseInt(statusId)) {
                case 0:
                    return 'status-pending'; // ยังไม่ชำระ
                case 1:
                    return 'status-completed'; // ชำระแล้ว
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
            return {
                OrderID: orderId,
                OrderNumber: `ORD00124${orderId + 4}`,
                CustomerName: 'สมยศ นิติรัตน์',
                ShippingAddress: '39/1 ถนนสยามสแควร์ พระราม เกรีย',
                ShippingPhone: '081-234-5678',
                OrderDate: '2025-01-19',
                TotalAmount: 1200,
                OrderStatusID: orderId === 1 ? 2 : orderId === 2 ? 4 : 1,
                StatusName: orderId === 1 ? 'ชำระเงินแล้ว / รอการตรวจสอบ' : orderId === 2 ? 'จัดส่งสำเร็จ' : 'รออการยืนยันคำสั่งซื้อ',
                PaymentStatusID: orderId === 1 ? 1 : orderId === 2 ? 1 : 0,
                PaymentStatusName: orderId === 1 ? 'ชำระแล้ว' : orderId === 2 ? 'ชำระแล้ว' : 'ยังไม่ชำระ',
                TrackingNumber: orderId === 1 ? 'TH1234567899' : orderId === 2 ? 'TH9876543210' : null,
                PaymentSlipPath: orderId <= 2 ? 'uploads/payment_slips/sample_slip.jpg' : null
            };
        }

        function getMockOrderItems(orderId) {
            return [{
                    ShoeName: 'รองเท้าผ้าใบสีดำ Nike Air Max',
                    Size: '42',
                    Quantity: 1,
                    Price: 1200
                },
                {
                    ShoeName: 'รองเท้าผ้าใบสีขาว Adidas Ultraboost',
                    Size: '41',
                    Quantity: 1,
                    Price: 1500
                }
            ];
        }

        // ฟังก์ชันเปิดรูปภาพในหน้าต่างใหม่
        function openImageModal(imagePath) {
            window.open(imagePath, '_blank', 'width=800,height=600,scrollbars=yes,resizable=yes');
        }

        // เพิ่มฟังก์ชันการค้นหาและกรอง (สำหรับอนาคต)
        function filterOrders(status) {
            // ฟังก์ชันกรองตามสถานะ
            const filteredOrders = orders.filter(order =>
                status === 'all' || order.OrderStatusID == status
            );
            renderFilteredOrders(filteredOrders);
        }

        function renderFilteredOrders(filteredOrders) {
            const tbody = document.getElementById('orderTableBody');
            tbody.innerHTML = '';

            if (filteredOrders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="loading">ไม่พบข้อมูลตามเงื่อนไขที่กำหนด</td></tr>';
                return;
            }

            filteredOrders.forEach(order => {
                const statusClass = getStatusClass(order.Ordertatus);
                const row = `
                    <tr>
                        <td>${order.OrderNumber}</td>
                        <td>รองเท้าผ้าใบสีดำ</td>
                        <td>฿${order.TotalAmount.toLocaleString()}</td>
                        <td>${order.CustomerName}</td>
                        <td>${formatDate(order.OrderDate)}</td>
                        <td><span class="status-badge ${statusClass}">${order.StatusName}</span></td>
                        <td class="action-buttons">
                            <button class="btn btn-info" onclick="viewOrderDetail(${order.OrderID})">ดูรายละเอียด</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // เพิ่มฟังก์ชันรีเฟรช
        function refreshOrders() {
            loadOrders();
        }

        // เพิ่มปุ่มรีเฟรชในหน้า (ถ้าต้องการ)
        function addRefreshButton() {
            const header = document.querySelector('.page-header');
            const refreshBtn = document.createElement('button');
            refreshBtn.className = 'btn btn-info';
            refreshBtn.textContent = 'รีเฟรชข้อมูล';
            refreshBtn.onclick = refreshOrders;
            refreshBtn.style.float = 'right';
            header.appendChild(refreshBtn);
        }

        // เรียกใช้เมื่อโหลดหน้า
        // addRefreshButton();
    </script>
</body>

</html>