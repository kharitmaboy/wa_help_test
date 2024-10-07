<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

use App\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Config\EnvLoader;
use Database\DatabaseConnectionFactory;
use Routes\Router;

try {
    EnvLoader::load(__DIR__ . '/.env');

    $dbConnection = DatabaseConnectionFactory::create();
    $dbConnection->connect();

    $userRepository = new UserRepository($dbConnection->getConnection());
    $userService = new UserService($userRepository);
    $userController = new UserController($userService);

    $router = new Router();
    $router->add('/upload', function () use ($userController) {
        $userController->handleFileImport();
    });

    $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    $router->dispatch($requestUri);
} catch (Exception $e) {
    echo $e->getMessage();
}

