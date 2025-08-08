<?php
require_once 'config.php';

class ShoetypeController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ สร้างหมวดหมู่ใหม่
    public function create($name, $imageFilename) {
        // 1. ค้นหา member_id ล่าสุด
        $sqlLastId = "SELECT shoetype_id FROM shoetype ORDER BY shoetype_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        if ($lastIdRow) {
            // ดึงตัวเลขจาก member_id เช่น MB009 => 9
            $lastNumber = (int)substr($lastIdRow['shoetype_id'], 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // สร้าง member_id ใหม่ เช่น MB001
        $shoeTypeId = 'ST' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);


        $sql = "INSERT INTO shoetype (shoetype_id, name, images, create_at) VALUES (:shoetype_id, :name, :image, NOW())";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':shoetype_id' => $shoeTypeId,
            ':name' => $name,
            ':image' => $imageFilename
        ]);
    }

    // ✅ อ่านหมวดหมู่ทั้งหมด (ไม่รวมที่ลบแล้ว)
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM shoetype WHERE delete_at IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ อ่านหมวดหมู่ตาม ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM shoetype WHERE shoetype_id = :id AND delete_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ แก้ไขหมวดหมู่ (ชื่อ และ/หรือ รูป)
    public function update($id, $name, $imageFilename = null) {
        $params = [':id' => $id, ':name' => $name];
        $sql = "UPDATE shoetype SET name = :name";

        if ($imageFilename !== null) {
            $sql .= ", images = :image";
            $params[':image'] = $imageFilename;
        }

        $sql .= ", update_at = NOW() WHERE shoetype_id = :id";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ✅ ลบหมวดหมู่แบบ soft delete
    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE shoetype SET delete_at = NOW() WHERE shoetype_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}