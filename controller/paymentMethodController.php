<?php 
require_once 'config.php';

class PaymentMethodController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ เพิ่มข้อมูลใหม่
    public function create($bank, $account_number, $name, $url_path) {
        if (empty($bank) || empty($account_number) || empty($name)) {
            return false; // กันแถวว่าง
        }

        $sqlLastId = "SELECT payment_method_id FROM payment_method ORDER BY payment_method_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        $nextNumber = $lastIdRow ? ((int)substr($lastIdRow['payment_method_id'],2) + 1) : 1;
        $paymentMethodId = 'PM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $createAt = date('Y-m-d H:i:s');
        $sql = "INSERT INTO payment_method 
                (payment_method_id, bank, account_number, name, url_path, create_at) 
                VALUES (:id, :bank, :account_number, :name, :url_path, :create_at)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $paymentMethodId,
            ':bank' => $bank,
            ':account_number' => $account_number,
            ':name' => $name,
            ':url_path' => $url_path,
            ':create_at' => $createAt
        ]);
    }

    // อ่านข้อมูลทั้งหมด
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM payment_method WHERE delete_at IS NULL");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // อ่านข้อมูลตาม ID
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM payment_method WHERE payment_method_id = :id AND delete_at IS NULL");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // อัปเดตข้อมูล (รองรับแก้ไขบางฟิลด์)
    public function update($id, $bank = null, $account_number = null, $name = null, $url_path = null) {
        $fields = [];
        $params = [':id' => $id];

        if ($bank !== null) { $fields[] = "bank = :bank"; $params[':bank'] = $bank; }
        if ($account_number !== null) { $fields[] = "account_number = :account_number"; $params[':account_number'] = $account_number; }
        if ($name !== null) { $fields[] = "name = :name"; $params[':name'] = $name; }
        if ($url_path !== null) { $fields[] = "url_path = :url_path"; $params[':url_path'] = $url_path; }

        if (empty($fields)) return false;

        $updateAt = date('Y-m-d H:i:s');
        $sql = "UPDATE payment_method SET " . implode(', ', $fields) . ", update_at = :update_at WHERE payment_method_id = :id";
        $params[':update_at'] = $updateAt;
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ลบแบบ Soft Delete
    public function delete($id) {
        $deleteAt = date('Y-m-d H:i:s');
        $stmt = $this->pdo->prepare("UPDATE payment_method SET delete_at = :delete_at WHERE payment_method_id = :id");
        return $stmt->execute([':delete_at' => $deleteAt, ':id' => $id]);
    }
}
