<?php
class SaleReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูลรายงานยอดขายทั้งหมด
    public function getSaleReport() {
        $sql = "
            SELECT 
                s.shoe_id,
                s.name,
                s.shoetype_id,
                st.name AS category_name,   -- ดึงชื่อหมวดหมู่
                s.size,
                s.price,
                oi.quantity,
                (oi.quantity * s.price) AS total_price,
                oi.create_at AS order_date
            FROM order_items AS oi
            INNER JOIN shoe AS s ON oi.shoe_id = s.shoe_id
            LEFT JOIN shoetype AS st ON s.shoetype_id = st.shoetype_id
            ORDER BY oi.create_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ ดึงข้อมูลสินค้ารายตัว
    public function getShoeById($id) {
        $sql = "
            SELECT 
                s.shoe_id,
                s.name,
                s.shoetype_id,
                st.name AS category_name,   -- ดึงชื่อหมวดหมู่
                s.size,
                s.price,
                oi.quantity,
                (oi.quantity * s.price) AS total_price,
                oi.create_at AS order_date
            FROM order_items AS oi
            INNER JOIN shoe AS s ON oi.shoe_id = s.shoe_id
            LEFT JOIN shoetype AS st ON s.shoetype_id = st.shoetype_id
            WHERE s.shoe_id = :id
            ORDER BY oi.create_at DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
