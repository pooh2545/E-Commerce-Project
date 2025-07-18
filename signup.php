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
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        /* Message styles */
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 14px;
            display: none;
        }

        .error-message {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .success-message {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
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

            <!-- Message containers -->
            <div id="errorMessage" class="message error-message"></div>
            <div id="successMessage" class="message success-message"></div>

            <form id="signupForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    <div class="password-requirements">
                        <div class="requirement" id="length">• At least 8 characters</div>
                        <div class="requirement" id="uppercase">• One uppercase letter</div>
                        <div class="requirement" id="lowercase">• One lowercase letter</div>
                        <div class="requirement" id="number">• One number</div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                </div>
                <button type="submit" class="signup-btn">SIGN UP</button>
            </form>

            <div class="divider">or</div>

            <div class="social-login">
                <a href="#" class="social-btn google-btn">
                    <span style="color: #4285f4;">G</span>
                    <span>Sign up with Google</span>
                </a>
                <a href="#" class="social-btn facebook-btn">
                    <span style="color: white;">f</span>
                    <span>Sign up with Facebook</span>
                </a>
            </div>

            <div class="login-link">
                Already a user? <a href="login.php">Login here</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include("includes/MainFooter.php"); ?>

    <script>
        // JavaScript สำหรับ signup.php (แทนที่ส่วนเก่าทั้งหมด)

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

        // Show/hide messages
        function showMessage(type, message) {
            const errorDiv = document.getElementById('errorMessage');
            const successDiv = document.getElementById('successMessage');

            // Hide both messages first
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';

            if (type === 'error') {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            } else if (type === 'success') {
                successDiv.textContent = message;
                successDiv.style.display = 'block';
            }
        }

        // Password input event listener
        document.getElementById('password').addEventListener('input', function() {
            checkPasswordStrength(this.value);
        });

        // Form validation and submission
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const email = document.getElementById('email').value.trim();
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Hide any previous messages
            showMessage('', '');

            // Basic validation
            if (!email || !username || !password || !confirmPassword) {
                showMessage('error', 'Please fill in all fields');
                return;
            }

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage('error', 'Please enter a valid email address');
                return;
            }

            // Username validation
            if (username.length < 3) {
                showMessage('error', 'Username must be at least 3 characters long');
                return;
            }

            // Password strength validation
            if (!checkPasswordStrength(password)) {
                showMessage('error', 'Password must meet all requirements');
                return;
            }

            // Password confirmation
            if (password !== confirmPassword) {
                showMessage('error', 'Passwords do not match');
                return;
            }

            // Disable submit button to prevent double submission
            const submitBtn = document.querySelector('.signup-btn');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.textContent = 'SIGNING UP...';

            // Send data to auth.php
            const formData = new FormData();
            formData.append('action', 'signup');
            formData.append('email', email);
            formData.append('username', username);
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
                    if (data.success) {
                        showMessage('success', data.message);
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
                        showMessage('error', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('error', 'An error occurred during sign up. Please try again.');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        });

        // Handle social signup buttons
        document.querySelector('.google-btn').addEventListener('click', function(e) {
            e.preventDefault();
            showMessage('error', 'Google sign up is not implemented yet');
        });

        document.querySelector('.facebook-btn').addEventListener('click', function(e) {
            e.preventDefault();
            showMessage('error', 'Facebook sign up is not implemented yet');
        });
    </script>
</body>

</html>