<?php
session_start();

// ลบข้อมูล session ทั้งหมด
$_SESSION = array();

// ลบ session cookie ถ้ามี
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// ทำลาย session
session_unset();
session_destroy();

// ลบ remember me cookie ถ้ามี
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
}

// เปลี่ยนเส้นทางไปยังหน้า index หรือหน้าแรก
header("Location: index.php");
exit();
?>