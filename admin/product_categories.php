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

// โหลดข้อมูลจาก API
async function loadCategories() {
    const res = await fetch("../controller/shoetype_api.php?action=all");
    const data = await res.json();
    categoryTable.innerHTML = "";
    data.forEach((item, index) => {
        const tr = document.createElement("tr");
        tr.dataset.id = item.shoetype_id;
        tr.innerHTML = `
            <td>${item.images ? `<img src="../controller/uploads/${item.images}" alt="">` : "ไม่มีรูป"}</td>
            <td>${item.name}</td>
            <td>
                <button type="button" class="edit-btn" onclick="editCategory(this)">แก้ไข</button>
                <button type="button" class="delete-btn" onclick="deleteCategory(this)">ลบ</button>
            </td>
        `;
        categoryTable.appendChild(tr);
    });
}

// แสดงรูป preview
categoryImageInput.addEventListener("change", () => {
    const file = categoryImageInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.innerHTML = `<p>ยังไม่มีรูปที่เลือก</p>`;
    }
});

// submit form
categoryForm.addEventListener("submit", async e => {
    e.preventDefault();

    const name = categoryNameInput.value.trim();
    if (!name) {
        alert("กรุณากรอกชื่อหมวดหมู่");
        return;
    }

    const imageFile = categoryImageInput.files[0];
    const formData = new FormData();
    formData.append("name", name);
    if (imageFile) formData.append("image", imageFile);

    let url;
    if (editIndex === -1) {
        url = "../controller/shoetype_api.php?action=create";
    } else {
        const id = categoryTable.children[editIndex].dataset.id;
        url = `../controller/shoetype_api.php?action=update&id=${id}`;
    }

    const res = await fetch(url, { method: "POST", body: formData });
    const result = await res.json();

    if (result.success) {
        alert(editIndex === -1 ? "เพิ่มหมวดหมู่สำเร็จ" : "แก้ไขหมวดหมู่สำเร็จ");
        await loadCategories();
        resetForm();
    } else {
        alert("เกิดข้อผิดพลาด");
    }
});

// ฟังก์ชันแก้ไข
window.editCategory = btn => {
    const row = btn.closest("tr");
    editIndex = Array.from(categoryTable.children).indexOf(row);
    categoryNameInput.value = row.children[1].innerText;

    const img = row.children[0].querySelector("img");
    if (img) {
        imagePreview.innerHTML = `<img src="${img.src}" alt="Preview">`;
    } else {
        imagePreview.innerHTML = `<p>ยังไม่มีรูปที่เลือก</p>`;
    }

    saveCategoryBtn.textContent = "บันทึกการแก้ไข";
};

// ฟังก์ชันลบ
window.deleteCategory = async btn => {
    if (!confirm("คุณต้องการลบหมวดหมู่นี้ใช่หรือไม่?")) return;

    const row = btn.closest("tr");
    const id = row.dataset.id;

    const res = await fetch(`../controller/shoetype_api.php?action=delete&id=${id}`, { method: "DELETE" });
    const result = await res.json();

    if (result.success) {
        alert("ลบสำเร็จ");
        row.remove();
        resetForm();
    } else {
        alert("ลบไม่สำเร็จ");
    }
};

// รีเซ็ตฟอร์ม
function resetForm() {
    categoryForm.reset();
    editIndex = -1;
    imagePreview.innerHTML = `<p>ยังไม่มีรูปที่เลือก</p>`;
    saveCategoryBtn.textContent = "บันทึกหมวดหมู่";
}

loadCategories();
</script>

  

  
</body>
</html>


