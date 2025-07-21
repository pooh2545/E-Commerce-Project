<?php
require_once 'config.php'; // ไฟล์นี้ควรมีการเชื่อมต่อ PDO

class MemberController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ✅ Create Member
    public function create($email, $username,$password) {
        // 1. ค้นหา member_id ล่าสุด
        $sqlLastId = "SELECT member_id FROM member ORDER BY member_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        if ($lastIdRow) {
            // ดึงตัวเลขจาก member_id เช่น MB009 => 9
            $lastNumber = (int)substr($lastIdRow['member_id'], 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // สร้าง member_id ใหม่ เช่น MB001
        $newMemberId = 'MB' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 2. บันทึกข้อมูล
        $sqlInsert = "INSERT INTO member (member_id, email,username, password, create_at) 
                      VALUES (:member_id, :email,:username, :password, NOW())";
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        return $stmtInsert->execute([
            ':member_id' => $newMemberId,
            ':email' => $email,
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    // ✅ Read All Members
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM member");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Read One Member by ID
    public function getById($member_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM member WHERE member_id = :member_id");
        $stmt->execute([':member_id' => $member_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Update Member
    public function update($member_id, $username, $password = null) {
        $fields = "username = :username, update_at = NOW()";
        $params = [':member_id' => $member_id, ':username' => $username];

        if ($password !== null) {
            $fields .= ", password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE member SET $fields WHERE member_id = :member_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    // ✅ Delete Member
    public function delete($member_id) {
        $stmt = $this->pdo->prepare("DELETE FROM member WHERE member_id = :member_id");
        return $stmt->execute([':member_id' => $member_id]);
    }

    // ✅ Login (Check Credentials + Update last_login)
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM member WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($member && password_verify($password, $member['password'])) {
            // Update last_login
            $update = $this->pdo->prepare("UPDATE member SET last_login = NOW() WHERE member_id = :id");
            $update->execute([':id' => $member['member_id']]);
            return $member;
        }

        return false;
    }
}
