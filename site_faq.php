<?php
require_once __DIR__ . '/controller/config.php';
require_once __DIR__ . '/controller/SiteContentController.php';

$controller = new SiteContentController($pdo);
$pageData = $controller->getByPageName('คำถามที่พบบ่อย (FAQ)');
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

        .pic {
            text-align: center;
        }
        
        img {
            margin:20px 0;
            max-width:600px; 
            height:auto;
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


    <!-- ถ้ามีรูป ให้แสดง -->
<div class="pic">
 <?php $imagePath = $pageData['url_path']; ?>
    <img src="<?= htmlspecialchars($imagePath) ?>" 
         alt="<?= htmlspecialchars($pageData['page_name']) ?>">
</div>


    <!-- ถ้ามี custom code -->
    <div class="custom-code">
        <?= $pageData['custom_code'] ?>
    </div>
</div>


        <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

</body>
</html>
