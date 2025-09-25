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
    <link rel="icon" type="image/x-icon" href="../assets/images/Logo.png">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        thead th, tbody td {
            text-align: center;
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

        /* กราฟยอดขาย */
        .chart-container {
            margin-top: 40px;
            margin-bottom: 40px;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
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

        <!-- เลือกปีและเดือน -->
        <div style="margin-bottom: 20px; display:flex; gap:10px; align-items:center;">
            <label for="yearSelect">เลือกปี:</label>
            <select id="yearSelect"></select>

            <label for="monthSelect">เลือกเดือน:</label>
            <select id="monthSelect">
                <option value="">ทั้งหมด</option>
                <option value="1">มกราคม</option>
                <option value="2">กุมภาพันธ์</option>
                <option value="3">มีนาคม</option>
                <option value="4">เมษายน</option>
                <option value="5">พฤษภาคม</option>
                <option value="6">มิถุนายน</option>
                <option value="7">กรกฎาคม</option>
                <option value="8">สิงหาคม</option>
                <option value="9">กันยายน</option>
                <option value="10">ตุลาคม</option>
                <option value="11">พฤศจิกายน</option>
                <option value="12">ธันวาคม</option>
            </select>
        </div>

        <!-- กราฟยอดขาย -->
        <div class="chart-container">
            <h3 style="color: #333; margin-bottom: 20px;">กราฟยอดขาย</h3>
            <canvas id="salesChart" width="400" height="200"></canvas>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>หมวดหมู่</th>
                        <th>ขนาด</th>
                        <th>จำนวนเงิน</th>
                        <th>จำนวนสินค้า</th>
                        <th>วันที่สั่ง</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr><td colspan="6" class="loading">กำลังโหลดข้อมูล...</td></tr>
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

<script>
let products = [];
let chart = null;

function populateYearSelect() {
    const yearSelect = document.getElementById('yearSelect');
    const currentYear = new Date().getFullYear();
    for(let i = currentYear; i >= currentYear - 10; i--) {
        const option = document.createElement('option');
        option.value = i;
        option.textContent = i + 543; // พ.ศ.
        yearSelect.appendChild(option);
    }
    yearSelect.value = currentYear;
}

function loadProducts() {
    const year = document.getElementById('yearSelect').value;
    const month = document.getElementById('monthSelect').value;
    let url = `../controller/sale_report_api.php?action=all&year=${year}`;
    if(month) url += `&month=${month}`;

    fetch(url)
    .then(res => res.json())
    .then(data => {
        products = data;
        renderProductTable();
        renderSalesChart();
    })
    .catch(err => {
        console.error('Error loading products:', err);
        document.getElementById('productTableBody').innerHTML =
            '<tr><td colspan="6" style="text-align:center;color:red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>';
    });
}

function renderProductTable() {
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML = '';
    if (!products || products.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#666;">ไม่มีข้อมูลสินค้า</td></tr>';
        document.getElementById('totalAmount').textContent = '฿0';
        return;
    }

    let totalAmount = 0;

    products.forEach(product => {
        const row = document.createElement('tr');
        row.dataset.id = product.shoe_id;
        let orderDate = '-';
        if(product.order_date){
            const date = new Date(product.order_date);
            orderDate = `${String(date.getDate()).padStart(2,'0')}/${String(date.getMonth()+1).padStart(2,'0')}/${date.getFullYear()+543}`;
        }
        totalAmount += Number(product.total_price);
        row.innerHTML = `
            <td>${product.shoe_id}</td>
            <td>${product.name}</td>
            <td>${product.category_name || 'ไม่ระบุ'}</td>
            <td>${product.size}</td>
            <td>฿${Math.round(product.price)}</td>
            <td>${product.quantity}</td>
            <td>${orderDate}</td>
        `;
        tbody.appendChild(row);
    });

    document.getElementById('totalAmount').textContent = `฿${Math.round(totalAmount)}`;
}

function renderSalesChart() {
    const year = document.getElementById('yearSelect').value;
    const month = document.getElementById('monthSelect').value;

    let labels = [];
    let salesData = [];

    if (!month) {
        // กรณีเลือก "ทั้งหมด" → รายเดือน
        const monthNames = [
            "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน",
            "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม",
            "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
        ];

        let salesByMonth = {};

        products.forEach(product => {
            if (!product.order_date) return;
            const date = new Date(product.order_date);
            const m = date.getMonth(); // 0–11
            const monthName = monthNames[m];
            if (!salesByMonth[monthName]) salesByMonth[monthName] = 0;
            salesByMonth[monthName] += Number(product.total_price);
        });

        labels = monthNames;
        salesData = labels.map(l => salesByMonth[l] || 0);

    } else {
        // กรณีเลือก "เดือนเดียว" → รายสัปดาห์
        let salesByWeek = { 
            "สัปดาห์ที่ 1": 0, 
            "สัปดาห์ที่ 2": 0, 
            "สัปดาห์ที่ 3": 0, 
            "สัปดาห์ที่ 4": 0, 
            "สัปดาห์ที่ 5": 0 
        };

        products.forEach(product => {
            if (!product.order_date) return;
            const date = new Date(product.order_date);
            if ((date.getMonth() + 1) === parseInt(month)) { // แปลง month เป็น number
                const week = Math.ceil(date.getDate() / 7); // สัปดาห์ 1–5
                const weekLabel = `สัปดาห์ที่ ${week}`; // ตรงกับ key ของ salesByWeek
                salesByWeek[weekLabel] += Number(product.total_price);
            }
        });

        const weekLabels = ["สัปดาห์ที่ 1", "สัปดาห์ที่ 2", "สัปดาห์ที่ 3", "สัปดาห์ที่ 4", "สัปดาห์ที่ 5"];
        labels = weekLabels;
        salesData = weekLabels.map(l => salesByWeek[l]);
    }

    const ctx = document.getElementById('salesChart').getContext('2d');
    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'ยอดขาย (บาท)',
                data: salesData,
                fill: true,
                borderColor: 'rgb(75,192,192)',
                tension: 0.3,
                backgroundColor: 'rgba(75,192,192,0.2)',
                pointBackgroundColor: 'rgb(75,192,192)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { callbacks: { label: ctx => `฿${ctx.formattedValue}` } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: v => `฿${v}` } }
            }
        }
    });
}



document.addEventListener('DOMContentLoaded',()=>{
    populateYearSelect();
    loadProducts();
    document.getElementById('yearSelect').addEventListener('change',loadProducts);
    document.getElementById('monthSelect').addEventListener('change',loadProducts);
});
</script>

</body>
</html>