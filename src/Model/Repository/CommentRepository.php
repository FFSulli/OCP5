<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\Comment;
use App\Model\Entity\Interfaces\EntityObjectInterface;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use Config\DotEnv;

final class CommentRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct(MySQLDB $database)
    {
        (new DotEnv(__DIR__ . '/../../../.env'))->load();
        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
    }

    public function find(int $id): ?Comment
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        return $this->findBy($criteria, $orderBy, 1, 1)[0] ?? null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $limit_field = !is_null($limit) ? ' LIMIT ' . $limit : '';
        $offset_field = !is_null($offset) ? ' OFFSET ' . $offset : '';

        $criteria_fields = [];
        $orderBy_fields = [];

        foreach ($criteria as $key => $value) {
            $criteria_fields[] = sprintf("%s = '%s'", $key, $value);
        }

        foreach ($orderBy as $key => $value) {
            $orderBy_fields[] = sprintf("%s %s", $key, $value);
        }

        $criteria_list = implode(' AND ', $criteria_fields);
        $orderBy_list = implode(', ', $orderBy_fields);

        $prepared = $this->database->prepare('SELECT * FROM comments limit_field=:limit_field offset_field=:offset_field WHERE criteria_list=:criteria_list ORDER BY orderBy_list=:orderBy_list');
        $data = $this->database->execute($prepared, [
            ":limit_field" => $limit_field,
            ":offset_field" => $offset_field,
            ":criteria_list" => $criteria_list,
            ":orderBy_list" => $orderBy_list
        ], Comment::class);

        $results = [];
        foreach ($data as $row) {
            $results[] = Comment::fromArray($row);
        }

        return $results;
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from comments');
        return $this->database->execute($prepared, [], Comment::class);
    }

    public function create(object $comment): bool
    {
        return false ;
    }

    public function update(object $comment): bool
    {
        return false;
    }

    public function delete(object $comment): bool
    {
        return false;
    }
}
