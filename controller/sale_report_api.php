<?php
require_once 'config.php';
require_once 'SaleReportController.php';

$controller = new SaleReportController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;
$year = $_GET['year'] ?? null;
$month = $_GET['month'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            if ($year) {
                echo json_encode($controller->getSaleReportByYearMonth($year, $month));
            } else {
                echo json_encode($controller->getSaleReport());
            }
        } elseif ($action === 'get' && isset($_GET['id'])) {
            echo json_encode($controller->getShoeById($_GET['id']));
        }
        break;

    default:
        echo json_encode(['status' => 'invalid request']);
}
?>
