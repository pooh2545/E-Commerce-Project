<?php
require_once 'config.php';
require_once 'contentManagmentController.php';

$controller = new ContentManagmentController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'all') {
            echo json_encode($controller->getAll());
        } elseif ($action === 'getByPageName' && isset($_GET['page_name'])) {
            echo json_encode($controller->getByPageName($_GET['page_name']));
        }
        break;

    case 'POST':
        if ($action === 'create') {
            $page_name = $_POST['page_name'] ?? '';
            $content = $_POST['content'] ?? '';
            $custom_code = $_POST['custom_code'] ?? '';
            $url_path = null;

            if (isset($_FILES['url_path']) && $_FILES['url_path']['error'] === 0) {
                $uploadDir = '../uploads/';
                if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

                $filename = time() . '_' . basename($_FILES['url_path']['name']);
                $targetFile = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['url_path']['tmp_name'], $targetFile)) {
                    $url_path = 'uploads/' . $filename;
                } else {
                    echo json_encode(['success' => false, 'message' => 'อัปโหลดไฟล์ล้มเหลว']);
                    exit;
                }
            }

            // ตรวจสอบว่าหน้านี้มีอยู่แล้วหรือยัง ถ้ามีอัปเดต, ถ้าไม่มีสร้างใหม่
            $existing = $controller->getByPageName($page_name);
            if ($existing) {
                $success = $controller->updateByPageName($page_name, $content, $url_path, $custom_code);
            } else {
                $success = $controller->create($page_name, $content, $url_path, $custom_code);
            }

            echo json_encode(['success' => $success]);
        }
        elseif ($action === 'delete' && isset($_POST['page_name'])) {
            $success = $controller->delete($_POST['page_name']);
            echo json_encode(['success' => $success]);
        }
        break;

    default:
        echo json_encode(['error' => 'Invalid method']);
        break;
}
