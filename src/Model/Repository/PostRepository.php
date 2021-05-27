<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database\MySQLDB;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use Config\DotEnv;

final class PostRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct()
    {
        (new DotEnv(__DIR__ . '/../../../.env'))->load();
        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
    }

    public function find(int $id): ?Post
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Post
    {
        return $this->findBy($criteria, $orderBy, 1, 1)[0] ?? null;
    }

    public function findBy(array $criteria, array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        $limitField = !is_null($limit) ? ' LIMIT ' . $limit : '';
        $offsetField = !is_null($offset) ? ' OFFSET ' . $offset : '';

        $criteriaFields = [];
        $orderByFields = [];

        // Utiliser array_keys
        foreach ($criteria as $key=>$value) {
            $criteriaFields[] = sprintf("%s = :%s", $key, $key);
        }

        foreach ($orderBy as $key=>$value) {
            $orderByFields[] = sprintf("%s %s", $key, $value);
        }

        $criteriaList = implode(' AND ', $criteriaFields);
        $orderByList = implode(', ', $orderByFields);

        $whereClause = 0 !== count($criteriaFields) ? sprintf('WHERE %s', $criteriaList) : '';
        $orderByClause = 0 !== count($orderByFields) ? sprintf('ORDER BY %s', $orderByList) : '';

        // SELECT * FROM post WHERE id = :id AND name = :name ORDER BY id DESC LIMIT 1 OFFSET 1
        // SELECT * FROM $tableName $whereClause $orderByClause $limitClause $offsetClause
        $prepared = $this->database->prepare('SELECT * FROM posts $whereClause limitField=:limitField offsetField=:offsetField WHERE criteria_list=:criteria_list ORDER BY orderBy_list=:orderBy_list');
        $data = $this->database->execute($prepared, [
            ":limitField" => $limitField,
            ":offsetField" => $offsetField,
            ":criteria_list" => $criteriaList,
            ":orderBy_list" => $orderByList
        ], Post::class);

        $results = [];
        foreach ($data as $row) {
            $results[] = Post::fromArray($row);
        }

        return $results;
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from posts');
        return $this->database->execute($prepared, [], Post::class);
    }

    public function create(object $post): bool
    {
        /** @var Post $post */
        return false;
    }

    public function update(object $post): bool
    {
        return false;
    }

    public function delete(object $post): bool
    {
        return false;
    }
}
