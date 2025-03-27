<?php
require_once __DIR__ . "/../models/EspnApi.php";
require_once __DIR__ . "/BaseController.php";

class HomeController extends BaseController {
    private $espnApi;

    public function __construct() {
        parent::__construct();
        $this->espnApi = new EspnApi();
    }

    public function home() {
        $isLoggedIn = $this->isLoggedIn();

        try {
            $news = $this->espnApi->getLatestNews(10);


            if ($isLoggedIn) {
                $data = $this->getHomePageData($isLoggedIn);
                $data['user'] = $this->getUserData();
                include __DIR__ . "/../views/home/home.php";
            } else {
                $data = [
                    'news' => $news,
                ];
                include __DIR__ . "/../views/home/default.php";
            }
        } catch (Exception $e) {
            $data = [
                'news' => [],
                'error' => $e->getMessage()
            ];

            if ($isLoggedIn) {
                include __DIR__ . "/../views/home/home.php";
            } else {
                include __DIR__ . "/../views/home/default.php";
            }
        }
    }
    

    private function getHomePageData($isLoggedIn) {
        try {
            $news = $this->espnApi->getLatestNews(5);

            if ($isLoggedIn) {
                $upcomingGames = $this->espnApi->getUpcomingGames(10);
                $recentResults = $this->espnApi->getRecentResults(5);
                
                $userData = $this->getUserData();
                
                return [
                    'news' => $news,
                    'upcomingGames' => $upcomingGames,
                    'recentResults' => $recentResults,
                    'username' => $userData['username'] ?? 'Guest'
                ];
            } else {
                return [
                    'news' => $news
                ];
            }
    
        } catch (Exception $e) {
            if ($isLoggedIn) {
                $userData = $this->getUserData();
                
                return [
                    'news' => [],
                    'upcomingGames' => [],
                    'recentResults' => [],
                    'username' => $userData['username'] ?? 'Guest',
                    'error' => $e->getMessage()
                ];
            } else {
                return [
                    'news' => [],
                    'error' => $e->getMessage()
                ];
            }
        }
    }
}
?>
