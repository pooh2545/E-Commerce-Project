<?php
class OrderController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * สร้างออเดอร์ใหม่พร้อมรายการสินค้า
     */
    public function createOrder($memberID, $addressID, $paymentMethodID, $totalAmount, $shippingAddress, $memberPhone, $notes = null, $items = [], $paymentTimeoutHours = 24)
    {
        try {
            $this->pdo->beginTransaction();

            // สร้างหมายเลขออเดอร์
            $orderNumber = $this->generateOrderNumber();

            // คำนวณเวลาหมดอายุการชำระเงิน
            $paymentExpireAt = date('Y-m-d H:i:s', strtotime("+{$paymentTimeoutHours} hours"));

            // บันทึกข้อมูลออเดอร์ (เริ่มต้นด้วยสถานะ 1 = รอการชำระเงิน)
            $sql = "INSERT INTO orders (order_number, member_id, total_amount, shipping_address, member_phone, 
                    payment_method_id, payment_status_id, order_status_id, note, payment_expire_at, create_at) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, 1, ?, ?, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $orderNumber,
                $memberID,
                $totalAmount,
                $shippingAddress,
                $memberPhone,
                $paymentMethodID,
                $notes,
                $paymentExpireAt
            ]);

            $orderID = $this->pdo->lastInsertId();

            // บันทึกประวัติสถานะเริ่มต้น
            $this->addOrderStatusHistory($orderID, 1, null, "สร้างออเดอร์ - กำหนดชำระเงินภายใน {$paymentTimeoutHours} ชั่วโมง");

            // บันทึกรายการสินค้าในออเดอร์
            foreach ($items as $item) {
                $this->addOrderItem($orderID, $item['shoe_id'], $item['quantity'], $item['unit_price']);
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
        
        $sql = "INSERT INTO order_items (order_id, shoe_id, quantity, unit_price, total_price, create_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$orderID, $shoeID, $quantity, $unitPrice, $totalPrice]);
    }

    /**
     * เพิ่มประวัติการเปลี่ยนสถานะ
     */
    private function addOrderStatusHistory($orderID, $newStatusID, $changedBy = null, $notes = null)
    {
        try {
            $sql = "INSERT INTO order_status_history (order_id, new_status, changed_by, notes, create_at) 
                    VALUES (?, ?, ?, ?, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$orderID, $newStatusID, $changedBy, $notes]);

        } catch (Exception $e) {
            error_log("Error adding order status history: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ล้างตะกร้าสินค้าของสมาชิก
     */
    private function clearMemberCart($memberID)
    {
        $sql = "DELETE FROM carts WHERE member_id = ?";
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

    /**
     * ดึงออเดอร์ตาม ID
     */
    public function getOrderById($orderID)
    {
        try {
            $sql = "SELECT o.*, 
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           ps.status_name as payment_status_name,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
                    LEFT JOIN order_status os ON o.order_status_id = os.order_status_id
                    WHERE o.order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($orderID);
                // ดึงประวัติการเปลี่ยนสถานะ
                $order['status_history'] = $this->getOrderStatusHistory($orderID);
            }

            return $order;

        } catch (Exception $e) {
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
                           ps.status_name as payment_status_name,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
                    LEFT JOIN order_status os ON o.order_status_id = os.order_status_id
                    WHERE o.order_number = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($order['order_id']);
                // ดึงประวัติการเปลี่ยนสถานะ
                $order['status_history'] = $this->getOrderStatusHistory($order['order_id']);
            }

            return $order;

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * ดึงรายการสินค้าในออเดอร์
     */
    private function getOrderItems($orderID)
    {
        try {
            $sql = "SELECT oi.*, s.shoe_name, s.img_path, st.type_name
                    FROM order_items oi
                    JOIN shoes s ON oi.shoe_id = s.shoe_id
                    LEFT JOIN shoetype st ON s.type_id = st.id
                    WHERE oi.order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * ดึงประวัติการเปลี่ยนสถานะของออเดอร์
     */
    private function getOrderStatusHistory($orderID)
    {
        try {
            $sql = "SELECT osh.*, os.name as status_name
                    FROM order_status_history osh
                    LEFT JOIN order_status os ON osh.new_status = os.order_status_id
                    WHERE osh.order_id = ?
                    ORDER BY osh.create_at ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
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
                           ps.status_name as payment_status_name,
                           os.name as order_status_name,
                           COUNT(oi.order_item_id) as item_count
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
                    LEFT JOIN order_status os ON o.order_status_id = os.order_status_id
                    LEFT JOIN order_items oi ON o.order_id = oi.order_id
                    WHERE o.member_id = ?
                    GROUP BY o.order_id
                    ORDER BY o.create_at DESC
                    LIMIT ? OFFSET ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$memberID, $limit, $offset]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * อัปเดตสถานะการชำระเงิน (จะอัปเดตสถานะออเดอร์อัตโนมัติ)
     */
    public function updatePaymentStatus($orderID, $paymentStatusID, $paymentSlipPath = null, $trackingNumber = null, $changedBy = null)
    {
        try {
            $this->pdo->beginTransaction();

            // ดึงข้อมูลออเดอร์ปัจจุบัน
            $currentOrder = $this->getCurrentOrderStatus($orderID);
            if (!$currentOrder) {
                throw new Exception('ไม่พบออเดอร์');
            }

            // อัปเดตสถานะการชำระเงิน
            $sql = "UPDATE orders SET 
                    payment_status_id = ?, 
                    payment_slip_path = COALESCE(?, payment_slip_path),
                    tracking_number = COALESCE(?, tracking_number),
                    update_at = NOW()
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$paymentStatusID, $paymentSlipPath, $trackingNumber, $orderID]);

            if (!$result) {
                throw new Exception('ไม่สามารถอัปเดตสถานะการชำระเงินได้');
            }

            // กำหนดสถานะออเดอร์ใหม่ตามสถานะการชำระเงิน
            $newOrderStatusID = $this->determineOrderStatusByPayment($paymentStatusID, $currentOrder['order_status_id']);
            
            if ($newOrderStatusID && $newOrderStatusID != $currentOrder['order_status_id']) {
                $this->updateOrderStatusInternal($orderID, $newOrderStatusID, $changedBy, 'อัปเดตตามสถานะการชำระเงิน');
            }

            $this->pdo->commit();
            
            return [
                'success' => true,
                'message' => 'อัปเดตสถานะเรียบร้อยแล้ว'
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
            if ($currentOrder['order_status_id'] == $orderStatusID) {
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
            // อัปเดตสถานะในตารางหลัก
            $sql = "UPDATE orders SET order_status_id = ?, update_at = NOW() WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$orderStatusID, $orderID]);

            if ($result) {
                // บันทึกประวัติการเปลี่ยนสถานะ
                $this->addOrderStatusHistory($orderID, $orderStatusID, $changedBy, $notes);
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
            $sql = "SELECT order_status_id, payment_status_id FROM orders WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * กำหนดสถานะออเดอร์ตามสถานะการชำระเงิน
     */
    private function determineOrderStatusByPayment($paymentStatusID, $currentOrderStatusID)
    {
        // Logic การกำหนดสถานะออเดอร์อัตโนมัติ
        switch ($paymentStatusID) {
            case 1: // รอการชำระเงิน
                return 1; // รอการชำระเงิน
            case 2: // รอตรวจสอบ
                return 2; // ชำระเงิน/รอการยืนยัน
            case 3: // ชำระเงินแล้ว
                return 3; // กำลังจัดส่ง (หรือ 4 จัดส่งแล้ว ขึ้นอยู่กับ logic)
            case 4: // ไม่สำเร็จ
                return 5; // ยกเลิกคำสั่ง
            default:
                return null; // ไม่เปลี่ยนสถานะ
        }
    }

    /**
     * อัปโหลด Payment Slip
     */
    public function uploadPaymentSlip($orderID, $paymentSlipPath, $changedBy = null)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE orders SET 
                    payment_slip_path = ?, 
                    payment_status_id = 2,
                    update_at = NOW() 
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$paymentSlipPath, $orderID]);
            
            if ($result) {
                // อัปเดตสถานะออเดอร์เป็น "ชำระเงิน/รอการยืนยัน"
                $this->updateOrderStatusInternal($orderID, 2, $changedBy, 'อัปโหลดหลักฐานการชำระเงิน');
            }

            $this->pdo->commit();
            
            return [
                'success' => $result,
                'message' => $result ? 'อัปโหลดหลักฐานการชำระเงินเรียบร้อยแล้ว' : 'ไม่สามารถอัปโหลดหลักฐานได้'
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

            $sql = "UPDATE orders SET 
                    tracking_number = ?, 
                    update_at = NOW() 
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$trackingNumber, $orderID]);
            
            if ($result) {
                // อัปเดตสถานะเป็น "จัดส่งแล้ว"
                $this->updateOrderStatusInternal($orderID, 4, $changedBy, 'ตั้งค่าหมายเลขพัสดุ: ' . $trackingNumber);
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
     * ยกเลิกออเดอร์ (เฉพาะกรณีที่ยังไม่ได้ชำระเงิน)
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
            if (!$forceCancel && !$this->canCancelOrder($currentOrder)) {
                return [
                    'success' => false,
                    'message' => 'ไม่สามารถยกเลิกออเดอร์ได้ เนื่องจากได้ทำการชำระเงินแล้ว'
                ];
            }

            $result = $this->updateOrderStatusInternal($orderID, 5, $changedBy, 'ยกเลิกออเดอร์: ' . $reason);

            $this->pdo->commit();
            
            return [
                'success' => $result,
                'message' => $result ? 'ยกเลิกออเดอร์เรียบร้อยแล้ว' : 'ไม่สามารถยกเลิกออเดอร์ได้'
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
     * ตรวจสอบว่าสามารถยกเลิกออเดอร์ได้หรือไม่
     */
    private function canCancelOrder($orderData)
    {
        // ไม่สามารถยกเลิกได้หากมีการชำระเงินแล้ว (payment_status_id >= 2)
        return $orderData['payment_status_id'] < 2;
    }

    /**
     * ยกเลิกออเดอร์ที่หมดเวลาชำระเงินอัตโนมัติ (Cron Job)
     */
    public function autoExpireOrders()
    {
        try {
            $this->pdo->beginTransaction();

            // ค้นหาออเดอร์ที่หมดเวลาชำระเงิน และยังอยู่ในสถานะรอชำระเงิน
            $sql = "SELECT order_id, order_number, payment_expire_at 
                    FROM orders 
                    WHERE payment_status_id = 1 
                    AND order_status_id = 1 
                    AND payment_expire_at <= NOW() 
                    AND payment_expire_at IS NOT NULL";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $expiredOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $expiredCount = 0;
            foreach ($expiredOrders as $order) {
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
                'message' => "ยกเลิกออเดอร์อัตโนมัติจำนวน {$expiredCount} รายการ"
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
                    LEFT JOIN members m ON o.member_id = m.member_id
                    WHERE o.payment_status_id = 1 
                    AND o.order_status_id = 1 
                    AND o.payment_expire_at IS NOT NULL
                    AND o.payment_expire_at BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL ? HOUR)
                    ORDER BY o.payment_expire_at ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$hoursBeforeExpiry]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            return [];
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

            if ($currentOrder['payment_status_id'] != 1 || $currentOrder['order_status_id'] != 1) {
                throw new Exception('ไม่สามารถขยายเวลาได้ เนื่องจากออเดอร์ไม่ได้อยู่ในสถานะรอชำระเงิน');
            }

            // ขยายเวลา
            $sql = "UPDATE orders SET 
                    payment_expire_at = DATE_ADD(COALESCE(payment_expire_at, NOW()), INTERVAL ? HOUR),
                    update_at = NOW() 
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$additionalHours, $orderID]);

            if ($result) {
                $this->addOrderStatusHistory(
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
            $whereClause = "WHERE o.payment_status_id IN (2, 3)"; // รอตรวจสอบ และ ชำระเงินแล้ว
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
            return null;
        }
    }
}