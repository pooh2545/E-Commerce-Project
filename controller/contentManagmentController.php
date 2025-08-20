<?php 
require_once 'config.php';

class ContentManagmentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ เพิ่มข้อมูลใหม่
    public function create($page_name, $content, $url_path, $custom_code) {
        $sqlLastId = "SELECT content_id FROM site_content ORDER BY content_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        $nextNumber = $lastIdRow ? (int)substr($lastIdRow['content_id'], 2) + 1 : 1;
        $contentId = 'CM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO site_content 
                (content_id, page_name, content, url_path, custom_code, create_at) 
                VALUES (:id, :page_name, :content, :url_path, :custom_code, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $contentId,
            ':page_name' => $page_name,
            ':content' => $content,
            ':url_path' => $url_path,
            ':custom_code' => $custom_code
        ]);
    }

    // ✅ อ่านข้อมูลทั้งหมด
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM site_content WHERE delete_at IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ อ่านข้อมูลตาม content_id
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM site_content WHERE content_id = :id AND delete_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ อ่านข้อมูลตาม page_name
    public function getByPageName($page_name) {
        $stmt = $this->pdo->prepare("SELECT * FROM site_content WHERE page_name = :page_name AND delete_at IS NULL");
        $stmt->execute([':page_name' => $page_name]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ อัปเดตตาม content_id
    public function update($id, $page_name, $content, $url_path, $custom_code) {
        $sql = "UPDATE site_content 
                SET page_name = :page_name, content = :content, url_path = :url_path, custom_code = :custom_code, update_at = NOW() 
                WHERE content_id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':page_name' => $page_name,
            ':content' => $content,
            ':url_path' => $url_path,
            ':custom_code' => $custom_code,
            ':id' => $id
        ]);
    }

    // ✅ อัปเดตตาม page_name
    public function updateByPageName($page_name, $content, $url_path, $custom_code) {
        $sql = "UPDATE site_content 
                SET content = :content, url_path = :url_path, custom_code = :custom_code, update_at = NOW() 
                WHERE page_name = :page_name";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':content' => $content,
            ':url_path' => $url_path,
            ':custom_code' => $custom_code,
            ':page_name' => $page_name
        ]);
    }

    // ✅ ลบแบบ Soft Delete
    public function delete($page_name) {
        $stmt = $this->pdo->prepare("UPDATE site_content SET delete_at = NOW() WHERE page_name = :page_name AND delete_at IS NULL");
        $stmt->execute([':page_name' => $page_name]);
        return $stmt->rowCount() > 0;
    }
}
