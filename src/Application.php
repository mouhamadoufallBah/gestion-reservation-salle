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
        try {
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
                    http_response_code(404);
                    \App\View\View::getInstance()->renderView('errors/404', [
                        'message' => 'La page demandée est introuvable.'
                    ]);
                    return;

                case Dispatcher::METHOD_NOT_ALLOWED:
                    http_response_code(405);
                    header(
                        'Allow: ' . implode(', ', $routeInfo[1])
                    );
                    \App\View\View::getInstance()->renderView('errors/405', [
                        'allowedMethods' => $routeInfo[1]
                    ]);
                    return;

                case Dispatcher::FOUND:
                    $handler = $routeInfo[1];
                    $vars = $routeInfo[2];
                    $this->dispatch($handler, $vars);
                    return;
            }
        } catch (\App\Exception\SalleIntrouvableException|\App\Exception\ReservationIntrouvableException $e) {
            http_response_code(404);
            \App\View\View::getInstance()->renderView('errors/404', [
                'message' => $e->getMessage()
            ]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log((string) $e);
            \App\View\View::getInstance()->renderView('errors/500', [
                'message' => 'Une erreur interne est survenue sur le serveur.',
                'details' => $e->getMessage(),
            ]);
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
