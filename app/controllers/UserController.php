<?php
class UserController extends BaseController {
    protected $userModel;
    protected $espnApi;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->espnApi = new EspnApi();
    }

    
    public function favoriteTeams() {
        $isLoggedIn = $this->isLoggedIn();
        if (!$isLoggedIn) {
            header('Location: /fin_proj/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $favoriteTeamIds = $this->userModel->getFavoriteTeamIds($userId);
        
        $favoriteTeams = [];
        foreach ($favoriteTeamIds as $teamId) {
            $teamData = $this->espnApi->getTeamById($teamId);
            
            if ($teamData) {
                $favoriteTeams[] = [
                    'team' => [
                        'id' => $teamId,
                        'name' => $teamData['team']['displayName'] ?? "Team #{$teamId}",
                        'abbreviation' => $teamData['team']['abbreviation'] ?? '',
                        'logo' => $teamData['team']['logos'][0]['href'] ?? '/fin_proj/public/images/team-default.png',
                        'conference' => $teamData['team']['groups']['parent']['id'] === '5' ? 'Eastern' : 'Western', 
                        'division' => $teamData['team']['divisionName'] ?? 'Unknown',
                        'record' => $teamData['team']['record']['items'][0]['summary'] ?? '0-0',
                    ]
                ];
            }
        }
        
        $data = [
            'user' => $this->getUserData(),
            'favoriteTeams' => $favoriteTeams
        ];
        
        include __DIR__ . "/../views/user/favorite-teams.php";
    }

}
?>