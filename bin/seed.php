<?php

use App\Repository\Database;
use Database\Seed;

try {

    if (!Database::databaseExists()) {

        echo "La base de données n'existe pas.\n";
        echo "Voulez-vous la créer ? (o/n) : ";

        $response = trim(fgets(STDIN));

        if (strtolower($response) !== 'o') {
            echo "Seed annulé.\n";
            exit(1);
        }

        Database::createDatabase();

        echo "Base de données créée avec succès.\n";
    }

    // Maintenant seulement on se connecte à la base
    Database::getInstance();

    // Le Seeder lance lui-même la migration
    $seed = new Seed();

    $seed->run();

} catch (\Throwable $e) {

    echo "Erreur : " . $e->getMessage() . "\n";

    exit(1);
}