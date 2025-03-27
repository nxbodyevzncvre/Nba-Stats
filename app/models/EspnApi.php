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
            $data = $this->makeRequest($this->teamsUrl);
            return $data['sports'][0]['leagues'][0]['teams'] ?? [];

        }catch(Exception $e){
            error_log("Error getting team: " . $e->getMessage());
            return [];
        }


    }


    public function getTeamById($id){
        try{
            $url = $this->teamsUrl . '/' . $id;
            return $this->makeRequest($url);

        }catch(Exception $e){
            error_log("Error getting team by id: " . $e->getMessage());
            return null;
        }
    }

    public function getTeamByAbbr($abbr){
        try{
            $teams = $this->getAllTeams();
            foreach($teams as $team){
                if (strtoupper($team['team']['abbreviation']) === strtoupper($abbr)) {
                    return $team['team'];
                }
            }

            return null;
        }catch(Exception  $e){
            error_log("Error getting team by abbr: " . $e -> getMessage());
            return null;
        }
    }

    public function getTeamPlayers($teamId){
        try{
            $url = $this->teamsUrl . '/' . $teamId . '/roster';
            $data = $this -> makeRequest($url);
            return $data;
        }catch(Exception $e){
            error_log("Error getting team players: " .$e -> getMessage());
            return [];
        }
    }

    public function getPlayerById($playerId){
        try{
            $url = $this->baseUrl . '/athletes/' . $playerId;
            return $this->makeRequest($url);
        }catch(Exception $e){
            error_log("Error getting player by id: " . $e->getMessage());
            return null;

        }
    }

    public function getPlayerStats($playerId){
        try{
            $url = $this->baseUrl . '/athletes/' . $playerId . '/stats';
            return $this->makeRequest($url);

        }catch(Exception $e){
            error_log("Error getting player stats: " . $e->getMessage());
            return [];
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

    public function getTeamNews($teamId, $limit = 10){
        try {
            $url = $this->teamsUrl . '/' . $teamId . '/news';
            $params = ['limit' => $limit];
            $data = $this->makeRequest($url, $params);
            return $data['articles'] ?? [];
        } catch (Exception $e) {
            error_log("Error getting team news: " . $e->getMessage());
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
    

}
?>