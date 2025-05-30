<?php
session_start();

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function($class) {
    $paths = [
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/views/',
        BASE_PATH . '/app/controllers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$url = parse_url($request_uri, PHP_URL_PATH);

switch ($url) {
    case '/fin_proj/register':
        $controller = new AuthController();
        $controller->register();
        break;

    case '/fin_proj/login':
        $controller = new AuthController();
        $controller->login();
        break;

    case '/fin_proj/':
        $controller = new HomeController();
        $controller->home();
        break;

    case '/fin_proj/home':
        $controller = new HomeController();
        $controller->home();
        break;

    case '/fin_proj/logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case '/fin_proj/players':
        $controller = new PlayerController();
        $controller->index();
        break;

    case '/fin_proj/players/search':
        $controller = new PlayerController();
        $controller->getAllPlayersJson();
        break;
    
    case '/fin_proj/teams':
        $controller = new TeamController();
        $controller->index();
        break;

    case (preg_match('/^\/fin_proj\/team\/(\d+)$/', $url, $matches) ? true : false):
        $id = $matches[1];
        $controller = new TeamController();
        $controller->show($id);
        break;

    case '/fin_proj/favorites/teams':
        $controller = new UserController();
        if (isset($_SESSION['user_id'])) {
            $controller->favoriteTeams();
        } else {

            header('Location: /fin_proj/login');
            exit;
        }
        break;

    case '/fin_proj/team/toggle-favorite':
        if ($request_method === 'POST') {
            $controller = new TeamController();
            $controller->toggleFavorite();
        } else {
            header('HTTP/1.0 405 Method Not Allowed');
            echo json_encode(['error' => 'Method Not Allowed']);
        }
        break;


    case '/fin_proj/admin':
        $controller = new AdminController();
        if (isset($_SESSION['user_id'])) {
            $controller->userList();
        } else {
            header('Location: /fin_proj/login');
            exit;
        }
        break;

    case (preg_match('/^\/fin_proj\/admin\/edit\/(\d+)$/', $url, $matches) ? true : false):
        $userId = $matches[1];
        $controller = new AdminController();
        if (isset($_SESSION['user_id'])) {
            $controller->editUser($userId);
        } else {
            header('Location: /fin_proj/login');
            exit;
        }
        break;

    case (preg_match('/^\/fin_proj\/admin\/update\/(\d+)$/', $url, $matches) ? true : false):
        $userId = $matches[1];
        $controller = new AdminController();
        if (isset($_SESSION['user_id'])) {
            $controller->updateUser($userId);
        } else {
            header('Location: /fin_proj/login');
            exit;
        }
        break;


        // /fin_proj/admin/update/{id}

    case (preg_match('/^\/fin_proj\/admin\/delete\/(\d+)$/', $url, $matches) ? true : false):
        $userId = $matches[1];
        $controller = new AdminController();
        if (isset($_SESSION['user_id'])) {
            $controller->deleteUser($userId);
        } else {
            header('Location: /fin_proj/login');
            exit;
        }
        break;


    default:
        header('HTTP/1.0 404 Not Found');
        echo "404 Not Found: {$url}";
        break;
}
?>