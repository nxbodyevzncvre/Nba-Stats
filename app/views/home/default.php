<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://fonts.googleapis.com/css?family=Big Shoulders Display' rel='stylesheet'>
    <link rel="stylesheet" href="../../../public/css/output.css">
    <style>
        body {
            font-family: 'Big Shoulders Display';
        }
        .bg-custom {
            background-image: url('../../../public/images/background-image.png');
        }
    </style>

</head>
<body class="relative text-base">
    <div class="relative min-h-screen">
        <div class="absolute top-0 left-0 w-full h-full">
            <video class="w-full h-full object-cover" autoplay muted loop playsinline>
                <source src="../../../public/videos/background.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="absolute top-0 left-0 w-full h-full bg-black/60"></div>
        </div>
        
        <div class="relative z-20">
            <?php 
            include "../../views/layout/header-default.php";
            ?>
        </div>
        
        <div class="absolute inset-0 z-10 flex items-center justify-center px-4">
            <div class="w-[900px] bg-black/30 p-8 rounded-lg text-white flex flex-col items-center">
                <div class="flex justify-between items-center w-full mb-8">
                    <div>
                        <h1 class="text-[64px] font-bold mb-6 text-center">NBA STATS</h1>
                        <p class="text-[32px] font-light leading-relaxed">Your Best Way to Dive Into<br>Basketball Brilliance</p>
                    </div>
                    <div class="">
                        <img src="../../../public/images/nba-teams.png" alt="NBA Teams" class="w-full">
                    </div>
                </div>
                <button class="px-10 py-3 bg-blue-600 hover:bg-blue-700 rounded-md text-[24px] transition-colors">Dive In</button>
            </div>
        </div>
    </div>
    
    <main class="relative min-h-screen bg-custom bg-fixed bg-cover bg-center">
        <div class="container-fluid mx-auto text-white">
            <div class="text-4xl float-end p-4">
                <p>YOUR <span class="text-red-700">BASKETBALL</span> JOURNEY STARTS HERE</p>
            </div>
            <div></div>
            <div></div>
        </div>
    </main>
</body>
</html>