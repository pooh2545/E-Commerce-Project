<!-- Header -->
<header class="header">
    <div class="header-top">
        <a href="index.php" class="logo">Shoe Store</a>
        <div class="search-bar">
            <!-- 
                <input type="text" placeholder="Search for shoes...">
                <button type="submit">🔍</button>
                -->
        </div>
        <div class="user-actions">
            <div class="cart-dropdown">
                <a href="cart.php" class="cart-link">
                    <img src="assets/icon/icons-cart.png" width="24px" alt="Cart" class="icon">
                    <span>Cart</span>
                    <span class="cart-count-header" id="cartCount" style="text-align: center; "></span>
                </a>
                <div class="cart-menu" id="cartMenu">
                    <div class="cart-menu-header">
                        <h4>ตะกร้าสินค้า</h4>
                    </div>
                    <div class="cart-menu-items" id="cartItems">
                        <!-- Cart items will be loaded here -->
                        <div class="cart-loading">
                            <div class="loading-spinner"></div>
                            <p>กำลังโหลด...</p>
                        </div>
                    </div>
                    <div class="cart-menu-footer" id="cartFooter" style="display: none;">
                        <div class="cart-total">
                            <span>รวมทั้งหมด: <strong id="cartTotal">฿0</strong></span>
                        </div>
                        <div class="cart-actions-header">
                            <a href="cart.php" class="view-cart-btn-header">ดูตะกร้า</a>
                            <a href="checkout.php?from=cart" class="checkout-btn-header">สั่งซื้อ</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (isset($_COOKIE['member_id'])) {
            ?>
                <div class="profile-dropdown">
                    <a href="profile.php">
                        <div class="profile-toggle">
                            <img src="assets/icon/icons-person.png" width="24px" alt="User" class="icon">
                            <span>
                                <?php
                                echo isset($_COOKIE['first_name']) && isset($_COOKIE['last_name'])
                                    ? $_COOKIE['first_name'] . ' ' . $_COOKIE['last_name']
                                    : 'Guest User';
                                ?>
                            </span>
                        </div>
                    </a>
                    <div class="profile-menu" id="profileMenu">
                        <div class="profile-menu-header">
                            <div class="profile-avatar-small">
                                <?php
                                // แสดง initials ของชื่อ
                                $initials = 'GU'; // Default
                                if (isset($_COOKIE['first_name']) && isset($_COOKIE['last_name'])) {
                                    $initials = substr($_COOKIE['first_name'], 0, 1) . substr($_COOKIE['last_name'], 0, 1);
                                }
                                echo $initials;
                                ?>
                            </div>
                            <div class="profile-info">
                                <div class="profile-name-small">
                                    <?php
                                    echo isset($_COOKIE['first_name']) && isset($_COOKIE['last_name'])
                                        ? $_COOKIE['first_name'] . ' ' . $_COOKIE['last_name']
                                        : 'Guest User';
                                    ?>
                                </div>
                                <div class="profile-email-small">
                                    <?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : 'guest@example.com'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="profile-menu-divider"></div>
                        <div class="profile-menu-items">
                            <a href="profile.php?section=profile" class="profile-menu-item">
                                <span class="menu-icon">👤</span>
                                <span>ข้อมูลส่วนตัว</span>
                            </a>
                            <a href="profile.php?section=orders" class="profile-menu-item">
                                <span class="menu-icon">📦</span>
                                <span>ประวัติการสั่งซื้อ</span>
                            </a>
                            <div class="profile-menu-divider"></div>
                            <a href="#" class="profile-menu-item logout-item" onclick="showLogoutConfirm(event)">
                                <span class="menu-icon">🚪</span>
                                <span>ออกจากระบบ</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <a href="signup.php" class="auth-link">Sign Up</a>
                <a href="login.php" class="auth-link">Sign In</a>
            <?php } ?>
        </div>
    </div>
    <nav class="nav-menu">
        <ul id="dynamicNavMenu">
            <!-- Categories will be loaded dynamically -->
            <li><a href="products.php">ทั้งหมด</a></li>
        </ul>
    </nav>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadDynamicNavigation();
        loadCartItems();
    });

    // Load categories for navigation menu
    async function loadDynamicNavigation() {
        try {
            const response = await fetch('controller/shoetype_api.php?action=all');
            if (!response.ok) throw new Error('Failed to fetch categories');

            const categories = await response.json();
            if (Array.isArray(categories)) {
                renderNavigationMenu(categories);
            }
        } catch (error) {
            console.error('Error loading navigation categories:', error);
            // Keep default menu if API fails
        }
    }

    // Render navigation menu with dynamic categories
    function renderNavigationMenu(categories) {
        const navMenu = document.getElementById('dynamicNavMenu');

        let menuHTML = '<li><a href="products.php">ทั้งหมด</a></li>';

        categories.forEach(category => {
            // Use shoetype_id as the category parameter
            menuHTML += `<li><a href="products.php?category=${category.shoetype_id}">${category.name}</a></li>`;
        });

        navMenu.innerHTML = menuHTML;
    }

    // ===== CART FUNCTIONS WITH API INTEGRATION =====

    // ดึงข้อมูล member_id จาก cookie หรือ session
    function getMemberId() {
        try {

            const rawCookie = document.cookie;

            // วิธีที่ 1: ใช้ indexOf และ substring
            const memberIdStart = rawCookie.indexOf('member_id=');
            if (memberIdStart !== -1) {
                const valueStart = memberIdStart + 'member_id='.length;
                let valueEnd = rawCookie.indexOf(';', valueStart);
                if (valueEnd === -1) {
                    valueEnd = rawCookie.length;
                }

                const memberId = rawCookie.substring(valueStart, valueEnd).trim();
                console.log('Method 1 - Found member_id:', memberId);
                return memberId;
            }

            return null;

        } catch (error) {
            return null;
        }
    }

    function debugCookieParsing() {
        console.log('=== Cookie Debug Detail ===');

        const rawCookie = document.cookie;
        console.log('Raw cookie string:', rawCookie);
        console.log('Raw cookie length:', rawCookie.length);

        // แยก cookies ด้วย semicolon
        const cookieParts = rawCookie.split(';');
        console.log('Cookie parts count:', cookieParts.length);

        cookieParts.forEach((part, index) => {
            console.log(`Part ${index}:`, `"${part}"`);
            console.log(`Part ${index} trimmed:`, `"${part.trim()}"`);

            const equalIndex = part.indexOf('=');
            if (equalIndex !== -1) {
                const name = part.substring(0, equalIndex).trim();
                const value = part.substring(equalIndex + 1).trim();
                console.log(`  -> Name: "${name}", Value: "${value}"`);

                if (name === 'member_id') {
                    console.log('*** FOUND MEMBER_ID ***');
                }
            }
        });

        console.log('=== End Debug ===');
    }

    // โหลดข้อมูลตะกร้าจาก API
    async function loadCartItems() {
        const memberId = getMemberId();

        // ถ้าไม่มี member_id แสดงตะกร้าว่าง
        if (!memberId) {
            showEmptyCart();
            return;
        }

        try {
            // แสดง loading
            showCartLoading();

            // เรียก API ดึงข้อมูลตะกร้า
            const response = await fetch(`controller/cart_api.php?action=get&member_id=${memberId}`);

            if (!response.ok) {
                throw new Error('Failed to fetch cart data');
            }

            const result = await response.json();

            if (result.success && Array.isArray(result.data)) {
                updateCartDisplay(result.data);
            } else {
                console.error('Invalid cart data format:', result);
                showEmptyCart();
            }

        } catch (error) {
            console.error('Error loading cart:', error);
            showCartError();
        }
    }

    // แสดงสถานะ loading
    function showCartLoading() {
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = `
            <div class="cart-loading">
                <div class="loading-spinner"></div>
                <p>กำลังโหลด...</p>
            </div>
        `;
    }

    // แสดงตะกร้าว่าง
    function showEmptyCart() {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const cartFooter = document.getElementById('cartFooter');

        cartItemsContainer.innerHTML = `
            <div class="cart-empty">
                <div class="cart-empty-icon">🛒</div>
                <p>ตะกร้าสินค้าว่างเปล่า</p>
                <a href="products.php" class="shop-now-btn">เลือกซื้อสินค้า</a>
            </div>
        `;
        cartCount.textContent = '0';
        cartFooter.style.display = 'none';
    }

    // แสดงข้อผิดพลาด
    function showCartError() {
        const cartItemsContainer = document.getElementById('cartItems');
        cartItemsContainer.innerHTML = `
            <div class="cart-error">
                <div class="cart-error-icon">⚠️</div>
                <p>เกิดข้อผิดพลาดในการโหลดตะกร้า</p>
                <button onclick="loadCartItems()" class="retry-btn">ลองใหม่</button>
            </div>
        `;
    }

    // อัปเดตการแสดงผลตะกร้า
    function updateCartDisplay(items) {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const cartTotal = document.getElementById('cartTotal');
        const cartFooter = document.getElementById('cartFooter');

        if (items.length === 0) {
            showEmptyCart();
            return;
        }

        // แสดงรายการสินค้า
        let itemsHtml = '';
        let totalPrice = 0;
        let totalItems = 0;

        items.forEach(item => {
            const itemTotal = parseFloat(item.total_price);
            totalPrice += itemTotal;
            totalItems += parseInt(item.quantity);

            // ตั้งค่า default image ถ้าไม่มี
            const imagePath = item.img_path ?
                `controller/uploads/products/${item.img_path}` :
                '';

            itemsHtml += `
                <div class="cart-item-header" data-cart-id="${item.cart_id}">
                    <div class="cart-item-image">
                        <img src="${imagePath}" alt="${item.name}" 
                             onerror="this.src=''">
                    </div>
                    <div class="cart-item-info">
                        <h5>${item.name}</h5>
                        <div class="cart-item-details">
                            <span class="item-size">ไซส์: ${item.size}</span>
                            <span class="item-quantity">จำนวน: ${item.quantity}</span>
                            <span class="item-category">${item.category_name || 'ไม่ระบุ'}</span>
                        </div>
                        <div class="cart-item-price">฿${parseFloat(item.total_price).toLocaleString()}</div>
                    </div>
                    <button class="remove-item" onclick="removeFromCart('${item.cart_id}')" title="ลบสินค้า">
                        ×
                    </button>
                </div>
            `;
        });

        cartItemsContainer.innerHTML = itemsHtml;
        cartCount.textContent = totalItems;
        cartTotal.textContent = `฿${totalPrice.toLocaleString()}`;
        cartFooter.style.display = 'block';
    }

    // ลบสินค้าออกจากตะกร้า
    async function removeFromCart(cartId) {
        try {
            // แสดง loading animation บน item ที่จะลบ
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (cartItem) {
                cartItem.style.opacity = '0.5';
                cartItem.style.pointerEvents = 'none';
            }

            const response = await fetch(`controller/cart_api.php?action=remove&cart_id=${cartId}`, {
                method: 'DELETE'
            });

            const result = await response.json();

            if (result.success) {
                // แสดงข้อความสำเร็จ (ถ้ามี notification system)
                if (typeof showNotification === 'function') {
                    showNotification(result.message || 'ลบสินค้าเรียบร้อยแล้ว', 'success');
                }

                // โหลดตะกร้าใหม่
                await loadCartItems();
            } else {
                // แสดงข้อความผิดพลาด
                if (typeof showNotification === 'function') {
                    showNotification(result.message || 'เกิดข้อผิดพลาดในการลบสินค้า', 'error');
                } else {
                    alert(result.message || 'เกิดข้อผิดพลาดในการลบสินค้า');
                }

                // คืนค่า UI กลับสู่สถานะปกติ
                if (cartItem) {
                    cartItem.style.opacity = '1';
                    cartItem.style.pointerEvents = 'auto';
                }
            }
        } catch (error) {
            console.error('Error removing item from cart:', error);

            if (typeof showNotification === 'function') {
                showNotification('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            } else {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }

            // คืนค่า UI กลับสู่สถานะปกติ
            const cartItem = document.querySelector(`[data-cart-id="${cartId}"]`);
            if (cartItem) {
                cartItem.style.opacity = '1';
                cartItem.style.pointerEvents = 'auto';
            }
        }
    }

    // ฟังก์ชันเพิ่มสินค้าในตะกร้า (สำหรับเรียกใช้จากหน้าอื่น)
    async function addToCart(shoeId, quantity = 1) {
        const memberId = getMemberId();

        if (!memberId) {
            if (typeof showNotification === 'function') {
                showNotification('กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า', 'warning');
            } else {
                alert('กรุณาเข้าสู่ระบบก่อนสั่งซื้อสินค้า');
            }
            window.location.href = 'login.php';
            return;
        }

        try {
            const formData = new FormData();
            formData.append('member_id', memberId);
            formData.append('shoe_id', shoeId);
            formData.append('quantity', quantity);

            const response = await fetch('controller/cart_api.php?action=add', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                if (typeof showNotification === 'function') {
                    showNotification(result.message || 'เพิ่มสินค้าในตะกร้าเรียบร้อยแล้ว', 'success');
                }

                // อัปเดตตะกร้าในทันที
                await loadCartItems();
            } else {
                if (typeof showNotification === 'function') {
                    showNotification(result.message || 'เกิดข้อผิดพลาดในการเพิ่มสินค้า', 'error');
                } else {
                    alert(result.message || 'เกิดข้อผิดพลาดในการเพิ่มสินค้า');
                }
            }
        } catch (error) {
            console.error('Error adding to cart:', error);

            if (typeof showNotification === 'function') {
                showNotification('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
            } else {
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        }
    }

    // ===== LOGOUT FUNCTIONS - ใช้ notification แทน modal =====

    function showLogoutConfirm(event) {
        event.preventDefault();
        
        // ใช้ showConfirm จาก notification.js
        if (typeof showConfirm === 'function') {
            showConfirm(
                'คุณต้องการออกจากระบบหรือไม่?',
                function() {
                    // กดตกลง - ทำการ logout
                    performLogout();
                },
                function() {
                    // กดยกเลิก - ไม่ทำอะไร
                    console.log('Logout cancelled');
                }
            );
        } else {
            // fallback ถ้าไม่มี notification system
            if (confirm('คุณต้องการออกจากระบบหรือไม่?')) {
                performLogout();
            }
        }
    }

    function performLogout() {
        // แสดง loading notification
        let hideLoading;
        if (typeof showLoading === 'function') {
            hideLoading = showLoading('กำลังออกจากระบบ...');
        }

        // ส่งคำขอ logout
        fetch('controller/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=logout'
            })
            .then(response => response.json())
            .then(data => {
                // ซ่อน loading
                if (hideLoading) {
                    hideLoading();
                }

                if (data.success) {
                    // แสดงข้อความสำเร็จ
                    if (typeof showSuccess === 'function') {
                        showSuccess('ออกจากระบบเรียบร้อยแล้ว', 2000);
                    }

                    // Redirect หลังจาก logout สำเร็จ
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 1500);
                } else {
                    // แสดงข้อความผิดพลาด
                    if (typeof showError === 'function') {
                        showError(data.message || 'ไม่สามารถออกจากระบบได้');
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถออกจากระบบได้'));
                    }
                }
            })
            .catch(error => {
                // ซ่อน loading
                if (hideLoading) {
                    hideLoading();
                }

                console.error('Logout error:', error);

                if (typeof showError === 'function') {
                    showError('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                } else {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                }
            });
    }

    // ===== UTILITY FUNCTIONS =====

    // ฟังก์ชันสำหรับ refresh cart จากภายนอก (ใช้เมื่อมีการเปลี่ยนแปลงจากหน้าอื่น)
    window.refreshCart = function() {
        loadCartItems();
    };

    // Export ฟังก์ชัน addToCart เพื่อให้หน้าอื่นเรียกใช้ได้
    window.addToCart = addToCart;
</script>

<style>
    /* เพิ่ม CSS สำหรับ loading และ error states */
    .cart-loading,
    .cart-error {
        text-align: center;
        padding: 20px;
        color: #666;
    }

    .loading-spinner {
        width: 20px;
        height: 20px;
        border: 2px solid #f3f3f3;
        border-top: 2px solid #333;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .cart-error-icon {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .retry-btn {
        background: #007bff;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 10px;
    }

    .retry-btn:hover {
        background: #0056b3;
    }

    .cart-item-header {
        transition: opacity 0.3s ease;
    }

    .cart-item-header[data-cart-id] {
        position: relative;
    }
</style>