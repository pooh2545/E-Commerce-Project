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
    <title>จัดการการส่งสินค้า</title>
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
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }

        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .btn-filter {
            background-color: #007bff;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-filter:hover {
            background-color: #0056b3;
        }

        .btn:disabled {
            opacity: 0.5 !important;
            cursor: not-allowed !important;
            pointer-events: none;
        }

        .btn-update:disabled {
            background-color: #6c757d !important;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .shipping-table {
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
            padding: 15px 10px;
            text-align: left;
            font-size: 14px;
            font-weight: 500;
        }

        td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
            font-size: 14px;
        }

        tr:hover {
            background-color: #f8f9fa;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            min-width: 80px;
            display: inline-block;
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

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
            margin-right: 5px;
        }

        .btn-update {
            background-color: #007bff;
            color: white;
        }

        .btn-update:hover {
            background-color: #0056b3;
        }

        .btn-track {
            background-color: #28a745;
            color: white;
        }

        .btn-track:hover {
            background-color: #218838;
        }

        .address-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-title {
            font-size: 18px;
            color: #333;
            margin: 0;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            padding: 10px 20px;
        }

        .tracking-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }

        .tracking-steps {
            list-style: none;
            margin-top: 15px;
        }

        .tracking-steps li {
            padding: 8px 0;
            border-left: 3px solid #28a745;
            padding-left: 15px;
            margin-bottom: 10px;
            background-color: #f8f9fa;
        }

        .tracking-steps li.pending {
            border-left-color: #6c757d;
            color: #6c757d;
        }

        .order-id {
            font-weight: 600;
            color: #333;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding: 20px;
        }

        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
        }

        .pagination button:hover:not(:disabled) {
            background-color: #f8f9fa;
        }

        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }

        .pagination .active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
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
        <div class="page-header">
            <h1 class="page-title">จัดการการส่งสินค้า</h1>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="statusFilter">สถานะการส่ง</label>
                    <select id="statusFilter" onchange="filterOrders()">
                        <option value="all">ทั้งหมด</option>
                        <option value="1">รอการยืนยันคำสั่งซื้อ</option>
                        <option value="2">ชำระเงินแล้ว / รอการตรวจสอบ</option>
                        <option value="3">กำลังจัดส่ง</option>
                        <option value="4">จัดส่งสำเร็จ</option>
                        <option value="5">ยกเลิก</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="searchInput">ค้นหา</label>
                    <input type="text" id="searchInput" placeholder="ค้นหาเลขออเดอร์หรือชื่อลูกค้า" onkeyup="filterOrders()">
                </div>
                <button class="btn-filter" onclick="refreshOrders()">รีเฟรช</button>
            </div>
        </div>

        <div id="loadingMessage" class="loading" style="display: none;">
            กำลังโหลดข้อมูล...
        </div>

        <div id="errorMessage" class="error-message" style="display: none;"></div>

        <div class="shipping-table">
            <table>
                <thead>
                    <tr>
                        <th>รหัสคำสั่งซื้อ</th>
                        <th>ข้อมูลลูกค้า</th>
                        <th>ราคา</th>
                        <th>ที่อยู่</th>
                        <th>วันที่สั่งซื้อ</th>
                        <th>สถานะ</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody id="shippingTableBody">
                </tbody>
            </table>
            <div id="emptyState" class="empty-state" style="display: none;">
                ไม่พบข้อมูลการสั่งซื้อ
            </div>
        </div>

        <div class="pagination" id="pagination" style="display: none;">
            <button id="prevPage" onclick="changePage(-1)">« ก่อนหน้า</button>
            <span id="pageInfo"></span>
            <button id="nextPage" onclick="changePage(1)">ถัดไป »</button>
        </div>
    </div>

    <!-- Modal สำหรับอัปเดตสถานะการส่งสินค้า -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal('updateModal')">&times;</span>
                <h2 class="modal-title">อัปเดตสถานะการส่งสินค้า</h2>
            </div>
            <form id="updateForm">
                <div class="form-group">
                    <label for="orderId">รหัสคำสั่งซื้อ:</label>
                    <input type="text" id="orderId" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="trackingNumber">หมายเลขติดตาม: <span style="color: red;">*</span></label>
                    <input type="text" id="trackingNumber" class="form-control" placeholder="TH1234567890" required>
                    <small class="form-text text-muted">กรุณากรอกหมายเลขติดตามอย่างน้อย 8 ตัวอักษร</small>
                </div>

                <div class="form-group">
                    <label for="shippingNote">หมายเหตุ:</label>
                    <textarea id="shippingNote" class="form-control" rows="3" placeholder="หมายเหตุเพิ่มเติม..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('updateModal')">ยกเลิก</button>
                    <button type="button" class="btn btn-primary" onclick="saveUpdate()">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal สำหรับติดตามการส่งสินค้า -->
    <div id="trackModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal('trackModal')">&times;</span>
                <h2 class="modal-title">ติดตามการส่งสินค้า</h2>
            </div>
            <div id="trackingContent">
                <div class="tracking-info">
                    <p><strong>รหัสคำสั่งซื้อ:</strong> <span id="trackOrderId"></span></p>
                    <p><strong>หมายเลขติดตาม:</strong> <span id="trackNumber"></span></p>
                    <p><strong>สถานะปัจจุบัน:</strong> <span id="currentStatus"></span></p>
                </div>
                <h4 style="margin-top: 20px; margin-bottom: 15px;">ประวัติการส่งสินค้า:</h4>
                <ul class="tracking-steps" id="trackingSteps">
                </ul>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('trackModal')">ปิด</button>
            </div>
        </div>
    </div>

    <script src="../assets/js/notification.js"></script>
    <script>
        let currentPage = 1;
        let totalPages = 1;
        let currentOrderId = null;
        let allOrders = [];
        let filteredOrders = [];

        // Status mapping
        const statusMap = {
            '1': {
                text: 'รอการยืนยันคำสั่งซื้อ',
                class: 'status-pending'
            },
            '2': {
                text: 'ชำระเงินแล้ว / รอการตรวจสอบ',
                class: 'status-pending'
            },
            '3': {
                text: 'กำลังจัดส่ง',
                class: 'status-processing'
            },
            '4': {
                text: 'จัดส่งสำเร็จ',
                class: 'status-completed'
            },
            '5': {
                text: 'ยกเลิก',
                class: 'status-cancelled'
            }
        };

        // API Base URL
        const API_BASE_URL = '../controller/order_api.php';

        // Load orders from API
        async function loadOrders(page = 1) {
            let hideLoading = null;
            
            try {
                hideLoading = showLoading('กำลังโหลดข้อมูลออเดอร์...');
                hideError();

                const params = new URLSearchParams({
                    action: 'all',
                    page: page,
                    limit: 20
                });

                const response = await fetch(API_BASE_URL + '?' + params);
                const data = await response.json();

                if (data.success) {
                    allOrders = data.data || [];
                    filteredOrders = [...allOrders];
                    filterOrders();
                    updatePagination(data.pagination || {
                        current: 1,
                        total: 1
                    });
                } else {
                    showError(data.message || 'ไม่สามารถโหลดข้อมูลได้');
                    renderOrderTable([]);
                }
            } catch (error) {
                console.error('Error loading orders:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                renderOrderTable([]);
            } finally {
                if (hideLoading) hideLoading();
            }
        }

        // Render order table
        function renderOrderTable(orders) {
            const tbody = document.getElementById('shippingTableBody');
            const emptyState = document.getElementById('emptyState');

            if (!orders || orders.length === 0) {
                tbody.innerHTML = '';
                emptyState.style.display = 'block';
                return;
            }

            emptyState.style.display = 'none';
            tbody.innerHTML = '';

            orders.forEach(order => {
                const status = statusMap[order.order_status] || {
                    text: 'ไม่ทราบสถานะ',
                    class: 'status-pending'
                };
                const orderDate = new Date(order.create_at).toLocaleDateString('th-TH');

                const canUpdate = order.order_status == '3' && (!order.tracking_number || order.tracking_number.trim() === '');
                const hasTracking = order.tracking_number && order.tracking_number.trim() !== '';

                let updateButton = '';
                if (canUpdate) {
                    updateButton = '<button class="btn btn-update" onclick="updateShipping(\'' + order.order_id + '\')">เพิ่ม Tracking</button>';
                } else if (order.order_status == '3' && hasTracking) {
                    updateButton = '<button class="btn btn-update" disabled style="opacity: 0.5; cursor: not-allowed;" title="มี Tracking Number แล้ว">เพิ่ม Tracking</button>';
                } else {
                    updateButton = '<button class="btn btn-update" disabled style="opacity: 0.5; cursor: not-allowed;" title="ไม่สามารถแก้ไขได้ในสถานะนี้">เพิ่ม Tracking</button>';
                }

                const row = document.createElement('tr');
                row.innerHTML = 
                    '<td class="order-id">' + (order.order_number || '#' + order.order_id) + '</td>' +
                    '<td>' + (order.first_name + ' ' + order.last_name || 'ไม่ระบุ') + '</td>' +
                    '<td>฿' + parseFloat(order.total_amount).toLocaleString() + '</td>' +
                    '<td class="address-cell" title="' + (order.shipping_address || '') + '">' + (order.shipping_address || 'ไม่ระบุ') + '</td>' +
                    '<td>' + orderDate + '</td>' +
                    '<td>' +
                        '<span class="status-badge ' + status.class + '">' + status.text + '</span>' +
                        (hasTracking ? '<br><small style="color: #28a745;">Tracking: ' + order.tracking_number + '</small>' : '') +
                    '</td>' +
                    '<td>' +
                        updateButton +
                        '<button class="btn btn-track" onclick="trackShipping(\'' + order.order_id + '\')">ติดตาม</button>' +
                    '</td>';
                tbody.appendChild(row);
            });
        }

        // Filter orders
        function filterOrders() {
            const statusFilter = document.getElementById('statusFilter').value;
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();

            filteredOrders = allOrders.filter(order => {
                const matchStatus = statusFilter === 'all' || order.order_status == statusFilter;

                const matchSearch = searchTerm === '' ||
                    (order.order_number || '').toLowerCase().includes(searchTerm) ||
                    (order.order_id || '').toString().toLowerCase().includes(searchTerm) ||
                    (order.first_name || '').toLowerCase().includes(searchTerm) ||
                    (order.last_name || '').toLowerCase().includes(searchTerm) ||
                    ((order.first_name || '') + ' ' + (order.last_name || '')).toLowerCase().includes(searchTerm);

                return matchStatus && matchSearch;
            });

            renderOrderTable(filteredOrders);
        }

        // Refresh orders
        function refreshOrders() {
            document.getElementById('statusFilter').value = 'all';
            document.getElementById('searchInput').value = '';
            loadOrders(currentPage);
            showInfo('รีเฟรชข้อมูลเรียบร้อยแล้ว');
        }

        // Update shipping status
        async function updateShipping(orderId) {
            let hideLoading = null;
            
            try {
                hideLoading = showLoading('กำลังโหลดข้อมูลออเดอร์...');
                
                const response = await fetch(API_BASE_URL + '?action=get&order_id=' + orderId);
                const data = await response.json();

                if (data.success && data.data) {
                    const order = data.data;
                    const canUpdate = order.order_status == '3' && (!order.tracking_number || order.tracking_number.trim() === '');

                    if (!canUpdate) {
                        showWarning('ไม่สามารถแก้ไขคำสั่งซื้อนี้ได้ เนื่องจากสถานะไม่เหมาะสมหรือมี Tracking Number แล้ว');
                        return;
                    }

                    currentOrderId = orderId;
                    document.getElementById('orderId').value = order.order_number || '#' + orderId;
                    document.getElementById('trackingNumber').value = '';
                    document.getElementById('shippingNote').value = '';
                    document.querySelector('#updateModal .modal-title').textContent = 'เพิ่มหมายเลขติดตาม';
                    document.getElementById('updateModal').style.display = 'block';
                } else {
                    showError('ไม่สามารถโหลดข้อมูลออเดอร์ได้');
                }
            } catch (error) {
                console.error('Error loading order details:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            } finally {
                if (hideLoading) hideLoading();
            }
        }

        // Save update
        async function saveUpdate() {
            if (!currentOrderId) return;

            let hideLoading = null;
            
            try {
                const trackingNumber = document.getElementById('trackingNumber').value.trim();
                const notes = document.getElementById('shippingNote').value.trim();

                if (!trackingNumber) {
                    showWarning('กรุณากรอกหมายเลขติดตาม');
                    return;
                }

                if (trackingNumber.length < 8) {
                    showWarning('หมายเลขติดตามต้องมีความยาวอย่างน้อย 8 ตัวอักษร');
                    return;
                }

                hideLoading = showLoading('กำลังบันทึกข้อมูล...');

                const trackingResponse = await fetch(API_BASE_URL + '?action=set-tracking&order_id=' + currentOrderId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        tracking_number: trackingNumber,
                        changed_by: '<?php echo $currentUser["username"] ?? "admin_id"; ?>',
                        notes: notes || 'เพิ่มหมายเลขติดตาม'
                    })
                });

                const trackingData = await trackingResponse.json();

                if (!trackingData.success) {
                    throw new Error(trackingData.message || 'ไม่สามารถอัปเดตหมายเลขติดตามได้');
                }

                const statusResponse = await fetch(API_BASE_URL + '?action=update-order-status&order_id=' + currentOrderId, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        order_status: 3,
                        changed_by: '<?php echo $currentUser["username"] ?? "admin_id"; ?>',
                        notes: 'เพิ่มหมายเลขติดตาม: ' + trackingNumber + (notes ? ' - ' + notes : '')
                    })
                });

                const statusData = await statusResponse.json();

                if (!statusData.success) {
                    console.warn('Warning: Could not update order status:', statusData.message);
                }

                closeModal('updateModal');
                showSuccess('เพิ่มหมายเลขติดตามเรียบร้อยแล้ว');
                loadOrders(currentPage);

            } catch (error) {
                console.error('Error saving update:', error);
                showError('เกิดข้อผิดพลาด: ' + error.message);
            } finally {
                if (hideLoading) hideLoading();
            }
        }

        // Track shipping
        async function trackShipping(orderId) {
            let hideLoading = null;
            
            try {
                hideLoading = showLoading('กำลังโหลดข้อมูลติดตาม...');
                
                const orderResponse = await fetch(API_BASE_URL + '?action=get&order_id=' + orderId);
                const orderData = await orderResponse.json();

                if (!orderData.success) {
                    showError('ไม่สามารถโหลดข้อมูลออเดอร์ได้');
                    return;
                }

                const order = orderData.data;
                const historyResponse = await fetch(API_BASE_URL + '?action=status-history&order_id=' + orderId);
                const historyData = await historyResponse.json();

                document.getElementById('trackOrderId').textContent = order.order_number || '#' + orderId;
                document.getElementById('trackNumber').textContent = order.tracking_number || 'ยังไม่มีหมายเลขติดตาม';

                const statusElement = document.getElementById('currentStatus');
                const currentStatus = statusMap[order.order_status] || {
                    text: 'ไม่ทราบสถานะ',
                    class: 'status-pending'
                };
                statusElement.textContent = currentStatus.text;
                statusElement.className = 'status-badge ' + currentStatus.class;

                const stepsContainer = document.getElementById('trackingSteps');
                stepsContainer.innerHTML = '';

                if (historyData.success && historyData.data && historyData.data.length > 0) {
                    historyData.data.forEach(step => {
                        const li = document.createElement('li');
                        const stepStatus = statusMap[step.new_status] || {
                            text: 'ไม่ทราบสถานะ'
                        };
                        const stepDate = new Date(step.create_at).toLocaleString('th-TH');

                        li.innerHTML = '<strong>' + stepStatus.text + '</strong><br>' +
                            '<small>เวลา: ' + stepDate + '</small>' +
                            (step.notes ? '<br><small>หมายเหตุ: ' + step.notes + '</small>' : '');
                        stepsContainer.appendChild(li);
                    });
                } else {
                    const li = document.createElement('li');
                    li.textContent = 'ยังไม่มีประวัติการเปลี่ยนแปลงสถานะ';
                    li.classList.add('pending');
                    stepsContainer.appendChild(li);
                }

                document.getElementById('trackModal').style.display = 'block';

            } catch (error) {
                console.error('Error tracking order:', error);
                showError('เกิดข้อผิดพลาดในการติดตามออเดอร์');
            } finally {
                if (hideLoading) hideLoading();
            }
        }

        // Pagination functions
        function changePage(direction) {
            const newPage = currentPage + direction;
            if (newPage >= 1 && newPage <= totalPages) {
                currentPage = newPage;
                loadOrders(currentPage);
            }
        }

        function updatePagination(pagination) {
            currentPage = pagination.current || 1;
            totalPages = pagination.total || 1;

            const paginationDiv = document.getElementById('pagination');
            const prevButton = document.getElementById('prevPage');
            const nextButton = document.getElementById('nextPage');
            const pageInfo = document.getElementById('pageInfo');

            if (totalPages > 1) {
                paginationDiv.style.display = 'flex';
                prevButton.disabled = currentPage <= 1;
                nextButton.disabled = currentPage >= totalPages;
                pageInfo.textContent = 'หน้า ' + currentPage + ' จาก ' + totalPages;
            } else {
                paginationDiv.style.display = 'none';
            }
        }

        // Hide error message (if exists on page)
        function hideError() {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                errorMessage.style.display = 'none';
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // Close modal when clicking outside of it
        window.onclick = function(event) {
            const updateModal = document.getElementById('updateModal');
            const trackModal = document.getElementById('trackModal');
            if (event.target === updateModal) {
                updateModal.style.display = 'none';
            }
            if (event.target === trackModal) {
                trackModal.style.display = 'none';
            }
        }

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            loadOrders();
        });
    </script>
</body>

</html>