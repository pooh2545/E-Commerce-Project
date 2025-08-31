<?php
require_once 'config.php';
require_once 'stockReportController.php';

$controller = new StockReportController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        // ✅ ดึงข้อมูลรายงานสต็อก
        if ($action === 'all') {
            echo json_encode($controller->getStockReport());
        }
        // ✅ ดึงข้อมูลสินค้าเจาะจง
        elseif ($action === 'get' && isset($_GET['id'])) {
            echo json_encode($controller->getShoeById($_GET['id']));
        }
        break;

    case 'POST':
        // ✅ อัปเดตสต็อกสินค้า
        if ($action === 'update' && isset($_POST['id'], $_POST['stock'])) {
            $result = $controller->updateStock($_POST['id'], $_POST['stock']);
            echo json_encode(['status' => $result ? 'success' : 'error']);
        }
        // ✅ ลบสินค้า
        elseif ($action === 'delete' && isset($_POST['id'])) {
            $result = $controller->deleteShoe($_POST['id']);
            echo json_encode(['status' => $result ? 'success' : 'error']);
        }
        break;

    default:
        echo json_encode(['status' => 'invalid request']);
}
?>
