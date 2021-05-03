<?php


declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\User;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use Config\DotEnv;

final class UserRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;


    public function __construct(MySQLDB $database)
    {
        (new DotEnv(__DIR__ . '/../../../.env'))->load();
        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
    }

    public function find(int $id): ?User
    {
        return null;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        $email = $criteria["email"];

        $prepared = $this->database->prepare('select * from user where email=:email');
        $data = $this->database->execute($prepared, [
            ":email" => $email
        ], User::class);

        return $data == null ? null : new user();
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll(): ?array
    {
        return null;
    }

    public function create(object $user): bool
    {
        return false;
    }

    public function update(object $user): bool
    {
        return false;
    }

    public function delete(object $user): bool
    {
        return false;
    }
}
