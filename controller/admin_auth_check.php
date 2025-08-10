<?php
// admin_auth_check.php - Admin Authentication Check System

function startAdminSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name('admin_session');
        session_start();
    }
}

function isAdminLoggedIn() {
    startAdminSession();
    
    // ตรวจสอบทั้ง session และ cookie
    if (isset($_SESSION['admin_id']) && isset($_COOKIE['admin_id'])) {
        return true;
    }
    
    // ถ้ามี cookie แต่ไม่มี session ให้สร้าง session ใหม่
    if (isset($_COOKIE['admin_id']) && isset($_COOKIE['admin_email'])) {
        $_SESSION['admin_id'] = $_COOKIE['admin_id'];
        $_SESSION['admin_email'] = $_COOKIE['admin_email'];
        $_SESSION['admin_username'] = $_COOKIE['admin_username'] ?? '';
        $_SESSION['login_time'] = time();
        return true;
    }
    
    return false;
}

function requireAdminLogin($redirect = 'index.php') {
    if (!isAdminLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}

function redirectIfAdminLoggedIn($redirect = 'productmanage.php') {
    if (isAdminLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}

function getAdminData() {
    startAdminSession();
    
    if (!isAdminLoggedIn()) {
        return null;
    }
    
    return [
        'admin_id' => $_SESSION['admin_id'] ?? $_COOKIE['admin_id'],
        'email' => $_SESSION['admin_email'] ?? $_COOKIE['admin_email'],
        'username' => $_SESSION['admin_username'] ?? $_COOKIE['admin_username'],
        'login_time' => $_SESSION['login_time'] ?? time()
    ];
}

function logoutAdmin() {
    startAdminSession();
    
    // ลบข้อมูล session
    $_SESSION = array();

    // ลบ session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time() - 3600, '/');
    }

    // ทำลาย session
    session_destroy();
    
    // ลบ admin cookies
    $admin_cookies = ['admin_id', 'admin_email', 'admin_username'];
    foreach ($admin_cookies as $cookie) {
        if (isset($_COOKIE[$cookie])) {
            setcookie($cookie, '', time() - 3600, '/');
        }
    }
}

function refreshAdminSession() {
    startAdminSession();
    
    if (isAdminLoggedIn()) {
        $expire_time = time() + (1 * 24 * 60 * 60); // 1 วัน
        
        // รีเฟรช cookies
        if (isset($_COOKIE['admin_id'])) {
            setcookie('admin_id', $_COOKIE['admin_id'], [
                'expires' => $expire_time,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
        
        if (isset($_COOKIE['admin_email'])) {
            setcookie('admin_email', $_COOKIE['admin_email'], [
                'expires' => $expire_time,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
        
        if (isset($_COOKIE['admin_username'])) {
            setcookie('admin_username', $_COOKIE['admin_username'], [
                'expires' => $expire_time,
                'path' => '/',
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
        }
    }
}

// Auto-refresh session on page load
refreshAdminSession();
?>