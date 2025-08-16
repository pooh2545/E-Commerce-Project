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
    public function createOrder($memberID, $addressID, $paymentMethodID, $totalAmount, $shippingAddress, $memberPhone, $notes = null, $items = [])
    {
        try {
            $this->pdo->beginTransaction();

            // สร้างหมายเลขออเดอร์
            $orderNumber = $this->generateOrderNumber();

            // บันทึกข้อมูลออเดอร์
            $sql = "INSERT INTO orders (order_number, member_id, total_amount, shipping_address, member_phone, 
                    payment_method_id, payment_status_id, order_status, note, create_at) 
                    VALUES (?, ?, ?, ?, ?, ?, 1, 'pending', ?, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $orderNumber,
                $memberID,
                $totalAmount,
                $shippingAddress,
                $memberPhone,
                $paymentMethodID,
                $notes
            ]);

            $orderID = $this->pdo->lastInsertId();

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
                'message' => 'สั่งซื้อสินค้าเรียบร้อยแล้ว'
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
                           ps.status_name as payment_status_name
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
                    WHERE o.order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($orderID);
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
                           ps.status_name as payment_status_name
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
                    WHERE o.order_number = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
                // ดึงรายการสินค้าในออเดอร์
                $order['items'] = $this->getOrderItems($order['order_id']);
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
     * ดึงออเดอร์ทั้งหมดของสมาชิก
     */
    public function getOrdersByMember($memberID, $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT o.*, 
                           pm.bank, 
                           ps.status_name as payment_status_name,
                           COUNT(oi.order_item_id) as item_count
                    FROM orders o
                    LEFT JOIN payment_methods pm ON o.payment_method_id = pm.id
                    LEFT JOIN payment_status ps ON o.payment_status_id = ps.id
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
     * อัปเดตสถานะการชำระเงิน
     */
    public function updatePaymentStatus($orderID, $paymentStatusID, $paymentSlipPath = null, $trackingNumber = null)
    {
        try {
            $sql = "UPDATE orders SET 
                    payment_status_id = ?, 
                    payment_slip_path = COALESCE(?, payment_slip_path),
                    tracking_number = COALESCE(?, tracking_number),
                    update_at = NOW()
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$paymentStatusID, $paymentSlipPath, $trackingNumber, $orderID]);
            
            return [
                'success' => $result,
                'message' => $result ? 'อัปเดตสถานะเรียบร้อยแล้ว' : 'ไม่สามารถอัปเดตสถานะได้'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * อัปเดตสถานะออเดอร์
     */
    public function updateOrderStatus($orderID, $orderStatus)
    {
        try {
            $sql = "UPDATE orders SET order_status = ?, update_at = NOW() WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$orderStatus, $orderID]);
            
            return [
                'success' => $result,
                'message' => $result ? 'อัปเดตสถานะออเดอร์เรียบร้อยแล้ว' : 'ไม่สามารถอัปเดตสถานะออเดอร์ได้'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * อัปโหลด Payment Slip
     */
    public function uploadPaymentSlip($orderID, $paymentSlipPath)
    {
        try {
            $sql = "UPDATE orders SET 
                    payment_slip_path = ?, 
                    payment_status_id = 2,
                    update_at = NOW() 
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$paymentSlipPath, $orderID]);
            
            return [
                'success' => $result,
                'message' => $result ? 'อัปโหลดหลักฐานการชำระเงินเรียบร้อยแล้ว' : 'ไม่สามารถอัปโหลดหลักฐานได้'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ตั้งค่าหมายเลขพัสดุ
     */
    public function setTrackingNumber($orderID, $trackingNumber)
    {
        try {
            $sql = "UPDATE orders SET 
                    tracking_number = ?, 
                    order_status = 'shipped',
                    update_at = NOW() 
                    WHERE order_id = ?";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$trackingNumber, $orderID]);
            
            return [
                'success' => $result,
                'message' => $result ? 'ตั้งค่าหมายเลขพัสดุเรียบร้อยแล้ว' : 'ไม่สามารถตั้งค่าหมายเลขพัสดุได้'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ยกเลิกออเดอร์
     */
    public function cancelOrder($orderID)
    {
        try {
            $sql = "UPDATE orders SET order_status = 'cancelled', update_at = NOW() WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$orderID]);
            
            return [
                'success' => $result,
                'message' => $result ? 'ยกเลิกออเดอร์เรียบร้อยแล้ว' : 'ไม่สามารถยกเลิกออเดอร์ได้'
            ];

        } catch (Exception $e) {
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