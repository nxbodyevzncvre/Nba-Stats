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
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $err) {
            error_log("Error in findById: " . $err->getMessage());
            return null;
        }
    }

    public function findByUsername($username) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $err) {
            error_log("Error in findByUsername: " . $err->getMessage());
            return null;
        }
    }

    public function getUsername($userId) {
        if (!$userId) return null;
        
        $user = $this->findById($userId);
        return $user['username'] ?? null;
    }

    public function isAdmin($userId) {
        if (!$userId) return false;
        
        $user = $this->findById($userId);
        return $user && isset($user['is_admin']) && $user['is_admin'] == 1;
    }

    public function getAllUsers() {
        try {
            $sql = "SELECT id, username, is_admin, created_at FROM {$this->table} ORDER BY username ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $err) {
            error_log("Error in getAllUsers: " . $err->getMessage());
            return [];
        }
    }

    public function deleteUser($userId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $err) {
            error_log("Error in deleteUser: " . $err->getMessage());
            return false;
        }
    }


    public function updateUser($userId, $username, $password = null, $isAdmin = null) {
        try {
            $existingUser = $this->findByUsername($username);
            if ($existingUser && $existingUser['id'] != $userId) {
                return [
                    'success' => false,
                    'message' => 'Username already exists'
                ];
            }

            $sql = "UPDATE {$this->table} SET username = :username";
            if ($password !== null && trim($password) !== '') {
                $sql .= ", password = :password";
            }
            if ($isAdmin !== null) {
                $sql .= ", is_admin = :is_admin";
            }
            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);

            if ($password !== null && trim($password) !== '') {
                $stmt->bindValue(':password', password_hash(trim($password), PASSWORD_BCRYPT), PDO::PARAM_STR);
            }
            if ($isAdmin !== null) {
                $stmt->bindValue(':is_admin', (int)$isAdmin, PDO::PARAM_INT);
            }

            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'User updated successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Failed to update user'
                ];
            }
        } catch (PDOException $err) {
            error_log("Error in updateUser: " . $err->getMessage());
            return [
                'success' => false,
                'message' => 'Database error occurred'
            ];
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
            error_log("Error in register: " . $err->getMessage());
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
        } catch (PDOException $err) {
            error_log("Database error: " . $err->getMessage());
            return [];
        }
    }
}
?>