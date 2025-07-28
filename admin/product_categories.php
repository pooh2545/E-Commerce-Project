<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>หน้าจัดการสินค้า : หมวดหมู่สินค้า</title>
  <style>
        * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      margin: 0;
      padding: 0;
    }

    
    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      height: 100vh;
      width: 220px;
      background-color: #752092;
      color: #fff;
      padding: 20px;
    }

    .sidebar h2 {
      /*color: #fff;*/
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


    /* Main content */
    .container {
      /*width: 70%;
      margin: 30px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);*/
        width: calc(100% - 280px);  /* ลดขนาดลง 240px สำหรับ sidebar + 40px สำหรับขอบขวา */
         margin-left: 240px;         /* ขยับให้พ้น sidebar */
        margin-right: 40px;         /* เว้นขอบขวา */
        margin-top: 30px;
        padding: 30px;
        border-radius: 10px;
 

    }
    h1 {
      text-align: left;
      color: #333;
      margin-bottom: 20px;
    }
    form {
      margin-bottom: 20px;
    }
    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      margin-bottom: 10px;
      background: #fff;
    }
    .preview {
      text-align: center;
      margin-top: 10px;
    }
    .preview img {
      max-width: 150px;
      border: 1px solid #ddd;
      padding: 5px;
      border-radius: 5px;
      background: #fafafa;
    }
    button {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      color: white;
      cursor: pointer;
      font-size: 14px;
    }
    button:hover {
      opacity: 0.9;
    }
    #saveCategoryBtn {
      background-color: #28a745;
      width: 100%;
      font-size: 16px;
      padding: 12px;
      margin-top: 10px;
    }
    .edit-btn {
      background-color: #0D6EFD;
      margin-right: 5px;
    }
    .edit-btn:hover {
      background-color: #0b5ed7;
    }
    .delete-btn {
      background-color: #dc3545;
    }
    .delete-btn:hover {
      background-color: #c82333;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
      vertical-align: middle;
      background: #fff;
    }
    th {
      background-color: #C957BC;
      color: white;
    }
    td img {
      max-width: 100px;
      border-radius: 5px;
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
    <h1>หมวดหมู่สินค้า</h1>

    <!-- ฟอร์มเพิ่ม/แก้ไขหมวดหมู่ -->
    <form id="categoryForm">
      <label for="categoryName">ชื่อหมวดหมู่</label>
      <input type="text" id="categoryName" placeholder="เช่น ลำลอง" required>

      <label for="categoryImage">รูปหมวดหมู่</label>
      <input type="file" id="categoryImage" accept="image/*">
      <div class="preview" id="imagePreview"><p>ยังไม่มีรูปที่เลือก</p></div>

      <button type="submit" id="saveCategoryBtn">บันทึกหมวดหมู่</button>
    </form>

    <!-- ตารางหมวดหมู่ -->
    <table>
      <thead>
        <tr>
          <th>รูปหมวดหมู่</th>
          <th>ชื่อหมวดหมู่</th>
          <th>จัดการ</th>
        </tr>
      </thead>
      <tbody id="categoryTable">
        <!-- หมวดหมู่จะถูกเพิ่มที่นี่ -->
      </tbody>
    </table>
  </div>

  <script>
    const categoryForm = document.getElementById("categoryForm");
    const categoryNameInput = document.getElementById("categoryName");
    const categoryImageInput = document.getElementById("categoryImage");
    const imagePreview = document.getElementById("imagePreview");
    const categoryTable = document.getElementById("categoryTable");
    const saveCategoryBtn = document.getElementById("saveCategoryBtn");

    let editIndex = -1;
    let currentImageData = "";

    // โหลดข้อมูลจาก LocalStorage
    window.addEventListener("DOMContentLoaded", function() {
      const savedCategories = JSON.parse(localStorage.getItem("productCategories")) || [];
      savedCategories.forEach(category => addCategoryToTable(category.name, category.image));
    });

    // แสดงภาพเมื่ออัปโหลด
    categoryImageInput.addEventListener("change", function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          currentImageData = e.target.result;
          imagePreview.innerHTML = `<img src="${currentImageData}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
      }
    });

    // เพิ่มหรือแก้ไขหมวดหมู่
    categoryForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const name = categoryNameInput.value.trim();
      if (!name) return;

      if (editIndex === -1) {
        addCategoryToTable(name, currentImageData);
      } else {
        updateCategoryInTable(editIndex, name, currentImageData);
        editIndex = -1;
        saveCategoryBtn.textContent = "บันทึกหมวดหมู่";
      }

      saveCategoriesToLocalStorage();
      categoryForm.reset();
      imagePreview.innerHTML = "<p>ยังไม่มีรูปที่เลือก</p>";
      currentImageData = "";
    });

    function addCategoryToTable(name, image) {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${image ? `<img src="${image}" alt="${name}">` : "ไม่มีรูป"}</td>
        <td>${name}</td>
        <td>
          <button class="edit-btn" onclick="editCategory(this)">แก้ไข</button>
          <button class="delete-btn" onclick="deleteCategory(this)">ลบ</button>
        </td>
      `;
      categoryTable.appendChild(row);
    }

    function updateCategoryInTable(index, name, image) {
      const row = categoryTable.children[index];
      row.children[0].innerHTML = image ? `<img src="${image}" alt="${name}">` : "ไม่มีรูป";
      row.children[1].innerText = name;
    }

    window.editCategory = function(btn) {
      const row = btn.closest("tr");
      editIndex = Array.from(categoryTable.children).indexOf(row);
      categoryNameInput.value = row.children[1].innerText;

      const imgTag = row.children[0].querySelector("img");
      if (imgTag) {
        currentImageData = imgTag.src;
        imagePreview.innerHTML = `<img src="${currentImageData}" alt="Preview">`;
      } else {
        imagePreview.innerHTML = "<p>ยังไม่มีรูปที่เลือก</p>";
      }

      saveCategoryBtn.textContent = "บันทึกการแก้ไข";
    }

    window.deleteCategory = function(btn) {
      btn.closest("tr").remove();
      saveCategoriesToLocalStorage();
    }

    function saveCategoriesToLocalStorage() {
      const categories = [];
      categoryTable.querySelectorAll("tr").forEach(row => {
        const imgTag = row.children[0].querySelector("img");
        categories.push({
          image: imgTag ? imgTag.src : "",
          name: row.children[1].innerText
        });
      });
      localStorage.setItem("productCategories", JSON.stringify(categories));
    }
  </script>
</body>
</html>


