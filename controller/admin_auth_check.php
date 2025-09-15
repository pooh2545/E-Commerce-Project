<?php
// auth_middleware.php - ไฟล์สำหรับตรวจสอบสิทธิ์
require_once 'config.php';
require_once 'AdminController.php';

class AuthMiddleware
{
    private $adminController;

    public function __construct($pdo)
    {
        $this->adminController = new AdminController($pdo);

        // เริ่ม session หากยังไม่เริ่ม
        if (session_status() === PHP_SESSION_NONE) {
            session_name('admin_session');
            session_start();
        }
    }

    /**
     * ตรวจสอบว่าผู้ใช้ล็อกอินแล้วหรือไม่
     */
    public function requireAuth()
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
        }
        return true;
    }

    /**
     * ตรวจสอบสิทธิ์ตามบทบาท
     */
    public function requireRole($required_role)
    {
        if (!$this->isLoggedIn()) {
            $this->redirectToLogin();
        }

        if (!$this->hasRole($required_role)) {
            $this->showAccessDenied();
        }
        return true;
    }

    /**
     * ตรวจสอบว่าล็อกอินแล้วหรือไม่
     */
    public function isLoggedIn()
    {
        return isset($_SESSION['admin_id']) || isset($_COOKIE['admin_id']);
    }

    /**
     * ตรวจสอบบทบาท
     */
    public function hasRole($required_role)
    {
        if (!$this->isLoggedIn()) {
            return false;
        }

        $current_role = $this->getCurrentRole();

        // Admin มีสิทธิ์ทุกอย่าง
        if ($current_role === 'Admin') {
            return true;
        }

        return $current_role === $required_role;
    }

    /**
     * ดึงบทบาทปัจจุบัน
     */
    public function getCurrentRole()
    {
        if (isset($_SESSION['admin_role'])) {
            return $_SESSION['admin_role'];
        } elseif (isset($_COOKIE['admin_role'])) {
            return $_COOKIE['admin_role'];
        }
        return null;
    }

    /**
     * ดึงข้อมูลผู้ใช้ปัจจุบัน
     */
    public function getCurrentUser()
    {
        if (!$this->isLoggedIn()) {
            return null;
        }

        return [
            'admin_id' => $_SESSION['admin_id'] ?? $_COOKIE['admin_id'],
            'username' => $_SESSION['admin_username'] ?? $_COOKIE['admin_username'],
            'email' => $_SESSION['admin_email'] ?? $_COOKIE['admin_email'],
            'role' => $_SESSION['admin_role'] ?? $_COOKIE['admin_role']
        ];
    }

    /**
     * สำหรับใช้ในหน้า login - ถ้ามี session แล้วให้ redirect ไปหน้า dashboard
     */
    public function redirectIfLoggedIn($redirect_to = 'admin_dashboard.php')
    {
        if ($this->isLoggedIn()) {
            header("Location: $redirect_to");
            exit();
        }
        return null;
    }

    /**
     * Redirect ไปหน้าล็อกอิน
     */
    private function redirectToLogin()
    {
        header('Location: index.php?error=login_required');
        exit;
    }

    /**
     * แสดงหน้า Access Denied
     */
    private function showAccessDenied()
    {
        http_response_code(403);
        echo '<!DOCTYPE html>
        <html lang="th">
        <head>
            <meta charset="UTF-8">
            <title>ไม่มีสิทธิ์เข้าใช้งาน</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; margin-top: 100px; }
                .error-box { background: #f8d7da; color: #721c24; padding: 20px; border-radius: 8px; max-width: 400px; margin: 0 auto; }
            </style>
        </head>
        <body>
            <div class="error-box">
                <h2>ไม่มีสิทธิ์เข้าใช้งาน</h2>
                <p>คุณไม่มีสิทธิ์ในการเข้าถึงหน้านี้</p>
                <a href="admin_dashboard.php">กลับไปหน้าหลัก</a>
            </div>
        </body>
        </html>';
        exit;
    }
}

// ตัวอย่างการใช้งานในหน้าต่างๆ

/**
 * สำหรับหน้าที่ต้องการล็อกอินเท่านั้น
 * เช่น dashboard.php
 */
function requireLogin()
{
    global $pdo;
    $auth = new AuthMiddleware($pdo);
    $auth->requireAuth();
    return $auth;
}

/**
 * สำหรับหน้าที่เฉพาะ Admin เท่านั้น
 * เช่น admin_management.php, user_management.php
 */
function requireAdmin()
{
    global $pdo;
    $auth = new AuthMiddleware($pdo);
    $auth->requireRole('Admin');
    return $auth;
}

/**
 * สำหรับหน้าที่ Employee สามารถเข้าได้
 * เช่น product_view.php
 */
function requireEmployee()
{
    global $pdo;
    $auth = new AuthMiddleware($pdo);
    $auth->requireRole('Employee');
    return $auth;
}

/**
 * สำหรับใช้ในหน้า Login - ถ้ามี session แล้วให้ redirect ไป dashboard
 * ใช้ในหน้า index.php (login page)
 */
function redirectIfAlreadyLoggedIn($redirect_to = 'admin_dashboard.php')
{
    global $pdo;
    $auth = new AuthMiddleware($pdo);
    $auth->redirectIfLoggedIn($redirect_to);
    return $auth;
}

// ตัวอย่างการใช้งานใน JavaScript (สำหรับ AJAX calls)
function getAuthHeaders()
{
    return `
    <script>
    // ฟังก์ชันตรวจสอบสิทธิ์ก่อน AJAX call
    async function checkPermission(requiredRole = null) {
        try {
            const response = await fetch('admin_api.php?action=check_session');
            const result = await response.json();
            
            if (!result.logged_in) {
                window.location.href = 'index.php?error=login_required';
                return false;
            }
            
            if (requiredRole && result.admin_data.role !== 'Admin' && result.admin_data.role !== requiredRole) {
                alert('คุณไม่มีสิทธิ์ในการดำเนินการนี้');
                return false;
            }
            
            return result.admin_data;
        } catch (error) {
            console.error('Permission check failed:', error);
            return false;
        }
    }
    
    // ตัวอย่างการใช้งาน
    async function deleteProduct(productId) {
        const userData = await checkPermission('Admin');
        if (!userData) return;
        
        // ดำเนินการลบสินค้า
        if (confirm('คุณแน่ใจหรือไม่?')) {
            // AJAX call here
        }
    }
    </script>
    `;
}