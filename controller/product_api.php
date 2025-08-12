<?php
require_once 'config.php';
require_once 'productController.php';

// Add Content-Type header for JSON response
header('Content-Type: application/json');

$controller = new ProductController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($action === 'all') {
                echo json_encode($controller->getAll());
            } elseif ($action === 'get' && isset($_GET['id'])) {
                $result = $controller->getById($_GET['id']);
                if ($result) {
                    echo json_encode($result);
                } else {
                    echo json_encode(['error' => 'Product not found']);
                }
            } elseif ($action === 'categories') {
                echo json_encode($controller->getCategories());
            } else {
                echo json_encode(['error' => 'Invalid action']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                $name = $_POST['name'] ?? '';
                $detail = $_POST['detail'] ?? '';
                $price = $_POST['price'] ?? 0;
                $stock = $_POST['stock'] ?? 0;
                $shoetypeId = $_POST['shoetype_id'] ?? '';
                $size = $_POST['size'] ?? '';
                $imageFilename = null;

                // Validate required fields
                if (empty($name) || empty($price) || empty($shoetypeId) || empty($size)) {
                    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน']);
                    exit;
                }

                // Handle file upload
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "uploads/products/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    $imageFilename = uniqid() . "_" . basename($_FILES["image"]["name"]);
                    $targetFile = $targetDir . $imageFilename;
                    
                    // Check file type
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo json_encode(['success' => false, 'message' => 'รองรับเฉพาะไฟล์ jpg, jpeg, png, gif เท่านั้น']);
                        exit;
                    }
                    
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                        exit;
                    }
                }

                $success = $controller->create($name, $detail, $price, $stock, $shoetypeId, $size, $imageFilename);
                echo json_encode(['success' => $success, 'message' => $success ? 'บันทึกข้อมูลเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการบันทึก']);
            } elseif ($action === 'update' && isset($_GET['id'])) {
                // รองรับการอัปเดตผ่าน POST method เพื่อให้ส่ง file ได้
                $name = $_POST['name'] ?? '';
                $detail = $_POST['detail'] ?? '';
                $price = $_POST['price'] ?? 0;
                $stock = $_POST['stock'] ?? 0;
                $shoetypeId = $_POST['shoetype_id'] ?? '';
                $size = $_POST['size'] ?? '';
                $imageFilename = null;

                // Validate required fields
                if (empty($name) || empty($price) || empty($shoetypeId) || empty($size)) {
                    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน']);
                    exit;
                }

                // Handle file upload สำหรับการอัปเดต
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $targetDir = "uploads/products/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    $imageFilename = uniqid() . "_" . basename($_FILES["image"]["name"]);
                    $targetFile = $targetDir . $imageFilename;
                    
                    // Check file type
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo json_encode(['success' => false, 'message' => 'รองรับเฉพาะไฟล์ jpg, jpeg, png, gif เท่านั้น']);
                        exit;
                    }
                    
                    if (!move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
                        exit;
                    }
                }

                $success = $controller->update($_GET['id'], $name, $detail, $price, $stock, $shoetypeId, $size, $imageFilename);
                echo json_encode(['success' => $success, 'message' => $success ? 'อัปเดตข้อมูลเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการอัปเดต']);
            } else {
                echo json_encode(['error' => 'Invalid action']);
            }
            break;

        case 'PUT':
            if ($action === 'update' && isset($_GET['id'])) {
                parse_str(file_get_contents("php://input"), $data);
                $name = $data['name'] ?? '';
                $detail = $data['detail'] ?? '';
                $price = $data['price'] ?? 0;
                $stock = $data['stock'] ?? 0;
                $shoetypeId = $data['shoetype_id'] ?? '';
                $size = $data['size'] ?? '';
                $imageFilename = $data['image'] ?? null;

                // Validate required fields
                if (empty($name) || empty($price) || empty($shoetypeId) || empty($size)) {
                    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน']);
                    exit;
                }
                
                $success = $controller->update($_GET['id'], $name, $detail, $price, $stock, $shoetypeId, $size, $imageFilename);
                echo json_encode(['success' => $success, 'message' => $success ? 'อัปเดตข้อมูลเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการอัปเดต']);
            } else {
                echo json_encode(['error' => 'Invalid action or missing ID']);
            }
            break;

        case 'DELETE':
            if ($action === 'delete' && isset($_GET['id'])) {
                $success = $controller->delete($_GET['id']);
                echo json_encode(['success' => $success, 'message' => $success ? 'ลบข้อมูลเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการลบ']);
            } else {
                echo json_encode(['error' => 'Invalid action or missing ID']);
            }
            break;

        default:
            echo json_encode(['error' => 'Invalid method']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}