#!/usr/bin/env php
<?php

require_once './vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

require_once BASE_PATH. '/config/database.php';

$command = $argv[1] ?? null;

switch ($command) {

    case 'migrate':

        require './bin/migrate.php';
        break;

    case 'seed':
        require './bin/seed.php';
        break;

    default:
        echo "Commande inconnue.\n\n";

        echo "Commandes disponibles :\n";
        echo "  php app migrate\n";
        echo "  php app seed\n";

        exit(1);
}