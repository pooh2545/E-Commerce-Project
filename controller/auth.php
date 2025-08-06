<?php
// auth.php - Authentication handler
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

function handleLogin($memberController) {
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

        $expire_time = time() + (1 * 24 * 60 * 60); // 30 วัน
        
        // เซ็ต cookie แบบปลอดภัย
        setcookie('member_id', $member['member_id'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']), // ใช้เฉพาะ HTTPS
            'httponly' => true, // ป้องกัน JavaScript access
            'samesite' => 'Strict' // ป้องกัน CSRF
        ]);
        
        setcookie('email', $member['email'], [
            'expires' => $expire_time,
            'path' => '/',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ]);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Login successful',
            'redirect' => 'index.php'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password']);
    }
}

function handleSignup($memberController) {
    $email = $_POST['email'] ?? '';
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $password = $_POST['password'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';
    
    // Validation
    if (empty($email) || empty($firstname) || empty($lastname) || empty($phone) ||empty($password) || empty($confirmPassword)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
        return;
    }
    
    if ($password !== $confirmPassword) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match']);
        return;
    }
    
    // Check if email already exists
    $existingMember = $memberController->getAll();
    foreach ($existingMember as $member) {
        if ($member['email'] === $email) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
            return;
        }
    }
    
    // Password strength validation
    if (!validatePassword($password)) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 8 characters with uppercase, lowercase, and number']);
        return;
    }
    
    // Create member
    try {
        $result = $memberController->create($email, $firstname, $lastname,$phone, $password);
        
        if ($result) {
            echo json_encode([
                'success' => true, 
                'message' => 'Account created successfully! Please login.',
                'redirect' => 'login.php'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create account. Please try again.']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error creating account: ' . $e->getMessage()]);
    }
}

function handleLogout() {
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
    
    // ลบ custom cookies
    $cookies_to_delete = ['customer_session','member_id', 'email', 'login_time'];
    foreach ($cookies_to_delete as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/');
        }
    }
    
    echo json_encode([
        'success' => true, 
        'message' => 'Logout successful',
        'redirect' => 'login.php'
    ]);
}

function validatePassword($password) {
    return strlen($password) >= 8 && 
           preg_match('/[A-Z]/', $password) && 
           preg_match('/[a-z]/', $password) && 
           preg_match('/[0-9]/', $password);
}
?>