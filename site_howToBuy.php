<?php
require_once __DIR__ . '/controller/config.php';
require_once __DIR__ . '/controller/SiteContentController.php';

$controller = new SiteContentController($pdo);
$pageData = $controller->getByPageName('วิธีการสั่งซื้อ');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageData['page_name']) ?></title>
    <link rel="icon" type="image/x-icon" href="assets/images/Logo.png">
        <link href="assets/css/header.css" rel="stylesheet">
        <link href="assets/css/footer.css" rel="stylesheet">

<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scrollbar-gutter: stable;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }

        .add-to-cart-btn.loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px;
        }

        .pic {
            text-align: center;

        }

        .pic img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto; /* จัดกลาง */
            padding-top: 20px;
            padding-bottom: 20px;
        }


        h1 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }

        .text {
             line-height: 1.6; 
        }


    </style>
</head>


<body>

       <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

    
<div class="container">
    <h1><?= htmlspecialchars($pageData['page_name']) ?></h1>

    <!-- แสดงเนื้อหา -->
    <div class="text">
        <?= nl2br(htmlspecialchars($pageData['content'])) ?>
    </div>


    <!-- ถ้ามีรูป ให้แสดง -->
<div class="pic">
    <?php if (!empty($pageData['url_path'])): ?>
        <img src="<?= htmlspecialchars($pageData['url_path']) ?>" 
             alt="<?= htmlspecialchars($pageData['page_name']) ?>">
    <?php endif; ?>
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
