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
    <title>รายงานยอดขาย</title>
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
            margin-left: 220px;
            padding: 30px;
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
            text-align: left;
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
        }

        .status {
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
            text-align: center;
            min-width: 80px;
            display: inline-block;
        }

        .status.paid {
            background-color: #3498db;
            color: white;
        }

        .status.completed {
            background-color: #27ae60;
            color: white;
        }

        .summary-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .summary-box {
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-align: center;
            min-width: 200px;
        }

        .summary-label {
            font-size: 14px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .summary-amount {
            font-size: 24px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
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
            
            .summary-box {
                min-width: 150px;
                padding: 12px 20px;
            }
            
            .summary-amount {
                font-size: 20px;
            }

            
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="main-content">
        <div class="report-container">
            <h2 class="report-title">รายงานยอดขาย</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>รหัสสินค้า</th>
                            <th>ชื่อสินค้า</th>
                            <th>หมวดหมู่</th>
                            <th>ขนาด</th>
                            <th>จำนวนเงิน</th>
                            <th>วันที่ชำระ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>P001</td>
                            <td>รองเท้าคัชชูหญิง</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>฿1,200</td>
                            <td>19/07/2025</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>J001</td>
                            <td>รองเท้าผ้าใบหญิง</td>
                            <td>ผู้ใหญ่</td>
                            <td>41 - 45</td>
                            <td>฿1,200</td>
                            <td>19/07/2025</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>A001</td>
                            <td>รองเท้าคลื่นเสียง</td>
                            <td>ลำลอง</td>
                            <td>36 - 40</td>
                            <td>฿1,200</td>
                            <td>19/07/2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="summary-section">
                <div class="summary-box">
                    <table>
                        <thead>
                            <th>รวมจำนวนเงินทั้งหมด</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>฿3,600</td>
                            </tr>
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>