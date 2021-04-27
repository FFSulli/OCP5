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
        ;
    }

    public function find(int $id): ?Post
    {
        $prepared = $this->database->prepare('select * from posts where id=:id');
        $data = $this->database->execute($prepared, [
            ":id" => $id
        ], Post::class);

        if ($data == null) {
            return null;
        }

        return new Post();
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Post
    {
        return null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from posts');
        $data = $this->database->execute($prepared, [], Post::class);

        if ($data == null) {
            return null;
        }

        return $data;
    }

    public function create(object $post): bool
    {
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
