<?php
require_once 'controller/auth_check.php';
redirectIfNotLoggedIn(); // จะ redirect ไป login.php ถ้ายังไม่ login

require_once 'controller/MemberController.php';

$memberController = new MemberController($pdo);

// ตรวจสอบและดึงข้อมูลผู้ใช้จาก cookie
$currentUser = null;
$userInfo = [
    'member_id' => '',
    'email' => '',
    'first_name' => '',
    'last_name' => '',
    'tel' => '',
    'avatar_initials' => 'GU'
];

if (isset($_COOKIE['member_id']) && isset($_COOKIE['email'])) {
    $member_id = $_COOKIE['member_id'];
    $email = $_COOKIE['email'];

    // ดึงข้อมูลเต็มจากฐานข้อมูล
    try {
        $currentUser = $memberController->getById($member_id);

        if ($currentUser && $currentUser['email'] === $email) {
            $userInfo = [
                'member_id' => $currentUser['member_id'],
                'email' => $currentUser['email'],
                'firstname' => $currentUser['first_name'],
                'lastname' => $currentUser['last_name'],
                'tel' => $currentUser['phone'] ?? '',
                'avatar_initials' => substr($currentUser['first_name'], 0, 1) . substr($currentUser['last_name'], 0, 1)
            ];
        }
    } catch (Exception $e) {
        // หากเกิดข้อผิดพลาด redirect ไป login
        header('Location: login.php');
        exit();
    }
}

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
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
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

    .password-strength {
        margin-top: 8px;
        font-size: 13px;
        font-weight: 500;
        padding: 5px 0;
        transition: all 0.3s ease;
    }

    .strength-weak {
        color: #e74c3c;
    }

    .strength-medium {
        color: #f39c12;
    }

    .strength-strong {
        color: #27ae60;
    }

    .form-input.error {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.1) !important;
    }

    .form-input.success {
        border-color: #27ae60 !important;
        box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1) !important;
    }

    /* Password visibility toggle (optional enhancement) */
    .password-field {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 16px;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .password-toggle:hover {
        color: #333;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
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
        background: rgba(0, 0, 0, 0.6);
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
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
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
        background: rgba(255, 255, 255, 0.2);
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
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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

    .strength-weak {
        color: #e74c3c;
    }

    .strength-medium {
        color: #f39c12;
    }

    .strength-strong {
        color: #27ae60;
    }

    .loading-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 200px;
    }

    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid #f3f3f3;
        border-top: 4px solid #9b59b6;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .order-card {
        background: #f8f9fa;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .order-card:hover {
        border-color: #9b59b6;
        box-shadow: 0 2px 10px rgba(155, 89, 182, 0.1);
    }

    .order-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .order-number {
        font-weight: bold;
        color: #333;
        font-size: 16px;
    }

    .order-status {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-processing {
        background: #cce5ff;
        color: #004085;
    }

    .status-shipped {
        background: #e2e3e5;
        color: #383d41;
    }

    .status-delivered {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .order-details {
        color: #666;
        line-height: 1.6;
        margin-bottom: 15px;
    }

    .order-items {
        background: #fff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        border-left: 4px solid #9b59b6;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 500;
        color: #333;
    }

    .item-details {
        font-size: 14px;
        color: #666;
    }

    .item-price {
        font-weight: bold;
        color: #9b59b6;
    }

    .order-total {
        text-align: right;
        font-size: 18px;
        font-weight: bold;
        color: #333;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 2px solid #ddd;
    }

    .order-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-sm {
        padding: 8px 16px;
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-actions {
            width: 100%;
            justify-content: stretch;
        }

        .order-actions .btn {
            flex: 1;
            text-align: center;
        }
    }

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
        <input type="hidden" id="userId" value="<?php echo htmlspecialchars($userInfo['member_id']); ?>">
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="sidebar">
                <div class="profile-avatar">
                    <div class="avatar"><?php echo htmlspecialchars($userInfo['avatar_initials']); ?></div>
                    <div class="profile-name">
                        <?php echo htmlspecialchars($userInfo['firstname'] . ' ' . $userInfo['lastname']); ?></div>
                    <div class="profile-email"><?php echo htmlspecialchars($userInfo['email']); ?></div>
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
                        <a href="#" class="menu-link" data-section="logout" onclick="showLogoutConfirm(event)">ออกจากระบบ</a>
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
                                <input type="text" name="firstname" class="form-input"
                                    value="<?php echo htmlspecialchars($userInfo['firstname']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">นามสกุล</label>
                                <input type="text" name="lastname" class="form-input"
                                    value="<?php echo htmlspecialchars($userInfo['lastname']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">อีเมล</label>
                            <input type="email" name="email" class="form-input"
                                value="<?php echo htmlspecialchars($userInfo['email']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" name="tel" class="form-input"
                                value="<?php echo htmlspecialchars($userInfo['tel']); ?>" maxlength="10" required>
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
                                <button class="btn btn-secondary btn-sm"
                                    onclick="setDefault(1)">ตั้งเป็นค่าเริ่มต้น</button>
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
                            <div class="password-field">
                                <input type="password" class="form-input" id="currentPassword" name="currentPassword"
                                    required autocomplete="current-password">
                                <!-- <button type="button" class="password-toggle" onclick="togglePasswordVisibility('currentPassword')">👁️</button> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">รหัสผ่านใหม่</label>
                            <div class="password-field">
                                <input type="password" class="form-input" id="newPassword" name="newPassword" required
                                    autocomplete="new-password" minlength="8">
                                <!-- <button type="button" class="password-toggle" onclick="togglePasswordVisibility('newPassword')">👁️</button> -->
                            </div>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                            <div class="password-field">
                                <input type="password" class="form-input" id="confirmPassword" name="confirmPassword"
                                    required autocomplete="new-password" minlength="8">
                                <!-- <button type="button" class="password-toggle" onclick="togglePasswordVisibility('confirmPassword')">👁️</button> -->
                            </div>
                        </div>

                        <div class="form-group">
                            <small style="color: #666; font-size: 13px; line-height: 1.4;">
                                <strong>คำแนะนำ:</strong> รหัสผ่านที่ปลอดภัยควรมีความยาวอย่างน้อย 8 ตัวอักษร
                                และประกอบด้วยตัวอักษรพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข
                            </small>
                        </div>

                        <div style="display: flex; gap: 15px; align-items: center; margin-top: 30px;">
                            <button type="submit" class="btn btn-primary">เปลี่ยนรหัสผ่าน</button>
                            <button type="button" class="btn btn-secondary"
                                onclick="document.getElementById('passwordForm').reset(); document.getElementById('passwordStrength').textContent = '';">ล้างข้อมูล</button>
                        </div>
                    </form>
                </div>

                <!-- Orders Section -->
                <div class="content-section" id="orders">
                    <h2 class="section-title">ประวัติการสั่งซื้อ</h2>

                    <!-- Loading State -->
                    <div id="ordersLoading" class="loading-container" style="display: none;">
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <div class="loading-spinner"></div>
                            <p>กำลังโหลดข้อมูล...</p>
                        </div>
                    </div>

                    <!-- Orders List -->
                    <div id="ordersList" class="orders-list">
                        <!-- Orders will be loaded here -->
                    </div>

                    <!-- No Orders State -->
                    <div id="noOrdersState" style="display: none;">
                        <div style="text-align: center; padding: 40px; color: #666;">
                            <p>ยังไม่มีประวัติการสั่งซื้อ</p>
                            <p style="font-size: 14px; margin-top: 10px;">เริ่มช้อปปิ้งกับเราได้เลย!</p>
                            <a href="shop.php" class="btn btn-primary" style="margin-top: 15px;">เริ่มช้อปปิ้ง</a>
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

    <?php include("includes/MainFooter.php"); ?>

    <script src="assets/js/notification.js"></script>
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

            // เปลี่ยนไปยัง section ที่ต้องการ
            navigateToSection(sectionId);

            // อัพเดท URL โดยไม่ reload หน้า (optional)
            updateUrlSection(sectionId);
        });
    });

    // ฟังก์ชันสำหรับเปลี่ยน section
    function navigateToSection(sectionId) {
        // Remove active class from all links and sections
        document.querySelectorAll('.menu-link').forEach(l => l.classList.remove('active'));
        document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));

        // Add active class to clicked link and corresponding section
        const targetLink = document.querySelector(`.menu-link[data-section="${sectionId}"]`);
        const targetSection = document.getElementById(sectionId);

        if (targetLink && targetSection) {
            targetLink.classList.add('active');
            targetSection.classList.add('active');

            // Scroll to section เฉพาะกรณีที่มาจากภายนอก
            if (window.profileSectionFromExternal) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                window.profileSectionFromExternal = false;
            }
        }
    }

    // ฟังก์ชันอัพเดท URL (optional - เพื่อให้ URL สะท้อนหน้าที่กำลังดู)
    function updateUrlSection(sectionId) {
        const newUrl = `${window.location.pathname}?section=${sectionId}`;
        window.history.pushState({
            section: sectionId
        }, '', newUrl);
    }

    // ฟังก์ชันสำหรับจัดการ browser back/forward button
    window.addEventListener('popstate', function(event) {
        if (event.state && event.state.section) {
            navigateToSection(event.state.section);
        } else {
            // ถ้าไม่มี state กลับไปที่ profile section
            navigateToSection('profile');
        }
    });

    // ปรับปรุงฟังก์ชัน navigateToProfileSection
    function navigateToProfileSection(section) {
        if (window.location.pathname.includes('profile.php')) {
            window.profileSectionFromExternal = true;
            navigateToSection(section);
            updateUrlSection(section);
        } else {
            window.location.href = `profile.php?section=${section}`;
        }
    }

    // ฟังก์ชันตรวจสอบ URL parameter เมื่อโหลดหน้า
    function checkUrlSection() {
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');

        if (section && ['profile', 'addresses', 'password', 'orders'].includes(section)) {
            setTimeout(() => {
                window.profileSectionFromExternal = true;
                navigateToSection(section);

                // อัพเดท history state
                window.history.replaceState({
                    section: section
                }, '', window.location.pathname + '?section=' + section);
            }, 100);
        } else {
            // ถ้าไม่มี section หรือ section ไม่ถูกต้อง ให้แสดง profile
            window.history.replaceState({
                section: 'profile'
            }, '', window.location.pathname);
        }
    }

    // Global functions for external access
    window.openOrdersSection = function() {
        navigateToProfileSection('orders');
    };

    window.openProfileSection = function(section) {
        navigateToProfileSection(section);
    };

    window.openAddressesSection = function() {
        navigateToProfileSection('addresses');
    };

    window.openPasswordSection = function() {
        navigateToProfileSection('password');
    };

    // Profile form submission using FormData with email validation
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // รับข้อมูลจากฟอร์มด้วย FormData
        const formData = new FormData(this);

        // แปลง FormData เป็น Object
        const userData = {};
        formData.forEach((value, key) => {
            userData[key] = value;
        });

        // ตรวจสอบข้อมูลพื้นฐาน
        if (!userData.email || !userData.firstname || !userData.lastname) {
            showError('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }

        // ตรวจสอบรูปแบบอีเมล
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(userData.email)) {
            showError('กรุณากรอกอีเมลให้ถูกต้อง');
            return;
        }

        // ดึง user ID จาก session หรือ local storage
        const userId = getUserId();

        // แสดง loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'กำลังบันทึก...';
        submitBtn.disabled = true;

        // ส่งข้อมูลไปยัง API
        fetch(`controller/member_api.php?action=update&id=${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(userData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('บันทึกข้อมูลเรียบร้อยแล้ว');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    // จัดการข้อผิดพลาดตามประเภท
                    let errorMessage = data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';

                    if (data.error === 'EMAIL_EXISTS') {
                        // เน้นที่ช่อง email
                        const emailInput = document.querySelector('input[name="email"]');
                        emailInput.focus();
                        emailInput.style.borderColor = '#e74c3c';

                        setTimeout(() => {
                            emailInput.style.borderColor = '';
                        }, 3000);
                    }

                    showError(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            })
            .finally(() => {
                // คืนค่าปุ่ม
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });

    // ฟังก์ชันตรวจสอบ email แบบ real-time (ถ้าต้องการ)
    function setupEmailValidation() {
        const emailInput = document.querySelector('input[name="email"]');
        let timeoutId;

        emailInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const email = this.value.trim();

            // ลบ style error เก่า
            this.style.borderColor = '';

            if (email && email.includes('@')) {
                timeoutId = setTimeout(() => {
                    checkEmailAvailability(email);
                }, 800); // รอ 800ms หลังจากหยุดพิมพ์
            }
        });
    }

    function checkEmailAvailability(email) {
        const userId = getUserId();

        fetch(` ?action=check-email&email=${encodeURIComponent(email)}&exclude_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                const emailInput = document.querySelector('input[name="email"]');

                if (data.exists) {
                    emailInput.style.borderColor = '#e74c3c';
                    showEmailMessage('อีเมลนี้ถูกใช้แล้ว', 'error');
                } else {
                    emailInput.style.borderColor = '#27ae60';
                    showEmailMessage('อีเมลนี้สามารถใช้ได้', 'success');
                }
            })
            .catch(error => {
                console.error('Error checking email:', error);
            });
    }

    function showEmailMessage(message, type) {
        // ลบข้อความเก่า
        const existingMsg = document.querySelector('.email-message');
        if (existingMsg) {
            existingMsg.remove();
        }

        // สร้างข้อความใหม่
        const emailInput = document.querySelector('input[name="email"]');
        const messageDiv = document.createElement('div');
        messageDiv.className = `email-message ${type}`;
        messageDiv.style.fontSize = '12px';
        messageDiv.style.marginTop = '5px';
        messageDiv.style.color = type === 'error' ? '#e74c3c' : '#27ae60';
        messageDiv.textContent = message;

        emailInput.parentNode.appendChild(messageDiv);

        // ลบข้อความหลัง 3 วินาที
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.remove();
            }
        }, 3000);
    }

    // เรียกใช้ฟังก์ชันตรวจสอบ email แบบ real-time
    setupEmailValidation(); // เปิด comment หากต้องการใช้งาน

    // Password form submission และ password strength checker
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('currentPassword').value.trim();
        const newPassword = document.getElementById('newPassword').value.trim();
        const confirmPassword = document.getElementById('confirmPassword').value.trim();

        // ตรวจสอบข้อมูลพื้นฐาน
        if (!currentPassword || !newPassword || !confirmPassword) {
            showError('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }

        // ตรวจสอบความยาวรหัสผ่านใหม่
        if (newPassword.length < 8) {
            showError('รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร');
            document.getElementById('newPassword').focus();
            return;
        }

        // ตรวจสอบการยืนยันรหัสผ่าน
        if (newPassword !== confirmPassword) {
            showError('รหัสผ่านใหม่และการยืนยันไม่ตรงกัน');
            document.getElementById('confirmPassword').focus();
            return;
        }

        // ตรวจสอบว่ารหัสผ่านใหม่ไม่เหมือนเดิม
        if (currentPassword === newPassword) {
            showError('รหัสผ่านใหม่ต้องไม่เหมือนกับรหัสผ่านเดิม');
            document.getElementById('newPassword').focus();
            return;
        }

        // ตรวจสอบความแข็งแกร่งของรหัสผ่าน
        const passwordStrength = checkPasswordStrength(newPassword);
        if (passwordStrength === 'weak') {
            showConfirm(
                'รหัสผ่านที่คุณเลือกมีความปลอดภัยต่ำ คุณต้องการดำเนินการต่อหรือไม่?',
                () => {
                    submitPasswordChange(currentPassword, newPassword);
                }
            );
            return;
        }

        submitPasswordChange(currentPassword, newPassword);
    });

    function submitPasswordChange(currentPassword, newPassword) {
        // ดึง user ID
        const userId = getUserId();
        if (!userId) {
            showError('ไม่พบข้อมูลผู้ใช้');
            return;
        }

        // แสดง loading state
        const submitBtn = document.querySelector('#passwordForm button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'กำลังเปลี่ยนรหัสผ่าน...';
        submitBtn.disabled = true;

        // ส่งข้อมูลไปยัง API
        const requestData = {
            current_password: currentPassword,
            new_password: newPassword
        };

        fetch(`controller/member_api.php?action=change-password&id=${userId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว');
                    // ล้างฟอร์ม
                    document.getElementById('passwordForm').reset();
                    // ล้าง password strength indicator
                    document.getElementById('passwordStrength').textContent = '';
                    // ล้าง error styles
                    document.querySelectorAll('.form-input.error, .form-input.success').forEach(input => {
                        input.classList.remove('error', 'success');
                    });

                    // แสดง Modal ยืนยันการ logout
                    setTimeout(() => {
                        showPasswordChangeLogoutModal();
                    }, 1500);
                } else {
                    // จัดการข้อผิดพลาดตามประเภท
                    let errorMessage = data.message || 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน';

                    if (data.error === 'WRONG_PASSWORD') {
                        // เน้นที่ช่องรหัสผ่านเดิม
                        const currentPasswordInput = document.getElementById('currentPassword');
                        currentPasswordInput.classList.add('error');
                        currentPasswordInput.focus();

                        setTimeout(() => {
                            currentPasswordInput.classList.remove('error');
                        }, 3000);
                    } else if (data.error === 'WEAK_PASSWORD' || data.error === 'SAME_PASSWORD') {
                        // เน้นที่ช่องรหัสผ่านใหม่
                        const newPasswordInput = document.getElementById('newPassword');
                        newPasswordInput.classList.add('error');
                        newPasswordInput.focus();

                        setTimeout(() => {
                            newPasswordInput.classList.remove('error');
                        }, 3000);
                    }

                    showError(errorMessage);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            })
            .finally(() => {
                // คืนค่าปุ่ม
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    }

    // ฟังก์ชันแสดง Modal ยืนยันการ logout หลังเปลี่ยนรหัสผ่าน
    function showPasswordChangeLogoutModal() {
        // สร้าง Modal ใหม่หรือใช้ Modal ที่มีอยู่
        const existingModal = document.getElementById('passwordChangeLogoutModal');
        if (existingModal) {
            existingModal.remove();
        }

        const modalHTML = `
        <div class="logout-modal show" id="passwordChangeLogoutModal">
            <div class="logout-modal-content">
                <div class="logout-modal-header" style="background: linear-gradient(135deg, #27ae60, #229954);">
                    <div class="logout-icon">🔐</div>
                    <div class="logout-modal-title">เปลี่ยนรหัสผ่านสำเร็จ</div>
                    <div class="logout-modal-subtitle">เพื่อความปลอดภัย กรุณาเข้าสู่ระบบใหม่</div>
                </div>
                <div class="logout-modal-body">
                    <div class="logout-message">
                        รหัสผ่านของคุณได้รับการเปลี่ยนแปลงเรียบร้อยแล้ว<br>
                        เพื่อความปลอดภัย คุณจะถูกออกจากระบบอัตโนมัติ<br>
                        กรุณาเข้าสู่ระบบใหม่ด้วยรหัสผ่านที่เพิ่งเปลี่ยน
                    </div>
                    <div class="logout-user-info">
                        <div class="logout-user-name">${document.querySelector('.profile-name').textContent}</div>
                        <div class="logout-user-email">${document.querySelector('.profile-email').textContent}</div>
                    </div>
                    <div class="logout-modal-actions">
                        <button class="logout-btn logout-btn-cancel" onclick="closePasswordChangeLogoutModal()">อยู่ต่อ</button>
                        <button class="logout-btn logout-btn-confirm" onclick="performPasswordChangeLogout()">ออกจากระบบ</button>
                    </div>
                </div>
            </div>
        </div>
    `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';

        // Auto logout หลังจาก 10 วินาที
        let countdown = 10;
        const countdownInterval = setInterval(() => {
            const confirmBtn = document.querySelector('#passwordChangeLogoutModal .logout-btn-confirm');
            if (confirmBtn && countdown > 0) {
                confirmBtn.textContent = `ออกจากระบบ (${countdown})`;
                countdown--;
            } else {
                clearInterval(countdownInterval);
                if (document.getElementById('passwordChangeLogoutModal')) {
                    performPasswordChangeLogout();
                }
            }
        }, 1000);

        // เก็บ interval ID เพื่อสามารถยกเลิกได้
        window.passwordChangeCountdown = countdownInterval;
    }

    function closePasswordChangeLogoutModal() {
        const modal = document.getElementById('passwordChangeLogoutModal');
        if (modal) {
            modal.remove();
        }
        document.body.style.overflow = '';

        // ยกเลิก countdown
        if (window.passwordChangeCountdown) {
            clearInterval(window.passwordChangeCountdown);
            window.passwordChangeCountdown = null;
        }
    }

    function performPasswordChangeLogout() {
        // ยกเลิก countdown ก่อน
        if (window.passwordChangeCountdown) {
            clearInterval(window.passwordChangeCountdown);
            window.passwordChangeCountdown = null;
        }

        // แสดง loading state
        const confirmBtn = document.querySelector('#passwordChangeLogoutModal .logout-btn-confirm');
        if (confirmBtn) {
            confirmBtn.textContent = 'กำลังออกจากระบบ...';
            confirmBtn.disabled = true;
        }

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
                    closePasswordChangeLogoutModal();
                    showSuccess('ออกจากระบบเรียบร้อยแล้ว กรุณาเข้าสู่ระบบใหม่ด้วยรหัสผ่านที่เปลี่ยนแปลง');

                    // Redirect to login page with message
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showError('เกิดข้อผิดพลาด: ' + data.message);
                    // Reset button
                    if (confirmBtn) {
                        confirmBtn.textContent = 'ออกจากระบบ';
                        confirmBtn.disabled = false;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการออกจากระบบ');
                // Reset button
                if (confirmBtn) {
                    confirmBtn.textContent = 'ออกจากระบบ';
                    confirmBtn.disabled = false;
                }
            });
    }

    // ทำความสะอาด countdown เมื่อออกจากหน้า
    window.addEventListener('beforeunload', function() {
        if (window.passwordChangeCountdown) {
            clearInterval(window.passwordChangeCountdown);
            window.passwordChangeCountdown = null;
        }
    });

    // ฟังก์ชันตรวจสอบความแข็งแกร่งของรหัสผ่าน
    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = '';
        let className = '';

        // ตรวจสอบความยาว
        if (password.length >= 8) score += 1;
        if (password.length >= 12) score += 1;

        // ตรวจสอบประเภทตัวอักษร
        if (/[a-z]/.test(password)) score += 1; // พิมพ์เล็ก
        if (/[A-Z]/.test(password)) score += 1; // พิมพ์ใหญ่
        if (/[0-9]/.test(password)) score += 1; // ตัวเลข
        if (/[^A-Za-z0-9]/.test(password)) score += 1; // สัญลักษณ์

        // ตรวจสอบรูปแบบที่ซับซ้อน
        if (/(.)\1{2,}/.test(password)) score -= 1; // ตัวอักษรซ้ำติดกันมากกว่า 2 ตัว
        if (/123|abc|qwe|password|12345678/.test(password.toLowerCase())) score -= 2; // รูปแบบที่ง่ายเกินไป

        // กำหนดระดับและข้อความ
        if (score < 3) {
            feedback = 'รหัสผ่านอ่อนแอ - ควรใช้ตัวอักษรพิมพ์เล็ก พิมพ์ใหญ่ ตัวเลข และสัญลักษณ์';
            className = 'strength-weak';
            return 'weak';
        } else if (score < 5) {
            feedback = 'รหัสผ่านปานกลาง - ปลอดภัยระดับหนึ่ง';
            className = 'strength-medium';
            return 'medium';
        } else {
            feedback = 'รหัสผ่านแข็งแกร่ง - ปลอดภัยดี';
            className = 'strength-strong';
            return 'strong';
        }
    }

    // อัพเดท Password strength indicator แบบ real-time
    document.getElementById('newPassword').addEventListener('input', function() {
        const password = this.value.trim();
        const strengthElement = document.getElementById('passwordStrength');

        if (password.length === 0) {
            strengthElement.textContent = '';
            strengthElement.className = 'password-strength';
            this.classList.remove('error', 'success');
            return;
        }

        const strength = checkPasswordStrength(password);
        let feedback = '';
        let className = '';

        if (strength === 'weak') {
            feedback = 'รหัสผ่านอ่อนแอ - ควรใช้ตัวอักษรพิมพ์เล็ก พิมพ์ใหญ่ ตัวเลข และสัญลักษณ์';
            className = 'strength-weak';
            this.classList.remove('success');
            this.classList.add('error');
        } else if (strength === 'medium') {
            feedback = 'รหัสผ่านปานกลาง - ปลอดภัยระดับหนึ่ง';
            className = 'strength-medium';
            this.classList.remove('error', 'success');
        } else {
            feedback = 'รหัสผ่านแข็งแกร่ง - ปลอดภัยดี';
            className = 'strength-strong';
            this.classList.remove('error');
            this.classList.add('success');
        }

        strengthElement.textContent = feedback;
        strengthElement.className = `password-strength ${className}`;
    });

    // ตรวจสอบการยืนยันรหัสผ่านแบบ real-time
    document.getElementById('confirmPassword').addEventListener('input', function() {
        const newPassword = document.getElementById('newPassword').value.trim();
        const confirmPassword = this.value.trim();

        if (confirmPassword.length === 0) {
            this.classList.remove('error', 'success');
            return;
        }

        if (newPassword === confirmPassword) {
            this.classList.remove('error');
            this.classList.add('success');
        } else {
            this.classList.remove('success');
            this.classList.add('error');
        }
    });

    // ตรวจสอบรหัสผ่านเดิมไม่เหมือนใหม่
    document.getElementById('newPassword').addEventListener('blur', function() {
        const currentPassword = document.getElementById('currentPassword').value.trim();
        const newPassword = this.value.trim();

        if (newPassword && currentPassword && newPassword === currentPassword) {
            this.classList.add('error');
            showError('รหัสผ่านใหม่ต้องไม่เหมือนกับรหัสผ่านเดิม');
        }
    });

    // ล้าง error state เมื่อเริ่มพิมพ์ใหม่
    document.querySelectorAll('#passwordForm input[type="password"]').forEach(input => {
        input.addEventListener('focus', function() {
            this.classList.remove('error');
        });
    });

    // ฟังก์ชันแสดง/ซ่อน password (ถ้าต้องการเพิ่ม)
    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const button = input.nextElementSibling;

        if (input.type === 'password') {
            input.type = 'text';
            button.innerHTML = '🙈';
        } else {
            input.type = 'password';
            button.innerHTML = '👁️';
        }
    }

    // เคลียร์ฟอร์มเมื่อเปลี่ยนหน้า
    document.querySelectorAll('.menu-link').forEach(link => {
        link.addEventListener('click', function() {
            if (this.getAttribute('data-section') !== 'password') {
                // ล้างฟอร์มรหัสผ่านเมื่อเปลี่ยนหน้า
                document.getElementById('passwordForm').reset();
                document.getElementById('passwordStrength').textContent = '';
                document.querySelectorAll('#passwordForm .form-input').forEach(input => {
                    input.classList.remove('error', 'success');
                });
            }
        });
    });


    // Global variables
    let addresses = [];
    let editingAddressIndex = -1;

    // Initialize addresses when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadAddresses();
        checkUrlSection();
    });

    // Load addresses from API
    function loadAddresses() {
        const userId = getUserId();
        if (!userId) {
            console.error('User ID not found');
            return;
        }

        fetch(`controller/member_api.php?action=addresses&member_id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    addresses = data.data || [];
                    renderAddresses();
                } else {
                    console.error('Failed to load addresses:', data.message);
                    showError('ไม่สามารถโหลดข้อมูลที่อยู่ได้');
                }
            })
            .catch(error => {
                console.error('Error loading addresses:', error);
                showError('เกิดข้อผิดพลาดในการโหลดข้อมูลที่อยู่');
            });
    }

    // Render addresses list
    function renderAddresses() {
        const addressList = document.querySelector('.address-list');
        addressList.innerHTML = '';

        if (addresses.length === 0) {
            addressList.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <p>ยังไม่มีที่อยู่ในระบบ</p>
                <p style="font-size: 14px; margin-top: 10px;">คลิก "เพิ่มที่อยู่ใหม่" เพื่อเพิ่มที่อยู่จัดส่งของคุณ</p>
            </div>
        `;
            return;
        }

        addresses.forEach((address, index) => {
            const addressCard = document.createElement('div');
            addressCard.className = `address-card${address.is_default == 1 ? ' default' : ''}`;

            addressCard.innerHTML = `
            <div class="address-header">
                <div class="address-type">${address.address_name}</div>
                ${address.is_default == 1 ? '<div class="default-badge">ค่าเริ่มต้น</div>' : ''}
            </div>
            <div class="address-details">
                <strong>${address.recipient_name}</strong><br>
                ${address.address_line}<br>
                ตำบล${address.sub_district} อำเภอ${address.district} จังหวัด${address.province} ${address.postal_code}<br>
                โทร: ${address.recipient_phone}
            </div>
            <div class="address-actions">
                ${address.is_default != 1 ? `<button class="btn btn-secondary btn-sm set-default-btn" data-address-id="${address.address_id}">ตั้งเป็นค่าเริ่มต้น</button>` : ''}
                <button class="btn btn-secondary btn-sm edit-btn" data-index="${index}">แก้ไข</button>
                <button class="btn btn-danger btn-sm delete-btn" data-address-id="${address.address_id}">ลบ</button>
            </div>
        `;

            addressList.appendChild(addressCard);
        });

        // เพิ่ม Event Listeners หลังจากสร้าง HTML เสร็จแล้ว
        setupAddressEventListeners();
    }

    // Show address modal
    function showAddressModal(index = -1) {
        editingAddressIndex = index;
        const modal = document.getElementById('addressModal');
        const form = document.getElementById('addressForm');

        if (index >= 0) {
            // Edit mode - populate form with existing data
            const address = addresses[index];
            document.getElementById('addressName').value = address.address_name || '';
            document.getElementById('recipientName').value = address.recipient_name || '';
            document.getElementById('recipientPhone').value = address.recipient_phone || '';
            document.getElementById('addressLine').value = address.address_line || '';
            document.getElementById('subDistrict').value = address.sub_district || '';
            document.getElementById('district').value = address.district || '';
            document.getElementById('province').value = address.province || '';
            document.getElementById('postalCode').value = address.postal_code || '';
            document.getElementById('setAsDefault').checked = address.is_default == 1;
            document.querySelector('.modal-title').textContent = 'แก้ไขที่อยู่';
        } else {
            // Add mode - reset form
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

    // Delete address
    function deleteAddress(buttonElement, addressId) {
        console.log('deleteAddress called with:', buttonElement, addressId); // Debug log

        showConfirm('คุณต้องการลบที่อยู่นี้หรือไม่?', () => {
            // ใช้ buttonElement ที่ส่งเข้ามา
            const deleteBtn = buttonElement;
            const originalText = deleteBtn.textContent;
            deleteBtn.textContent = 'กำลังลบ...';
            deleteBtn.disabled = true;

            console.log('Deleting address ID:', addressId); // Debug log

            fetch(`controller/member_api.php?action=delete-address&address_id=${addressId}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    console.log('Delete response status:', response.status); // Debug log
                    return response.json();
                })
                .then(data => {
                    console.log('Delete response data:', data); // Debug log
                    if (data.success) {
                        showSuccess('ลบที่อยู่เรียบร้อยแล้ว');
                        loadAddresses(); // Reload addresses
                    } else {
                        showError(data.message || 'ไม่สามารถลบที่อยู่ได้');
                    }
                })
                .catch(error => {
                    console.error('Error deleting address:', error);
                    showError('เกิดข้อผิดพลาดในการลบที่อยู่');
                })
                .finally(() => {
                    // คืนค่าปุ่มเดิม
                    deleteBtn.textContent = originalText;
                    deleteBtn.disabled = false;
                });
        });
    }

    // Set default address
    function setDefaultAddress(buttonElement, addressId) {
        console.log('setDefaultAddress called with:', buttonElement, addressId); // Debug log

        // ใช้ buttonElement ที่ส่งเข้ามา
        const setBtn = buttonElement;
        const originalText = setBtn.textContent;
        setBtn.textContent = 'กำลังตั้งค่า...';
        setBtn.disabled = true;

        // หา address ที่ต้องการอัพเดท
        const address = addresses.find(addr => addr.address_id == addressId);
        if (!address) {
            console.error('Address not found for ID:', addressId); // Debug log
            showError('ไม่พบข้อมูลที่อยู่');
            setBtn.textContent = originalText;
            setBtn.disabled = false;
            return;
        }

        console.log('Setting default for address:', address); // Debug log

        const updateData = {
            recipient_name: address.recipient_name,
            recipient_phone: address.recipient_phone,
            address_name: address.address_name,
            address_line: address.address_line,
            sub_district: address.sub_district,
            district: address.district,
            province: address.province,
            postal_code: address.postal_code,
            is_default: 1
        };

        console.log('Update data:', updateData); // Debug log

        fetch(`controller/member_api.php?action=update-address&address_id=${addressId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updateData)
            })
            .then(response => {
                console.log('Update response status:', response.status); // Debug log
                return response.json();
            })
            .then(data => {
                console.log('Update response data:', data); // Debug log
                if (data.success) {
                    showSuccess('ตั้งที่อยู่เริ่มต้นเรียบร้อยแล้ว');
                    loadAddresses(); // Reload addresses
                } else {
                    showError(data.message || 'ไม่สามารถตั้งที่อยู่เริ่มต้นได้');
                }
            })
            .catch(error => {
                console.error('Error setting default address:', error);
                showError('เกิดข้อผิดพลาดในการตั้งที่อยู่เริ่มต้น');
            })
            .finally(() => {
                // คืนค่าปุ่มเดิม
                setBtn.textContent = originalText;
                setBtn.disabled = false;
            });
    }

    // Address form submission
    document.getElementById('addressForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = {
            member_id: getUserId(),
            recipient_name: document.getElementById('recipientName').value.trim(),
            recipient_phone: document.getElementById('recipientPhone').value.trim(),
            address_name: document.getElementById('addressName').value.trim(),
            address_line: document.getElementById('addressLine').value.trim(),
            sub_district: document.getElementById('subDistrict').value.trim(),
            district: document.getElementById('district').value.trim(),
            province: document.getElementById('province').value.trim(),
            postal_code: document.getElementById('postalCode').value.trim(),
            is_default: document.getElementById('setAsDefault').checked ? 1 : 0
        };

        // Validate required fields
        const requiredFields = ['recipient_name', 'recipient_phone', 'address_name', 'address_line',
            'sub_district', 'district', 'province', 'postal_code'
        ];

        for (let field of requiredFields) {
            if (!formData[field]) {
                showError('กรุณากรอกข้อมูลให้ครบถ้วน');
                document.getElementById(field.replace('_', '')).focus();
                return;
            }
        }

        // Validate phone number format (basic)
        const phonePattern = /^[0-9-+().\s]{10}$/;
        if (!phonePattern.test(formData.recipient_phone)) {
            showError('กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง');
            document.getElementById('recipientPhone').focus();
            return;
        }

        // Validate postal code (5 digits for Thailand)
        const postalPattern = /^[0-9]{5}$/;
        if (!postalPattern.test(formData.postal_code)) {
            showError('กรุณากรอกรหัสไปรษณีย์ 5 หลัก');
            document.getElementById('postalCode').focus();
            return;
        }

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = editingAddressIndex >= 0 ? 'กำลังอัพเดท...' : 'กำลังบันทึก...';
        submitBtn.disabled = true;

        let apiUrl, method;
        if (editingAddressIndex >= 0) {
            // Update existing address
            const addressId = addresses[editingAddressIndex].address_id;
            apiUrl = `controller/member_api.php?action=update-address&address_id=${addressId}`;
            method = 'PUT';
            // Remove member_id for update
            delete formData.member_id;
        } else {
            // Create new address
            apiUrl = `controller/member_api.php?action=create-address`;
            method = 'POST';
        }

        fetch(apiUrl, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess(editingAddressIndex >= 0 ? 'อัพเดทที่อยู่เรียบร้อยแล้ว' :
                        'เพิ่มที่อยู่เรียบร้อยแล้ว');
                    loadAddresses(); // Reload addresses
                    closeAddressModal();
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            })
            .catch(error => {
                console.error('Error saving address:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    });

    // Utility function to get user ID
    function getUserId() {
        const userIdInput = document.getElementById('userId');
        if (userIdInput) {
            return userIdInput.value;
        }
        return null;
    }

    // Close modal when clicking outside
    document.getElementById('addressModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddressModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('addressModal').classList.contains('show')) {
            closeAddressModal();
        }
    });

    // Auto-format postal code (Thailand format)
    document.getElementById('postalCode').addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        if (value.length > 5) {
            value = value.substring(0, 5); // Limit to 5 digits
        }
        e.target.value = value;
    });

    // Auto-format phone number
    document.getElementById('recipientPhone').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d-+().\s]/g,
            ''); // Allow only digits, dash, plus, parentheses, dot, space
        if (value.length > 10) {
            value = value.substring(0, 10); // Limit to 10 digits
        }
        e.target.value = value;
    });

    // Real-time validation feedback
    function setupAddressValidation() {
        const requiredInputs = ['addressName', 'recipientName', 'recipientPhone', 'addressLine',
            'subDistrict', 'district', 'province', 'postalCode'
        ];

        requiredInputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if (input) {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.style.borderColor = '#e74c3c';
                    } else {
                        this.style.borderColor = '';
                    }
                });

                input.addEventListener('input', function() {
                    if (this.style.borderColor === 'rgb(231, 76, 60)') { // If was red
                        this.style.borderColor = '';
                    }
                });
            }
        });
    }

    // Initialize validation
    setupAddressValidation();

    function showLogoutConfirm(event) {
        event.preventDefault();
        
        // ใช้ showConfirm จาก notification.js
        if (typeof showConfirm === 'function') {
            showConfirm(
                'คุณต้องการออกจากระบบหรือไม่?',
                function() {
                    // กดตกลง - ทำการ logout
                    performLogout();
                },
                function() {
                    // กดยกเลิก - ไม่ทำอะไร
                    console.log('Logout cancelled');
                }
            );
        } else {
            // fallback ถ้าไม่มี notification system
            if (confirm('คุณต้องการออกจากระบบหรือไม่?')) {
                performLogout();
            }
        }
    }

    function performLogout() {
        // แสดง loading notification
        let hideLoading;
        if (typeof showLoading === 'function') {
            hideLoading = showLoading('กำลังออกจากระบบ...');
        }

        // ส่งคำขอ logout
        fetch('controller/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=logout'
            })
            .then(response => response.json())
            .then(data => {
                // ซ่อน loading
                if (hideLoading) {
                    hideLoading();
                }

                if (data.success) {
                    // แสดงข้อความสำเร็จ
                    if (typeof showSuccess === 'function') {
                        showSuccess('ออกจากระบบเรียบร้อยแล้ว', 2000);
                    }

                    // Redirect หลังจาก logout สำเร็จ
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 1500);
                } else {
                    // แสดงข้อความผิดพลาด
                    if (typeof showError === 'function') {
                        showError(data.message || 'ไม่สามารถออกจากระบบได้');
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถออกจากระบบได้'));
                    }
                }
            })
            .catch(error => {
                // ซ่อน loading
                if (hideLoading) {
                    hideLoading();
                }

                console.error('Logout error:', error);

                if (typeof showError === 'function') {
                    showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                } else {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                }
            });
    }

    // Global variables for orders
    let userOrders = [];

    // Load user orders when page loads or when orders section is accessed
    function loadUserOrders() {
        const userId = getUserId();
        if (!userId) {
            console.error('User ID not found');
            showOrdersError('ไม่พบข้อมูลผู้ใช้');
            return;
        }

        // Show loading state
        showOrdersLoading(true);

        fetch(`controller/order_api.php?action=member-orders&member_id=${userId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Orders response:', data); // Debug log
                if (data.success) {
                    userOrders = data.data || [];
                    renderUserOrders();
                } else {
                    console.error('Failed to load orders:', data.message);
                    showOrdersError(data.message || 'ไม่สามารถโหลดประวัติการสั่งซื้อได้');
                }
            })
            .catch(error => {
                console.error('Error loading orders:', error);
                showOrdersError('เกิดข้อผิดพลาดในการโหลดข้อมูล กรุณาลองใหม่อีกครั้ง');
            })
            .finally(() => {
                showOrdersLoading(false);
            });
    }

    // Show/hide loading state
    function showOrdersLoading(show) {
        const loadingElement = document.getElementById('ordersLoading');
        const ordersListElement = document.getElementById('ordersList');
        const noOrdersElement = document.getElementById('noOrdersState');

        if (loadingElement) {
            loadingElement.style.display = show ? 'block' : 'none';
        }
        if (ordersListElement) {
            ordersListElement.style.display = show ? 'none' : 'block';
        }
        if (noOrdersElement) {
            noOrdersElement.style.display = 'none';
        }
    }

    // Show error state
    function showOrdersError(message) {
        const ordersListElement = document.getElementById('ordersList');
        if (ordersListElement) {
            ordersListElement.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #666;">
                <p style="color: #e74c3c; font-weight: 500;">⚠️ ${message}</p>
                <button class="btn btn-secondary" onclick="loadUserOrders()" style="margin-top: 15px;">
                    ลองใหม่อีกครั้ง
                </button>
            </div>
        `;
        }
    }

    // Render user orders
    function renderUserOrders() {
        const ordersListElement = document.getElementById('ordersList');
        const noOrdersElement = document.getElementById('noOrdersState');

        if (!ordersListElement) {
            console.error('Orders list element not found');
            return;
        }

        if (userOrders.length === 0) {
            ordersListElement.style.display = 'none';
            if (noOrdersElement) {
                noOrdersElement.style.display = 'block';
            }
            return;
        }

        ordersListElement.innerHTML = '';
        ordersListElement.style.display = 'block';
        if (noOrdersElement) {
            noOrdersElement.style.display = 'none';
        }

        userOrders.forEach(order => {
            const orderCard = createOrderCard(order);
            ordersListElement.appendChild(orderCard);
        });
    }

    // Create order card element
    function createOrderCard(order) {
        const orderCard = document.createElement('div');
        orderCard.className = 'order-card';

        // Format dates
        const orderDate = formatThaiDate(order.create_at);
        const statusText = getOrderStatusText(order.order_status);
        const statusClass = getOrderStatusClass(order.order_status);

        const totalItems = order.item_count;

        // Format price
        const totalAmount = parseFloat(order.total_amount || 0).toLocaleString('th-TH');

        orderCard.innerHTML = `
                <div class="order-header">
                    <div class="order-number">คำสั่งซื้อ #${order.order_number}</div>
                    <span class="order-status ${statusClass}">${statusText}</span>
                </div>
                <div class="order-details">
                    วันที่สั่งซื้อ: ${orderDate}<br>
                    จำนวน: ${totalItems} รายการ<br>
                    ยอดรวม: ฿${totalAmount}
                    ${order.payment_due_date ? `<br>กำหนดชำระเงิน: ${formatThaiDate(order.payment_due_date)}` : ''}
                </div>
                ${order.items && order.items.length > 0 ? createOrderItemsHTML(order.items) : ''}
                <div class="order-actions">
                    <button class="btn btn-secondary btn-sm" onclick="viewOrderDetails('${order.order_number}')">
                        ดูรายละเอียด
                    </button>
                    ${createOrderActionButtons(order)}
                </div>
            `;

        return orderCard;
    }

    // Create order items HTML
    function createOrderItemsHTML(items) {
        if (!items || items.length === 0) return '';

        const itemsHTML = items.slice(0, 3).map(item => `
                    <div class="order-item">
                        <div>
                            <div class="item-name">${item.shoe_name || 'สินค้า'}</div>
                            <div class="item-details">
                                ไซส์: ${item.size || 'ไม่ระบุ'} | จำนวน: ${item.quantity || 1}
                                ${item.color ? ` | สี: ${item.color}` : ''}
                            </div>
                        </div>
                        <div class="item-price">฿${parseFloat(item.price || 0).toLocaleString('th-TH')}</div>
                    </div>
                `).join('');

        const moreItemsText = items.length > 3 ?
            `<div style="text-align: center; padding: 8px; color: #666; font-size: 14px;">และอีก ${items.length - 3} รายการ</div>` :
            '';

        return `
        <div class="order-items">
            ${itemsHTML}
            ${moreItemsText}
            <div class="order-total">
                รวมทั้งหมด: ฿${parseFloat(items.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity)), 0)).toLocaleString('th-TH')}
            </div>
        </div>
    `;
    }

    // Create action buttons based on order status
    function createOrderActionButtons(order) {
        let buttons = '';

        switch (parseInt(order.order_status)) {
            case 1: // รอชำระเงิน
                buttons +=
                    `<button class="btn btn-primary btn-sm" onclick="payForOrder('${order.order_number}')">ชำระเงิน</button>`;
                buttons +=
                    `<button class="btn btn-danger btn-sm" onclick="cancelOrder('${order.order_number}')">ยกเลิก</button>`;
                break;
            case 2: // ชำระแล้ว / รอการตรวจสอบ
                buttons +=
                    `<button class="btn btn-secondary btn-sm" onclick="viewPaymentStatus('${order.order_number}')">สถานะการชำระเงิน</button>`;
                break;
            case 3: // กำลังส่ง
                if (order.tracking_number) {
                    buttons +=
                        `<button class="btn btn-primary btn-sm" onclick="trackPackage('${order.tracking_number}')">ติดตามพัสดุ</button>`;
                }
                buttons +=
                    `<button class="btn btn-secondary btn-sm" onclick="contactSupport('${order.order_number}')">ติดต่อเรา</button>`;
                break;
            case 4: // จัดส่งสำเร็จ
                buttons +=
                    `<button class="btn btn-secondary btn-sm" onclick="reorderItems('${order.order_number}')">สั่งซื้อซ้ำ</button>`;
                break;
            case 5: // ยกเลิกคำสั่งซื้อ
                buttons +=
                    `<button class="btn btn-secondary btn-sm" onclick="reorderItems('${order.order_number}')">สั่งซื้อใหม่</button>`;
                buttons +=
                    `<button class="btn btn-secondary btn-sm" onclick="viewCancelReason('${order.order_number}')">เหตุผลที่ยกเลิก</button>`;
                break;
        }

        return buttons;
    }

    // Get order status text
    function getOrderStatusText(statusId) {
        const statusMap = {
            1: 'รอชำระเงิน',
            2: 'ชำระแล้ว / รอการตรวจสอบ',
            3: 'กำลังส่ง',
            4: 'จัดส่งสำเร็จ',
            5: 'ยกเลิกคำสั่งซื้อ'
        };
        return statusMap[statusId] || 'ไม่ทราบสถานะ';
    }

    // Get order status CSS class
    function getOrderStatusClass(statusId) {
        const statusClasses = {
            1: 'status-pending',
            2: 'status-confirmed',
            3: 'status-shipped',
            4: 'status-delivered',
            5: 'status-cancelled'
        };
        return statusClasses[statusId] || 'status-pending';
    }

    // Format Thai date
    function formatThaiDate(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        const thaiMonths = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
            'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
            'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        return `${date.getDate()} ${thaiMonths[date.getMonth()]} ${date.getFullYear() + 543}`;
    }

    // Order action functions
    function viewOrderDetails(orderNumber) {
        // Redirect to order status page
        window.open(`orderstatus.php?order=${orderNumber}`, '_blank');
    }

    function payForOrder(orderNumber) {
        // Redirect to payment page
        window.location.href = `order-payment.php?order=${orderNumber}`;
    }

    function cancelOrder(orderNumber) {
        // ใช้ showConfirm แทน confirm แบบเก่า
        showConfirm(
            'คุณต้องการยกเลิกคำสั่งซื้อนี้หรือไม่?',
            function() {
                // เมื่อกดตกลง
                // Find order
                const order = userOrders.find(o => o.order_number === orderNumber);
                if (!order) {
                    showError('ไม่พบคำสั่งซื้อ');
                    return;
                }

                // Show loading
                const cancelBtn = event.target;
                const originalText = cancelBtn.textContent;
                cancelBtn.textContent = 'กำลังยกเลิก...';
                cancelBtn.disabled = true;

                // แสดง loading notification
                const hideLoading = showLoading('กำลังยกเลิกคำสั่งซื้อ...');

                fetch(`controller/order_api.php?action=cancel&order_id=${order.order_id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            reason: 'ยกเลิกโดยลูกค้า',
                            changed_by: 'ลูกค้า'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        hideLoading(); // ปิด loading

                        if (data.success) {
                            showSuccess('ยกเลิกคำสั่งซื้อเรียบร้อยแล้ว');
                            loadUserOrders(); // Reload orders
                        } else {
                            showError(data.message || 'ไม่สามารถยกเลิกคำสั่งซื้อได้');
                        }
                    })
                    .catch(error => {
                        console.error('Error cancelling order:', error);
                        hideLoading(); // ปิด loading
                        showError('เกิดข้อผิดพลาดในการยกเลิกคำสั่งซื้อ');
                    })
                    .finally(() => {
                        cancelBtn.textContent = originalText;
                        cancelBtn.disabled = false;
                    });
            },
            function() {
                // เมื่อกดยกเลิก - ไม่ต้องทำอะไร
                showInfo('การยกเลิกคำสั่งซื้อถูกยกเลิก');
            }
        );
    }

    function viewPaymentStatus(orderNumber) {
        window.open(`payment-status.php?order=${orderNumber}`, '_blank');
    }

    function trackPackage(trackingNumber) {
        // Open tracking page or external tracking service
        window.open(`tracking.php?track=${trackingNumber}`, '_blank');
    }

    function reviewOrder(orderNumber) {
        window.location.href = `review.php?order=${orderNumber}`;
    }

    function reorderItems(orderNumber) {
        // ใช้ showConfirm แทน confirm แบบเก่า
        showConfirm(
            'คุณต้องการสั่งซื้อสินค้าเหล่านี้ซ้ำหรือไม่?',
            function() {
                // เมื่อกดตกลง
                showLoading('กำลังเตรียมข้อมูลสำหรับการสั่งซื้อซ้ำ...');

                setTimeout(() => {
                    window.location.href = `reorder.php?order=${orderNumber}`;
                }, 1000);
            },
            function() {
                // เมื่อกดยกเลิก
                showInfo('การสั่งซื้อซ้ำถูกยกเลิก');
            }
        );
    }

    function contactSupport(orderNumber) {
        window.open(`contact.php?order=${orderNumber}&subject=สอบถามเกี่ยวกับการจัดส่ง`, '_blank');
    }

    function viewCancelReason(orderNumber) {
        // สามารถแสดงใน modal หรือ redirect ไปหน้าใหม่
        window.open(`orderstatus.php?order=${orderNumber}#cancel-reason`, '_blank');
    }

    // Update the original menu navigation to load orders when orders section is clicked
    // แก้ไขส่วนใน event listener ที่มีอยู่แล้ว
    document.querySelectorAll('.menu-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            const sectionId = this.getAttribute('data-section');

            // Check if logout is clicked
            if (sectionId === 'logout') {
                showLogoutModal();
                return;
            }

            // Load orders when orders section is accessed
            if (sectionId === 'orders' && userOrders.length === 0) {
                //const hideLoading = showLoading('กำลังโหลดคำสั่งซื้อ...');

                loadUserOrders();
            }

            // เปลี่ยนไปยัง section ที่ต้องการ
            navigateToSection(sectionId);

            // อัพเดท URL โดยไม่ reload หน้า (optional)
            updateUrlSection(sectionId);
        });
    });

    // Auto-load orders when page loads if orders section is active
    document.addEventListener('DOMContentLoaded', function() {
        // ... existing code ...

        // Check if orders section should be shown on load
        const urlParams = new URLSearchParams(window.location.search);
        const section = urlParams.get('section');

        if (section === 'orders') {
            setTimeout(() => {
                //const hideLoading = showLoading('กำลังโหลดคำสั่งซื้อ...');

                loadUserOrders()
                    .then(() => {
                        hideLoading();
                    })
                    .catch(() => {
                        hideLoading();
                        showError('ไม่สามารถโหลดข้อมูลคำสั่งซื้อได้');
                    });
            }, 500);
        }

        // ... existing code ...
    });

    // Export functions for external use
    window.loadUserOrders = loadUserOrders;
    window.viewOrderDetails = viewOrderDetails;

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

    // ฟังก์ชันจัดการ Event Listeners สำหรับ Address Management
    function setupAddressEventListeners() {
        // Event Listener สำหรับปุ่มตั้งค่าเริ่มต้น
        document.querySelectorAll('.set-default-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const addressId = this.getAttribute('data-address-id');

                // แสดง loading
                const hideLoading = showLoading('กำลังตั้งค่าที่อยู่เริ่มต้น...');

                setDefaultAddress(this, addressId)
                    .then(() => {
                        hideLoading();
                        showSuccess('ตั้งค่าที่อยู่เริ่มต้นเรียบร้อยแล้ว');
                    })
                    .catch(() => {
                        hideLoading();
                        showError('ไม่สามารถตั้งค่าที่อยู่เริ่มต้นได้');
                    });
            });
        });

        // Event Listener สำหรับปุ่มแก้ไข
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const index = parseInt(this.getAttribute('data-index'));
                editAddress(index);
            });
        });

        // Event Listener สำหรับปุ่มลบ
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const addressId = this.getAttribute('data-address-id');

                // ใช้ showConfirm แทน confirm แบบเก่า
                showConfirm(
                    'คุณต้องการลบที่อยู่นี้หรือไม่?',
                    function() {
                        // เมื่อกดตกลง
                        const hideLoading = showLoading('กำลังลบที่อยู่...');

                        deleteAddress(button, addressId)
                            .then(() => {
                                hideLoading();
                                showSuccess('ลบที่อยู่เรียบร้อยแล้ว');
                            })
                            .catch(() => {
                                hideLoading();
                                showError('ไม่สามารถลบที่อยู่ได้');
                            });
                    },
                    function() {
                        // เมื่อกดยกเลิก
                        showInfo('การลบที่อยู่ถูกยกเลิก');
                    }
                );
            });
        });
    }

    // เพิ่มฟังก์ชันสำหรับแสดง notification เมื่อมีการเปลี่ยนแปลงสถานะคำสั่งซื้อ
    function notifyOrderStatusChange(orderNumber, newStatus) {
        let message = '';
        let type = 'info';

        switch (newStatus) {
            case 'confirmed':
                message = `คำสั่งซื้อ #${orderNumber} ได้รับการยืนยันแล้ว`;
                type = 'success';
                break;
            case 'preparing':
                message = `คำสั่งซื้อ #${orderNumber} กำลังเตรียมสินค้า`;
                type = 'info';
                break;
            case 'shipped':
                message = `คำสั่งซื้อ #${orderNumber} ได้จัดส่งแล้ว`;
                type = 'success';
                break;
            case 'delivered':
                message = `คำสั่งซื้อ #${orderNumber} ส่งถึงแล้ว`;
                type = 'success';
                break;
            case 'cancelled':
                message = `คำสั่งซื้อ #${orderNumber} ถูกยกเลิก`;
                type = 'warning';
                break;
            default:
                message = `คำสั่งซื้อ #${orderNumber} มีการเปลี่ยนแปลงสถานะ`;
        }

        showNotification(message, type);
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        renderAddresses();
        setupAddressEventListeners();

        // แสดงข้อความต้อนรับ
        setTimeout(() => {
            showInfo('ยินดีต้อนรับสู่หน้าโปรไฟล์ผู้ใช้งาน', 3000);
        }, 1000);
    });
    </script>
</body>

</html>