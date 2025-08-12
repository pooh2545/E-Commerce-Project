<?php
require_once 'config.php';

class ProductController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ สร้างสินค้าใหม่
    public function create($name, $detail, $price, $stock, $shoetypeId, $size, $imageFilename) {
        try {
            // 1. ค้นหา shoe_id ล่าสุด
            $sqlLastId = "SELECT shoe_id FROM shoe ORDER BY shoe_id DESC LIMIT 1";
            $stmtLast = $this->pdo->prepare($sqlLastId);
            $stmtLast->execute();
            $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

            if ($lastIdRow) {
                // ดึงตัวเลขจาก shoe_id เช่น PD001 => 1
                $lastNumber = (int)substr($lastIdRow['shoe_id'], 2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            // สร้าง shoe_id ใหม่ 
            $shoeId = 'PD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $sql = "INSERT INTO shoe (shoe_id, name, detail, price, stock, shoetype_id, size, img_path, create_at) 
                    VALUES (:shoe_id, :name, :detail, :price, :stock, :shoetype_id, :size, :img_path, NOW())";
            
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                ':shoe_id' => $shoeId,
                ':name' => $name,
                ':detail' => $detail,
                ':price' => $price,
                ':stock' => $stock,
                ':shoetype_id' => $shoetypeId,
                ':size' => $size,
                ':img_path' => $imageFilename
            ]);
        } catch (PDOException $e) {
            error_log("Error creating product: " . $e->getMessage());
            return false;
        }
    }

    // ✅ อ่านสินค้าทั้งหมด (ไม่รวมที่ลบแล้ว) พร้อมข้อมูลหมวดหมู่
    public function getAll() {
        try {
            $sql = "SELECT s.*, st.name as category_name 
                    FROM shoe s 
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id 
                    WHERE s.delete_at IS NULL 
                    ORDER BY s.create_at DESC";
            
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting all products: " . $e->getMessage());
            return [];
        }
    }

    // ✅ อ่านสินค้าตาม ID
    public function getById($id) {
        try {
            $sql = "SELECT s.*, st.name as category_name 
                    FROM shoe s 
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id 
                    WHERE s.shoe_id = :id AND s.delete_at IS NULL";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting product by ID: " . $e->getMessage());
            return false;
        }
    }

    // ✅ แก้ไขสินค้า
    public function update($id, $name, $detail, $price, $stock, $shoetypeId, $size, $imageFilename = null) {
        try {
            $params = [
                ':id' => $id, 
                ':name' => $name, 
                ':detail' => $detail, 
                ':price' => $price, 
                ':stock' => $stock, 
                ':shoetype_id' => $shoetypeId, 
                ':size' => $size
            ];
            
            $sql = "UPDATE shoe SET 
                    name = :name, 
                    detail = :detail, 
                    price = :price, 
                    stock = :stock, 
                    shoetype_id = :shoetype_id, 
                    size = :size";

            if ($imageFilename !== null && $imageFilename !== '') {
                $sql .= ", img_path = :img_path";
                $params[':img_path'] = $imageFilename;
            }

            $sql .= ", update_at = NOW() WHERE shoe_id = :id";

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Error updating product: " . $e->getMessage());
            return false;
        }
    }

    // ✅ ลบสินค้าแบบ soft delete
    public function delete($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM shoe WHERE shoe_id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Error deleting product: " . $e->getMessage());
            return false;
        }
    }

    // ✅ ดึงหมวดหมู่ทั้งหมดสำหรับ dropdown
    public function getCategories() {
        try {
            $stmt = $this->pdo->query("SELECT shoetype_id, name FROM shoetype WHERE delete_at IS NULL ORDER BY name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting categories: " . $e->getMessage());
            return [];
        }
    }
}