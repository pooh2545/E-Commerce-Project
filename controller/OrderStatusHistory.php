<?php
class OrderStatusHistory
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * เพิ่มประวัติการเปลี่ยนสถานะ
     */
    public function addHistory($orderID, $newStatusID, $changedBy = null, $notes = null)
    {
        try {
            $paymentUpdateAt = date('Y-m-d H:i:s');
            $sql = "INSERT INTO order_status_history (order_id, new_status, changed_by, notes, create_at) 
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$orderID, $newStatusID, $changedBy, $notes,$paymentUpdateAt]);
        } catch (Exception $e) {
            error_log("Error adding order status history: " . $e->getMessage());
            return false;
        }
    }

    /**
     * ดึงประวัติการเปลี่ยนสถานะของออเดอร์
     */
    public function getHistoryByOrderId($orderID)
    {
        try {
            $sql = "SELECT osh.*, os.name as status_name, 
                           m.fname, m.lname,
                           CASE 
                               WHEN osh.changed_by IS NULL THEN 'SYSTEM'
                               WHEN osh.changed_by = 'SYSTEM' THEN 'ระบบอัตโนมัติ'
                               ELSE CONCAT(COALESCE(m.fname, ''), ' ', COALESCE(m.lname, ''))
                           END as changed_by_name
                    FROM order_status_history osh
                    LEFT JOIN order_status os ON osh.new_status = os.order_status_id
                    LEFT JOIN members m ON osh.changed_by = m.member_id
                    WHERE osh.order_id = ?
                    ORDER BY osh.create_at ASC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderID]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting order status history: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงประวัติการเปลี่ยนสถานะทั้งหมดในช่วงเวลา
     */
    public function getHistoryByDateRange($startDate, $endDate, $statusId = null, $limit = 100)
    {
        try {
            $whereConditions = ["DATE(osh.create_at) BETWEEN ? AND ?"];
            $params = [$startDate, $endDate];

            if ($statusId) {
                $whereConditions[] = "osh.new_status = ?";
                $params[] = $statusId;
            }

            $whereClause = 'WHERE ' . implode(' AND ', $whereConditions);
            $params[] = $limit;

            $sql = "SELECT osh.*, os.name as status_name, o.order_number,
                           CASE 
                               WHEN osh.changed_by IS NULL THEN 'SYSTEM'
                               WHEN osh.changed_by = 'SYSTEM' THEN 'ระบบอัตโนมัติ'
                               ELSE CONCAT(COALESCE(m.fname, ''), ' ', COALESCE(m.lname, ''))
                           END as changed_by_name
                    FROM order_status_history osh
                    LEFT JOIN order_status os ON osh.new_status = os.order_status_id
                    LEFT JOIN orders o ON osh.order_id = o.order_id
                    LEFT JOIN members m ON osh.changed_by = m.member_id
                    {$whereClause}
                    ORDER BY osh.create_at DESC
                    LIMIT ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting order status history by date range: " . $e->getMessage());
            return [];
        }
    }

    /**
     * นับจำนวนการเปลี่ยนสถานะในแต่ละสถานะ
     */
    public function getStatusChangeStats($startDate = null, $endDate = null)
    {
        try {
            $whereClause = "";
            $params = [];

            if ($startDate && $endDate) {
                $whereClause = "WHERE DATE(osh.create_at) BETWEEN ? AND ?";
                $params = [$startDate, $endDate];
            }

            $sql = "SELECT os.name as status_name, 
                           COUNT(osh.history_id) as change_count,
                           osh.new_status as status_id
                    FROM order_status_history osh
                    LEFT JOIN order_status os ON osh.new_status = os.order_status_id
                    {$whereClause}
                    GROUP BY osh.new_status, os.name
                    ORDER BY change_count DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting status change statistics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ดึงออเดอร์ที่มีการเปลี่ยนสถานะล่าสุด
     */
    public function getRecentStatusChanges($limit = 20)
    {
        try {
            $sql = "SELECT osh.*, os.name as status_name, o.order_number, o.total_amount,
                           m.fname, m.lname, m.email,
                           CASE 
                               WHEN osh.changed_by IS NULL THEN 'SYSTEM'
                               WHEN osh.changed_by = 'SYSTEM' THEN 'ระบบอัตโนมัติ'
                               ELSE CONCAT(COALESCE(changer.fname, ''), ' ', COALESCE(changer.lname, ''))
                           END as changed_by_name
                    FROM order_status_history osh
                    LEFT JOIN order_status os ON osh.new_status = os.order_status_id
                    LEFT JOIN orders o ON osh.order_id = o.order_id
                    LEFT JOIN members m ON o.member_id = m.member_id
                    LEFT JOIN members changer ON osh.changed_by = changer.member_id
                    ORDER BY osh.create_at DESC
                    LIMIT ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting recent status changes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * ลบประวัติเก่าเกินกว่าจำนวนวันที่กำหนด (สำหรับการดูแลรักษาระบบ)
     */
    public function cleanupOldHistory($daysToKeep = 365)
    {
        try {
            $sql = "DELETE FROM order_status_history 
                    WHERE create_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$daysToKeep]);
            
            $deletedRows = $stmt->rowCount();
            
            return [
                'success' => $result,
                'deleted_records' => $deletedRows,
                'message' => "ลบประวัติเก่าเกินกว่า {$daysToKeep} วัน จำนวน {$deletedRows} รายการ"
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ];
        }
    }

    /**
     * สร้างรายงานเวลาเฉลี่ยในแต่ละขั้นตอนของออเดอร์
     */
    public function getOrderProcessingTimeReport($startDate = null, $endDate = null)
    {
        try {
            $whereClause = "";
            $params = [];

            if ($startDate && $endDate) {
                $whereClause = "WHERE DATE(o.create_at) BETWEEN ? AND ?";
                $params = [$startDate, $endDate];
            }

            $sql = "SELECT 
                        -- เวลาเฉลี่ยจากสร้างออเดอร์ถึงชำระเงิน
                        AVG(TIMESTAMPDIFF(HOUR, o.create_at, 
                            (SELECT MIN(osh1.create_at) FROM order_status_history osh1 
                             WHERE osh1.order_id = o.order_id AND osh1.new_status = 2)
                        )) as avg_hours_to_payment,
                        
                        -- เวลาเฉลี่ยจากชำระเงินถึงจัดส่ง
                        AVG(TIMESTAMPDIFF(HOUR, 
                            (SELECT MIN(osh2.create_at) FROM order_status_history osh2 
                             WHERE osh2.order_id = o.order_id AND osh2.new_status = 2),
                            (SELECT MIN(osh3.create_at) FROM order_status_history osh3 
                             WHERE osh3.order_id = o.order_id AND osh3.new_status = 4)
                        )) as avg_hours_to_shipping,
                        
                        -- เวลาเฉลี่ยจากจัดส่งถึงสำเร็จ
                        AVG(TIMESTAMPDIFF(HOUR, 
                            (SELECT MIN(osh4.create_at) FROM order_status_history osh4 
                             WHERE osh4.order_id = o.order_id AND osh4.new_status = 4),
                            (SELECT MIN(osh5.create_at) FROM order_status_history osh5 
                             WHERE osh5.order_id = o.order_id AND osh5.new_status = 6)
                        )) as avg_hours_to_complete,
                        
                        COUNT(o.order_id) as total_orders
                    FROM orders o
                    {$whereClause}
                    AND o.order_status >= 2"; // เฉพาะออเดอร์ที่มีการชำระเงิน

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting order processing time report: " . $e->getMessage());
            return null;
        }
    }
}