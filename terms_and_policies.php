<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>เงื่อนไขและนโยบาย</title>

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
    ol, ul {
      margin-left: 20px;
      padding-left: 0;  
      font-size: 16px;
      color: #333;
    }
    ul {
      list-style-type: disc;
      margin-left: 20px; /* เพิ่ม margin เพื่อขยับจุด bullet */
      color: #555;
    }

  </style>
</head>
<body>

       <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

  <div class="container">
    <h1>เงื่อนไขและนโยบาย</h1>
    <ol>
      <li>
        การใช้งานเว็บไซต์
        <ul>
          <li>ผู้ใช้ต้องใช้เว็บไซต์อย่างสุจริต ไม่กระทำการใด ๆ ที่ผิดกฎหมาย หรือสร้างความเสียหายแก่เว็บไซต์</li>
          <li>ห้ามคัดลอก ดัดแปลง หรือนำเนื้อหาภายในเว็บไซต์ไปใช้โดยไม่ได้รับอนุญาต</li>
        </ul>
      </li>
      <li>
        การสั่งซื้อสินค้า
        <ul>
          <li>ลูกค้าต้องกรอกข้อมูลที่เป็นความจริงและครบถ้วน</li>
          <li>เมื่อทำการสั่งซื้อและชำระเงินแล้ว ถือว่าเป็นการยืนยันการซื้ออย่างสมบูรณ์</li>
        </ul>
      </li>
      <li>
        การชำระเงิน
        <ul>
          <li>รองรับการชำระผ่านโอนเงินธนาคาร / QR Code</li>
          <li>หลังการชำระเงิน ลูกค้าต้องแนบใบเสร็จการชำระเงินด้วย</li>
        </ul>
      </li>
      <li>
        การยกเลิกคำสั่งซื้อ
        <ul>
          <li>ลูกค้าสามารถกดยกเลิกคำสั่งซื้อได้ภายใน 15 นาที หากยังไม่ได้ชำระเงิน</li>
          <li>ไม่มีการยกเลิกคำสั่งซื้อหากมีการชำระเงินแล้ว</li>
        </ul>
      </li>
      <li>
        การจัดส่งสินค้า
        <ul>
          <li>ร้านจะจัดส่งภายใน [1-3 วันทำการ] หลังจากได้รับการชำระเงิน</li>
          <li>การจัดส่งมีค่าใช้จ่ายเพิ่มเติม</li>
        </ul>
      </li>
      <li>
        การรับคืนสินค้า
        <ul>
          <li>ทางร้านไม่มีนโยบายรับคืนสินค้า ไม่ว่ากรณีใด ๆ กรุณาตรวจสอบรายการสินค้าให้เรียบร้อยก่อนทำการชำระเงิน</li>
        </ul>
      </li>
      <li>
        นโยบายความเป็นส่วนตัว
        <ul>
          <li>ข้อมูลของลูกค้าจะถูกเก็บไว้อย่างปลอดภัย และใช้เพื่อวัตถุประสงค์ภายในร้านเท่านั้น</li>
          <li>จะไม่เผยแพร่ข้อมูลส่วนบุคคลแก่บุคคลภายนอกโดยไม่ได้รับอนุญาต</li>
        </ul>
      </li>
      <li>
        การเปลี่ยนแปลงนโยบาย
        <ul>
          <li>ร้านขอสงวนสิทธิ์ในการเปลี่ยนแปลงเงื่อนไขและนโยบาย โดยไม่ต้องแจ้งให้ทราบล่วงหน้า</li>
          <li>กรุณาตรวจสอบหน้าเงื่อนไขและนโยบายทุกครั้งก่อนสั่งซื้อ</li>
        </ul>
      </li>
    </ol>
  </div>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

</body>
</html>
