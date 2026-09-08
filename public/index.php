<?php
use App\Application;
use DI\ContainerBuilder;


define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$builder = new ContainerBuilder();

$builder->addDefinitions(
    BASE_PATH . '/config/container.php'
);

$container = $builder->build();

$application = $container->get(Application::class);

$application->run();