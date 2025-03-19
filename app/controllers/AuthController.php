<?php
require_once __DIR__ . "/../models/User.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: /home");
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->regProcess();
            return;
        }

        require_once __DIR__ . '/../views/auth/auth.php';
    }

    private function regProcess() {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        $errors = [];

        if (empty($username)) {
            $errors['username'] = "Username is required";
        }

        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password should have more than 6 symbols';
        }

        if (empty($confirm_password)) {
            $errors['confirm_password'] = "Password confirmation is required";
        } elseif (strlen($confirm_password) < 6) {
            $errors['confirm_password'] = "Password confirmation should have more than 6 symbols";
        }

        if ($password != $confirm_password) {
            $errors['confirm_password'] = "Passwords are not equal";
        }

        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = [
                'username' => $username,
            ];

            header('Location: /fin_proj/register'); 
            exit;
        }

        $result = $this->userModel->register($username, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $username;

            header('Location: /home');
            exit;
        } else {
            $_SESSION['register_errors'] = ['general' => $result['message']];
            $_SESSION['register_data'] = [
                'username' => $username,
            ];

            header('Location: /fin_proj/register');
            exit;
        }
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: /home'); 
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->logProcess();
            return;
        }
        
        require_once __DIR__ . '/../views/auth/auth.php'; 
    }

    private function logProcess() {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $errors = [];

        if (empty($username)) {
            $errors['username'] = 'Username should be filled';
        }

        if (empty($password)) {
            $errors['password'] = 'Password should be filled';
        }

        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            $_SESSION['login_data'] = [
                'username' => $username,
            ];

            header('Location: /fin_proj/login'); 
            exit;
        }

        $result = $this->userModel->login($username, $password);

        if ($result['success']) {
            header('Location: /home');
            exit;
        } else {
            $_SESSION['login_errors'] = ['general' => $result['message']];
            $_SESSION['login_data'] = ['username' => $username];

            header('Location: /fin_proj/login'); 
            exit;
        }
    }
}
?>
