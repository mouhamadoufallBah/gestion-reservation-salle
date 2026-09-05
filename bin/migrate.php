<?php

use App\Repository\Database;
use Database\Migration;

try {
    Database::getInstance();

    $migration = new Migration();
    $migration->run();

} catch (\Throwable $e) {

    echo "La base de données n'existe pas ou est inaccessible.\n";
    echo "Voulez-vous créer la base de données ? (o/n) : ";

    $response = trim(fgets(STDIN));

    if (strtolower($response) === 'o') {

        try {

            Database::createDatabase();

            echo "Base de données créée avec succès.\n";

            Database::getInstance();

            $migration = new Migration();
            $migration->run();

        } catch (\Throwable $e) {

            echo "Erreur : " . $e->getMessage() . "\n";
            exit(1);
        }

    } else {

        echo "Migration annulée.\n";
        exit(1);
    }
}