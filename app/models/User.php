<?php
require_once __DIR__ . "/../config/database.php";

class User {
    private $db;
    private $table = "users";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $password, $isAdmin = 0) {
        if ($this->findByUsername($username)) {
            return [
                'success' => false, 
                'message' => 'User already exists'
            ];
        }
    
        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
    
        try {
            $sql = "INSERT INTO {$this->table} (username, password, is_admin) VALUES (:username, :password, :is_admin)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_pass);
            $stmt->bindParam(':is_admin', $isAdmin, PDO::PARAM_INT);
    
            $stmt->execute();
    
            return ['success' => true, 'message' => 'User registered successfully!'];
        } catch (PDOException $err) {
            return ['success' => false, 'message' => 'Something went wrong!'];
        }
    }
    

    public function login($username, $password) {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    
        $user = $this->findByUsername($username);
    
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found!'
            ];
        }
    
        if (password_verify($password, $user['password'])) {

            session_regenerate_id();
    
            $this->createUserSession([
                'id' => $user['id'],
                'username' => $user['username']
            ]);
    
            return [
                'success' => true,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username']
                ]
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Wrong password!'
            ];
        }
    }
    
    
    public function isAdmin($userId) {
        try {
            $stmt = $this->db->prepare("SELECT is_admin FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$userId]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return ($user && $user['is_admin'] == 1);
        } catch (PDOException $e) {
            return false;
        }
    }
    public function getAllUsers() {
        try {
            $stmt = $this->db->prepare("SELECT id, username, is_admin, created_at FROM users");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    public function deleteUser($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            return false;
        }
    }
    

    
    public function isValidSession($userId) {
        $query = "SELECT id FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }



    public function logout() {
        $_SESSION = [];
        session_destroy();
        return true;
    }


    public function createUserSession($user){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    }

    public function getUserFromSession(){
        if(isset($_SESSION['user_id'])){
            return $this->findById($_SESSION['user_id']);
        }
        return null;
    }


    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            return null;
        }
    }

    public function findById($id) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            return null;
        }
    }
    public function getUsername($userId) {
        $user = $this->findById($userId);
        return $user['username'] ?? null;
    }

    public function getFavoriteTeamIds($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT team_id 
                FROM favorite_teams 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            
            $teamIds = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $teamIds[] = $row['team_id'];
            }
            
            return $teamIds;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }
    public function getFavoritePlayerIds($userId) {
        try {
            $stmt = $this->db->prepare("
                SELECT player_id 
                FROM favorite_players 
                WHERE user_id = ?
            ");
            $stmt->execute([$userId]);
            
            $playerIds = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $playerIds[] = $row['player_id'];
            }
            
            return $playerIds;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return [];
        }
    }


   
    public function toggleFavoritePlayer($userId, $playerId) {
        try {
            $stmt = $this->db->prepare("
                SELECT id FROM favorite_players 
                WHERE user_id = ? AND player_id = ?
            ");
            $stmt->execute([$userId, $playerId]);
            $favorite = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($favorite) {
                $stmt = $this->db->prepare("
                    DELETE FROM favorite_players 
                    WHERE user_id = ? AND player_id = ?
                ");
                $stmt->execute([$userId, $playerId]);
                return ['success' => true, 'action' => 'removed'];
            } else {
                $stmt = $this->db->prepare("
                    INSERT INTO favorite_players (user_id, player_id) 
                    VALUES (?, ?)
                ");
                $stmt->execute([$userId, $playerId]);
                return ['success' => true, 'action' => 'added'];
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Database error'];
        }
    }

}
?>