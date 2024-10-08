<?php

declare(strict_types = 1);

namespace App\Services\Interfaces;

interface MailQueueServiceInterface
{
    public function addUsersToMailQueue(int $mailingId): void;
    public function isExistsMailing(int $mailingId): bool;
    public function sendMail(string $userName, string $title, string $body): void;
}
