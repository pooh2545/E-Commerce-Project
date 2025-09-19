<?php
require_once '../controller/admin_auth_check.php';

$auth = requireAdmin();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
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
            font-size: 12px;
            padding: 5px 10px;
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

        /* Password Input Wrapper Styles */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input[type="password"],
        .password-wrapper input[type="text"] {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 16px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            min-height: 30px;
        }

        .password-toggle:hover {
            color: #333;
        }

        .password-toggle:focus {
            outline: none;
        }

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .strength-weak {
            color: #dc3545;
        }

        .strength-medium {
            color: #ffc107;
        }

        .strength-strong {
            color: #28a745;
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
            margin: 10% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 450px;
            max-width: 90%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            max-height: 80vh;
            overflow-y: auto;
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
            
            .modal-content {
                width: 95%;
                margin: 5% auto;
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
                    <input type="text" id="username" name="username" >
                </div>
                <div class="form-group">
                    <label for="email">อีเมล:</label>
                    <input type="email" id="email" name="email" >
                </div>
                <div class="form-group" id="passwordGroup">
                    <label for="password">รหัสผ่าน:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" >
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            Show
                        </button>
                    </div>
                    <div id="passwordStrength" class="password-strength"></div>
                </div>
                <div class="form-group">
                    <label for="role">สิทธิ์การเข้าถึง:</label>
                    <select id="role" name="role" >
                        <option value="Admin">Admin</option>
                        <option value="Employee">Employee</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">บันทึก</button>
                <button type="button" class="btn btn-danger" onclick="closeModal()">ยกเลิก</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/notification.js"></script>
    <script>
        let currentAdminId = null;
        let isEditMode = false;
        let currentUserRole = null;

        // ตรวจสอบสิทธิ์และโหลดข้อมูลเมื่อเริ่มต้น
        document.addEventListener('DOMContentLoaded', function() {
            checkUserPermission();
            setupPasswordValidation();
        });

        // ตั้งค่า Password Validation
        function setupPasswordValidation() {
            const passwordInput = document.getElementById('password');

            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
            });
        }

        // ตรวจสอบความแข็งแกร่งของรหัสผ่าน
        function checkPasswordStrength(password) {
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (!password) {
                strengthDiv.textContent = '';
                return;
            }

            let strength = 0;
            let feedback = [];

            // ตรวจสอบความยาว
            if (password.length >= 8) {
                strength++;
            } else {
                feedback.push('ต้องมีอย่างน้อย 8 ตัวอักษร');
            }

            // ตรวจสอบตัวเลข
            if (/\d/.test(password)) {
                strength++;
            } else {
                feedback.push('ต้องมีตัวเลข');
            }

            // ตรวจสอบตัวอักษรพิมพ์เล็ก
            if (/[a-z]/.test(password)) {
                strength++;
            } else {
                feedback.push('ต้องมีตัวอักษรพิมพ์เล็ก');
            }

            // ตรวจสอบตัวอักษรพิมพ์ใหญ่
            if (/[A-Z]/.test(password)) {
                strength++;
            } else {
                feedback.push('ต้องมีตัวอักษรพิมพ์ใหญ่');
            }

            // ตรวจสอบอักขระพิเศษ
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                strength++;
            } else {
                feedback.push('ต้องมีอักขระพิเศษ');
            }

            // แสดงผลความแข็งแกร่ง
            let strengthText = '';
            let strengthClass = '';

            if (strength <= 2) {
                strengthText = 'อ่อนแอ';
                strengthClass = 'strength-weak';
            } else if (strength <= 3) {
                strengthText = 'ปานกลาง';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'แข็งแกร่ง';
                strengthClass = 'strength-strong';
            }

            strengthDiv.textContent = `ความแข็งแกร่งรหัสผ่าน: ${strengthText}`;
            strengthDiv.className = `password-strength ${strengthClass}`;

            if (feedback.length > 0 && strength <= 3) {
                strengthDiv.textContent += ` (${feedback.join(', ')})`;
            }
        }

        // สลับการแสดง/ซ่อนรหัสผ่าน
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.parentElement.querySelector('.password-toggle');
            
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = 'Hide';
                button.title = 'ซ่อนรหัสผ่าน';
            } else {
                input.type = 'password';
                button.textContent = 'Show';
                button.title = 'แสดงรหัสผ่าน';
            }
        }

        // ตรวจสอบสิทธิ์ผู้ใช้
        async function checkUserPermission() {
            try {
                const response = await fetch('../controller/admin_api.php?action=check_session');
                const result = await response.json();
                
                if (result.logged_in && result.admin_data) {
                    currentUserRole = 'Admin';
                    
                    if (currentUserRole === 'Admin') {
                        document.getElementById('mainContent').style.display = 'block';
                        loadAdmins();
                    } else {
                        document.getElementById('accessDenied').style.display = 'block';
                    }
                } else {
                    window.location.href = 'index.php';
                }
            } catch (error) {
                console.error('Error checking permission:', error);
                showError('เกิดข้อผิดพลาดในการตรวจสอบสิทธิ์');
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
                            <select onchange="updateRole('${admin.admin_id}', this.value)" ${admin.admin_id == '<?php echo $currentUser["admin_id"]; ?>' ? 'disabled' : ''}>
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
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            }
        }

        // แสดงฟอร์มเพิ่ม Admin
        function showAddForm() {
            if (currentUserRole !== 'Admin') {
                showError('คุณไม่มีสิทธิ์ในการเพิ่มผู้ดูแล');
                return;
            }

            isEditMode = false;
            currentAdminId = null;
            document.getElementById('modalTitle').textContent = 'เพิ่มผู้ดูแลใหม่';
            document.getElementById('adminForm').reset();
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('password').required = false;
            document.getElementById('passwordStrength').textContent = '';
            document.getElementById('adminModal').style.display = 'block';
        }

        // แก้ไข Admin
        async function editAdmin(adminId) {
            if (currentUserRole !== 'Admin') {
                showError('คุณไม่มีสิทธิ์ในการแก้ไข');
                return;
            }

            const hideLoading = showLoading('กำลังโหลดข้อมูล...');

            try {
                const response = await fetch(`../controller/admin_api.php?action=get&id=${adminId}`);
                const admin = await response.json();

                hideLoading();

                if (admin) {
                    isEditMode = true;
                    currentAdminId = adminId;
                    document.getElementById('modalTitle').textContent = 'แก้ไขข้อมูลผู้ดูแล';
                    document.getElementById('username').value = admin.username;
                    document.getElementById('email').value = admin.email;
                    
                    if (admin.admin_id !== '<?php echo $currentUser["admin_id"]; ?>'){
                        document.getElementById('role').value = admin.role || 'Employee';
                        document.getElementById('role').removeAttribute("disabled");
                    } else {
                        document.getElementById('role').value = admin.role || 'Employee';
                        document.getElementById('role').setAttribute("disabled","");
                    }
                    
                    // แสดงฟิลด์รหัสผ่านในโหมดแก้ไข (ไม่บังคับ)
                    document.getElementById('passwordGroup').style.display = 'block';
                    document.getElementById('password').required = false;
                    document.getElementById('password').placeholder = 'เว้นว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน';
                    document.getElementById('passwordStrength').textContent = '';

                    document.getElementById('adminModal').style.display = 'block';
                    showInfo('โหลดข้อมูลสำเร็จ');
                }
            } catch (error) {
                hideLoading();
                console.error('Error loading admin:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
            }
        }

        // ลบ Admin
        async function deleteAdmin(adminId) {
            if (currentUserRole !== 'Admin') {
                showError('คุณไม่มีสิทธิ์ในการลบ');
                return;
            }

            showConfirm(
                'คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?<br><span style="color: #e74c3c; font-size: 14px;">การกระทำนี้ไม่สามารถย้อนกลับได้</span>',
                async function() {
                    const hideLoading = showLoading('กำลังลบข้อมูล...');
                    
                    try {
                        const response = await fetch(`../controller/admin_api.php?action=delete&id=${adminId}`, {
                            method: 'DELETE'
                        });
                        const result = await response.json();

                        hideLoading();

                        if (result.success) {
                            showSuccess('ลบข้อมูลสำเร็จ');
                            loadAdmins();
                        } else {
                            showError('เกิดข้อผิดพลาดในการลบข้อมูล');
                        }
                    } catch (error) {
                        hideLoading();
                        console.error('Error deleting admin:', error);
                        showError('เกิดข้อผิดพลาดในการลบข้อมูล');
                    }
                },
                function() {
                    showInfo('ยกเลิกการลบข้อมูล');
                }
            );
        }

        // อัปเดตบทบาท (สิทธิ์)
        async function updateRole(adminId, newRole) {
            if (currentUserRole !== 'Admin') {
                showError('คุณไม่มีสิทธิ์ในการเปลี่ยนสิทธิ์');
                return;
            }

            const hideLoading = showLoading('กำลังอัปเดตสิทธิ์...');

            try {
                const response = await fetch(`../controller/admin_api.php?action=update_role&id=${adminId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ role: newRole })
                });
                
                const result = await response.json();
                
                hideLoading();
                
                if (result.success) {
                    showSuccess('อัปเดตสิทธิ์สำเร็จ');
                } else {
                    showError('เกิดข้อผิดพลาดในการอัปเดตสิทธิ์');
                    loadAdmins();
                }
            } catch (error) {
                hideLoading();
                console.error('Error updating role:', error);
                showError('เกิดข้อผิดพลาดในการอัปเดตสิทธิ์');
                loadAdmins();
            }
        }

        // ปิด Modal
        function closeModal() {
            document.getElementById('adminModal').style.display = 'none';
            document.getElementById('adminForm').reset();
            document.getElementById('passwordStrength').textContent = '';
            
            // รีเซ็ต password field types
            document.getElementById('password').type = 'password';
            document.querySelectorAll('.password-toggle').forEach(btn => {
                btn.textContent = 'Show';
            });
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

            // Validation
            if (!data.username || data.username.length < 3) {
                showWarning('กรุณาใส่ชื่อผู้ใช้อย่างน้อย 3 ตัวอักษร');
                return;
            }

            if (!data.email || !isValidEmail(data.email)) {
                showWarning('กรุณาใส่อีเมลที่ถูกต้อง');
                return;
            }

            // ตรวจสอบรหัสผ่านสำหรับการเพิ่มใหม่
            if (!isEditMode) {
                if (!data.password) {
                    showWarning('กรุณาใส่รหัสผ่าน');
                    return;
                }

                if (data.password.length < 6) {
                    showWarning('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
                    return;
                }
            } 
            // ตรวจสอบรหัสผ่านสำหรับการแก้ไข (ถ้ามีการใส่รหัสผ่านใหม่)
            else if (data.password) {
                if (data.password.length < 6) {
                    showWarning('รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร');
                    return;
                }
            }

            const hideLoading = showLoading(isEditMode ? 'กำลังอัปเดตข้อมูล...' : 'กำลังเพิ่มผู้ดูแล...');

            try {
                let url, method;
                
                // เตรียมข้อมูลที่จะส่ง
                const submitData = {
                    username: data.username,
                    email: data.email,
                    role: data.role
                };

                if (isEditMode) {
                    url = `../controller/admin_api.php?action=update&id=${currentAdminId}`;
                    method = 'PUT';
                    // ถ้ารหัสผ่านไม่ว่าง ให้เพิ่มเข้าไป
                    if (data.password) {
                        submitData.password = data.password;
                    }
                } else {
                    url = '../controller/admin_api.php?action=create';
                    method = 'POST';
                    submitData.password = data.password;
                }

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(submitData)
                });

                const result = await response.json();

                hideLoading();

                if (result.success) {
                    showSuccess(isEditMode ? 'อัปเดตข้อมูลสำเร็จ' : 'เพิ่มผู้ดูแลสำเร็จ');
                    formChanged = false;
                    closeModal();
                    loadAdmins();
                } else {
                    showError(result.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            } catch (error) {
                hideLoading();
                console.error('Error saving admin:', error);
                showError('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
            }
        });

        // ฟังก์ชันตรวจสอบอีเมล
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
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

        // เพิ่มฟังก์ชันค้นหา (ถ้ามี input สำหรับค้นหา)
        function searchAdmins() {
            const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('#adminTableBody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });

            if (searchTerm) {
                showInfo(`แสดงผลลัพธ์การค้นหา: "${searchTerm}"`);
            }
        }

        // เพิ่มฟังก์ชันรีเฟรชข้อมูล
        function refreshData() {
            showInfo('กำลังรีเฟรชข้อมูล...');
            loadAdmins();
        }

        // เพิ่ม Event Listener สำหรับ Enter key ในช่องค้นหา
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = document.getElementById('adminModal');
                if (modal.style.display === 'block') {
                    closeModal();
                }
            }
        });

        // ฟังก์ชันเพิ่มเติมสำหรับการตรวจสอบความปลอดภัยของรหัสผ่าน
        function validatePasswordSecurity(password) {
            const minLength = 8;
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);
            const hasNonalphas = /\W/.test(password);
            
            return password.length >= minLength && hasUpperCase && hasLowerCase && hasNumbers && hasNonalphas;
        }

        // ฟังก์ชันสำหรับการตรวจสอบรหัสผ่านที่ซ้ำกัน
        function checkPasswordCommonality(password) {
            const commonPasswords = [
                'password', '123456', '123456789', 'qwerty', 'abc123', 
                'password123', 'admin', 'letmein', 'welcome', 'monkey'
            ];
            
            return !commonPasswords.includes(password.toLowerCase());
        }

        // เพิ่มการตรวจสอบเมื่อพิมพ์รหัสผ่าน
        document.getElementById('password').addEventListener('input', function(e) {
            const password = e.target.value;
            
            if (password && !checkPasswordCommonality(password)) {
                showWarning('กรุณาหลีกเลี่ยงการใช้รหัสผ่านที่ง่ายต่อการเดา');
            }
        });

        // Auto-focus ไปที่ช่องแรกเมื่อเปิด modal
        document.getElementById('adminModal').addEventListener('transitionend', function() {
            if (this.style.display === 'block') {
                document.getElementById('username').focus();
            }
        });

        // เพิ่มการตรวจสอบการเปลี่ยนแปลงก่อนปิด modal
        let formChanged = false;
        document.querySelectorAll('#adminForm input, #adminForm select').forEach(input => {
            input.addEventListener('change', () => {
                formChanged = true;
            });
        });

        // แก้ไข closeModal เพื่อตรวจสอบการเปลี่ยนแปลง
        const originalCloseModal = closeModal;
        closeModal = function() {
            if (formChanged) {
                showConfirm(
                    'คุณมีการเปลี่ยนแปลงที่ยังไม่ได้บันทึก ต้องการปิดหน้าต่างนี้หรือไม่?',
                    function() {
                        formChanged = false;
                        originalCloseModal();
                    },
                    function() {
                        // ไม่ทำอะไร - ให้อยู่ใน modal
                    }
                );
            } else {
                originalCloseModal();
            }
        };
    </script>
</body>
</html>