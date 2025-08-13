<?php
require_once 'config.php';
require_once 'cartController.php';

// Add Content-Type header for JSON response
header('Content-Type: application/json');

$controller = new CartController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($action === 'get' && isset($_GET['member_id'])) {
                // ดึงสินค้าในตะกร้าของสมาชิก
                $cartItems = $controller->getCartByMember($_GET['member_id']);
                echo json_encode(['success' => true, 'data' => $cartItems]);
            } elseif ($action === 'total' && isset($_GET['member_id'])) {
                // ดึงยอดรวมของตะกร้า
                $total = $controller->getCartTotal($_GET['member_id']);
                echo json_encode(['success' => true, 'data' => $total]);
            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ action และ member_id']);
            }
            break;

        case 'POST':
            if ($action === 'add') {
                // เพิ่มสินค้าในตะกร้า
                $memberId = $_POST['member_id'] ?? '';
                $shoeId = $_POST['shoe_id'] ?? '';
                $quantity = $_POST['quantity'] ?? 1;

                // Validate required fields
                if (empty($memberId) || empty($shoeId)) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุ member_id และ shoe_id']);
                    exit;
                }

                $result = $controller->addToCart($memberId, $shoeId, $quantity);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'PUT':
            if ($action === 'update' && isset($_GET['cart_id'])) {
                // อัปเดตจำนวนสินค้าในตะกร้า
                parse_str(file_get_contents("php://input"), $data);
                $quantity = $data['quantity'] ?? 1;

                if ($quantity < 1) {
                    echo json_encode(['success' => false, 'message' => 'จำนวนสินค้าต้องมากกว่า 0']);
                    exit;
                }

                $result = $controller->updateCartQuantity($_GET['cart_id'], $quantity);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ cart_id ที่ต้องการอัปเดต']);
            }
            break;

        case 'DELETE':
            if ($action === 'remove' && isset($_GET['cart_id'])) {
                // ลบสินค้าออกจากตะกร้า
                $result = $controller->removeFromCart($_GET['cart_id']);
                echo json_encode($result);
            } elseif ($action === 'clear' && isset($_GET['member_id'])) {
                // ล้างตะกร้าทั้งหมดของสมาชิก
                $result = $controller->clearCart($_GET['member_id']);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ cart_id หรือ member_id']);
            }
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'HTTP Method ไม่ถูกต้อง']);
            break;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ: ' . $e->getMessage()]);
}