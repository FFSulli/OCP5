<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\Comment;
use App\Model\Entity\Interfaces\EntityObjectInterface;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use App\Service\DotEnv\DotEnv;
use App\Service\DotEnv\DotEnvService;

final class CommentRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct(DotEnvService $dotEnvService)
    {
        $this->database = new MySQLDB($dotEnvService->get('DATABASE_HOST'), $dotEnvService->get('DATABASE_NAME'), $dotEnvService->get('DATABASE_USER'), $dotEnvService->get('DATABASE_PASSWORD'));
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

        /** @var Comment $comment */

        $prepared = $this->database->prepare('INSERT INTO comments (content, user_fk, post_fk) VALUES (:content, :user_fk, :post_fk)');
        $prepared->bindValue(':content', $comment->getContent());
        $prepared->bindValue(':user_fk', $comment->getUserFk());
        $prepared->bindValue(':post_fk', $comment->getPostFk());

        return $prepared->execute();
    }

    public function update(object $comment): bool
    {

        /** @var Comment $comment */

        $prepared = $this->database->prepare('UPDATE comments SET content = :content, verified = :verified, user_fk = :user_fk, post_fk = :post_fk WHERE id = :id');
        $prepared->bindValue(':id', $comment->getId());
        $prepared->bindValue(':content', $comment->getContent());
        $prepared->bindValue(':verified', $comment->isVerified());
        $prepared->bindValue(':user_fk', $comment->getUserFk());
        $prepared->bindValue(':post_fk', $comment->getPostFk());

        return $prepared->execute();
    }

    public function delete(object $comment): bool
    {

        /** @var Comment $comment */

        $prepared = $this->database->prepare('DELETE FROM comments WHERE id = :id');
        $prepared->bindValue(':id', $comment->getId());

        return $prepared->execute();
    }
}
