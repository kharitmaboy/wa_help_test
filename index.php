<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

use Database\DatabaseConnectionFactory;
use Сonfig\EnvLoader;

try {
    EnvLoader::load(__DIR__ . '/.env');

    $dbConnection = DatabaseConnectionFactory::create();
    $dbConnection->connect();
} catch (Exception $e) {

}

