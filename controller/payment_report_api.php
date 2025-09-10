<?php
require_once 'config.php';
require_once 'paymentReportController.php';

$controller = new PaymentReportController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

header('Content-Type: application/json');

switch ($method) {
    case 'GET':
        // ✅ ดึงข้อมูลการชำระเงินทั้งหมด
        if ($action === 'all') {
            echo json_encode($controller->getPaymentReport());
        }
        // ✅ ดึงข้อมูลแบบรายตัว (ตาม order_number)
        elseif ($action === 'get' && isset($_GET['id'])) {
            echo json_encode($controller->getPaymentById($_GET['id']));
        }
        else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>
