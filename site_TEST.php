<?php
require_once __DIR__ . '/controller/config.php';
require_once __DIR__ . '/controller/SiteContentController.php';

$controller = new SiteContentController($pdo);
$pageData = $controller->getByPageName('หน้าทดลอง');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageData['page_name']) ?></title>

        <link href="assets/css/header.css" rel="stylesheet">
        <link href="assets/css/footer.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
    }

        .container {
            width: 80%;
            margin: 30px auto;
            background: #f7f7f7;
            padding: 30px;
            border-radius: 10px;
            line-height: 1.8;
    }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        /* Layout หลัก (ตัวที่ทำ sticky footer)*/
        html, body {
            height: 100%;
            display: flex;
            flex-direction: column;
        }


    </style>
</head>


<body>

       <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

 <div class="container">
    <h1><?= htmlspecialchars($pageData['page_name']) ?></h1>


    <!-- แสดงเนื้อหา -->
    <div>
        <?= nl2br(htmlspecialchars($pageData['content'])) ?>
    </div>


    <div class="url_path">
        <img src="store02.jpg" alt="store02" style="width: 100%;">
        <td>${item.url_path ? `<img src="../controller/uploads/${item.url_path}" alt="">` : "ไม่มีรูป"}</td>
    </div>

    <div class="custom_code">
    <?php if (!empty($pageData['custom_code'])): ?>
        <?= $pageData['custom_code'] ?>
    <?php endif; ?>
    </div>    

 </div>

        <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>


<script>
    const imagePreview = document.getElementById("imagePreview");

    // โหลดข้อมูลจาก API
    async function loadCategories() {
    const res = await fetch("../controller/site_content_api.php?action=all");
    const data = await res.json();
    categoryTable.innerHTML = "";
    data.forEach((item, index) => {
        const tr = document.createElement("tr");
        tr.dataset.id = item.content_id;
        tr.innerHTML = `
            <td>${item.url_path ? `<img src="../controller/uploads/${item.url_path}" alt="">` : "ไม่มีรูป"}</td>
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

    loadCategories();

</script>

    
</body>
</html>
