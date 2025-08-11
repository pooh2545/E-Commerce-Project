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
                    <span class="cart-count" id="cartCount">0</span>
                </a>
                <div class="cart-menu" id="cartMenu">
                    <div class="cart-menu-header">
                        <h4>ตะกร้าสินค้า</h4>
                    </div>
                    <div class="cart-menu-items" id="cartItems">
                        <!-- Cart items will be loaded here -->
                        <div class="cart-empty">
                            <div class="cart-empty-icon">🛒</div>
                            <p>ตะกร้าสินค้าว่างเปล่า</p>
                            <a href="products.php" class="shop-now-btn">เลือกซื้อสินค้า</a>
                        </div>
                    </div>
                    <div class="cart-menu-footer" id="cartFooter" style="display: none;">
                        <div class="cart-total">
                            <span>รวมทั้งหมด: <strong id="cartTotal">฿0</strong></span>
                        </div>
                        <div class="cart-actions">
                            <a href="cart.php" class="view-cart-btn">ดูตะกร้า</a>
                            <a href="checkout.php" class="checkout-btn">สั่งซื้อ</a>
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
                            <a href="profile.php" class="profile-menu-item">
                                <span class="menu-icon">👤</span>
                                <span>ข้อมูลส่วนตัว</span>
                            </a>
                            <a href="orders.php" class="profile-menu-item">
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
        <ul>
            <li><a href="products.php?category=men">ผู้ชาย</a></li>
            <li><a href="products.php?category=women">ผู้หญิง</a></li>
            <li><a href="products.php?category=extra-size">Extra Size</a></li>
            <li><a href="products.php?category=divided">Divided</a></li>
            <li><a href="products.php?category=sport">Sport</a></li>
            <li><a href="products.php?category=bag">Bag</a></li>
            <li><a href="products.php?category=shoes">Shoes</a></li>
        </ul>
    </nav>
</header>

<!-- Logout Confirmation Modal -->
<div class="logout-confirm-modal" id="logoutConfirmModal">
    <div class="logout-confirm-content">
        <div class="logout-confirm-header">
            <div class="logout-confirm-icon">🚪</div>
            <h3>ยืนยันการออกจากระบบ</h3>
            <p>คุณต้องการออกจากระบบหรือไม่?</p>
        </div>
        <div class="logout-confirm-actions">
            <button class="logout-btn-cancel" onclick="closeLogoutConfirm()">ยกเลิก</button>
            <button class="logout-btn-confirm" onclick="confirmLogout()">ออกจากระบบ</button>
        </div>
    </div>
</div>

<script>
    // Cart Functions
    function loadCartItems() {
        // โหลดข้อมูลตะกร้าจาก localStorage หรือ API
        const cartItems = getCartFromStorage();
        updateCartDisplay(cartItems);
    }

    function getCartFromStorage() {
        // ตัวอย่างข้อมูล Cart (ในการใช้งานจริงจะเอาจาก localStorage หรือ database)
        return [{
                id: 1,
                name: "รองเท้าผ้าใบ Nike Air Max",
                price: 2500,
                quantity: 1,
                image: "assets/images/shoe1.jpg",
                size: "42"
            },
            {
                id: 2,
                name: "รองเท้าหนัง Formal",
                price: 1800,
                quantity: 2,
                image: "assets/images/shoe2.jpg",
                size: "41"
            }
        ];
    }

    function updateCartDisplay(items) {
        const cartItemsContainer = document.getElementById('cartItems');
        const cartCount = document.getElementById('cartCount');
        const cartTotal = document.getElementById('cartTotal');
        const cartFooter = document.getElementById('cartFooter');

        if (items.length === 0) {
            cartItemsContainer.innerHTML = `
                    <div class="cart-empty">
                        <div class="cart-empty-icon">🛒</div>
                        <p>ตะกร้าสินค้าว่างเปล่า</p>
                        <a href="products.php" class="shop-now-btn">เลือกซื้อสินค้า</a>
                    </div>
                `;
            cartCount.textContent = '0';
            cartFooter.style.display = 'none';
            return;
        }

        // แสดงรายการสินค้า
        let itemsHtml = '';
        let totalPrice = 0;
        let totalItems = 0;

        items.forEach(item => {
            const itemTotal = item.price * item.quantity;
            totalPrice += itemTotal;
            totalItems += item.quantity;

            itemsHtml += `
                    <div class="cart-item">
                        <div class="cart-item-image">
                            <img src="${item.image}" alt="${item.name}" <!--onerror="this.src='assets/images/placeholder.jpg'"-->>
                        </div>
                        <div class="cart-item-info">
                            <h5>${item.name}</h5>
                            <div class="cart-item-details">
                                <span class="item-size">ไซส์: ${item.size}</span>
                                <span class="item-quantity">จำนวน: ${item.quantity}</span>
                            </div>
                            <div class="cart-item-price">฿${itemTotal.toLocaleString()}</div>
                        </div>
                        <button class="remove-item" onclick="removeFromCart(${item.id})">×</button>
                    </div>
                `;
        });

        cartItemsContainer.innerHTML = itemsHtml;
        cartCount.textContent = totalItems;
        cartTotal.textContent = `฿${totalPrice.toLocaleString()}`;
        cartFooter.style.display = 'block';
    }

    function removeFromCart(itemId) {
        // ฟังก์ชันลบสินค้าจากตะกร้า
        console.log('Remove item:', itemId);
        // ในการใช้งานจริงจะส่งไป API หรือลบจาก localStorage
        // แล้วโหลดข้อมูลใหม่
        loadCartItems();
    }

    // เรียกใช้เมื่อหน้าเว็บโหลด
    document.addEventListener('DOMContentLoaded', function() {
        loadCartItems();
    });

    // Logout Confirmation Functions
    function showLogoutConfirm(event) {
        event.preventDefault();
        document.getElementById('logoutConfirmModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeLogoutConfirm() {
        document.getElementById('logoutConfirmModal').classList.remove('show');
        document.body.style.overflow = '';
    }

    function confirmLogout() {
        const confirmBtn = document.querySelector('.logout-btn-confirm');
        const originalText = confirmBtn.textContent;

        confirmBtn.textContent = 'กำลังออกจากระบบ...';
        confirmBtn.disabled = true;

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
                if (data.success) {
                    closeLogoutConfirm();

                    // แสดงข้อความสำเร็จ (ถ้ามี notification system)
                    if (typeof showNotification === 'function') {
                        showNotification('ออกจากระบบเรียบร้อยแล้ว');
                    }

                    // Redirect หลังจาก logout สำเร็จ
                    setTimeout(() => {
                        window.location.href = data.redirect || 'login.php';
                    }, 1000);
                } else {
                    // แสดงข้อความผิดพลาด
                    if (typeof showNotification === 'function') {
                        showNotification('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถออกจากระบบได้'), 'error');
                    } else {
                        alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถออกจากระบบได้'));
                    }

                    // Reset button
                    confirmBtn.textContent = originalText;
                    confirmBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Logout error:', error);

                if (typeof showNotification === 'function') {
                    showNotification('เกิดข้อผิดพลาดในการเชื่อมต่อ', 'error');
                } else {
                    alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
                }

                // Reset button
                confirmBtn.textContent = originalText;
                confirmBtn.disabled = false;
            });
    }

    // Close modal when clicking outside
    document.getElementById('logoutConfirmModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeLogoutConfirm();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('logoutConfirmModal').classList.contains('show')) {
                closeLogoutConfirm();
            }
        }
    });
</script>