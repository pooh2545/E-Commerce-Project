<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <style>
        .header_about_us {
            text-align: center;
        }

        .content {
            display: flex;         /* จัดให้องค์ประกอบภายในเรียงแนวนอน */
            align-items: center;   /* จัดให้ตรงกลางตามแนวแกนตั้ง */
            justify-content: space-between;  /* เว้นช่องว่างระหว่างสองฝั่ง */
            gap: 80px;
            padding-left: 65px;
            padding-right: 65px;
            font-weight: bold;
        }

        .pic_about_us, .details_about_us {
            flex: 1;
            font-weight: bold;
        }

        .link_contact_us {
            color: #C957BC;
            font-weight: bold;
        }


    </style>
</head>

<body>
    <div class="header_about_us">
        <h2>เกี่ยวกับเรา</h2>
    </div>

    <!-- เนื้อหา -->
    <div class="content">

    <!-- รูปภาพ -->
     <div class="pic_about_us">
      <img src="img/store01.jpg" alt="รูปเกี่ยวกับเรา" class="rounded-xl shadow-lg w-full" />
      
     </div>

     <!-- ข้อความ -->
    <div class="details_about_us">
        <div class="box1">
            <h2>ร้านรองเท้าจ้า</h2>
            <p>ร้านรองเท้าในตลาดผู้ใหญ่จิ๋ว สำหรับทุกเพศทุกวัย ที่เราคัดสรรรองเท้าคุณภาพดีในราคาสบายกระเป๋า พร้อมส่งถึงมือคุณอย่างรวดเร็ว</p>
        </div>

        <div class="box2">
            <p>เราดำเนินการโดยทีมเล็ก ๆ ที่ใส่ใจในทุกรายละเอียด ตั้งแต่การเลือกสินค้า แพ็คของ ไปจนถึงการให้บริการลูกค้า ด้วยความตั้งใจอยากให้ทุกก้าวของคุณ "สบาย...และมั่นใจ"</p>
        </div>
            

        <div class="link_contact_us">
            <a href="https://example.com" style="color: #C957BC;">ติดต่อเรา? คลิ๊กที่นี้!</a>
        </div>
    </div>
    </div>

</body>
</html>