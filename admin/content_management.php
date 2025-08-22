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

    .preview {
      text-align: center;
      margin-top: 10px;
    }

    .preview img {
      max-width: 300px;
      max-height: 200px;
      border: 1px solid #ccc;
      border-radius: 5px;
      margin-top: 10px;
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
      <button class="delete-btn" onclick="deletePage()">ลบหน้า</button>
    </div>

    <label for="page-select">เลือกหน้าเว็บไซต์</label>
    <select id="page-select"></select>

    <div class="checkbox-group">
      <label><input type="checkbox" id="toggleContent" checked> แสดงเนื้อหา</label>
      <label><input type="checkbox" id="toggleImage"> อัปโหลดรูปภาพ</label>
      <label><input type="checkbox" id="toggleCode"> แสดงโค้ด HTML</label>
    </div>

    <div id="sectionContent" class="section" style="display:block;">
      <label for="page-content">เนื้อหาข้อความ</label>
      <textarea id="page-content" placeholder="เนื้อหาข้อความ..."></textarea>
    </div>

    <div id="sectionImage" class="section">
      <label for="image-upload">รูปภาพ</label>
      <input type="file" id="image-upload" accept="image/*">
      <div class="preview" id="imagePreview"><p>ยังไม่มีรูปที่เลือก</p></div>
    </div>

    <div id="sectionCode" class="section">
      <label for="custom-html">HTML/Custom Code</label>
      <textarea id="custom-html" placeholder="HTML/Custom Code..."></textarea>
    </div>

    <button class="save-btn" id="saveBtn">บันทึกการเปลี่ยนแปลง</button>


<script>
const pageSelect=document.getElementById('page-select');
const pageContent=document.getElementById('page-content');
const customHtml=document.getElementById('custom-html');
const imageUpload=document.getElementById('image-upload');
const imagePreview=document.getElementById('imagePreview');

async function loadPages(){
  const res=await fetch("../controller/content_management_api.php?action=all");
  const data=await res.json();
  pageSelect.innerHTML='';
  data.forEach(p=>{
    const option=document.createElement('option');
    option.value=p.page_name;
    option.text=p.page_name;
    pageSelect.add(option);
  });
  if(data.length>0) loadPage();
}

async function loadPage(){
  const page_name=pageSelect.value;
  const res=await fetch(`../controller/content_management_api.php?action=getByPageName&page_name=${encodeURIComponent(page_name)}`);
  const data=await res.json();

  pageContent.value=data.content||'';
  customHtml.value=data.custom_code||'';

  if(data.url_path){
    imagePreview.innerHTML=`<img src="${data.url_path}" />`;
  }else{
    imagePreview.innerHTML='<p>ยังไม่มีรูปที่เลือก</p>';
  }
  imageUpload.value='';
}

function addPage(){
  const name=prompt("กรุณาใส่ชื่อหน้าที่ต้องการเพิ่ม:");
  if(name){
    const option=document.createElement("option");
    option.value=name;
    option.text=name;
    pageSelect.add(option);
    pageSelect.value=name;
    pageContent.value='';
    customHtml.value='';
    imageUpload.value='';
    imagePreview.innerHTML='<p>ยังไม่มีรูปที่เลือก</p>';
  }
}

async function deletePage(){
  const page_name=pageSelect.value;
  if(!page_name) return alert("กรุณาเลือกหน้าก่อนลบ");
  if(!confirm(`คุณต้องการลบหน้าที่ชื่อ "${page_name}" หรือไม่?`)) return;

  const res=await fetch(`../controller/content_management_api.php?action=delete`,{
    method:'DELETE',
    headers:{'Content-Type':'application/json'},
    body:JSON.stringify({page_name})
  });
  const result=await res.json();
  if(result.success){ alert("ลบสำเร็จ"); loadPages(); }
  else alert("ลบไม่สำเร็จ");
}

async function savePage(){
  const page_name=pageSelect.value;
  const content=pageContent.value;
  const custom_code=customHtml.value;
  const file=imageUpload.files[0];

  const formData=new FormData();
  formData.append('page_name',page_name);
  formData.append('content',content);
  formData.append('custom_code',custom_code);
  if(file) formData.append('url_path',file);

  const res=await fetch('../controller/content_management_api.php?action=create',{method:'POST',body:formData});
  const result=await res.json();
  if(result.success){ alert("บันทึกสำเร็จ"); loadPages(); }
  else { alert("บันทึกไม่สำเร็จ"); console.log(result); }
}

document.getElementById('saveBtn').addEventListener('click',savePage);

document.getElementById('toggleContent').addEventListener('change',e=>{document.getElementById('sectionContent').style.display=e.target.checked?'block':'none';});
document.getElementById('toggleImage').addEventListener('change',e=>{document.getElementById('sectionImage').style.display=e.target.checked?'block':'none';});
document.getElementById('toggleCode').addEventListener('change',e=>{document.getElementById('sectionCode').style.display=e.target.checked?'block':'none';});

imageUpload.addEventListener('change',()=>{
  const file=imageUpload.files[0];
  if(file){
    const reader=new FileReader();
    reader.onload=e=>{imagePreview.innerHTML=`<img src="${e.target.result}" />`;};
    reader.readAsDataURL(file);
  }else imagePreview.innerHTML='<p>ยังไม่มีรูปที่เลือก</p>';
});

pageSelect.addEventListener('change',loadPage);
loadPages();
</script>


</div>
</body>
</html>
