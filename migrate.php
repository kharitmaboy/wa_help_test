<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

use Config\EnvLoader;
use Database\DatabaseConnectionFactory;
use Migrations\CreateUsersTable;

try {
    EnvLoader::load(__DIR__ . '/.env');

    $dbConnection = DatabaseConnectionFactory::create();
    $dbConnection->connect();
    $migration = new CreateUsersTable($dbConnection->getConnection());
    $migration->up();
} catch (Exception $e) {
    echo $e->getMessage();
}
