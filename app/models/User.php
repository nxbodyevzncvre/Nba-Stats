<?php
require_once __DIR__ . "/../config/database.php";

class User {
    private $db;
    private $table = "users";

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function register($username, $password) {
        if ($this->findByUsername($username)) {
            return [
                'success' => false,
                'message' => 'User has already been registered'
            ];
        }

        $hashed_pass = password_hash($password, PASSWORD_BCRYPT);
        
        try {
            $sql = "INSERT INTO {$this->table} (username, password)
            VALUES (:username, :password)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_pass);

            $stmt->execute();

            return [
                'success' => true,
                'user_id' => $this->db->lastInsertId(),
                'message' => 'You have been registered successfully!'
            ];
        } catch (PDOException $err) {
            return [
                'success' => false,
                'message' => "Something went wrong, try again!"
            ];
        }
    }

    public function login($username, $password) {
        $user = $this->findByUsername($username);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found!'
            ];
        }

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

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
}
?>