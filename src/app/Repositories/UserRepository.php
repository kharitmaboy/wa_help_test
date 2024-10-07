<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\UserRepositoryInterface;
use PDO;
use PDOException;

class UserRepository implements UserRepositoryInterface
{
    private const MAX_CHUNK_SIZE = 500;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function saveUsers(array $users): void
    {
        $usersChunks = array_chunk($users, self::MAX_CHUNK_SIZE);

        // Для оптимизации реализуем выполнение запроса раз в 500 пройденных записей
        foreach ($usersChunks as $usersChunk) {
            $placeholders = [];
            $values = [];

            foreach ($usersChunk as $user) {
                $placeholders[] = '(?, ?)';
                $values[] = $user['number'];
                $values[] = $user['name'];
            }

            $query = 'INSERT INTO users (number, name) VALUES ' . implode(', ', $placeholders);
            $this->connection->beginTransaction();

            try {
                $stmt = $this->connection->prepare($query);
                $stmt->execute($values);

                $this->connection->commit();
            } catch (PDOException $e) {
                $this->connection->rollBack();
                throw new PDOException('Error executing query: ' . $e->getMessage());
            }
        }
    }
}