<?php
session_start();

if (isset($_SESSION['member_id']) && isset($_SESSION['email']) && isset($_SESSION['login_time'])) {
    // ตรวจสอบว่า session ยังไม่หมดอายุ (ถ้ามีการกำหนด timeout)
    $session_timeout = 3600; // 1 ชั่วโมง
    if (time() - $_SESSION['login_time'] < $session_timeout) {
        header('Location: index.php');
        exit();
    } else {
        // session หมดอายุ ให้ลบ session
        session_destroy();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Sign Up</title>
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

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .signup-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .signup-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            border-color: #8e44ad;
        }

        .signup-btn {
            width: 100%;
            background: #8e44ad;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background 0.3s;
        }

        .signup-btn:hover {
            background: #7d3c98;
        }

        .signup-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 14px;
        }

        .social-login {
            margin-bottom: 15px;
        }

        .social-btn {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background: white;
            cursor: pointer;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: background 0.3s;
            text-decoration: none;
            color: #333;
        }

        .social-btn:hover {
            background: #f8f9fa;
        }

        .google-btn {
            margin-bottom: 10px;
        }

        .facebook-btn {
            background: #4267B2;
            color: white;
            border-color: #4267B2;
        }

        .facebook-btn:hover {
            background: #365899;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link a {
            color: #8e44ad;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .signup-container {
                padding: 30px 20px;
            }
        }

        /* Password strength indicator */
        .password-strength {
            margin-top: 5px;
            height: 3px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: width 0.3s, background-color 0.3s;
        }

        .strength-weak {
            background: #e74c3c;
        }

        .strength-medium {
            background: #f39c12;
        }

        .strength-strong {
            background: #27ae60;
        }

        .password-requirements {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .requirement {
            margin: 2px 0;
        }

        .requirement.met {
            color: #27ae60;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <?php include("includes/MainHeader.php"); ?>

    <!-- Main Content -->
    <main class="main-content">
        <div class="signup-container">
            <h2 class="signup-title">SIGN UP</h2>

            <form id="signupForm">
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" placeholder="Enter your firstname" >
                </div>
                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" placeholder="Enter your lastname" >
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" >
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="Enter your phone number" maxlength="10" >
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" >
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="length">• อย่างน้อย 8 ตัวอักษร</div>
                        <div class="requirement" id="uppercase">• ตัวพิมพ์ใหญ่ 1 ตัว</div>
                        <div class="requirement" id="lowercase">• ตัวพิมพ์เล็ก 1 ตัว</div>
                        <div class="requirement" id="number">• ตัวเลข 1 ตัว</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" >
                </div>
                <button type="submit" class="signup-btn">SIGN UP</button>
            </form>

            <div class="login-link">
                Already a user? <a href="login.php">Login here</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

    <!-- Include notification.js -->
    <script src="assets/js/notification.js"></script>
    
    <script>
        // Password strength checker
        function checkPasswordStrength(password) {
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password)
            };

            // Update requirement indicators
            Object.keys(requirements).forEach(req => {
                const element = document.getElementById(req);
                if (requirements[req]) {
                    element.classList.add('met');
                } else {
                    element.classList.remove('met');
                }
            });

            // Calculate strength
            const metRequirements = Object.values(requirements).filter(Boolean).length;
            const strengthBar = document.getElementById('strengthBar');

            if (metRequirements === 0) {
                strengthBar.style.width = '0%';
                strengthBar.className = 'password-strength-bar';
            } else if (metRequirements <= 2) {
                strengthBar.style.width = '33%';
                strengthBar.className = 'password-strength-bar strength-weak';
            } else if (metRequirements === 3) {
                strengthBar.style.width = '66%';
                strengthBar.className = 'password-strength-bar strength-medium';
            } else {
                strengthBar.style.width = '100%';
                strengthBar.className = 'password-strength-bar strength-strong';
            }

            return metRequirements === 4;
        }

        // Validate phone number (Thai format)
        function validateThaiPhone(phone) {
            const phoneRegex = /^[0-9]{10}$/;
            return phoneRegex.test(phone) && (phone.startsWith('08') || phone.startsWith('09') || phone.startsWith('06') || phone.startsWith('02'));
        }

        // Password input event listener
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });

        // Phone input validation
        document.getElementById('phone').addEventListener('input', function() {
            // Allow only numbers
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Form validation and submission
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const firstname = document.getElementById('firstname').value.trim();
            const lastname = document.getElementById('lastname').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Basic validation
            if (!email || !firstname || !lastname || !phone || !password || !confirmPassword) {
                showError('กรุณากรอกข้อมูลให้ครบถ้วน');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('กรุณากรอกอีเมลให้ถูกต้อง');
                return;
            }

            // Phone validation
            if (!validateThaiPhone(phone)) {
                showError('กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง (10 หลัก)');
                return;
            }

            // Password strength validation
            if (!checkPasswordStrength(password)) {
                showError('รหัสผ่านต้องตรงตามเงื่อนไขทั้งหมด');
                return;
            }

            // Password confirmation
            if (password !== confirmPassword) {
                showError('รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน');
                return;
            }

            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.signup-btn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'กำลังสมัครสมาชิก...';

            // Show loading
            const closeLoading = showLoading('กำลังสมัครสมาชิก...');

            // Send data to auth.php
            const formData = new FormData();
            formData.append('action', 'signup');
            formData.append('email', email);
            formData.append('firstname', firstname);
            formData.append('lastname', lastname);
            formData.append('phone', phone);
            formData.append('password', password);
            formData.append('confirmPassword', confirmPassword);

            fetch('controller/auth.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    closeLoading(); // ปิด loading notification
                    
                    if (data.success) {
                        showSuccess(data.message || 'สมัครสมาชิกสำเร็จ');
                        
                        // Clear form
                        document.getElementById('signupForm').reset();
                        
                        // Reset password strength indicator
                        document.getElementById('strengthBar').style.width = '0%';
                        document.getElementById('strengthBar').className = 'password-strength-bar';
                        
                        // Reset requirements
                        ['length', 'uppercase', 'lowercase', 'number'].forEach(req => {
                            document.getElementById(req).classList.remove('met');
                        });

                        // Redirect after delay
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    } else {
                        showError(data.message || 'เกิดข้อผิดพลาดในการสมัครสมาชิก');
                    }
                })
                .catch(error => {
                    closeLoading(); // ปิด loading notification
                    console.error('Error:', error);
                    showError('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });
    </script>
</body>

</html>