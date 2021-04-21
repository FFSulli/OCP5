<?php

namespace App\Service\Database;

use PDO;

class MySQLDB implements iDatabase
{
    private PDO $pdo;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        $this->pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $statement): \PDOStatement
    {
        return $this->pdo->prepare($statement);
    }

    /**
     * @param \PDOStatement $prepared
     * @param array $args
     * @return array
     */
    public function execute(\PDOStatement $prepared, array $args): array
    {
        $prepared->execute($args);
        return $prepared->fetchAll();
    }
}
