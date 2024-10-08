<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\MailQueueRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\MailQueueServiceInterface;

class MailQueueService implements MailQueueServiceInterface
{
    private MailQueueRepository $mailQueueRepository;
    private UserRepository $userRepository;

    public function __construct(MailQueueRepository $mailQueueRepository, UserRepository $userRepository)
    {
        $this->mailQueueRepository = $mailQueueRepository;
        $this->userRepository = $userRepository;
    }

    public function addUsersToMailQueue(int $mailingId): void
    {
        $usersIds = $this->userRepository->getUsers();

        if (empty($usersIds)) {
            echo 'No users to add for mailing with id ' . $mailingId;

            return;
        }

        $this->addToQueue($usersIds, $mailingId);
    }

    private function addToQueue(array $usersIds, int $mailingId): void
    {
        $this->mailQueueRepository->addToQueue($usersIds, $mailingId);
    }

    public function isExistsMailing(int $mailingId): bool
    {
        return $this->mailQueueRepository->isExistsMailing($mailingId);
    }

    public function sendMail(string $userName, string $title, string $body): void
    {
        echo 'Successfully sending mail to ' . $userName . PHP_EOL;
    }
}