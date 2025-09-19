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
    <title>รายงานชำระเงิน</title>
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo.png">
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

        .btn-view {
            background-color: #3498db;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            color: white;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-view:hover { background-color: #2980b9; }
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0; top: 0;
            width: 100%; height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .modal img { 
            max-width: 100%; 
            height: auto; 
            margin-top: 15px;
            margin-bottom: 15px;
        }
        .modal-close {
            color: white;
            background-color: #C957BC;
            padding: 4px 10px;
            border-radius: 4px;
            margin-top: 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="main-content">
    <div class="report-container">
        <h2 class="report-title">รายงานการชำระเงิน</h2>
        
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>รหัสสั่งซื้อ</th>
                        <th>ชื่อลูกค้า</th>
                        <th>เบอร์โทร</th>
                        <th>วันที่สั่ง</th>
                        <th>จำนวนเงิน</th>
                        <th>ดูหลักฐาน</th>
                        <th>สถานะ</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr><td colspan="7" class="loading">กำลังโหลดข้อมูล...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="summary-section">
            <div class="summary-box">
                <table>
                    <thead><th>รวมจำนวนเงินทั้งหมด</th></thead>
                    <tbody>
                        <tr><td id="totalAmount">฿0</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="slipModal" class="modal">
    <div class="modal-content">
        <img id="slipImage" src="" alt="ไม่มีสลิป">
        <div class="modal-close"> <span>ปิดหน้าต่าง</span> </div>
    </div>
</div>

<script>
let payments = [];

// ✅ โหลดข้อมูลการชำระเงิน
function loadPayments() {
    fetch('../controller/payment_report_api.php?action=all')
        .then(res => res.json())
        .then(data => {
            payments = data;
            renderPaymentTable();
        })
        .catch(err => {
            console.error('Error loading payments:', err);
            document.getElementById('productTableBody').innerHTML =
                '<tr><td colspan="7" style="text-align:center;color:red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
        });
}

// ✅ แสดงข้อมูลในตาราง
function renderPaymentTable() {
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML = '';
    if (!payments || payments.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#666;">ไม่มีข้อมูล</td></tr>';
        document.getElementById('totalAmount').textContent = '฿0';
        return;
    }

    let totalAmount = 0;

    payments.forEach(payment => {
        const row = document.createElement('tr');
        let orderDate = '-';

        // ✅ แปลงวันที่ create_at ให้เป็น DD/MM/YYYY (พ.ศ.)
        if (payment.create_at) {
            const date = new Date(payment.create_at);
            orderDate = `${String(date.getDate()).padStart(2,'0')}/` +
                        `${String(date.getMonth()+1).padStart(2,'0')}/` +
                        `${date.getFullYear() + 543}`;
        }

        totalAmount += Number(payment.total_amount);

        row.innerHTML = `
            <td>${payment.order_number}</td>
            <td>${payment.recipient_name}</td>
            <td>${payment.shipping_phone}</td>
            <td>${orderDate}</td>
            <td>฿${Number(payment.total_amount).toLocaleString()}</td>
            
            <td>
                <button onclick="viewSlip('${payment.payment_slip_path}')"
                        style="padding:5px 10px; background:#3498db; color:white; border:none; border-radius:5px; cursor:pointer;">
                    ดูสลิป
                </button>
            </td>
            
            <td>${payment.order_status_name}</td>

            
        `;
        tbody.appendChild(row);
    });

    // ✅ รวมจำนวนเงินทั้งหมด
    document.getElementById('totalAmount').textContent = `฿${totalAmount.toLocaleString()}`;
}

// ✅ เปิดสลิปการชำระเงินใน popup modal
function viewSlip(path) {
    if (!path) {
        alert("ไม่มีหลักฐานการชำระเงิน");
        return;
    }

    const modal = document.getElementById('slipModal');
    const slipImage = document.getElementById('slipImage');
    slipImage.src = ` ../controller/${path}`;
    modal.style.display = "block";
}

// ✅ ปิด modal เมื่อกดปุ่มปิด
document.querySelector('.modal-close').addEventListener('click', () => {
    document.getElementById('slipModal').style.display = 'none';
});

// ✅ ปิด modal เมื่อคลิกพื้นที่นอกกรอบ
window.addEventListener('click', (event) => {
    const modal = document.getElementById('slipModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
});


document.addEventListener('DOMContentLoaded', () => {
    loadPayments();
});
</script>

</body>
</html>