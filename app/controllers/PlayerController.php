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
    
    public function getAllPlayersJson() {
        $allPlayers = $this->getCachedPlayers();

        if (empty($allPlayers)) {
            $allPlayers = $this->fetchAllPlayers();
            $this->cacheAllPlayers($allPlayers);
        }


        $search = $_GET['search'] ?? '';
        $position = $_GET['position'] ?? 'all';
        $team = $_GET['team'] ?? 'all';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $playersPerPage = $this->playersPerPage;


        $filteredPlayers = array_filter($allPlayers, function ($player) use ($search, $position, $team) {
            $matchesSearch = empty($search) || stripos($player['fullName'], $search) !== false;
            $matchesPosition = $position === 'all' || stripos($player['position'], $position) !== false;
            $matchesTeam = $team === 'all' || $player['teamId'] == $team;

            return $matchesSearch && $matchesPosition && $matchesTeam;
        });


        $totalPlayers = count($filteredPlayers);
        $totalPages = ceil($totalPlayers / $playersPerPage);
        $offset = ($page - 1) * $playersPerPage;
        $playersForPage = array_slice($filteredPlayers, $offset, $playersPerPage);


        $response = [
            'players' => $playersForPage,
            'pagination' => [
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'playersPerPage' => $playersPerPage,
                'totalPlayers' => $totalPlayers,
                'start' => $offset + 1,
                'end' => min($offset + $playersPerPage, $totalPlayers),
                'startPage' => max(1, $page - 2),
                'endPage' => min($totalPages, $page + 2),
            ],
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>