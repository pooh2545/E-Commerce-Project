<?php
require_once 'config.php';
require_once 'shoetypeController.php';

$controller = new ShoetypeController($pdo);
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
        $name = $_POST['name'] ?? '';
        $imageFilename = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $imageFilename = uniqid() . "_" . basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $imageFilename;
            move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        }

        $success = $controller->create($name, $imageFilename);
        echo json_encode(['success' => $success]);
    }
    break;


    case 'PUT':
        if ($action === 'update' && isset($_GET['id'])) {
            parse_str(file_get_contents("php://input"), $data);
            $name = $data['name'] ?? '';
            $imageFilename = $data['image'] ?? null;
            $success = $controller->update($_GET['id'], $name, $imageFilename);
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
