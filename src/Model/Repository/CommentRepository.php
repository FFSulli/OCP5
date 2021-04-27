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
        $prepared = $this->database->prepare('select * from comments where id=:id');
        $data = $this->database->execute($prepared, [
            ":id" => $id
        ], Comment::class);

        return new Comment();
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
    {
        return null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $post_fk = $criteria["post_fk"];

        $prepared = $this->database->prepare('select * from comments where post_fk=:post_fk');
        $data = $this->database->execute($prepared, [
            ":post_fk" => $post_fk
        ], Comment::class);

        if ($data == null) {
            return null;
        }

        return $data;
    }

    public function findAll(): ?array
    {
        return null;
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
