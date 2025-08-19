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
                
                $orders = $controller->getOrdersByMember($_GET['member_id'], $limit, $offset);
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

            } elseif ($action === 'status-history' && isset($_GET['order_id'])) {
                // ดึงประวัติการเปลี่ยนสถานะ
                $order = $controller->getOrderById($_GET['order_id']);
                if ($order && isset($order['status_history'])) {
                    echo json_encode(['success' => true, 'data' => $order['status_history']]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'ไม่พบประวัติสถานะ']);
                }

            } elseif ($action === 'near-expiry') {
                // ดึงออเดอร์ที่ใกล้หมดเวลาชำระเงิน
                $hoursBeforeExpiry = isset($_GET['hours']) ? intval($_GET['hours']) : 2;
                
                $orders = $controller->getOrdersNearExpiry($hoursBeforeExpiry);
                echo json_encode(['success' => true, 'data' => $orders]);

            } elseif ($action === 'auto-expire') {
                // ยกเลิกออเดอร์ที่หมดเวลาอัตโนมัติ (สำหรับ Cron Job)
                $result = $controller->autoExpireOrders();
                echo json_encode($result);

            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ action ที่ถูกต้อง']);
            }
            break;

        case 'POST':
            if ($action === 'create') {
                // สร้างออเดอร์ใหม่
                $data = json_decode(file_get_contents('php://input'), true);
                
                // ตรวจสอบข้อมูลที่จำเป็น
                $required = ['member_id', 'address_id', 'payment_method_id', 'total_amount', 'shipping_address', 'member_phone', 'items'];
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
                    $data['address_id'],
                    $data['payment_method_id'],
                    $data['total_amount'],
                    $data['shipping_address'],
                    $data['member_phone'],
                    $data['notes'] ?? null,
                    $data['items'],
                    $paymentTimeoutHours
                );

                echo json_encode($result);

            } elseif ($action === 'upload-payment-slip') {
                // อัปโหลดหลักฐานการชำระเงิน
                if (!isset($_POST['order_id']) || !isset($_FILES['payment_slip'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุ order_id และไฟล์หลักฐานการชำระเงิน']);
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

            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
            }
            break;

        case 'PUT':
            if ($action === 'update-payment-status' && isset($_GET['order_id'])) {
                // อัปเดตสถานะการชำระเงิน
                $data = json_decode(file_get_contents('php://input'), true);
                
                if (!isset($data['payment_status_id'])) {
                    echo json_encode(['success' => false, 'message' => 'กรุณาระบุ payment_status_id']);
                    exit;
                }

                $result = $controller->updatePaymentStatus(
                    $_GET['order_id'],
                    $data['payment_status_id'],
                    $data['payment_slip_path'] ?? null,
                    $data['tracking_number'] ?? null,
                    $data['changed_by'] ?? null
                );

                echo json_encode($result);

            } elseif ($action === 'update-order-status' && isset($_GET['order_id'])) {
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
                    $data['reason'] ?? null
                );
                echo json_encode($result);

            } elseif ($action === 'confirm-payment' && isset($_GET['order_id'])) {
                // ยืนยันการชำระเงิน (สำหรับ Admin)
                $data = json_decode(file_get_contents('php://input'), true);
                
                $result = $controller->updatePaymentStatus(
                    $_GET['order_id'],
                    3, // สถานะ "ชำระเงินแล้ว"
                    null,
                    null,
                    $data['changed_by'] ?? null
                );
                echo json_encode($result);

            } elseif ($action === 'reject-payment' && isset($_GET['order_id'])) {
                // ปฏิเสธการชำระเงิน (สำหรับ Admin)
                $data = json_decode(file_get_contents('php://input'), true);
                
                $result = $controller->updatePaymentStatus(
                    $_GET['order_id'],
                    4, // สถานะ "ไม่สำเร็จ"
                    null,
                    null,
                    $data['changed_by'] ?? null
                );
                echo json_encode($result);

            } elseif ($action === 'complete-order' && isset($_GET['order_id'])) {
                // ออเดอร์สำเร็จ
                $data = json_decode(file_get_contents('php://input'), true);
                
                $result = $controller->updateOrderStatus(
                    $_GET['order_id'],
                    5, // สถานะ "สำเร็จ" (ตาม order_status table)
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

            } else {
                echo json_encode(['success' => false, 'message' => 'กรุณาระบุ action และ order_id ที่ถูกต้อง']);
            }
            break;

        case 'DELETE':
            // สำหรับอนาคต - อาจจะใช้สำหรับลบออเดอร์ (ถ้าจำเป็น)
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