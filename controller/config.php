<?php
$host = "26.107.197.161";
$dbname = "ShoeStore";
$user = "newuser";
$password = "mypassword";

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $user, $password);

    // ตั้งค่า Error Mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
} catch (PDOException $e) {
    echo "การเชื่อมต่อล้มเหลว: " . $e->getMessage();
}

// การตั้งค่าเพิ่มเติม
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf']);
define('DEFAULT_PAYMENT_TIMEOUT_HOURS', 24);
define('LOW_STOCK_THRESHOLD', 10);

// Timezone
date_default_timezone_set('Asia/Bangkok');

// Error reporting (ปิดใน production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/error.log');
?>
