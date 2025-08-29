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
    <title>รายงานสต็อกสินค้า</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .main-content {
            padding: 20px;
            background-color: #e8e8e8;
            min-height: 100vh;
        }

        .header {
            background-color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #333;
            font-size: 24px;
            font-weight: 500;
        }

        .report-container {
            border-radius: 8px;
            padding: 20px;
        }

        .report-title {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        thead {
            background: #C957BC;
        }

        thead th {
            padding: 12px 15px;
            text-align: center;
            color: white;
            font-weight: 500;
            font-size: 14px;
            border: none;
        }

        tbody tr {
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }

        tbody tr:hover {
            background-color: #f8f9fa;
        }

        tbody td {
            padding: 12px 15px;
            font-size: 14px;
            color: #333;
            text-align: center;
        }

        .status-button {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            min-width: 60px;
            display: inline-block;
            margin: 0 2px;
            border: none;
            cursor: pointer;
        }

        .status-button.edit {
            background-color: #3498db;
            color: white;
        }

        .status-button.delete {
            background-color: #e74c3c;
            color: white;
        }

        .status-button:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            table {
                font-size: 12px;
            }
            
            thead th, tbody td {
                padding: 8px 10px;
            }
            
            .status-button {
                padding: 3px 8px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="report-container">
            <h2 class="report-title">รายงานสต็อกสินค้า</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่</th>
                            <th>ขนาด</th>
                            <th>ราคา</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>P001</td>
                            <td>รองเท้าผู้ชายสีดำ</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>5</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>J002</td>
                            <td>รองเท้าผู้ใหญ่สีน้ำตาล</td>
                            <td>ผู้หญิง</td>
                            <td>41 - 45</td>
                            <td>8</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P001</td>
                            <td>รองเท้าผู้ชาย</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>5</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>J002</td>
                            <td>รองเท้าผู้หญิง</td>
                            <td>ผู้หญิง</td>
                            <td>41 - 45</td>
                            <td>8</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P001</td>
                            <td>รองเท้าผู้ชาย</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>5</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>J002</td>
                            <td>รองเท้าผู้หญิง</td>
                            <td>ผู้หญิง</td>
                            <td>41 - 45</td>
                            <td>8</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>P001</td>
                            <td>รองเท้าผู้ชาย</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>5</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                        <tr>
                            <td>J002</td>
                            <td>รองเท้าผู้หญิงน้ำตาล</td>
                            <td>ผู้หญิง</td>
                            <td>41 - 45</td>
                            <td>8</td>
                            <td>
                                <button class="status-button edit">แก้ไข</button>
                                <button class="status-button delete">ลบ</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // เพิ่ม JavaScript สำหรับจัดการการคลิกปุ่ม
        document.addEventListener('DOMContentLoaded', function() {
            // จัดการปุ่มแก้ไข
            const editButtons = document.querySelectorAll('.status-button.edit');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    alert('คลิกปุ่มแก้ไข');
                });
            });

            // จัดการปุ่มลบ
            const deleteButtons = document.querySelectorAll('.status-button.delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('คุณต้องการลบรายการนี้หรือไม่?')) {
                        const row = this.closest('tr');
                        row.remove();
                    }
                });
            });
        });
    </script>
</body>
</html>