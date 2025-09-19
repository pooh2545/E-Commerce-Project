<?php
require_once 'config.php';

class auth_check{

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function isLoggedIn() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        return (isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])) ||
               (isset($_COOKIE['member_id']) && !empty($_COOKIE['member_id']));
    }

    public function redirectIfLoggedIn($redirect_to = 'index.php') {
        if ($this->isLoggedIn()) {  // เรียกใช้ method ของ class เดียวกัน
            header("Location: $redirect_to");
            exit();
        }
    }

    public function redirectIfNotLoggedIn($redirect_to = 'login.php') {
        if (!$this->isLoggedIn()) {  // เรียกใช้ method ของ class เดียวกัน
            header("Location: $redirect_to");
            exit();
        }
    }

    public function redirectIfNoItemCart($redirect_to = 'cart.php'){
        // เช็ค session ก่อน
        $sql = "SELECT c.*,s.name, s.img_path, s.size ,s.stock, st.name as category_name
                        FROM cart c 
                        LEFT JOIN shoe s ON c.shoe_id = s.shoe_id 
                        LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                        WHERE c.member_id = :member_id
                        ORDER BY c.create_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':member_id' => $_COOKIE['member_id']]);
        
        // ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
        $cartItems = $stmt->fetchAll();
        if (count($cartItems) == 0) {
            header("Location: $redirect_to");
            exit();
        }
        
        return $cartItems;  // คืนค่าข้อมูลตะกร้าสำหรับใช้งาน
    }

    public function redirectIfNotMemberOrder($orderNumber , $redirect_to = 'profile.php?section=orders'){
        $sql = "SELECT o.*, 
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           pm.url_path as qr_path,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id
                    WHERE o.order_number = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($_COOKIE['member_id'] !== $order['member_id']){
                header("Location: $redirect_to");
                exit();
            }
    }

    public function redirectIfAlreadyPayOrder($orderNumber , $redirect_to = 'profile.php?section=orders'){
        $sql = "SELECT o.*, 
                           pm.bank, pm.account_number, pm.name as bank_account_name,
                           pm.url_path as qr_path,
                           os.name as order_status_name
                    FROM orders o
                    LEFT JOIN payment_method pm ON o.payment_method_id = pm.payment_method_id
                    LEFT JOIN order_status os ON o.order_status = os.order_status_id
                    WHERE o.order_number = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$orderNumber]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($_COOKIE['member_id'] !== $order['member_id'] || $order['order_status'] >= 2){
                header("Location: $redirect_to");
                exit();
            }
    }
}

// ฟังก์ชัน global สำหรับใช้งานง่าย 
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return (isset($_SESSION['member_id']) && !empty($_SESSION['member_id'])) ||
           (isset($_COOKIE['member_id']) && !empty($_COOKIE['member_id']));
}

function redirectIfLoggedIn($redirect_to = 'index.php') {
    if (isLoggedIn()) {  // ตอนนี้เรียกใช้ฟังก์ชัน global
        header("Location: $redirect_to");
        exit();
    }
}

function redirectIfNotLoggedIn($redirect_to = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect_to");
        exit();
    }
}

?>