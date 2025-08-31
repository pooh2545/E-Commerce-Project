<?php
require_once '../controller/admin_auth_check.php';

$auth = requireLogin();
$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
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
        }

        .sidebar a:hover {
            background-color: #C957BC;
            color: white;
        }

        .sidebar a.active {
            background-color: #C957BC;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Sidebar Component -->
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin_dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'Admin_Dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
        <a href="productmanage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'productmanage.php') ? 'active' : ''; ?>">จัดการสินค้า</a>
        <a href="ordermanage.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'active' : ''; ?>">คำสั่งซื้อ</a>
        <a href="admin_management.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">ผู้ใช้งาน</a>
        <a href="saleReport.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'reports.php') ? 'active' : ''; ?>">รายงาน</a>
        <a href="content_management.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">ตั้งค่า</a>
        <a id="adminLogoutBtn">ออกจากระบบ</a>
    </div>
    <script>

        function adminLogout(){
            if (confirm('คุณต้องการออกจากระบบใช้หรือไม่?')) {
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
                        console.error('Error deleting product:', error);
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