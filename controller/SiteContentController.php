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

    // ✅ ดึงข้อมูลหน้าเดียวจาก content_id
    public function getById($content_id) {
        $sql = "SELECT * FROM site_content WHERE content_id = :id AND delete_at IS NULL LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $content_id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['url_path']) {
            $row['url_path'] = '../' . $row['url_path']; // ปรับ path ให้ตรง frontend
        }
        return $row;
    }
}
