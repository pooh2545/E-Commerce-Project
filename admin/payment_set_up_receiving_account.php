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

    let editIndex = -1;
    let currentQrData = "";

    // โหลดข้อมูลที่บันทึกไว้
    window.addEventListener("DOMContentLoaded", function() {
      const savedAccounts = JSON.parse(localStorage.getItem("storeAccounts")) || [];
      savedAccounts.forEach(account => addAccountToTable(account.bank, account.number, account.name));

      const savedQr = localStorage.getItem("storeQrCode");
      if (savedQr) {
        currentQrData = savedQr;
        qrPreview.innerHTML = `<p>QR Code ที่บันทึกไว้:</p><img src="${savedQr}" alt="QR Code">`;
      }
    });

    // เพิ่ม/แก้ไขบัญชี
    accountForm.addEventListener("submit", function(e) {
      e.preventDefault();
      const bankName = bankNameInput.value;
      const accountNumber = accountNumberInput.value;
      const accountName = accountNameInput.value;

      if (editIndex === -1) {
        addAccountToTable(bankName, accountNumber, accountName);
      } else {
        updateAccountInTable(editIndex, bankName, accountNumber, accountName);
        editIndex = -1;
        formTitle.innerText = "เพิ่มบัญชีธนาคาร";
        saveAccountBtn.innerText = "บันทึกบัญชี";
      }

      saveAccountsToLocalStorage();
      accountForm.reset();
    });

    function addAccountToTable(bank, number, name) {
      const row = document.createElement("tr");
      row.innerHTML = `
        <td>${bank}</td>
        <td>${number}</td>
        <td>${name}</td>
        <td>
          <button class="edit-btn" onclick="editAccount(this)">แก้ไข</button>
          <button class="delete-btn" onclick="deleteAccount(this)">ลบ</button>
        </td>
      `;
      accountTable.appendChild(row);
    }

    function updateAccountInTable(index, bank, number, name) {
      const row = accountTable.children[index];
      row.children[0].innerText = bank;
      row.children[1].innerText = number;
      row.children[2].innerText = name;
    }

    window.editAccount = function(btn) {
      const row = btn.closest("tr");
      editIndex = Array.from(accountTable.children).indexOf(row);

      bankNameInput.value = row.children[0].innerText;
      accountNumberInput.value = row.children[1].innerText;
      accountNameInput.value = row.children[2].innerText;

      formTitle.innerText = "แก้ไขบัญชีธนาคาร";
      saveAccountBtn.innerText = "บันทึกการแก้ไข";
    }

    window.deleteAccount = function(btn) {
      btn.closest("tr").remove();
      saveAccountsToLocalStorage();
    }

    function saveAccountsToLocalStorage() {
      const rows = accountTable.querySelectorAll("tr");
      const accounts = [];
      rows.forEach(row => {
        const cols = row.querySelectorAll("td");
        accounts.push({
          bank: cols[0].innerText,
          number: cols[1].innerText,
          name: cols[2].innerText
        });
      });
      localStorage.setItem("storeAccounts", JSON.stringify(accounts));
    }

    // แสดง QR Code
    qrUpload.addEventListener("change", function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          currentQrData = e.target.result;
          qrPreview.innerHTML = `<p>QR Code ที่อัปโหลด:</p><img src="${currentQrData}" alt="QR Code">`;
        };
        reader.readAsDataURL(file);
      }
    });

    // บันทึก QR Code
    saveQrBtn.addEventListener("click", function() {
      if (!currentQrData) {
        alert("กรุณาอัปโหลด QR Code ก่อนบันทึก");
        return;
      }
      localStorage.setItem("storeQrCode", currentQrData);
      alert("บันทึก QR Code เรียบร้อยแล้ว");
    });

    // ลบ QR Code
    deleteQrBtn.addEventListener("click", function() {
      localStorage.removeItem("storeQrCode");
      currentQrData = "";
      qrPreview.innerHTML = "<p>ยังไม่มี QR Code</p>";
    });
  </script>
</body>
</html>
