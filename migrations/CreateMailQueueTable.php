<?php

declare(strict_types=1);

namespace Migrations;

use PDO;
use PDOException;

class CreateMailQueueTable
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up(): void
    {
        $query = "
        CREATE TABLE IF NOT EXISTS mail_queue (
            id INT PRIMARY KEY AUTO_INCREMENT,
            user_id INT NOT NULL,
            mailing_id INT NOT NULL,
            status ENUM('pending', 'processing', 'failed') NOT NULL DEFAULT 'pending',
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (mailing_id) REFERENCES mailings(id) ON DELETE CASCADE,
            UNIQUE (user_id, mailing_id)
        );
        ";

        try {
            $this->connection->exec($query);
        } catch (PDOException $e) {
            echo 'Error creating table: ' . $e->getMessage();
        }
    }

    public function down(): void
    {
        $query = "DROP TABLE IF EXISTS mail_queue;";

        try {
            $this->connection->exec($query);
        } catch (PDOException $e) {
            echo 'Error drop table' . $e->getMessage();
        }
    }
}