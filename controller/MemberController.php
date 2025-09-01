<?php
require_once 'config.php'; // ไฟล์นี้ควรมีการเชื่อมต่อ PDO

class MemberController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ Create Member
    public function create($email, $firstname, $lastname, $phone, $password)
    {
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
        $createAt = date('Y-m-d H:i:s');
        $sqlInsert = "INSERT INTO member (member_id, email,first_name,last_name,phone, password, create_at) 
                      VALUES (:member_id, :email,:firstname ,:lastname,:phone,:password, :create_at)";
        $stmtInsert = $this->pdo->prepare($sqlInsert);
        return $stmtInsert->execute([
            ':member_id' => $newMemberId,
            ':email' => $email,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':phone' => $phone,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':create_at' => $createAt
        ]);
    }

    // ✅ Read All Members
    public function getAll()
    {
        $sql = "SELECT m.*, 
                       COALESCE(COUNT(o.order_id), 0) as order_count
                FROM member m 
                LEFT JOIN orders o ON m.member_id = o.member_id 
                GROUP BY m.member_id, m.email, m.first_name, m.last_name, m.phone, m.password, m.create_at
                ORDER BY m.member_id";
        
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Read One Member by ID
    public function getById($member_id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM member WHERE member_id = :member_id");
        $stmt->execute([':member_id' => $member_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ ตรวจสอบ email ซ้ำ (ไม่รวมตัวเอง)
    public function isEmailExists($email, $excludeMemberId = null)
    {
        if ($excludeMemberId) {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM member WHERE email = :email AND member_id != :member_id");
            $stmt->execute([':email' => $email, ':member_id' => $excludeMemberId]);
        } else {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM member WHERE email = :email");
            $stmt->execute([':email' => $email]);
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function changePassword($memberId, $currentPassword, $newPassword)
    {
        try {
            // Debug: Log ข้อมูลที่ได้รับ
            error_log("changePassword called with member ID: " . $memberId);
            error_log("Current password length: " . strlen($currentPassword));
            error_log("New password length: " . strlen($newPassword));

            // 1. ตรวจสอบรหัสผ่านปัจจุบัน
            $stmt = $this->pdo->prepare("SELECT password FROM member WHERE member_id = :member_id");
            $stmt->execute([':member_id' => $memberId]);
            $member = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$member) {
                error_log("Member not found: " . $memberId);
                return [
                    'success' => false,
                    'error' => 'MEMBER_NOT_FOUND',
                    'message' => 'ไม่พบข้อมูลสมาชิก'
                ];
            }

            // 2. ตรวจสอบรหัสผ่านเดิม
            if (!password_verify($currentPassword, $member['password'])) {
                error_log("Wrong current password for member: " . $memberId);
                return [
                    'success' => false,
                    'error' => 'WRONG_PASSWORD',
                    'message' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'
                ];
            }

            // 3. ตรวจสอบรหัสผ่านใหม่ไม่เหมือนเดิม
            if (password_verify($newPassword, $member['password'])) {
                error_log("New password same as old password for member: " . $memberId);
                return [
                    'success' => false,
                    'error' => 'SAME_PASSWORD',
                    'message' => 'รหัสผ่านใหม่ต้องไม่เหมือนกับรหัสผ่านเดิม'
                ];
            }

            // 4. อัพเดทรหัสผ่านใหม่
            $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateAt = date('Y-m-d H:i:s');
            $updateStmt = $this->pdo->prepare("UPDATE member SET password = :password, update_at = :update_at WHERE member_id = :member_id");
            $result = $updateStmt->execute([
                ':password' => $hashedNewPassword,
                ':update_at' => $updateAt,
                ':member_id' => $memberId
            ]);

            if ($result) {
                error_log("Password changed successfully for member: " . $memberId);
                return [
                    'success' => true,
                    'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'
                ];
            } else {
                error_log("Failed to update password for member: " . $memberId);
                return [
                    'success' => false,
                    'error' => 'UPDATE_FAILED',
                    'message' => 'ไม่สามารถอัพเดทรหัสผ่านได้'
                ];
            }
        } catch (Exception $e) {
            error_log("Database error in changePassword: " . $e->getMessage());
            return [
                'success' => false,
                'error' => 'DATABASE_ERROR',
                'message' => 'เกิดข้อผิดพลาดในฐานข้อมูล: ' . $e->getMessage()
            ];
        }
    }

    // ✅ Update Member (ปรับปรุงให้ตรวจสอบ email ซ้ำ)
    public function update($member_id, $email, $firstname, $lastname, $phone, $password = null)
    {
        // ตรวจสอบ email ซ้ำ (ไม่รวมตัวเอง)
        if ($this->isEmailExists($email, $member_id)) {
            return [
                'success' => false,
                'error' => 'EMAIL_EXISTS',
                'message' => 'อีเมลนี้ถูกใช้แล้ว กรุณาใช้อีเมลอื่น'
            ];
        }

        $updateAt = date('Y-m-d H:i:s');
        $fields = "email = :email, first_name = :firstname, last_name = :lastname, phone = :phone, update_at = :update_at";
        $params = [
            ':member_id' => $member_id,
            ':email' => $email,
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':phone' => $phone,
            ':update_at' => $updateAt
        ];

        if ($password !== null) {
            $fields .= ", password = :password";
            $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql = "UPDATE member SET $fields WHERE member_id = :member_id";
        $stmt = $this->pdo->prepare($sql);
        $result = $stmt->execute($params);

        return [
            'success' => $result,
            'message' => $result ? 'อัพเดทข้อมูลสำเร็จ' : 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล'
        ];
    }

    // ✅ Delete Member
    public function delete($member_id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM member WHERE member_id = :member_id");
        return $stmt->execute([':member_id' => $member_id]);
    }

    // ✅ Login (Check Credentials + Update last_login)
    public function login($email, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM member WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($member && password_verify($password, $member['password'])) {
            // Update last_login
            $lastLogin = date('Y-m-d H:i:s');
            $update = $this->pdo->prepare("UPDATE member SET last_login = :last_login WHERE member_id = :id");
            $update->execute([':last_login' => $lastLogin, ':id' => $member['member_id']]);
            return $member;
        }

        return false;
    }

    // ✅ Create Address
    public function createAddress($memberId, $recipientName, $recipientPhone, $addressName, $addressLine, $subDistrict, $district, $province, $postalCode, $isDefault = 0)
    {
        // 1. ค้นหา address_id ล่าสุด
        $sqlLastId = "SELECT address_id FROM address ORDER BY address_id DESC LIMIT 1";
        $stmtLast = $this->pdo->prepare($sqlLastId);
        $stmtLast->execute();
        $lastIdRow = $stmtLast->fetch(PDO::FETCH_ASSOC);

        if ($lastIdRow) {
            // ดึงตัวเลขจาก address_id เช่น AD009 => 9
            $lastNumber = (int)substr($lastIdRow['address_id'], 2);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        // สร้าง address_id ใหม่ เช่น AD001
        $newAddressId = 'AD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 2. ถ้าเป็น default address ให้อัพเดทที่อยู่เดิมของ member นี้ให้ไม่เป็น default
        if ($isDefault == 1) {
            $sqlUpdateDefault = "UPDATE address SET is_default = 0 WHERE member_id = :member_id";
            $stmtUpdateDefault = $this->pdo->prepare($sqlUpdateDefault);
            $stmtUpdateDefault->execute([':member_id' => $memberId]);
        }

        // 3. บันทึกข้อมูลที่อยู่ใหม่
        $createAt = date('Y-m-d H:i:s');
        $updateAt = date('Y-m-d H:i:s');
        $sqlInsert = "INSERT INTO address (
                    address_id, 
                    member_id, 
                    recipient_name, 
                    recipient_phone, 
                    address_name, 
                    address_line, 
                    sub_district, 
                    district, 
                    province, 
                    postal_code, 
                    is_default, 
                    create_at, 
                    update_at
                  ) VALUES (
                    :address_id, 
                    :member_id, 
                    :recipient_name, 
                    :recipient_phone, 
                    :address_name, 
                    :address_line, 
                    :sub_district, 
                    :district, 
                    :province, 
                    :postal_code, 
                    :is_default, 
                    :create_at, 
                    :update_at
                  )";

        $stmtInsert = $this->pdo->prepare($sqlInsert);
        return $stmtInsert->execute([
            ':address_id' => $newAddressId,
            ':member_id' => $memberId,
            ':recipient_name' => $recipientName,
            ':recipient_phone' => $recipientPhone,
            ':address_name' => $addressName,
            ':address_line' => $addressLine,
            ':sub_district' => $subDistrict,
            ':district' => $district,
            ':province' => $province,
            ':postal_code' => $postalCode,
            ':is_default' => $isDefault,
            ':create_at' => $createAt,
            ':update_at' => $updateAt
        ]);
    }

    // ✅ Update Address
    public function updateAddress($addressId, $recipientName, $recipientPhone, $addressName, $addressLine, $subDistrict, $district, $province, $postalCode, $isDefault = null)
    {
        // 1. ตรวจสอบว่า address มีอยู่จริงหรือไม่
        $sqlCheck = "SELECT member_id FROM address WHERE address_id = :address_id";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([':address_id' => $addressId]);
        $addressData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$addressData) {
            return false; // ไม่พบที่อยู่
        }

        // 2. ถ้าต้องการตั้งเป็น default address
        if ($isDefault == 1) {
            $sqlUpdateDefault = "UPDATE address SET is_default = 0 WHERE member_id = :member_id AND address_id != :address_id";
            $stmtUpdateDefault = $this->pdo->prepare($sqlUpdateDefault);
            $stmtUpdateDefault->execute([
                ':member_id' => $addressData['member_id'],
                ':address_id' => $addressId
            ]);
        }

        // 3. อัพเดทข้อมูลที่อยู่
        $updateAt = date('Y-m-d H:i:s');
        if ($isDefault !== null) {
            $sqlUpdate = "UPDATE address SET 
                    recipient_name = :recipient_name,
                    recipient_phone = :recipient_phone,
                    address_name = :address_name,
                    address_line = :address_line,
                    sub_district = :sub_district,
                    district = :district,
                    province = :province,
                    postal_code = :postal_code,
                    is_default = :is_default,
                    update_at = :update_at
                  WHERE address_id = :address_id";

            $params = [
                ':recipient_name' => $recipientName,
                ':recipient_phone' => $recipientPhone,
                ':address_name' => $addressName,
                ':address_line' => $addressLine,
                ':sub_district' => $subDistrict,
                ':district' => $district,
                ':province' => $province,
                ':postal_code' => $postalCode,
                ':is_default' => $isDefault,
                ':update_at' => $updateAt,
                ':address_id' => $addressId
            ];
        } else {
            $sqlUpdate = "UPDATE address SET 
                    recipient_name = :recipient_name,
                    recipient_phone = :recipient_phone,
                    address_name = :address_name,
                    address_line = :address_line,
                    sub_district = :sub_district,
                    district = :district,
                    province = :province,
                    postal_code = :postal_code,
                    update_at = :update_at
                  WHERE address_id = :address_id";

            $params = [
                ':recipient_name' => $recipientName,
                ':recipient_phone' => $recipientPhone,
                ':address_name' => $addressName,
                ':address_line' => $addressLine,
                ':sub_district' => $subDistrict,
                ':district' => $district,
                ':province' => $province,
                ':postal_code' => $postalCode,
                ':update_at' => $updateAt,
                ':address_id' => $addressId
            ];
        }

        $stmtUpdate = $this->pdo->prepare($sqlUpdate);
        return $stmtUpdate->execute($params);
    }

    // ✅ Delete Address
    public function deleteAddress($addressId)
    {
        // 1. ตรวจสอบว่า address มีอยู่จริงหรือไม่

        $sqlCheck = "SELECT member_id, is_default FROM address WHERE address_id = :address_id";
        $stmtCheck = $this->pdo->prepare($sqlCheck);
        $stmtCheck->execute([':address_id' => $addressId]);
        $addressData = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$addressData) {
            return false; // ไม่พบที่อยู่
        }

        // 2. ลบที่อยู่
        $sqlDelete = "DELETE FROM address WHERE address_id = :address_id";
        $stmtDelete = $this->pdo->prepare($sqlDelete);
        $result = $stmtDelete->execute([':address_id' => $addressId]);

        // 3. ถ้าที่อยู่ที่ลบเป็น default address ให้ตั้งที่อยู่แรกของ member เป็น default
        if ($result && $addressData['is_default'] == 1) {
            $sqlSetNewDefault = "UPDATE address SET is_default = 1 
                            WHERE member_id = :member_id 
                            ORDER BY create_at ASC 
                            LIMIT 1";
            $stmtSetDefault = $this->pdo->prepare($sqlSetNewDefault);
            $stmtSetDefault->execute([':member_id' => $addressData['member_id']]);
        }

        return $result;
    }

    // ✅ Get Address by ID
    public function getAddressById($addressId)
    {
        $sql = "SELECT * FROM address WHERE address_id = :address_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':address_id' => $addressId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ✅ Get All Addresses by Member ID
    public function getAddressesByMember($memberId)
    {
        $sql = "SELECT * FROM address WHERE member_id = :member_id ORDER BY is_default DESC, create_at ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Get Default Address by Member ID
    public function getDefaultAddress($memberId)
    {
        $sql = "SELECT * FROM address WHERE member_id = :member_id AND is_default = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':member_id' => $memberId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
