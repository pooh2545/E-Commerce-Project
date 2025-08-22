<?php
class SiteContentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ ดึงข้อมูลทั้งหมด
    public function getAll() {
        $sql = "SELECT * FROM site_content WHERE delete_at IS NULL ORDER BY create_at DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ ดึงข้อมูลหน้าเดียวจาก page_name
    public function getByPageName($page_name) {
        $sql = "SELECT * FROM site_content WHERE page_name = :page_name AND delete_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['page_name' => $page_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
