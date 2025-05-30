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
            'users' => $users,
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

    public function editUser($userId) {
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            $_SESSION['error_message'] = "User not found.";
            header("Location: /fin_proj/admin");
            exit;
        }

        $data = [
            'user' => [
                'isLoggedIn' => isset($_SESSION['user_id']),
                'username' => $this->userModel->getUsername($_SESSION['user_id'] ?? 0) ?: 'Guest',
                'isAdmin' => $this->isAdmin()
            ],
            'editUser' => $user
        ];

        include __DIR__ . '/../views/admin/edit-user.php';
    }


    public function updateUser($userId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /fin_proj/admin");
            exit;
        }


        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Валидация
        if (empty($username)) {
            $_SESSION['error_message'] = "Username is required.";
            header("Location: /fin_proj/admin/edit/$userId");
            exit;
        }



        $passwordToUpdate = empty($password) ? null : $password;


        $result = $this->userModel->updateUser($userId, $username, $passwordToUpdate);

        if ($result['success']) {
            $_SESSION['success_message'] = $result['message'];
            header("Location: /fin_proj/admin");
        } else {
            $_SESSION['error_message'] = $result['message'];
            header("Location: /fin_proj/admin/edit/$userId");
        }
        exit;
    }
}
?>