<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database\MySQLDB;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use App\Service\DotEnv\DotEnvService;


final class PostRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct(MySQLDB $database)
    {
        $this->database = $database;
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
            foreach ($orderBy as $key => $value) {
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

    public function create(object $post): bool
    {
        /** @var Post $post */

        $prepared = $this->database->prepare('INSERT INTO users (title, excerpt, content, slug, user_fk) VALUES (:title, :excerpt, :content, :slug, :user_fk)');
        $prepared->bindValue(':title', $post->getTitle());
        $prepared->bindValue(':excerpt', $post->getExcerpt());
        $prepared->bindValue(':content', $post->getContent());
        $prepared->bindValue(':slug', $post->getSlug());
        $prepared->bindValue(':user_fk', $post->getUserFk());

        return $prepared->execute();
    }

    public function update(object $post): bool
    {
        /** @var Post $post */

        $prepared = $this->database->prepare('UPDATE posts SET title = :title, excerpt = :excerpt, content = :content, slug = :slug, user_fk = :user_fk, post_status_fk = :post_status_fk WHERE id = :id');
        $prepared->bindValue(':id', $post->getId());
        $prepared->bindValue(':title', $post->getTitle());
        $prepared->bindValue(':excerpt', $post->getExcerpt());
        $prepared->bindValue(':content', $post->getContent());
        $prepared->bindValue(':slug', $post->getSlug());
        $prepared->bindValue(':user_fk', $post->getUserFk());
        $prepared->bindValue(':post_status_fk', $post->getStatusFk());

        return $prepared->execute();
    }

    public function delete(object $post): bool
    {
        /** @var Post $post */

        $prepared = $this->database->prepare('DELETE FROM posts WHERE id = :id');
        $prepared->bindValue(':id', $post->getId());

        return $prepared->execute();
    }

    public function countPosts(?array $criteria): int
    {
        $criteriaFields = [];
        $binds = [];

        foreach ($criteria as $key => $value) {
            $criteriaFields[] = sprintf("%s = :%s", $key, $key);
            $binds[sprintf(":%s", $key)] = $value;
        }

        $criteriaList = implode(' AND ', $criteriaFields);

        $whereClause = 0 !== count($criteriaFields) ? sprintf('WHERE %s', $criteriaList) : '';

//        var_dump("SELECT COUNT(id) AS postsCount FROM posts $whereClause");
//        die();

        $prepared = $this->database->prepare("SELECT COUNT(id) AS postsCount FROM posts $whereClause");
        $result = $this->database->executeAggregat($prepared, $binds);
//        var_dump($result['postsCount']);
//        die();
        return (int) $result['postsCount'];
//        return (int) $result;
    }
}
