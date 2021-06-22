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
        return $this->findBy($criteria, $orderBy, 1, 1)[0] ?? null;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        $criteriaFields = [];
        $orderByFields = [];
        $binds = [];

        $criteria_fields = [];
        $orderBy_fields = [];

        foreach ($criteria as $key=>$value) {
            $criteria_fields[] = sprintf("%s = '%s'", $key, $value);
        }

        foreach ($orderBy as $key=>$value) {
            $orderBy_fields[] = sprintf("%s %s", $key, $value);
        }

        $criteriaList = implode(' AND ', $criteriaFields);
        $orderByList = implode(', ', $orderByFields);

        $whereClause = 0 !== count($criteriaFields) ? sprintf('WHERE %s', $criteriaList) : '';
        $orderByClause = 0 !== count($orderByFields) ? sprintf(' ORDER BY %s', $orderByList) : '';
        $limitClause = !is_null($limit) ? ' LIMIT ' . $limit : '';
        $offsetClause = !is_null($offset) ? ' OFFSET ' . $offset : '';

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
