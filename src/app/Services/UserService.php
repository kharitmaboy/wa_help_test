<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use Exception;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws Exception
     */
    public function importUsersFromCSV(string $filePath): void
    {
        if (!is_readable($filePath)) {
            throw new Exception('File is not accessible');
        }

        $users = [];
        $this->userRepository->saveUsers($users);
    }
}