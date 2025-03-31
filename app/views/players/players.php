<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>NBA Players</title>
    <link rel="stylesheet" href="/fin_proj/public/css/players.css">
</head>
<body>
    <?php require_once __DIR__ . "/../layout/header/header-logged.php"?>
    
    <main class="main-content">
        <div class="container">
            <h1 class="page-title">NBA Players</h1>
            
            <div class="filters">
                <div class="search-container">
                    <input type="text" id="player-search" placeholder="Search players...">
                </div>
                <div class="filter-container">
                    <select id="position-filter">
                        <option value="all">All Positions</option>
                        <option value="G">Guards</option>
                        <option value="F">Forwards</option>
                        <option value="C">Centers</option>
                    </select>
                    <select id="team-filter">
                        <option value="all">All Teams</option>
                        <?php 
                        $teams = [];
                        foreach ($data['players'] as $player) {
                            if (!isset($teams[$player['teamId']])) {
                                $teams[$player['teamId']] = $player['teamName'];
                            }
                        }
                        asort($teams);
                        foreach ($teams as $teamId => $teamName) {
                            echo '<option value="' . htmlspecialchars($teamId) . '">' . htmlspecialchars($teamName) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <?php if (empty($data['players'])): ?>
                <div class="no-players">
                    <p>No players found.</p>
                </div>
            <?php else: ?>
                <div class="players-grid" id="players-grid">
                    <?php foreach ($data['players'] as $player): ?>
                        <?php 
                            $position = $player['position'] ?? 'Unknown';
                            $positionClass = '';
                            if (strpos($position, 'G') !== false) {
                                $positionClass = 'G';
                            } else if (strpos($position, 'F') !== false) {
                                $positionClass = 'F';
                            } else if (strpos($position, 'C') !== false) {
                                $positionClass = 'C';
                            }
                        ?>
                        <div class="player-card" data-position="<?php echo htmlspecialchars($positionClass); ?>" data-team="<?php echo htmlspecialchars($player['teamId']); ?>">
                            <div class="player-header">
                                <img src="<?php echo htmlspecialchars($player['teamLogo'] ?? '/fin_proj/public/images/team-default.png'); ?>" alt="Team Logo" class="team-logo-small">
                                <span class="player-number">#<?php echo htmlspecialchars($player['jersey'] ?? 'N/A'); ?></span>
                            </div>
                            <div class="player-image-container">
                                <img src="<?php echo htmlspecialchars($player['headshot'] ?? '/fin_proj/public/images/player-default.png'); ?>" alt="<?php echo htmlspecialchars($player['fullName'] ?? 'Player'); ?>" class="player-image" onerror="this.src='/fin_proj/public/images/player-default.png'">
                            </div>
                            <div class="player-info">
                                <h3 class="player-name"><?php echo htmlspecialchars($player['fullName'] ?? 'Unknown Player'); ?></h3>
                                <p class="player-position"><?php echo htmlspecialchars($player['position'] ?? 'Unknown'); ?></p>
                                <p class="player-team"><?php echo htmlspecialchars($player['teamName'] ?? 'Unknown Team'); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <div class="pagination">
                    <div class="pagination-info">
                        Showing <?php echo ($data['pagination']['currentPage'] - 1) * $data['pagination']['playersPerPage'] + 1; ?> - 
                        <?php echo min($data['pagination']['currentPage'] * $data['pagination']['playersPerPage'], $data['pagination']['totalPlayers']); ?> 
                        of <?php echo $data['pagination']['totalPlayers']; ?> players
                    </div>
                    <div class="pagination-controls">
                        <?php if ($data['pagination']['currentPage'] > 1): ?>
                            <a href="/fin_proj/players?page=1" class="pagination-link">First</a>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['currentPage'] - 1; ?>" class="pagination-link">Previous</a>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $data['pagination']['currentPage'] - 2);
                        $endPage = min($data['pagination']['totalPages'], $data['pagination']['currentPage'] + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="/fin_proj/players?page=<?php echo $i; ?>" class="pagination-link <?php echo ($i == $data['pagination']['currentPage']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['currentPage'] + 1; ?>" class="pagination-link">Next</a>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['totalPages']; ?>" class="pagination-link">Last</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php require_once __DIR__ . "/../layout/footer/footer-logged.php"?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const playerSearch = document.getElementById('player-search');
            const positionFilter = document.getElementById('position-filter');
            const teamFilter = document.getElementById('team-filter');
            const playerCards = document.querySelectorAll('.player-card');
            
            function filterPlayers() {
                const searchTerm = playerSearch.value.toLowerCase();
                const positionValue = positionFilter.value;
                const teamValue = teamFilter.value;
                
                playerCards.forEach(card => {
                    const playerName = card.querySelector('.player-name').textContent.toLowerCase();
                    const playerPosition = card.dataset.position;
                    const playerTeam = card.dataset.team;
                    
                    const matchesSearch = playerName.includes(searchTerm);
                    const matchesPosition = positionValue === 'all' || playerPosition === positionValue;
                    const matchesTeam = teamValue === 'all' || playerTeam === teamValue;
                    
                    if (matchesSearch && matchesPosition && matchesTeam) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                const visiblePlayers = document.querySelectorAll('.player-card[style="display: block;"]');
                const playersGrid = document.getElementById('players-grid');
                
                if (visiblePlayers.length === 0) {
                    if (!document.querySelector('.no-results')) {
                        const noResults = document.createElement('div');
                        noResults.className = 'no-results';
                        noResults.innerHTML = '<p>No players match your filters.</p>';
                        playersGrid.appendChild(noResults);
                    }
                } else {
                    const noResults = document.querySelector('.no-results');
                    if (noResults) {
                        noResults.remove();
                    }
                }
            }
            
            playerSearch.addEventListener('input', filterPlayers);
            positionFilter.addEventListener('change', filterPlayers);
            teamFilter.addEventListener('change', filterPlayers);
            
            const favoritePlayerBtns = document.querySelectorAll('.favorite-player-btn');
            favoritePlayerBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const playerId = this.getAttribute('data-player-id');
                    toggleFavoritePlayer(playerId, this);
                });
            });
            
            function toggleFavoritePlayer(playerId, button) {
                fetch('/fin_proj/player/toggle-favorite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `player_id=${playerId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            button.classList.add('favorited');
                            button.querySelector('span').textContent = 'Unlike';
                        } else {
                            button.classList.remove('favorited');
                            button.querySelector('span').textContent = 'Like';
                        }
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating favorites');
                });
            }
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            const closeMenuButton = document.querySelector('.close-menu-button');
            const mobileMenu = document.querySelector('.mobile-menu');


            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.add('active');
                mobileMenu.classList.remove('hidden');
            });


            closeMenuButton.addEventListener('click', () => {
                mobileMenu.classList.remove('active');
                mobileMenu.classList.add('hidden');
            });

            const avatar = document.getElementById('avatar');
            const dropdownMenu = document.getElementById('dropdown-menu');

            avatar.addEventListener('click', () => {
                dropdownMenu.classList.toggle('active');
            });

            document.addEventListener('click', (event) => {
                if (!avatar.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>