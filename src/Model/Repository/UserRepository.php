<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Service\Database\MySQLDB;
use App\Model\Entity\User;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;
use App\Service\DotEnv\DotEnv;
use App\Service\DotEnv\DotEnvService;

final class UserRepository implements EntityRepositoryInterface
{
    private MySQLDB $database;

    public function __construct(MySQLDB $database)
    {

        $this->database = $database;
    }

    public function find(int $userId): ?User
    {
        return $this->findOneBy(['id' => $userId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        $prefetch = $this->preFetch($criteria, $orderBy, 1, 0) ?? null;

        return $this->database->fetch($prefetch['statement'], $prefetch['binds'], User::class);
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $prefetch = $this->preFetch($criteria, $orderBy, $limit, $offset);

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

        $prepared = $this->database->prepare('UPDATE users SET first_name = :firstName, last_name = :lastName, email = :email, password = :password WHERE id = :id');
        $prepared->bindValue(':id', $user->getId());
        $prepared->bindValue(':firstName', $user->getFirstName());
        $prepared->bindValue(':lastName', $user->getLastName());
        $prepared->bindValue(':email', $user->getEmail());
        $prepared->bindValue(':password', $user->getPassword());

        return $prepared->execute();
    }

    public function delete(object $user): bool
    {
        /** @var User $user */

        $prepared = $this->database->prepare('DELETE FROM users WHERE id = :id');
        $prepared->bindValue(':id', $user->getId());

        return $prepared->execute();
    }

    private function preFetch(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
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

        return [
            'statement' => $this->database->prepare('SELECT * FROM users ' . $whereClause . $orderByClause . $limitClause . $offsetClause),
            'binds' => $binds
        ];
    }
}
