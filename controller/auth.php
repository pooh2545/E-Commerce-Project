<?php
// auth.php - Authentication handler (แก้ไขแล้ว)
header('Content-Type: application/json; charset=utf-8');

require_once 'config.php';
require_once 'MemberController.php';

$memberController = new MemberController($pdo);

// Handle different actions
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        handleLogin($memberController);
        break;
    case 'signup':
        handleSignup($memberController);
        break;
    case 'logout':
        handleLogout();
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

function handleLogin($memberController)
{
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username and password are required']);
        return;
    }

    $member = $memberController->login($email, $password);

    if ($member) {
        session_name('customer_session');
        session_start();

        $expire_time = time() + (1 * 24 * 60 * 60); // 1 วัน

        // ตั้ง cookie แบบที่ JavaScript เข้าถึงได้ (เอา httponly ออก)
        setcookie('member_id', $member['member_id'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']), // ใช้เฉพาะ HTTPS
            'httponly' => false, // **แก้ไข: ให้ JavaScript เข้าถึงได้**
            'samesite' => 'Strict' // ป้องกัน CSRF
        ]);

        setcookie('email', $member['email'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => false, // **แก้ไข: ให้ JavaScript เข้าถึงได้**
            'samesite' => 'Strict'
        ]);

        setcookie('first_name', $member['first_name'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => false, // **แก้ไข: ให้ JavaScript เข้าถึงได้**
            'samesite' => 'Strict'
        ]);
        
        setcookie('last_name', $member['last_name'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => false, // **แก้ไข: ให้ JavaScript เข้าถึงได้**
            'samesite' => 'Strict'
        ]);

        echo json_encode([
            'success' => true,
            'message' => ' เข้าสู่ระบบสำเร็จ',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
    }
}

function handleSignup($memberController)
{
    $email = $_POST['email'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Validation
    if (empty($email) || empty($firstname) || empty($lastname) || empty($phone) || empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        return;
    }

    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน']);
        return;
    }

    // Check if email already exists
    $existingMember = $memberController->getAll();
    foreach ($existingMember as $member) {
        if ($member['email'] === $email) {
            echo json_encode(['success' => false, 'message' => 'อีเมลนี้ถูกใช้งานแล้ว']);
            return;
        }
    }

    // Password strength validation
    if (!validatePassword($password)) {
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านต้องตรงตามเงื่อนไขทั้งหมด']);
        return;
    }

    // Create member
    try {
        $result = $memberController->create($email, $firstname, $lastname, $phone, $password);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'สมัครสมาชิกสำเร็จ',
                'redirect' => 'login.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create account. Please try again.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating account: ' . $e->getMessage()]);
    }
}

function handleLogout()
{
    // ตรวจสอบสถานะ session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    // ลบข้อมูล session
    $_SESSION = array();

    // ลบ session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // ทำลาย session
    session_destroy();

    // ลบ custom cookies - **แก้ไข: เพิ่ม first_name และ last_name**
    $cookies_to_delete = ['customer_session', 'member_id', 'email', 'first_name', 'last_name', 'login_time'];
    foreach ($cookies_to_delete as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/');
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'ออกจากระบบสำเร็จ',
        'redirect' => 'index.php'
    ]);
}

function validatePassword($password)
{
    return strlen($password) >= 8 &&
        preg_match('/[A-Z]/', $password) &&
        preg_match('/[a-z]/', $password) &&
        preg_match('/[0-9]/', $password);
}
?>