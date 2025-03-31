<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../models/EspnApi.php";
require_once __DIR__ . "/../models/PlayerModel.php";

class PlayerController extends BaseController {
    private $espnApi;
    private $playerModel;
    private $playersPerPage = 20;

    public function __construct() {
        parent::__construct();
        $this->espnApi = new EspnApi();
        $this->playerModel = new PlayerModel();
    }
    
    public function index() {
        $isLoggedIn = $this->isLoggedIn();
        $userId = $isLoggedIn ? $_SESSION['user_id'] : null;
        
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($currentPage < 1) $currentPage = 1;
        
        $allPlayers = $this->getCachedPlayers();
        
        if (empty($allPlayers)) {
            $allPlayers = $this->fetchAllPlayers();
            
            $this->cacheAllPlayers($allPlayers);
        }

        $totalPlayers = count($allPlayers);
        $totalPages = ceil($totalPlayers / $this->playersPerPage);
        if ($currentPage > $totalPages) $currentPage = $totalPages;
        
        $offset = ($currentPage - 1) * $this->playersPerPage;
        $playersForPage = array_slice($allPlayers, $offset, $this->playersPerPage);
        
        $data = [
            'user' => $this->getUserData(),
            'players' => $playersForPage,
            'pagination' => [
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'playersPerPage' => $this->playersPerPage,
                'totalPlayers' => $totalPlayers
            ]
        ];
        
        include __DIR__ . "/../views/players/players.php";
    }
    
    private function fetchAllPlayers() {
        $allTeams = $this->espnApi->getAllTeams();
        
        $allPlayers = [];
        
        foreach ($allTeams as $teamData) {
            $teamId = $teamData['team']['id'];
            $teamName = $teamData['team']['name'];
            $teamLogo = $teamData['team']['logo'];
            
            $teamPlayers = $this->espnApi->getTeamPlayers($teamId);
            
            foreach ($teamPlayers as &$player) {
                $player['teamId'] = $teamId;
                $player['teamName'] = $teamName;
                $player['teamLogo'] = $teamLogo;
            }
            
            // adding player to array
            $allPlayers = array_merge($allPlayers, $teamPlayers);
        }
        
        usort($allPlayers, function($a, $b) {
            return strcmp($a['fullName'], $b['fullName']);
        });
        
        return $allPlayers;
    }
    
    private function getCachedPlayers() {
        $cacheFile = __DIR__ . '/../cache/all_players.json';
        
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < 86400)) {
            $cachedData = file_get_contents($cacheFile);
            return json_decode($cachedData, true);
        }
        
        return [];
    }
    
    private function cacheAllPlayers($players) {
        $cacheDir = __DIR__ . '/../cache';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        $cacheFile = $cacheDir . '/all_players.json';
        file_put_contents($cacheFile, json_encode($players));
    }
    

    public function show($id) {
        $isLoggedIn = $this->isLoggedIn();
        $data['user'] = $this->getUserData();
        
        try {
            $data['player'] = $this->espnApi->getPlayerById($id);
            $data['stats'] = $this->espnApi->getPlayerStats($id);
            
            if ($isLoggedIn) {
                $data['isFavorite'] = $this->playerModel->isPlayerFavorite($_SESSION['user_id'], $id);
            } else {
                $data['isFavorite'] = false;
            }
            
            include __DIR__ . "/../views/player/player-details.php";
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
            include __DIR__ . "/../views/error/not-found.php";
        }
    }
}
?>