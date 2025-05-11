<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>NBA Players</title>
    <link rel="stylesheet" href="/fin_proj/public/css/players.css">
    <link rel="shortcut icon" href="/fin_proj/public/images/nba-logo.png" type="image/x-icon">

</head>
<body>
    <?php require_once __DIR__ . "/../layout/header/header-logged.php"?>

     <!-- LOADING SPINNER -->
     <div id="preloader">
        <div class="spinner"></div>
    </div>

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
                        Showing <?php 
                            $start = ($data['pagination']['currentPage'] - 1) * $data['pagination']['playersPerPage'] + 1; 
                            $end = min($data['pagination']['currentPage'] * $data['pagination']['playersPerPage'], $data['pagination']['totalPlayers']);
                            echo $start . ' - ' . $end; 
                        ?> of <?php echo $data['pagination']['totalPlayers']; ?> players
                    </div>
                    <div class="pagination-controls">
                        <?php if ($data['pagination']['currentPage'] > 1): ?>
                            <a href="/fin_proj/players?page=1&search=<?php echo urlencode($searchTerm); ?>&position=<?php echo urlencode($positionFilter); ?>&team=<?php echo urlencode($teamFilter); ?>" class="pagination-link">First</a>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['currentPage'] - 1; ?>&search=<?php echo urlencode($searchTerm); ?>&position=<?php echo urlencode($positionFilter); ?>&team=<?php echo urlencode($teamFilter); ?>" class="pagination-link">Previous</a>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $data['pagination']['currentPage'] - 2);
                        $endPage = min($data['pagination']['totalPages'], $data['pagination']['currentPage'] + 2);
                        
                        for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="/fin_proj/players?page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>&position=<?php echo urlencode($positionFilter); ?>&team=<?php echo urlencode($teamFilter); ?>" class="pagination-link <?php echo ($i == $data['pagination']['currentPage']) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($data['pagination']['currentPage'] < $data['pagination']['totalPages']): ?>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['currentPage'] + 1; ?>&search=<?php echo urlencode($searchTerm); ?>&position=<?php echo urlencode($positionFilter); ?>&team=<?php echo urlencode($teamFilter); ?>" class="pagination-link">Next</a>
                            <a href="/fin_proj/players?page=<?php echo $data['pagination']['totalPages']; ?>&search=<?php echo urlencode($searchTerm); ?>&position=<?php echo urlencode($positionFilter); ?>&team=<?php echo urlencode($teamFilter); ?>" class="pagination-link">Last</a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </main>
    
    <?php require_once __DIR__ . "/../layout/footer/footer-logged.php"?>
    
    <script>
    const preloader = document.getElementById("preloader");


    window.addEventListener("load", () => {
        preloader.style.opacity = "0";
        preloader.style.visibility = "hidden";
        setTimeout(() => preloader.remove(), 500);
    });

    document.addEventListener('DOMContentLoaded', async function () {
        const playersGrid = document.getElementById('players-grid');
        const playerSearch = document.getElementById('player-search');
        const positionFilter = document.getElementById('position-filter');
        const teamFilter = document.getElementById('team-filter');


        async function fetchPlayers(page = 1) {
            const searchTerm = playerSearch.value.trim();
            const position = positionFilter.value;
            const team = teamFilter.value;

            try {
                const response = await fetch(`/fin_proj/players/search?page=${page}&search=${encodeURIComponent(searchTerm)}&position=${encodeURIComponent(position)}&team=${encodeURIComponent(team)}`);
                if (response.ok) {
                    const data = await response.json();
                    renderPlayers(data.players);
                    updatePagination(data.pagination);
                } else {
                    console.error('Failed to fetch players:', response.statusText);
                }
            } catch (err) {
                console.error('Error fetching players:', err);
            }
        }


        function renderPlayers(players) {
            playersGrid.innerHTML = '';
            if (players.length === 0) {
                playersGrid.innerHTML = '<div class="no-results"><p>No players match your filters.</p></div>';
                return;
            }

            players.forEach(player => {
                const card = document.createElement('div');
                card.className = 'player-card';
                card.dataset.position = player.position.toLowerCase();
                card.dataset.team = player.teamName.toLowerCase();
                card.innerHTML = `
                    <div class="player-header">
                        <img src="${player.teamLogo}" alt="Team Logo" class="team-logo-small">
                        <span class="player-number">#${player.jersey}</span>
                    </div>
                    <div class="player-image-container">
                        <img src="${player.headshot}" alt="${player.fullName}" class="player-image" onerror="this.src='/fin_proj/public/images/player-default.png'">
                    </div>
                    <div class="player-info">
                        <h3 class="player-name">${player.fullName}</h3>
                        <p class="player-position">${player.position}</p>
                        <p class="player-team">${player.teamName}</p>
                    </div>
                `;
                playersGrid.appendChild(card);
            });
        }


        function updatePagination(pagination) {
            const paginationControls = document.querySelector('.pagination-controls');
            const paginationInfo = document.querySelector('.pagination-info');

            paginationInfo.textContent = `Showing ${pagination.start} - ${pagination.end} of ${pagination.totalPlayers} players`;

            paginationControls.innerHTML = '';

            if (pagination.currentPage > 1) {
                paginationControls.innerHTML += `
                    <a href="#" class="pagination-link" data-page="1">First</a>
                    <a href="#" class="pagination-link" data-page="${pagination.currentPage - 1}">Previous</a>
                `;
            }

            for (let i = pagination.startPage; i <= pagination.endPage; i++) {
                paginationControls.innerHTML += `
                    <a href="#" class="pagination-link ${i === pagination.currentPage ? 'active' : ''}" data-page="${i}">${i}</a>
                `;
            }

            if (pagination.currentPage < pagination.totalPages) {
                paginationControls.innerHTML += `
                    <a href="#" class="pagination-link" data-page="${pagination.currentPage + 1}">Next</a>
                    <a href="#" class="pagination-link" data-page="${pagination.totalPages}">Last</a>
                `;
            }


            document.querySelectorAll('.pagination-link').forEach(link => {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const page = parseInt(this.getAttribute('data-page'));
                    fetchPlayers(page);
                });
            });
        }


        playerSearch.addEventListener('input', () => fetchPlayers(1));
        positionFilter.addEventListener('change', () => fetchPlayers(1));
        teamFilter.addEventListener('change', () => fetchPlayers(1));


        fetchPlayers(1);
    });
    </script>

</body>
</html>