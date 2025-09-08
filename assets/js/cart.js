// cart.js - ฟังก์ชันสำหรับจัดการตะกร้าสินค้า (Updated Version - Without showNotification)

// ฟังก์ชันดึง member_id จาก cookie (ให้สอดคล้องกับ MainHeader.php)
function getMemberId() {
    try {
        const rawCookie = document.cookie;
        const memberIdStart = rawCookie.indexOf('member_id=');
        
        if (memberIdStart !== -1) {
            const valueStart = memberIdStart + 'member_id='.length;
            let valueEnd = rawCookie.indexOf(';', valueStart);
            if (valueEnd === -1) {
                valueEnd = rawCookie.length;
            }
            
            const memberId = rawCookie.substring(valueStart, valueEnd).trim();
            return memberId || null;
        }
        
        return null;
    } catch (error) {
        console.error('Error getting member_id from cookie:', error);
        return null;
    }
}

// ตรวจสอบสถานะการเข้าสู่ระบบ
function checkLoginStatus() {
    return getMemberId();
}

// เพิ่มสินค้าลงตะกร้า (Main function)
async function addToCart(shoeId, quantity = 1) {
    try {
        const memberId = getMemberId();
        
        console.log('Adding to cart:', { shoeId, quantity, memberId }); // Debug log
        
        if (!memberId) {
            showWarning('กรุณาเข้าสู่ระบบก่อนเพิ่มสินค้าลงตะกร้า');
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 1500);
            return false;
        }

        const formData = new FormData();
        formData.append('member_id', memberId);
        formData.append('shoe_id', shoeId);
        formData.append('quantity', quantity);

        const response = await fetch('controller/cart_api.php?action=add', {
            method: 'POST',
            body: formData
        });

        console.log('Response status:', response.status); // Debug log

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        console.log('API Response:', result); // Debug log

        if (result.success) {
            showSuccess(result.message || 'เพิ่มสินค้าลงตะกร้าเรียบร้อยแล้ว!');
            
            // อัปเดต cart count ใน header
            if (typeof loadCartItems === 'function') {
                loadCartItems(); // เรียกฟังก์ชันจาก MainHeader.php
            } else {
                updateCartCount();
            }
            
            return true;
        } else {
            showError(result.message || 'เกิดข้อผิดพลาดในการเพิ่มสินค้า');
            return false;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        return false;
    }
}

