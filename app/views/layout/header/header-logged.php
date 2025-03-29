<header>
    <div class="container">
        <div class="header-content">
            <div class="logo">
                <img src="/fin_proj/public/images/nba-logo.png" alt="NBA Stats Logo" class="logo-img">
                <span class="logo-text">Stats</span>
            </div>
            
            <nav class="nav-links">
                <span class="nav-link active">Home</span>
                <span class="nav-link">Teams</span>
                <span class="nav-link">Players</span>
                <span class="nav-link">Games</span>
                <span class="nav-link">Stats</span>
            </nav>
            
            <div class="user-menu">
                <div class="avatar" id = "avatar"><?php echo htmlspecialchars(substr($data['user']['username'], 0, 2) ?? 'GU'); ?></div>
                <button class="mobile-menu-button">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="dropdown-menu hidden " id="dropdown-menu">
                    <a href="/fin_proj/logout" class="dropdown-item">Logout</a>
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
            <span class="mobile-nav-link">Home</span>
            <span class="mobile-nav-link">Teams</span>
            <span class="mobile-nav-link">Players</span>
            <span class="mobile-nav-link">Games</span>
            <span class="mobile-nav-link">Stats</span>
        </nav>
    </div>
</header>