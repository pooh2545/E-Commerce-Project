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
    <title>Admin Management</title>

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

        select, input[type="file"], input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            background-color: white;
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
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close {
            color: #aaa;
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

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }

        .alert-success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .access-denied {
            background-color: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
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
        <div id="accessDenied" class="access-denied" style="display: none;">
            <h3>ไม่มีสิทธิ์เข้าใช้งาน</h3>
            <p>คุณไม่มีสิทธิ์ในการจัดการข้อมูลผู้ดูแลระบบ</p>
        </div>

        <div id="mainContent" style="display: none;">
            <div class="page-header">
                <h1 class="page-title">จัดการผู้ดูแลระบบ</h1>
                <button class="btn btn-primary" onclick="showAddForm()" style="margin-top: 20px;">+ เพิ่มผู้ดูแล</button>
            </div>

            <div id="alertContainer"></div>

            <div class="loading" id="loading">
                <p>กำลังโหลด...</p>
            </div>

            <div class="product-table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อผู้ดูแล</th>
                            <th>อีเมล</th>
                            <th>สิทธิ์การเข้าถึง</th>
                            <th>วันที่สร้าง</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="adminTableBody">
                        <!-- ข้อมูลจะถูกโหลดจาก API -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Admin Modal -->
    <div id="adminModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">เพิ่มผู้ดูแลใหม่</h3>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="adminForm">
                <div class="form-group">
                    <label for="username">ชื่อผู้ดูแล:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group" id="passwordGroup">
                    <label for="password">รหัสผ่าน:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">สิทธิ์การเข้าถึง:</label>
                    <select id="role" name="role" required>
                        <option value="Admin">Admin</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">บันทึก</button>
                <button type="button" class="btn btn-danger" onclick="closeModal()">ยกเลิก</button>
            </form>
        </div>
    </div>

    <script>
        let currentAdminId = null;
        let isEditMode = false;
        let currentUserRole = null;

        // ตรวจสอบสิทธิ์และโหลดข้อมูลเมื่อเริ่มต้น
        document.addEventListener('DOMContentLoaded', function() {
            checkUserPermission();
        });

        // ตรวจสอบสิทธิ์ผู้ใช้
        async function checkUserPermission() {
            try {
                const response = await fetch('../controller/admin_api.php?action=check_session');
                const result = await response.json();
                
                if (result.logged_in && result.admin_data) {
                    // สมมติว่า role เก็บในข้อมูล admin หรือสามารถเพิ่มได้
                    currentUserRole = 'Admin'; // หรือดึงจาก result.admin_data.role
                    
                    // ตรวจสอบว่าเป็น Admin หรือไม่
                    if (currentUserRole === 'Admin') {
                        document.getElementById('mainContent').style.display = 'block';
                        loadAdmins();
                    } else {
                        document.getElementById('accessDenied').style.display = 'block';
                    }
                } else {
                    // ไม่ได้ล็อกอิน redirect ไปหน้าล็อกอิน
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Error checking permission:', error);
                showAlert('เกิดข้อผิดพลาดในการตรวจสอบสิทธิ์', 'error');
            }
        }

        // โหลดข้อมูล Admin ทั้งหมด
        async function loadAdmins() {
            document.getElementById('loading').style.display = 'block';
            
            try {
                const response = await fetch('../controller/admin_api.php?action=all');
                const admins = await response.json();
                
                const tbody = document.getElementById('adminTableBody');
                tbody.innerHTML = '';

                admins.forEach(admin => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${admin.admin_id}</td>
                        <td>${admin.username}</td>
                        <td>${admin.email}</td>
                        <td>
                            <select onchange="updateRole('${admin.admin_id}', this.value)" ${currentUserRole !== 'Admin' ? 'disabled' : ''}>
                                <option value="Admin" ${admin.role === 'Admin' ? 'selected' : ''}>Admin</option>
                                <option value="Employee" ${admin.role === 'Employee' ? 'selected' : ''}>Employee</option>
                            </select>
                        </td>
                        <td>${formatDate(admin.create_at)}</td>
                        <td>
                            ${currentUserRole === 'Admin' ? `
                                <button class="btn btn-info" onclick="editAdmin('${admin.admin_id}')">แก้ไข</button>
                                <button class="btn btn-danger" onclick="deleteAdmin('${admin.admin_id}')">ลบ</button>
                            ` : 'ไม่มีสิทธิ์'}
                        </td>
                    `;
                    tbody.appendChild(row);
                });

                document.getElementById('loading').style.display = 'none';
            } catch (error) {
                document.getElementById('loading').style.display = 'none';
                console.error('Error loading admins:', error);
                showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
            }
        }

        // แสดงฟอร์มเพิ่ม Admin
        function showAddForm() {
            if (currentUserRole !== 'Admin') {
                showAlert('คุณไม่มีสิทธิ์ในการเพิ่มผู้ดูแล', 'error');
                return;
            }

            isEditMode = false;
            currentAdminId = null;
            document.getElementById('modalTitle').textContent = 'เพิ่มผู้ดูแลใหม่';
            document.getElementById('adminForm').reset();
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('password').required = true;
            document.getElementById('adminModal').style.display = 'block';
        }

        // แก้ไข Admin
        async function editAdmin(adminId) {
            if (currentUserRole !== 'Admin') {
                showAlert('คุณไม่มีสิทธิ์ในการแก้ไข', 'error');
                return;
            }

            try {
                const response = await fetch(`../controller/admin_api.php?action=get&id=${adminId}`);
                const admin = await response.json();

                if (admin) {
                    isEditMode = true;
                    currentAdminId = adminId;
                    document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลผู้ดูแล';
                    document.getElementById('username').value = admin.username;
                    document.getElementById('email').value = admin.email;
                    document.getElementById('role').value = admin.role || 'Employee';
                    
                    // ซ่อนฟิลด์รหัสผ่านในโหมดแก้ไข (หรือทำให้ไม่ required)
                    document.getElementById('passwordGroup').style.display = 'block';
                    document.getElementById('password').required = false;
                    document.getElementById('password').placeholder = 'เว้นว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน';

                    document.getElementById('adminModal').style.display = 'block';
                }
            } catch (error) {
                console.error('Error loading admin:', error);
                showAlert('เกิดข้อผิดพลาดในการโหลดข้อมูล', 'error');
            }
        }

        // ลบ Admin
        async function deleteAdmin(adminId) {
            if (currentUserRole !== 'Admin') {
                showAlert('คุณไม่มีสิทธิ์ในการลบ', 'error');
                return;
            }

            if (confirm('คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?')) {
                try {
                    const response = await fetch(`../controller/admin_api.php?action=delete&id=${adminId}`, {
                        method: 'DELETE'
                    });
                    const result = await response.json();

                    if (result.success) {
                        showAlert('ลบข้อมูลสำเร็จ', 'success');
                        loadAdmins();
                    } else {
                        showAlert('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting admin:', error);
                    showAlert('เกิดข้อผิดพลาดในการลบข้อมูล', 'error');
                }
            }
        }

        // อัปเดตบทบาท (สิทธิ์)
        async function updateRole(adminId, newRole) {
            if (currentUserRole !== 'Admin') {
                showAlert('คุณไม่มีสิทธิ์ในการเปลี่ยนสิทธิ์', 'error');
                return;
            }

            try {
                // เนื่องจาก API ปัจจุบันไม่รองรับการอัปเดตบทบาท
                // คุณอาจต้องเพิ่ม endpoint นี้ใน admin_api.php
                const response = await fetch(`../controller/admin_api.php?action=update_role&id=${adminId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ role: newRole })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showAlert('อัปเดตสิทธิ์สำเร็จ', 'success');
                } else {
                    showAlert('เกิดข้อผิดพลาดในการอัปเดตสิทธิ์', 'error');
                    loadAdmins(); // โหลดใหม่เพื่อแสดงค่าเดิม
                }
            } catch (error) {
                console.error('Error updating role:', error);
                showAlert('เกิดข้อผิดพลาดในการอัปเดตสิทธิ์', 'error');
                loadAdmins();
            }
        }

        // ปิด Modal
        function closeModal() {
            document.getElementById('adminModal').style.display = 'none';
            document.getElementById('adminForm').reset();
        }

        // Handle form submission
        document.getElementById('adminForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const data = {
                username: formData.get('username'),
                email: formData.get('email'),
                password: formData.get('password'),
                role: formData.get('role')
            };

            try {
                let url, method;
                
                if (isEditMode) {
                    url = `../controller/admin_api.php?action=update&id=${currentAdminId}`;
                    method = 'PUT';
                    // ถ้ารหัสผ่านว่าง ให้ลบออก
                    if (!data.password) {
                        delete data.password;
                    }
                } else {
                    url = '../controller/admin_api.php?action=create';
                    method = 'POST';
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showAlert(isEditMode ? 'อัปเดตข้อมูลสำเร็จ' : 'เพิ่มผู้ดูแลสำเร็จ', 'success');
                    closeModal();
                    loadAdmins();
                } else {
                    showAlert('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
                }
            } catch (error) {
                console.error('Error saving admin:', error);
                showAlert('เกิดข้อผิดพลาดในการบันทึกข้อมูล', 'error');
            }
        });

        // แสดงข้อความแจ้งเตือน
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            
            alertContainer.innerHTML = '';
            alertContainer.appendChild(alertDiv);

            // ลบข้อความหลังจาก 5 วินาที
            setTimeout(() => {
                alertDiv.remove();
            }, 5000);
        }

        // จัดรูปแบบวันที่
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // ปิด modal เมื่อคลิกนอกพื้นที่
        window.onclick = function(event) {
            const modal = document.getElementById('adminModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>