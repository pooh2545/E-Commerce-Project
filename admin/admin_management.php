<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: none;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 5px;
        }

        .page-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            transition: all 0.3s;
        }

        .btn-primary {
            background-color: #7B3F98;
            color: white;
        }

        .btn-primary:hover {
            background-color: #6A2C87;
        }

        
        .btn-success {
            background-color: #28a745;
            color: white;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-info {
            background-color: #007bff;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            font-size: 12px;
            padding: 5px 10px;
        }

        .product-table {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background-color: #C957BC;
            color: white;
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }

        tr:hover {
            background-color: #f8f9fa;
        }


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
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">จัดการผู้ดูแลระบบ</h1>
            <button class="btn btn-primary" onclick="showAddForm()" style="margin-top: 20px;">+ เพิ่มผู้ดูแล</button>

        </div>

        <div class="product-table">
            
            <table>

                <thead>
                    <tr>
                        <th>ชื่อผู้ดูแล</th>
                        <th>อีเมล</th>
                        <th>สิทธิการเข้าถึง</th>
                        <th>การจัดการ</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    <tr id="row1">
                        <td><span class="text">สมชาย แอดมิน</td>
                        <td><span class="text">admin1@example.com</td>
                        <td>
                            <select>
                                <option>Admin</option>
                                <option>Employee</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info" onclick="editProduct('row1', this)">แก้ไข</button>
                            <button class="btn btn-danger" onclick="deleteProduct('row1')">ลบ</button>
                        </td>
                    </tr>

                    <tr id="row2">
                        <td><span class="text">กิตติ แอดมิน</td>
                        <td><span class="text">admin2@example.com</td>
                        <td>
                            <select>
                                <option>Admin</option>
                                <option>Employee</option>
                            </select>
                        </td>
                        <td>
                            <button class="btn btn-info" onclick="editProduct('row2', this)">แก้ไข</button>
                            <button class="btn btn-danger" onclick="deleteProduct('row2')">ลบ</button>
                        </td>
                    </tr>

                </tbody>
            </table>


        </div>



        

    </div>


  <script>
    function deleteProduct(rowId) {
      const confirmDelete = confirm("คุณแน่ใจหรือไม่ว่าต้องการลบรายการนี้?");
      if (confirmDelete) {
        const row = document.getElementById(rowId);
        row.remove();
      }
    }

    function editProduct(rowId, btn) {
      const row = document.getElementById(rowId);
      const tds = row.querySelectorAll("td");

      if (btn.innerText === "แก้ไข") {
        // เปลี่ยนเป็น input
        const name = tds[0].querySelector(".text").innerText;
        const email = tds[1].querySelector(".text").innerText;

        tds[0].innerHTML = `<input type="text" value="${name}">`;
        tds[1].innerHTML = `<input type="email" value="${email}">`;

        btn.innerText = "บันทึก";
        btn.classList.remove("btn-info");
        btn.classList.add("btn-success");
      } else {
        // บันทึก
        const newName = tds[0].querySelector("input").value;
        const newEmail = tds[1].querySelector("input").value;

        tds[0].innerHTML = `<span class="text">${newName}</span>`;
        tds[1].innerHTML = `<span class="text">${newEmail}</span>`;

        btn.innerText = "แก้ไข";
        btn.classList.remove("btn-success");
        btn.classList.add("btn-info");
      }
    }
  </script>
</body>

</html>