<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams | NBA Stats</title>
    <link href="https://fonts.googleapis.com/css?family=Big+Shoulders+Display" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo '/fin_proj/public/css/teams.css'; ?>">
    <style>
        
    </style>
</head>
<body>
    <div class="page-container">
        <?php require_once __DIR__ . "/../layout/header/header-logged.php"; ?>
        <div class="header-section">
            <h1>NBA Teams</h1>
            <p>Explore all 30 NBA teams and their statistics</p>
            <div class="search-bar">
                <input type="text" id="team-search" placeholder="Search teams..." onkeyup="filterTeams()">
            </div>
        </div>
        <main>
            <?php if (!empty($data['error'])): ?>
                <div class="error">
                    <p><?php echo htmlspecialchars($data['error']); ?></p>
                </div>
            <?php elseif (empty($data['teams'])): ?>
                <div class="error">
                    <p>No teams available at this time.</p>
                </div>
            <?php else: ?>
                <div class="teams-list" id="teams-list">
                    <?php foreach ($data['teams'] as $team): ?>
                        <div class="team-card" 
                             data-team-id="<?php echo htmlspecialchars($team['id'] ?? ''); ?>" 
                             data-team-name="<?php echo htmlspecialchars(strtolower($team['name'])); ?>"
                             onclick="goToTeamPage('<?php echo htmlspecialchars($team['id'] ?? ''); ?>')">
                            <img src="<?php echo htmlspecialchars($team['logo'] ?? '/fin_proj/public/images/default-logo.png'); ?>" 
                                 alt="<?php echo htmlspecialchars($team['name'] ?? 'Team') . ' Logo'; ?>" 
                                 class="team-logo">
                            <h2><?php echo htmlspecialchars($team['name'] ?? 'Unknown'); ?></h2>
                            <p><?php echo htmlspecialchars($team['conference'] ?? 'Unknown'); ?></p>
                            <?php if ($data['user']['isLoggedIn']): ?>
                                <button 
                                    class="favorite-btn" 
                                    data-team-id="<?php echo htmlspecialchars($team['id'] ?? ''); ?>"
                                    data-favorited="<?php echo in_array($team['id'], $data['favoriteTeams']) ? 'true' : 'false'; ?>"
                                    onclick="event.stopPropagation();">
                                    <?php echo in_array($team['id'], $data['favoriteTeams']) ? 'Unfavorite' : 'Favorite'; ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </main>
        <?php require_once __DIR__ . "/../layout/footer/footer-logged.php"; ?>
    </div>
    <script>

        function goToTeamPage(teamId) {
            window.location.href = `/fin_proj/team/${teamId}`;
        }

        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.stopPropagation();
                const teamId = this.getAttribute('data-team-id');
                const isFavorited = this.getAttribute('data-favorited') === 'true';

                fetch('/fin_proj/team/toggle-favorite', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `team_id=${teamId}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const newState = data.action === 'added' ? 'true' : 'false';
                        this.setAttribute('data-favorited', newState);
                        this.textContent = data.action === 'added' ? 'Unfavorite' : 'Favorite';
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        function filterTeams() {
            const searchInput = document.getElementById('team-search').value.toLowerCase();
            const teamCards = document.querySelectorAll('.team-card');

            teamCards.forEach(card => {
                const teamName = card.getAttribute('data-team-name');
                if (teamName.includes(searchInput)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
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
    </script>
</body>
</html>