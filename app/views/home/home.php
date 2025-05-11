<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NBA Stats - Home</title>
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo '/fin_proj/public/css/home.css'; ?>">
    <link rel="shortcut icon" href="/fin_proj/public/images/nba-logo.png" type="image/x-icon">
</head>
<body>
    <!-- Header -->
    <?php include_once __DIR__ . "/../layout/header/header-logged.php"; ?>
 
    <!-- LOADING SPINNER -->
    <div id="preloader">
        <div class="spinner"></div>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Welcome back, <?php echo htmlspecialchars($data['user']['username'] ?? 'Guest')?></h1>                <p class="hero-subtitle">Stay updated with the latest NBA games, stats, and news. Your personalized basketball experience awaits.</p>
                <div class="hero-buttons">
                    <button class="primary-button">Today's Games</button>
                    <a class="secondary-button" href="/fin_proj/favorites/teams">Your Favorites</a>
                </div>
            </div>
        </div>
    </section>

    <main>
        <div class="container">

            <section class="live-games">
                <h2 class="section-title">Live & Upcoming Games</h2>
                <div class="games-scroll">
                    <?php if (empty($data['upcomingGames'])): ?>
                        <p>No upcoming games found.</p>
                    <?php else: ?>
                        <?php foreach ($data['upcomingGames'] as $game): ?>
                            <div class="game-card">
                                <?php 
                                    $isLive = isset($game['status']) && $game['status']['type']['state'] === 'in';
                                    $statusClass = $isLive ? 'LIVE' : 'UPCOMING';
                                    $statusStyle = $isLive ? '' : 'background-color: #3B82F6;';
                                ?>
                                <span class="game-status" style="<?php echo $statusStyle; ?>"><?php echo $statusClass; ?></span>
                                <div class="game-teams">
                                    <div class="team">
                                        <?php 
                                            $homeTeamLogo = isset($game['competitions'][0]['competitors'][0]['team']['logo']) 
                                                ? $game['competitions'][0]['competitors'][0]['team']['logo'] 
                                                : '/fin_proj/public/images/lebron-default.png';
                                            $homeTeamName = isset($game['competitions'][0]['competitors'][0]['team']['name']) 
                                                ? $game['competitions'][0]['competitors'][0]['team']['name'] 
                                                : 'Team';
                                            $homeTeamScore = isset($game['competitions'][0]['competitors'][0]['score']) 
                                                ? $game['competitions'][0]['competitors'][0]['score'] 
                                                : '';
                                        ?>
                                        <a href="/fin_proj/team/<?php echo htmlspecialchars($game['competitions'][0]['competitors'][0]['team']['id'])?>"><img src="<?php echo htmlspecialchars($homeTeamLogo); ?>" alt="<?php echo htmlspecialchars($homeTeamName); ?>" class="team-logo"></a>
                                        <span class="team-name"><?php echo htmlspecialchars($homeTeamName); ?></span>
                                        <?php if ($isLive && !empty($homeTeamScore)): ?>
                                            <span class="team-score"><?php echo htmlspecialchars($homeTeamScore); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <span style="font-size: 20px;">vs</span>
                                    </div>
                                    <div class="team">
                                        <?php 
                                            $awayTeamLogo = isset($game['competitions'][0]['competitors'][1]['team']['logo']) 
                                                ? $game['competitions'][0]['competitors'][1]['team']['logo'] 
                                                : '/fin_proj/public/images/lebron-default.png';
                                            $awayTeamName = isset($game['competitions'][0]['competitors'][1]['team']['name']) 
                                                ? $game['competitions'][0]['competitors'][1]['team']['name'] 
                                                : 'Team';
                                            $awayTeamScore = isset($game['competitions'][0]['competitors'][1]['score']) 
                                                ? $game['competitions'][0]['competitors'][1]['score'] 
                                                : '';
                                        ?>
                                        <a href="/fin_proj/team/<?php echo htmlspecialchars($game['competitions'][0]['competitors'][1]['team']['id'])?>"><img src="<?php echo htmlspecialchars($awayTeamLogo); ?>" alt="<?php echo htmlspecialchars($awayTeamName); ?>" class="team-logo"></a>
                                        <span class="team-name"><?php echo htmlspecialchars($awayTeamName); ?></span>
                                        <?php if ($isLive && !empty($awayTeamScore)): ?>
                                            <span class="team-score"><?php echo htmlspecialchars($awayTeamScore); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="game-info">
                                    <?php 
                                        if ($isLive) {
                                            echo isset($game['status']['displayClock']) ? $game['status']['displayClock'] . ' remaining' : '';
                                            echo isset($game['status']['period']) ? ' - Q' . $game['status']['period'] : '';
                                        } else {
                                            $gameDate = new DateTime($game['date']);
                                            echo $gameDate->format('M j, Y, g:i A');
                                        }
                                    ?>
                                </p>
                                <button class="watch-button" id = "watch-button"><?php echo $isLive ? 'Watch Now' : 'Set Reminder'; ?></button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

            <!-- News Section -->
            <section class="news-section">
                <h2 class="section-title">Latest NBA News</h2>
                <div class="news-grid">
                    <?php if (empty($data['news'])): ?>
                        <p>No news found.</p>
                    <?php else: ?>
                        <?php foreach ($data['news'] as $index => $article): ?>
                            <?php if ($index < 3): ?>
                                <div class="news-card" >
                                    <a href="http://espn.com/nba" target="_blank">
                                    <?php 
                                    $imageUrl = isset($article['images'][0]['url']) 
                                        ? $article['images'][0]['url'] 
                                        : '/fin_proj/public/images/lebron-default.png';
                                    ?>
                                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="News Image" class="news-image">
                                    <div class="news-content">
                                        <h3 class="news-title"><?php echo htmlspecialchars($article['headline']); ?></h3>
                                        <p class="news-excerpt"><?php echo htmlspecialchars($article['description']); ?></p>
                                        <div class="news-meta">
                                            <?php 

                                            $pubDate = new DateTime($article['published']);
                                            $now = new DateTime();
                                            $interval = $now->diff($pubDate);
                                            
                                            if ($interval->days == 0) {
                                                if ($interval->h == 0) {
                                                    echo '<span>' . $interval->i . ' minutes ago</span>';
                                                } else {
                                                    echo '<span>' . $interval->h . ' hours ago</span>';
                                                }
                                            } else if ($interval->days == 1) {
                                                echo '<span>Yesterday</span>';
                                            } else {
                                                echo '<span>' . $pubDate->format('M j, Y') . '</span>';
                                            }
                                            ?>
                                            <span><?php echo htmlspecialchars($article['source'] ?? 'ESPN'); ?></span>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer -->
    <?php include_once __DIR__ . "/../layout/footer/footer-logged.php"; ?>

    <script>
        let buttons = document.querySelectorAll("#watch-button");
        buttons.forEach(button => {
            button.addEventListener("click", () => {alert("Success!")});
        });

        document.addEventListener("DOMContentLoaded", () => {
        const preloader = document.getElementById("preloader");
        window.addEventListener("load", () => {
            preloader.style.opacity = "0";
            preloader.style.visibility = "hidden";
            setTimeout(() => preloader.remove(), 500);
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


    });




    </script>
</body>
</html>



