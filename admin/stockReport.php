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
    <title>รายงานสต็อกสินค้า</title>
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
    <div class="page-header">
        <h1 class="page-title">รายงานสต็อกสินค้า</h1>
    </div>

    <div class="product-table">
        <table>
            <thead>
                <tr>
                    <th>รหัสสินค้า</th>
                    <th>ชื่อสินค้า</th>
                    <th>หมวดหมู่</th>
                    <th>ขนาด</th>
                    <th>จำนวน</th>
                    <th>การจัดการ</th>
                </tr>
            </thead>
            <tbody id="productTableBody">
                <tr><td colspan="6" class="loading">กำลังโหลดข้อมูล...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
let products = [];

function loadProducts() {
    fetch('../controller/stock_report_api.php?action=all')
    .then(res => res.json())
    .then(data => {
        products = data;
        renderProductTable();
    })
    .catch(err => {
        console.error('Error loading products:', err);
        document.getElementById('productTableBody').innerHTML =
            '<tr><td colspan="6" style="text-align:center;color:red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
    });
}

function renderProductTable() {
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML = '';

    if (!products || products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#666;">ไม่มีข้อมูลสินค้า</td></tr>';
        return;
    }

    products.forEach(product => {
        const row = document.createElement('tr');
        row.dataset.id = product.shoe_id;

        row.innerHTML = `
            <td>${product.shoe_id}</td>
            <td>${product.name}</td>
            <td>${product.category_name || 'ไม่ระบุ'}</td>
            <td>${product.size}</td>
            <td>
                <span id="stock-${product.shoe_id}">${product.stock}</span>
                <input type="number" min="0" id="input-stock-${product.shoe_id}" value="${product.stock}" style="display:none;">
            </td>
            <td>
                <button class="btn btn-info" onclick="editStock('${product.shoe_id}')">แก้ไข</button>
                <button class="btn btn-success" id="save-btn-${product.shoe_id}" onclick="saveStock('${product.shoe_id}')" style="display:none;">บันทึก</button>
                <button class="btn btn-danger" onclick="deleteProduct('${product.shoe_id}')">ลบ</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function editStock(id) {
    document.getElementById(`stock-${id}`).style.display = "none";
    document.getElementById(`input-stock-${id}`).style.display = "inline-block";
    document.getElementById(`save-btn-${id}`).style.display = "inline-block";
}

function saveStock(id) {
    const newStock = document.getElementById(`input-stock-${id}`).value;

    fetch('../controller/stock_report_api.php?action=update', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `id=${encodeURIComponent(id)}&stock=${encodeURIComponent(newStock)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ อัปเดตจำนวนสินค้าเรียบร้อยแล้ว');
            document.getElementById(`stock-${id}`).innerText = newStock;
            document.getElementById(`stock-${id}`).style.display = "inline";
            document.getElementById(`input-stock-${id}`).style.display = "none";
            document.getElementById(`save-btn-${id}`).style.display = "none";
        } else {
            alert('❌ ไม่สามารถอัปเดตจำนวนสินค้าได้');
        }
    })
    .catch(err => {
        console.error('Error updating stock:', err);
        alert('เกิดข้อผิดพลาดในการอัปเดตจำนวน');
    });
}

function deleteProduct(id) {
    if (!confirm('คุณแน่ใจหรือไม่ที่จะลบสินค้านี้?')) return;

    fetch('../controller/stock_report_api.php?action=delete', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: `id=${encodeURIComponent(id)}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            document.querySelector(`tr[data-id="${id}"]`).remove();
        } else {
            alert('❌ ลบสินค้าไม่สำเร็จ');
        }
    })
    .catch(err => {
        console.error('Error deleting product:', err);
        alert('เกิดข้อผิดพลาดในการลบสินค้า');
    });
}

document.addEventListener('DOMContentLoaded', loadProducts);
</script>
</body>
</html>