<?php

use App\Application;
use App\Controller\ReservationController;
use App\Controller\SalleController;
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
use App\Validation\AnnulationReservationValidator;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;

return [
    SalleRepositoryInterface::class =>
    autowire(SalleRepository::class),

    ReservationRepositoryInterface::class =>
    autowire(ReservationRepository::class),

    Capsule::class =>
    factory(function (): Capsule {
        $capsule = new Capsule();

        require_once(dirname(__DIR__) . "/config/database.php");
        $capsule->addConnection([
            'driver'    => $_ENV['DB_DRIVER'],
            'host'      => $_ENV['DB_HOST'],
            'database'  => $_ENV['DB_DATABASE'],
            'port'  => $_ENV['DB_PORT'],
            'username'  => $_ENV['DB_USERNAME'],
            'password'  => $_ENV['DB_PASSWORD'],
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();


        // var_dump('CAPSULE INITIALISE');
        // die;

        return $capsule;
    }),

    Dispatcher::class =>
    factory(function (): Dispatcher {

        return simpleDispatcher(
            require BASE_PATH . '/routes/web.php'
        );
    }),

    FlashService::class =>
    autowire(),

    SalleValidator::class =>
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

    SalleController::class =>
    autowire(),

    ReservationController::class =>
    autowire(),

    Application::class =>
    autowire(),
];
