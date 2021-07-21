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


    public function __construct()
    {
        (new DotEnv(__DIR__ . '/../../../.env'))->load();
        $this->database = new MySQLDB(getenv('DATABASE_HOST'), getenv('DATABASE_NAME'), getenv('DATABASE_USER'), getenv('DATABASE_PASSWORD'));
    }

    public function find(int $userId): ?User
    {
        return $this->findOneBy(['id' => $userId]);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?User
    {
        return $this->findBy($criteria, $orderBy, 1, 0)[0] ?? null;
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

        $prepared = $this->database->prepare('SELECT * FROM users ' . $whereClause . $orderByClause . $limitClause . $offsetClause);

        return $this->database->execute($prepared, $binds, User::class);
    }

    public function findAll(): ?array
    {
        $prepared = $this->database->prepare('select * from users');
        return $this->database->execute($prepared, [], User::class);
    }

    public function create(object $user): bool
    {
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $prepared = $this->database->prepare('INSERT INTO users (first_name, last_name, email, password) VALUES (:firstName, :lastName, :email, :password)');
        $prepared->bindParam(':firstName', $firstName);
        $prepared->bindParam(':lastName', $lastName);
        $prepared->bindParam(':email', $email);
        $prepared->bindParam(':password', $password);
        $prepared->execute();

        return true;
    }

    public function update(object $user): bool
    {
        $id = $user->getId();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $email = $user->getEmail();
        $password = $user->getPassword();

        $prepared = $this->database->prepare('UPDATE users SET first_name = :firstName, last_name = :lastName, email = :email, password = :password WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->bindParam(':firstName', $firstName);
        $prepared->bindParam(':lastName', $lastName);
        $prepared->bindParam(':email', $email);
        $prepared->bindParam(':password', $password);
        $prepared->execute();

        return true;
    }

    public function delete(object $user): bool
    {
        $id = $user->getId();

        $prepared = $this->database->prepare('DELETE FROM users WHERE id = :id');
        $prepared->bindParam(':id', $id);
        $prepared->execute();

        return true;
    }
}
