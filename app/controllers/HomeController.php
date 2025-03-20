<?php
require_once __DIR__ . "/../models/EspnApi.php";


class HomeController{
    private $espnApi;

    public function __construct(){
        $this->espnApi = new EspnApi();

    }

    public function home(){
        $isLoggedIn = isset($_SESSION['user_id']);

        $data = $this->getHomePageData();

        if($isLoggedIn){
            include __DIR__ . "/../views/home/home.php";
        }else{
            include __DIR__ . "/../views/home/default.php";
        }

    }


    private function getHomePageData(){
        try{
            $news = $this->espnApi->getLatestNews(5);

            $upcomingGames = $this->espnApi->getUpcomingGames(5);

            $recentResults = $this->espnApi->getRecentResults(5);


            return [
                'news' => $news,
                'upcomingGames' => $upcomingGames,
                'recentResults' => $recentResults
            ];

        }catch(Exception $e){
            return [
                'news' => [],
                'upcomingGames' => [],
                'recentResults' => [],
                'error' => $e->getMessage()
            ];
        }
       
    }



}



?>