<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า</title>
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
            background: none;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .page-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #7B3F98;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6A2C87;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
        }

        .product-table {
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
            background-color: #7B3F98;
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

        .product-image {
            width: 60px;
            height: 60px;
            background-color: #ddd;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 12px;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
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
            border-color: #7B3F98;
            box-shadow: 0 0 0 2px rgba(123, 63, 152, 0.2);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .file-input {
            padding: 8px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .hidden {
            display: none;
        }

        .back-link {
            color: #7B3F98;
            text-decoration: none;
            margin-bottom: 20px;
            display: inline-block;
        }

        .back-link:hover {
            text-decoration: underline;
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

        .product-id {
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- หน้ารายการสินค้า -->
        <div id="productList">
            <div class="page-header">
                <h1 class="page-title">รายการสินค้า</h1>
                <button class="btn btn-primary" onclick="showAddForm()" style="margin-top: 20px;">+ เพิ่มสินค้า</button>
            </div>

            <div class="product-table">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>รูปภาพ</th>
                            <th>หมวดหมู่</th>
                            <th>ราคา</th>
                            <th>ขนาด</th>
                            <th>จำนวน</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <tr>
                            <td>
                                P001
                            </td>
                            <td>รองเท้าผ้าใบสีดำ</td>
                            <td>
                                <div class="product-image">รูป</div>
                            </td>
                            <td>ชิ้นเสื้อ</td>
                            <td>8199</td>
                            <td>36 - 40</td>
                            <td>5</td>
                            <td>
                                <button class="btn btn-info" onclick="editProduct('P001')">แก้ไข</button>
                                <button class="btn btn-danger" onclick="deleteProduct('P001')">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                J002
                            </td>
                            <td>รองเท้าผู้หญิงสีขาว</td>
                            <td>
                                <div class="product-image">รูป</div>
                            </td>
                            <td>อุปกรณ์</td>
                            <td>8259</td>
                            <td>41 - 45</td>
                            <td>8</td>
                            <td>
                                <button class="btn btn-info" onclick="editProduct('J002')">แก้ไข</button>
                                <button class="btn btn-danger" onclick="deleteProduct('J002')">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้าเพิ่ม/แก้ไขสินค้า -->
        <div id="productForm" class="hidden">
            <a href="#" class="back-link" onclick="showProductList()">← กลับไปยังรายการสินค้า</a>
            
            <div class="page-header">
                <h1 class="page-title" id="formTitle">เพิ่มสินค้าใหม่</h1>
            </div>

            <div id="successAlert" class="alert alert-success hidden">
                บันทึกข้อมูลสินค้าเรียบร้อยแล้ว
            </div>

            <div class="form-container">
                <form id="productFormData">
                    <div class="form-group">
                        <label for="productCode">รหัสสินค้า</label>
                        <input type="text" id="productCode" class="form-control" placeholder="เช่น P001">
                    </div>

                    <div class="form-group">
                        <label for="productName">ชื่อสินค้า</label>
                        <input type="text" id="productName" class="form-control" placeholder="ชื่อสินค้า">
                    </div>

                    <div class="form-group">
                        <label for="productImage">รูปภาพ</label>
                        <input type="file" id="productImage" class="form-control file-input" accept="image/*">
                        <div style="text-align: center; margin-top: 10px;">
                            <small style="color: #666;">ลองไฟล์รูปภาพ</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="productCategory">หมวดหมู่</label>
                        <select id="productCategory" class="form-control">
                            <option value="">เลือกหมวดหมู่</option>
                            <option value="ชิ้นเสื้อ">ชิ้นเสื้อ</option>
                            <option value="อุปกรณ์">อุปกรณ์</option>
                            <option value="รองเท้า">รองเท้า</option>
                            <option value="กระเป๋า">กระเป๋า</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="productPrice">ราคา</label>
                        <input type="number" id="productPrice" class="form-control" placeholder="เช่น 199">
                    </div>

                    <div class="form-group">
                        <label for="productSize">ขนาด</label>
                        <input type="text" id="productSize" class="form-control" placeholder="เช่น 39">
                    </div>

                    <div class="form-group">
                        <label for="productStock">จำนวนของเหลือ</label>
                        <input type="number" id="productStock" class="form-control" placeholder="เช่น 5">
                    </div>

                    <div class="form-group">
                        <label for="productDescription">รายละเอียดสินค้า</label>
                        <textarea id="productDescription" class="form-control" placeholder="รายละเอียดสินค้า..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success" onclick="saveProduct()">บันทึกข้อมูลสินค้า</button>
                        <button type="button" class="btn btn-primary" onclick="showProductList()">กลับสู่หน้ารายการสินค้า</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let products = [
            {
                code: 'P001',
                name: 'รองเท้าผ้าใบสีดำ',
                category: 'ลำลอง',
                price: 199,
                size: '36 - 40',
                stock: 5,
                description: 'รองเท้าผ้าใบสีดำคุณภาพดี'
            },
            {
                code: 'J002',
                name: 'รองเท้าผู้หญิงสีขาว',
                category: 'ผู้ใหญ่',
                price: 259,
                size: '41 - 45',
                stock: 8,
                description: 'รองเท้าผู้หญิงสีขาวสวยงาม'
            }
        ];

        let editingProduct = null;

        function showProductList() {
            document.getElementById('productList').classList.remove('hidden');
            document.getElementById('productForm').classList.add('hidden');
            document.getElementById('successAlert').classList.add('hidden');
        }

        function showAddForm() {
            document.getElementById('productList').classList.add('hidden');
            document.getElementById('productForm').classList.remove('hidden');
            document.getElementById('formTitle').textContent = 'เพิ่มสินค้าใหม่';
            clearForm();
            editingProduct = null;
        }

        function editProduct(productCode) {
            const product = products.find(p => p.code === productCode);
            if (product) {
                document.getElementById('productList').classList.add('hidden');
                document.getElementById('productForm').classList.remove('hidden');
                document.getElementById('formTitle').textContent = 'แก้ไขสินค้า';
                
                // Fill form with product data
                document.getElementById('productCode').value = product.code;
                document.getElementById('productName').value = product.name;
                document.getElementById('productCategory').value = product.category;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productSize').value = product.size;
                document.getElementById('productStock').value = product.stock;
                document.getElementById('productDescription').value = product.description || '';
                
                editingProduct = productCode;
            }
        }

        function deleteProduct(productCode) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?')) {
                products = products.filter(p => p.code !== productCode);
                renderProductTable();
            }
        }

        function clearForm() {
            document.getElementById('productFormData').reset();
        }

        function saveProduct() {
            const formData = {
                code: document.getElementById('productCode').value,
                name: document.getElementById('productName').value,
                category: document.getElementById('productCategory').value,
                price: parseInt(document.getElementById('productPrice').value),
                size: document.getElementById('productSize').value,
                stock: parseInt(document.getElementById('productStock').value),
                description: document.getElementById('productDescription').value
            };

            // Validate required fields
            if (!formData.code || !formData.name || !formData.category || !formData.price) {
                alert('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
                return;
            }

            if (editingProduct) {
                // Update existing product
                const index = products.findIndex(p => p.code === editingProduct);
                if (index !== -1) {
                    products[index] = formData;
                }
            } else {
                // Add new product
                if (products.find(p => p.code === formData.code)) {
                    alert('รหัสสินค้านี้มีอยู่แล้ว กรุณาใช้รหัสอื่น');
                    return;
                }
                products.push(formData);
            }

            renderProductTable();
            document.getElementById('successAlert').classList.remove('hidden');
            
            setTimeout(() => {
                showProductList();
            }, 1500);
        }

        function renderProductTable() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';

            products.forEach(product => {
                const row = `
                    <tr>
                        <td>
                            ${product.code}
                        </td>
                        <td>${product.name}</td>
                        <td>
                            <div class="product-image">รูป</div>
                        </td>
                        <td>${product.category}</td>
                        <td>${product.price}</td>
                        <td>${product.size}</td>
                        <td>${product.stock}</td>
                        <td>
                            <button class="btn btn-info" onclick="editProduct('${product.code}')">แก้ไข</button>
                            <button class="btn btn-danger" onclick="deleteProduct('${product.code}')">ลบ</button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Initialize the page
        renderProductTable();
    </script>
</body>
</html>