<?php

declare(strict_types=1);

require __DIR__ . '/autoload.php';

use App\Controllers\MailingController;
use App\Controllers\UserController;
use App\Repositories\MailQueueRepository;
use App\Repositories\UserRepository;
use App\Services\MailQueueService;
use App\Services\UserService;
use App\Workers\MailQueueWorker;
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

    $mailQueueRepository = new MailQueueRepository($dbConnection->getConnection());
    $mailQueueService = new MailQueueService($mailQueueRepository, $userRepository);
    $mailQueueWorker = new MailQueueWorker($dbConnection->getConnection(), $mailQueueService);
    $mailingController = new MailingController($mailQueueService, $mailQueueWorker);

    $router = new Router();

    $router->add('/upload', function () use ($userController) {
        $userController->handleFileImport();
    });

    $router->add('/add_mailing', function () use ($mailingController) {
        $mailingController->handleAddMailing();
    });

    $router->add('/mailing', function () use ($mailingController) {
        $mailingController->handleMailing();
    });

    $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    $router->dispatch($requestUri);
} catch (Exception $e) {
    echo $e->getMessage();
}

