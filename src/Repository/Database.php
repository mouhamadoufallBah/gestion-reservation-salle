<?php

namespace App\Repository;

use Illuminate\Database\Capsule\Manager;
use RuntimeException;

class Database
{
    private static ?Manager $capsule = null;

    private function __construct()
    {
    }

    public static function getInstance(): Manager
    {
        if (self::$capsule === null) {

            $config = require BASE_PATH . '/config/database.php';

            $capsule = new Manager();

            $capsule->addConnection($config);

            $capsule->setAsGlobal();
            $capsule->bootEloquent();

            self::$capsule = $capsule;
        }

        return self::$capsule;
    }

    public static function databaseExists(): bool
    {
        $config = require BASE_PATH . '/config/database.php';

        $database = $config['database'];

        $config['database'] = null;

        $capsule = new Manager();

        $capsule->addConnection($config);

        $connection = $capsule->getConnection();

        $result = $connection->select(
            'SELECT SCHEMA_NAME 
             FROM INFORMATION_SCHEMA.SCHEMATA 
             WHERE SCHEMA_NAME = ?',
            [$database]
        );

        return count($result) > 0;
    }

    public static function createDatabase(): void
    {
        $config = require BASE_PATH . '/config/database.php';

        $database = $config['database'];

        $config['database'] = null;

        $capsule = new Manager();

        $capsule->addConnection($config);

        $connection = $capsule->getConnection();

        $connection->statement(
            "CREATE DATABASE IF NOT EXISTS `{$database}`"
        );
    }
}