<?php
require_once 'config.php';

class PaymentMethodController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ เพิ่มข้อมูลใหม่
    public function create($bank, $account_number, $name, $url_path) {
        $sqlLastId = "SELECT payment_method_id FROM payment_method ORDER BY payment_method_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        if ($lastIdRow) {
            $lastNumber = (int)substr($lastIdRow['payment_method_id'], 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $paymentMethodId = 'PM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO payment_method 
                (payment_method_id, bank, account_number, name, url_path, create_at) 
                VALUES (:id, :bank, :account_number, :name, :url_path, NOW())";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $paymentMethodId,
            ':bank' => $bank,
            ':account_number' => $account_number,
            ':name' => $name,
            ':url_path' => $url_path
        ]);
    }

    // ✅ อ่านข้อมูลทั้งหมด
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM payment_method WHERE delete_at IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ อ่านข้อมูลตาม ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payment_method WHERE payment_method_id = :id AND delete_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ อัปเดตข้อมูล
    public function update($id, $bank, $account_number, $name, $url_path) {
        $sql = "UPDATE payment_method 
                SET bank = :bank, account_number = :account_number, name = :name, url_path = :url_path, update_at = NOW() 
                WHERE payment_method_id = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':bank' => $bank,
            ':account_number' => $account_number,
            ':name' => $name,
            ':url_path' => $url_path,
            ':id' => $id
        ]);
    }

    // ✅ ลบแบบ Soft Delete
    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE payment_method SET delete_at = NOW() WHERE payment_method_id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
