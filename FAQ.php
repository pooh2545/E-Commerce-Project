<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>คำถามที่พบบ่อย</title>
    <link rel="icon" type="image/x-icon" href="assets/images/Logo.png">
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

    ol,
    ul {
        margin-left: 20px;
        padding-left: 0;
        font-size: 16px;
        color: #333;
    }

    ul {
        list-style-type: disc;
        margin-left: 20px;
        /* เพิ่ม margin เพื่อขยับจุด bullet */
        color: #555;
    }

    a {
        color: #752092;
        /* สีน้ำเงิน */
        text-decoration: none;
        /* ไม่ขีดเส้นใต้ */
    }

    a:hover {
        text-decoration: underline;
        /* ขีดเส้นใต้เมื่อเอาเมาส์ชี้ */
        color: #C957BC;
        /* สีฟ้าเข้มขึ้นเมื่อ hover */
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php include("includes/MainHeader.php"); ?>

    <div class="container">
        <h1>คำถามที่พบบ่อย (FAQ)</h1>
        <ol>
            <li>
                สินค้าใช้เวลาจัดส่งกี่วัน?
                <ul>
                    <li>สินค้าจะถูกจัดส่งภายใน 1–3 วันทำการ หลังจากทางร้านได้รับยอดชำระเงิน</li>
                    <li>ระยะเวลาการจัดส่งขึ้นอยู่กับพื้นที่ โดยทั่วไปจะใช้เวลา ประมาณ 1–5 วันทำการ</li>
                </ul>
            </li>
            <li>
                ชำระเงินได้ผ่านช่องทางใดบ้าง?
                <ul>
                    <li>โอนผ่านบัญชีธนาคาร</li>
                    <li>ชำระผ่าน QR Code (แสกนจากแอปธนาคาร)</li>
                </ul>
            </li>
            <li>
                สามารถยกเลิกคำสั่งซื้อได้หรือไม่?
                <ul>
                    <li>คำสั่งซื้อที่ยัง ไม่ได้ชำระเงิน จะถูกยกเลิกโดยอัตโนมัติเมื่อหมดเวลาชำระ</li>
                    <li>หากชำระเงินแล้ว ไม่สามารถยกเลิกคำสั่งซื้อได้ทุกกรณี</li>
                </ul>
            </li>
            <li>
                สามารถคืนสินค้าหรือขอเงินคืนได้หรือไม่?
                <ul>
                    <li>ไม่มีนโยบายคืนสินค้า หรือคืนเงิน กรุณาตรวจสอบรายการสินค้าให้แน่ใจก่อนชำระเงิน</li>
                </ul>
            </li>
            <li>
                หากต้องการสอบถามเพิ่มเติม ติดต่อได้ทางไหน?
                <ul>
                    <li><a href="contact_us.php">สามารถดูข้อมูลติดต่อได้ที่หน้า ติดต่อเรา คลิ๊ก!</a></li>
                </ul>
            </li>
        </ol>
    </div>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

</body>

</html>