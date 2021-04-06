<?php

declare(strict_types=1);

// class pour gérer la connection à la base de donnée
namespace App\Service;

use PDO;

final class Database
{

    private string $db_url;

    public function __construct()
    {
        $this->db_url = $_ENV["DATABASE_URL"];
    }

    /**
     * Initialize PDO connection.
     * @return PDO
     */
    public function initPdo(): \PDO
    {
        $pdo = new PDO($this->db_url);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }

}
