<?php

namespace App\Core;

use Illuminate\Database\Capsule\Manager;

class Database
{
    private static ?Manager $capsule = null;

    private function __construct() {}

    public static function getInstance(): Manager
    {
        if (self::$capsule === null) {
            try {
                $config = require(BASE_PATH . '/config/database.php');

                $capsule = new Manager();

                $capsule->addConnection($config);

                $capsule->setAsGlobal();
                $capsule->bootEloquent();

                self::$capsule = $capsule;
            } catch (\PDOException $e) {
                throw new \RuntimeException(
                    'Impossible de se connecter à la base de données.',
                    0,
                    $e
                );
            }
        }

        return self::$capsule;
    }
}
