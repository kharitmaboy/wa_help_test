<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    public function importUsersFromCSV(string $filePath): void;
}
