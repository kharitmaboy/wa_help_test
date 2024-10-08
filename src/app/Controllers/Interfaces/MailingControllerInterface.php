<?php

declare(strict_types=1);

namespace App\Controllers\Interfaces;

interface MailingControllerInterface {
    public function handleAddMailing(): void;
    public function handleMailing(): void;
}
