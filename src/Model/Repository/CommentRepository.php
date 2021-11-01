<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\Comment;
use App\Model\Entity\Interfaces\EntityObjectInterface;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use App\Service\DotEnv\DotEnvService;

final class CommentRepository extends BaseRepository implements EntityRepositoryInterface
{

    public function __construct(MySQLDB $database)
    {
        parent::__construct($database);
    }

    public function find(int $commentId): ?Comment
    {
        return $this->findOneBy(['id' => $commentId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        $prefetch = $this->preFetch($criteria, $orderBy, 1, 0, 'comments') ?? null;

        return $this->database->fetch($prefetch['statement'], $prefetch['binds'], Comment::class);
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $prefetch = $this->preFetch($criteria, $orderBy, $limit, $offset, 'comments');

        return $this->database->fetchAll($prefetch['statement'], $prefetch['binds'], Comment::class);
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from comments');
        return $this->database->fetchAll($prepared, [], Comment::class);
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

    public function allowComment(object $comment): bool
    {
        /** @var Comment $comment */

        $prepared = $this->database->prepare('UPDATE comments SET verified = 1 WHERE id = :id');
        $prepared->bindValue(':id', $comment->getId());

        return $prepared->execute();
    }

}
