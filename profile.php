<?php
require_once 'controller/auth_check.php';
redirectIfNotLoggedIn(); // จะ redirect ไป login.php ถ้ายังไม่ login
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้ใช้งาน - Logo Store</title>
    <link href="assets/css/header.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .profile-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
        }

        .sidebar {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: fit-content;
            position: sticky;
            top: 20px;
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9b59b6, #8e44ad);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            margin: 0 auto 15px;
        }

        .profile-name {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #666;
            font-size: 14px;
        }

        .menu-list {
            list-style: none;
        }

        .menu-item {
            margin-bottom: 10px;
        }

        .menu-link {
            display: block;
            padding: 12px 15px;
            text-decoration: none;
            color: #666;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .menu-link:hover,
        .menu-link.active {
            background: #9b59b6;
            color: white;
        }

        .main-content {
            background: white;
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
            border-bottom: 3px solid #9b59b6;
            padding-bottom: 10px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 8px;
            color: #333;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #9b59b6;
            box-shadow: 0 0 0 3px rgba(155, 89, 182, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background: #9b59b6;
            color: white;
        }

        .btn-primary:hover {
            background: #8e44ad;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #5a6268;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .address-card {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            position: relative;
        }

        .address-card.default {
            border-color: #9b59b6;
            background: #f8f3ff;
        }

        .address-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .address-type {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }

        .default-badge {
            background: #9b59b6;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .address-details {
            color: #666;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .address-actions {
            display: flex;
            gap: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        .modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 15px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
        }

        .close-btn:hover {
            color: #333;
        }

        /* Logout Confirmation Modal Styles */
        .logout-modal {
            display: none;
            position: fixed;
            z-index: 1001;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            animation: fadeIn 0.3s ease;
        }

        .logout-modal.show {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-modal-content {
            background: white;
            border-radius: 20px;
            padding: 0;
            max-width: 450px;
            width: 90%;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            animation: slideIn 0.3s ease;
            overflow: hidden;
        }

        .logout-modal-header {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }

        .logout-icon {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
        }

        .logout-modal-title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .logout-modal-subtitle {
            font-size: 14px;
            opacity: 0.9;
        }

        .logout-modal-body {
            padding: 30px;
            text-align: center;
        }

        .logout-message {
            font-size: 16px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .logout-user-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
            border-left: 4px solid #9b59b6;
        }

        .logout-user-name {
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
        }

        .logout-user-email {
            color: #666;
            font-size: 14px;
        }

        .logout-modal-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .logout-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 100px;
            position: relative;
            overflow: hidden;
        }

        .logout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .logout-btn:hover::before {
            left: 100%;
        }

        .logout-btn-cancel {
            background: #6c757d;
            color: white;
        }

        .logout-btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-1px);
        }

        .logout-btn-confirm {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .logout-btn-confirm:hover {
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-1px);
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #27ae60;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            z-index: 1001;
            font-size: 14px;
            font-weight: 500;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }

        .notification.error {
            background: #e74c3c;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .strength-weak { color: #e74c3c; }
        .strength-medium { color: #f39c12; }
        .strength-strong { color: #27ae60; }

        @media (max-width: 768px) {
            .profile-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .sidebar {
                position: static;
                padding: 20px;
            }

            .main-content {
                padding: 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container">
        <h1 class="page-title">โปรไฟล์ผู้ใช้งาน</h1>
        
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="profile-avatar">
                    <div class="avatar">JD</div>
                    <div class="profile-name">John Doe</div>
                    <div class="profile-email">john.doe@example.com</div>
                </div>
                
                <ul class="menu-list">
                    <li class="menu-item">
                        <a href="#" class="menu-link active" data-section="profile">ข้อมูลส่วนตัว</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" data-section="addresses">ที่อยู่จัดส่ง</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" data-section="password">เปลี่ยนรหัสผ่าน</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" data-section="orders">ประวัติการสั่งซื้อ</a>
                    </li>
                    <li class="menu-item">
                        <a href="#" class="menu-link" data-section="logout">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Profile Section -->
                <div class="content-section active" id="profile">
                    <h2 class="section-title">ข้อมูลส่วนตัว</h2>
                    <form id="profileForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">ชื่อ</label>
                                <input type="text" class="form-input" value="John" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" class="form-input" value="Doe" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-input" value="john.doe@example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-input" value="081-234-5678" required>
                        </div>

                        
                        <button type="submit" class="btn btn-primary">บันทึกข้อมูล</button>
                    </form>
                </div>

                <!-- Addresses Section -->
                <div class="content-section" id="addresses">
                    <h2 class="section-title">ที่อยู่จัดส่ง</h2>
                    
                    <div class="address-list">
                        <div class="address-card default">
                            <div class="address-header">
                                <div class="address-type">ที่อยู่หลัก</div>
                                <div class="default-badge">ค่าเริ่มต้น</div>
                            </div>
                            <div class="address-details">
                                <strong>John Doe</strong><br>
                                123 ถนนสุขุมวิท แขวงคลองเตย<br>
                                เขตคลองเตย กรุงเทพมหานคร 10110<br>
                                โทร: 081-234-5678
                            </div>
                            <div class="address-actions">
                                <button class="btn btn-secondary btn-sm" onclick="editAddress(0)">แก้ไข</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAddress(0)">ลบ</button>
                            </div>
                        </div>

                        <div class="address-card">
                            <div class="address-header">
                                <div class="address-type">ที่อยู่ทำงาน</div>
                            </div>
                            <div class="address-details">
                                <strong>John Doe</strong><br>
                                456 ถนนพหลโยธิน แขวงลาดยาว<br>
                                เขตจตุจักร กรุงเทพมหานคร 10900<br>
                                โทร: 081-234-5678
                            </div>
                            <div class="address-actions">
                                <button class="btn btn-secondary btn-sm" onclick="setDefault(1)">ตั้งเป็นค่าเริ่มต้น</button>
                                <button class="btn btn-secondary btn-sm" onclick="editAddress(1)">แก้ไข</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteAddress(1)">ลบ</button>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary" onclick="showAddressModal()">เพิ่มที่อยู่ใหม่</button>
                </div>

                <!-- Password Section -->
                <div class="content-section" id="password">
                    <h2 class="section-title">เปลี่ยนรหัสผ่าน</h2>
                    <form id="passwordForm">
                        <div class="form-group">
                            <label class="form-label">รหัสผ่านปัจจุบัน</label>
                            <input type="password" class="form-input" id="currentPassword" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <input type="password" class="form-input" id="newPassword" required>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <input type="password" class="form-input" id="confirmPassword" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
                    </form>
                </div>

                <!-- Orders Section -->
                <div class="content-section" id="orders">
                    <h2 class="section-title">ประวัติการสั่งซื้อ</h2>
                    
                    <div class="address-card">
                        <div class="address-header">
                            <div class="address-type">คำสั่งซื้อ #ORD2568</div>
                            <span style="color: #27ae60; font-weight: bold;">กำลังจัดส่ง</span>
                        </div>
                        <div class="address-details">
                            วันที่สั่งซื้อ: 26 กรกฎาคม 2025<br>
                            จำนวน: 4 รายการ<br>
                            ยอดรวม: ฿836
                        </div>
                        <div class="address-actions">
                            <button class="btn btn-secondary btn-sm">ดูรายละเอียด</button>
                            <button class="btn btn-primary btn-sm">ติดตามพัสดุ</button>
                        </div>
                    </div>

                    <div class="address-card">
                        <div class="address-header">
                            <div class="address-type">คำสั่งซื้อ #ORD2567</div>
                            <span style="color: #27ae60; font-weight: bold;">จัดส่งสำเร็จ</span>
                        </div>
                        <div class="address-details">
                            วันที่สั่งซื้อ: 20 กรกฎาคม 2025<br>
                            จำนวน: 2 รายการ<br>
                            ยอดรวม: ฿450
                        </div>
                        <div class="address-actions">
                            <button class="btn btn-secondary btn-sm">ดูรายละเอียด</button>
                            <button class="btn btn-success btn-sm">รีวิวสินค้า</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Address Modal -->
    <div class="modal" id="addressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">เพิ่ม/แก้ไขที่อยู่</h3>
                <button class="close-btn" onclick="closeAddressModal()">&times;</button>
            </div>
            
            <form id="addressForm">
                <div class="form-group">
                    <label class="form-label">ชื่อที่อยู่</label>
                    <input type="text" class="form-input" id="addressName" placeholder="เช่น บ้าน, ที่ทำงาน" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">ชื่อผู้รับ</label>
                        <input type="text" class="form-input" id="recipientName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="tel" class="form-input" id="recipientPhone" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">ที่อยู่</label>
                    <input type="text" class="form-input" id="addressLine" placeholder="บ้านเลขที่, ซอย, ถนน" required>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">แขวง/ตำบล</label>
                        <input type="text" class="form-input" id="subDistrict" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">เขต/อำเภอ</label>
                        <input type="text" class="form-input" id="district" required>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">จังหวัด</label>
                        <input type="text" class="form-input" id="province" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">รหัสไปรษณีย์</label>
                        <input type="text" class="form-input" id="postalCode" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" id="setAsDefault"> ตั้งเป็นที่อยู่เริ่มต้น
                    </label>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeAddressModal()">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="logout-modal" id="logoutModal">
        <div class="logout-modal-content">
            <div class="logout-modal-header">
                <div class="logout-icon">🚪</div>
                <div class="logout-modal-title">ยืนยันการออกจากระบบ</div>
                <div class="logout-modal-subtitle">คุณต้องการออกจากระบบหรือไม่?</div>
            </div>
            <div class="logout-modal-body">
                <div class="logout-message">
                    หากคุณออกจากระบบ คุณจะต้องเข้าสู่ระบบใหม่อีกครั้งเพื่อเข้าถึงข้อมูลส่วนตัวและประวัติการสั่งซื้อของคุณ
                </div>
                <div class="logout-user-info">
                    <div class="logout-user-name">John Doe</div>
                    <div class="logout-user-email">john.doe@example.com</div>
                </div>
                <div class="logout-modal-actions">
                    <button class="logout-btn logout-btn-cancel" onclick="closeLogoutModal()">ยกเลิก</button>
                    <button class="logout-btn logout-btn-confirm" onclick="confirmLogout()">ออกจากระบบ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <?php include("includes/MainFooter.php"); ?>
    <script>
        // Menu navigation
        document.querySelectorAll('.menu-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                const sectionId = this.getAttribute('data-section');
                
                // Check if logout is clicked
                if (sectionId === 'logout') {
                    showLogoutModal();
                    return;
                }
                
                // Remove active class from all links and sections
                document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
                
                // Add active class to clicked link
                this.classList.add('active');
                
                // Show corresponding section
                document.getElementById(sectionId).classList.add('active');
            });
        });

        // Profile form submission
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('บันทึกข้อมูลเรียบร้อยแล้ว');
        });

        // Password form submission
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                showNotification('รหัสผ่านใหม่ไม่ตรงกัน', 'error');
                return;
            }
            
            if (newPassword.length < 8) {
                showNotification('รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร', 'error');
                return;
            }
            
            showNotification('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
            this.reset();
        });

        // Password strength checker
        document.getElementById('newPassword').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            let strength = 0;
            let message = '';
            let className = '';
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            if (strength < 3) {
                message = 'รหัสผ่านอ่อน';
                className = 'strength-weak';
            } else if (strength < 4) {
                message = 'รหัสผ่านปานกลาง';
                className = 'strength-medium';
            } else {
                message = 'รหัสผ่านแข็งแรง';
                className = 'strength-strong';
            }
            
            strengthDiv.textContent = message;
            strengthDiv.className = `password-strength ${className}`;
        });

        // Address management
        let addresses = [
            {
                name: 'ที่อยู่หลัก',
                recipient: 'John Doe',
                phone: '081-234-5678',
                address: '123 ถนนสุขุมวิท แขวงคลองเตย',
                subDistrict: 'คลองเตย',
                district: 'คลองเตย',
                province: 'กรุงเทพมหานคร',
                postalCode: '10110',
                isDefault: true
            },
            {
                name: 'ที่อยู่ทำงาน',
                recipient: 'John Doe',
                phone: '081-234-5678',
                address: '456 ถนนพหลโยธิน แขวงลาดยาว',
                subDistrict: 'ลาดยาว',
                district: 'จตุจักร',
                province: 'กรุงเทพมหานคร',
                postalCode: '10900',
                isDefault: false
            }
        ];

        let editingAddressIndex = -1;

        function showAddressModal(index = -1) {
            editingAddressIndex = index;
            const modal = document.getElementById('addressModal');
            const form = document.getElementById('addressForm');
            
            if (index >= 0) {
                // Edit mode
                const address = addresses[index];
                document.getElementById('addressName').value = address.name;
                document.getElementById('recipientName').value = address.recipient;
                document.getElementById('recipientPhone').value = address.phone;
                document.getElementById('addressLine').value = address.address;
                document.getElementById('subDistrict').value = address.subDistrict;
                document.getElementById('district').value = address.district;
                document.getElementById('province').value = address.province;
                document.getElementById('postalCode').value = address.postalCode;
                document.getElementById('setAsDefault').checked = address.isDefault;
                document.querySelector('.modal-title').textContent = 'แก้ไขที่อยู่';
            } else {
                // Add mode
                form.reset();
                document.querySelector('.modal-title').textContent = 'เพิ่มที่อยู่ใหม่';
            }
            
            modal.classList.add('show');
        }

        function closeAddressModal() {
            document.getElementById('addressModal').classList.remove('show');
            editingAddressIndex = -1;
        }

        function editAddress(index) {
            showAddressModal(index);
        }

        function deleteAddress(index) {
            if (confirm('คุณต้องการลบที่อยู่นี้หรือไม่?')) {
                addresses.splice(index, 1);
                renderAddresses();
                showNotification('ลบที่อยู่เรียบร้อยแล้ว');
            }
        }

        function setDefault(index) {
            addresses.forEach((addr, i) => {
                addr.isDefault = i === index;
            });
            renderAddresses();
            showNotification('ตั้งที่อยู่เริ่มต้นเรียบร้อยแล้ว');
        }

        function renderAddresses() {
            const addressList = document.querySelector('.address-list');
            addressList.innerHTML = '';
            
            addresses.forEach((address, index) => {
                const addressCard = document.createElement('div');
                addressCard.className = `address-card${address.isDefault ? ' default' : ''}`;
                
                addressCard.innerHTML = `
                    <div class="address-header">
                        <div class="address-type">${address.name}</div>
                        ${address.isDefault ? '<div class="default-badge">ค่าเริ่มต้น</div>' : ''}
                    </div>
                    <div class="address-details">
                        <strong>${address.recipient}</strong><br>
                        ${address.address}<br>
                        ${address.subDistrict} ${address.district} ${address.province} ${address.postalCode}<br>
                        โทร: ${address.phone}
                    </div>
                    <div class="address-actions">
                        ${!address.isDefault ? `<button class="btn btn-secondary btn-sm" onclick="setDefault(${index})">ตั้งเป็นค่าเริ่มต้น</button>` : ''}
                        <button class="btn btn-secondary btn-sm" onclick="editAddress(${index})">แก้ไข</button>
                        <button class="btn btn-danger btn-sm" onclick="deleteAddress(${index})">ลบ</button>
                    </div>
                `;
                
                addressList.appendChild(addressCard);
            });
        }

        // Address form submission
        document.getElementById('addressForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                name: document.getElementById('addressName').value,
                recipient: document.getElementById('recipientName').value,
                phone: document.getElementById('recipientPhone').value,
                address: document.getElementById('addressLine').value,
                subDistrict: document.getElementById('subDistrict').value,
                district: document.getElementById('district').value,
                province: document.getElementById('province').value,
                postalCode: document.getElementById('postalCode').value,
                isDefault: document.getElementById('setAsDefault').checked
            };
            
            if (formData.isDefault) {
                // Set all other addresses as not default
                addresses.forEach(addr => addr.isDefault = false);
            }
            
            if (editingAddressIndex >= 0) {
                // Edit existing address
                addresses[editingAddressIndex] = formData;
                showNotification('แก้ไขที่อยู่เรียบร้อยแล้ว');
            } else {
                // Add new address
                addresses.push(formData);
                showNotification('เพิ่มที่อยู่เรียบร้อยแล้ว');
            }
            
            renderAddresses();
            closeAddressModal();
        });

        // Notification function
        function showNotification(message, type = 'success') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type} show`;
            
            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('addressModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddressModal();
            }
        });

        // Logout Modal Functions
        function showLogoutModal() {
            document.getElementById('logoutModal').classList.add('show');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeLogoutModal() {
            document.getElementById('logoutModal').classList.remove('show');
            document.body.style.overflow = ''; // Restore scrolling
        }

        function confirmLogout() {
            // Show loading state
            const confirmBtn = document.querySelector('.logout-btn-confirm');
            const originalText = confirmBtn.textContent;
            confirmBtn.textContent = 'กำลังออกจากระบบ...';
            confirmBtn.disabled = true;
                
            // ส่ง AJAX request ไปยัง logout action
            fetch('controller/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=logout'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeLogoutModal();
                    showNotification('ออกจากระบบเรียบร้อยแล้ว');
                    
                    // Redirect to login page
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 1500);
                } else {
                    showNotification('เกิดข้อผิดพลาด: ' + data.message);
                    // Reset button
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('เกิดข้อผิดพลาดในการออกจากระบบ');
                // Reset button
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            });
        }

        // Close logout modal when clicking outside
        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });

        // Close logout modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('logoutModal').classList.contains('show')) {
                closeLogoutModal();
            }
        });


        // Initialize page
        renderAddresses();
    </script>
</body>
</html>