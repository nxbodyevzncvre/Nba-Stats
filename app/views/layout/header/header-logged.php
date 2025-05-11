<header>
    <div class="container">
        <div class="header-content">
            <a href="/fin_proj/home" style="text-decoration:none;">
                <div class="logo">
                    <img src="/fin_proj/public/images/nba-logo.png" alt="NBA Stats Logo" class="logo-img">
                    <span class="logo-text">Stats</span>
                </div>
            </a>
            
            <nav class="nav-links">
                <a href="/fin_proj/home" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/home') !== false ? 'active' : ''; ?>">Home</a>
                <a href="/fin_proj/teams" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/teams') !== false ? 'active' : ''; ?>">Teams</a>
                <a href="/fin_proj/players" class="<?php echo strpos($_SERVER['REQUEST_URI'], '/players') !== false ? 'active' : ''; ?>">Players</a>
            </nav>
            
            <div class="user-menu">
                <div class="avatar" id = "avatar"><?php echo htmlspecialchars(substr($data['user']['username'], 0, 2) ?? 'GU'); ?></div>
                <button class="mobile-menu-button">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="dropdown-menu hidden " id="dropdown-menu">
                    <a href="/fin_proj/favorites/teams" class="dropdown-item">Favorite Teams</a>
                    <a href="/fin_proj/logout" class="dropdown-item">Logout</a>
                    <?php if (isset($_SESSION['user_id']) && $this->userModel->isAdmin($_SESSION['user_id'])): ?>
                        <a href="/fin_proj/admin" class="dropdown-item">Admin Panel</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


    <div class="mobile-menu hidden">
        <div class="mobile-menu-header">
            <span class="mobile-menu-title">Menu</span>
            <button class="close-menu-button">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <nav class="mobile-nav-links">
            <a href="/fin_proj/home" class="mobile-nav-link">Home</ф>
            <a href="/fin_proj/teams" class="mobile-nav-link">Teams</a>
            <a href="/fin_proj/players" class="mobile-nav-link">Players</a>
        </nav>
    </div>
</header>