<?php
class EspnApi{
    private $baseUrl = 'https://site.api.espn.com/apis/site/v2/sports/basketball/nba';
    private $newsUrl = 'https://site.api.espn.com/apis/site/v2/sports/basketball/nba/news';
    private $teamsUrl = 'https://site.api.espn.com/apis/site/v2/sports/basketball/nba/teams';
    private $scoresUrl = 'https://site.api.espn.com/apis/site/v2/sports/basketball/nba/scoreboard';


    private function makeRequest($url, $params = []){
        if (!empty($params)){
            $url .= '?' . http_build_query($params);

        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 
        'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36');

        $response = curl_exec($ch);
        $error = curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);


        if ($error){
            throw new Exception("cURL Error: $error");
        }
        
        if($httpCode != 200){
            throw new Exception ("HTTP Error: $httpCode");

        }

        $data = json_decode($response, true);
        
        if(json_last_error() !== JSON_ERROR_NONE){
            throw new Exception("JSON Error: " . json_last_error_msg());

        }

        return $data;


    }


    public function getAllTeams(){
        try{
            $response = $this->makeRequest($this->teamsUrl);;
        
            if (!$response || !isset($response['sports'][0]['leagues'][0]['teams'])) {
                return [];
            }
            
            $teams = [];
            foreach ($response['sports'][0]['leagues'][0]['teams'] as $teamData) {
                $team = $teamData['team'];
                $teams[] = [
                    'team' => [
                        'id' => $team['id'],
                        'name' => $team['displayName'],
                        'abbreviation' => $team['abbreviation'],
                        'logo' => isset($team['logos']) && !empty($team['logos']) ? 
                            $team['logos'][0]['href'] : '/fin_proj/public/images/team-default.png',
                        'conference' => isset($team['conferenceId']) ? 
                            ($team['conferenceId'] == 1 ? 'Eastern' : 'Western') : 'Unknown',
                        'division' => $team['divisionName'] ?? 'Unknown'
                    ]
                ];
            }
            
            return $teams;

        }catch(Exception $e){
            error_log("Error getting team: " . $e->getMessage());
            return [];
        }


    }


    public function getTeamById($id) {
        try {
            $url = $this->teamsUrl . '/' . $id;
            $result = $this->makeRequest($url);
            error_log("API Response for team $id: " . json_encode($result));
            return $result;
        } catch (Exception $e) {
            error_log("Error getting team by id $id: " . $e->getMessage());
            return null;
        }
    }
    

    public function getTeamPlayers($teamId) {
        try {
            $url = $this->teamsUrl . '/' . $teamId . '/roster';
            $data = $this->makeRequest($url);
            
            $roster = [];
            if (isset($data['athletes'])) {
                foreach ($data['athletes'] as $athlete) {
                    $player = [
                        'id' => $athlete['id'],
                        'firstName' => $athlete['firstName'] ?? '',
                        'lastName' => $athlete['lastName'] ?? '',
                        'fullName' => $athlete['fullName'] ?? ($athlete['firstName'] . ' ' . $athlete['lastName']),
                        'position' => isset($athlete['position']['displayName']) ? $athlete['position']['displayName'] : 'N/A',
                        'jersey' => $athlete['jersey'] ?? 'N/A',
                        'headshot' => isset($athlete['headshot']['href']) ? 
                            $athlete['headshot']['href'] : '/fin_proj/public/images/player-default.png',
                        'height' => isset($athlete['displayHeight']) ? $athlete['displayHeight'] : 'N/A',
                        'weight' => isset($athlete['displayWeight']) ? $athlete['displayWeight'] : 'N/A',
                        'age' => $athlete['age'] ?? 'N/A',
                        'experience' => isset($athlete['experience']['years']) ? $athlete['experience']['years'] : 'N/A'
                    ];
                    
                    $roster[] = $player;
                }
            }
            
            return $roster;
        } catch (Exception $e) {
            error_log("Error getting team players: " . $e->getMessage());
            return [];
        }
    }

    public function getPlayerById($playerId) {
        try {
            $allTeams = $this->getAllTeams();
            
            foreach ($allTeams as $teamData) {
                $teamId = $teamData['team']['id'];
                $teamName = $teamData['team']['name'];
                $teamLogo = $teamData['team']['logo'];
                
                $roster = $this->getTeamPlayers($teamId);
                
                foreach ($roster as $player) {
                    if ($player['id'] == $playerId) {
                        $player['teamId'] = $teamId;
                        $player['teamName'] = $teamName;
                        $player['teamLogo'] = $teamLogo;
                        
                        return $player;
                    }
                }
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Error getting player data: " . $e->getMessage());
            return null;
        }
    }



    public function getLatestNews($limit = 10){
        try{
            $params = ['limit' => $limit];
            $data = $this->makeRequest($this->newsUrl, $params);
            return $data['articles'] ?? '';

        } catch (Exception $e) {
            error_log("Error getting latest news: " . $e->getMessage());
            return [];
        }


    }




    public function getRecentResults($limit = 10){
        try{
            $params = [
                'limit' => $limit,
                'dates' => date('Ymd', strtotime('-7 days')) . '-' . date('Ymd', strtotime('-1 day'))
            ];
            $data = $this->makeRequest($this->scoresUrl);
            return $data['events'] ?? [];

        }catch(Exception $e){
            error_log("Error getting recent results: " . $e->getMessage());
            return [];
        }
    }

    public function getUpcomingGames($limit = 10){
        try{
            $params = [
                'limit' => $limit,
                'dates' => date('Ymd') . '-' . date('Ymd', strtotime('+7 day'))
            ];
            $data = $this->makeRequest($this->scoresUrl);
            return $data['events'] ?? [];
        }catch(Exception $e){
            error_log("Error getting upcoming games: " . $e->getMessage());
            return [];
        }
    }
    



    private function parsePlayerStats($category) {
        $stats = [];
        
        if (!isset($category['splits'])) {
            return $stats;
        }
        
        foreach ($category['splits'] as $split) {
            if (!isset($split['stats'])) {
                continue;
            }
            
            foreach ($split['stats'] as $stat) {
                $stats[$stat['name']] = [
                    'value' => $stat['value'],
                    'displayValue' => $stat['displayValue'] ?? $stat['value']
                ];
            }
        }
        
        return $stats;
    }
    public function getTeamRecentGames($teamId, $limit = 5) {
        try {
            $url = $this->teamsUrl . '/' . $teamId . '/schedule';
            $data = $this->makeRequest($url);

            $recentGames = [];
            if (isset($data['events'])) {
                foreach ($data['events'] as $event) {
                    if (count($recentGames) >= $limit) {
                        break;
                    }

                    if (isset($event['status']['type']['completed']) && $event['status']['type']['completed']) {
                        $recentGames[] = [
                            'id' => $event['id'],
                            'date' => $event['date'],
                            'name' => $event['name'],
                            'shortName' => $event['shortName'],
                            'status' => $event['status']['type']['description'] ?? 'Completed',
                            'score' => $event['competitions'][0]['competitors'] ?? []
                        ];
                    }
                }
            }

            return $recentGames;
        } catch (Exception $e) {
            error_log("Error getting team recent games: " . $e->getMessage());
            return [];
        }
    }

    public function getTeamUpcomingGames($teamId, $limit = 5) {
        try {
            $url = $this->teamsUrl . '/' . $teamId . '/schedule';
            $data = $this->makeRequest($url);

            $upcomingGames = [];
            if (isset($data['events'])) {
                foreach ($data['events'] as $event) {
                    if (count($upcomingGames) >= $limit) {
                        break;
                    }

                    if (isset($event['status']['type']['completed']) && !$event['status']['type']['completed']) {
                        $upcomingGames[] = [
                            'id' => $event['id'],
                            'date' => $event['date'],
                            'name' => $event['name'],
                            'shortName' => $event['shortName'],
                            'status' => $event['status']['type']['description'] ?? 'Scheduled',
                            'competitors' => $event['competitions'][0]['competitors'] ?? []
                        ];
                    }
                }
            }

            return $upcomingGames;
        } catch (Exception $e) {
            error_log("Error getting team upcoming games: " . $e->getMessage());
            return [];
        }
    }



    
}
?>