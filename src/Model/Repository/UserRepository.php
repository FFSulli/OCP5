<?php
//
//declare(strict_types=1);
//
//namespace App\Model\Repository;
//
//use App\Service\Database\MySQLDB;
//use App\Model\Entity\User;
//use App\Model\Repository\Interfaces\EntityRepositoryInterface;
//
//final class UserRepository implements EntityRepositoryInterface
//{
//    private MySQLDB $database;
//
//
//    public function __construct(MySQLDB $database)
//    {
//        $this->database = $database;
//    }
//
//    public function find(int $id): ?User
//    {
//        return null;
//    }
//
//    public function findOneBy(array $criteria, array $orderBy = null): ?User
//    {
//        $email = $criteria["email"];
//
//        $prepared = $this->database->prepare('select * from user where email=:email');
//        $data = $this->database->execute($prepared, [
//            ":email" => $email
//        ]);
//
//        // réfléchir à l'hydratation des entités;
//        return $data == null ? null : new user((int)$data['id'], $data['pseudo'], $data['email'], $data['password']);
//    }
//
//    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
//    {
//        return null;
//    }
//
//    public function findAll(): ?array
//    {
//        return null;
//    }
//
//    public function create(object $user): bool
//    {
//        return false;
//    }
//
//    public function update(object $user): bool
//    {
//        return false;
//    }
//
//    public function delete(object $user): bool
//    {
//        return false;
//    }
//}
