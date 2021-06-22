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

    public function find(int $postId): ?Post
    {
        return $this->findOneBy(['id' => $postId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Post
    {
        return $this->findBy($criteria, $orderBy, 1, 0)[0] ?? null;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {

        $criteriaFields = [];
        $orderByFields = [];
        $binds = [];

        foreach ($criteria as $key => $value) {
            $criteriaFields[] = sprintf("%s = :%s", $key, $key);
            $binds[sprintf(":%s", $key)] = $value;
        }

        if (null !== $orderBy) {
            foreach ($orderBy as $key=>$value) {
                $orderByFields[] = sprintf("%s %s", $key, $value);
            }
        }

        $criteriaList = implode(' AND ', $criteriaFields);
        $orderByList = implode(', ', $orderByFields);

        $whereClause = 0 !== count($criteriaFields) ? sprintf('WHERE %s', $criteriaList) : '';
        $orderByClause = 0 !== count($orderByFields) ? sprintf(' ORDER BY %s', $orderByList) : '';
        $limitClause = null !== $limit ? ' LIMIT ' . $limit : '';
        $offsetClause = null !== $offset ? ' OFFSET ' . $offset : '';

        $prepared = $this->database->prepare('SELECT * FROM posts ' . $whereClause . $orderByClause . $limitClause . $offsetClause);

        return $this->database->execute($prepared, $binds, Post::class);

    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from posts');
        return $this->database->execute($prepared, [], Post::class);
    }

//    public function create(object $post): bool
//    {
//        /** @var Post $post */
//        return false;
//    }
//
//    public function update(object $post): bool
//    {
//        return false;
//    }
//
//    public function delete(object $post): bool
//    {
//        return false;
//    }
}
