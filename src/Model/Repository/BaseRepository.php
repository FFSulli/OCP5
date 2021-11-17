<?php

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;

abstract class BaseRepository
{

    protected MySQLDB $database;

    public function __construct(MySQLDB $database)
    {
        $this->database = $database;
    }

    protected function preFetch(array $criteria, array $orderBy = null, int $limit = null, int $offset = null, string $table): ?array
    {
        $criteriaFields = [];
        $orderByFields = [];
        $binds = [];

        foreach ($criteria as $key => $value) {
            $criteriaFields[] = sprintf("%s = :%s", $key, $key);
            $binds[sprintf(":%s", $key)] = $value;
        }

        if (null !== $orderBy) {
            foreach ($orderBy as $key => $value) {
                $orderByFields[] = sprintf("%s %s", $key, $value);
            }
        }

        $criteriaList = implode(' AND ', $criteriaFields);
        $orderByList = implode(', ', $orderByFields);

        $whereClause = 0 !== count($criteriaFields) ? sprintf(' WHERE %s', $criteriaList) : '';
        $orderByClause = 0 !== count($orderByFields) ? sprintf(' ORDER BY %s', $orderByList) : '';
        $limitClause = null !== $limit ? ' LIMIT ' . $limit : '';
        $offsetClause = null !== $offset ? ' OFFSET ' . $offset : '';

        return [
            'statement' => $this->database->prepare('SELECT * FROM ' . $table . $whereClause . $orderByClause . $limitClause . $offsetClause),
            'binds' => $binds
        ];
    }
}
