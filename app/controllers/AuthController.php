<?php
require_once __DIR__ . "/BaseController.php";

class AuthController extends BaseController{

    public function register() {
        if ($this->isLoggedIn()) {
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
            $this->userModel->createUserSession([
                'id' => $result['user_id'],
                'username' => $username
            ]);
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


        if ($this->isLoggedIn()) {
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
        $this->userModel->logout();
        header("Location: /fin_proj/home");
        exit;
    }
}
