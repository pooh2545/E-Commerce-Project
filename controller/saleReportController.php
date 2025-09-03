<?php
class SaleReportController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูล(เอาเฉพาะบางช่อง)
    public function getSaleReport() {
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





 } 


?>