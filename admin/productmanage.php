<?php
require_once '../controller/admin_auth_check.php';

$auth = requireLogin();
$currentUser = $auth->getCurrentUser();

// ตรวจสอบ URL parameters
$action = isset($_GET['add']) ? 'add' : (isset($_GET['id']) ? 'edit' : 'list');
$productId = isset($_GET['id']) ? $_GET['id'] : null;
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า</title>
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
        <!-- หน้ารายการสินค้า -->
        <div id="productList" class="<?php echo $action === 'list' ? '' : 'hidden'; ?>">
            <div class="page-header">
                <h1 class="page-title">รายการสินค้า</h1>
                <a href="productmanage.php?add" class="btn btn-primary" style="margin-top: 20px;">+ เพิ่มสินค้า</a>
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
                            <td colspan="8" class="loading">กำลังโหลดข้อมูล...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- หน้าเพิ่ม/แก้ไขสินค้า -->
        <div id="productForm" class="<?php echo ($action === 'add' || $action === 'edit') ? '' : 'hidden'; ?>">
            <a href="productmanage.php" class="back-link">← กลับไปยังรายการสินค้า</a>

            <div class="page-header">
                <h1 class="page-title" id="formTitle">
                    <?php 
                    if ($action === 'add') {
                        echo 'เพิ่มสินค้าใหม่';
                    } elseif ($action === 'edit') {
                        echo 'แก้ไขสินค้า';
                    }
                    ?>
                </h1>
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
                        <input type="text" id="productName" name="name" class="form-control" placeholder="ชื่อสินค้า"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="productImage">รูปภาพ</label>
                        <input type="file" id="productImage" name="image" class="form-control file-input"
                            accept="image/*">
                        <div style="text-align: center; margin-top: 10px;">
                            <small style="color: #666;">เลือกไฟล์รูปภาพ (jpg, png, gif)</small>
                        </div>
                        <div id="currentImage" class="hidden" style="margin-top: 10px;">
                            <small style="color: #666;">รูปปัจจุบัน:</small>
                            <div style="margin-top: 5px;">
                                <img id="currentImagePreview"
                                    style="max-width: 200px; height: auto; border-radius: 4px;">
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
                        <input type="number" id="productPrice" name="price" class="form-control" placeholder="เช่น 199"
                            step="0.01" min="0" required>
                    </div>


                    <div class="form-group">
                        <label for="productSize">ขนาด *</label>
                        <input type="text" id="productSize" name="size" class="form-control"
                            placeholder="เช่น 36-40 หรือ 39" required>
                    </div>

                    <div class="form-group">
                        <label for="productStock">จำนวนคงเหลือ *</label>
                        <input type="number" id="productStock" name="stock" class="form-control" placeholder="เช่น 5"
                            min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="productDetail">รายละเอียดสินค้า</label>
                        <textarea id="productDetail" name="detail" class="form-control"
                            placeholder="รายละเอียดสินค้า..."></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-success"
                            onclick="saveProduct()">บันทึกข้อมูลสินค้า</button>
                        <a href="productmanage.php" class="btn btn-primary">กลับสู่หน้ารายการสินค้า</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/notification.js"></script>
    <script>
    let products = [];
    let categories = [];
    let editingProduct = <?php echo $action === 'edit' && $productId ? "'" . $productId . "'" : 'null'; ?>;
    const currentAction = '<?php echo $action; ?>';

    // แสดงหน้ารายการสินค้า
    function showProductList() {
        window.location.href = 'productmanage.php';
    }

    // แสดงฟอร์มเพิ่มสินค้า
    function showAddForm() {
        window.location.href = 'productmanage.php?add';
    }

    // โหลดข้อมูลสินค้าจาก API
    function loadProducts() {
        if (currentAction !== 'list') return;
        
        const hideLoading = showLoading('กำลังโหลดข้อมูลสินค้า...');

        fetch('../controller/product_api.php?action=all')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                hideLoading();
                if (Array.isArray(data)) {
                    products = data;
                    renderProductTable();
                    showSuccess(`โหลดข้อมูลสินค้าแล้ว ${data.length} รายการ`);
                } else {
                    throw new Error('Invalid data format');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading products:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า');
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
                showError('เกิดข้อผิดพลาดในการโหลดหมวดหมู่');
                document.getElementById('productCategory').innerHTML =
                    '<option value="">เกิดข้อผิดพลาดในการโหลดหมวดหมู่</option>';
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

    // แก้ไขสินค้า - redirect ไป URL ใหม่
    function editProduct(productId) {
        window.location.href = `productmanage.php?id=${productId}`;
    }

    // โหลดข้อมูลสินค้าสำหรับแก้ไข
    function loadProductForEdit(productId) {
        const hideLoading = showLoading('กำลังโหลดข้อมูลสินค้า...');

        fetch(`../controller/product_api.php?action=get&id=${productId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(product => {
                hideLoading();
                if (product && !product.error) {
                    // เติมข้อมูลในฟอร์ม
                    document.getElementById('productName').value = product.name || '';
                    document.getElementById('productDetail').value = product.detail || '';
                    document.getElementById('productPrice').value = product.price || '';
                    document.getElementById('productStock').value = product.stock || '0';
                    document.getElementById('productSize').value = product.size || '';

                    // แสดงรูปปัจจุบัน
                    if (product.img_path !== null) {
                        document.getElementById('currentImage').classList.remove('hidden');
                        document.getElementById('currentImagePreview').src = '../controller/uploads/products/' +
                            product.img_path;
                    } else {
                        document.getElementById('currentImage').classList.add('hidden');
                    }

                    loadCategories().then(() => {
                        document.getElementById('productCategory').value = product.shoetype_id || '';
                    });

                    showInfo(`เริ่มแก้ไขสินค้า: ${product.name}`);
                } else {
                    showError('ไม่พบข้อมูลสินค้าที่ต้องการแก้ไข');
                    setTimeout(() => {
                        window.location.href = 'productmanage.php';
                    }, 2000);
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error loading product:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูลสินค้า');
                setTimeout(() => {
                    window.location.href = 'productmanage.php';
                }, 2000);
            });
    }

    // ลบสินค้า
    function deleteProduct(productId) {
        // หาชื่อสินค้าสำหรับแสดงใน confirmation
        const product = products.find(p => p.shoe_id == productId);
        const productName = product ? product.name : 'สินค้านี้';

        showConfirm(
            `คุณแน่ใจหรือไม่ที่จะลบสินค้า "${productName}"?<br><small style="color: #666;">การดำเนินการนี้ไม่สามารถยกเลิกได้</small>`,
            () => {
                // เมื่อกดตกลง
                const hideLoading = showLoading('กำลังลบสินค้า...');

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
                        hideLoading();
                        if (data.success) {
                            loadProducts();
                            showSuccess(data.message || `ลบสินค้า "${productName}" เรียบร้อยแล้ว`);
                        } else {
                            showError(data.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
                        }
                    })
                    .catch(error => {
                        hideLoading();
                        console.error('Error deleting product:', error);
                        showError('เกิดข้อผิดพลาดในการลบสินค้า');
                    });
            },
            () => {
                // เมื่อกดยกเลิก
                showInfo('ยกเลิกการลบสินค้า');
            }
        );
    }

    // ล้างฟอร์ม
    function clearForm() {
        document.getElementById('productFormData').reset();
        document.getElementById('currentImage').classList.add('hidden');

        // ล้าง image preview
        const previewContainer = document.getElementById('imagePreviewContainer');
        if (previewContainer) {
            previewContainer.classList.add('hidden');
        }
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
                showWarning(`กรุณากรอก${label}`);
                // เลื่อนไปที่ฟิลด์ที่ขาดข้อมูล
                const fieldElement = document.querySelector(`[name="${field}"]`);
                if (fieldElement) {
                    fieldElement.focus();
                    fieldElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
                return;
            }
        }

        // ตรวจสอบข้อมูลตัวเลข
        const price = parseFloat(formData.get('price'));
        const stock = parseInt(formData.get('stock'));

        if (isNaN(price) || price <= 0) {
            showWarning('กรุณากรอกราคาที่ถูกต้อง (ตัวเลขที่มากกว่า 0)');
            document.getElementById('productPrice').focus();
            return;
        }

        if (isNaN(stock) || stock < 0) {
            showWarning('กรุณากรอกจำนวนคงเหลือที่ถูกต้อง (ตัวเลขที่มากกว่าหรือเท่ากับ 0)');
            document.getElementById('productStock').focus();
            return;
        }

        // Hide alerts
        document.getElementById('successAlert').classList.add('hidden');
        document.getElementById('errorAlert').classList.add('hidden');

        let url, method;
        const actionText = editingProduct ? 'อัปเดต' : 'บันทึก';
        const hideLoading = showLoading(`กำลัง${actionText}ข้อมูลสินค้า...`);

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
                hideLoading();
                console.log('Response data:', data); // เพิ่มเพื่อ debug

                if (data.success) {
                    const successMessage = data.message ||
                        (editingProduct ? 'อัปเดตข้อมูลสินค้าเรียบร้อยแล้ว' : 'บันทึกข้อมูลสินค้าเรียบร้อยแล้ว');

                    showSuccess(successMessage);

                    // แสดงผลสำเร็จใน form ด้วย (backup)
                    document.getElementById('successAlert').classList.remove('hidden');
                    document.getElementById('successAlert').textContent = successMessage;

                    setTimeout(() => {
                        window.location.href = 'productmanage.php';
                    }, 1500);
                } else {
                    const errorMessage = data.message ||
                        (editingProduct ? 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล' : 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');

                    showError(errorMessage);

                    // แสดงข้อผิดพลาดใน form ด้วย (backup)
                    document.getElementById('errorAlert').classList.remove('hidden');
                    document.getElementById('errorAlert').textContent = errorMessage;
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error saving product:', error);
                const errorMessage = editingProduct ?
                    'เกิดข้อผิดพลาดในการอัปเดตข้อมูล' : 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';

                showError(errorMessage);

                // แสดงข้อผิดพลาดใน form ด้วย (backup)
                document.getElementById('errorAlert').classList.remove('hidden');
                document.getElementById('errorAlert').textContent = errorMessage;
            });
    }

    // แสดงตารางสินค้า
    function renderProductTable() {
        const tbody = document.getElementById('productTableBody');
        tbody.innerHTML = '';

        if (products.length === 0) {
            tbody.innerHTML =
            '<tr><td colspan="8" style="text-align: center; color: #666;">ไม่มีข้อมูลสินค้า</td></tr>';
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
        if (currentAction === 'list') {
            showInfo('กำลังเริ่มต้นระบบจัดการสินค้า...');
            loadProducts();
        } else if (currentAction === 'add') {
            showInfo('เริ่มเพิ่มสินค้าใหม่...');
            loadCategories();
            clearForm();
        } else if (currentAction === 'edit' && editingProduct) {
            showInfo('กำลังโหลดข้อมูลสำหรับแก้ไข...');
            loadCategories().then(() => {
                loadProductForEdit(editingProduct);
            });
        }
    });

    // ตรวจสอบ session ทุก 5 นาที
    setInterval(function() {
        fetch('../controller/admin_api.php?action=check_session')
            .then(response => response.json())
            .then(data => {
                if (!data.logged_in) {
                    showWarning('Session หมดอายุ กำลังนำท่านไปสู่หน้าเข้าสู่ระบบ...', 3000);
                    setTimeout(() => {
                        window.location.href = 'index.php';
                    }, 3000);
                }
            })
            .catch(err => {
                console.error('Session check error:', err);
                showError('ไม่สามารถตรวจสอบสถานะการเข้าสู่ระบบได้');
            });
    }, 5 * 60 * 1000); // 5 minutes

    // เพิ่มฟังก์ชันสำหรับจัดการ keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl + S หรือ Cmd + S สำหรับบันทึกข้อมูล
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            if (currentAction === 'add' || currentAction === 'edit') {
                saveProduct();
            }
        }

        // ESC สำหรับกลับไปหน้ารายการ
        if (e.key === 'Escape') {
            if (currentAction === 'add' || currentAction === 'edit') {
                showConfirm(
                    'ต้องการกลับไปหน้ารายการสินค้าหรือไม่?<br><small style="color: #666;">ข้อมูลที่ยังไม่ได้บันทึกจะหายไป</small>',
                    () => {
                        window.location.href = 'productmanage.php';
                    },
                    () => {
                        showInfo('ยังคงอยู่ในหน้าแก้ไขสินค้า');
                    }
                );
            }
        }
    });

    // เพิ่มการ validate form แบบ real-time
    document.addEventListener('DOMContentLoaded', function() {
        // เพิ่ม event listener สำหรับ price field
        const priceField = document.getElementById('productPrice');
        if (priceField) {
            priceField.addEventListener('input', function(e) {
                const value = parseFloat(e.target.value);
                if (isNaN(value) || value <= 0) {
                    e.target.style.borderColor = '#dc3545';
                } else {
                    e.target.style.borderColor = '#28a745';
                }
            });
        }

        // เพิ่ม event listener สำหรับ stock field
        const stockField = document.getElementById('productStock');
        if (stockField) {
            stockField.addEventListener('input', function(e) {
                const value = parseInt(e.target.value);
                if (isNaN(value) || value < 0) {
                    e.target.style.borderColor = '#dc3545';
                } else {
                    e.target.style.borderColor = '#28a745';
                }
            });
        }

        // เพิ่ม event listener สำหรับ required fields
        const requiredFields = ['productName', 'productCategory', 'productSize'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function(e) {
                    if (e.target.value.trim() === '') {
                        e.target.style.borderColor = '#dc3545';
                    } else {
                        e.target.style.borderColor = '#28a745';
                    }
                });
            }
        });

        // เพิ่ม preview สำหรับรูปภาพ
        const imageInput = document.getElementById('productImage');
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // ตรวจสอบประเภทไฟล์
                    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                    if (!allowedTypes.includes(file.type)) {
                        showWarning('กรุณาเลือกไฟล์รูปภาพเท่านั้น (jpg, png, gif)');
                        e.target.value = '';
                        return;
                    }

                    // ตรวจสอบขนาดไฟล์ (ไม่เกิน 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        showWarning('ขนาดไฟล์ต้องไม่เกิน 5 MB');
                        e.target.value = '';
                        return;
                    }

                    // แสดง preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const currentImageDiv = document.getElementById('currentImage');
                        const currentImagePreview = document.getElementById('currentImagePreview');
                        
                        currentImageDiv.classList.remove('hidden');
                        currentImagePreview.src = e.target.result;
                        
                        // เปลี่ยน label
                        const label = currentImageDiv.querySelector('small');
                        if (label) {
                            label.textContent = 'รูปใหม่ที่เลือก:';
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    // เพิ่มฟังก์ชันสำหรับ auto-save (optional)
    let autoSaveTimer;
    function startAutoSave() {
        if (currentAction === 'add' || currentAction === 'edit') {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // Auto-save draft (สามารถเพิ่ม localStorage หรือ sessionStorage)
                const formData = new FormData(document.getElementById('productFormData'));
                const draft = {};
                for (let [key, value] of formData.entries()) {
                    if (key !== 'image') { // ไม่เก็บไฟล์ใน draft
                        draft[key] = value;
                    }
                }
                // บันทึก draft ใน localStorage (optional)
                // localStorage.setItem('productDraft', JSON.stringify(draft));
                showInfo('บันทึกข้อมูลร่างอัตโนมัติ', 1000);
            }, 30000); // Auto-save ทุก 30 วินาที
        }
    }

    // เริ่ม auto-save เมื่อมีการพิมพ์
    document.addEventListener('DOMContentLoaded', function() {
        const formInputs = document.querySelectorAll('#productFormData input, #productFormData select, #productFormData textarea');
        formInputs.forEach(input => {
            input.addEventListener('input', startAutoSave);
        });
    });
    </script>
</body>

</html>