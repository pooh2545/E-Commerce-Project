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
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f6f9;
    }

    /* Main content */
    .main {
      margin-left: 220px;
      padding: 30px;
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }

    .topbar h1 {
      font-size: 24px;
      color: #333;
    }

    .topbar .user {
      font-size: 16px;
      color: #555;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: white;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
    }

    .card h3 {
      margin-bottom: 10px;
      color: #C957BC;
      font-size: 18px;
    }

    .card p {
      font-size: 24px;
      font-weight: bold;
      color: #333;
    }

    .recent {
      margin-top: 40px;
    }

    .recent h2 {
      margin-bottom: 15px;
      color: #333;
      font-weight: bold;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background-color: white;
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #C957BC;
      color: white;
    }

    tr:hover {
      background-color: #f1f1f1;
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

    /* Responsive */
    @media (max-width: 768px) {
      .main {
        margin-left: 0;
        padding: 20px;
      }
      
      .cards {
        grid-template-columns: 1fr;
      }
    }

    .report-title{
        margin-bottom: 40px;
    }

  </style>
</head>

<body>

  <!-- Include Sidebar -->
  <?php include 'sidebar.php'; ?>

  <div class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
    </div>

    <!-- Summary Cards -->
<div class="cards">
  <div class="card">
    <h3>คำสั่งซื้อวันนี้</h3>
    <p id="todayOrders">0 รายการ</p>
  </div>
  <div class="card">
    <h3>รายได้วันนี้</h3>
    <p id="todayRevenue">฿0</p>
  </div>
  <div class="card">
    <h3>สมาชิกทั้งหมด</h3>
    <p id="totalMembers">0 คน</p>
  </div>
  <div class="card">
    <h3>สินค้าใกล้หมด</h3>
    <p id="low-stock-products">0 รายการ</p>
  </div>
</div>


    <!-- Sales Chart -->
    <div class="chart-container">
      <h3 style="color: #333; margin-bottom: 20px;">กราฟยอดขาย</h3>
      <canvas id="salesChart" width="400" height="200"></canvas>
    </div>

    <!-- Recent Orders -->
    <div class="report-container">
      <h2 class="report-title">คำสั่งซื้อล่าสุด</h2>
      <table>
        <thead>
          <tr>
            <th>รหัสสั่งซื้อ</th>
            <th>ชื่อลูกค้า</th>
            <th>วันที่สั่ง</th>
            <th>จำนวนเงิน</th>
            <th>สถานะ</th>
          </tr>
        </thead>
        <tbody id="productTableBody">
          <tr><td colspan="5" class="loading">กำลังโหลดข้อมูล...</td></tr>
        </tbody>
      </table>
    </div>
  </div>

<script>
let payments = [];
let chart = null;

// โหลด summary
function loadSummary() {
    fetch('../controller/dashboard_api.php?action=summary')
        .then(res=>res.json())
        .then(summary=>{
            document.getElementById("todayOrders").textContent = summary.today_orders + " รายการ";
            document.getElementById("todayRevenue").textContent = "฿" + Number(summary.today_revenue).toLocaleString();
            document.getElementById("totalMembers").textContent = summary.total_members + " คน";
            document.getElementById("low-stock-products").textContent = (summary.low_stock_products ?? 0) + " รายการ";
        })
        .catch(err=>console.error("Error loading summary:",err));
}

// โหลด Orders + กราฟ
function loadDashboard() {
    fetch('../controller/dashboard_api.php?action=all')
        .then(res=>res.json())
        .then(data=>{
            payments = data;
            renderPaymentTable();
            renderSalesChart();
        })
        .catch(err=>console.error('Error loading dashboard:',err));
}

// แสดง Orders ในตาราง
function renderPaymentTable(){
    const tbody = document.getElementById('productTableBody');
    tbody.innerHTML='';
    if(!payments || payments.length===0){
        tbody.innerHTML='<tr><td colspan="5" style="text-align:center;color:#666;">ไม่มีข้อมูล</td></tr>';
        return;
    }
    payments.forEach(payment=>{
        const row=document.createElement('tr');
        let orderDate='-';
        if(payment.create_at){
            const date=new Date(payment.create_at);
            orderDate=`${String(date.getDate()).padStart(2,'0')}/${String(date.getMonth()+1).padStart(2,'0')}/${date.getFullYear()+543}`;
        }
        row.innerHTML=`
            <td>${payment.order_number}</td>
            <td>${payment.recipient_name}</td>
            <td>${orderDate}</td>
            <td>฿${Number(payment.total_amount).toLocaleString()}</td>
            <td>${payment.order_status_name}</td>
        `;
        tbody.appendChild(row);
    });
}

// กราฟยอดขาย
function renderSalesChart(){
    let salesByMonth={};
    payments.forEach(payment=>{
        const date=new Date(payment.create_at);
        const month=date.getMonth()+1;
        const monthName=`เดือน ${month}`;
        if(!salesByMonth[monthName]) salesByMonth[monthName]=0;
        salesByMonth[monthName]+=Number(payment.total_amount);
    });
    const labels=Object.keys(salesByMonth).sort((a,b)=>parseInt(a.split(' ')[1])-parseInt(b.split(' ')[1]));
    const salesData=labels.map(l=>salesByMonth[l]);
    const ctx=document.getElementById('salesChart').getContext('2d');
    if(chart) chart.destroy();
    chart=new Chart(ctx,{
        type:'line',
        data:{labels,datasets:[{label:'ยอดขาย (บาท)',data:salesData,fill:true,borderColor:'rgb(75,192,192)',backgroundColor:'rgba(75,192,192,0.2)',tension:0.3}]},
        options:{responsive:true,plugins:{legend:{position:'top'},tooltip:{callbacks:{label:ctx=>`฿${ctx.formattedValue}`}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>`฿${v}`}}}}
    });
}

// โหลดเมื่อเปิดหน้า
document.addEventListener('DOMContentLoaded',()=>{
    loadSummary();
    loadDashboard();
});
</script>
</body>
</html>