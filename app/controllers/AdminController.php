<?php
require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/BaseController.php";

class AdminController extends BaseController {
    protected $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new User();

        if (!$this->isAdmin()) {
            header("Location: /fin_proj/");
            exit;
        }
    }

    private function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        return $this->userModel->isAdmin($_SESSION['user_id']);
    }

    public function userList() {
        $users = $this->userModel->getAllUsers();
        

        $data = [
            'user' => [
                'isLoggedIn' => isset($_SESSION['user_id']),
                'username' => $this->userModel->getUsername($_SESSION['user_id'] ?? 0) ?: 'Guest',
                'isAdmin' => $this->isAdmin()
            ]
        ];

        include __DIR__ . '/../views/admin/users.php';
    }

    public function deleteUser($userId) {
        if ($userId == $_SESSION['user_id']) {
            $_SESSION['error_message'] = "You cannot delete yourself.";
            header("Location: /fin_proj/admin");
            exit;
        }
        if ($this->userModel->deleteUser($userId)) {
            $_SESSION['success_message'] = "User deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Error deleting user.";
        }
        header("Location: /fin_proj/admin");
        exit;
    }
}
?>