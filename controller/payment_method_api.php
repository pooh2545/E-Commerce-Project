<?php 
require_once 'config.php';
require_once 'paymentMethodController.php';

$controller = new PaymentMethodController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

switch ($method) {
    case 'GET':
        if ($action === 'all') echo json_encode($controller->getAll());
        elseif ($action === 'get' && isset($_GET['id'])) echo json_encode($controller->getById($_GET['id']));
        break;

    case 'POST':
        if ($action === 'create') {
            $bank = $_POST['bank'] ?? '';
            $account_number = $_POST['account_number'] ?? '';
            $name = $_POST['name'] ?? '';
            $url_path = $_POST['url_path'] ?? '';;

            // อัปโหลด QR ถ้ามี
            if (!empty($_FILES['url_path']['name'])) {
                $uploadDir = "../controller/uploads/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['url_path']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['url_path']['tmp_name'], $targetPath)) $url_path = $fileName;
            }

            $success = $controller->create($bank, $account_number, $name, $url_path);
            echo json_encode(['success' => $success]);
        }

        elseif ($action === 'update' && isset($_GET['id'])) {
            $id = $_GET['id'];
            $bank = $_POST['bank'] ?? null;
            $account_number = $_POST['account_number'] ?? null;
            $name = $_POST['name'] ?? null;
            $url_path = $_POST['url_path'] ?? null;

            // อัปโหลด QR
            if (!empty($_FILES['url_path']['name'])) {
                $uploadDir = "../controller/uploads/";
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES['url_path']['name']);
                $targetPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['url_path']['tmp_name'], $targetPath)) $url_path = $fileName;
            }

            $success = $controller->update($id, $bank, $account_number, $name, $url_path);
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
