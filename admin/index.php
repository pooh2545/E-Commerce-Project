<?php
require_once '../controller/admin_auth_check.php';
redirectIfAlreadyLoggedIn();
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            padding: 50px 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(30deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(30deg);
            }
        }

        .login-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            position: relative;
            z-index: 1;
        }

        .login-subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
            z-index: 1;
        }

        .form-label {
            display: block;
            color: #555;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fafafa;
            position: relative;
        }

        .form-input:focus {
            outline: none;
            border-color: #8e44ad;
            background-color: white;
            box-shadow: 0 0 0 4px rgba(142, 68, 173, 0.1);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: #bbb;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #7d3c98, #8e44ad);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(142, 68, 173, 0.3);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .login-btn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
                margin: 10px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2 class="login-title">Admin Panel</h2>
        <p class="login-subtitle">เข้าสู่ระบบจัดการ</p>

        <form id="loginForm">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    placeholder="กรอกอีเมลของคุณ"
                    required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    placeholder="กรอกรหัสผ่านของคุณ"
                    required>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">เข้าสู่ระบบ</button>
        </form>
    </div>

    <!-- เพิ่ม notification.js -->
    <script src="../assets/js/notification.js"></script>
    
    <script>
        let hideLoadingNotification = null; // เก็บฟังก์ชันปิด loading

        // ตรวจสอบว่าเข้าสู่ระบบแล้วหรือไม่
        function checkAdminSession() {
            fetch('../controller/admin_api.php?action=check_session')
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        // ถ้าเข้าสู่ระบบแล้วให้ redirect
                        window.location.href = 'admin_dashboard.php';
                    }
                })
                .catch(err => console.log('Session check error:', err));
        }

        // ตรวจสอบ session เมื่อโหลดหน้า
        checkAdminSession();

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');

            // ตรวจสอบข้อมูลว่างเปล่า
            if (!email || !password) {
                showError('กรุณากรอกข้อมูลให้ครบถ้วน');
                return;
            }

            // ตรวจสอบรูปแบบอีเมล
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('กรุณากรอกอีเมลที่ถูกต้อง');
                return;
            }

            // แสดง loading state
            loginBtn.disabled = true;
            loginBtn.textContent = 'กำลังเข้าสู่ระบบ...';
            hideLoadingNotification = showLoading('กำลังตรวจสอบข้อมูลเข้าสู่ระบบ...');

            fetch('../controller/admin_api.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        email,
                        password
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    // ปิด loading notification
                    if (hideLoadingNotification) {
                        hideLoadingNotification();
                        hideLoadingNotification = null;
                    }

                    if (data && data.success) {
                        // แสดง success message
                        showSuccess(`เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับ: ${data.username}`, 2000);

                        // ปรับปุ่มแสดงสถานะสำเร็จ
                        loginBtn.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
                        loginBtn.textContent = 'เข้าสู่ระบบสำเร็จ!';

                        // เคลียร์ฟอร์ม
                        document.getElementById('email').value = '';
                        document.getElementById('password').value = '';

                        // ไปยังหน้า admin หลังจาก delay เล็กน้อย
                        setTimeout(() => {
                            window.location.href = data.redirect || 'admin_dashboard.php';
                        }, 1500);
                    } else {
                        // แสดง error message
                        const errorMessage = data.error || data.message || 'ข้อมูลเข้าสู่ระบบไม่ถูกต้อง';
                        showError(`เข้าสู่ระบบไม่สำเร็จ: ${errorMessage}`);
                        resetLoginButton();
                    }
                })
                .catch(err => {
                    console.error('Login error:', err);
                    
                    // ปิด loading notification
                    if (hideLoadingNotification) {
                        hideLoadingNotification();
                        hideLoadingNotification = null;
                    }

                    // แสดง error notification
                    if (err.message.includes('HTTP error')) {
                        showError('เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์ กรุณาลองใหม่อีกครั้ง');
                    } else {
                        showError('เกิดข้อผิดพลาดในการเข้าสู่ระบบ กรุณาลองใหม่อีกครั้ง');
                    }
                    
                    resetLoginButton();
                });
        });

        function resetLoginButton() {
            const loginBtn = document.getElementById('loginBtn');

            loginBtn.disabled = false;
            loginBtn.textContent = 'เข้าสู่ระบบ';
            loginBtn.style.background = 'linear-gradient(135deg, #8e44ad, #9b59b6)';
        }

        // เพิ่ม smooth animation เมื่อโหลดหน้า
        window.addEventListener('load', function() {
            const container = document.querySelector('.login-container');
            container.style.opacity = '0';
            container.style.transform = 'translateY(50px) scale(0.9)';
            container.style.transition = 'all 0.8s ease';

            setTimeout(() => {
                container.style.opacity = '1';
                container.style.transform = 'translateY(0) scale(1)';
            }, 200);
        });

        // Handle Enter key
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const loginForm = document.getElementById('loginForm');
                if (loginForm) {
                    loginForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // เพิ่ม validation แบบ real-time
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                showWarning('รูปแบบอีเมลไม่ถูกต้อง', 3000);
                this.style.borderColor = '#e74c3c';
            } else if (email) {
                this.style.borderColor = '#27ae60';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                this.style.borderColor = '#f39c12';
            } else if (this.value.length >= 6) {
                this.style.borderColor = '#27ae60';
            } else {
                this.style.borderColor = '#e0e0e0';
            }
        });

        // ป้องกัน multiple form submission
        let isSubmitting = false;
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault();
                return;
            }
            isSubmitting = true;
            
            // Reset flag หลังจาก 3 วินาที
            setTimeout(() => {
                isSubmitting = false;
            }, 3000);
        });
    </script>
</body>

</html>