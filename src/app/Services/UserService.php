<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Services\Interfaces\UserServiceInterface;
use Exception;

class UserService implements UserServiceInterface
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
        if (($handle = fopen($filePath, 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $users[] = [
                    'number' => $data[0],
                    'name' => $data[1]
                ];
            }
            fclose($handle);
        }

        $this->userRepository->saveUsers($users);
    }
}