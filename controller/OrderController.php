<?php
require_once 'OrderStatusHistory.php';
require_once 'StockManager.php';

class OrderController
{
    private $pdo;
    private $statusHistory;
    private $stockManager;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->statusHistory = new OrderStatusHistory($pdo);
        $this->stockManager = new StockManager($pdo);
    }

    /**
     * สร้างออเดอร์ใหม่พร้อมรายการสินค้า
     */
    public function createOrder($memberID, $recipient_name, $paymentMethodID, $totalAmount, $shippingAddress, $memberPhone, $notes = null, $items = [], $paymentTimeoutHours = 24)
    {
        try {
            $this->pdo->beginTransaction();

            // ตรวจสอบและจองสต็อกสินค้าก่อน
            $stockValidation = $this->stockManager->validateAndReserveStock($items);
            if (!$stockValidation['success']) {
                $this->pdo->rollback();
                return $stockValidation;
            }

            // สร้างหมายเลขออเดอร์
            $orderNumber = $this->generateOrderNumber();

            // คำนวดเวลาหมดอายุการชำระเงิน
            $paymentExpireAt = date('Y-m-d H:i:s', strtotime("+{$paymentTimeoutHours} hours"));

            $paymentCreateAt = date('Y-m-d H:i:s');
            $paymentUpdateAt = date('Y-m-d H:i:s');

            // บันทึกข้อมูลออเดอร์ (เริ่มต้นด้วยสถานะ 1 = รอการชำระเงิน)
            $sql = "INSERT INTO orders (order_number, member_id, total_amount, shipping_address, shipping_phone, 
                    payment_method_id, order_status, note, payment_expire_at, create_at , update_at , recipient_name) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, ?, ?, ?,?,?)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $orderNumber,
                $memberID,
                $totalAmount,
                $shippingAddress,
                $memberPhone,
                $paymentMethodID,
                $notes,
                $paymentExpireAt,
                $paymentCreateAt,
                $paymentUpdateAt,
                $recipient_name
            ]);

            $orderID = $this->pdo->lastInsertId();

            // บันทึกประวัติสถานะเริ่มต้น
            $this->statusHistory->addHistory($orderID, 1, null, "สร้างออเดอร์ - กำหนดชำระเงินภายใน {$paymentTimeoutHours} ชั่วโมง");

            // บันทึกรายการสินค้าในออเดอร์และลดสต็อก
            foreach ($items as $item) {
                $this->addOrderItem($orderID, $item['shoe_id'], $item['quantity'], $item['unit_price']);
                $this->stockManager->updateStock($item['shoe_id'], -$item['quantity'], $orderID, 'ORDER_CREATE');
            }

            // ลบสินค้าออกจากตะกร้า
            $this->clearMemberCart($memberID);

            $this->pdo->commit();

            return [
                'success' => true,
                'order_id' => $orderID,
                'order_number' => $orderNumber,
                'payment_expire_at' => $paymentExpireAt,
                'message' => "สั่งซื้อสินค้าเรียบร้อยแล้ว กรุณาชำระเงินภายใน {$paymentTimeoutHours} ชั่วโมง"
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * เพิ่มรายการสินค้าในออเดอร์
     */
    private function addOrderItem($orderID, $shoeID, $quantity, $unitPrice)
    {
        $totalPrice = $unitPrice * $quantity;

        $createAt = date('Y-m-d H:i:s');
        $sql = "INSERT INTO order_items (order_id, shoe_id, quantity, unit_price, total_price, create_at) 
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$orderID, $shoeID, $quantity, $unitPrice, $totalPrice, $createAt]);
    }

    /**
     * ล้างตะกร้าสินค้าของสมาชิก
     */
    private function clearMemberCart($memberID)
    {
        $sql = "DELETE FROM cart WHERE member_id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$memberID]);
    }

    /**
     * สร้างหมายเลขออเดอร์อัตโนมัติ
     */
    private function generateOrderNumber()
    {
        $date = date('Ymd');

        // หาหมายเลขออเดอร์ล่าสุดในวันนั้น
        $sql = "SELECT MAX(CAST(SUBSTRING(order_number, -4) AS UNSIGNED)) as max_num 
                FROM orders 
                WHERE order_number LIKE ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["ORD{$date}%"]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $nextNum = ($result['max_num'] ?? 0) + 1;
        $orderNumber = sprintf("ORD%s%04d", $date, $nextNum);

        return $orderNumber;
    }

    //ดึงทั้งหมด
    public function getAllOrder()
    {
        try {
            $sql = "SELECT o.*, mb.first_name , mb.last_name,
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN member mb ON mb.member_id = o.member_id
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $order = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $order;
        } catch (Exception $e) {
            error_log("Error getting order : " . $e->getMessage());
            return null;
        }
    }

    /**
     * ดึงออเดอร์ตาม ID
     */
    public function getOrderById($orderID)
    {
        try {
            $sql = "SELECT o.*, mb.first_name , mb.last_name,
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id
                    LEFT JOIN member mb ON mb.member_id = o.member_id
                    WHERE o.order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($orderID);
                // ดึงประวัติการเปลี่ยนสถานะ
                $order['status_history'] = $this->statusHistory->getHistoryByOrderId($orderID);
            }

            return $order;
        } catch (Exception $e) {
            error_log("Error getting order by ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ดึงออเดอร์ด้วย Order Number
     */
    public function getOrderByNumber($orderNumber)
    {
        try {
            $sql = "SELECT o.*, 
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id
                    WHERE o.order_number = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($order['order_id']);
                // ดึงประวัติการเปลี่ยนสถานะ
                $order['status_history'] = $this->statusHistory->getHistoryByOrderId($order['order_id']);
            }

            return $order;
        } catch (Exception $e) {
            error_log("Error getting order by number: " . $e->getMessage());
            return null;
        }
    }

    /**
     * ดึงรายการสินค้าในออเดอร์
     */
    private function getOrderItems($orderID)
    {
        try {
            $sql = "SELECT oi.*, s.name AS shoename, s.img_path, st.name AS shoetypename, s.size
                    FROM order_items oi
                    JOIN shoe s ON oi.shoe_id = s.shoe_id
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                    WHERE oi.order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting order items: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงออเดอร์ทั้งหมดของสมาชิก
     */
    public function getOrdersByMember($memberID, $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT o.*, 
                           pm.bank, 
                           os.name as order_status_name,
                           COUNT(oi.order_item_id) as item_count
                    FROM orders o
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id
                    LEFT JOIN order_items oi ON o.order_id = oi.order_id
                    WHERE o.member_id = ?
                    GROUP BY o.order_id
                    ORDER BY o.create_at DESC
                    ";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$memberID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting orders by member: " . $e->getMessage());
            return [];
        }
    }

    /**
     * อัปเดตสถานะออเดอร์
     */
    public function updateOrderStatus($orderID, $orderStatusID, $changedBy = null, $notes = null)
    {
        try {
            $this->pdo->beginTransaction();

            // ดึงสถานะปัจจุบัน
            $currentOrder = $this->getCurrentOrderStatus($orderID);
            if (!$currentOrder) {
                throw new Exception('ไม่พบออเดอร์');
            }

            // ตรวจสอบว่าสถานะเปลี่ยนแปลงหรือไม่
            if ($currentOrder['order_status'] == $orderStatusID) {
                return [
                    'success' => true,
                    'message' => 'สถานะออเดอร์ยังคงเดิม'
                ];
            }

            $result = $this->updateOrderStatusInternal($orderID, $orderStatusID, $changedBy, $notes);

            $this->pdo->commit();

            return [
                'success' => $result,
                'message' => $result ? 'อัปเดตสถานะออเดอร์เรียบร้อยแล้ว' : 'ไม่สามารถอัปเดตสถานะออเดอร์ได้'
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * อัปเดตสถานะออเดอร์ (Internal method)
     */
    private function updateOrderStatusInternal($orderID, $orderStatusID, $changedBy = null, $notes = null)
    {
        try {
            $paymentUpdateAt = date('Y-m-d H:i:s');
            // อัปเดตสถานะในตารางหลัก
            $sql = "UPDATE orders SET order_status = ?, update_at = ? WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$orderStatusID, $paymentUpdateAt ,$orderID]);

            if ($result) {
                // บันทึกประวัติการเปลี่ยนสถานะ
                $this->statusHistory->addHistory($orderID, $orderStatusID, $changedBy, $notes);
            }

            return $result;
        } catch (Exception $e) {
            throw new Exception('ไม่สามารถอัปเดตสถานะได้: ' . $e->getMessage());
        }
    }

    /**
     * ดึงสถานะปัจจุบันของออเดอร์
     */
    private function getCurrentOrderStatus($orderID)
    {
        try {
            $sql = "SELECT order_status FROM orders WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting current order status: " . $e->getMessage());
            return null;
        }
    }

    /**
     * อัปโหลด Payment Slip
     */
    public function uploadPaymentSlip($orderID, $paymentSlipPath, $changedBy = null)
    {
        try {
            $this->pdo->beginTransaction();

            $updateAt = date('Y-m-d H:i:s');
            $sql = "UPDATE orders SET 
                    payment_slip_path = ?, 
                    order_status = 2,
                    update_at = ? 
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$paymentSlipPath, $updateAt, $orderID]);

            if ($result) {
                // อัปเดตสถานะออเดอร์เป็น "ชำระเงิน/รอการยืนยัน"
                $this->updateOrderStatusInternal($orderID, 2, $changedBy, 'อัปโหลดหลักการการชำระเงิน');
            }

            $this->pdo->commit();

            return [
                'success' => $result,
                'message' => $result ? 'อัปโหลดหลักการการชำระเงินเรียบร้อยแล้ว' : 'ไม่สามารถอัปโหลดหลักการได้'
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ตั้งค่าหมายเลขพัสดุ
     */
    public function setTrackingNumber($orderID, $trackingNumber, $changedBy = null)
    {
        try {
            $this->pdo->beginTransaction();

            $updateAt = date('Y-m-d H:i:s');
            $sql = "UPDATE orders SET 
                    tracking_number = ?, 
                    update_at = ? 
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$trackingNumber, $updateAt, $orderID]);

            if ($result) {
                // อัปเดตสถานะเป็น "จัดส่งแล้ว"
                $this->updateOrderStatusInternal($orderID, 3, $changedBy, 'ตั้งค่าหมายเลขพัสดุ: ' . $trackingNumber);
            }

            $this->pdo->commit();

            return [
                'success' => $result,
                'message' => $result ? 'ตั้งค่าหมายเลขพัสดุเรียบร้อยแล้ว' : 'ไม่สามารถตั้งค่าหมายเลขพัสดุได้'
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ยกเลิกออเดอร์ (เฉพาะกรณีที่ยังไม่ได้ชำระเงิน) - พร้อมคืนสต็อก
     */
    public function cancelOrder($orderID, $changedBy = null, $reason = null, $forceCancel = false)
    {
        try {
            $this->pdo->beginTransaction();

            // ตรวจสอบสถานะปัจจุบัน
            $currentOrder = $this->getCurrentOrderStatus($orderID);
            if (!$currentOrder) {
                throw new Exception('ไม่พบออเดอร์');
            }

            // ตรวจสอบว่าสามารถยกเลิกได้หรือไม่
            if (!$forceCancel && !$this->canCancelOrder($orderID)) {
                return [
                    'success' => false,
                    'message' => 'ไม่สามารถยกเลิกออเดอร์ได้'
                ];
            }

            // คืนสต็อกสินค้า
            $this->stockManager->restoreOrderStock($orderID);

            $result = $this->updateOrderStatusInternal($orderID, 5, $changedBy, 'ยกเลิกออเดอร์: ' . $reason);

            $this->pdo->commit();

            return [
                'success' => $result,
                'message' => $result ? 'ยกเลิกออเดอร์และคืนสต็อกสินค้าเรียบร้อยแล้ว' : 'ไม่สามารถยกเลิกออเดอร์ได้'
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ตรวจสอบว่าสามารถยกเลิกได้หรือไม่
     */
    public function canCancelOrder($orderID)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT order_status, payment_expire_at
                FROM orders 
                WHERE order_id = ?
            ");
            $stmt->execute([$orderID]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                return false;
            }

            // เงื่อนไขที่ไม่สามารถยกเลิกได้
            if ($order['order_status'] == 5) { // ยกเลิกแล้ว
                return false;
            }

            if ($order['order_status'] >= 3) { // ส่งสินค้าแล้ว
                return false;
            }

            return true;
        } catch (Exception $e) {
            error_log("Can Cancel Order Check Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ยกเลิกออเดอร์ที่หมดเวลาชำระเงินอัตโนมัติ (Cron Job) - พร้อมคืนสต็อก
     */
    public function autoExpireOrders()
    {
        try {
            $this->pdo->beginTransaction();

            // ค้นหาออเดอร์ที่หมดเวลาชำระเงิน และยังอยู่ในสถานะรอชำระเงิน
            $sql = "SELECT order_id, order_number, payment_expire_at 
                    FROM orders 
                    WHERE 
                        order_status = 1 
                        AND payment_expire_at <= NOW() 
                        AND payment_expire_at IS NOT NULL";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $expiredOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $expiredCount = 0;
            foreach ($expiredOrders as $order) {
                // คืนสต็อกสินค้า
                $this->stockManager->restoreOrderStock($order['order_id']);

                // ยกเลิกออเดอร์อัตโนมัติ
                $result = $this->updateOrderStatusInternal(
                    $order['order_id'],
                    5, // สถานะยกเลิก
                    'SYSTEM',
                    'ยกเลิกอัตโนมัติเนื่องจากหมดเวลาชำระเงิน'
                );

                if ($result) {
                    $expiredCount++;
                }
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'expired_count' => $expiredCount,
                'message' => "ยกเลิกออเดอร์อัตโนมัติและคืนสต็อกจำนวน {$expiredCount} รายการ"
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ดึงออเดอร์ที่ใกล้หมดเวลาชำระเงิน
     */
    public function getOrdersNearExpiry($hoursBeforeExpiry = 2)
    {
        try {
            $sql = "SELECT o.*, m.fname, m.lname, m.email 
                    FROM orders o
                    LEFT JOIN member m ON o.member_id = m.member_id
                    WHERE o.order_status = 1 
                    AND o.payment_expire_at IS NOT NULL
                    AND o.payment_expire_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ? HOUR)
                    ORDER BY o.payment_expire_at ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$hoursBeforeExpiry]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting orders near expiry: " . $e->getMessage());
            return [];
        }
    }


public function approvePayment($orderID, $changedBy = 'admin', $notes = 'อนุมัติการชำระเงินโดย Admin')
{
    try {
        $this->pdo->beginTransaction();

        // ตรวจสอบสถานะปัจจุบัน
        $currentOrder = $this->getCurrentOrderStatus($orderID);
        if (!$currentOrder) {
            throw new Exception('ไม่พบออเดอร์');
        }

        if ($currentOrder['order_status'] != 2) {
            throw new Exception('ออเดอร์ไม่ได้อยู่ในสถานะรอยืนยันการชำระเงิน');
        }

        // อัปเดตสถานะเป็น "จัดเตรียมสินค้า" (status 3)
        $result = $this->updateOrderStatusInternal($orderID, 3, $changedBy, $notes);

        $this->pdo->commit();

        return [
            'success' => $result,
            'message' => $result ? 'อนุมัติการชำระเงินเรียบร้อยแล้ว' : 'ไม่สามารถอนุมัติการชำระเงินได้'
        ];
    } catch (Exception $e) {
        $this->pdo->rollback();
        return [
            'success' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ];
    }
}

/**
 * ปฏิเสธการชำระเงิน
 */
public function rejectPayment($orderID, $reason, $changedBy = 'admin')
{
    try {
        $this->pdo->beginTransaction();

        // ตรวจสอบสถานะปัจจุบัน
        $currentOrder = $this->getCurrentOrderStatus($orderID);
        if (!$currentOrder) {
            throw new Exception('ไม่พบออเดอร์');
        }

        if ($currentOrder['order_status'] != 2) {
            throw new Exception('ออเดอร์ไม่ได้อยู่ในสถานะรอยืนยันการชำระเงิน');
        }

        // คืนสต็อกสินค้า
        $this->stockManager->restoreOrderStock($orderID);

        // อัปเดตสถานะเป็น "รอการชำระเงิน" (status 4) และลบ payment slip
        $sql = "UPDATE orders SET 
                order_status = 4, 
                payment_slip_path = NULL,
                update_at = ? 
                WHERE order_id = ?";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([date('Y-m-d H:i:s'),$orderID]);

        // บันทึกประวัติการเปลี่ยนสถานะ
        $this->statusHistory->addHistory(
            $orderID, 
            4, 
            $changedBy, 
            'ปฏิเสธการชำระเงิน: ' . $reason
        );

        $this->pdo->commit();

        return [
            'success' => true,
            'message' => 'ปฏิเสธการชำระเงินเรียบร้อยแล้ว ลูกค้าสามารถอัปโหลดหลักฐานใหม่ได้'
        ];
    } catch (Exception $e) {
        $this->pdo->rollback();
        return [
            'success' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ];
    }
}

/**
 * ดึงรายการคำสั่งซื้อที่รอการอนุมัติการชำระเงิน (status = 2)
 */
public function getPendingPaymentOrders()
{
    try {
        $sql = "SELECT o.*, 
                       m.first_name, m.last_name, m.email,
                       pm.bank, pm.account_number, pm.name as bank_account_name,
                       os.name as order_status_name
                FROM orders o
                LEFT JOIN member m ON o.member_id = m.member_id
                LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                LEFT JOIN order_status os ON o.order_status = os.order_status_id
                WHERE o.order_status = 2
                ORDER BY o.update_at ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        error_log("Error getting pending payment orders: " . $e->getMessage());
        return [];
    }
}

/**
 * เพิ่มหมายเหตุการชำระเงิน
 */
public function addPaymentNote($orderID, $note, $changedBy = 'admin')
{
    try {
        $this->pdo->beginTransaction();

        // ตรวจสอบว่าออเดอร์มีอยู่จริง
        $currentOrder = $this->getCurrentOrderStatus($orderID);
        if (!$currentOrder) {
            throw new Exception('ไม่พบออเดอร์');
        }

        // เพิ่มหมายเหตุในตาราง order_status_history
        $this->statusHistory->addHistory(
            $orderID,
            $currentOrder['order_status'], // คงสถานะเดิม
            $changedBy,
            'หมายเหตุการชำระเงิน: ' . $note
        );

        $this->pdo->commit();

        return [
            'success' => true,
            'message' => 'เพิ่มหมายเหตุเรียบร้อยแล้ว'
        ];
    } catch (Exception $e) {
        $this->pdo->rollback();
        return [
            'success' => false,
            'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
        ];
    }
}

    /**
     * ขยายเวลาชำระเงิน (สำหรับ Admin)
     */
    public function extendPaymentTime($orderID, $additionalHours, $changedBy = null)
    {
        try {
            $this->pdo->beginTransaction();

            // ตรวจสอบสถานะออเดอร์
            $currentOrder = $this->getCurrentOrderStatus($orderID);
            if (!$currentOrder) {
                throw new Exception('ไม่พบออเดอร์');
            }

            if ($currentOrder['order_status'] != 1) {
                throw new Exception('ไม่สามารถขยายเวลาได้ เนื่องจากออเดอร์ไม่ได้อยู่ในสถานะรอชำระเงิน');
            }

            // ขยายเวลา
            $updateAt = date('Y-m-d H:i:s');
            $sql = "UPDATE orders SET 
                    payment_expire_at = DATE_ADD(COALESCE(payment_expire_at, NOW()), INTERVAL ? HOUR),
                    update_at = ? 
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$additionalHours, $updateAt, $orderID]);

            if ($result) {
                $this->statusHistory->addHistory(
                    $orderID,
                    1, // คงสถานะเดิม
                    $changedBy,
                    "ขยายเวลาชำระเงินเพิ่ม {$additionalHours} ชั่วโมง"
                );
            }

            $this->pdo->commit();

            return [
                'success' => $result,
                'message' => $result ? "ขยายเวลาชำระเงินเพิ่ม {$additionalHours} ชั่วโมงเรียบร้อยแล้ว" : 'ไม่สามารถขยายเวลาได้'
            ];
        } catch (Exception $e) {
            $this->pdo->rollback();
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * สร้างรายงานยอดขาย
     */
    public function getSalesReport($startDate = null, $endDate = null)
    {
        try {
            $whereClause = "WHERE o.order_status IN (4, 6)"; // จัดส่งแล้ว และ สำเร็จ
            $params = [];

            if ($startDate && $endDate) {
                $whereClause .= " AND DATE(o.create_at) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }

            $sql = "SELECT 
                        COUNT(o.order_id) as total_orders,
                        SUM(o.total_amount) as total_revenue,
                        AVG(o.total_amount) as average_order_value,
                        COUNT(DISTINCT o.member_id) as unique_customers
                    FROM orders o 
                    {$whereClause}";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting sales report: " . $e->getMessage());
            return null;
        }
    }

    // Delegate methods ไปยัง StockManager
    public function getStockReport($lowStockThreshold = 10)
    {
        return $this->stockManager->getStockReport($lowStockThreshold);
    }

    public function getStockMovementHistory($shoeID = null, $startDate = null, $endDate = null, $limit = 100)
    {
        return $this->stockManager->getStockMovementHistory($shoeID, $startDate, $endDate, $limit);
    }

    public function adjustStock($shoeID, $newQuantity, $changedBy = null, $notes = null)
    {
        return $this->stockManager->adjustStock($shoeID, $newQuantity, $changedBy, $notes);
    }

    public function validateStockIntegrity()
    {
        return $this->stockManager->validateStockIntegrity();
    }

    public function resetReservedStock()
    {
        //return $this->stockManager->resetReservedStock();
    }

    // Delegate methods ไปยัง OrderStatusHistory
    public function getOrderStatusHistory($orderID)
    {
        return $this->statusHistory->getHistoryByOrderId($orderID);
    }

    public function getStatusChangeStats($startDate = null, $endDate = null)
    {
        return $this->statusHistory->getStatusChangeStats($startDate, $endDate);
    }

    public function getRecentStatusChanges($limit = 20)
    {
        return $this->statusHistory->getRecentStatusChanges($limit);
    }

    public function getOrderProcessingTimeReport($startDate = null, $endDate = null)
    {
        return $this->statusHistory->getOrderProcessingTimeReport($startDate, $endDate);
    }
}