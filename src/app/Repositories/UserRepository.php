<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function saveUsers(array $users): void
    {
        $query = "INSERT INTO users (number, name) VALUES (:number, :name)";
        $stmt = $this->connection->prepare($query);

        foreach ($users as $user) {
            $stmt->execute([
               ':number' => $user['number'],
               ':name' => $user['name'],
            ]);
        }
    }
}