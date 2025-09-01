<?php
require_once 'config.php';

class CartController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // ✅ เพิ่มสินค้าในตะกร้า
    public function addToCart($memberId, $shoeId, $quantity = 1)
    {
        try {
            // 1. ตรวจสอบว่าสินค้ามีอยู่จริงและมี stock เพียงพอ
            $shoeCheck = $this->checkShoeAvailability($shoeId, $quantity);
            if (!$shoeCheck['available']) {
                return ['success' => false, 'message' => $shoeCheck['message']];
            }

            // 2. สร้าง cart_id ใหม่
            $cartId = $this->generateCartId();

            // 3. ตรวจสอบว่าสินค้านี้มีในตะกร้าของสมาชิกแล้วหรือไม่
            $existingItem = $this->getCartItem($memberId, $shoeId);

            if ($existingItem) {
                // อัปเดตจำนวนและราคา
                $newQuantity = $existingItem['quantity'] + $quantity;

                // ตรวจสอบ stock อีกครั้งหลังจากเพิ่มจำนวน
                $shoeCheck = $this->checkShoeAvailability($shoeId, $newQuantity);
                if (!$shoeCheck['available']) {
                    return ['success' => false, 'message' => $shoeCheck['message']];
                }

                $newTotalPrice = $shoeCheck['price'] * $newQuantity;

                $updateAt = date('Y-m-d H:i:s');
                $sql = "UPDATE cart SET quantity = :quantity, total_price = :total_price, update_at = :update_at 
                        WHERE member_id = :member_id AND shoe_id = :shoe_id";
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([
                    ':quantity' => $newQuantity,
                    ':total_price' => $newTotalPrice,
                    ':update_at' => $updateAt,
                    ':member_id' => $memberId,
                    ':shoe_id' => $shoeId
                ]);
            } else {
                // เพิ่มรายการใหม่
                $totalPrice = $shoeCheck['price'] * $quantity;

                $createAt = date('Y-m-d H:i:s');
                $sql = "INSERT INTO cart (cart_id, member_id, shoe_id, quantity, unit_price, total_price, create_at) 
                        VALUES (:cart_id, :member_id, :shoe_id, :quantity, :unit_price, :total_price, :create_at)";
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([
                    ':cart_id' => $cartId,
                    ':member_id' => $memberId,
                    ':shoe_id' => $shoeId,
                    ':quantity' => $quantity,
                    ':unit_price' => $shoeCheck['price'],
                    ':total_price' => $totalPrice,
                    ':create_at' => $createAt
                ]);
            }

            return ['success' => $result, 'message' => $result ? 'เพิ่มในตะกร้าเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการเพิ่มสินค้า'];
        } catch (PDOException $e) {
            error_log("Error adding to cart: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ'];
        }
    }

    // ✅ ดึงสินค้าในตะกร้าของสมาชิกคนใดคนหนึ่ง
    public function getCartByMember($memberId)
    {
        try {
            $sql = "SELECT c.*,s.name, s.img_path, s.size ,s.stock, st.name as category_name
                    FROM cart c 
                    LEFT JOIN shoe s ON c.shoe_id = s.shoe_id 
                    LEFT JOIN shoetype st ON s.shoetype_id = st.shoetype_id
                    WHERE c.member_id = :member_id
                    ORDER BY c.create_at DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':member_id' => $memberId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cart by member: " . $e->getMessage());
            return [];
        }
    }

    public function getCartTotalQuantityByMember($memberId)
    {
        try {
            // สมมติว่ามี column quantity ในตาราง cart
            $sql = "SELECT SUM(c.quantity) as total_quantity
                FROM cart c 
                WHERE c.member_id = :member_id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':member_id' => $memberId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_quantity'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error counting total quantity in cart by member: " . $e->getMessage());
            return 0;
        }
    }

    // ✅ อัปเดตจำนวนสินค้าในตะกร้า
    public function updateCartQuantity($cartId, $quantity)
    {
        try {
            // ตรวจสอบว่าตะกร้ามีอยู่จริง
            $cartItem = $this->getCartById($cartId);
            if (!$cartItem) {
                return ['success' => false, 'message' => 'ไม่พบรายการในตะกร้า'];
            }

            // ตรวจสอบ stock
            $shoeCheck = $this->checkShoeAvailability($cartItem['shoe_id'], $quantity);
            if (!$shoeCheck['available']) {
                return ['success' => false, 'message' => $shoeCheck['message']];
            }

            $totalPrice = $shoeCheck['price'] * $quantity;

            $updateAt = date('Y-m-d H:i:s');
            $sql = "UPDATE cart SET quantity = :quantity, total_price = :total_price, update_at = :update_at 
                    WHERE cart_id = :cart_id";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([
                ':quantity' => $quantity,
                ':total_price' => $totalPrice,
                ':update_at' => $updateAt,
                ':cart_id' => $cartId
            ]);

            return ['success' => $result, 'message' => $result ? 'อัปเดตจำนวนเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการอัปเดต'];
        } catch (PDOException $e) {
            error_log("Error updating cart quantity: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ'];
        }
    }

    // ✅ ลบสินค้าออกจากตะกร้า
    public function removeFromCart($cartId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cart WHERE cart_id = :cart_id");
            $result = $stmt->execute([':cart_id' => $cartId]);

            return ['success' => $result, 'message' => $result ? 'ลบสินค้าออกจากตะกร้าเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการลบ'];
        } catch (PDOException $e) {
            error_log("Error removing from cart: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ'];
        }
    }

    // ✅ ล้างตะกร้าของสมาชิก
    public function clearCart($memberId)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM cart WHERE member_id = :member_id");
            $result = $stmt->execute([':member_id' => $memberId]);

            return ['success' => $result, 'message' => $result ? 'ล้างตะกร้าเรียบร้อยแล้ว' : 'เกิดข้อผิดพลาดในการล้างตะกร้า'];
        } catch (PDOException $e) {
            error_log("Error clearing cart: " . $e->getMessage());
            return ['success' => false, 'message' => 'เกิดข้อผิดพลาดของระบบ'];
        }
    }

    // ✅ คำนวณราคารวมของตะกร้า
    public function getCartTotal($memberId)
    {
        try {
            $sql = "SELECT COUNT(*) as total_items, SUM(total_price) as total_amount 
                    FROM cart WHERE member_id = :member_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':member_id' => $memberId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cart total: " . $e->getMessage());
            return ['total_items' => 0, 'total_amount' => 0];
        }
    }

    // ===== Helper Methods =====

    private function generateCartId()
    {
        try {
            $sql = "SELECT cart_id FROM cart ORDER BY cart_id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $lastIdRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($lastIdRow) {
                $lastNumber = (int)substr($lastIdRow['cart_id'], 2);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            return 'CT' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        } catch (PDOException $e) {
            error_log("Error generating cart ID: " . $e->getMessage());
            return 'CT001';
        }
    }

    private function checkShoeAvailability($shoeId, $quantity)
    {
        try {
            $sql = "SELECT price, stock FROM shoe WHERE shoe_id = :shoe_id AND delete_at IS NULL";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':shoe_id' => $shoeId]);
            $shoe = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$shoe) {
                return ['available' => false, 'message' => 'ไม่พบสินค้าดังกล่าว'];
            }

            if ($shoe['stock'] < $quantity) {
                return ['available' => false, 'message' => 'สินค้าในสต็อกไม่เพียงพอ (เหลือ ' . $shoe['stock'] . ' ชิ้น)'];
            }

            return ['available' => true, 'price' => $shoe['price']];
        } catch (PDOException $e) {
            error_log("Error checking shoe availability: " . $e->getMessage());
            return ['available' => false, 'message' => 'เกิดข้อผิดพลาดในการตรวจสอบสินค้า'];
        }
    }

    private function getCartItem($memberId, $shoeId)
    {
        try {
            $sql = "SELECT * FROM cart WHERE member_id = :member_id AND shoe_id = :shoe_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':member_id' => $memberId, ':shoe_id' => $shoeId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cart item: " . $e->getMessage());
            return false;
        }
    }

    private function getCartById($cartId)
    {
        try {
            $sql = "SELECT * FROM cart WHERE cart_id = :cart_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':cart_id' => $cartId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting cart by ID: " . $e->getMessage());
            return false;
        }
    }
}
