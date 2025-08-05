<?php
require_once 'config.php'; 
require_once 'MemberController.php';

$memberController = new MemberController($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($_GET['action'] === 'all') {
            echo json_encode($memberController->getAll());
        }elseif($_GET['action'] === 'get' && isset($_GET['id'])){
            echo json_encode($memberController->getById($_GET['id']));
        }
        break;
    
    case 'PUT':
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>