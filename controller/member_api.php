<?php
require_once 'config.php'; 
require_once 'MemberController.php';

$memberController = new MemberController($pdo);
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if ($_GET['action'] === 'all') {
            echo json_encode($memberController->getAll());
            
        } elseif ($_GET['action'] === 'get' && isset($_GET['id'])) {
            echo json_encode($memberController->getById($_GET['id']));
            
        } elseif ($_GET['action'] === 'check-email' && isset($_GET['email'])) {
            // ตรวจสอบ email ซ้ำ
            $excludeId = isset($_GET['exclude_id']) ? $_GET['exclude_id'] : null;
            $exists = $memberController->isEmailExists($_GET['email'], $excludeId);
            echo json_encode(['exists' => $exists]);
            
        } elseif ($_GET['action'] === 'addresses' && isset($_GET['member_id'])) {
            // ดึงที่อยู่ทั้งหมดของสมาชิก
            $addresses = $memberController->getAddressesByMember($_GET['member_id']);
            echo json_encode([
                'success' => true,
                'data' => $addresses
            ]);
            
        } elseif ($_GET['action'] === 'address' && isset($_GET['address_id'])) {
            // ดึงที่อยู่ตาม ID
            $address = $memberController->getAddressById($_GET['address_id']);
            if ($address) {
                echo json_encode([
                    'success' => true,
                    'data' => $address
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'NOT_FOUND',
                    'message' => 'ไม่พบที่อยู่ที่ระบุ'
                ]);
            }
            
        } elseif ($_GET['action'] === 'default-address' && isset($_GET['member_id'])) {
            // ดึงที่อยู่ default ของสมาชิก
            $address = $memberController->getDefaultAddress($_GET['member_id']);
            echo json_encode([
                'success' => true,
                'data' => $address
            ]);
        }
        break;
    
    case 'POST':
        if ($_GET['action'] === 'create') {
            // สร้างสมาชิกใหม่
            $data = json_decode(file_get_contents('php://input'), true);
            
            // ตรวจสอบข้อมูลที่จำเป็น
            if (empty($data['email']) || empty($data['firstname']) || 
                empty($data['lastname']) || empty($data['phone']) || 
                empty($data['password'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'MISSING_DATA',
                    'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
                ]);
                break;
            }

            // ตรวจสอบ email ซ้ำ
            if ($memberController->isEmailExists($data['email'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'EMAIL_EXISTS',
                    'message' => 'อีเมลนี้มีการใช้งานแล้ว'
                ]);
                break;
            }

            $result = $memberController->create(
                $data['email'],
                $data['firstname'],
                $data['lastname'],
                $data['phone'],
                $data['password']
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'สร้างสมาชิกสำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'CREATE_FAILED',
                    'message' => 'ไม่สามารถสร้างสมาชิกได้'
                ]);
            }
            
        } elseif ($_GET['action'] === 'create-address') {
            // สร้างที่อยู่ใหม่
            $data = json_decode(file_get_contents('php://input'), true);
            
            // ตรวจสอบข้อมูลที่จำเป็น
            $required = ['member_id', 'recipient_name', 'recipient_phone', 
                        'address_name', 'address_line', 'sub_district', 
                        'district', 'province', 'postal_code'];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'MISSING_DATA',
                        'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
                    ]);
                    break 2;
                }
            }

            $result = $memberController->createAddress(
                $data['member_id'],
                $data['recipient_name'],
                $data['recipient_phone'],
                $data['address_name'],
                $data['address_line'],
                $data['sub_district'],
                $data['district'],
                $data['province'],
                $data['postal_code'],
                $data['is_default'] ?? 0
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'เพิ่มที่อยู่สำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'CREATE_FAILED',
                    'message' => 'ไม่สามารถเพิ่มที่อยู่ได้'
                ]);
            }
            
        } elseif ($_GET['action'] === 'login') {
            // เข้าสู่ระบบ
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['email']) || empty($data['password'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'MISSING_DATA',
                    'message' => 'กรุณากรอก email และ password'
                ]);
                break;
            }

            $member = $memberController->login($data['email'], $data['password']);
            
            if ($member) {
                // เริ่ม session
                session_start();
                $_SESSION['member_id'] = $member['member_id'];
                $_SESSION['email'] = $member['email'];
                
                echo json_encode([
                    'success' => true,
                    'message' => 'เข้าสู่ระบบสำเร็จ',
                    'data' => [
                        'member_id' => $member['member_id'],
                        'email' => $member['email'],
                        'first_name' => $member['first_name'],
                        'last_name' => $member['last_name']
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'LOGIN_FAILED',
                    'message' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
                ]);
            }
        }
        break;
    
    case 'PUT':
        if ($_GET['action'] === 'update' && isset($_GET['id'])) {
        $data = json_decode(file_get_contents('php://input'), true);
        
        // ตรวจสอบข้อมูลที่จำเป็น
        if (empty($data['email']) || empty($data['firstname']) || empty($data['lastname'])) {
            echo json_encode([
                'success' => false, 
                'error' => 'MISSING_DATA',
                'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
            ]);
            break;
        }

        // ตรวจสอบอีเมลซ้ำ (ยกเว้นของตัวเอง)
        if ($memberController->isEmailExists($data['email'], $_GET['id'])) {
            echo json_encode([
                'success' => false,
                'error' => 'EMAIL_EXISTS', 
                'message' => 'อีเมลนี้ถูกใช้แล้ว'
            ]);
            break;
        }

        $result = $memberController->update(
            $_GET['id'], 
            $data['email'], 
            $data['firstname'], 
            $data['lastname'], 
            $data['tel'] ?? null
        );
        
        // ตรวจสอบว่าการอัพเดทสำเร็จหรือไม่
        if ($result && isset($result['success']) && $result['success'] === true) {
            // อัพเดท Cookie ด้วยอีเมลใหม่ (ถ้าเปลี่ยน)
            if (isset($_COOKIE['email']) && $_COOKIE['email'] !== $data['email']) {
                setcookie('email', $data['email'], time() + (86400 * 30), '/', '', false, true);
            }
            
            // เก็บข้อมูลใหม่ใน session สำหรับใช้ในหน้า profile.php
            session_start();
            $_SESSION['profile_updated'] = true;
            $_SESSION['updated_user_data'] = [
                'email' => $data['email'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'tel' => $data['tel'] ?? ''
            ];
            
            // ส่งข้อมูลที่อัพเดทกลับไปด้วย
            echo json_encode([
                'success' => true, 
                'message' => 'อัพเดทข้อมูลเรียบร้อยแล้ว',
                'updated_data' => [
                    'email' => $data['email'],
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'tel' => $data['tel'] ?? '',
                    'avatar_initials' => substr($data['firstname'], 0, 1) . substr($data['lastname'], 0, 1)
                ]
            ]);
        } else {
            // ถ้า $result เป็น array ที่มี error message
            if (is_array($result) && isset($result['success']) && $result['success'] === false) {
                echo json_encode($result);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'UPDATE_FAILED',
                    'message' => 'ไม่สามารถอัพเดทข้อมูลได้'
                ]);
            }
        }
            
        } elseif ($_GET['action'] === 'update-address' && isset($_GET['address_id'])) {
            // อัพเดทที่อยู่
            $data = json_decode(file_get_contents('php://input'), true);
            
            // ตรวจสอบข้อมูลที่จำเป็น
            $required = ['recipient_name', 'recipient_phone', 'address_name', 
                        'address_line', 'sub_district', 'district', 'province', 'postal_code'];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'MISSING_DATA',
                        'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน'
                    ]);
                    break 2;
                }
            }

            $result = $memberController->updateAddress(
                $_GET['address_id'],
                $data['recipient_name'],
                $data['recipient_phone'],
                $data['address_name'],
                $data['address_line'],
                $data['sub_district'],
                $data['district'],
                $data['province'],
                $data['postal_code'],
                $data['is_default'] ?? null
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'อัพเดทที่อยู่สำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'UPDATE_FAILED',
                    'message' => 'ไม่สามารถอัพเดทที่อยู่ได้'
                ]);
            }
            
        } elseif ($_GET['action'] === 'change-password' && isset($_GET['id'])) {
            // เปลี่ยนรหัสผ่าน
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['current_password']) || empty($data['new_password'])) {
                echo json_encode([
                    'success' => false,
                    'error' => 'MISSING_DATA',
                    'message' => 'กรุณากรอกรหัสผ่านเดิมและรหัสผ่านใหม่'
                ]);
                break;
            }
            /*
            $result = $memberController->changePassword(
                $_GET['id'],
                $data['current_password'],
                $data['new_password']
            );

            echo json_encode($result);*/
        }
        break;
    
    case 'DELETE':
        if ($_GET['action'] === 'delete' && isset($_GET['id'])) {
            // ลบสมาชิก
            $result = $memberController->delete($_GET['id']);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ลบสำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'DELETE_FAILED',
                    'message' => 'ไม่สามารถลบได้'
                ]);
            }
            
        } elseif ($_GET['action'] === 'delete-address' && isset($_GET['address_id'])) {
            // ลบที่อยู่
            $result = $memberController->deleteAddress($_GET['address_id']);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'ลบที่อยู่สำเร็จ'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'DELETE_FAILED',
                    'message' => 'ไม่สามารถลบที่อยู่ได้'
                ]);
            }
            
        } elseif ($_GET['action'] === 'logout') {
            // ออกจากระบบ
            session_start();
            session_destroy();
            
            echo json_encode([
                'success' => true,
                'message' => 'ออกจากระบบสำเร็จ'
            ]);
        }
        break;
    
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false, 
            'error' => 'METHOD_NOT_ALLOWED',
            'message' => 'HTTP method ไม่ถูกต้อง'
        ]);
        break;
}
?>