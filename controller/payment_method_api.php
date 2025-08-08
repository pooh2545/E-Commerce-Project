<?php
require_once 'config.php';
require_once 'paymentMethodController.php';

$controller = new PaymentMethodController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            echo json_encode($controller->getAll());
        } elseif ($action === 'get' && isset($_GET['id'])) {
            echo json_encode($controller->getById($_GET['id']));
        }
        break;

case 'POST':
    if ($action === 'create') {
            // สมมติว่าไม่มีรูปไฟล์ อาจจะปรับเพิ่มได้เหมือนตัวอย่าง shoetype
            $bank = $_POST['bank'] ?? '';
            $account_number = $_POST['account_number'] ?? '';
            $name = $_POST['name'] ?? '';
            $url_path = $_POST['url_path'] ?? '';

            $success = $controller->create($bank, $account_number, $name, $url_path);
            echo json_encode(['success' => $success]);
        }
    break;


    case 'PUT':
        if ($action === 'update' && isset($_GET['id'])) {
            parse_str(file_get_contents("php://input"), $data);
            $bank = $data['bank'] ?? '';
            $account_number = $data['account_number'] ?? '';
            $name = $data['name'] ?? '';
            $url_path = $data['url_path'] ?? '';

            $success = $controller->update($_GET['id'], $bank, $account_number, $name, $url_path);
            echo json_encode(['success' => $success]);
        }
        break;

    case 'DELETE':
        if ($action === 'delete' && isset($_GET['id'])) {
            $success = $controller->delete($_GET['id']);
            echo json_encode(['success' => $success]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid method']);
        break;
}
