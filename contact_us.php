<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Contact Us</title>
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

    .header_contact_us {
        text-align: center;
    }

    .content {
        display: flex;
        align-items: center;
        /* จัดให้ตรงกลางตามแนวแกนตั้ง */
        justify-content: space-between;
        /* เว้นช่องว่างระหว่างสองฝั่ง */
        padding-left: 65px;
        padding-right: 65px;
    }

    .box1 {
        display: flex;
        flex-direction: column;
        flex: 1;
        padding-left: 65px;


    }

    .box2 {
        flex: 1;
    }


    .qr-code {
        width: 280px;
        height: 280px;
    }

    .map-container {
        width: 100%;
        margin-top: 20px;
    }

    iframe {
        width: 100%;
        height: 450px;
        border: 0;
    }

    .address {
        text-align: center;
        padding: 65px;
        /*padding-left: 65px;
            padding-right: 65px;
            padding-bottom: 65px;*/
    }

    p {
        margin-top: 0px;
        margin-bottom: 0px;
        font-weight: bold;
    }

    .adress-detail {
        padding-bottom: 30px;
    }
    </style>

</head>


<body>
    <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

    <div class="header_contact_us">
        <h2>ติดต่อเรา</h2>
    </div>


    <div class="content">

        <!-- ข้อความและคิวอา -->
        <div class="box1">
            <p>โทรศัพท์: 082-532-8622</p>
            <p>Facebook: Narerat Jatayavorn</p>
            <p>อีเมล: koednanare@gmail.com</p>
            <p>Line:</p>
            <div class="qr-code">
                <img src="qr_code_line.jpg" alt="qr_code_line" style="width: 100%;">
            </div>
        </div>

        <!-- รูปภาพ -->
        <div class="box2">
            <img src="store02.jpg" alt="store02" style="width: 100%;">
        </div>

    </div>

    <!-- ข้อความและแมพ -->
    <div class="address">
        <div class="adress-detail">
            <p>หรือมาที่ตั้งร้าน บ้านเลขที่61/68 ตลาดผู้ใหญ่จิ๋ว ม.1 </p>
            <p>ต.กะแดะ อ.กาญจนดิษฐ์ จ.สุราษฎร์ธานี 84160</p>
        </div>

        <div class="map-container">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d370.79651281779604!2d99.47906941582927!3d9.164089553591127!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sth!4v1753857039504!5m2!1sen!2sth"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>




    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

</body>

</html>