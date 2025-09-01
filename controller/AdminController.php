<?php
require_once 'config.php';

class AdminController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ Create Admin with Role
    public function create($username, $email, $password, $role = 'Employee') {
        // 1. ตรวจสอบว่าอีเมลซ้ำหรือไม่
        $checkEmail = $this->pdo->prepare("SELECT admin_id FROM admin WHERE email = :email");
        $checkEmail->execute([':email' => $email]);
        if ($checkEmail->fetch()) {
            return ['success' => false, 'message' => 'อีเมลนี้มีอยู่ในระบบแล้ว'];
        }

        // 2. สร้าง admin_id ใหม่
        $sqlLastId = "SELECT admin_id FROM admin ORDER BY admin_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        if ($lastIdRow) {
            $lastNumber = (int)substr($lastIdRow['admin_id'], 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newAdminId = 'AD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 3. บันทึกข้อมูล
        $createAt = date('Y-m-d H:i:s');
        $sqlInsert = "INSERT INTO admin (admin_id, username, email, password, role, create_at) 
                      VALUES (:admin_id, :username, :email, :password, :role, :create_at)";
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        $result = $stmtInsert->execute([
            ':admin_id' => $newAdminId,
            ':username' => $username,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => $role,
            ':create_at' => $createAt
        ]);

        return ['success' => $result, 'admin_id' => $newAdminId];
    }

    // ✅ Read All Admins
    public function getAll() {
        $stmt = $this->pdo->query("SELECT admin_id, username, email, role, create_at, update_at, last_login FROM admin ORDER BY create_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Read One Admin by ID
    public function getById($admin_id) {
        $stmt = $this->pdo->prepare("SELECT admin_id, username, email, role, create_at, update_at, last_login FROM admin WHERE admin_id = :admin_id");
        $stmt->execute([':admin_id' => $admin_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Update Admin
    public function update($admin_id, $username, $password = null, $role = null) {
        $updateAt = date('Y-m-d H:i:s');
        $fields = "username = :username, update_at = :update_at";
        $params = [':admin_id' => $admin_id, ':username' => $username, ':update_at' => $updateAt];

        if ($password !== null && !empty($password)) {
            $fields .= ", password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($role !== null) {
            $fields .= ", role = :role";
            $params[':role'] = $role;
        }

        $sql = "UPDATE admin SET $fields WHERE admin_id = :admin_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ✅ Update Role Only
    public function updateRole($admin_id, $role) {
        $updateAt = date('Y-m-d H:i:s');
        $sql = "UPDATE admin SET role = :role, update_at = :update_at WHERE admin_id = :admin_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':admin_id' => $admin_id, ':role' => $role, ':update_at' => $updateAt]);
    }

    // ✅ Delete Admin
    public function delete($admin_id) {
        // ตรวจสอบว่าไม่ใช่ Admin คนสุดท้าย
        $countAdmins = $this->pdo->query("SELECT COUNT(*) as count FROM admin WHERE role = 'Admin'")->fetch();
        if ($countAdmins['count'] <= 1) {
            $checkRole = $this->pdo->prepare("SELECT role FROM admin WHERE admin_id = :admin_id");
            $checkRole->execute([':admin_id' => $admin_id]);
            $adminRole = $checkRole->fetch();
            
            if ($adminRole && $adminRole['role'] === 'Admin') {
                return ['success' => false, 'message' => 'ไม่สามารถลบ Admin คนสุดท้ายได้'];
            }
        }

        $stmt = $this->pdo->prepare("DELETE FROM admin WHERE admin_id = :admin_id");
        $result = $stmt->execute([':admin_id' => $admin_id]);
        return ['success' => $result];
    }

    // ✅ Login (Check Credentials + Update last_login)
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admin WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            // Update last_login
            $lastLogin = date('Y-m-d H:i:s');
            $update = $this->pdo->prepare("UPDATE admin SET last_login = :last_login WHERE admin_id = :id");
            $update->execute([':last_login' => $lastLogin, ':id' => $admin['admin_id']]);
            
            // ไม่ส่งรหัสผ่านกลับไป
            unset($admin['password']);
            return $admin;
        }

        return false;
    }

    // ✅ Check Permission
    public function hasPermission($admin_id, $required_role = 'Admin') {
        $stmt = $this->pdo->prepare("SELECT role FROM admin WHERE admin_id = :admin_id");
        $stmt->execute([':admin_id' => $admin_id]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$admin) return false;
        
        // Admin มีสิทธิ์ทุกอย่าง
        if ($admin['role'] === 'Admin') return true;
        
        // ตรวจสอบสิทธิ์ตามที่ต้องการ
        return $admin['role'] === $required_role;
    }

    // ✅ Get Admin Role
    public function getRole($admin_id) {
        $stmt = $this->pdo->prepare("SELECT role FROM admin WHERE admin_id = :admin_id");
        $stmt->execute([':admin_id' => $admin_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['role'] : null;
    }
}