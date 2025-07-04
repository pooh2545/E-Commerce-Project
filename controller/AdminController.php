<?php
require_once 'config.php'; // ไฟล์นี้ควรมีการเชื่อมต่อ PDO

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ Create Admin
    public function create($username, $password) {
    // 1. ค้นหา admin_id ล่าสุด
    $sqlLastId = "SELECT admin_id FROM admin ORDER BY admin_id DESC LIMIT 1";
    $stmtLast = $this->pdo->prepare($sqlLastId);
    $stmtLast->execute();
    $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

    if ($lastIdRow) {
        // ดึงตัวเลขจาก admin_id เช่น AD009 => 9
        $lastNumber = (int)substr($lastIdRow['admin_id'], 2);
        $nextNumber = $lastNumber + 1;
    } else {
        // ถ้ายังไม่มีข้อมูลเลย
        $nextNumber = 1;
    }

    // สร้าง admin_id ใหม่ เช่น AD001
    $newAdminId = 'AD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    // 2. บันทึกข้อมูล
    $sqlInsert = "INSERT INTO admin (admin_id, username, password, create_at) 
                  VALUES (:admin_id, :username, :password, NOW())";
    $stmtInsert = $this->pdo->prepare($sqlInsert);
    return $stmtInsert->execute([
        ':admin_id' => $newAdminId,
        ':username' => $username,
        ':password' => password_hash($password, PASSWORD_DEFAULT)
    ]);
}

    // ✅ Read All Admins
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM admin");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Read One Admin by ID
    public function getById($admin_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE admin_id = :admin_id");
        $stmt->execute([':admin_id' => $admin_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Update Admin
    public function update($admin_id, $username, $password = null) {
        $fields = "username = :username, update_at = NOW()";
        $params = [':admin_id' => $admin_id, ':username' => $username];

        if ($password !== null) {
            $fields .= ", password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE admin SET $fields WHERE admin_id = :admin_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ✅ Delete Admin
    public function delete($admin_id) {
        $stmt = $this->pdo->prepare("DELETE FROM admin WHERE admin_id = :admin_id");
        return $stmt->execute([':admin_id' => $admin_id]);
    }

    // ✅ Login (Check Credentials + Update last_login)
    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Update last_login
            $update = $this->pdo->prepare("UPDATE admin SET last_login = NOW() WHERE admin_id = :id");
            $update->execute([':id' => $admin['admin_id']]);
            return $admin;
        }

        return false;
    }
}
