<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

use Config\EnvLoader;
use Database\DatabaseConnectionFactory;
use Migrations\CreateMailingsTable;
use Migrations\CreateMailQueueTable;
use Migrations\CreateUsersTable;

try {
    EnvLoader::load(__DIR__ . '/.env');

    $dbConnection = DatabaseConnectionFactory::create();
    $dbConnection->connect();

    $migrationUsersTable = new CreateUsersTable($dbConnection->getConnection());
    $migrationMailingsTable = new CreateMailingsTable($dbConnection->getConnection());
    $migrationMailQueueTable = new CreateMailQueueTable($dbConnection->getConnection());

    $migrationUsersTable->up();
    $migrationMailingsTable->up();
    $migrationMailQueueTable->up();
} catch (Exception $e) {
    echo $e->getMessage();
}
