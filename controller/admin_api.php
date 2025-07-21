<?php
require_once 'config.php'; 
require_once 'AdminController.php';

$adminController = new AdminController($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        if ($_GET['action'] === 'create') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $adminController->create($data['username'], $data['password']);
            echo json_encode(['success' => $result]);
        } elseif ($_GET['action'] === 'login') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $adminController->login($data['username'], $data['password']);
            echo json_encode($result ?: ['error' => 'Invalid credentials']);
        }
        break;

    case 'GET':
        if ($_GET['action'] === 'all') {
            echo json_encode($adminController->getAll());
        } elseif ($_GET['action'] === 'get' && isset($_GET['id'])) {
            echo json_encode($adminController->getById($_GET['id']));
        }
        break;

    case 'PUT':
        if ($_GET['action'] === 'update' && isset($_GET['id'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $adminController->update($_GET['id'], $data['username'], $data['password'] ?? null);
            echo json_encode(['success' => $result]);
        }
        break;

    case 'DELETE':
        if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
            $result = $adminController->delete($_GET['id']);
            echo json_encode(['success' => $result]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid request']);
        break;
}
