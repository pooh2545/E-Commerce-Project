<?php
require_once 'config.php';
require_once 'OrderController.php';

// Add Content-Type header for JSON response
header('Content-Type: application/json');

// Allow CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$controller = new OrderController($pdo);
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? null;

try {
    switch ($method) {
        case 'GET':
            if ($action === 'get' && isset($_GET['order_id'])) {
                // ดึงออเดอร์ตาม ID
                $order = $controller->getOrderById($_GET['order_id']);
                if ($order) {
                    echo json_encode(['success' => true, 'data' => $order]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่พบออเดอร์ที่ระบุ']);
                }
            } elseif ($action === 'all') {
                // ดึงออเดอร์ทั้งหมด
                $order = $controller->getAllOrder();
                if ($order) {
                    echo json_encode(['success' => true, 'data' => $order]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่พบออเดอร์']);
                }
            } elseif ($action === 'get-by-number' && isset($_GET['order_number'])) {
                // ดึงออเดอร์ตาม Order Number
                $order = $controller->getOrderByNumber($_GET['order_number']);
                if ($order) {
                    echo json_encode(['success' => true, 'data' => $order]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่พบออเดอร์ที่ระบุ']);
                }
            } elseif ($action === 'member-orders' && isset($_GET['member_id'])) {
                // ดึงออเดอร์ทั้งหมดของสมาชิก
                $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
                $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

                $orders = $controller->getOrdersByMember($_GET['member_id']);
                echo json_encode(['success' => true, 'data' => $orders]);
            } elseif ($action === 'sales-report') {
                // รายงานยอดขาย
                $startDate = $_GET['start_date'] ?? null;
                $endDate = $_GET['end_date'] ?? null;

                $report = $controller->getSalesReport($startDate, $endDate);
                if ($report) {
                    echo json_encode(['success' => true, 'data' => $report]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่สามารถดึงรายงานได้']);
                }
            } elseif ($action === 'stock-report') {
                // รายงานสต็อกสินค้า
                $lowStockThreshold = isset($_GET['threshold']) ? intval($_GET['threshold']) : 10;

                $report = $controller->getStockReport($lowStockThreshold);
                echo json_encode(['success' => true, 'data' => $report]);
            } elseif ($action === 'stock-movements') {
                // ประวัติการเปลี่ยนแปลงสต็อก
                $shoeID = $_GET['shoe_id'] ?? null;
                $startDate = $_GET['start_date'] ?? null;
                $endDate = $_GET['end_date'] ?? null;
                $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 100;

                $movements = $controller->getStockMovementHistory($shoeID, $startDate, $endDate, $limit);
                echo json_encode(['success' => true, 'data' => $movements]);
            } elseif ($action === 'status-history' && isset($_GET['order_id'])) {
                // ดึงประวัติการเปลี่ยนสถานะ
                $history = $controller->getOrderStatusHistory($_GET['order_id']);
                echo json_encode(['success' => true, 'data' => $history]);
            } elseif ($action === 'status-stats') {
                // สถิติการเปลี่ยนสถานะ
                $startDate = $_GET['start_date'] ?? null;
                $endDate = $_GET['end_date'] ?? null;

                $stats = $controller->getStatusChangeStats($startDate, $endDate);
                echo json_encode(['success' => true, 'data' => $stats]);
            } elseif ($action === 'recent-changes') {
                // การเปลี่ยนสถานะล่าสุด
                $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;

                $changes = $controller->getRecentStatusChanges($limit);
                echo json_encode(['success' => true, 'data' => $changes]);
            } elseif ($action === 'processing-time-report') {
                // รายงานเวลาดำเนินการ
                $startDate = $_GET['start_date'] ?? null;
                $endDate = $_GET['end_date'] ?? null;

                $report = $controller->getOrderProcessingTimeReport($startDate, $endDate);
                echo json_encode(['success' => true, 'data' => $report]);
            } elseif ($action === 'near-expiry') {
                // ดึงออเดอร์ที่ใกล้หมดเวลาชำระเงิน
                $hoursBeforeExpiry = isset($_GET['hours']) ? intval($_GET['hours']) : 2;

                $orders = $controller->getOrdersNearExpiry($hoursBeforeExpiry);
                echo json_encode(['success' => true, 'data' => $orders]);
            } elseif ($action === 'auto-expire') {
                // ยกเลิกออเดอร์ที่หมดเวลาอัตโนมัติ (สำหรับ Cron Job)
                $result = $controller->autoExpireOrders();
                echo json_encode($result);
            } elseif ($action === 'validate-stock') {
                // ตรวจสอบความถูกต้องของสต็อก (Data Integrity Check)
                $result = $controller->validateStockIntegrity();
                echo json_encode($result);
            } elseif ($action === 'can-cancel' && isset($_GET['order_id'])) {
                // ตรวจสอบว่าสามารถยกเลิกได้หรือไม่
                $canCancel = $controller->canCancelOrder($_GET['order_id']);
                echo json_encode(['success' => true, 'can_cancel' => $canCancel]);
            } elseif ($action === 'pending-payments') {
                // ดึงรายการคำสั่งซื้อที่รอการอนุมัติการชำระเงิน (status = 2)
                $orders = $controller->getPendingPaymentOrders();
                echo json_encode(['success' => true, 'data' => $orders]);
            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ action ที่ถูกต้อง']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                // สร้างออเดอร์ใหม่
                $data = json_decode(file_get_contents('php://input'), true);

                // ตรวจสอบข้อมูลที่จำเป็น
                $required = ['member_id', 'recipient_name', 'payment_method_id', 'total_amount', 'shipping_address', 'shipping_phone', 'items'];
                foreach ($required as $field) {
                    if (!isset($data[$field]) || empty($data[$field])) {
                        echo json_encode(['success' => false, 'message' => "กรุณาระบุ {$field}"]);
                        exit;
                    }
                }

                // ตรวจสอบรายการสินค้า
                if (!is_array($data['items']) || empty($data['items'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุรายการสินค้า']);
                    exit;
                }

                // เวลาหมดอายุการชำระเงิน (default 24 ชั่วโมง)
                $paymentTimeoutHours = $data['payment_timeout_hours'] ?? 24;

                $result = $controller->createOrder(
                    $data['member_id'],
                    $data['recipient_name'],
                    $data['payment_method_id'],
                    $data['total_amount'],
                    $data['shipping_address'],
                    $data['shipping_phone'],
                    $data['notes'] ?? null,
                    $data['items'],
                    $paymentTimeoutHours
                );

                echo json_encode($result);
            } elseif ($action === 'upload-payment-slip') {
                // อัปโหลดหลักการการชำระเงิน
                if (!isset($_POST['order_id']) || !isset($_FILES['payment_slip'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุ order_id และไฟล์หลักการการชำระเงิน']);
                    exit;
                }

                $orderID = $_POST['order_id'];
                $file = $_FILES['payment_slip'];
                $changedBy = $_POST['changed_by'] ?? null; // ผู้ที่ทำการเปลี่ยนแปลง

                // ตรวจสอบไฟล์
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!in_array($file['type'], $allowedTypes)) {
                    echo json_encode(['success' => false, 'message' => 'รองรับเฉพาะไฟล์ JPG, PNG, PDF เท่านั้น']);
                    exit;
                }

                if ($file['size'] > 5 * 1024 * 1024) { // 5MB
                    echo json_encode(['success' => false, 'message' => 'ขนาดไฟล์ต้องไม่เกิน 5 MB']);
                    exit;
                }

                // สร้างโฟลเดอร์ถ้าไม่มี
                $uploadDir = 'uploads/payment_slips/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                // สร้างชื่อไฟล์ใหม่
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $fileName = 'payment_slip_' . $orderID . '_' . time() . '.' . $extension;
                $filePath = $uploadDir . $fileName;

                // อัปโหลดไฟล์
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $result = $controller->uploadPaymentSlip($orderID, $filePath, $changedBy);
                    echo json_encode($result);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่สามารถอัปโหลดไฟล์ได้']);
                }
            } elseif ($action === 'reset-reserved-stock') {
                // รีเซ็ตสต็อกที่ถูกจองไว้ (กรณีมีปัญหา)
                $result = $controller->resetReservedStock();
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action for POST method']);
            }
            break;

        case 'PUT':
            if ($action === 'update-order-status' && isset($_GET['order_id'])) {
                // อัปเดตสถานะออเดอร์
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['order_status_id'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุ order_status_id']);
                    exit;
                }

                $result = $controller->updateOrderStatus(
                    $_GET['order_id'],
                    $data['order_status_id'],
                    $data['changed_by'] ?? null,
                    $data['notes'] ?? null
                );
                echo json_encode($result);
            } elseif ($action === 'approve-payment' && isset($_GET['order_id'])) {
                // ยืนยันการชำระเงิน
                $data = json_decode(file_get_contents('php://input'), true);

                $result = $controller->approvePayment(
                    $_GET['order_id'],
                    $data['changed_by'] ?? 'admin',
                    $data['notes'] ?? 'ยืนยันการชำระเงินโดย Admin'
                );
                echo json_encode($result);
            } elseif ($action === 'reject-payment' && isset($_GET['order_id'])) {
                // ปฏิเสธการชำระเงิน
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['reason']) || empty($data['reason'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุเหตุผลในการปฏิเสธ']);
                    exit;
                }

                $result = $controller->rejectPayment(
                    $_GET['order_id'],
                    $data['reason'],
                    $data['changed_by'] ?? 'admin'
                );
                echo json_encode($result);
            } elseif ($action === 'set-tracking' && isset($_GET['order_id'])) {
                // ตั้งค่าหมายเลขพัสดุ
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['tracking_number'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุหมายเลขพัสดุ']);
                    exit;
                }

                $result = $controller->setTrackingNumber(
                    $_GET['order_id'],
                    $data['tracking_number'],
                    $data['changed_by'] ?? null
                );
                echo json_encode($result);
            } elseif ($action === 'cancel' && isset($_GET['order_id'])) {
                // ยกเลิกออเดอร์
                $data = json_decode(file_get_contents('php://input'), true);

                $result = $controller->cancelOrder(
                    $_GET['order_id'],
                    $data['changed_by'] ?? null,
                    $data['reason'] ?? null,
                    $data['force_cancel'] ?? false
                );
                echo json_encode($result);
            } elseif ($action === 'complete-order' && isset($_GET['order_id'])) {
                // ออเดอร์สำเร็จ
                $data = json_decode(file_get_contents('php://input'), true);

                $result = $controller->updateOrderStatus(
                    $_GET['order_id'],
                    4, // สถานะ "จัดส่งสำเร็จ" (ตาม order_status table)
                    $data['changed_by'] ?? null,
                    'ออเดอร์สำเร็จ'
                );
                echo json_encode($result);
            } elseif ($action === 'extend-payment-time' && isset($_GET['order_id'])) {
                // ขยายเวลาชำระเงิน (สำหรับ Admin)
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['additional_hours'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุจำนวนชั่วโมงที่ต้องการขยาย']);
                    exit;
                }

                $result = $controller->extendPaymentTime(
                    $_GET['order_id'],
                    $data['additional_hours'],
                    $data['changed_by'] ?? null
                );
                echo json_encode($result);
            } elseif ($action === 'adjust-stock' && isset($_GET['shoe_id'])) {
                // ปรับปรุงสต็อกสินค้าด้วยตนเอง (สำหรับ Admin)
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['new_quantity'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุจำนวนสต็อกใหม่']);
                    exit;
                }

                $result = $controller->adjustStock(
                    $_GET['shoe_id'],
                    $data['new_quantity'],
                    $data['changed_by'] ?? null,
                    $data['notes'] ?? null
                );
                echo json_encode($result);
            } elseif ($action === 'add-payment-note' && isset($_GET['order_id'])) {
                // เพิ่มหมายเหตุการชำระเงิน
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['note']) || empty($data['note'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุหมายเหตุ']);
                    exit;
                }

                $result = $controller->addPaymentNote(
                    $_GET['order_id'],
                    $data['note'],
                    $data['changed_by'] ?? 'admin'
                );
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ action และ ID ที่ถูกต้อง']);
            }
            break;

        case 'DELETE':
            // สำหรับอนาคต - อาจะใช้สำหรับลบออเดอร์ (ถ้าจำเป็น)
            echo json_encode(['success' => false, 'message' => 'การลบออเดอร์ไม่ได้รับอนุญาต']);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'HTTP Method ไม่ถูกต้อง']);
            break;
    }
} catch (Exception $e) {
    error_log("Order API Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ: ' . $e->getMessage()]);
} 