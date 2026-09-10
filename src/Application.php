<?php

declare(strict_types=1);

namespace App;

use App\Exception\MethodNotAllowedException;
use App\Exception\RouteNotFoundException;
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
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?: '/';

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $routeInfo = $this->dispatcher->dispatch(
            $method,
            $uri
        );

        switch ($routeInfo[0]) {

            case Dispatcher::NOT_FOUND:
                throw new RouteNotFoundException(
                    'La page demandée est introuvable.'
                );

            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException(
                    'La méthode HTTP utilisée n’est pas autorisée pour cette route.',
                    $routeInfo[1]
                );

            case Dispatcher::FOUND:
                $this->dispatch(
                    $routeInfo[1],
                    $routeInfo[2]
                );

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