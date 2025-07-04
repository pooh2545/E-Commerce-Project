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

    echo "เชื่อมต่อฐานข้อมูลสำเร็จ";
} catch (PDOException $e) {
    echo "การเชื่อมต่อล้มเหลว: " . $e->getMessage();
}
?>
