<?php
session_start();

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function($class){
    $paths = [
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/views/',
        BASE_PATH . '/app/controllers/',
    ];
    
    foreach($paths as $path){
        $file = $path . $class . '.php';
        if (file_exists($file)){
            require_once $file;
            return;
        }
    }
});

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

$url = parse_url($request_uri, PHP_URL_PATH);

switch($url){
    case '/fin_proj/register':
        $controller = new AuthController();
        $controller->register();
        break;

    case '/fin_proj/login':
        $controller = new AuthController();
        $controller->login();
        break;

    case '/fin_proj/home':
        $controller = new HomeController();
        $controller -> home();
        break;
    
    default:
        header('HTTP/1.0 404 NOT FOUND');
        echo "404 NOT FOUND {$url}";
        break;
}
?>