<?php

use App\Repository\Database;
use FastRoute\Dispatcher;

define('BASE_PATH', dirname(__DIR__));

require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$container = require BASE_PATH . '/config/container.php';

$dispatcher = FastRoute\simpleDispatcher(
    require BASE_PATH . '/routes/web.php'
);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routeInfo = $dispatcher->dispatch($method, $uri);

switch ($routeInfo[0]) {

    case Dispatcher::NOT_FOUND:

        http_response_code(404);

        require BASE_PATH . '/templates/errors/404.html.php';

        break;

    case Dispatcher::METHOD_NOT_ALLOWED:

        http_response_code(405);

        $allowedMethods = $routeInfo[1];

        header(
            'Allow: ' . implode(', ', $allowedMethods)
        );

        require BASE_PATH . '/templates/errors/405.html.php';

        break;

    case Dispatcher::FOUND:

        [$controllerClass, $action] = $routeInfo[1];

        $vars = $routeInfo[2];

        $controller = $container->get($controllerClass);

        $controller->$action(...array_values($vars));

        break;
}