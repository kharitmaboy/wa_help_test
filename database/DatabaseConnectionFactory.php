<?php

declare(strict_types=1);

namespace Database;

use Exception;

/**
 * Фабрика позволяет использовать любую СУБД, прописанную в .env
 */
class DatabaseConnectionFactory
{
    /**
     * @throws Exception
     */
    public static function create(): DatabaseConnectionInterface
    {
        $dbConnection = getenv("DB_CONNECTION");

        switch ($dbConnection) {
            case "mysql":
                return new MySQLDatabase();
            default:
                throw new Exception('Database Connection Not Supported: ' . $dbConnection);
        }
    }
}