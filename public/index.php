<?php
session_start();
define('APPLICATION_PATH', getcwd() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR);
define('PUBLIC_PATH', getcwd());
define('DEBUG', true);

require APPLICATION_PATH . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
//Загружаем конфигурацию бд
new \App\Core\Config();

$routes = explode('/', $_SERVER['REQUEST_URI']);
$controller_name = "Main";
$action_name = 'DefaultPage';
$data = 0;

// получаем название контроллера
if (!empty($routes[1])) {
    $controller_name = $routes[1];
}

// получаем название действия
if (!empty($routes[2])) {
    $action_name = $routes[2];
}

if (!empty($routes[3])) {
    $data = $routes[3];
}

//получаем имя файла контроллера
$filename = APPLICATION_PATH . "controllers" . DIRECTORY_SEPARATOR . ucfirst($controller_name) . ".php";
//если файл существует то подключаем его
try {
    if (file_exists($filename)) {
        require_once $filename;
    } else {
        throw new Exception("Controller file not found");
    }

    //получаем имя класса
    $class_name = '\App\\'.ucfirst($controller_name);

    //если класс существует создаем новый контроллер
    if (class_exists($class_name)) {
        $controller = new $class_name();
    } else {
        throw new Exception("Controller class not found");
    }

    //проверяем наличие метода в данном классе и если есть выполняем его
    if (method_exists($controller, $action_name)) {
        $controller->$action_name($data);
    } else {
        throw new Exception("Method not found");
    }
} catch (Exception $e) {
    // если появились исключения/ошибки показываем страницу 404
    require APPLICATION_PATH."errors/404.php";
}
