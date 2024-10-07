<?php

declare(strict_types=1);

namespace App\Controllers\Interfaces;

interface UserControllerInterface
{
    public function handleFileImport(): void;
}
