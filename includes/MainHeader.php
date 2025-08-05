
<!-- Header -->
    <header class="header">
        <div class="header-top">
            <a href="index.php" class="logo">Shoe Store</a>
            <div class="search-bar">
                <!-- 
                <input type="text" placeholder="Search for shoes...">
                <button type="submit">🔍</button>
                -->
            </div>
            <div class="user-actions">
                <a href="cart.php">
                    <img src="assets/icon/icons-cart.png" width="24px" alt="Cart" class="icon">
                </a>
                <?php
                if(!isset($_SESSION['member_id']) && isset($_SESSION['email'])){
                ?>
                <a href="login.php">
                    <img src="assets/icon/icons-person.png" width="32px"  class="icon">
                </a>
                <?php }else{ ?>
                <a href="profile.php">
                    <img src="assets/icon/icons-person.png" width="32px" alt="User" class="icon">
                </a>
                <?php  } ?>
            </div>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="products.php?category=men">ผู้ชาย</a></li>
                <li><a href="products.php?category=women">ผู้หญิง</a></li>
                <li><a href="products.php?category=extra-size">Extra Size</a></li>
                <li><a href="products.php?category=divided">Divided</a></li>
                <li><a href="products.php?category=sport">Sport</a></li>
                <li><a href="products.php?category=bag">Bag</a></li>
                <li><a href="products.php?category=shoes">Shoes</a></li>
            </ul>
        </nav>
    </header>

    <script>
        // Handle search
        document.querySelector('.search-bar button').addEventListener('click', function(e) {
            e.preventDefault();
            const searchTerm = document.querySelector('.search-bar input').value;
            if (searchTerm.trim()) {
                alert('Search for: ' + searchTerm);
            }
        });

        // Handle search on Enter key
        document.querySelector('.search-bar input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const searchTerm = this.value;
                if (searchTerm.trim()) {
                    alert('Search for: ' + searchTerm);
                }
            }
        });
    </script>