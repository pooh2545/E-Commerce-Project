<?php
class StockReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูลรายงานสต็อก (เอาเฉพาะบางช่อง)
    public function getStockReport() {
            $sql = "SELECT s.*, st.name as category_name 
                    FROM shoe s 
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id 
                    WHERE s.delete_at IS NULL 
                    ORDER BY s.create_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ ดึงข้อมูลสินค้าแบบเจาะจง
    public function getShoeById($id) {
            $sql = "SELECT s.*, st.name as category_name 
                    FROM shoe s 
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id 
                    WHERE s.shoe_id = :id AND s.delete_at IS NULL";
                 
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ อัปเดตจำนวนสต็อกสินค้า
    public function updateStock($id, $stock) {
        $sql = "UPDATE shoe 
                SET stock = :stock, update_at = NOW()
                WHERE shoe_id = :id AND delete_at IS NULL";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':stock' => $stock, ':id' => $id]);
    }

    // ✅ ลบสินค้า (soft delete)
    public function deleteShoe($id) {
        $sql = "UPDATE shoe 
                SET delete_at = NOW() 
                WHERE shoe_id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>
