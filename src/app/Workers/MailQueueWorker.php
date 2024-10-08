<?php

declare(strict_types=1);

namespace App\Workers;

use App\Services\MailQueueService;
use Exception;
use PDO;
use PDOException;

class MailQueueWorker
{
    private PDO $connection;
    private MailQueueService $mailQueueService;

    public function __construct(PDO $connection, MailQueueService $mailQueueService)
    {
        $this->connection = $connection;
        $this->mailQueueService = $mailQueueService;
    }

    public function processQueue(): void
    {
        $query = "SELECT q.id, u.name, m.title, m.body FROM mail_queue q
        JOIN users u ON q.user_id = u.id
        JOIN mailings m ON q.mailing_id = m.id
        WHERE q.status = :status";

        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute([
                'status' => 'pending',
            ]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($tasks)) {
                echo "Очередь пуста\n";

                return;
            }

            foreach ($tasks as $task) {
                $this->markAsProcessing($task['id']);

                try {
                    $this->mailQueueService->sendMail($task['name'], $task['title'], $task['body']);
                } catch (Exception $e) {
                    $this->markAsFailed($task['id']);
                    echo 'Failed to send mailing ' . $e->getMessage();
                }

                // Удаляем задачу из очереди после успешной обработки
                $this->removeTask($task['id']);
            }
        } catch (PDOException $e) {
            throw new PDOException('Failed to get tasks: ' . $e->getMessage());
        }
    }

    private function markAsProcessing($taskId): void
    {
        try {
            $stmt = $this->connection->prepare("UPDATE mail_queue SET status = 'processing' WHERE id = :id");
            $stmt->execute(['id' => $taskId]);
        } catch (Exception $e) {
            throw new PDOException('Failed to set status \'processing\': ' . $e->getMessage());
        }
    }

    private function removeTask($taskId): void
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM mail_queue WHERE id = :id");
            $stmt->execute(['id' => $taskId]);
        } catch (Exception $e) {
            throw new PDOException('Failed to delete task: ' . $e->getMessage());
        }
    }

    private function markAsFailed($taskId): void
    {
        try {
            $stmt = $this->connection->prepare("UPDATE mail_queue SET status = 'failed' WHERE id = :id");
            $stmt->execute(['id' => $taskId]);
        } catch (Exception $e) {
            throw new PDOException('Failed to set status \'failed\': ' . $e->getMessage());
        }
    }
}