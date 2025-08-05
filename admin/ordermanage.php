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
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            transition: all 0.3s;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-info:hover {
            background-color: #0056b3;
        }

        .order-detail-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
    <div class="container">
        <!-- หน้ารายการคำสั่งซื้อ -->
        <div id="orderList">
            <div class="page-header">
                <h1 class="page-title">หน้าจัดการคำสั่งซื้อ  :   รายการคำสั่งซื้อ</h1>
                <p class="page-subtitle"></p>
            </div>

            <div class="order-table">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสคำสั่งซื้อ</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>ข้อมูลผู้ซื้อ</th>
                            <th>วันที่สั่ง</th>
                            <th>สถานะ</th>
                            <th>ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody id="orderTableBody">
                        <tr>
                            <td>#001245</td>
                            <td>รองเท้าผ้าใบสีดำ...</td>
                            <td>฿1,200</td>
                            <td>สมยศ นิติรัตน์</td>
                            <td>19/07/2025</td>
                            <td><span class="status-badge status-completed">จัดส่งแล้ว</span></td>
                            <td><button class="btn btn-info" onclick="viewOrderDetail('#001245')">ดูรายละเอียด</button></td>
                        </tr>
                        <tr>
                            <td>#001246</td>
                            <td>รองเท้าผ้าใบสีดำ...</td>
                            <td>฿1,200</td>
                            <td>สมยศ นิติรัตน์</td>
                            <td>19/07/2025</td>
                            <td><span class="status-badge status-processing">กำลังดำเนินการ</span></td>
                            <td><button class="btn btn-info" onclick="viewOrderDetail('#001246')">ดูรายละเอียด</button></td>
                        </tr>
                        <tr>
                            <td>#001247</td>
                            <td>รองเท้าผ้าใบสีดำ...</td>
                            <td>฿1,200</td>
                            <td>สมยศ นิติรัตน์</td>
                            <td>19/07/2025</td>
                            <td><span class="status-badge status-pending">รอการชำระ</span></td>
                            <td><button class="btn btn-info" onclick="viewOrderDetail('#001247')">ดูรายละเอียด</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้ารายละเอียดคำสั่งซื้อ -->
        <div id="orderDetail" class="hidden">
            <!--<a href="#" class="back-link" onclick="showOrderList()">← กลับไปยังรายการคำสั่งซื้อ</a>-->
            <div class="page-header">
                <h1 class="page-title">หน้าจัดการคำสั่งซื้อ  :  รายละเอียดคำสั่งซื้อ</h1>
                <p class="page-subtitle"></p>
            </div>

            <div class="order-detail-card">
                <h2 class="order-detail-title">รายละเอียดคำสั่งซื้อ</h2>
                
                <div class="order-info" id="orderDetailInfo">
                    <div><strong>หมายเลขคำสั่งซื้อ:</strong> <span id="detailOrderId">#001245</span></div>
                    <div><strong>ชื่อสินค้า:</strong> <span id="detailProductName">รองเท้าผ้าใบสีดำ</span></div>
                    <div><strong>ที่อยู่จัดส่ง:</strong> <span id="detailAddress">39/1 ถนนสยามสแควร์ พระรามเท่า กรุง</span></div>
                    <div><strong>วันที่สั่งซื้อ:</strong> <span id="detailOrderDate">17 ม.ค. 2025</span></div>
                    <div><strong>สถานะการสั่งซื้อ:</strong> <span id="detailStatus" class="status-badge status-completed">กำลังจัดส่ง</span></div>
                    <div><strong>หมายเลขติดตาม:</strong> <span id="detailTrackingNumber">TH1234567899</span></div>
                </div>

                <div class="order-actions">
                    <button class="btn btn-info" onclick="showOrderList()">กลับไปหน้ารายการคำสั่งซื้อ</button>
                <!--<button class="btn btn-info" onclick="updateOrderStatus()">ปรับปรุงสถานะคำสั่งซื้อ</button> -->    
                </div>
            </div>
        </div>
    </div>

    <script>
        const orders = [
            {
                id: '#001245',
                productName: 'รองเท้าผ้าใบสีดำ',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                orderDate: '19/07/2025',
                status: 'จัดส่งสำเร็จ',
                statusClass: 'status-completed',
                address: '39/1 ถนนสยามสแควร์ พระรามเท่า กรุง',
                trackingNumber: 'TH1234567899',
                detailOrderDate: '17 ม.ค. 2025'
            },
            {
                id: '#001246',
                productName: 'รองเท้าผ้าใบสีดำ',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                orderDate: '19/07/2025',
                status: 'กำลังจัดส่ง',
                statusClass: 'status-processing',
                address: '120/5 ถนนรัชดาภิเษก ห้วยขวาง กรุงเทพ',
                trackingNumber: 'TH9876543210',
                detailOrderDate: '18 ม.ค. 2025'
            },
            {
                id: '#001247',
                productName: 'รองเท้าผ้าใบสีดำ',
                price: '฿1,200',
                customer: 'สมยศ นิติรัตน์',
                orderDate: '19/07/2025',
                status: 'ชำระเงินแล้ว',
                statusClass: 'status-pending',
                address: '456/78 ถนนสุขุมวิท วัฒนา กรุงเทพ',
                trackingNumber: 'รอหมายเลขติดตาม',
                detailOrderDate: '19 ม.ค. 2025'
            }
        ];

        function showOrderList() {
            document.getElementById('orderList').classList.remove('hidden');
            document.getElementById('orderDetail').classList.add('hidden');
        }

        function viewOrderDetail(orderId) {
            const order = orders.find(o => o.id === orderId);
            if (order) {
                document.getElementById('orderList').classList.add('hidden');
                document.getElementById('orderDetail').classList.remove('hidden');
                
                // Fill order detail information
                document.getElementById('detailOrderId').textContent = order.id;
                document.getElementById('detailProductName').textContent = order.productName;
                document.getElementById('detailAddress').textContent = order.address;
                document.getElementById('detailOrderDate').textContent = order.detailOrderDate;
                document.getElementById('detailTrackingNumber').textContent = order.trackingNumber;
                
                const statusElement = document.getElementById('detailStatus');
                statusElement.textContent = order.status;
                statusElement.className = `status-badge ${order.statusClass}`;
            }
        }

        function updateOrderStatus() {
            const statusOptions = [
                { text: 'รอการชำระ', class: 'status-pending' },
                { text: 'ชำระเงินแล้ว', class: 'status-pending' },
                { text: 'กำลังดำเนินการ', class: 'status-processing' },
                { text: 'กำลังจัดส่ง', class: 'status-completed' },
                { text: 'จัดส่งสำเร็จ', class: 'status-completed' },
                { text: 'ยกเลิก', class: 'status-cancelled' }
            ];

            const currentStatus = document.getElementById('detailStatus').textContent;
            const currentIndex = statusOptions.findIndex(s => s.text === currentStatus);
            const nextIndex = (currentIndex + 1) % statusOptions.length;
            const nextStatus = statusOptions[nextIndex];

            const statusElement = document.getElementById('detailStatus');
            statusElement.textContent = nextStatus.text;
            statusElement.className = `status-badge ${nextStatus.class}`;

            // Update tracking number if status changes to shipping
            if (nextStatus.text === 'กำลังจัดส่ง' || nextStatus.text === 'จัดส่งแล้ว') {
                document.getElementById('detailTrackingNumber').textContent = 'TH' + Math.floor(Math.random() * 9000000000 + 1000000000);
            }

            alert('อัปเดตสถานะคำสั่งซื้อเรียบร้อยแล้ว');
        }

        function renderOrderTable() {
            const tbody = document.getElementById('orderTableBody');
            tbody.innerHTML = '';

            orders.forEach(order => {
                const row = `
                    <tr>
                        <td>${order.id}</td>
                        <td>${order.productName}...</td>
                        <td>${order.price}</td>
                        <td>${order.customer}</td>
                        <td>${order.orderDate}</td>
                        <td><span class="status-badge ${order.statusClass}">${order.status}</span></td>
                        <td><button class="btn btn-info" onclick="viewOrderDetail('${order.id}')">ดูรายละเอียด</button></td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Initialize the page
        renderOrderTable();
    </script>
</body>
</html>