<?php
require_once 'config.php';
require_once 'AdminController.php';

// เริ่ม session
session_name('admin_session');
session_start();

$adminController = new AdminController($pdo);
$method = $_SERVER['REQUEST_METHOD'];

// ฟังก์ชันตรวจสอบสิทธิ์
function checkPermission($required_role = 'Admin')
{
    global $adminController;

    if (!isset($_SESSION['admin_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized', 'message' => 'กรุณาเข้าสู่ระบบ']);
        exit;
    }

    if (!$adminController->hasPermission($_SESSION['admin_id'], $required_role)) {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden', 'message' => 'ไม่มีสิทธิ์เข้าใช้งาน']);
        exit;
    }

    return true;
}

// จัดการ request ตาม method และ action
switch ($method) {
    case 'POST':
        if ($_GET['action'] === 'create') {
            checkPermission('Admin'); // เฉพาะ Admin เท่านั้นที่สร้างได้

            $data = json_decode(file_get_contents('php://input'), true);

            if (!$data || empty($data['username']) || empty($data['email']) || empty($data['password'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                exit;
            }

            $role = isset($data['role']) ? $data['role'] : 'Employee';
            $result = $adminController->create($data['username'], $data['email'], $data['password'], $role);
            echo json_encode($result);
        } elseif ($_GET['action'] === 'login') {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!is_array($data) || empty($data['email']) || empty($data['password'])) {
                echo json_encode(['error' => 'Missing email or password']);
                http_response_code(400);
                exit;
            }

            $result = $adminController->login($data['email'], $data['password']);

            if ($result) {
                $expire_time = time() + (1 * 24 * 60 * 60); // 1 วัน

                // เซ็ต cookie แบบปลอดภัยสำหรับ admin
                setcookie('admin_id', $result['admin_id'], [
                    'expires' => $expire_time,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie('admin_email', $result['email'], [
                    'expires' => $expire_time,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie('admin_username', $result['username'], [
                    'expires' => $expire_time,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie('admin_role', $result['role'], [
                    'expires' => $expire_time,
                    'path' => '/',
                    'secure' => isset($_SERVER['HTTPS']),
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                // เก็บข้อมูลใน session
                $_SESSION['admin_id'] = $result['admin_id'];
                $_SESSION['admin_email'] = $result['email'];
                $_SESSION['admin_username'] = $result['username'];
                $_SESSION['admin_role'] = $result['role'];
                $_SESSION['login_time'] = time();

                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'username' => $result['username'],
                    'admin_id' => $result['admin_id'],
                    'role' => $result['role'],
                    'redirect' => 'admin_management.php'
                ]);
            } else {
                echo json_encode(['error' => 'Invalid credentials']);
            }
        } elseif ($_GET['action'] === 'logout') {
            handleAdminLogout();
        }
        break;

    case 'GET':
        if ($_GET['action'] === 'all') {
            checkPermission('Admin'); // เฉพาะ Admin เท่านั้นที่ดูได้
            echo json_encode($adminController->getAll());
        } elseif ($_GET['action'] === 'get' && isset($_GET['id'])) {
            checkPermission('Admin');
            echo json_encode($adminController->getById($_GET['id']));
        } elseif ($_GET['action'] === 'check_session') {
            checkAdminSession();
        } elseif ($_GET['action'] === 'get_role') {
            if (isset($_SESSION['admin_id'])) {
                $role = $adminController->getRole($_SESSION['admin_id']);
                echo json_encode(['role' => $role]);
            } else {
                echo json_encode(['role' => null]);
            }
        }
        break;

    case 'PUT':
        if ($_GET['action'] === 'update' && isset($_GET['id'])) {
            checkPermission('Admin');

            $data = json_decode(file_get_contents('php://input'), true);
            $password = !empty($data['password']) ? $data['password'] : null;
            $role = isset($data['role']) ? $data['role'] : null;

            $result = $adminController->update($_GET['id'], $data['username'], $password, $role);
            echo json_encode(['success' => $result]);
        } elseif ($_GET['action'] === 'update_role' && isset($_GET['id'])) {
            checkPermission('Admin');

            $data = json_decode(file_get_contents('php://input'), true);
            $result = $adminController->updateRole($_GET['id'], $data['role']);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'DELETE':
        if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
            checkPermission('Admin');

            // ตรวจสอบไม่ให้ลบตัวเอง
            if ($_GET['id'] === $_SESSION['admin_id']) {
                echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบบัญชีตัวเองได้']);
                exit;
            }

            $result = $adminController->delete($_GET['id']);
            echo json_encode($result);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

function handleAdminLogout()
{
    // ตรวจสอบสถานะ session
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_name('admin_session');
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

    // ลบ admin cookies
    $admin_cookies = ['admin_id', 'admin_email', 'admin_username', 'admin_role'];
    foreach ($admin_cookies as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/');
        }
    }

    echo json_encode([
        'success' => true,
        'message' => 'Admin logout successful',
        'redirect' => 'index.php'
    ]);
}

function checkAdminSession()
{
    global $adminController;

    $isLoggedIn = false;
    $adminData = null;

    // ตรวจสอบทั้ง session และ cookie
    if (isset($_SESSION['admin_id']) && isset($_COOKIE['admin_id'])) {
        $isLoggedIn = true;
        $adminData = [
            'admin_id' => $_SESSION['admin_id'],
            'email' => $_SESSION['admin_email'] ?? $_COOKIE['admin_email'],
            'username' => $_SESSION['admin_username'] ?? $_COOKIE['admin_username'],
            'role' => $_SESSION['admin_role'] ?? $_COOKIE['admin_role'],
            'login_time' => $_SESSION['login_time']
        ];
    } elseif (isset($_COOKIE['admin_id'])) {
        // ถ้ามี cookie แต่ไม่มี session ให้สร้าง session ใหม่
        $_SESSION['admin_id'] = $_COOKIE['admin_id'];
        $_SESSION['admin_email'] = $_COOKIE['admin_email'];
        $_SESSION['admin_username'] = $_COOKIE['admin_username'];
        $_SESSION['admin_role'] = $_COOKIE['admin_role'];
        $_SESSION['login_time'] = time();

        $isLoggedIn = true;
        $adminData = [
            'admin_id' => $_COOKIE['admin_id'],
            'email' => $_COOKIE['admin_email'],
            'username' => $_COOKIE['admin_username'],
            'role' => $_COOKIE['admin_role'],
            'login_time' => $_SESSION['login_time']
        ];
    }

    echo json_encode([
        'logged_in' => $isLoggedIn,
        'admin_data' => $adminData
    ]);
}
