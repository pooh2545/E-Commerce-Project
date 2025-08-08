<?php
require_once 'config.php'; 
require_once 'MemberController.php';

$memberController = new MemberController($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($_GET['action'] === 'all') {
            echo json_encode($memberController->getAll());
        } elseif ($_GET['action'] === 'get' && isset($_GET['id'])) {
            echo json_encode($memberController->getById($_GET['id']));
        } elseif ($_GET['action'] === 'check-email' && isset($_GET['email'])) {
            // ตรวจสอบ email ซ้ำ
            $excludeId = isset($_GET['exclude_id']) ? $_GET['exclude_id'] : null;
            $exists = $memberController->isEmailExists($_GET['email'], $excludeId);
            echo json_encode(['exists' => $exists]);
        }
        break;
    
    case 'PUT':
        if ($_GET['action'] === 'update' && isset($_GET['id'])) {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // ตรวจสอบข้อมูลที่จำเป็น
            if (empty($data['email']) || empty($data['firstname']) || empty($data['lastname'])) {
                echo json_encode([
                    'success' => false, 
                    'error' => 'MISSING_DATA',
                    'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
                ]);
                break;
            }

            $result = $memberController->update(
                $_GET['id'], 
                $data['email'], 
                $data['firstname'], 
                $data['lastname'], 
                $data['tel'] ?? null
            );
            
            echo json_encode($result);
        }
        break;
    
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>