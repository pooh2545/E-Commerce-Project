
<!-- Header -->
    <header class="header">
        <div class="header-top">
            <div class="logo">Shoe Store</div>
            <div class="search-bar">
                <input type="text" placeholder="Search for shoes...">
                <button type="submit">🔍</button>
            </div>
            <div class="user-actions">
                <a href="#">👤</a>
                <a href="#">👥</a>
            </div>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="#">ผู้ชาย</a></li>
                <li><a href="#">ผู้หญิง</a></li>
                <li><a href="#">Extra Size</a></li>
                <li><a href="#">Divided</a></li>
                <li><a href="#">Sport</a></li>
                <li><a href="#">Bag</a></li>
                <li><a href="#">Shoes</a></li>
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