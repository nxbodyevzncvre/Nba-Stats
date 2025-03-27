<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <link rel="stylesheet" href="<?php echo '/fin_proj/public/css/default.css'; ?>">

    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</head>
<body>
    <div class="default-hero">
        <div class="default-hero__video">
            <video autoplay muted loop playsinline>
                <source src="/fin_proj/public/videos/background.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="default-hero__overlay"></div>
        </div>
        
        <div class="default-hero__header">
            <?php include_once __DIR__ . "/../layout/header/header-default.php"?>
        </div>
        
        <div class="default-hero__content">
            <div class="default-hero__box">
                <div class="default-hero__header-content">
                    <div class="default-hero__text">
                        <h1 class="default-hero__title">NBA STATS</h1>
                        <p class="default-hero__description">Your Best Way to Dive Into<br>Basketball Brilliance</p>
                    </div>
                    <div class="default-hero__image">
                        <img src="/fin_proj/public/images/nba-teams.png" alt="NBA Teams">
                    </div>
                </div>
                <button class="btn btn--primary">Dive In</button>
            </div>
        </div>
    </div>
    
    <main class="default-main">
        <div class="container">
            <div class="default-main__title">
                <p>YOUR <span class="default-main__highlight">BASKETBALL</span> JOURNEY STARTS HERE</p>
            </div>
            <section class="last-nba-news">
                <div class="swiper-container right-swiper">
                    <div class="swiper-wrapper">
                        <?php if (empty($data['news'])): ?>
                            <p>No news found.</p>
                        <?php else: ?>
                            <?php foreach ($data['news'] as $index => $article): ?>
                                <?php if ($index < 5): ?>
                                    <div class="swiper-slide" style="height:320px">
                                        <?php 
                                        $imageUrl = isset($article['images'][0]['url']) 
                                            ? $article['images'][0]['url'] 
                                            : '/fin_proj/public/images/lebron-default.png';
                                        ?>
                                        <div class="swiper-slide__image" style="background-image: url('<?php echo htmlspecialchars($imageUrl); ?>');"></div>
                                        <h3 class="swiper-slide__title"><?php echo htmlspecialchars($article['headline']); ?></h3>
                                        <p class="swiper-slide__description" style = "height:40px"><?php echo htmlspecialchars($article['description']); ?></p>
                                        <div class="swiper-slide__footer">
                                            <span>
                                                <?php 
                                                $pubDate = new DateTime($article['published']);
                                                $now = new DateTime();
                                                $interval = $now->diff($pubDate);

                                                if ($interval->days == 0) {
                                                    if ($interval->h == 0) {
                                                        echo $interval->i . ' minutes ago';
                                                    } else {
                                                        echo $interval->h . ' hours ago';
                                                    }
                                                } else if ($interval->days == 1) {
                                                    echo 'Yesterday';
                                                } else {
                                                    echo $pubDate->format('M j, Y');
                                                }
                                                ?>
                                            </span>
                                            <a href="https://www.espn.com/nba/" target="_blank">Read more</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="last-nba-news">
                <div class="swiper-container left-swiper">
                    <div class="swiper-wrapper">
                        <?php if (empty($data['news'])): ?>
                            <p>No news found.</p>
                        <?php else: ?>
                            <?php foreach ($data['news'] as $index => $article): ?>
                                <?php if ($index >= 5 && $index < 10): ?>
                                    <div class="swiper-slide" style="height:320px">
                                        <?php 
                                        $imageUrl = isset($article['images'][0]['url']) 
                                            ? $article['images'][0]['url'] 
                                            : '/fin_proj/public/images/lebron-default.png';
                                        ?>
                                        <div class="swiper-slide__image" style="background-image: url('<?php echo htmlspecialchars($imageUrl); ?>');"></div>
                                        <h3 class="swiper-slide__title"><?php echo htmlspecialchars($article['headline']); ?></h3>
                                        <p class="swiper-slide__description" style = "height:40px"><?php echo htmlspecialchars($article['description']); ?></p>
                                        <div class="swiper-slide__footer">
                                            <span>
                                                <?php 
                                                $pubDate = new DateTime($article['published']);
                                                $now = new DateTime();
                                                $interval = $now->diff($pubDate);

                                                if ($interval->days == 0) {
                                                    if ($interval->h == 0) {
                                                        echo $interval->i . ' minutes ago';
                                                    } else {
                                                        echo $interval->h . ' hours ago';
                                                    }
                                                } else if ($interval->days == 1) {
                                                    echo 'Yesterday';
                                                } else {
                                                    echo $pubDate->format('M j, Y');
                                                }
                                                ?>
                                            </span>
                                            <a href="https://www.espn.com/nba/" target="_blank">Read more</a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <?php include_once __DIR__ .  "/../layout/footer/footer-default.php"?>
</div>


    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var swiperRight = new Swiper('.right-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            direction: 'horizontal',
            loop: true, 
            parallax:true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                reverseDirection: false,
                pauseOnMouseEnter: true,
            },
            speed: 2500, 
            loopAdditionalSlides: 1,
            effect: 'slide',
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        var swiperLeft = new Swiper('.left-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            direction: 'horizontal',
            loop: true,
            parallax:true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
                reverseDirection: true,
                pauseOnMouseEnter: true,
            },
            speed: 2500,
            loopAdditionalSlides: 1,
            effect: 'slide',
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });

        document.querySelector('.right-swiper').addEventListener('mouseover', () => swiperRight.autoplay.stop());
        document.querySelector('.right-swiper').addEventListener('mouseout', () => swiperRight.autoplay.start());
        document.querySelector('.left-swiper').addEventListener('mouseover', () => swiperLeft.autoplay.stop());
        document.querySelector('.left-swiper').addEventListener('mouseout', () => swiperLeft.autoplay.start());
    </script>
    <script type="module" src=""></script>
</body>
</html>