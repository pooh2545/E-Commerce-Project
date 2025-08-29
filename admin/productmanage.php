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
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .product-id {
            color: #666;
            font-size: 12px;
        }

        .admin-user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            backdrop-filter: blur(10px);
            margin-bottom: 20px;
        }

        #adminWelcome {
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .admin-logout-btn {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }

        .admin-logout-btn:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        }

        .admin-logout-btn:active {
            transform: translateY(0);
        }

        .loading {
            text-align: center;
            padding: 20px;
            color: #666;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
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
                            <td colspan="9" class="loading">กำลังโหลดข้อมูล...</td>
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

            <div id="errorAlert" class="alert alert-danger hidden">
                เกิดข้อผิดพลาดในการบันทึกข้อมูล
            </div>

            <div class="form-container">
                <form id="productFormData" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="productName">ชื่อสินค้า *</label>
                        <input type="text" id="productName" name="name" class="form-control" placeholder="ชื่อสินค้า" required>
                    </div>

                    <div class="form-group">
                        <label for="productImage">รูปภาพ</label>
                        <input type="file" id="productImage" name="image" class="form-control file-input" accept="image/*">
                        <div style="text-align: center; margin-top: 10px;">
                            <small style="color: #666;">เลือกไฟล์รูปภาพ (jpg, png, gif)</small>
                        </div>
                        <div id="currentImage" class="hidden" style="margin-top: 10px;">
                            <small style="color: #666;">รูปปัจจุบัน:</small>
                            <div style="margin-top: 5px;">
                                <img id="currentImagePreview" style="max-width: 200px; height: auto; border-radius: 4px;">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="productCategory">หมวดหมู่ *</label>
                        <select id="productCategory" name="shoetype_id" class="form-control" required>
                            <option value="">กำลังโหลด...</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="productPrice">ราคา *</label>
                        <input type="number" id="productPrice" name="price" class="form-control" placeholder="เช่น 199" step="0.01" min="0" required>
                    </div>


                    <div class="form-group">
                        <label for="productSize">ขนาด *</label>
                        <input type="text" id="productSize" name="size" class="form-control" placeholder="เช่น 36-40 หรือ 39" required>
                    </div>

                    <div class="form-group">
                        <label for="productStock">จำนวนคงเหลือ *</label>
                        <input type="number" id="productStock" name="stock" class="form-control" placeholder="เช่น 5" min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="productDetail">รายละเอียดสินค้า</label>
                        <textarea id="productDetail" name="detail" class="form-control" placeholder="รายละเอียดสินค้า..."></textarea>
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
        let products = [];
        let categories = [];
        let editingProduct = null;

        // แสดงหน้ารายการสินค้า
        function showProductList() {
            document.getElementById('productList').classList.remove('hidden');
            document.getElementById('productForm').classList.add('hidden');
            document.getElementById('successAlert').classList.add('hidden');
            document.getElementById('errorAlert').classList.add('hidden');
            loadProducts();
        }

        // แสดงฟอร์มเพิ่มสินค้า
        function showAddForm() {
            document.getElementById('productList').classList.add('hidden');
            document.getElementById('productForm').classList.remove('hidden');
            document.getElementById('formTitle').textContent = 'เพิ่มสินค้าใหม่';
            document.getElementById('currentImage').classList.add('hidden');
            clearForm();
            editingProduct = null;
            loadCategories();
        }

        // โหลดข้อมูลสินค้าจาก API
        function loadProducts() {
            fetch('../controller/product_api.php?action=all')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        products = data;
                        renderProductTable();
                    } else {
                        throw new Error('Invalid data format');
                    }
                })
                .catch(error => {
                    console.error('Error loading products:', error);
                    document.getElementById('productTableBody').innerHTML =
                        '<tr><td colspan="8" style="text-align: center; color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
                });
        }

        // โหลดหมวดหมู่จาก API
        function loadCategories() {
            return fetch('../controller/product_api.php?action=categories')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (Array.isArray(data)) {
                        categories = data;
                        renderCategoryOptions();
                        return data;
                    } else {
                        throw new Error('Invalid category data format');
                    }
                })
                .catch(error => {
                    console.error('Error loading categories:', error);
                    document.getElementById('productCategory').innerHTML = '<option value="">เกิดข้อผิดพลาดในการโหลดหมวดหมู่</option>';
                });
        }

        // แสดงตัวเลือกหมวดหมู่ใน dropdown
        function renderCategoryOptions() {
            const select = document.getElementById('productCategory');
            select.innerHTML = '<option value="">เลือกหมวดหมู่</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.shoetype_id;
                option.textContent = category.name;
                select.appendChild(option);
            });
        }

        // แก้ไขสินค้า
        function editProduct(productId) {
            fetch(`../controller/product_api.php?action=get&id=${productId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(product => {
                    if (product && !product.error) {
                        document.getElementById('productList').classList.add('hidden');
                        document.getElementById('productForm').classList.remove('hidden');
                        document.getElementById('formTitle').textContent = 'แก้ไขสินค้า';
                        document.getElementById('successAlert').classList.add('hidden');
                        document.getElementById('errorAlert').classList.add('hidden');

                        // เติมข้อมูลในฟอร์ม
                        document.getElementById('productName').value = product.name || '';
                        document.getElementById('productDetail').value = product.detail || '';
                        document.getElementById('productPrice').value = product.price || '';
                        document.getElementById('productStock').value = product.stock || '';
                        document.getElementById('productSize').value = product.size || '';

                        // แสดงรูปปัจจุบัน
                        if (product.img_path !== null) {
                            document.getElementById('currentImage').classList.remove('hidden');
                            document.getElementById('currentImagePreview').src = '../controller/uploads/products/' + product.img_path;
                        } else {
                            document.getElementById('currentImage').classList.add('hidden');
                        }

                        loadCategories().then(() => {
                            document.getElementById('productCategory').value = product.shoetype_id || '';
                        });

                        editingProduct = productId;
                    } else {
                        alert('ไม่พบข้อมูลสินค้า');
                    }
                })
                .catch(error => {
                    console.error('Error loading product:', error);
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า');
                });
        }

        // ลบสินค้า
        function deleteProduct(productId) {
            if (confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?')) {
                fetch(`../controller/product_api.php?action=delete&id=${productId}`, {
                        method: 'DELETE'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            loadProducts();
                            alert(data.message || 'ลบสินค้าเรียบร้อยแล้ว');
                        } else {
                            alert(data.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting product:', error);
                        alert('เกิดข้อผิดพลาดในการลบสินค้า');
                    });
            }
        }

        // ล้างฟอร์ม
        function clearForm() {
            document.getElementById('productFormData').reset();
            document.getElementById('currentImage').classList.add('hidden');
        }

        // บันทึกสินค้า
        function saveProduct() {
            const form = document.getElementById('productFormData');
            const formData = new FormData(form);

            // ตรวจสอบข้อมูลที่จำเป็น
            const requiredFields = {
                'name': 'ชื่อสินค้า',
                'price': 'ราคา',
                'stock': 'จำนวนคงเหลือ',
                'shoetype_id': 'หมวดหมู่',
                'size': 'ขนาด'
            };

            for (let [field, label] of Object.entries(requiredFields)) {
                if (!formData.get(field) || formData.get(field).trim() === '') {
                    alert(`กรุณากรอก${label}`);
                    return;
                }
            }

            // Hide alerts
            document.getElementById('successAlert').classList.add('hidden');
            document.getElementById('errorAlert').classList.add('hidden');

            let url, method;
            if (editingProduct) {
                // อัปเดตสินค้า - ใช้ POST method พร้อม action=update
                url = `../controller/product_api.php?action=update&id=${editingProduct}`;
                method = 'POST';
            } else {
                // เพิ่มสินค้าใหม่
                url = '../controller/product_api.php?action=create';
                method = 'POST';
            }

            fetch(url, {
                    method: method,
                    body: formData // ใช้ FormData โดยตรงเพื่อส่งทั้งข้อมูลและไฟล์
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Response data:', data); // เพิ่มเพื่อ debug

                    if (data.success) {
                        document.getElementById('successAlert').classList.remove('hidden');
                        document.getElementById('successAlert').textContent = data.message ||
                            (editingProduct ? 'อัปเดตข้อมูลเรียบร้อยแล้ว' : 'บันทึกข้อมูลเรียบร้อยแล้ว');
                        setTimeout(() => {
                            showProductList();
                        }, 1500);
                    } else {
                        document.getElementById('errorAlert').classList.remove('hidden');
                        document.getElementById('errorAlert').textContent = data.message ||
                            (editingProduct ? 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล' : 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    }
                })
                .catch(error => {
                    console.error('Error saving product:', error);
                    document.getElementById('errorAlert').classList.remove('hidden');
                    document.getElementById('errorAlert').textContent = editingProduct ?
                        'เกิดข้อผิดพลาดในการอัปเดตข้อมูล' : 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                });
        }
        // แสดงตารางสินค้า
        function renderProductTable() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';

            if (products.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; color: #666;">ไม่มีข้อมูลสินค้า</td></tr>';
                return;
            }

            products.forEach(product => {
                const imageHtml = product.img_path ?
                    `<img src="../controller/uploads/products/${product.img_path}" alt="${product.name}" loading="lazy">` :
                    '<div style="color: #999; font-size: 12px;">ไม่มีรูป</div>';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.shoe_id}</td>
                    <td>${product.name}</td>
                    <td>
                        <div class="product-image">
                            ${imageHtml}
                        </div>
                    </td>
                    <td>${product.category_name || 'ไม่ระบุ'}</td>
                    <td>฿${parseFloat(product.price).toLocaleString()}</td>
                    <td>${product.size}</td>
                    <td>${product.stock}</td>
                    <td>
                        <button class="btn btn-info" onclick="editProduct('${product.shoe_id}')">แก้ไข</button>
                        <button class="btn btn-danger" onclick="deleteProduct('${product.shoe_id}')">ลบ</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }



        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
        });

        // ตรวจสอบ session ทุก 5 นาที
        setInterval(function() {
            fetch('../controller/admin_api.php?action=check_session')
                .then(response => response.json())
                .then(data => {
                    if (!data.logged_in) {
                        alert('Session หมดอายุ กรุณาเข้าสู่ระบบใหม่');
                        window.location.href = 'index.php';
                    }
                })
                .catch(err => console.error('Session check error:', err));
        }, 5 * 60 * 1000); // 5 minutes
    </script>
</body>

</html>