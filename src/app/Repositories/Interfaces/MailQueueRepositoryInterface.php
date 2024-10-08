<?php

declare(strict_types = 1);

namespace App\Repositories\Interfaces;

interface MailQueueRepositoryInterface
{
    public function addToQueue(array $usersIds, int $mailingId): void;
    public function isExistsMailing(int $mailingId): bool;
}
