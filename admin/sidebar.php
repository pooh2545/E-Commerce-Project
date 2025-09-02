<?php
require_once '../controller/admin_auth_check.php';

$auth = requireLogin();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 220px;
            background-color: #752092;
            color: #ffffff;
            padding: 20px;
            z-index: 1000;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .sidebar h2 {
            font-size: 20px;
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }

        .sidebar a:hover {
            background-color: #C957BC;
            color: white;
        }

        .sidebar a.active {
            background-color: #C957BC;
            color: white;
        }

        /* Submenu Styles */
        .menu-item {
            position: relative;
        }

        .menu-item.has-submenu>a::after {
            content: '';
            float: right;
            transition: transform 0.3s ease;
        }

        .menu-item.has-submenu.open>a::after {
            transform: rotate(180deg);
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;

            border-radius: 6px;
            margin-bottom: 0px;
        }

        .submenu.open {
            max-height: 200px;
            /* Adjust as needed */
        }

        .submenu a {
            padding: 8px 15px 8px 25px;
            font-size: 14px;
            border-radius: 4px;
            margin-bottom: 5px;
            background-color: transparent;
        }

        .submenu a:hover {
            background-color: #C957BC;
            color: white;
        }

        .submenu a.active {
            background-color: rgba(255, 255, 255, 0.25);
        }

        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background-color: #752092;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .sidebar-overlay.active {
                display: block;
            }

            .mobile-menu-btn {
                display: block;
            }

            /* Adjust main content for mobile */
            .main {
                margin-left: 0 !important;
            }

            .container {
                margin-left: 0 !important;
                padding-left: 20px !important;
                padding-right: 20px !important;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .sidebar h2 {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .sidebar a {
                padding: 12px;
                font-size: 14px;
            }

            .submenu a {
                padding: 10px 15px 10px 30px;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z" />
        </svg>
    </button>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar Component -->
    <div class="sidebar">
        <h2>Admin Panel</h2>

        <a href="admin_dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>

        <!-- Product Management with Submenu -->
        <div class="menu-item has-submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['productmanage.php', 'product_categories.php'])) ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['productmanage.php', 'product_categories.php'])) ? 'active' : ''; ?>">
                จัดการสินค้า
            </a>
            <div class="submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['productmanage.php', 'product_categories.php'])) ? 'open' : ''; ?>">
                <a href="productmanage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'productmanage.php') ? 'active' : ''; ?>">ข้อมูลสินค้า</a>
                <a href="product_categories.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'product_categories.php') ? 'active' : ''; ?>">หมวดหมู่สินค้า</a>
            </div>
        </div>

        <div class="menu-item has-submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['ordermanage.php', 'shippingManage.php'])) ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['ordermanage.php', 'shippingManage.php'])) ? 'active' : ''; ?>">
                คำสั่งซื้อ
            </a>
            <div class="submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['ordermanage.php', 'shippingManage.php'])) ? 'open' : ''; ?>">
                <a href="ordermanage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'ordermanage.php') ? 'active' : ''; ?>">รายการคำสั่งซื้อ</a>
                <a href="shippingManage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'shippingManage.php') ? 'active' : ''; ?>">จัดการการส่งสินค้า</a>
            </div>
        </div>

        <div class="menu-item has-submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['admin_management.php', 'memberManage.php'])) ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['admin_management.php', 'memberManage.php'])) ? 'active' : ''; ?>">
                ผู้ใช้งาน
            </a>
            <div class="submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['admin_management.php', 'memberManage.php'])) ? 'open' : ''; ?>">
                <a href="admin_management.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'admin_management.php') ? 'active' : ''; ?>">ข้อมูลผู้ดูแลระบบ</a>
                <a href="memberManage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'memberManage.php') ? 'active' : ''; ?>">ข้อมูลลูกค้า</a>
            </div>
        </div>

        <div class="menu-item has-submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['saleReport.php', 'paymentReport.php', 'stockReport.php'])) ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['saleReport.php', 'paymentReport.php', 'stockReport.php'])) ? 'active' : ''; ?>">
                รายงาน
            </a>
            <div class="submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['saleReport.php', 'paymentReport.php', 'stockReport.php'])) ? 'open' : ''; ?>">
                <a href="saleReport.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'saleReport.php') ? 'active' : ''; ?>">รายงานยอดขาย</a>
                <a href="paymentReport.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'paymentReport.php') ? 'active' : ''; ?>">รายงานชำระเงิน</a>
                <a href="stockReport.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'stockReport.php') ? 'active' : ''; ?>">รายงานสต็อกสินค้า</a>
            </div>
        </div>


        <div class="menu-item has-submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['content_management.php', 'payment_set_up_receiving_account.php'])) ? 'open' : ''; ?>">
            <a href="javascript:void(0)" onclick="toggleSubmenu(this)" class="<?php echo (in_array(basename($_SERVER['PHP_SELF']), ['content_management.php', 'payment_set_up_receiving_account.php'])) ? 'active' : ''; ?>">
                ตั้งค่า
            </a>
            <div class="submenu <?php echo (in_array(basename($_SERVER['PHP_SELF']), ['content_management.php', 'payment_set_up_receiving_account.php'])) ? 'open' : ''; ?>">
                <a href="content_management.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'content_management.php') ? 'active' : ''; ?>">จัดการเนื้อหาเว็บไซต์</a>
                <a href="payment_set_up_receiving_account.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'payment_set_up_receiving_account.php') ? 'active' : ''; ?>">ตั้งค่าการชำระเงิน</a>
            </div>
        </div>

        <a id="adminLogoutBtn">ออกจากระบบ</a>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        }

        function toggleSubmenu(element) {
            const menuItem = element.parentElement;
            const submenu = menuItem.querySelector('.submenu');

            menuItem.classList.toggle('open');
            submenu.classList.toggle('open');
        }

        // Close sidebar when clicking on submenu items (mobile)
        document.querySelectorAll('.submenu a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Close sidebar when clicking on regular menu items (mobile)
        document.querySelectorAll('.sidebar > a:not(#adminLogoutBtn)').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    closeSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });

        function adminLogout() {
            if (confirm('คุณต้องการออกจากระบบใช่หรือไม่?')) {
                fetch(`../controller/admin_api.php?action=logout`, {
                        method: 'POST'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            alert(data.message || 'ออกจากระบบเรียบร้อย');
                            window.location.href = 'index.php';
                        } else {
                            alert(data.message || 'เกิดข้อผิดพลาด');
                        }
                    })
                    .catch(error => {
                        console.error('Error logging out:', error);
                        alert('เกิดข้อผิดพลาดในออกจากระบบ');
                    });
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('adminLogoutBtn').addEventListener('click', adminLogout);
        });
    </script>
</body>

</html>