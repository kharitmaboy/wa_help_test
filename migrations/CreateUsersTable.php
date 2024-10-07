<?php

declare(strict_types=1);

namespace Migrations;

use PDO;
use PDOException;

class CreateUsersTable
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function up(): void
    {
        $query = "
        CREATE TABLE IF NOT EXISTS users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            number VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL
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
        $query = "DROP TABLE IF EXISTS users;";

        try {
            $this->connection->exec($query);
        } catch (PDOException $e) {
            echo 'Error drop table' . $e->getMessage();
        }
    }
}