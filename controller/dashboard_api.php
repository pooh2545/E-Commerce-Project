<?php
require_once 'config.php';
require_once 'dashboardController.php';

$controller = new DashboardController($pdo);

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            echo json_encode($controller->getDashboardAll());
        } elseif ($action === 'summary') {
            echo json_encode($controller->getDashboardSummary());
        }
        break;

    default:
        echo json_encode(['status' => 'invalid request']);
}
?>
