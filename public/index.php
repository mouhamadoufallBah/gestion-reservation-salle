<?php

use App\Core\Database;

define('BASE_PATH', dirname(__DIR__));

require_once(BASE_PATH . "/vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

try {
    Database::getInstance();

    echo "Connexion réussie !";
} catch (RuntimeException $e) {
    echo $e->getMessage();
}
