<?php

declare(strict_types = 1);

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function saveUsers(array $users): void;
}
