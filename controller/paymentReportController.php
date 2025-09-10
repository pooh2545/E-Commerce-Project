<?php
class PaymentReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูลการชำระเงินทั้งหมด (ยกเว้น status 5)
    public function getPaymentReport() {
        $sql = "
            SELECT 
                o.order_number,
                o.recipient_name,
                o.shipping_phone,
                o.create_at,
                o.total_amount,
                o.payment_slip_path,
                s.name AS order_status_name
            FROM orders o
            LEFT JOIN order_status s ON o.order_status = s.order_status_id
            WHERE o.order_status <> 5
            ORDER BY o.create_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ ดึงข้อมูลการชำระเงินแบบรายตัว (ยกเว้น status 5)
    public function getPaymentById($order_number) {
        $sql = "
            SELECT 
                o.order_number,
                o.recipient_name,
                o.shipping_phone,
                o.create_at,
                o.total_amount,
                o.payment_slip_path,
                s.name AS order_status_name
            FROM orders o
            LEFT JOIN order_status s ON o.order_status = s.order_status_id
            WHERE o.order_number = :order_number
              AND o.order_status <> 5
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':order_number', $order_number, PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $data : ['status' => 'error', 'message' => 'ไม่พบข้อมูล'];
    }
}
?>
