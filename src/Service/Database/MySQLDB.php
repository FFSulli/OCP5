<?php

namespace App\Service\Database;

use PDO;
use PDOStatement;

class MySQLDB implements DatabaseInterface
{
    private PDO $pdo;

    public function __construct(string $dbHost, string $dbName, string $dbUser, string $dbPassword)
    {
        $this->pdo = new PDO('mysql:host=' . $dbHost . ';dbname=' . $dbName, $dbUser, $dbPassword);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @param string $statement
     * @return PDOStatement
     */
    public function prepare(string $statement): PDOStatement
    {
        return $this->pdo->prepare($statement);
    }

    /**
     * @param PDOStatement $prepared
     * @param array $args
     * @param string $PDOClass
     * @return array
     */
    public function execute(PDOStatement $prepared, array $args, string $PDOClass): array
    {
        $prepared->execute($args);
        return $prepared->fetchAll(PDO::FETCH_CLASS, $PDOClass);
    }
}
