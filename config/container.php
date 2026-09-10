<?php

use App\Application;
use App\Controller\IReservationController;
use App\Controller\ISalleController;
use App\Controller\ReservationController;
use App\Controller\ReservationJsonController;
use App\Controller\SalleController;
use App\Controller\SalleJsonController;
use App\Exception\ExceptionHandler;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Service\AfficherReservationService;
use App\Service\AfficherSalleService;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Service\CreerSalleService;
use App\Service\FlashService;
use App\Service\ListerReservationsService;
use App\Service\ListerSallesService;
use App\Service\ModifierSalleService;
use App\Middleware\ExceptionMiddleware;
use App\Validation\AnnulationReservationValidator;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use App\View\View;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use Psr\Container\ContainerInterface;

use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;

$viewFormat = strtolower(
    trim(
        $_ENV['VIEW_FORMAT']
            ?? $_SERVER['VIEW_FORMAT']
            ?? getenv('VIEW_FORMAT')
            ?? 'html'
    )
);

$salleControllers = [
    'html' => SalleController::class,
    'json' => SalleJsonController::class,
];

$reservationControllers = [
    'html' => ReservationController::class,
    'json' => ReservationJsonController::class,
];

$selectedClasseController =
    $salleControllers[$viewFormat] ?? $salleControllers['html'];

$selectedReservationController =
    $reservationControllers[$viewFormat] ?? $reservationControllers['html'];

return [

    SalleRepositoryInterface::class =>
    autowire(SalleRepository::class),

    ReservationRepositoryInterface::class =>
    autowire(ReservationRepository::class),

    Capsule::class =>
    factory(function (): Capsule {
        $capsule = new Capsule();

        require_once dirname(__DIR__) . "/config/database.php";

        $capsule->addConnection([
            'driver'    => $_ENV['DB_DRIVER'],
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'port'      => $_ENV['DB_PORT'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        return $capsule;
    }),

    View::class =>
    factory(function (ContainerInterface $container) {
        return View::getInstance();
    }),

    ExceptionHandler::class => factory(function () use ($viewFormat): ExceptionHandler {
        return new ExceptionHandler(
            viewFormat: $viewFormat
        );
    }),

    ExceptionMiddleware::class =>
    autowire(),

    Dispatcher::class =>
    factory(function (): Dispatcher {
        return simpleDispatcher(
            require BASE_PATH . '/routes/web.php'
        );
    }),

    FlashService::class =>
    autowire(),

    ReservationValidator::class =>
    autowire(),

    AnnulationReservationValidator::class =>
    autowire(),

    CreerSalleService::class =>
    autowire(),

    ModifierSalleService::class =>
    autowire(),

    AfficherSalleService::class =>
    autowire(),

    ListerSallesService::class =>
    autowire(),

    CreerReservationService::class =>
    autowire(),

    AnnulerReservationService::class =>
    autowire(),

    ListerReservationsService::class =>
    autowire(),

    AfficherReservationService::class =>
    autowire(),

    ISalleController::class =>
    autowire($selectedClasseController),

    IReservationController::class =>
    autowire($selectedReservationController),

    Application::class =>
    autowire(),
];
