<?php

namespace App\Service\Database;

use PDO;

class PGSQLDB implements iDatabase
{
    private PDO $pdo;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        $this->pdo = new PDO('pgsql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $statement): \PDOStatement
    {
        return $this->pdo->prepare($statement);
    }

    public function execute(\PDOStatement $prepared, array $args): array
    {
        $prepared->execute($args);
        return $prepared->fetchAll();
    }
}
