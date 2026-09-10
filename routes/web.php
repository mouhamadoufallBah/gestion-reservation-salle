<?php
use App\Controller\ISalleController;
use App\Controller\IReservationController;
use FastRoute\RouteCollector;

return static function (RouteCollector $routes): void {
    $routes->addRoute('GET', '/', [ISalleController::class, 'index']);
    $routes->addRoute('GET', '/salles', [ISalleController::class, 'index']);
    $routes->addRoute('GET', '/salles/create', [ISalleController::class, 'create']);
    $routes->addRoute('POST', '/salles', [ISalleController::class, 'store']);
    $routes->addRoute('GET', '/salles/{id:\\d+}', [ISalleController::class, 'show']);
    $routes->addRoute('GET', '/salles/{id:\\d+}/edit', [ISalleController::class, 'edit']);
    $routes->addRoute('POST', '/salles/{id:\\d+}/edit', [ISalleController::class, 'update']);

    $routes->addRoute('GET', '/reservations', [IReservationController::class, 'index']);
    $routes->addRoute('GET', '/reservations/create', [IReservationController::class, 'create']);
    $routes->addRoute('POST', '/reservations', [IReservationController::class, 'store']);
    $routes->addRoute('GET', '/reservations/{id:\\d+}', [IReservationController::class, 'show']);
    $routes->addRoute('GET', '/reservations/{id:\\d+}/edit', [IReservationController::class, 'edit']);
    $routes->addRoute('POST', '/reservations/{id:\\d+}/edit', [IReservationController::class, 'update']);
    $routes->addRoute('POST', '/reservations/{id:\\d+}/cancel', [IReservationController::class, 'cancel']);
};