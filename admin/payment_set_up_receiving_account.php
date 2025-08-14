<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>การตั้งค่าการชำระเงิน</title>
  <style>
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
      /*color: #fff;*/
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
      width: 60%;
      margin: 30px auto;
      padding: 30px;
      border-radius: 10px;
    }
    h1, h2 {
      text-align: left;
      color: #333;
    }
    form {
      margin-top: 20px;
    }
    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
      font-size: 16px;
      background-color: #fff;
    }
    button {
      padding: 8px 12px;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 14px;
      cursor: pointer;
    }
    button:hover {
      opacity: 0.9;
    }
    #saveAccountBtn {
      background-color: #28a745;
      width: 100%;
      font-size: 16px;
      padding: 12px;
      margin-top: 10px;
    }
    .delete-btn {
      background-color: #dc3545;
      margin-left: 5px;
    }
    .delete-btn:hover {
      background-color: #c82333;
    }
    .edit-btn {
      background-color: #0D6EFD;
    }
    .edit-btn:hover {
      background-color: #0b5ed7;
    }
    .account-list, .qr-section {
      margin-top: 30px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: center;
    }
    th {
      background-color: #C957BC;
      color: #fff;
    }

    tr {
      background-color: #fff;
    }


    .qr-preview {
      text-align: center;
      margin-top: 10px;
    }
    .qr-preview img {
      max-width: 200px;
      margin-top: 10px;
      border: 1px solid #ccc;
      padding: 5px;
    }
    .qr-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
    }
    .save-qr-btn {
      background-color: #28a745;
      width: 49%;
    }
    .delete-qr-btn {
      background-color: #dc3545;
      width: 49%;
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
    <h1>การตั้งค่าการชำระเงิน</h1>

    <!-- ฟอร์มเพิ่ม/แก้ไขบัญชี -->
    <form id="accountForm">
      <h2 id="formTitle">เพิ่มบัญชีธนาคาร</h2>
      <label for="bankName">ธนาคาร</label>
      <select id="bankName" required>
        <option value="">เลือกธนาคาร</option>
        <option>กสิกรไทย</option>
        <option>ไทยพาณิชย์</option>
        <option>กรุงเทพ</option>
        <option>กรุงไทย</option>
        <option>ทหารไทยธนชาต</option>
      </select>

      <label for="accountNumber">เลขที่บัญชี</label>
      <input type="text" id="accountNumber" placeholder="เช่น 123-4-56789-0" required>

      <label for="accountName">ชื่อบัญชี</label>
      <input type="text" id="accountName" placeholder="ชื่อ-นามสกุลเจ้าของบัญชี" required>

      <button type="submit" id="saveAccountBtn">บันทึกบัญชี</button>
    </form>

    <!-- ตารางบัญชี -->
    <div class="account-list">
      <h2>บัญชีธนาคารที่ตั้งค่าไว้</h2>
      <table>
        <thead>
          <tr>
            <th>ธนาคาร</th>
            <th>เลขที่บัญชี</th>
            <th>ชื่อบัญชี</th>
            <th>จัดการ</th>
          </tr>
        </thead>
        <tbody id="accountTable">
          <!-- บัญชีจะถูกเพิ่มที่นี่ -->
        </tbody>
      </table>
    </div>

    <!-- QR Code -->
    <div class="qr-section">
      <h2>เพิ่ม QR Code สำหรับบัญชีร้านค้า</h2>
      <input type="file" id="qrUpload" accept="image/*">
      <div class="qr-preview" id="qrPreview"></div>
      <div class="qr-buttons">
        <button class="save-qr-btn" id="saveQrBtn">บันทึก QR Code</button>
        <button class="delete-qr-btn" id="deleteQrBtn">ลบ QR Code</button>
      </div>
    </div>
  </div>

 
<script>
const API_URL = "../controller/payment_method_api.php";

const accountForm = document.getElementById("accountForm");
const accountTable = document.getElementById("accountTable");
const bankNameInput = document.getElementById("bankName");
const accountNumberInput = document.getElementById("accountNumber");
const accountNameInput = document.getElementById("accountName");
const formTitle = document.getElementById("formTitle");
const saveAccountBtn = document.getElementById("saveAccountBtn");

const qrUpload = document.getElementById("qrUpload");
const qrPreview = document.getElementById("qrPreview");
const saveQrBtn = document.getElementById("saveQrBtn");
const deleteQrBtn = document.getElementById("deleteQrBtn");

let editId = null;

// โหลดบัญชีทั้งหมด
async function loadAccounts() {
  accountTable.innerHTML = "";
  const res = await fetch(`${API_URL}?action=all`);
  const accounts = await res.json();
  accounts.forEach(acc => addAccountToTable(acc.payment_method_id, acc.bank, acc.account_number, acc.name, acc.qr_path));
}

// เพิ่มแถวตาราง
function addAccountToTable(id, bank, number, name) {
  const row = document.createElement("tr");
  row.innerHTML = `
    <td>${bank}</td>
    <td>${number}</td>
    <td>${name}</td>
    <td>
      <button class="edit-btn" onclick="editAccount('${id}', this)">แก้ไข</button>
      <button class="delete-btn" onclick="deleteAccount('${id}')">ลบ</button>
    </td>
  `;
  accountTable.appendChild(row);
}

// ฟอร์ม submit เพิ่ม/แก้ไข
accountForm.addEventListener("submit", async function(e){
  e.preventDefault();
  const formData = new FormData();
  formData.append("bank", bankNameInput.value);
  formData.append("account_number", accountNumberInput.value);
  formData.append("name", accountNameInput.value);
  if(qrUpload.files[0]) formData.append("qr_image", qrUpload.files[0]);

  let url = editId ? `${API_URL}?action=update&id=${editId}` : `${API_URL}?action=create`;
  const res = await fetch(url, { method: "POST", body: formData });
  const result = await res.json();

  if(result.success){
    alert(editId ? "แก้ไขสำเร็จ" : "เพิ่มสำเร็จ");
    resetForm();
    loadAccounts();
  } else {
    alert("เกิดข้อผิดพลาด");
  }
});

// แก้ไขบัญชี
window.editAccount = function(id, btn){
  const row = btn.closest("tr");
  bankNameInput.value = row.children[0].innerText;
  accountNumberInput.value = row.children[1].innerText;
  accountNameInput.value = row.children[2].innerText;

  editId = id;
  formTitle.innerText = "แก้ไขบัญชีธนาคาร";
  saveAccountBtn.innerText = "บันทึกการแก้ไข";
};

// ลบบัญชี
window.deleteAccount = async function(id){
  if(!confirm("คุณแน่ใจว่าต้องการลบ?")) return;
  const res = await fetch(`${API_URL}?action=delete&id=${id}`, { method: "DELETE" });
  const result = await res.json();
  if(result.success){
    alert("ลบสำเร็จ");
    loadAccounts();
  } else alert("ลบไม่สำเร็จ");
};

// QR Preview
qrUpload.addEventListener("change", ()=>{
  const file = qrUpload.files[0];
  if(file){
    const reader = new FileReader();
    reader.onload = e => qrPreview.innerHTML = `<img src="${e.target.result}">`;
    reader.readAsDataURL(file);
  } else qrPreview.innerHTML = `<p>ยังไม่มี QR Code</p>`;
});

// บันทึก QR
saveQrBtn.addEventListener("click", async ()=>{
  if(!qrUpload.files[0]) { alert("เลือก QR Code ก่อน"); return; }
  const formData = new FormData();
  formData.append("qr_image", qrUpload.files[0]);
  let url = editId ? `${API_URL}?action=update&id=${editId}` : `${API_URL}?action=create`;
  const res = await fetch(url, { method: "POST", body: formData });
  const result = await res.json();
  if(result.success){ alert("บันทึก QR Code สำเร็จ"); loadAccounts(); }
});

// ลบ QR
deleteQrBtn.addEventListener("click", async ()=>{
  if(!editId){ alert("เลือกบัญชีก่อน"); return; }
  if(!confirm("คุณต้องการลบ QR Code นี้?")) return;
  const res = await fetch(`${API_URL}?action=delete_qr&id=${editId}`, { method: "POST" });
  const result = await res.json();
  if(result.success){ alert("ลบ QR Code สำเร็จ"); qrPreview.innerHTML = `<p>ยังไม่มี QR Code</p>`; loadAccounts(); }
});

function resetForm(){
  accountForm.reset();
  qrPreview.innerHTML = `<p>ยังไม่มี QR Code</p>`;
  editId = null;
  formTitle.innerText = "เพิ่มบัญชีธนาคาร";
  saveAccountBtn.innerText = "บันทึกบัญชี";
}

// โหลดข้อมูลเมื่อเปิดหน้า
window.addEventListener("DOMContentLoaded", loadAccounts);
</script>
</body>
</html>
