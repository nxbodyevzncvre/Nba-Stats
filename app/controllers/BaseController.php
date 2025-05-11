<?php
require_once __DIR__ . "/../models/User.php";

class BaseController {
    protected $userModel;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new User();
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']) && 
            $this->userModel->isValidSession($_SESSION['user_id']);
    }

    protected function getUserData() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'] ?? 'not found',
                'isLoggedIn' => true
            ];
        }

        return [
            'id' => null,
            'username' => null,
            'isLoggedIn' => false
        ];
    }

    protected function getFullUserData() {
        if ($this->isLoggedIn()) {
            return $this->userModel->findById($_SESSION['user_id']);
        }
        return null;
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            header("Location: /fin_proj/login");
            exit;
        }
    }
}
?>