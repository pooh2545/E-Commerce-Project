<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการเนื้อหาเว็บไซต์</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 30px 50px;
      background-color: #f9f9f9;
      color: #333;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background-color: #752092;
      padding: 20px;
    }

    .sidebar h2 {
      color: #fff;
      font-size: 20px;
      text-align: center;
      margin-bottom: 30px;
    }

    .sidebar a {
      color: #fff;
      display: block;
      text-decoration: none;
      padding: 10px;
      border-radius: 6px;
      margin-bottom: 10px;
    }

    .sidebar a:hover {
      background-color: #C957BC;
      color: #fff;
    }

    .container {
      margin-left: 260px;
      width: calc(100% - 320px);
      padding: 30px;
      border-radius: 10px;
    }

    h1 {
      font-size: 28px;
      margin-bottom: 20px;
    }

    .button-group {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    button {
      padding: 10px 20px;
      font-size: 16px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
    }

    .add-btn {
      background-color: #0D6EFD;
      color: white;
    }

    .delete-btn {
      background-color: #f44336;
      color: white;
    }

    label {
      display: block;
      margin-top: 20px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    select, textarea, input[type="file"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 4px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      background-color: white;
    }

    textarea {
      height: 150px;
      resize: vertical;
    }

    .save-btn {
      width: 100%;
      margin-top: 30px;
      background-color: #28A745;
      color: white;
    }

    .section {
      display: none;
    }

    .checkbox-group {
      margin-top: 20px;
    }

    .checkbox-group label {
      display: inline-block;
      margin-right: 20px;
      font-weight: normal;
    }

        /* ... (CSS เดิมตามที่คุณมีอยู่แล้ว) ... */
    select, input[type="file"], input[type="text"], input[type="email"] {
      width: 100%;
      padding: 10px;
      font-size: 16px;
      border-radius: 4px;
      border: 1px solid #ccc;
      box-sizing: border-box;
      background-color: white;
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

  <div class="container">
    <h1>จัดการเนื้อหาเว็บไซต์</h1>

    <div class="button-group">
      <button class="add-btn" onclick="addPage()">เพิ่มหน้า</button>
      <button class="delete-btn" onclick="confirmDelete()">ลบหน้า</button>
    </div>

    <label for="page-select">เลือกหน้าเว็บไซต์</label>
    <select id="page-select">
      <option value="หน้าหลัก">หน้าหลัก</option>
      <option value="เกี่ยวกับเรา">เกี่ยวกับเรา</option>
    </select>

    <div class="checkbox-group">
      <label><input type="checkbox" id="toggleContent" checked> แสดงเนื้อหา</label>
      <label><input type="checkbox" id="toggleImage"> อัปโหลดรูปภาพ</label>
      <label><input type="checkbox" id="toggleCode"> แสดงโค้ด HTML</label>
    </div>

    <div id="sectionContent" class="section" style="display: block;">
      <label for="page-content">เนื้อหาข้อความ</label>
      <textarea id="page-content" placeholder="เนื้อหาข้อความ..."></textarea>
    </div>

    <div id="sectionImage" class="section">
      <label for="image-upload">รูปภาพ</label>
      <input type="file" id="image-upload" accept="image/*">
    </div>

    <div id="sectionCode" class="section">
      <label for="custom-html">HTML/Custom Code</label>
      <textarea id="custom-html" placeholder="HTML/Custom Code..."></textarea>
    </div>

    <button class="save-btn">บันทึกการเปลี่ยนแปลง</button>

    <script>
      const pageSelect = document.getElementById('page-select');

      function addPage() {
        const name = prompt("กรุณาใส่ชื่อหน้าที่ต้องการเพิ่ม:");
        if (name) {
          const option = document.createElement("option");
          option.value = name;
          option.text = name;
          pageSelect.add(option);
          pageSelect.value = name;

          document.getElementById('page-content').value = '';
          document.getElementById('image-upload').value = '';
          document.getElementById('custom-html').value = '';
        }
      }

      function confirmDelete() {
        const selectedIndex = pageSelect.selectedIndex;
        if (selectedIndex !== -1) {
          const confirmDelete = confirm("คุณต้องการลบหน้านี้หรือไม่?");
          if (confirmDelete) {
            pageSelect.remove(selectedIndex);
            alert("ลบหน้านี้แล้ว");

            document.getElementById('page-content').value = '';
            document.getElementById('image-upload').value = '';
            document.getElementById('custom-html').value = '';
          }
        }
      }

      // แสดง/ซ่อน section ตาม checkbox
      document.getElementById('toggleContent').addEventListener('change', function () {
        document.getElementById('sectionContent').style.display = this.checked ? 'block' : 'none';
      });

      document.getElementById('toggleImage').addEventListener('change', function () {
        document.getElementById('sectionImage').style.display = this.checked ? 'block' : 'none';
      });

      document.getElementById('toggleCode').addEventListener('change', function () {
        document.getElementById('sectionCode').style.display = this.checked ? 'block' : 'none';
      });
    </script>
  </div>
</body>
</html>
