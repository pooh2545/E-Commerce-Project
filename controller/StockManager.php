<?php
class StockManager
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * ตรวจสอบและจองสต็อกสินค้า
     */
    public function validateAndReserveStock($items)
    {
        try {
            $insufficientStock = [];

            foreach ($items as $item) {
                // ตรวจสอบสต็อกปัจจุบัน
                $sql = "SELECT stock, name FROM shoe WHERE shoe_id = ? FOR UPDATE";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$item['shoe_id']]);
                $shoe = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$shoe) {
                    $insufficientStock[] = "ไม่พบสินค้า ID: {$item['shoe_id']}";
                    continue;
                }

                if ($shoe['stock'] < $item['quantity']) {
                    $insufficientStock[] = "{$shoe['name']} (คงเหลือ: {$shoe['stock']}, ต้องการ: {$item['quantity']})";
                }
            }

            if (!empty($insufficientStock)) {
                return [
                    'success' => false,
                    'message' => 'สต็อกสินค้าไม่เพียงพอ: ' . implode(', ', $insufficientStock)
                ];
            }

            return ['success' => true];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาดในการตรวจสอบสต็อก: ' . $e->getMessage()
            ];
        }
    }

    /**
     * อัปเดตสต็อกสินค้า
     */
    public function updateStock($shoeID, $quantity, $orderID = null, $action = 'MANUAL')
    {
        try {
            // อัปเดตสต็อกในตาราง shoe
            $sql = "UPDATE shoe SET 
                    stock = stock + ?, 
                    update_at = NOW() 
                    WHERE shoe_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$quantity, $shoeID]);

            if ($result) {
                // บันทึกประวัติการเปลี่ยนแปลงสต็อก
                $this->logStockMovement($shoeID, $quantity, $orderID, $action);
            }

            return $result;
        } catch (Exception $e) {
            error_log("Error updating stock: " . $e->getMessage());
            return false;
        }
    }

    /**
     * บันทึกประวัติการเปลี่ยนแปลงสต็อก
     */
    public function logStockMovement($shoeID, $quantity, $orderID = null, $action = 'MANUAL', $notes = null)
    {
        try {
            // ดึงสต็อกปัจจุบัน
            $sql = "SELECT stock FROM shoe WHERE shoe_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$shoeID]);
            $currentStock = $stmt->fetchColumn();

            // บันทึกประวัติ (สมมติว่ามีตาราง stock_movements)
            $sql = "INSERT INTO stock_movements 
                    (shoe_id, order_id, movement_type, quantity, stock_before, stock_after, action, notes, create_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

            $movementType = $quantity > 0 ? 'IN' : 'OUT';
            $stockBefore = $currentStock - $quantity;

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                $shoeID,
                $orderID,
                $movementType,
                abs($quantity),
                $stockBefore,
                $currentStock,
                $action,
                $notes
            ]);
        } catch (Exception $e) {
            error_log("Error logging stock movement: " . $e->getMessage());
            return false;
        }
    }

    /**
     * คืนสต็อกสินค้าเมื่อยกเลิกออเดอร์
     */
    public function restoreOrderStock($orderID)
    {
        try {
            // ดึงรายการสินค้าในออเดอร์
            $sql = "SELECT shoe_id, quantity FROM order_items WHERE order_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($orderItems as $item) {
                // เพิ่มสต็อกกลับ
                $this->updateStock($item['shoe_id'], $item['quantity'], $orderID, 'ORDER_CANCEL');
            }

            return true;
        } catch (Exception $e) {
            error_log("Error restoring stock for order {$orderID}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ดึงรายงานสต็อกสินค้า
     */
    public function getStockReport($lowStockThreshold = 10)
    {
        try {
            $sql = "SELECT s.shoe_id, s.name, s.stock, s.price, st.name as shoe_type,
                           CASE 
                               WHEN s.stock <= 0 THEN 'OUT_OF_STOCK'
                               WHEN s.stock <= ? THEN 'LOW_STOCK'
                               ELSE 'IN_STOCK'
                           END as stock_status
                    FROM shoe s
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                    ORDER BY s.stock ASC, s.name ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$lowStockThreshold]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting stock report: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงประวัติการเปลี่ยนแปลงสต็อกสินค้า
     */
    public function getStockMovementHistory($shoeID = null, $startDate = null, $endDate = null, $limit = 100)
    {
        try {
            $whereConditions = [];
            $params = [];

            if ($shoeID) {
                $whereConditions[] = "sm.shoe_id = ?";
                $params[] = $shoeID;
            }

            if ($startDate && $endDate) {
                $whereConditions[] = "DATE(sm.create_at) BETWEEN ? AND ?";
                $params[] = $startDate;
                $params[] = $endDate;
            }

            $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
            $params[] = $limit;

            $sql = "SELECT sm.*, s.name as shoe_name, o.order_number,
                           CASE 
                               WHEN sm.movement_type = 'IN' THEN 'เพิ่มสต็อก'
                               WHEN sm.movement_type = 'OUT' THEN 'ลดสต็อก'
                               ELSE sm.movement_type
                           END as movement_type_text
                    FROM stock_movements sm
                    LEFT JOIN shoe s ON sm.shoe_id = s.shoe_id
                    LEFT JOIN orders o ON sm.order_id = o.order_id
                    {$whereClause}
                    ORDER BY sm.create_at DESC
                    LIMIT ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting stock movement history: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ปรับปรุงสต็อกสินค้าด้วยตนเอง (สำหรับ Admin)
     */
    public function adjustStock($shoeID, $newQuantity, $changedBy = null, $notes = null)
    {
        try {
            $this->pdo->beginTransaction();

            // ดึงสต็อกปัจจุบัน
            $sql = "SELECT stock, name FROM shoe WHERE shoe_id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$shoeID]);
            $shoe = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$shoe) {
                throw new Exception('ไม่พบสินค้า');
            }

            $currentStock = $shoe['stock'];
            $difference = $newQuantity - $currentStock;

            if ($difference != 0) {
                // อัปเดตสต็อก
                $sql = "UPDATE shoe SET stock = ?, update_at = NOW() WHERE shoe_id = ?";
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([$newQuantity, $shoeID]);

                if ($result) {
                    // บันทึกประวัติการปรับปรุง
                    $adjustNotes = ($notes ?? '') . " (ปรับจาก {$currentStock} เป็น {$newQuantity})";
                    $this->logStockMovement($shoeID, $difference, null, 'MANUAL_ADJUST', $adjustNotes);
                }
            }

            $this->pdo->commit();

            return [
                'success' => true,
                'old_quantity' => $currentStock,
                'new_quantity' => $newQuantity,
                'difference' => $difference,
                'shoe_name' => $shoe['name'],
                'message' => $difference != 0 ? 'ปรับปรุงสต็อกเรียบร้อยแล้ว' : 'สต็อกไม่มีการเปลี่ยนแปลง'
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
     * ตรวจสอบความถูกต้องของสต็อก (Data Integrity Check)
     */
    public function validateStockIntegrity()
    {
        try {
            $issues = [];

            // ตรวจสอบสต็อกติดลบ
            $sql = "SELECT shoe_id, name, stock FROM shoe WHERE stock < 0";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $negativeStock = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!empty($negativeStock)) {
                $issues['negative_stock'] = $negativeStock;
            }

            // ตรวจสอบสินค้าที่ไม่มีในตาราง shoe แต่มีใน stock_movements
            $sql = "SELECT DISTINCT sm.shoe_id 
                    FROM stock_movements sm 
                    LEFT JOIN shoe s ON sm.shoe_id = s.shoe_id 
                    WHERE s.shoe_id IS NULL";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $orphanedMovements = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($orphanedMovements)) {
                $issues['orphaned_movements'] = $orphanedMovements;
            }

            return [
                'success' => true,
                'issues' => $issues,
                'message' => empty($issues) ? 'สต็อกสินค้าถูกต้อง' : 'พบปัญหาในระบบสต็อก'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * ดึงสินค้าที่มีสต็อกต่ำ
     */
    public function getLowStockItems($threshold = 10)
    {
        try {
            $sql = "SELECT s.shoe_id, s.name, s.stock, s.price, st.name as shoe_type
                    FROM shoe s
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                    WHERE s.stock <= ? AND s.stock > 0
                    ORDER BY s.stock ASC, s.name ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$threshold]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting low stock items: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงสินค้าที่หมดสต็อก
     */
    public function getOutOfStockItems()
    {
        try {
            $sql = "SELECT s.shoe_id, s.name, s.price, st.name as shoe_type
                    FROM shoe s
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                    WHERE s.stock <= 0
                    ORDER BY s.name ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting out of stock items: " . $e->getMessage());
            return [];
        }
    }

    /**
     * คำนวณมูลค่าสต็อกรวม
     */
    public function calculateTotalStockValue()
    {
        try {
            $sql = "SELECT 
                        SUM(s.stock * s.price) as total_value,
                        COUNT(s.shoe_id) as total_items,
                        SUM(s.stock) as total_quantity,
                        AVG(s.price) as average_price
                    FROM shoe s 
                    WHERE s.stock > 0";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error calculating stock value: " . $e->getMessage());
            return null;
        }
    }

    /**
     * รายงานการเคลื่อนไหวสต็อกรายวัน
     */
    public function getDailyStockMovementSummary($startDate = null, $endDate = null)
    {
        try {
            $whereClause = "";
            $params = [];

            if ($startDate && $endDate) {
                $whereClause = "WHERE DATE(sm.create_at) BETWEEN ? AND ?";
                $params = [$startDate, $endDate];
            }

            $sql = "SELECT 
                        DATE(sm.create_at) as movement_date,
                        sm.movement_type,
                        COUNT(sm.movement_id) as transaction_count,
                        SUM(sm.quantity) as total_quantity,
                        COUNT(DISTINCT sm.shoe_id) as unique_products
                    FROM stock_movements sm
                    {$whereClause}
                    GROUP BY DATE(sm.create_at), sm.movement_type
                    ORDER BY movement_date DESC, sm.movement_type";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting daily stock movement summary: " . $e->getMessage());
            return [];
        }
    }
}