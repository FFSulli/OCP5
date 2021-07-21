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

    public function __construct()
    {
        (new DotEnv(__DIR__ . '/../../../.env'))->load();
        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
    }

    public function find(int $commentId): ?Comment
    {
        return $this->findOneBy(['id' => $commentId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        return $this->findBy($criteria, $orderBy, 1, 1)[0] ?? null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
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

        $prepared = $this->database->prepare('SELECT * FROM comments ' . $whereClause . $orderByClause . $limitClause . $offsetClause);

        return $this->database->execute($prepared, $binds, Comment::class);
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from comments');
        return $this->database->execute($prepared, [], Comment::class);
    }

    public function create(object $comment): bool
    {
        $content = $comment->getContent();
        $user_fk = $comment->getUserFk();
        $post_fk = $comment->getPostFk();

        $prepared = $this->database->prepare('INSERT INTO comments (content, user_fk, post_fk) VALUES (:content, :user_fk, :post_fk)');
        $prepared->bindParam(':content', $content);
        $prepared->bindParam(':user_fk', $user_fk);
        $prepared->bindParam(':post_fk', $post_fk);
        $prepared->execute();

        return true;
    }

    public function update(object $comment): bool
    {
        $id = $comment->getId();
        $content = $comment->getContent();
        $verified = $comment->getVerified();
        $user_fk = $comment->getUserFk();
        $post_fk = $comment->getPostFk();

        $prepared = $this->database->prepare('UPDATE comments SET content = :content, verified = :verified, user_fk = :user_fk, post_fk = :post_fk WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->bindParam(':content', $content);
        $prepared->bindParam(':verified', $verified);
        $prepared->bindParam(':user_fk', $user_fk);
        $prepared->bindParam(':post_fk', $post_fk);
        $prepared->execute();

        return true;
    }

    public function delete(object $comment): bool
    {
        $id = $comment->getId();

        $prepared = $this->database->prepare('DELETE FROM comments WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->execute();

        return true;
    }
}
