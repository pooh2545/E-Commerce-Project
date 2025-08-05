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

        .loading {
            display: none;
            text-align: center;
            color: #8e44ad;
            margin-top: 10px;
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

            <!-- Message Display -->
            <div id="messageDiv" style="display: none;"></div>

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
                <div class="loading" id="loading">Logging in...</div>
            </form>
            
            <div class="divider">or sign in with</div>
            
            <div class="social-login">
                <a href="#" class="social-btn google-btn">
                    <span style="color: #4285f4;">G</span>
                    <span>Sign in with Google</span>
                </a>
                <a href="#" class="social-btn facebook-btn">
                    <span style="color: white;">f</span>
                    <span>Sign in with Facebook</span>
                </a>
            </div>
            
            <div class="signup-link">
                Don't have an account? <a href="signup.php">Sign up here</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>
    <!---->

    <script>
        function showMessage(message, type = 'error') {
            const messageDiv = document.getElementById('messageDiv');
            messageDiv.className = type === 'error' ? 'error-message' : 'success-message';
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const loginBtn = document.getElementById('loginBtn');
            const loading = document.getElementById('loading');
            
            // Basic validation
            if (!email || !password) {
                showMessage('Please fill in all fields');
                return;
            }
            
            // Show loading state
            loginBtn.disabled = true;
            loading.style.display = 'block';
            
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
                if (data.success) {
                    showMessage(data.message, 'success');
                    // Redirect after short delay
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                } else {
                    showMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.');
            })
            .finally(() => {
                loginBtn.disabled = false;
                loading.style.display = 'none';
            });
        });

        // Handle social login buttons
        document.querySelector('.google-btn').addEventListener('click', function(e) {
            e.preventDefault();
            showMessage('Google login feature coming soon!', 'success');
        });

        document.querySelector('.facebook-btn').addEventListener('click', function(e) {
            e.preventDefault();
            showMessage('Facebook login feature coming soon!', 'success');
        });

        // Handle search
        document.querySelector('.search-bar button').addEventListener('click', function(e) {
            e.preventDefault();
            const searchTerm = document.querySelector('.search-bar input').value.trim();
            if (searchTerm) {
                showMessage(`Search functionality coming soon! You searched for: ${searchTerm}`, 'success');
            }
        });

        // Handle search on Enter key
        document.querySelector('.search-bar input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value.trim();
                if (searchTerm) {
                    showMessage(`Search functionality coming soon! You searched for: ${searchTerm}`, 'success');
                }
            }
        });

        
    </script>
</body>
</html>