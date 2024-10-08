<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\MailQueueRepositoryInterface;
use PDO;
use PDOException;

class MailQueueRepository implements MailQueueRepositoryInterface
{
    private const MAX_CHUNK_SIZE = 500;
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function addToQueue(array $usersIds, int $mailingId): void
    {
        // В очередь на рассылку добавляем пачками по 500 для оптимизации запросов к БД
        $usersIdsChunks = array_chunk($usersIds, self::MAX_CHUNK_SIZE);

        foreach ($usersIdsChunks as $usersIdsChunk) {
            $placeholders = [];
            $values = [];

            foreach ($usersIdsChunk as $userId) {
                $placeholders[] = '(?, ?, ?)';
                $values[] = $userId;
                $values[] = $mailingId;
                $values[] = 'pending';
            }

            $query = 'INSERT INTO mail_queue (user_id, mailing_id, status) VALUES ' . implode(', ', $placeholders);
            $this->connection->beginTransaction();

            try {
                $stmt = $this->connection->prepare($query);
                $stmt->execute($values);

                $this->connection->commit();
            } catch (PDOException $e) {
                $this->connection->rollBack();
                throw new PDOException('Error creating task to queue: ' . $e->getMessage());
            }
        }
    }

    public function isExistsMailing(int $mailingId): bool
    {
        $query = "SELECT id FROM mailings WHERE id = :mailing_id;";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute([
                'mailing_id' => $mailingId,
            ]);

            return (bool) $stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new PDOException('Error getting mailing with id ' . $mailingId . ". Error: " . $e->getMessage());
        }
    }
}