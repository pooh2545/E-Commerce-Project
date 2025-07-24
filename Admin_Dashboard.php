<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
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

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background-color: #752092;
      color: white;
      padding: 20px;
    }

    .sidebar h2 {
      font-size: 20px;
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      display: block;
      color: #ccc;
      text-decoration: none;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    .sidebar a:hover {
      background-color: #495057;
      color: white;
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
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="#">Dashboard</a>
    <a href="#">จัดการสินค้า</a>
    <a href="#">คำสั่งซื้อ</a>
    <a href="#">ผู้ใช้งาน</a>
    <a href="#">รายงาน</a>
    <a href="#">ตั้งค่า</a>
    <a href="#">ออกจากระบบ</a>
  </div>

  <!-- Main content -->
  <div class="main">
    <div class="topbar">
      <h1>Dashboard</h1>
    </div>

    <!-- Summary Cards -->
    <div class="cards">
      <div class="card">
        <h3>คำสั่งซื้อวันนี้</h3>
        <p>32 รายการ</p>
      </div>
      <div class="card">
        <h3>รายได้วันนี้</h3>
        <p>฿12,400</p>
      </div>
      <div class="card">
        <h3>สมาชิกทั้งหมด</h3>
        <p>1,052 คน</p>
      </div>
      <div class="card">
        <h3>สินค้าใกล้หมด</h3>
        <p>6 รายการ</p>
      </div>
    </div>

<div class="bg-gray-100 p-4 rounded-lg shadow">
          <canvas id="salesChart" class="w-full h-64" width="595" height="298" style="display: block; box-sizing: border-box; height: 149px; width: 297px;"></canvas>
        </div>

  







    <!-- Recent Orders -->
    <div class="recent">
      <h2>คำสั่งซื้อล่าสุด</h2>
      <table>
        <thead>
          <tr>
            <th>รหัส</th>
            <th>ลูกค้า</th>
            <th>วันที่</th>
            <th>ยอดรวม</th>
            <th>สถานะ</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#001245</td>
            <td>น.ส. สุภาวดี</td>
            <td>19/07/2025</td>
            <td>฿1,200</td>
            <td>จัดส่งแล้ว</td>
          </tr>
          <tr>
            <td>#001244</td>
            <td>คุณธีรพล</td>
            <td>19/07/2025</td>
            <td>฿870</td>
            <td>กำลังเตรียมของ</td>
          </tr>
          <tr>
            <td>#001243</td>
            <td>คุณลลิตา</td>
            <td>19/07/2025</td>
            <td>฿2,500</td>
            <td>รอดำเนินการ</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html> 

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 min-h-screen items-center justify-center">

  <!-- Script กราฟ -->
  <script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.'],
        datasets: [{
          label: 'ยอดขาย (บาท)',
          data: [12000, 19000, 3000, 5000, 20000, 30000],
          fill: true,
          borderColor: 'rgb(75, 192, 192)',
          tension: 0.3,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          pointBackgroundColor: 'rgb(75, 192, 192)'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
        },
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>
</body>
</html>
