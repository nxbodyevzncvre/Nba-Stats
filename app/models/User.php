<?php
require_once __DIR__ . "/../config/database.php";
class User {
    protected $db;
    protected $table = 'users';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
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

    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username);
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

    public function isAdmin($userId) {
        $user = $this->findById($userId);
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }

    public function getAllUsers() {
        try {
            $sql = "SELECT * FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            return [];
        }
    }

    public function deleteUser($userId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $userId);
            return $stmt->execute();
        } catch (PDOException $err) {
            return false;
        }
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
    
            $user = $this->findByUsername($username);
            if ($user) {

                if (session_status() !== PHP_SESSION_ACTIVE) {
                    session_start();
                }
                session_regenerate_id();
                $this->createUserSession([
                    'id' => $user['id'],
                    'username' => $user['username']
                ]);
            }
            return [
                'success' => true, 
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username']
                ]
            ];
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

    public function logout() {
        $_SESSION = [];
        session_destroy();
        return true;
    }

    
    private function createUserSession($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
    }

    public function isValidSession($userId) {
        $query = "SELECT id FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
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
    

 
}
?>