// อัปเดตจำนวนสินค้าในตะกร้า
async function updateCartCount() {
    try {
        const memberId = getMemberId();
        if (!memberId) return;

        const response = await fetch(`controller/cart_api.php?action=get&member_id=${memberId}`);
        
        if (!response.ok) {
            console.error('Failed to fetch cart data:', response.status);
            return;
        }

        const result = await response.json();

        if (result.success && Array.isArray(result.data)) {
            const totalItems = result.data.reduce((total, item) => total + parseInt(item.quantity || 0), 0);
            
            // อัปเดต cart counter ใน header
            const cartCounter = document.querySelector('.cart-count');
            const cartCountElement = document.getElementById('cartCount');
            
            if (cartCounter) {
                cartCounter.textContent = totalItems;
                cartCounter.style.display = totalItems > 0 ? 'inline' : 'none';
            }
            
            if (cartCountElement) {
                cartCountElement.textContent = totalItems;
            }
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

// ดึงข้อมูลตะกร้าสินค้า
async function getCartItems() {
    try {
        const memberId = getMemberId();
        if (!memberId) return [];

        const response = await fetch(`controller/cart_api.php?action=get&member_id=${memberId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success && Array.isArray(result.data)) {
            return result.data;
        }
        return [];
    } catch (error) {
        console.error('Error getting cart items:', error);
        return [];
    }
}

// คำนวณยอดรวมของตะกร้า
async function getCartTotal() {
    try {
        const memberId = getMemberId();
        if (!memberId) return 0;

        const response = await fetch(`controller/cart_api.php?action=total&member_id=${memberId}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            return parseFloat(result.data) || 0;
        }
        return 0;
    } catch (error) {
        console.error('Error getting cart total:', error);
        return 0;
    }
}

// ลบสินค้าออกจากตะกร้า
async function removeFromCart(cartId) {
    try {
        const response = await fetch(`controller/cart_api.php?action=remove&cart_id=${cartId}`, {
            method: 'DELETE'
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            showSuccess(result.message || 'ลบสินค้าออกจากตะกร้าแล้ว');
            
            // อัปเดต cart
            if (typeof loadCartItems === 'function') {
                loadCartItems();
            } else {
                updateCartCount();
            }
            
            return true;
        } else {
            showError(result.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
            return false;
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        return false;
    }
}

// อัปเดตจำนวนสินค้าในตะกร้า
async function updateCartQuantity(cartId, quantity) {
    try {
        // ใช้ URLSearchParams สำหรับ PUT request
        const params = new URLSearchParams();
        params.append('quantity', quantity);

        const response = await fetch(`controller/cart_api.php?action=update&cart_id=${cartId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: params.toString()
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            showSuccess(result.message || 'อัปเดตจำนวนสินค้าแล้ว');
            
            // อัปเดต cart
            if (typeof loadCartItems === 'function') {
                loadCartItems();
            } else {
                updateCartCount();
            }
            
            return true;
        } else {
            showError(result.message || 'เกิดข้อผิดพลาดในการอัปเดต');
            return false;
        }
    } catch (error) {
        console.error('Error updating cart quantity:', error);
        showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        return false;
    }
}

// ล้างตะกร้าทั้งหมด
async function clearCart() {
    try {
        const memberId = getMemberId();
        if (!memberId) {
            showWarning('กรุณาเข้าสู่ระบบก่อน');
            return false;
        }

        // ใช้ showConfirm แทน confirm ธรรมดา
        return new Promise((resolve) => {
            showConfirm(
                'คุณต้องการล้างสินค้าในตะกร้าทั้งหมดหรือไม่?',
                async () => {
                    try {
                        const response = await fetch(`controller/cart_api.php?action=clear&member_id=${memberId}`, {
                            method: 'DELETE'
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const result = await response.json();

                        if (result.success) {
                            showSuccess(result.message || 'ล้างตะกร้าสินค้าแล้ว');
                            
                            // อัปเดต cart
                            if (typeof loadCartItems === 'function') {
                                loadCartItems();
                            } else {
                                updateCartCount();
                            }
                            
                            resolve(true);
                        } else {
                            showError(result.message || 'เกิดข้อผิดพลาดในการล้างตะกร้า');
                            resolve(false);
                        }
                    } catch (error) {
                        console.error('Error clearing cart:', error);
                        showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
                        resolve(false);
                    }
                },
                () => {
                    resolve(false);
                }
            );
        });
    } catch (error) {
        console.error('Error in clearCart:', error);
        showError('เกิดข้อผิดพลาดในการเชื่อมต่อ: ' + error.message);
        return false;
    }
}

// Debug function สำหรับตรวจสอบสถานะ
function debugCart() {
    console.log('=== Cart Debug Info ===');
    console.log('Member ID:', getMemberId());
    console.log('Raw Cookie:', document.cookie);
    console.log('Cart count element:', document.getElementById('cartCount'));
    console.log('========================');
}

// เพิ่ม debug function ให้ global scope
window.debugCart = debugCart;

// โหลดจำนวนสินค้าในตะกร้าเมื่อเริ่มต้น
document.addEventListener('DOMContentLoaded', function() {
    console.log('Cart.js loaded, initializing...'); // Debug log
    updateCartCount();
});

// Export functions for global access
window.addToCart = addToCart;
window.removeFromCart = removeFromCart;
window.updateCartQuantity = updateCartQuantity;
window.clearCart = clearCart;
window.getMemberId = getMemberId;