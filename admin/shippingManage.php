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
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
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

        .shipping-table {
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
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
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
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">จัดการการส่งสินค้า</h1>
        </div>

        <div class="shipping-table">
            <table>
                <thead>
                    <tr>
                        <th>รหัสคำสั่งซื้อ</th>
                        <th>ชื่อสินค้า</th>
                        <th>ราคา</th>
                        <th>ข้อมูลลูกค้า</th>
                        <th>ที่อยู่</th>
                        <th>วันที่จัดส่ง</th>
                        <th>สถานะ</th>
                        <!--
                        <th>การจัดการ</th>
                        -->
                    </tr>
                </thead>
                <tbody id="shippingTableBody">
                    <tr>
                        <td class="order-id">#001245</td>
                        <td>รองเท้าผ้าใบสีดำ...</td>
                        <td>฿1,200</td>
                        <td>สมยศ นิติรัตน์</td>
                        <td class="address-cell" title="ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...">ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...</td>
                        <td>19/07/2025</td>
                        <td><span class="status-badge status-completed">จัดส่งแล้ว</span></td>
                        <!--
                        <td>
                            <button class="btn btn-update" onclick="updateShipping('#001245')">อัปเดต</button>
                            <button class="btn btn-track" onclick="trackShipping('#001245')">ติดตาม</button>
                        </td>
                        -->
                    </tr>
                    <tr>
                        <td class="order-id">#001246</td>
                        <td>รองเท้าผ้าใบสีดำ...</td>
                        <td>฿1,200</td>
                        <td>สมยศ นิติรัตน์</td>
                        <td class="address-cell" title="ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...">ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...</td>
                        <td>19/07/2025</td>
                        <td><span class="status-badge status-processing">กำลังจัดส่ง</span></td>
                        <td>
                            <button class="btn btn-update" onclick="updateShipping('#001246')">อัปเดต</button>
                            <button class="btn btn-track" onclick="trackShipping('#001246')">ติดตาม</button>
                        </td>
                    </tr>
                    <tr>
                        <td class="order-id">#001247</td>
                        <td>รองเท้าผ้าใบสีดำ...</td>
                        <td>฿1,200</td>
                        <td>สมยศ นิติรัตน์</td>
                        <td class="address-cell" title="ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...">ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...</td>
                        <td>19/07/2025</td>
                        <td><span class="status-badge status-pending">กำลังเตรียม</span></td>
                        <td>
                            <button class="btn btn-update" onclick="updateShipping('#001247')">อัปเดต</button>
                            <button class="btn btn-track" onclick="trackShipping('#001247')">ติดตาม</button>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    <label for="shippingStatus">สถานะการส่งสินค้า:</label>
                    <select id="shippingStatus" class="form-control">
                        <option value="กำลังเตรียม">กำลังเตรียม</option>
                        <option value="กำลังจัดส่ง">กำลังจัดส่ง</option>
                        <option value="จัดส่งแล้ว">จัดส่งแล้ว</option>
                        <option value="ส่งคืน">ส่งคืน</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trackingNumber">หมายเลขติดตาม:</label>
                    <input type="text" id="trackingNumber" class="form-control" placeholder="TH1234567890">
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
                    <p><strong>รหัสคำสั่งซื้อ:</strong> <span id="trackOrderId">#001245</span></p>
                    <p><strong>หมายเลขติดตาม:</strong> <span id="trackNumber">TH1234567890</span></p>
                    <p><strong>สถานะปัจจุบัน:</strong> <span id="currentStatus" class="status-badge status-completed">จัดส่งแล้ว</span></p>
                </div>
                <h4 style="margin-top: 20px; margin-bottom: 15px;">ประวัติการส่งสินค้า:</h4>
                <ul class="tracking-steps" id="trackingSteps">
                    <li>✓ รับคำสั่งซื้อ - 19/07/2025 09:00</li>
                    <li>✓ เตรียมสินค้า - 19/07/2025 14:30</li>
                    <li>✓ ออกจากคลังสินค้า - 20/07/2025 08:00</li>
                    <li>✓ ส่งมอบเรียบร้อย - 21/07/2025 16:45</li>
                </ul>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('trackModal')">ปิด</button>
            </div>
        </div>
    </div>

    <script>
        let shippingData = [
            {
                orderId: '#001245',
                productName: 'รองเท้าผ้าใบสีดำ...',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                address: 'ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...',
                orderDate: '19/07/2025',
                status: 'จัดส่งแล้ว',
                statusClass: 'status-completed',
                trackingNumber: 'TH1234567890',
                trackingSteps: [
                    '✓ รับคำสั่งซื้อ - 19/07/2025 09:00',
                    '✓ เตรียมสินค้า - 19/07/2025 14:30',
                    '✓ ออกจากคลังสินค้า - 20/07/2025 08:00',
                    '✓ ส่งมอบเรียบร้อย - 21/07/2025 16:45'
                ]
            },
            {
                orderId: '#001246',
                productName: 'รองเท้าผ้าใบสีดำ...',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                address: 'ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...',
                orderDate: '19/07/2025',
                status: 'กำลังจัดส่ง',
                statusClass: 'status-processing',
                trackingNumber: 'TH9876543210',
                trackingSteps: [
                    '✓ รับคำสั่งซื้อ - 19/07/2025 10:00',
                    '✓ เตรียมสินค้า - 19/07/2025 15:30',
                    '✓ ออกจากคลังสินค้า - 20/07/2025 09:00',
                    'กำลังขนส่ง - อัปเดตล่าสุด 21/07/2025 10:00'
                ]
            },
            {
                orderId: '#001247',
                productName: 'รองเท้าผ้าใบสีดำ...',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                address: 'ปากเสาอำเภอ 123 ...ซอยดิง เขตร์ xxxx...',
                orderDate: '19/07/2025',
                status: 'กำลังเตรียม',
                statusClass: 'status-pending',
                trackingNumber: 'รอหมายเลขติดตาม',
                trackingSteps: [
                    '✓ รับคำสั่งซื้อ - 19/07/2025 11:00',
                    'กำลังเตรียมสินค้า - อัปเดตล่าสุด 19/07/2025 16:00'
                ]
            }
        ];

        let currentOrderId = null;

        function updateShipping(orderId) {
            const order = shippingData.find(o => o.orderId === orderId);
            if (order) {
                currentOrderId = orderId;
                document.getElementById('orderId').value = orderId;
                document.getElementById('shippingStatus').value = order.status;
                document.getElementById('trackingNumber').value = order.trackingNumber;
                document.getElementById('updateModal').style.display = 'block';
            }
        }

        function trackShipping(orderId) {
            const order = shippingData.find(o => o.orderId === orderId);
            if (order) {
                document.getElementById('trackOrderId').textContent = orderId;
                document.getElementById('trackNumber').textContent = order.trackingNumber;
                
                const statusElement = document.getElementById('currentStatus');
                statusElement.textContent = order.status;
                statusElement.className = `status-badge ${order.statusClass}`;
                
                const stepsContainer = document.getElementById('trackingSteps');
                stepsContainer.innerHTML = '';
                order.trackingSteps.forEach(step => {
                    const li = document.createElement('li');
                    li.textContent = step;
                    if (step.includes('กำลัง') && !step.includes('✓')) {
                        li.classList.add('pending');
                    }
                    stepsContainer.appendChild(li);
                });
                
                document.getElementById('trackModal').style.display = 'block';
            }
        }

        function saveUpdate() {
            const orderId = document.getElementById('orderId').value;
            const newStatus = document.getElementById('shippingStatus').value;
            const trackingNumber = document.getElementById('trackingNumber').value;
            
            const orderIndex = shippingData.findIndex(o => o.orderId === orderId);
            if (orderIndex !== -1) {
                shippingData[orderIndex].status = newStatus;
                shippingData[orderIndex].trackingNumber = trackingNumber;
                
                // Update status class
                switch(newStatus) {
                    case 'กำลังเตรียม':
                        shippingData[orderIndex].statusClass = 'status-pending';
                        break;
                    case 'กำลังจัดส่ง':
                        shippingData[orderIndex].statusClass = 'status-processing';
                        break;
                    case 'จัดส่งแล้ว':
                        shippingData[orderIndex].statusClass = 'status-completed';
                        break;
                    default:
                        shippingData[orderIndex].statusClass = 'status-pending';
                }
                
                renderShippingTable();
                closeModal('updateModal');
                alert('อัปเดตสถานะการส่งสินค้าเรียบร้อยแล้ว');
            }
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function renderShippingTable() {
            const tbody = document.getElementById('shippingTableBody');
            tbody.innerHTML = '';

            shippingData.forEach(order => {
                const row = `
                    <tr>
                        <td class="order-id">${order.orderId}</td>
                        <td>${order.productName}</td>
                        <td>${order.price}</td>
                        <td>${order.customer}</td>
                        <td class="address-cell" title="${order.address}">${order.address}</td>
                        <td>${order.orderDate}</td>
                        <td><span class="status-badge ${order.statusClass}">${order.status}</span></td>
                        <!--
                        <td>
                            <button class="btn btn-update" onclick="updateShipping('${order.orderId}')">อัปเดต</button>
                            <button class="btn btn-track" onclick="trackShipping('${order.orderId}')">ติดตาม</button>
                        </td>
                        -->
                    </tr>
                `;
                tbody.innerHTML += row;
            });
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
        renderShippingTable();
    </script>
</body>
</html>