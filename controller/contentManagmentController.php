<?php
require_once 'config.php';

class ContentManagmentController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // เพิ่มหน้าใหม่
    public function create($page_name, $content, $url_path, $custom_code) {
        // สร้าง content_id อัตโนมัติ
        $stmtId = $this->pdo->query("SELECT content_id FROM site_content ORDER BY content_id DESC LIMIT 1");
        $last = $stmtId->fetch(PDO::FETCH_ASSOC);
        $nextNumber = $last ? (int)substr($last['content_id'],2)+1 : 1;
        $content_id = 'CM' . str_pad($nextNumber,3,'0',STR_PAD_LEFT);

        $createAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("INSERT INTO site_content 
            (content_id, page_name, content, url_path, custom_code, create_at) 
            VALUES (:id, :page_name, :content, :url_path, :custom_code, :create_at)");

        return $stmt->execute([
            ':id'=>$content_id,
            ':page_name'=>$page_name,
            ':content'=>$content,
            ':url_path'=>$url_path,
            ':custom_code'=>$custom_code,
            ':create_at'=>$createAt
        ]);
    }

    // อ่านข้อมูลทั้งหมด
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM site_content WHERE delete_at IS NULL ORDER BY create_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // อ่านข้อมูลตาม page_name
    public function getByPageName($page_name) {
        $stmt = $this->pdo->prepare("SELECT * FROM site_content WHERE page_name=:page_name AND delete_at IS NULL");
        $stmt->execute([':page_name'=>$page_name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row && $row['url_path']) {
            // ปรับ path ให้ตรงกับ frontend
            $row['url_path'] = '../' . $row['url_path'];
        }
        return $row;
    }

    // อัปเดตหน้า
    public function update($id, $page_name, $content, $url_path, $custom_code) {
        $updateAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE site_content 
            SET page_name=:page_name, content=:content, url_path=:url_path, custom_code=:custom_code, update_at=:update_at
            WHERE content_id=:id");
        return $stmt->execute([
            ':page_name'=>$page_name,
            ':content'=>$content,
            ':url_path'=>$url_path,
            ':custom_code'=>$custom_code,
            ':update_at'=>$updateAt,
            ':id'=>$id
        ]);
    }

    // ลบแบบ Soft Delete
    public function delete($page_name) {
        $deleteAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE site_content SET delete_at=:delete_at WHERE page_name=:page_name AND delete_at IS NULL");
        $stmt->execute([':delete_at'=>$deleteAt, ':page_name'=>$page_name]);
        return $stmt->rowCount()>0;
    }

// ลบเฉพาะรูปภาพ
    public function deleteImage($page_name) {
        $updateAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE site_content 
            SET url_path=NULL, update_at=:update_at 
            WHERE page_name=:page_name AND delete_at IS NULL");
        $stmt->execute([
            ':page_name'=>$page_name,
            ':update_at'=>$updateAt
        ]);
        return $stmt->rowCount() > 0;
    }

// อัปเดตหน้าโดยใช้ชื่อหน้าเดิม(old) → ชื่อใหม่(new)
    public function updateByPageName($old_page_name, $new_page_name, $content, $url_path, $custom_code) {
        $row = $this->getByPageName($old_page_name);
        if (!$row) return false;

        $updateAt = date('Y-m-d H:i:s');

        $stmt = $this->pdo->prepare("UPDATE site_content 
            SET page_name=:new_page_name, content=:content, url_path=:url_path, custom_code=:custom_code, update_at=:update_at
            WHERE content_id=:id");
        
        return $stmt->execute([
            ':new_page_name' => $new_page_name,
            ':content' => $content,
            ':url_path' => $url_path,
            ':custom_code' => $custom_code,
            ':update_at' => $updateAt,
            ':id' => $row['content_id']
        ]);
    }

}
