<?php
// 1. namespace
namespace guestbook;

session_start();

// 2. require_once controllers
require_once 'Controllers/HomeController.php';
require_once 'Controllers/RegisterController.php';
require_once 'Controllers/LoginController.php';
require_once 'Controllers/AdminController.php';
require_once 'Controllers/LogoutController.php';
require_once 'Controllers/GuestbookController.php'; 

// 3. ROUTING
$uri = str_replace('/guestbookOopMvcBase-main', '', $_SERVER['REQUEST_URI']);
switch ($uri) {
    case '/':
        $controllerClassName = 'HomeController';
        break;
    case '/register':
        $controllerClassName = 'RegisterController';
        break;
    case '/login':
        $controllerClassName = 'LoginController';
        break;
    case '/logout':
        $controllerClassName = 'LogoutController';
        break;
    case '/admin':
        $controllerClassName = 'AdminController';
        break;
    case '/guestbook': // <--- добавлено
        $controllerClassName = 'GuestbookController';
        break;
    default:
        echo 'Path not found.';
        die;
}

// 4. Визначаємо повне ім’я класу з простором імен
$controllerClassName = "guestbook\\Controllers\\$controllerClassName";

// 5. Викликаємо метод
$controller = new $controllerClassName();
$controller->execute();
