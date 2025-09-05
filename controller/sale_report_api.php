<?php
require_once 'config.php';
require_once 'saleReportController.php';

$controller = new SaleReportController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        // ✅ ดึงข้อมูลรายงานการขาย
        if ($action === 'all') {
            echo json_encode($controller->getSaleReport());
        }
        // ✅ ดึงข้อมูลสินค้ารายตัว
        elseif ($action === 'get' && isset($_GET['id'])) {
            echo json_encode($controller->getShoeById($_GET['id']));
        }
        break;

    default:
        echo json_encode(['status' => 'invalid request']);
}
?>
