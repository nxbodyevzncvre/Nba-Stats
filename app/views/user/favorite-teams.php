<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>My Favorite Teams</title>
    <link rel="stylesheet" href="/fin_proj/public/css/favorites.css">
</head>
<body>
    <?php require_once __DIR__ . "/../layout/header/header-logged.php"?>
    
    <main class="main-content">
        <div class="main-container">
            <h1 class="page-title">My Favorite Teams</h1>
            
            <?php if (empty($data['favoriteTeams'])): ?>
                <div class="no-favorites">
                    <p>You haven't added any favorite teams yet.</p>
                    <a href="/fin_proj/teams" class="browse-link">Browse Teams</a>
                </div>
            <?php else: ?>
                <div class="teams-grid">
                    <?php foreach ($data['favoriteTeams'] as $teamData): ?>
                        <div class="team-card">
                            <a href="/fin_proj/team/<?php echo htmlspecialchars($teamData['team']['id'] ?? ''); ?>" class="team-link">
                                <img src="<?php echo htmlspecialchars($teamData['team']['logo'] ?? '/fin_proj/public/images/team-default.png'); ?>" 
                                     alt="<?php echo htmlspecialchars($teamData['team']['name'] ?? 'Team Logo'); ?>" class="team-logo">
                                <div class="team-info">
                                    <h2 class="team-name"><?php echo htmlspecialchars($teamData['team']['name'] ?? 'Unknown Team'); ?></h2>
                                    <p class="team-conference"><?php echo htmlspecialchars($teamData['team']['conference'] ?? 'Unknown'); ?> Conference</p>
                                    <p class="team-record">Record: <?php echo htmlspecialchars($teamData['team']['record'] ?? '0-0'); ?></p>
                                </div>
                            </a>
                            <button class="remove-favorite-btn" data-team-id="<?php echo htmlspecialchars($teamData['team']['id'] ?? ''); ?>">
                                Remove
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <?php require_once __DIR__ . "/../layout/footer/footer-logged.php"?>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeFavoriteBtns = document.querySelectorAll('.remove-favorite-btn');
            removeFavoriteBtns.forEach(button => {
                button.addEventListener('click', function() {
                    const teamId = this.getAttribute('data-team-id');
                    if (!teamId) {
                        alert('Invalid team ID');
                        return;
                    }
                    
                    const teamCard = this.closest('.team-card');
                    
                    fetch('/fin_proj/team/toggle-favorite', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `team_id=${teamId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.action === 'removed') {
                            teamCard.remove();
                            
                            const remainingTeams = document.querySelectorAll('.team-card');
                            if (remainingTeams.length === 0) {
                                const teamsGrid = document.querySelector('.teams-grid');
                                teamsGrid.innerHTML = `
                                    <div class="no-favorites">
                                        <p>You haven't added any favorite teams yet.</p>
                                        <a href="/fin_proj/teams" class="browse-link">Browse Teams</a>
                                    </div>
                                `;
                            }
                        } else {
                            alert(data.message || 'An error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating favorites');
                    });
                });
            });
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