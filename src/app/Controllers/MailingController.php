<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Interfaces\MailingControllerInterface;
use App\Services\MailQueueService;
use App\Workers\MailQueueWorker;

class MailingController implements MailingControllerInterface
{
    private MailQueueService $mailQueueService;
    private MailQueueWorker $mailQueueWorker;

    public function __construct(MailQueueService $mailQueueService, MailQueueWorker $mailQueueWorker)
    {
        $this->mailQueueService = $mailQueueService;
        $this->mailQueueWorker = $mailQueueWorker;
    }

    public function handleAddMailing(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mailingId = (int)$_POST['mailing_id'];

            if ($this->mailQueueService->isExistsMailing($mailingId))
            {
                $this->mailQueueService->addUsersToMailQueue($mailingId);
            }
        }
    }

    public function handleMailing(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->mailQueueWorker->processQueue();
        }
    }
}