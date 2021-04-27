<?php

//
//declare(strict_types=1);
//
//namespace App\Model\Repository;
//
//use App\Service\Database\MySQLDB;
//use App\Model\Entity\Comment;
//use App\Model\Entity\Interfaces\EntityObjectInterface;
//use App\Model\Repository\Interfaces\EntityRepositoryInterface;
//
//final class CommentRepository implements EntityRepositoryInterface
//{
//    private MySQLDB $database;
//
//    public function __construct(MySQLDB $database)
//    {
//        $this->database = $database;
//    }
//
//    public function find(int $id): ?Comment
//    {
//        $prepared = $this->database->prepare('select * from comment where id=:id');
//        $data = $this->database->execute($prepared, [
//            ":id" => $id
//        ]);
//
//        return new Comment($data['id'], $data['title'], $data['text'], $data['idPost']);
//    }
//
//    public function findOneBy(array $criteria, array $orderBy = null): ?Comment
//    {
//        return null;
//    }
//
//    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
//    {
//        $idPost = $criteria["idPost"];
//
//        $prepared = $this->database->prepare('select * from comment where idPost=:idPost');
//        $data = $this->database->execute($prepared, [
//            ":idPost" => $idPost
//        ]);
//
//        if ($data == null) {
//            return null;
//        }
//
//        // réfléchir à l'hydratation des entités;
//        $comments = [];
//        foreach ($data as $comment) {
//            $comments[] = new Comment((int)$comment['id'], $comment['pseudo'], $comment['text'], (int)$comment['idPost']);
//        }
//
//        return $comments;
//    }
//
//    public function findAll(): ?array
//    {
//        return null;
//    }
//
//    public function create(object $comment): bool
//    {
//        return false ;
//    }
//
//    public function update(object $comment): bool
//    {
//        return false;
//    }
//
//    public function delete(object $comment): bool
//    {
//        return false;
//    }
//}
