<?php
require_once __DIR__ . "/BaseController.php";
require_once __DIR__ . "/../models/EspnApi.php";
require_once __DIR__ . "/../models/TeamModel.php";
require_once __DIR__ . "/../models/PlayerModel.php";


class TeamController extends BaseController{
    private $espnApi;
    private $teamModel;
    private $playerModel;



    public function __construct() {
        parent::__construct();
        $this->espnApi = new EspnApi();
        $this->teamModel = new TeamModel();
        $this->playerModel = new PlayerModel();
    }

    
    public function index(){
        $isLoggedIn = $this->isLoggedIn();
        $data = $this->getTeamsData();
        $data['user'] = $this->getUserData();

        if($isLoggedIn){
            $data['favoriteTeams'] = $this->teamModel->getUserFavoriteTeams($_SESSION['user_id']);

        }else{
            $data['favoriteTeams'] = [];
        }

        include __DIR__ . "/../views/team/teams.php";
    }
    
    public function show($id) {
        $isLoggedIn = $this->isLoggedIn();
        $data['user'] = $this->getUserData();
    
        if (empty($id) || !is_numeric($id)) {
            $data['error'] = "Invalid team ID provided.";
            include __DIR__ . "/../views/error/not-found.php";
            return;
        }
    
        try {
            $teamData = $this->espnApi->getTeamById($id);
            if (!$teamData) {
                throw new Exception("Team data not found for ID: $id");
            }
    

            $data['team'] = [
                'id' => $teamData['team']['id'],
                'name' => $teamData['team']['displayName'],
                'logo' => $teamData['team']['logos'][0]['href'],
                'stadium' => [
                    'image' => $teamData['team']['franchise']['venue']['images'][0]['href'] ?? '/fin_proj/public/images/arena.jpg'
                ],
                'conference' => $teamData['team']['groups']['parent']['id'] === '5' ? 'Eastern' : 'Western', 
                'division' => $teamData['team']['groups']['isConference'] ? 'N/A' : ($teamData['team']['groups']['id'] === '9' ? 'Southeast' : 'Unknown'),
                'record' => $teamData['team']['record']['items'][0]['summary'] ?? '0-0',
                'winPercentage' => number_format($teamData['team']['record']['items'][0]['stats'][17]['value'], 3) ?? '.000', 
                'ppg' => $teamData['team']['record']['items'][0]['stats'][3]['value'] ?? '0.0', 
                'conferenceRank' => preg_replace('/[^0-9]/', '', $teamData['team']['standingSummary']) ?? 'N/A' 
            ];
    
            $data['roster'] = $this->espnApi->getTeamPlayers($id);
            $data['recentGames'] = $this->espnApi->getTeamRecentGames($id, 5);
            $data['upcomingGames'] = $this->espnApi->getTeamUpcomingGames($id, 5);
    
            if ($isLoggedIn) {
                $data['isFavorite'] = $this->teamModel->isTeamFavorite($_SESSION['user_id'], $id);
            } else {
                $data['isFavorite'] = false;
                $data['favoritePlayers'] = [];
            }
    
            include __DIR__ . "/../views/team/team.php";
        } catch (Exception $e) {
            $data['error'] = $e->getMessage();
            include __DIR__ . "/../views/error/not-found.php";
        }
    }

    private function getTeamsData() {
        try {
            $rawTeams = $this->espnApi->getAllTeams();
            $teams = array_map(function ($teamData) {
            $team = $teamData['team'];
            return [
                'id' => $team['id'],
                'name' => $team['name'],
                'conference' => $team['conference'],
                'logo' => $team['logo']
            ];
        }, $rawTeams);
            return [
                'teams' => $teams,
                'error' => null
            ];
        } catch (Exception $e) {
            error_log("Error in getTeamsData: " . $e->getMessage());
            return [
                'teams' => [],
                'error' => $e->getMessage()
            ];
        }
    }


    public function toggleFavorite(){
        if(!$this->isLoggedIn()){
            echo json_encode([
                'success' => false,
                'message' => 'User not logged in',

            ]);
            return;
        }

        $teamId = $_POST['team_id'] ?? null;
        
        if(!$teamId){
            echo json_encode([
                'success' => false,
                'message' => 'TeamId is required'

            ]);
            return;
        }

        $result = $this->teamModel->toggleFavoriteTeam($_SESSION['user_id'], $teamId);

        echo json_encode($result);
    }


    
    
    private function parseRosterData($rosterData) {
        $roster = [];
        
        if (empty($rosterData) || !is_array($rosterData)) {
            return $roster;
        }
        
        if (isset($rosterData['athletes'])) {
            foreach ($rosterData['athletes'] as $category) {
                if (!isset($category['items'])) {
                    continue;
                }
                
                foreach ($category['items'] as $playerData) {
                    $player = $this->formatPlayerData($playerData);
                    if ($player) {
                        $roster[] = $player;
                    }
                }
            }
        } elseif (isset($rosterData['items'])) {
            foreach ($rosterData['items'] as $playerData) {
                $player = $this->formatPlayerData($playerData);
                if ($player) {
                    $roster[] = $player;
                }
            }
        }
        
        return $roster;
    }
    
    private function formatPlayerData($playerData) {
        if (!isset($playerData['id'])) {
            return null;
        }
        
        return [
            'id' => $playerData['id'],
            'firstName' => $playerData['firstName'] ?? '',
            'lastName' => $playerData['lastName'] ?? '',
            'fullName' => $playerData['fullName'] ?? ($playerData['firstName'] . ' ' . $playerData['lastName']),
            'position' => $playerData['position']['abbreviation'] ?? $playerData['position']['name'] ?? 'N/A',
            'jersey' => $playerData['jersey'] ?? 'N/A',
            'headshot' => isset($playerData['headshot']['href']) ? 
                $playerData['headshot']['href'] : '/fin_proj/public/images/player-default.png',
            'height' => $playerData['height'] ?? 'N/A',
            'weight' => $playerData['weight'] ?? 'N/A',
            'age' => $playerData['age'] ?? 'N/A'
        ];
    }
}



?>