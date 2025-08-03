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
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            color: #666;
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .form-input:focus {
            outline: none;
            border-color: #8e44ad;
            background-color: white;
            box-shadow: 0 0 0 3px rgba(142, 68, 173, 0.1);
        }

        .form-input::placeholder {
            color: #aaa;
            font-style: italic;
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #8e44ad, #9b59b6);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #7d3c98, #8e44ad);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .login-title {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="login-title">Admin LOGIN</h2>
        
        <form id="loginForm">
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-input" 
                    placeholder="Enter your email"
                    required
                >
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Enter your password"
                    required
                >
            </div>
            
            <button type="submit" class="login-btn">LOGIN</button>
        </form>
    </div>

    <script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        
        if (email && password) {
            fetch('../controller/admin_api.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email, password })
            })
            .then(response => response.json())
            .then(data => {
                if (data && !data.error) {
                    alert('เข้าสู่ระบบสำเร็จ! ยินดีต้อนรับ: ' + data.username);

                    // ปรับปุ่มและ animation
                    const button = document.querySelector('.login-btn');
                    button.style.background = 'linear-gradient(135deg, #27ae60, #2ecc71)';
                    button.textContent = 'เข้าสู่ระบบสำเร็จ!';

                    // ไปยังหน้า admin หรือหน้าอื่น
                    setTimeout(() => {
                        window.location.href = 'productmanage.php'; // <-- เปลี่ยนตาม path จริง
                    }, 1500);
                } else {
                    alert('เข้าสู่ระบบไม่สำเร็จ: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error('Login error:', err);
                alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
            });
        }
    });

    // เพิ่ม smooth animation เมื่อโหลดหน้า
    window.addEventListener('load', function() {
        const container = document.querySelector('.login-container');
        container.style.opacity = '0';
        container.style.transform = 'translateY(30px)';
        container.style.transition = 'all 0.6s ease';
        
        setTimeout(() => {
            container.style.opacity = '1';
            container.style.transform = 'translateY(0)';
        }, 100);
    });
</script>

</body>
</html>