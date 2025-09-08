<?php
require_once 'controller/auth_check.php';
redirectIfLoggedIn(); // จะ redirect ไป index.php ถ้า login แล้ว
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Store - Login</title>
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

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-title {
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

        .forgot-password {
            text-align: right;
            margin-top: 5px;
        }

        .forgot-password a {
            color: #8e44ad;
            text-decoration: none;
            font-size: 12px;
        }

        .login-btn {
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

        .login-btn:hover {
            background: #7d3c98;
        }

        .login-btn:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
        }

        .divider {
            text-align: center;
            margin: 20px 0;
            color: #666;
            font-size: 14px;
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .signup-link a {
            color: #8e44ad;
            text-decoration: none;
            font-weight: bold;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include("includes/MainHeader.php"); ?>
    
    <!-- Main Content -->
    <main class="main-content">
        <div class="login-container">
            <h2 class="login-title">LOGIN</h2>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <div class="forgot-password">
                        <a href="#">Forgot password?</a>
                    </div>
                </div>
                <button type="submit" class="login-btn" id="loginBtn">LOGIN</button>
            </form>
            
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

    <!-- Include notification.js -->
    <script src="assets/js/notification.js"></script>
    
    <script>
        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            
            // Basic validation
            if (!email || !password) {
                showError('กรุณากรอกข้อมูลให้ครบถ้วน');
                return;
            }
            
            // Show loading state
            loginBtn.disabled = true;
            loginBtn.textContent = 'กำลังเข้าสู่ระบบ...';
            const closeLoading = showLoading('กำลังเข้าสู่ระบบ...');
            
            // Send login request
            fetch('controller/auth.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=login&email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
            })
            .then(response => response.json())
            .then(data => {
                closeLoading(); // ปิด loading notification
                
                if (data.success) {
                    showSuccess(data.message || 'เข้าสู่ระบบสำเร็จ');
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 1000);
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาดในการเข้าสู่ระบบ');
                }
            })
            .catch(error => {
                closeLoading(); // ปิด loading notification
                console.error('Error:', error);
                showError('เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง');
            })
            .finally(() => {
                loginBtn.disabled = false;
                loginBtn.textContent = 'LOGIN';
            });
        });

        // Handle search (if exists in header)
        const searchButton = document.querySelector('.search-bar button');
        const searchInput = document.querySelector('.search-bar input');
        
        if (searchButton) {
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();
                if (searchTerm) {
                    showInfo(`คุณค้นหา: ${searchTerm} (ฟีเจอร์ค้นหากำลังพัฒนา)`);
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const searchTerm = this.value.trim();
                    if (searchTerm) {
                        showInfo(`คุณค้นหา: ${searchTerm} (ฟีเจอร์ค้นหากำลังพัฒนา)`);
                    }
                }
            });
        }
    </script>
</body>
</html>