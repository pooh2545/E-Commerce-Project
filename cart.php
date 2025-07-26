<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - Logo Store</title>
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

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .breadcrumb {
            margin-bottom: 20px;
            font-size: 14px;
            color: #666;
        }

        .breadcrumb a {
            color: #666;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #9b59b6;
        }

        .cart-header {
            font-size: 28px;
            margin-bottom: 30px;
            color: #333;
            font-weight: bold;
        }

        .cart-container {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .cart-items {
            flex: 2;
            min-width: 400px;
        }

        .cart-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .item-image {
            width: 80px;
            height: 80px;
            background-color: #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .item-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
        }

        .item-price {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-btn {
            width: 35px;
            height: 35px;
            border: none;
            background: #9b59b6;
            color: white;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s;
        }

        .qty-btn:hover {
            background: #8e44ad;
        }

        .qty-input {
            width: 50px;
            height: 35px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .item-total {
            font-size: 18px;
            font-weight: bold;
            color: #27ae60;
            margin-left: 20px;
        }

        .remove-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            margin-left: 15px;
        }

        .remove-btn svg {
            width: 24px;
            height: 24px;
            fill: #e74c3c;
        }

        .cart-summary {
            flex: 1;
            min-width: 300px;
        }

        .summary-box {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 20px;
        }

        .summary-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            color: #333;
            border-top: 2px solid #eee;
            padding-top: 15px;
            margin-top: 20px;
        }

        .discount {
            color: #27ae60;
        }

        .checkout-btn {
            width: 100%;
            background: #9b59b6;
            color: white;
            border: none;
            padding: 15px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background: #8e44ad;
        }

        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
            }
            
            .cart-item {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .quantity-controls {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/MainHeader.php"); ?>
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="#">หน้าแรก</a> > <span>ตะกร้าสินค้า</span>
        </div>

        <!-- Cart Header -->
        <h1 class="cart-header">ตะกร้าสินค้า</h1>

        <div class="cart-container">
            <!-- Cart Items -->
            <div class="cart-items">
                <!-- Item 1 -->
                <div class="cart-item">
                    <div class="item-image">
                        <span>ภาพสินค้า</span>
                    </div>
                    <div class="item-details">
                        <div class="item-name">ยี่ห้องเก่า</div>
                        <div class="item-description">รายละเอียด</div>
                        <div class="item-price">฿199</div>
                    </div>
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="decreaseQty(this)">-</button>
                        <input type="number" class="qty-input" value="2" min="1" onchange="updateTotal(this)">
                        <button class="qty-btn" onclick="increaseQty(this)">+</button>
                    </div>
                    <div class="item-total">฿398</div>
                    <button class="remove-btn" onclick="removeItem(this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                        </svg>
                    </button>
                </div>

                <!-- Item 2 -->
                <div class="cart-item">
                    <div class="item-image">
                        <span>ภาพสินค้า</span>
                    </div>
                    <div class="item-details">
                        <div class="item-name">ยี่ห้องเก่า</div>
                        <div class="item-description">รายละเอียด</div>
                        <div class="item-price">฿199</div>
                    </div>
                    <div class="quantity-controls">
                        <button class="qty-btn" onclick="decreaseQty(this)">-</button>
                        <input type="number" class="qty-input" value="2" min="1" onchange="updateTotal(this)">
                        <button class="qty-btn" onclick="increaseQty(this)">+</button>
                    </div>
                    <div class="item-total">฿398</div>
                    <button class="remove-btn" onclick="removeItem(this)">
                        <svg viewBox="0 0 24 24">
                            <path d="M19,4H15.5L14.5,3H9.5L8.5,4H5V6H19M6,19A2,2 0 0,0 8,21H16A2,2 0 0,0 18,19V7H6V19Z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="summary-box">
                    <div class="summary-title">สรุปยอด</div>
                    
                    <div class="summary-row">
                        <span>4 รายการ</span>
                        <span>฿796</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>ค่าจัดส่ง</span>
                        <span>฿40</span>
                    </div>
                    
                    <div class="summary-row discount">
                        <span>ยอดรวม</span>
                        <span>฿836</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>ชำระเงิน</span>
                        <span id="total-amount">฿836</span>
                    </div>
                    
                    <button class="checkout-btn">ชำระเงิน</button>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/MainFooter.php"); ?>
    <script>
        function increaseQty(btn) {
            const input = btn.previousElementSibling;
            const currentValue = parseInt(input.value);
            input.value = currentValue + 1;
            updateTotal(input);
        }

        function decreaseQty(btn) {
            const input = btn.nextElementSibling;
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = currentValue - 1;
                updateTotal(input);
            }
        }

        function updateTotal(input) {
            const cartItem = input.closest('.cart-item');
            const price = parseInt(cartItem.querySelector('.item-price').textContent.replace('฿', ''));
            const quantity = parseInt(input.value);
            const total = price * quantity;
            
            cartItem.querySelector('.item-total').textContent = '฿' + total;
            updateCartSummary();
        }

        function removeItem(btn) {
            if (confirm('คุณต้องการลบสินค้านี้ออกจากตะกร้าหรือไม่?')) {
                btn.closest('.cart-item').remove();
                updateCartSummary();
            }
        }

        function updateCartSummary() {
            const cartItems = document.querySelectorAll('.cart-item');
            let totalItems = 0;
            let subtotal = 0;

            cartItems.forEach(item => {
                const quantity = parseInt(item.querySelector('.qty-input').value);
                const itemTotal = parseInt(item.querySelector('.item-total').textContent.replace('฿', ''));
                
                totalItems += quantity;
                subtotal += itemTotal;
            });

            const shipping = 40;
            const total = subtotal + shipping;

            // Update summary
            const summaryRows = document.querySelectorAll('.summary-row');
            summaryRows[0].innerHTML = `<span>${totalItems} รายการ</span><span>฿${subtotal}</span>`;
            summaryRows[2].innerHTML = `<span>ยอดรวม</span><span>฿${total}</span>`;
            
            document.getElementById('total-amount').textContent = '฿' + total;
        }

        // Initialize cart summary
        updateCartSummary();
    </script>
</body>
</html>