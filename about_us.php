<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
        <link href="assets/css/header.css" rel="stylesheet">
        <link href="assets/css/footer.css" rel="stylesheet">
        <link rel="icon" type="image/x-icon" href="assets/images/Logo.png">
    <style>




        .content {
            display: flex;         /* จัดให้องค์ประกอบภายในเรียงแนวนอน */
            align-items: center;   /* จัดให้ตรงกลางตามแนวแกนตั้ง */
            justify-content: space-between;  /* เว้นช่องว่างระหว่างสองฝั่ง */
            gap: 80px;
            padding-left: 65px;
            padding-right: 65px;
            font-weight: bold;
        }

        .details_about_us {
            flex: 1;
            font-weight: bold;
            margin-right: 80px;
        }

        .pic_about_us {
            flex: 1;
            
        }

        .link_contact_us {
            color: #C957BC;
            font-weight: bold;
        }

        .box2 {
            margin-top: 55px;
            margin-bottom: 55px;
        }


    </style>
</head>

<body>
       <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>


    <!-- เนื้อหา -->
    <div class="content">

    <!-- รูปภาพ -->
     <div class="pic_about_us">
      <img src="store01.jpg" alt="คำอธิบาย" style="width: 100%;">
     </div>

     <!-- ข้อความ -->
    <div class="details_about_us">
        <div class="box1">
            <h2 style="margin-bottom: 0px;">ร้านรองเท้าจ้า</h2>
            <p style="margin-top: 0px;">ร้านรองเท้าในตลาดผู้ใหญ่จิ๋ว สำหรับทุกเพศทุกวัย ที่เราคัดสรรรองเท้าคุณภาพดีในราคาสบายกระเป๋า พร้อมส่งถึงมือคุณอย่างรวดเร็ว</p>
        </div>

        <div class="box2">
            <p>เราดำเนินการโดยทีมเล็ก ๆ ที่ใส่ใจในทุกรายละเอียด ตั้งแต่การเลือกสินค้า แพ็คของ ไปจนถึงการให้บริการลูกค้า ด้วยความตั้งใจอยากให้ทุกก้าวของคุณ "สบาย...และมั่นใจ"</p>
        </div>
            

        <div class="link_contact_us">
            <a href="site_contactUs.php" style="color: #C957BC;">ติดต่อเรา? คลิ๊กที่นี้!</a>
        </div>
    </div>
    </div>
    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>
</body>
</html>