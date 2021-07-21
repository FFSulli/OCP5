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
        $title = $post->getTitle();
        $excerpt = $post->getExcerpt();
        $content = $post->getContent();
        $slug = $post->getSlug();
        $user_fk = $post->getUserFk();

        $prepared = $this->database->prepare('INSERT INTO users (title, excerpt, content, slug, user_fk) VALUES (:title, :excerpt, :content, :slug, :user_fk)');
        $prepared->bindParam(':title', $title);
        $prepared->bindParam(':excerpt', $excerpt);
        $prepared->bindParam(':content', $content);
        $prepared->bindParam(':slug', $slug);
        $prepared->bindParam(':user_fk', $user_fk);
        $prepared->execute();

        return true;
    }

    public function update(object $post): bool
    {
        $id = $post->getId();
        $title = $post->getTitle();
        $excerpt = $post->getExcerpt();
        $content = $post->getContent();
        $slug = $post->getSlug();
        $user_fk = $post->getUserFk();
        $post_status_fk = $post->getStatusFk();

        $prepared = $this->database->prepare('UPDATE posts SET title = :title, excerpt = :excerpt, content = :content, slug = :slug, user_fk = :user_fk, post_status_fk = :post_status_fk WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->bindParam(':title', $title);
        $prepared->bindParam(':excerpt', $excerpt);
        $prepared->bindParam(':content', $content);
        $prepared->bindParam(':slug', $slug);
        $prepared->bindParam(':user_fk', $user_fk);
        $prepared->bindParam(':post_status_fk', $post_status_fk);
        $prepared->execute();

        return true;
    }

    public function delete(object $post): bool
    {
        $id = $post->getId();

        $prepared = $this->database->prepare('DELETE FROM posts WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->execute();

        return true;
    }
}
