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

    <button class="save-btn" id="saveBtn">บันทึกการเปลี่ยนแปลง</button>

    <script>
const pageSelect = document.getElementById('page-select');

// โหลดหน้าทั้งหมดจาก DB
function loadPages() {
  fetch("../controller/content_management_api.php?action=all")
    .then(res => res.json())
    .then(data => {
      pageSelect.innerHTML = '';
      data.forEach(p => {
        const option = document.createElement('option');
        option.value = p.page_name;
        option.text = p.page_name;
        pageSelect.add(option);
      });
      if (data.length > 0) loadPage();
    });
}

// โหลดข้อมูลหน้าเว็บที่เลือก
function loadPage() {
  const page_name = pageSelect.value;
  fetch(`../controller/content_management_api.php?action=get&page_name=${encodeURIComponent(page_name)}`)
    .then(res => res.json())
    .then(data => {
      document.getElementById('page-content').value = data.content || '';
      document.getElementById('custom-html').value = data.custom_code || '';
      // TODO: โหลดรูปภาพถ้าต้องการ
    });
}

// เพิ่มหน้าใหม่
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

// ลบหน้า
function deletePage() {
  const page_name = pageSelect.value;
  if (!page_name) return alert("กรุณาเลือกหน้าก่อนลบ");
  if (confirm(`คุณต้องการลบหน้าที่ชื่อ "${page_name}" หรือไม่?`)) {
    fetch(`../controller/content_management_api.php?action=delete&page_name=${encodeURIComponent(page_name)}`, {
      method: 'DELETE'
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert("ลบสำเร็จ");
        loadPages();
      } else {
        alert("ลบไม่สำเร็จ");
        console.log(data);
      }
    });
  }
}

const saveBtn = document.getElementById('saveBtn');
saveBtn.addEventListener('click', savePage);

// บันทึกหน้า (เพิ่มหรือแก้ไข)
/*function savePage() {
  const page_name = pageSelect.value;
  const content = document.getElementById('page-content').value;
  const custom_code = document.getElementById('custom-html').value;
  const url_path = ''; // ใส่ path ถ้าต้องการ

  fetch('../controller/content_management_api.php?action=create', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({page_name, content, url_path, custom_code})
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("บันทึกสำเร็จ");
      loadPages();
    } else {
      alert("บันทึกไม่สำเร็จ");
      console.log(data);
    }
  });
}*/
function savePage() {
  const page_name = pageSelect.value;
  const content = document.getElementById('page-content').value;
  const custom_code = document.getElementById('custom-html').value;
  const url_path = '';

  const formData = new FormData();
  formData.append('page_name', page_name);
  formData.append('content', content);
  formData.append('url_path', url_path);
  formData.append('custom_code', custom_code);

  fetch('../controller/content_management_api.php?action=create', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      alert("บันทึกสำเร็จ");
      loadPages();
    } else {
      alert("บันทึกไม่สำเร็จ");
      console.log(data);
    }
  });
}


// Event checkbox แสดง/ซ่อน
document.getElementById('toggleContent').addEventListener('change', e => {
  document.getElementById('sectionContent').style.display = e.target.checked ? 'block' : 'none';
});
document.getElementById('toggleImage').addEventListener('change', e => {
  document.getElementById('sectionImage').style.display = e.target.checked ? 'block' : 'none';
});
document.getElementById('toggleCode').addEventListener('change', e => {
  document.getElementById('sectionCode').style.display = e.target.checked ? 'block' : 'none';
});




// โหลดหน้าทั้งหมดตอนเปิดเพจ
loadPages();


    </script>
  </div>
</body>
</html>
