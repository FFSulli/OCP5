<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database\MySQLDB;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

final class PostRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct(MySQLDB $database)
    {
        $this->database = $database;
    }

    public function find(int $id): ?Post
    {
        $prepared = $this->database->prepare('select * from posts where id=:id');
        $data = $this->database->execute($prepared, [
            ":id" => $id
        ]);

        return new Post($data['id'], $data['title'], $data['excerpt'], $data['content'], $data['slug'], $data['createdAt'], $data['updatedAt'], $data['user_fk']);
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
        // SB ici faire l'hydratation des objets
        $prepared = $this->database->prepare('select * from posts');
        $data = $this->database->execute($prepared, []);

        if ($data == null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $posts = [];
        foreach ($data as $post) {
            $posts[] = new Post($data['id'], $data['title'], $data['excerpt'], $data['content'], $data['slug'], $data['createdAt'], $data['updatedAt'], $data['user_fk']);
        }

        return $posts;
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
