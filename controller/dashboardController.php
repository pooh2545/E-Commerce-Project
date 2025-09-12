<?php
class DashboardController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูล Dashboard summary
    public function getDashboardSummary() {
        $summary = [];

        // สมาชิกทั้งหมด
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS total_members FROM member");
        $stmt->execute();
        $summaryMembers = $stmt->fetch(PDO::FETCH_ASSOC);
        $summary['total_members'] = $summaryMembers['total_members'] ?? 0;

        // คำสั่งซื้อวันนี้ + รายได้วันนี้
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) AS today_orders, 
                   SUM(total_amount) AS today_revenue
            FROM orders
            WHERE DATE(create_at) = CURDATE()
        ");
        $stmt->execute();
        $todaySummary = $stmt->fetch(PDO::FETCH_ASSOC);
        $summary['today_orders'] = $todaySummary['today_orders'] ?? 0;
        $summary['today_revenue'] = $todaySummary['today_revenue'] ?? 0;

        // ✅ จำนวนสินค้าใกล้หมด (stock < 5)
        $stmt = $this->pdo->prepare("SELECT COUNT(*) AS low_stock_products FROM shoe WHERE stock < 5");
        $stmt->execute();
        $summaryLowStock = $stmt->fetch(PDO::FETCH_ASSOC);
        $summary['low_stock_products'] = $summaryLowStock['low_stock_products'] ?? 0;

        return $summary;
    }

    // ✅ ดึงคำสั่งซื้อทั้งหมด (สำหรับตาราง + กราฟ)
    public function getDashboardAll() {
        $sql = "
            SELECT 
                o.order_number,
                o.recipient_name,
                o.shipping_phone,
                o.create_at,
                o.total_amount,
                s.name AS order_status_name
            FROM orders o
            LEFT JOIN order_status s ON o.order_status = s.order_status_id
            ORDER BY o.create_at DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
