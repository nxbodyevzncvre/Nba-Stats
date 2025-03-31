<?php
require_once __DIR__ . "/../config/database.php";

class PlayerModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getUserFavoritePlayers($userId) {
        try {
            $sql = "SELECT player_id FROM favorite_players WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
            return $result;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function toggleFavoritePlayer($userId, $playerId) {
        try {
            $checkSql = "SELECT id FROM favorite_players WHERE user_id = :user_id AND player_id = :player_id";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':player_id', $playerId, PDO::PARAM_STR);
            $checkStmt->execute();
            
            $exists = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($exists) {
                $deleteSql = "DELETE FROM favorite_players WHERE user_id = :user_id AND player_id = :player_id";
                $deleteStmt = $this->db->prepare($deleteSql);
                $deleteStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $deleteStmt->bindParam(':player_id', $playerId, PDO::PARAM_STR);
                $deleteStmt->execute();
                
                return [
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Player removed from favorites'
                ];
            } else {
                $insertSql = "INSERT INTO favorite_players (user_id, player_id) VALUES (:user_id, :player_id)";
                $insertStmt = $this->db->prepare($insertSql);
                $insertStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
                $insertStmt->bindParam(':player_id', $playerId, PDO::PARAM_STR);
                $insertStmt->execute();
                
                return [
                    'success' => true,
                    'action' => 'added',
                    'message' => 'Player added to favorites'
                ];
            }
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
    
    public function isPlayerFavorite($userId, $playerId) {
        try {
            $sql = "SELECT id FROM favorite_players WHERE user_id = :user_id AND player_id = :player_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':player_id', $playerId, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>