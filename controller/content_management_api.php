<?php
require_once 'config.php';
require_once 'contentManagmentController.php';

$controller = new ContentManagmentController($pdo);
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
            $page_name = $_POST['page_name'] ?? '';
            $content = $_POST['content'] ?? '';
            $url_path = $_POST['url_path'] ?? '';
            $custom_code = $_POST['custom_code'] ?? '';

            $success = $controller->create($page_name, $content, $url_path, $custom_code);
            echo json_encode(['success' => $success]);
        }
    break;

    case 'PUT':
        if ($action === 'update' && isset($_GET['id'])) {
            parse_str(file_get_contents("php://input"), $data);

            $page_name = $data['page_name'] ?? '';
            $content = $data['content'] ?? '';
            $url_path = $data['url_path'] ?? '';
            $custom_code = $data['custom_code'] ?? '';

            $success = $controller->update($_GET['id'], $page_name, $content, $url_path, $custom_code);
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