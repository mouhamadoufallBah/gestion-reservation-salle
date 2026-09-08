<?php

declare(strict_types=1);

namespace App;

use DI\Container;
use FastRoute\Dispatcher;


final class Application
{
    public function __construct(
        private Dispatcher $dispatcher,
        private Container $container,
    ) {}

    public function run(): void
    {
        $uri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );

        $method = $_SERVER['REQUEST_METHOD'];

        $routeInfo = $this->dispatcher->dispatch(
            $method,
            $uri
        );

        switch ($routeInfo[0]) {

            case Dispatcher::NOT_FOUND:

                http_response_code(404);

                require BASE_PATH . '/templates/errors/404.html.php';

                return;

            case Dispatcher::METHOD_NOT_ALLOWED:

                http_response_code(405);

                header(
                    'Allow: ' . implode(', ', $routeInfo[1])
                );

                require BASE_PATH . '/templates/errors/405.html.php';

                return;

            case Dispatcher::FOUND:

                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                $this->dispatch($handler, $vars);

                return;
        }
    }

    private function dispatch(
        array $handler,
        array $vars
    ): void {
        [$controllerClass, $action] = $handler;

        $controller = $this->container->get(
            $controllerClass
        );

        $controller->$action(...array_values($vars));
    }
}
