<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน - Logo Store</title>
    <link href="assets/css/header.css" rel="stylesheet">
    <link href="assets/css/footer.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;

        }
        
        .payment-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .payment-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .payment-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border: 2px solid #e9ecef;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            color: #666;
        }

        .info-value {
            font-weight: 500;
            color: #333;
        }

        .bank-info {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .bank-label {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
            font-weight: 500;
        }

        .qr-info {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .qr-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .qr-label {
            font-size: 16px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 500;
        }

        .qr-code {
            width: 200px;
            height: 200px;
            background: #e9ecef;
            border-radius: 10px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 14px;
            border: 2px solid #dee2e6;
        }

        .upload-section {
            margin-bottom: 30px;
        }

        .upload-label {
            display: block;
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            font-weight: 500;
        }

        .file-upload {
            width: 100%;
            padding: 12px;
            border: 2px dashed #9b59b6;
            border-radius: 8px;
            background: #f8f3ff;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .file-upload:hover {
            background: #f0e6ff;
            border-color: #8e44ad;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .upload-text {
            color: #9b59b6;
            font-size: 14px;
            pointer-events: none;
        }

        .file-selected {
            color: #27ae60;
            font-weight: 500;
        }

        .submit-btn {
            width: 100%;
            background: #9b59b6;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background: #8e44ad;
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .note {
            font-size: 12px;
            color: #666;
            text-align: center;
            margin-top: 15px;
            line-height: 1.4;
        }

        @media (max-width: 600px) {
            .payment-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .payment-title {
                font-size: 24px;
            }

            .qr-code {
                width: 180px;
                height: 180px;
            }
        }

        /* Loading animation */
        .loading {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="payment-container">
        <h1 class="payment-title">ชำระเงิน</h1>
        
        <div class="payment-info">
            <div class="info-item">
                <span class="info-label">หมายเลขคำสั่งซื้อ:</span>
                <span class="info-value">#ORD202507260001</span>
            </div>
            <div class="info-item">
                <span class="info-label">จำนวนสินค้า:</span>
                <span class="info-value">4 รายการ</span>
            </div>
            <div class="info-item">
                <span class="info-label">ยอดรวม:</span>
                <span class="info-value">฿836</span>
            </div>
            <!--
            <div class="info-item">
                <span class="info-label">วิธีการชำระเงิน:</span>
                <span class="info-value">โอนเงินผ่านธนาคาร</span>
            </div>
            -->
        </div>

        <div class="bank-info">
            <div class="bank-label">โอนเงินไปที่บัญชี</div>
            <div class="info-item">
                    <div class="info-label">ธนาคาร: กรุงไทย</div>
            </div>
            <div class="info-item">
                    <div class="info-label">ชื่อบัญชี: Narerat Jattayaworn</div>
            </div>
            <div class="info-item">
                    <div class="info-label">เลขที่บัญชี: xxxxxxxxxxxxx</div>
            </div>
        </div>

        <div class="qr-info">
            <div class="bank-label" style="margin-bottom: 10px;">หรือสแกนคิวอาร์โค้ด: </div>
            <div class="qr-code">
                <div>
                    <div>QR Code</div>
                    <div style="font-size: 12px; margin-top: 5px;">สำหรับชำระเงิน</div>
                </div>
            </div>
        </div>


        <form id="paymentForm">
            <div class="upload-section">
                <label class="upload-label">อัปโหลดหลักฐานการชำระเงิน</label>
                <div class="file-upload">
                    <input type="file" id="paymentSlip" accept="image/*" required>
                    <div class="upload-text" id="uploadText">
                        คลิกเพื่อเลือกไฟล์หรือลากไฟล์มาวางที่นี่
                        <br><small>(รองรับไฟล์ .jpg, .png, .pdf)</small>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                <div class="btn-content">
                    <div class="loading" id="loading"></div>
                    <span id="btnText">ยืนยันการชำระเงิน</span>
                </div>
            </button>
        </form>

        <div class="note">
            * กรุณาอัปโหลดหลักฐานการโอนเงินเพื่อยืนยันการชำระเงิน<br>
            * ระบบจะตรวจสอบการชำระเงินภายใน 24 ชั่วโมง
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script>
        const fileInput = document.getElementById('paymentSlip');
        const uploadText = document.getElementById('uploadText');
        const paymentForm = document.getElementById('paymentForm');
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        const btnText = document.getElementById('btnText');

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2); // MB
                uploadText.innerHTML = `
                    <div class="file-selected">
                        ✓ ไฟล์ที่เลือก: ${fileName}
                        <br><small>ขนาด: ${fileSize} MB</small>
                    </div>
                `;
            }
        });

        // Handle drag and drop
        const fileUpload = document.querySelector('.file-upload');

        fileUpload.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = '#f0e6ff';
            this.style.borderColor = '#8e44ad';
        });

        fileUpload.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.background = '#f8f3ff';
            this.style.borderColor = '#9b59b6';
        });

        fileUpload.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '#f8f3ff';
            this.style.borderColor = '#9b59b6';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                const event = new Event('change', { bubbles: true });
                fileInput.dispatchEvent(event);
            }
        });

        // Handle form submission
        paymentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!fileInput.files[0]) {
                alert('กรุณาเลือกไฟล์หลักฐานการชำระเงิน');
                return;
            }

            // Show loading
            loading.style.display = 'block';
            btnText.textContent = 'กำลังประมวลผล...';
            submitBtn.disabled = true;

            // Simulate upload process
            setTimeout(() => {
                alert('ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว!\nระบบจะตรวจสอบและแจ้งผลภายใน 24 ชั่วโมง');
                
                // Reset form
                loading.style.display = 'none';
                btnText.textContent = 'ยืนยันการชำระเงิน';
                submitBtn.disabled = false;
                
                // You can redirect to success page here
                // window.location.href = '/payment-success';
            }, 2000);
        });

        // Validate file type on selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    alert('กรุณาเลือกไฟล์ประเภท JPG, PNG หรือ PDF เท่านั้น');
                    this.value = '';
                    uploadText.innerHTML = `
                        คลิกเพื่อเลือกไฟล์หรือลากไฟล์มาวางที่นี่
                        <br><small>(รองรับไฟล์ .jpg, .png, .pdf)</small>
                    `;
                    return;
                }

                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('ขนาดไฟล์ต้องไม่เกิน 5 MB');
                    this.value = '';
                    uploadText.innerHTML = `
                        คลิกเพื่อเลือกไฟล์หรือลากไฟล์มาวางที่นี่
                        <br><small>(รองรับไฟล์ .jpg, .png, .pdf)</small>
                    `;
                    return;
                }
            }
        });
    </script>
</body>
</html>