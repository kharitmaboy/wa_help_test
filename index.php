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

    /** @todo
     * Возможно стоит сделать общие контроллер, сервис и репозиторий,
     * в которых происходит инициализация нужных нам контроллеров, сервисов и репозиториев,
     * чтобы приложение было более удобно расширять
     */
    $userRepository = new UserRepository($dbConnection->getConnection());
    $userService = new UserService($userRepository);
    $userController = new UserController($userService);

    $mailQueueRepository = new MailQueueRepository($dbConnection->getConnection());
    $mailQueueService = new MailQueueService($mailQueueRepository, $userRepository);
    $mailQueueWorker = new MailQueueWorker($dbConnection->getConnection(), $mailQueueService);
    $mailingController = new MailingController($mailQueueService, $mailQueueWorker);

    $router = new Router();

    // endpoint для загрузки пользователей из csv файла
    $router->add('/upload', function () use ($userController) {
        $userController->handleFileImport();
    });

    // endpoint для прохода по пользоватлеям и добавления в очередь на отправку рассылки
    $router->add('/add_mailing', function () use ($mailingController) {
        $mailingController->handleAddMailing();
    });

    /** @todo По-хорошему воркер должен быть всегда запущен
     * То есть более правильно было бы запускать его по крону
     * или настроить supervisor, если не использовать библиотеки php
     */
    $router->add('/mailing', function () use ($mailingController) {
        $mailingController->handleMailing();
    });

    $requestUri = strtok($_SERVER['REQUEST_URI'], '?');
    $router->dispatch($requestUri);
} catch (Exception $e) {
    echo $e->getMessage();
}

