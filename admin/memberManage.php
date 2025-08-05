<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการลูกค้า</title>
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

        .btn-info {
            background-color: #007bff;
            color: white;
        }

        .btn-info:hover {
            background-color: #0056b3;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            padding: 12px 30px;
            font-size: 14px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            font-size: 14px;
        }

        .btn-primary:hover {
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
    </style>
</head>
<body>
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
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="customerTableBody">
                        <tr>
                            <td>P001</td>
                            <td>สมหญิง โอชา</td>
                            <td>somchai@example.com</td>
                            <td>0812345678</td>
                            <td>
                                <button class="btn btn-info" onclick="viewCustomerDetail('P001')">รายละเอียด</button>
                                <button class="btn btn-danger" onclick="deleteCustomer('P001')">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P002</td>
                            <td>สมยศ นิติรัตน์</td>
                            <td>somchai2@example.com</td>
                            <td>0812345679</td>
                            <td>
                                <button class="btn btn-info" onclick="viewCustomerDetail('P002')">รายละเอียด</button>
                                <button class="btn btn-danger" onclick="deleteCustomer('P002')">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P001</td>
                            <td>สมหญิง โอชา</td>
                            <td>somchai@example.com</td>
                            <td>0812345678</td>
                            <td>
                                <button class="btn btn-info" onclick="viewCustomerDetail('P001')">รายละเอียด</button>
                                <button class="btn btn-danger" onclick="deleteCustomer('P001')">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P002</td>
                            <td>สมยศ นิติรัตน์</td>
                            <td>somchai2@example.com</td>
                            <td>0812345679</td>
                            <td>
                                <button class="btn btn-info" onclick="viewCustomerDetail('P002')">รายละเอียด</button>
                                <button class="btn btn-danger" onclick="deleteCustomer('P002')">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้ารายละเอียดลูกค้า -->
        <div id="customerDetail" class="hidden">
            <!--<a href="#" class="back-link" onclick="showCustomerList()">← กลับไปยังรายการลูกค้า</a>-->
            
            <div class="page-header">
                <h1 class="page-title">รายละเอียดลูกค้า</h1>
            </div>

            <div id="successAlert" class="alert alert-success hidden">
                บันทึกข้อมูลลูกค้าเรียบร้อยแล้ว
            </div>

            <div class="customer-detail-container">
                <form id="customerForm">
                    <div class="form-group">
                        <label for="customerId">รหัสลูกค้า</label>
                        <input type="text" id="customerId" class="form-control" value="P001" readonly>
                    </div>

                    <div class="form-group">
                        <label for="customerName">ชื่อนามสกุล</label>
                        <input type="text" id="customerName" class="form-control" value="สมหญิง โอชา">
                    </div>

                    <div class="form-group">
                        <label for="customerEmail">อีเมล</label>
                        <input type="email" id="customerEmail" class="form-control email-field" value="somchai@example.com">
                    </div>

                    <div class="form-group">
                        <label for="customerPhone">เบอร์โทร</label>
                        <input type="tel" id="customerPhone" class="form-control" value="0812345678">
                    </div>

                    <div class="form-group">
                        <label for="customerAddress">ที่อยู่</label>
                        <textarea id="customerAddress" class="form-control" placeholder="123/4 หมู้บ้านลูกอิฟ แขวงสวนจิตต์ เขตสวนจิตต์ กทม">123/4 หมู้บ้านลูกอิฟ แขวงสวนจิตต์ เขตสวนจิตต์ กทม</textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success" onclick="saveCustomer()">บันทึกการแก้ไข</button>
                        <button type="button" class="btn btn-primary" onclick="showCustomerList()">กลับสู่หน้ารายการลูกค้า</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let customers = [
            {
                id: 'P001',
                name: 'สมหญิง โอชา',
                email: 'somchai@example.com',
                phone: '0812345678',
                address: '123/4 หมู้บ้านลูกอิฟ แขวงสวนจิตต์ เขตสวนจิตต์ กทม'
            },
            {
                id: 'P002',
                name: 'สมยศ นิติรัตน์',
                email: 'somchai2@example.com',
                phone: '0812345679',
                address: '456/7 ซอยรามคำแหง แขวงหัวหมาก เขตบางกะปิ กทม'
            }
        ];

        let currentCustomerId = null;

        function showCustomerList() {
            document.getElementById('customerList').classList.remove('hidden');
            document.getElementById('customerDetail').classList.add('hidden');
            document.getElementById('successAlert').classList.add('hidden');
        }

        function viewCustomerDetail(customerId) {
            const customer = customers.find(c => c.id === customerId);
            if (customer) {
                document.getElementById('customerList').classList.add('hidden');
                document.getElementById('customerDetail').classList.remove('hidden');
                
                // Fill form with customer data
                document.getElementById('customerId').value = customer.id;
                document.getElementById('customerName').value = customer.name;
                document.getElementById('customerEmail').value = customer.email;
                document.getElementById('customerPhone').value = customer.phone;
                document.getElementById('customerAddress').value = customer.address;
                
                currentCustomerId = customerId;
            }
        }

        function deleteCustomer(customerId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะลบลูกค้ารายนี้?')) {
                customers = customers.filter(c => c.id !== customerId);
                renderCustomerTable();
                alert('ลบข้อมูลลูกค้าเรียบร้อยแล้ว');
            }
        }

        function saveCustomer() {
            const formData = {
                id: document.getElementById('customerId').value,
                name: document.getElementById('customerName').value,
                email: document.getElementById('customerEmail').value,
                phone: document.getElementById('customerPhone').value,
                address: document.getElementById('customerAddress').value
            };

            // Validate required fields
            if (!formData.name || !formData.email || !formData.phone) {
                alert('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
                return;
            }

            // Update customer data
            const customerIndex = customers.findIndex(c => c.id === currentCustomerId);
            if (customerIndex !== -1) {
                customers[customerIndex] = formData;
            }

            renderCustomerTable();
            document.getElementById('successAlert').classList.remove('hidden');
            
            setTimeout(() => {
                document.getElementById('successAlert').classList.add('hidden');
            }, 3000);
        }

        function renderCustomerTable() {
            const tbody = document.getElementById('customerTableBody');
            tbody.innerHTML = '';

            customers.forEach(customer => {
                const row = `
                    <tr>
                        <td>${customer.id}</td>
                        <td>${customer.name}</td>
                        <td>${customer.email}</td>
                        <td>${customer.phone}</td>
                        <td>
                            <button class="btn btn-info" onclick="viewCustomerDetail('${customer.id}')">รายละเอียด</button>
                            <button class="btn btn-danger" onclick="deleteCustomer('${customer.id}')">ลบ</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Initialize the page
        renderCustomerTable();
    </script>
</body>
</html>