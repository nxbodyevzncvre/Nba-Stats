<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <title>Golden State Warriors - Team Page</title>
    <link rel="stylesheet" href="../../../public/css/team.css">
</head>
<body>
    <?php require_once "../layout/header/header-logged.php"?>
    <div class="team-header">
        <img src="../../../public/images/arena.jpg" alt="Team Arena" class="header-bg">
        <div class="team-info-container">
            <div class="container">
                <div class="team-info">
                    <img src="../../../public/images/lebron-default.png" alt="Team Logo" class="team-logo">
                    <div class="team-details">
                        <h1 class="team-name">Golden State Warriors</h1>
                        <div class="team-conference">
                            <span>Western Conference</span>
                            <span class="dot"></span>
                            <span>Pacific Division</span>
                        </div>
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
                    <p class="stat-value">29-15</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Win Percentage</p>
                    <p class="stat-value">.659</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Points Per Game</p>
                    <p class="stat-value">119.2</p>
                </div>
                <div class="stat-card">
                    <p class="stat-label">Conference Rank</p>
                    <p class="stat-value">4th</p>
                </div>
            </div>

            <!-- Team Roster -->
            <section class="roster-section">
                <h2 class="section-title">Team Roster</h2>
                <div class="roster-grid">
                    <div class="player-card">
                        <img src="../../../public/images/lebron-default.png" alt="Stephen Curry" class="player-image">
                        <div>
                            <h3 class="player-name">Stephen Curry</h3>
                            <p class="player-position">Point Guard | #30</p>
                        </div>
                    </div>
                    <div class="player-card">
                        <img src="../../../public/images/lebron-default.png" alt="Stephen Curry" class="player-image">
                        <div>
                            <h3 class="player-name">Stephen Curry</h3>
                            <p class="player-position">Point Guard | #30</p>
                        </div>
                    </div>
                    <div class="player-card">
                        <img src="../../../public/images/lebron-default.png" alt="Stephen Curry" class="player-image">
                        <div>
                            <h3 class="player-name">Stephen Curry</h3>
                            <p class="player-position">Point Guard | #30</p>
                        </div>
                    </div>
                    <div class="player-card">
                        <img src="../../../public/images/lebron-default.png" alt="Klay Thompson" class="player-image">
                        <div>
                            <h3 class="player-name">Klay Thompson</h3>
                            <p class="player-position">Shooting Guard | #11</p>
                        </div>
                    </div>
                    <div class="player-card">
                        <img src="../../../public/images/lebron-default.png" alt="Draymond Green" class="player-image">
                        <div>
                            <h3 class="player-name">Draymond Green</h3>
                            <p class="player-position">Power Forward | #23</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Recent Games & Schedule -->
            <div class="games-container">
                <!-- Recent Games -->
                <section>
                    <h2 class="section-title">Recent Games</h2>
                    <div class="games-list">
                        <div class="game-card">
                            <div class="game-header">
                                <span class="game-date">Jan 20, 2024</span>
                                <span class="game-result win">W</span>
                            </div>
                            <div class="team-score">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Warriors" class="small-team-logo">
                                    <span>Warriors</span>
                                </div>
                                <span class="score">120</span>
                            </div>
                            <div class="team-score">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Lakers" class="small-team-logo">
                                    <span>Lakers</span>
                                </div>
                                <span class="score">115</span>
                            </div>
                        </div>

                        <div class="game-card">
                            <div class="game-header">
                                <span class="game-date">Jan 18, 2024</span>
                                <span class="game-result loss">L</span>
                            </div>
                            <div class="team-score">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Warriors" class="small-team-logo">
                                    <span>Warriors</span>
                                </div>
                                <span class="score">112</span>
                            </div>
                            <div class="team-score">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Suns" class="small-team-logo">
                                    <span>Suns</span>
                                </div>
                                <span class="score">118</span>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Upcoming Games -->
                <section>
                    <h2 class="section-title">Upcoming Games</h2>
                    <div class="games-list">
                        <div class="game-card">
                            <span class="game-time">Jan 23, 2024 - 7:30 PM ET</span>
                            <div class="teams-vs">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Warriors" class="small-team-logo">
                                    <span>Warriors</span>
                                </div>
                                <span class="vs">vs</span>
                                <div class="team-with-logo">
                                    <span>Kings</span>
                                    <img src="../../../public/images/lebron-default.png" alt="Kings" class="small-team-logo">
                                </div>
                            </div>
                        </div>

                        <div class="game-card">
                            <span class="game-time">Jan 25, 2024 - 8:00 PM ET</span>
                            <div class="teams-vs">
                                <div class="team-with-logo">
                                    <img src="../../../public/images/lebron-default.png" alt="Warriors" class="small-team-logo">
                                    <span>Warriors</span>
                                </div>
                                <span class="vs">vs</span>
                                <div class="team-with-logo">
                                    <span>Clippers</span>
                                    <img src="../../../public/images/lebron-default.png" alt="Clippers" class="small-team-logo">
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <?php require_once "../layout/footer/footer-logged.php"?>
</body>
</html>