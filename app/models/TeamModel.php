<?php
require_once __DIR__ . "/../config/database.php";

class TeamModel{

    private $db;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();

    }

    public function getUserFavoriteTeams($userId){
        try{
            $sql = "SELECT team_id FROM favorite_teams WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result;

        }catch(PDOException $e){
            return [];
        }
    }

    public function toggleFavoriteTeam($userId, $teamId){
        try{
            $checkSql = "SELECT id FROM favorite_teams WHERE user_id = :user_id AND team_id = :team_id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(":team_id", $teamId, PDO::PARAM_STR);
            $checkStmt->execute();

            
            $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if($exists){
                $deleteSql = "DELETE FROM favorite_teams WHERE user_id = :user_id AND team_id = :team_id"; 
                $deleteStmt = $this->db->prepare($deleteSql);
                $deleteStmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
                $deleteStmt->bindParam(":team_id", $teamId, PDO::PARAM_STR);
                $deleteStmt->execute();

                return[
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Team was deleted successfully'
                ];
            
            }else{
                $insertSql = "INSERT INTO favorite_teams(user_id, team_id) VALUES(:user_id, :team_id)";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bindParam(":user_id", $userId);
                $insertStmt->bindParam(":team_id", $teamId);
                $insertStmt->execute();

                return[
                    'success' => true,
                    'action' => "added",
                    'message' => 'Team was added successfully'
                ];


            }
        }catch(PDOException $e){
            return[
                'sucess' => false,
                'message' => 'Error with db ' . $e->getMessage()
            ];
        }

    }
    public function isTeamFavorite($userId, $teamId){
        try{
            $sql = "SELECT id FROM favorite_teams WHERE user_id = :user_id AND team_id = :team_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":user_id", $userId, PDO::PARAM_INT);
            $stmt->bindParam(":team_id", $teamId, PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;

        }catch(PDOException $e){
            return false;
        }
    }


}

?>