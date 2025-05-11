<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title><?php echo htmlspecialchars($data['team']['name']); ?> - Team Page</title>
    <link rel="stylesheet" href="/fin_proj/public/css/team.css">
    <link rel="shortcut icon" href="/fin_proj/public/images/nba-logo.png" type="image/x-icon">

</head>
<body>
    <?php require_once  __DIR__ . "/../layout/header/header-logged.php"?>
    <div class="team-header">
        <img src="<?php echo htmlspecialchars($data['team']['stadium']['image'] ?? '../../../public/images/arena.jpg'); ?>" alt="Team Arena" class="header-bg">
        <div class="team-info-container">
            <div class="container">
                <div class="team-info">
                    <img src="<?php echo htmlspecialchars($data['team']['logo']); ?>" alt="<?php echo htmlspecialchars($data['team']['name']); ?> Logo" class="team-logo">
                    <div class="team-details">
                        <h1 class="team-name"><?php echo htmlspecialchars($data['team']['name']); ?></h1>
                        <div class="team-conference">
                            <span><?php echo htmlspecialchars($data['team']['conference']); ?> Conference</span>
                            <span class="dot"></span>
                            <span><?php echo htmlspecialchars($data['team']['division']); ?> Division</span>
                        </div>
                        <?php if ($data['user']['isLoggedIn']): ?>
                        <div class="team-actions">
                            <button class="favorite-team-btn <?php echo $data['isFavorite'] ? 'favorited' : ''; ?>" data-team-id="<?php echo htmlspecialchars($data['team']['id']); ?>">
                                <span><?php echo $data['isFavorite'] ? 'Remove from Favorites' : 'Add to Favorites'; ?></span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="main-content">
        <div class="container">
            <!-- Team Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-label">Season Record</p>
                    <p class="stat-value"><?php echo htmlspecialchars($data['team']['record'] ?? '0-0'); ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Win Percentage</p>
                    <p class="stat-value"><?php echo htmlspecialchars($data['team']['winPercentage'] ?? '.000'); ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Points Per Game</p>
                    <p class="stat-value"><?php echo htmlspecialchars($data['team']['ppg'] ?? '0.0'); ?></p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Conference Rank</p>
                    <p class="stat-value"><?php echo htmlspecialchars($data['team']['conferenceRank'] ?? 'N/A'); ?></p>
                </div>
            </div>

            <!-- Team Roster -->
            <section class="roster-section">
                <h2 class="section-title">Team Roster</h2>
                <?php if (empty($data['roster'])): ?>
                    <p class="no-players">No roster information available.</p>
                <?php else: ?>
                    <div class="roster-grid">
                        <?php foreach ($data['roster'] as $player): ?>
                            <div class="player-card">
                                <img src="<?php echo htmlspecialchars($player['headshot']); ?>" alt="<?php echo htmlspecialchars($player['fullName']); ?>" class="player-image" onerror="this.src='/fin_proj/public/images/player-default.png'">
                                <div class="player-info">
                                    <h3 class="player-name"><?php echo htmlspecialchars($player['fullName']); ?></h3>
                                    <p class="player-position"><?php echo htmlspecialchars($player['position']); ?> | #<?php echo htmlspecialchars($player['jersey']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Recent Games & Schedule -->
            <div class="games-container">
                <!-- Recent Games -->
                <section>
                    <h2 class="section-title">Recent Games</h2>
                    <div class="games-list">
                        <?php if (empty($data['recentGames'])): ?>
                            <p class="no-games">No recent games available.</p>
                        <?php else: ?>
                            <?php foreach ($data['recentGames'] as $game): ?>
                                <?php 
                                    $isWin = $game['result'] === 'W';
                                    $resultClass = $isWin ? 'win' : 'loss';
                                ?>
                                <div class="game-card">
                                    <div class="game-header">
                                        <span class="game-date"><?php echo htmlspecialchars($game['date']); ?></span>
                                        <span class="game-result <?php echo $resultClass; ?>"><?php echo htmlspecialchars($game['result']); ?></span>
                                    </div>
                                    <div class="team-score">
                                        <div class="team-with-logo">
                                            <img src="<?php echo htmlspecialchars($game['homeTeam']['logo']); ?>" alt="<?php echo htmlspecialchars($game['homeTeam']['name']); ?>" class="small-team-logo">
                                            <span><?php echo htmlspecialchars($game['homeTeam']['name']); ?></span>
                                        </div>
                                        <span class="score"><?php echo htmlspecialchars($game['homeTeam']['score']); ?></span>
                                    </div>
                                    <div class="team-score">
                                        <div class="team-with-logo">
                                            <img src="<?php echo htmlspecialchars($game['awayTeam']['logo']); ?>" alt="<?php echo htmlspecialchars($game['awayTeam']['name']); ?>" class="small-team-logo">
                                            <span><?php echo htmlspecialchars($game['awayTeam']['name']); ?></span>
                                        </div>
                                        <span class="score"><?php echo htmlspecialchars($game['awayTeam']['score']); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Upcoming Games -->
                <section>
                    <h2 class="section-title">Upcoming Games</h2>
                    <div class="games-list">
                        <?php if (empty($data['upcomingGames'])): ?>
                            <p class="no-games">No upcoming games available.</p>
                        <?php else: ?>
                            <?php foreach ($data['upcomingGames'] as $game): ?>
                                <div class="game-card">
                                    <span class="game-time"><?php echo htmlspecialchars($game['date']); ?> - <?php echo htmlspecialchars($game['time']); ?></span>
                                    <div class="teams-vs">
                                        <div class="team-with-logo">
                                            <img src="<?php echo htmlspecialchars($game['homeTeam']['logo']); ?>" alt="<?php echo htmlspecialchars($game['homeTeam']['name']); ?>" class="small-team-logo">
                                            <span><?php echo htmlspecialchars($game['homeTeam']['name']); ?></span>
                                        </div>
                                        <span class="vs">vs</span>
                                        <div class="team-with-logo">
                                            <span><?php echo htmlspecialchars($game['awayTeam']['name']); ?></span>
                                            <img src="<?php echo htmlspecialchars($game['awayTeam']['logo']); ?>" alt="<?php echo htmlspecialchars($game['awayTeam']['name']); ?>" class="small-team-logo">
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const favoriteTeamBtn = document.querySelector('.favorite-team-btn');
            if (favoriteTeamBtn) {
                favoriteTeamBtn.addEventListener('click', function() {
                    const teamId = this.getAttribute('data-team-id');
                    toggleFavoriteTeam(teamId, this);
                });
            }

            const favoritePlayerBtns = document.querySelectorAll('.favorite-player-btn');
            favoritePlayerBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const playerId = this.getAttribute('data-player-id');
                    toggleFavoritePlayer(playerId, this);
                });
            });

            function toggleFavoriteTeam(teamId, button) {
                fetch('/fin_proj/team/toggle-favorite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `team_id=${teamId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            button.classList.add('favorited');
                            button.querySelector('span').textContent = 'Remove from Favorites';
                        } else {
                            button.classList.remove('favorited');
                            button.querySelector('span').textContent = 'Add to Favorites';
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating favorites');
                });
            }


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
                        } else {
                            button.classList.remove('favorited');
                        }
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating favorites');
                });
            }
        });
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
    </script>
</body>
</html>