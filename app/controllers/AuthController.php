<?php
require_once __DIR__ . "/../models/User.php";

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if (isset($_SESSION['user_id'])) {
            header("Location: /fin_proj/home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
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
            $errors['password'] = "Password is required";
        } elseif (strlen($password) < 6) {
            $errors['password'] = "Password must be at least 6 characters";
        }

        if ($password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match";
        }

        if (!empty($errors)) {
            $_SESSION['register_errors'] = $errors;
            $_SESSION['register_data'] = ['username' => $username];
            header("Location: /fin_proj/register");
            exit;
        }

        $result = $this->userModel->register($username, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['username'] = $username;
            header("Location: /fin_proj/home");
            exit;
        } else {
            $_SESSION['register_errors'] = ['general' => $result['message']];
            $_SESSION['register_data'] = ['username' => $username];
            header("Location: /fin_proj/register");
            exit;
        }
    }

    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            session_unset();
        }


        if (isset($_SESSION['user_id']) && $this->userModel->isValidSession($_SESSION['user_id'])) {
            header("Location: /fin_proj/home");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
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
            $errors['username'] = "Username is required";
        }

        if (empty($password)) {
            $errors['password'] = "Password is required";
        }

        if (!empty($errors)) {
            $_SESSION['login_errors'] = $errors;
            $_SESSION['login_data'] = ['username' => $username];
            header("Location: /fin_proj/login");
            exit;
        }

        $result = $this->userModel->login($username, $password);

        if ($result['success']) {
            $_SESSION['user_id'] = $result['user']['id'];
            $_SESSION['username'] = $result['user']['username'];
            header("Location: /fin_proj/home");
            exit;
        } else {
            $_SESSION['login_errors'] = ['general' => $result['message']];
            $_SESSION['login_data'] = ['username' => $username];
            header("Location: /fin_proj/login");
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /fin_proj/login");
        exit;
    }
}
