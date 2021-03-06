<?php

declare(strict_types=1);

namespace App\Service\Database;

interface DatabaseInterface
{
    public function prepare(string $statement): \PDOStatement;

    public function fetchAll(\PDOStatement $prepared, array $args, string $PDOClass): ?array;
}
