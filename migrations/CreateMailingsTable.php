<?php

declare(strict_types=1);

namespace Migrations;

use PDO;
use PDOException;

class CreateMailingsTable
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up(): void
    {
        $query = "
        CREATE TABLE IF NOT EXISTS mailings (
            id INT PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            body TEXT NOT NULL
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
        $query = "DROP TABLE IF EXISTS mailings;";

        try {
            $this->connection->exec($query);
        } catch (PDOException $e) {
            echo 'Error drop table' . $e->getMessage();
        }
    }
}