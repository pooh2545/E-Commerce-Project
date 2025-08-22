<?php
require_once '../config.php';
require_once '../controllers/SiteContentController.php';

$controller = new SiteContentController($pdo);
$pageData = $controller->getByPageName('faq');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageData['page_name']) ?></title>
</head>
<body>
    <h1><?= htmlspecialchars($pageData['page_name']) ?></h1>
    <div>
        <?= $pageData['content'] ?>
    </div>

    <?php if (!empty($pageData['custom_code'])): ?>
        <!-- รัน custom HTML/CSS/JS -->
        <?= $pageData['custom_code'] ?>
    <?php endif; ?>
</body>
</html>
