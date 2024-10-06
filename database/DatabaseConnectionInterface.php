<?php

declare(strict_types=1);

namespace Database;

use PDO;

interface DatabaseConnectionInterface
{
    public function connect();
    public function getConnection(): PDO;
}
