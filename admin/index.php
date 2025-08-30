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

        .message {
            margin-bottom: 20px;
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loading {
            display: none;
            margin-top: 15px;
            color: #8e44ad;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        .loading::after {
            content: '';
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 2px solid #8e44ad;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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

        <!-- Message Display -->
        <div id="messageDiv" style="display: none;"></div>

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
            <div class="loading" id="loading">กำลังเข้าสู่ระบบ...</div>
        </form>
    </div>

    <script>
        // ตรวจสอบว่าเข้าสู่ระบบแล้วหรือไม่
        function checkAdminSession() {
            fetch('../controller/admin_api.php?action=check_session')
                .then(response => response.json())
                .then(data => {
                    if (data.logged_in) {
                        // ถ้าเข้าสู่ระบบแล้วให้ redirect
                        window.location.href = 'productmanage.php';
                    }
                })
                .catch(err => console.log('Session check error:', err));
        }

        // ตรวจสอบ session เมื่อโหลดหน้า
        checkAdminSession();

        function showMessage(message, type = 'error') {
            const messageDiv = document.getElementById('messageDiv');
            messageDiv.className = 'message ' + (type === 'error' ? 'error-message' : 'success-message');
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';

            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');

            if (!email || !password) {
                showMessage('กรุณากรอกข้อมูลให้ครบถ้วน');
                return;
            }

            // แสดง loading state
            loginBtn.disabled = true;
            loginBtn.textContent = 'กำลังเข้าสู่ระบบ...';
            loading.style.display = 'block';

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
                .then(response => response.json())
                .then(data => {
                    if (data && data.success) {
                        showMessage('เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับ: ' + data.username, 'success');

                        // ปรับปุ่งและ animation
                        loginBtn.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
                        loginBtn.textContent = 'เข้าสู่ระบบสำเร็จ!';

                        // ไปยังหน้า admin
                        setTimeout(() => {
                            window.location.href = data.redirect || 'admin_dashboard.php';
                        }, 1500);
                    } else {
                        showMessage('เข้าสู่ระบบไม่สำเร็จ: ' + (data.error || data.message || 'ข้อมูลไม่ถูกต้อง'));
                        resetLoginButton();
                    }
                })
                .catch(err => {
                    console.error('Login error:', err);
                    showMessage('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
                    resetLoginButton();
                });
        });

        function resetLoginButton() {
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');

            loginBtn.disabled = false;
            loginBtn.textContent = 'เข้าสู่ระบบ';
            loginBtn.style.background = 'linear-gradient(135deg, #8e44ad, #9b59b6)';
            loading.style.display = 'none';
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
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
</body>

</html>