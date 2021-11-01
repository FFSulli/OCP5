<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\User;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use App\Service\DotEnv\DotEnv;
use App\Service\DotEnv\DotEnvService;

final class UserRepository extends BaseRepository implements EntityRepositoryInterface
{

    public function __construct(MySQLDB $database)
    {
        parent::__construct($database);
    }

    public function find(int $userId): ?User
    {
        return $this->findOneBy(['id' => $userId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        $prefetch = $this->preFetch($criteria, $orderBy, 1, 0, 'users') ?? null;

        return $this->database->fetch($prefetch['statement'], $prefetch['binds'], User::class);
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $prefetch = $this->preFetch($criteria, $orderBy, $limit, $offset, 'users');

        return $this->database->fetchAll($prefetch['statement'], $prefetch['binds'], User::class);
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from users');
        return $this->database->fetchAll($prepared, [], User::class);
    }

    public function create(object $user): bool
    {
        /** @var User $user */

        $prepared = $this->database->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)');
        $prepared->bindValue(':firstName', $user->getFirstName());
        $prepared->bindValue(':lastName', $user->getLastName());
        $prepared->bindValue(':email', $user->getEmail());
        $prepared->bindValue(':password', $user->getPassword());

        return $prepared->execute();
    }

    public function update(object $user): bool
    {
        /** @var User $user */

        $prepared = $this->database->prepare('UPDATE users SET first_name = :firstName, last_name = :lastName, email = :email, password = :password, role_fk = :roleFk WHERE id = :id');
        $prepared->bindValue(':id', $user->getId());
        $prepared->bindValue(':firstName', $user->getFirstName());
        $prepared->bindValue(':lastName', $user->getLastName());
        $prepared->bindValue(':email', $user->getEmail());
        $prepared->bindValue(':password', $user->getPassword());
        $prepared->bindValue(':roleFk', $user->getRoleFk());

        return $prepared->execute();
    }

    public function delete(object $user): bool
    {
        /** @var User $user */

        $prepared = $this->database->prepare('DELETE FROM users WHERE id = :id');
        $prepared->bindValue(':id', $user->getId());

        return $prepared->execute();
    }

}
