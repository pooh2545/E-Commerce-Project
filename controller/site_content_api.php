<?php
require_once 'config.php';
require_once 'SiteContentController.php';

$controller = new SiteContentController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

if ($method === 'GET') {
    // ✅ ถ้าอยากดึงข้อมูลทั้งหมด
    if ($action === 'all') {
        echo json_encode($controller->getAll());
    }
    // ✅ ถ้าอยากดึงข้อมูลตามชื่อเพจ
    elseif ($action === 'getByPageName' && isset($_GET['page_name'])) {
        echo json_encode($controller->getByPageName($_GET['page_name']));
    }
    else {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
