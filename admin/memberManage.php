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
    <title>จัดการลูกค้า</title>
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
            margin-bottom: 20px;
        }

        .page-title {
            font-size: 24px;
            color: #333;
        }

        .customer-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin-right: 5px;
            transition: all 0.3s;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-info:hover:not(:disabled) {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover:not(:disabled) {
            background-color: #c82333;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            font-size: 14px;
        }

        .btn-success:hover:not(:disabled) {
            background-color: #218838;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            font-size: 14px;
        }

        .btn-primary:hover:not(:disabled) {
            background-color: #0056b3;
        }

        .customer-detail-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        /* Address Section */
        .address-section {
            margin-top: 30px;
            border-top: 2px solid #eee;
            padding-top: 20px;
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .address-list {
            display: grid;
            gap: 15px;
        }

        .address-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 15px;
            position: relative;
        }

        .address-card.default {
            border-color: #28a745;
            background: #d4edda;
        }

        .address-card .default-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
        }

        .address-card .address-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .address-card .recipient {
            color: #666;
            margin-bottom: 5px;
        }

        .address-card .address-text {
            color: #333;
            line-height: 1.4;
        }

        .address-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
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
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: white;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
            animation: slideIn 0.3s;
        }

        .modal-header {
            padding: 20px 30px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            color: #333;
            font-size: 18px;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .modal-close:hover {
            background-color: #f0f0f0;
            color: #333;
        }

        .modal-body {
            padding: 30px;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover:not(:disabled) {
            background-color: #545b62;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 11px;
        }

        .order-count {
            font-weight: bold;
            color: #007bff;
            margin-right: 10px;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }

        .order-count.zero {
            color: #6c757d;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .orders-table th,
        .orders-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .orders-table th {
            background-color: #C957BC;
            font-weight: 600;
        }

        .order-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-paid { background-color: #d4edda; color: #155724; }
        .status-shipped { background-color: #cce5ff; color: #004085; }
        .status-delivered { background-color: #d1ecf1; color: #0c5460; }
        .status-cancelled { background-color: #f8d7da; color: #721c24; }

        .orders-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .orders-summary h4 {
            margin: 0 0 10px 0;
            color: #333;
        }

        .orders-summary p {
            margin: 5px 0;
            color: #666;
        }

        .btn-default {
            background-color: #28a745;
            color: white;
        }

        .btn-default:hover:not(:disabled) {
            background-color: #218838;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <!-- หน้ารายการลูกค้า -->
        <div id="customerList">
            <div class="page-header">
                <h1 class="page-title">รายการลูกค้า</h1>
            </div>

            <div class="customer-table">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสลูกค้า</th>
                            <th>ชื่อ - นามสกุล</th>
                            <th>อีเมล</th>
                            <th>เบอร์โทร</th>
                            <th>จำนวนออเดอร์</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        <tr>
                            <td colspan="6" class="loading">กำลังโหลดข้อมูล...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้ารายละเอียดลูกค้า -->
        <div id="customerDetail" class="hidden">
            <div class="page-header">
                <h1 class="page-title">รายละเอียดลูกค้า</h1>
            </div>

            <div id="detailAlert" class="alert hidden">
                <span id="detailAlertMessage"></span>
            </div>

            <div class="customer-detail-container">
                <form id="customerForm">
                    <div class="form-group">
                        <label for="customerId">รหัสลูกค้า</label>
                        <input type="text" id="customerId" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="customerEmail">อีเมล</label>
                        <input type="email" id="customerEmail" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="customerFirstname">ชื่อ</label>
                        <input type="text" id="customerFirstname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="customerLastname">นามสกุล</label>
                        <input type="text" id="customerLastname" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="customerPhone">เบอร์โทร</label>
                        <input type="tel" id="customerPhone" class="form-control">
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success" id="saveBtn" onclick="saveCustomer()">บันทึกการแก้ไข</button>
                        <button type="button" class="btn btn-primary" onclick="showCustomerList()">กลับสู่หน้ารายการลูกค้า</button>
                    </div>
                </form>

                <!-- Address Section -->
                <div class="address-section">
                    <div class="address-header">
                        <h3>ที่อยู่ทั้งหมด</h3>
                        <button class="btn btn-primary" onclick="showAddressModal()">เพิ่มที่อยู่ใหม่</button>
                    </div>
                    <div id="addressList" class="address-list">
                        <div class="loading">กำลังโหลดที่อยู่...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Modal -->
        <div id="ordersModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">รายการออเดอร์ของลูกค้า</h2>
                    <button class="modal-close" onclick="closeOrdersModal()">×</button>
                </div>
                <div class="modal-body">
                    <div id="ordersList">
                        <div class="loading">กำลังโหลดข้อมูลออเดอร์...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Modal -->
        <div id="addressModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title" id="modalTitle">เพิ่มที่อยู่ใหม่</h2>
                    <button class="modal-close" onclick="closeAddressModal()">×</button>
                </div>
                <div class="modal-body">
                    <form id="addressForm">
                        <input type="hidden" id="addressId" value="">
                        <input type="hidden" id="addressMemberId" value="">

                        <div class="form-group">
                            <label for="addressName">ชื่อที่อยู่</label>
                            <input type="text" id="addressName" class="form-control" placeholder="เช่น ที่บ้าน, ที่ทำงาน" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="recipientName">ชื่อผู้รับ</label>
                                <input type="text" id="recipientName" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="recipientPhone">เบอร์โทรผู้รับ</label>
                                <input type="tel" id="recipientPhone" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="addressLine">ที่อยู่</label>
                            <textarea id="addressLine" class="form-control" placeholder="บ้านเลขที่, หมู่บ้าน, ซอย, ถนน" required></textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="subDistrict">ตำบล/แขวง</label>
                                <input type="text" id="subDistrict" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="district">อำเภอ/เขต</label>
                                <input type="text" id="district" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="province">จังหวัด</label>
                                <input type="text" id="province" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="postalCode">รหัสไปรษณีย์</label>
                                <input type="text" id="postalCode" class="form-control" pattern="[0-9]{5}" maxlength="5" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="isDefault"> ตั้งเป็นที่อยู่เริ่มต้น
                            </label>
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn btn-success" id="saveAddressBtn" onclick="saveAddress()">บันทึก</button>
                            <button type="button" class="btn btn-secondary" onclick="closeAddressModal()">ยกเลิก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/notification.js"></script>
    <script>
        let customers = [];
        let currentCustomerId = null;

        // API Helper Functions
        async function apiRequest(url, options = {}) {
            try {
                const response = await fetch(url, {
                    headers: {
                        'Content-Type': 'application/json',
                        ...options.headers
                    },
                    ...options
                });
                
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        // Load all customers
        async function loadCustomers() {
            const hideLoading = showLoading('กำลังโหลดข้อมูลลูกค้า...');
            
            try {
                const data = await apiRequest('../controller/member_api.php?action=all');
                hideLoading();
                
                if (Array.isArray(data)) {
                    customers = data;
                    renderCustomerTable();
                    showSuccess('โหลดข้อมูลลูกค้าเรียบร้อยแล้ว');
                } else {
                    showError('เกิดข้อผิดพลาดในการโหลดข้อมูลลูกค้า');
                }
            } catch (error) {
                hideLoading();
                console.error('Error loading customers:', error);
                showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                document.getElementById('customerTableBody').innerHTML = '<tr><td colspan="5" class="no-data">ไม่สามารถโหลดข้อมูลได้</td></tr>';
            }
        }

        // Load customer orders
        async function loadCustomerOrders(memberId) {
            try {
                document.getElementById('ordersList').innerHTML = '<div class="loading">กำลังโหลดข้อมูลออเดอร์...</div>';
                
                const data = await apiRequest(`../controller/order_api.php?action=member-orders&member_id=${memberId}`);
                if (data.success && data.data) {
                    renderOrdersList(data.data);
                } else {
                    document.getElementById('ordersList').innerHTML = '<div class="no-data">ไม่มีออเดอร์</div>';
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                document.getElementById('ordersList').innerHTML = '<div class="no-data">เกิดข้อผิดพลาดในการโหลดออเดอร์</div>';
                showError('เกิดข้อผิดพลาดในการโหลดออเดอร์');
            }
        }

        // Load customer addresses
        async function loadCustomerAddresses(memberId) {
            try {
                const data = await apiRequest(`../controller/member_api.php?action=addresses&member_id=${memberId}`);
                if (data.success && data.data) {
                    renderAddressList(data.data);
                } else {
                    document.getElementById('addressList').innerHTML = '<div class="no-data">ไม่มีที่อยู่</div>';
                }
            } catch (error) {
                console.error('Error loading addresses:', error);
                document.getElementById('addressList').innerHTML = '<div class="no-data">เกิดข้อผิดพลาดในการโหลดที่อยู่</div>';
                showError('เกิดข้อผิดพลาดในการโหลดที่อยู่');
            }
        }

        // Render customer table
        function renderCustomerTable() {
            const tbody = document.getElementById('customerTableBody');
            
            if (customers.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="no-data">ไม่มีข้อมูลลูกค้า</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            customers.forEach(customer => {
                const row = `
                    <tr>
                        <td>${customer.member_id || '-'}</td>
                        <td>${customer.first_name || ''} ${customer.last_name || ''}</td>
                        <td>${customer.email || '-'}</td>
                        <td>${customer.tel || customer.phone || '-'}</td>
                        <td>
                            <span class="order-count ${(customer.order_count && customer.order_count > 0) ? '' : 'zero'}">${customer.order_count || 0}</span>
                            ${(customer.order_count && customer.order_count > 0) ? `<button class="btn btn-sm btn-primary" onclick="viewMemberOrders('${customer.member_id}')">ดูออเดอร์</button>` : ''}
                        </td>
                        <td>
                            <button class="btn btn-info" onclick="viewCustomerDetail('${customer.member_id}')">รายละเอียด</button>
                            <button class="btn btn-danger" onclick="deleteCustomer('${customer.member_id}')">ลบ</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Render orders list
        function renderOrdersList(orders) {
            const ordersList = document.getElementById('ordersList');
            
            if (!orders || orders.length === 0) {
                ordersList.innerHTML = '<div class="no-data">ไม่มีออเดอร์</div>';
                return;
            }

            // Calculate summary
            const totalOrders = orders.length;
            const totalAmount = orders.reduce((sum, order) => sum + parseFloat(order.total_amount || 0), 0);
            const pendingOrders = orders.filter(order => order.order_status == 1).length;
            const paidOrders = orders.filter(order => order.order_status == 2).length;
            const shippedOrders = orders.filter(order => order.order_status == 3).length;
            const deliveredOrders = orders.filter(order => order.order_status == 4).length;
            const cancelledOrders = orders.filter(order => order.order_status == 5).length;

            let ordersHtml = `
                <div class="orders-summary">
                    <h4>สรุปออเดอร์</h4>
                    <p><strong>จำนวนออเดอร์ทั้งหมด:</strong> ${totalOrders} รายการ</p>
                    <p><strong>ยอดรวมทั้งหมด:</strong> ${totalAmount.toLocaleString('th-TH', {style: 'currency', currency: 'THB'})}</p>
                    <p><strong>สถานะ:</strong> รอชำระเงิน: ${pendingOrders}, ชำระแล้ว: ${paidOrders}, จัดส่งแล้ว: ${shippedOrders}, จัดส่งสำเร็จ: ${deliveredOrders}, ยกเลิก: ${cancelledOrders}</p>
                </div>
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>เลขที่ออเดอร์</th>
                            <th>วันที่สั่งซื้อ</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                            <th>การชำระเงิน</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            orders.forEach(order => {
                const orderDate = new Date(order.create_at).toLocaleDateString('th-TH');
                const totalAmount = parseFloat(order.total_amount || 0).toLocaleString('th-TH', {
                    style: 'currency',
                    currency: 'THB'
                });
                
                const statusClass = getStatusClass(order.order_status);
                const statusText = getStatusText(order.order_status);
                const paymentStatus = order.order_status == 2 || order.order_status <= 4 ? 'ชำระแล้ว' : 'ยังไม่ชำระ';

                ordersHtml += `
                    <tr>
                        <td>${order.order_number || order.order_id || '-'}</td>
                        <td>${orderDate}</td>
                        <td>${totalAmount}</td>
                        <td><span class="order-status ${statusClass}">${statusText}</span></td>
                        <td>${paymentStatus}</td>
                    </tr>
                `;
            });

            ordersHtml += `
                    </tbody>
                </table>
            `;

            ordersList.innerHTML = ordersHtml;
        }

        // Get status class for styling
        function getStatusClass(status) {
            switch(parseInt(status)) {
                case 1: return 'status-pending';
                case 2: return 'status-paid';
                case 3: return 'status-shipped';
                case 4: return 'status-delivered';
                case 5: return 'status-cancelled';
                default: return '';
            }
        }

        // Get status text
        function getStatusText(status) {
            switch(parseInt(status)) {
                case 1: return 'รอชำระเงิน';
                case 2: return 'ชำระเงินแล้ว';
                case 3: return 'จัดส่งแล้ว';
                case 4: return 'จัดส่งสำเร็จ';
                case 5: return 'ยกเลิก';
                default: return 'ไม่ทราบสถานะ';
            }
        }

        // Render address list
        function renderAddressList(addresses) {
            const addressList = document.getElementById('addressList');
            
            if (!addresses || addresses.length === 0) {
                addressList.innerHTML = '<div class="no-data">ไม่มีที่อยู่</div>';
                return;
            }

            addressList.innerHTML = '';
            addresses.forEach(address => {
                const isDefault = address.is_default == 1;
                const addressCard = `
                    <div class="address-card ${isDefault ? 'default' : ''}">
                        ${isDefault ? '<span class="default-badge">ค่าเริ่มต้น</span>' : ''}
                        <div class="address-header">
                            <div class="address-type">${address.address_name || 'ที่อยู่'}</div>
                        </div>
                        <div class="address-details">
                            <strong>${address.recipient_name || ''}</strong><br>
                            ${address.address_line || ''}<br>
                            ${address.sub_district || ''} ${address.district || ''} ${address.province || ''} ${address.postal_code || ''}<br>
                            โทร: ${address.recipient_phone || ''}
                        </div>
                        <div class="address-actions">
                            ${!isDefault ? `<button class="btn btn-default btn-sm" onclick="setDefaultAddress('${address.address_id}')">ตั้งเป็นค่าเริ่มต้น</button>` : ''}
                            <button class="btn btn-secondary btn-sm" onclick="editAddress('${address.address_id}')">แก้ไข</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteAddress('${address.address_id}')">ลบ</button>
                        </div>
                    </div>
                `;
                addressList.innerHTML += addressCard;
            });
        }

        // Show/Hide sections
        function showCustomerList() {
            document.getElementById('customerList').classList.remove('hidden');
            document.getElementById('customerDetail').classList.add('hidden');
            currentCustomerId = null;
        }

        function showCustomerDetail() {
            document.getElementById('customerList').classList.add('hidden');
            document.getElementById('customerDetail').classList.remove('hidden');
        }

        // View customer detail
        async function viewCustomerDetail(customerId) {
            const hideLoading = showLoading('กำลังโหลดข้อมูลลูกค้า...');
            
            try {
                const data = await apiRequest(`../controller/member_api.php?action=get&id=${customerId}`);
                hideLoading();
                
                if (data && data.member_id) {
                    showCustomerDetail();
                    
                    // Fill form with customer data
                    document.getElementById('customerId').value = data.member_id || '';
                    document.getElementById('customerEmail').value = data.email || '';
                    document.getElementById('customerFirstname').value = data.first_name || '';
                    document.getElementById('customerLastname').value = data.last_name || '';
                    document.getElementById('customerPhone').value = data.tel || data.phone || '';
                    
                    currentCustomerId = customerId;
                    
                    // Load addresses
                    await loadCustomerAddresses(customerId);
                    showSuccess('โหลดข้อมูลลูกค้าเรียบร้อยแล้ว');
                } else {
                    showError('ไม่พบข้อมูลลูกค้า');
                }
            } catch (error) {
                hideLoading();
                console.error('Error loading customer detail:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูลลูกค้า');
            }
        }

        // Save customer
        async function saveCustomer() {
            const formData = {
                email: document.getElementById('customerEmail').value.trim(),
                firstname: document.getElementById('customerFirstname').value.trim(),
                lastname: document.getElementById('customerLastname').value.trim(),
                tel: document.getElementById('customerPhone').value.trim()
            };

            // Validate required fields
            if (!formData.email || !formData.firstname || !formData.lastname) {
                showWarning('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
                return;
            }

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                showWarning('รูปแบบอีเมลไม่ถูกต้อง');
                return;
            }

            const hideLoading = showLoading('กำลังบันทึกข้อมูล...');

            try {
                // Disable save button
                const saveBtn = document.getElementById('saveBtn');
                if (saveBtn) {
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'กำลังบันทึก...';
                }

                const response = await apiRequest(`../controller/member_api.php?action=update&id=${currentCustomerId}`, {
                    method: 'PUT',
                    body: JSON.stringify(formData)
                });

                hideLoading();

                if (response.success) {
                    showSuccess(response.message || 'บันทึกข้อมูลเรียบร้อยแล้ว');
                    // Reload customer list
                    await loadCustomers();
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            } catch (error) {
                hideLoading();
                console.error('Error saving customer:', error);
                showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
            } finally {
                // Re-enable save button
                const saveBtn = document.getElementById('saveBtn');
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'บันทึกการแก้ไข';
                }
            }
        }

        // Delete customer
        async function deleteCustomer(customerId) {
            showConfirm(
                'คุณแน่ใจหรือไม่ที่จะลบลูกค้ารายนี้?',
                async function() {
                    const hideLoading = showLoading('กำลังลบข้อมูล...');
                    
                    try {
                        const response = await apiRequest(`../controller/member_api.php?action=delete&id=${customerId}`, {
                            method: 'DELETE'
                        });

                        hideLoading();

                        if (response.success) {
                            showSuccess(response.message || 'ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
                            await loadCustomers();
                        } else {
                            showError(response.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
                        }
                    } catch (error) {
                        hideLoading();
                        console.error('Error deleting customer:', error);
                        showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                    }
                }
            );
        }

        // Delete address
        async function deleteAddress(addressId) {
            showConfirm(
                'คุณแน่ใจหรือไม่ที่จะลบที่อยู่นี้?',
                async function() {
                    const hideLoading = showLoading('กำลังลบที่อยู่...');
                    
                    try {
                        const response = await apiRequest(`../controller/member_api.php?action=delete-address&address_id=${addressId}`, {
                            method: 'DELETE'
                        });

                        hideLoading();

                        if (response.success) {
                            showSuccess(response.message || 'ลบที่อยู่เรียบร้อยแล้ว');
                            await loadCustomerAddresses(currentCustomerId);
                        } else {
                            showError(response.message || 'เกิดข้อผิดพลาดในการลบที่อยู่');
                        }
                    } catch (error) {
                        hideLoading();
                        console.error('Error deleting address:', error);
                        showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
                    }
                }
            );
        }

        // Edit address
        async function editAddress(addressId) {
            const hideLoading = showLoading('กำลังโหลดข้อมูลที่อยู่...');
            
            try {
                const data = await apiRequest(`../controller/member_api.php?action=address&address_id=${addressId}`);
                hideLoading();
                
                if (data.success && data.data) {
                    const address = data.data;
                    
                    // Fill the form with existing data
                    document.getElementById('modalTitle').textContent = 'แก้ไขที่อยู่';
                    document.getElementById('addressId').value = address.address_id || '';
                    document.getElementById('addressMemberId').value = address.member_id || currentCustomerId;
                    document.getElementById('addressName').value = address.address_name || '';
                    document.getElementById('recipientName').value = address.recipient_name || '';
                    document.getElementById('recipientPhone').value = address.recipient_phone || '';
                    document.getElementById('addressLine').value = address.address_line || '';
                    document.getElementById('subDistrict').value = address.sub_district || '';
                    document.getElementById('district').value = address.district || '';
                    document.getElementById('province').value = address.province || '';
                    document.getElementById('postalCode').value = address.postal_code || '';
                    document.getElementById('isDefault').checked = address.is_default == 1;
                    
                    showAddressModal(true);
                } else {
                    showError('ไม่พบข้อมูลที่อยู่');
                }
            } catch (error) {
                hideLoading();
                console.error('Error loading address:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูลที่อยู่');
            }
        }

        // Show orders modal
        function showOrdersModal() {
            document.getElementById('ordersModal').classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        // Close orders modal
        function closeOrdersModal() {
            document.getElementById('ordersModal').classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // View member orders
        async function viewMemberOrders(memberId) {
            showOrdersModal();
            await loadCustomerOrders(memberId);
            // Refresh customer list to update order counts
            await loadCustomers();
        }

        // Show address modal
        function showAddressModal(isEdit = false) {
            if (!isEdit) {
                // Clear form for new address
                document.getElementById('modalTitle').textContent = 'เพิ่มที่อยู่ใหม่';
                document.getElementById('addressForm').reset();
                document.getElementById('addressId').value = '';
                document.getElementById('addressMemberId').value = currentCustomerId;
            }
            
            document.getElementById('addressModal').classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scroll
        }

        // Close address modal
        function closeAddressModal() {
            document.getElementById('addressModal').classList.remove('show');
            document.body.style.overflow = 'auto'; // Restore scroll
        }

        // Save address
        async function saveAddress() {
            const formData = {
                member_id: document.getElementById('addressMemberId').value,
                recipient_name: document.getElementById('recipientName').value.trim(),
                recipient_phone: document.getElementById('recipientPhone').value.trim(),
                address_name: document.getElementById('addressName').value.trim(),
                address_line: document.getElementById('addressLine').value.trim(),
                sub_district: document.getElementById('subDistrict').value.trim(),
                district: document.getElementById('district').value.trim(),
                province: document.getElementById('province').value.trim(),
                postal_code: document.getElementById('postalCode').value.trim(),
                is_default: document.getElementById('isDefault').checked ? 1 : 0
            };

            // Validate required fields
            const requiredFields = [
                'recipient_name', 'recipient_phone', 'address_name', 
                'address_line', 'sub_district', 'district', 'province', 'postal_code'
            ];

            for (let field of requiredFields) {
                if (!formData[field]) {
                    showWarning('กรุณากรอกข้อมูลให้ครบถ้วน');
                    return;
                }
            }

            // Validate postal code
            if (!/^[0-9]{5}$/.test(formData.postal_code)) {
                showWarning('รหัสไปรษณีย์ต้องเป็นตัวเลข 5 หลัก');
                return;
            }

            const hideLoading = showLoading('กำลังบันทึกที่อยู่...');

            try {
                // Disable save button
                const saveBtn = document.getElementById('saveAddressBtn');
                if (saveBtn) {
                    saveBtn.disabled = true;
                    saveBtn.textContent = 'กำลังบันทึก...';
                }

                const addressId = document.getElementById('addressId').value;
                let response;

                if (addressId) {
                    // Update existing address
                    response = await apiRequest(`../controller/member_api.php?action=update-address&address_id=${addressId}`, {
                        method: 'PUT',
                        body: JSON.stringify(formData)
                    });
                } else {
                    // Create new address
                    response = await apiRequest(`../controller/member_api.php?action=create-address`, {
                        method: 'POST',
                        body: JSON.stringify(formData)
                    });
                }

                hideLoading();

                if (response.success) {
                    showSuccess(response.message || 'บันทึกที่อยู่เรียบร้อยแล้ว');
                    closeAddressModal();
                    await loadCustomerAddresses(currentCustomerId);
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการบันทึกที่อยู่');
                }
            } catch (error) {
                hideLoading();
                console.error('Error saving address:', error);
                showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
            } finally {
                // Re-enable save button
                const saveBtn = document.getElementById('saveAddressBtn');
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'บันทึก';
                }
            }
        }

        // Set default address
        async function setDefaultAddress(addressId) {
            const hideLoading = showLoading('กำลังตั้งค่าที่อยู่เริ่มต้น...');
            
            try {
                // Get current address data first
                const addressData = await apiRequest(`../controller/member_api.php?action=address&address_id=${addressId}`);
                if (!addressData.success || !addressData.data) {
                    hideLoading();
                    showError('ไม่พบข้อมูลที่อยู่');
                    return;
                }

                const address = addressData.data;
                const updateData = {
                    recipient_name: address.recipient_name,
                    recipient_phone: address.recipient_phone,
                    address_name: address.address_name,
                    address_line: address.address_line,
                    sub_district: address.sub_district,
                    district: address.district,
                    province: address.province,
                    postal_code: address.postal_code,
                    is_default: 1
                };

                const response = await apiRequest(`../controller/member_api.php?action=update-address&address_id=${addressId}`, {
                    method: 'PUT',
                    body: JSON.stringify(updateData)
                });

                hideLoading();

                if (response.success) {
                    showSuccess('ตั้งค่าที่อยู่เริ่มต้นเรียบร้อยแล้ว');
                    await loadCustomerAddresses(currentCustomerId);
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการตั้งค่าที่อยู่เริ่มต้น');
                }
            } catch (error) {
                hideLoading();
                console.error('Error setting default address:', error);
                showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
            }
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadCustomers();
            
            // Close modal when clicking outside
            document.getElementById('addressModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAddressModal();
                }
            });

            // Close orders modal when clicking outside
            document.getElementById('ordersModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeOrdersModal();
                }
            });
        });
    </script>
</body>
</html>